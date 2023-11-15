<?php
include '../Connect.php';

require_once '../vendor/autoload.php';

use Dompdf\Dompdf;


if (isset($_REQUEST['valToSearch'])) {
    $searchBy = $_REQUEST['searchBy'];
    $valToSearch = $_REQUEST['valToSearch'];
    $fileStatus = "FileStatus = 'Open'";
    if (isset($_REQUEST['showArchived'])) {
        $fileStatus = "wip.FileStatus = 'Open' OR wip.FileStatus = 'Archived'";
    }

    $filterFactor = "";
    if ($searchBy == 'ClientName') {
        $filterFactor = " WHERE " . $fileStatus . " AND c1.ClientName LIKE '%" . $valToSearch . "%' OR c2.Clientname LIKE '%" . $valToSearch . "%'";
    } else if ($searchBy == 'ToBeClosed') {
        $filterFactor = " WHERE wip.FileStatus = 'To Be Closed'";
    } else {
        $filterFactor = " WHERE " . $fileStatus . " AND wip." . $searchBy . " LIKE '%" . $valToSearch . "%'";
    }

    if ($searchBy == 'PersonForNextTask') {
        $sql = "SELECT 
            wip.FileNumber, 
            wip.FeeEarner,
            wip.MatterDescription AS 'Matter Description',
            c1.ClientName AS 'Client 1 Name',
            CASE
                WHEN wip.Client2Contact_ID > 0 THEN c2.ClientName
                ELSE NULL
            END AS 'Client 2 Name',
            wip.PersonForNextTask AS 'Person For Next Task',
            wip.TasksRequired AS 'Tasks Required',
            DATE_FORMAT(wip.DateOfNextWork,'%d/%m/%Y') AS 'Date Of Next Work',
            wip.comments
            FROM wip
            JOIN client_contact_details c1 ON wip.Client1Contact_ID = c1.ID
            LEFT JOIN client_contact_details c2 ON wip.Client2Contact_ID <> 0 AND wip.Client2Contact_ID = c2.ID WHERE wip.FileStatus = 'Open' AND wip.PersonForNextTask = '" . $valToSearch .
            "' ORDER BY wip.FileNumber";
    } else {
        $sql = "SELECT 
            wip.FileNumber, 
            wip.FeeEarner,
            wip.MatterDescription AS 'Matter Description',
            c1.ClientName AS 'Client 1 Name',
            CASE
                WHEN wip.Client2Contact_ID > 0 THEN c2.ClientName
                ELSE NULL
            END AS 'Client 2 Name',
            wip.PersonLastWorked AS 'Last Work Done By',
            wip.DescOfLastWork AS 'Desc Of Last Work',
            DATE_FORMAT(wip.DateOfLastWork,'%d/%m/%Y') AS 'Date Of Last Work',
            wip.comments
            FROM wip
            JOIN client_contact_details c1 ON wip.Client1Contact_ID = c1.ID
            LEFT JOIN client_contact_details c2 ON wip.Client2Contact_ID <> 0 AND wip.Client2Contact_ID = c2.ID" . $filterFactor .
            " ORDER BY wip.FileNumber";
    }

    $data = $con->query($sql);
    $document = new Dompdf();
    $document->setPaper('A4', 'landscape');
    if ($searchBy == 'PersonForNextTask') {
        $document->loadHtml(display_data($data, true));
    } else {
        $document->loadHtml(display_data($data, false));
    }

    $document->render();
    $document->stream("Print Report_" . $searchBy . "_" . $valToSearch . " _" . date('d-m-Y'));

    header("Location: ../Report.php");
} else {
    echo "Enter Value";
}

function display_data($data, $nextWork)
{

    $output = '<table id="tblUser" cellpadding="5" cellspacing="0" border="0" class="table table-striped align-middle" >';
    $output .= '<thead>';
    $output .= '<tr>';
    // $output .= '<th></th>';
    $output .= '<th>No.</th>';
    $output .= '<th>File Number</th>';
    $output .= '<th>FeeEarner</th>';
    $output .= '<th>Matter Description</th>';
    $output .= '<th>Client 1 Name</th>';
    $output .= '<th>Client 2 Name</th>';
    if ($nextWork == true) {
        $output .= '<th>Person - Next Task(s)</th>';
        $output .= '<th>Task(s) Required</th>';
        $output .= '<th>Date Of Next Work</th>';
    } else {
        $output .= '<th>Last Work Done By</th>';
        $output .= '<th>Desc Of Last Work</th>';
        $output .= '<th>Date Of Last Work</th>';
    }
    $output .= '<th>Comments</th>';
    $output .= '</tr>';
    $output .= '</thead>';
    $output .= '<tbody>';
    $color = true;
    $index = 1;
    while ($row = $data->fetch_assoc()) {
        if ($color) {
            $output .= '<tr style="background-color: #f2f2f2;">';
            $color = false;
        } else {
            $output .= '<tr>';
            $color = true;
        }
        // $output .= '<td class="dt-control"></td>';
        $output .= '<td style="border:solid;" data-priority=1 >' . $index . '</td>';
        $output .= '<td style="border:solid;" data-priority=2 >' . $row['FileNumber'] . '</a></td>';
        $output .= '<td style="border:solid;" data-priority=3 >' . $row['FeeEarner'] . '</td>';
        $output .= '<td style="border:solid;" data-priority=4 >' . $row['Matter Description'] . '</td>';
        $output .= '<td style="border:solid;" data-priority=5 >' . $row['Client 1 Name'] . '</td>';
        $output .= '<td style="border:solid;" data-priority=6 >' . $row['Client 2 Name'] . '</td>';
        if ($nextWork == true) {
            $output .= '<td style="border:solid;" data-priority=6 >' . $row['Person For Next Task'] . '</td>';
            $output .= '<td style="border:solid;" data-priority=7 >' . $row['Tasks Required'] . '</td>';
            $output .= '<td style="border:solid;" data-priority=8 >' . $row['Date Of Next Work'] . '</td>';
        } else {
            $output .= '<td style="border:solid;" data-priority=6 >' . $row['Last Work Done By'] . '</td>';
            $output .= '<td style="border:solid;" data-priority=7 >' . $row['Desc Of Last Work'] . '</td>';
            $output .= '<td style="border:solid;" data-priority=8 >' . $row['Date Of Last Work'] . '</td>';
        }
        $output .= '<td style="border:solid;" data-priority=10 >' . $row['comments'] . '</td>';
        $output .= '</tr>';
        $index = $index + 1;
    }

    $output .= '</tbody>';

    $html = mb_convert_encoding($output, 'HTML-ENTITIES', 'UTF-8');

    return $html;
}
