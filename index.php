<?php
include 'Connect.php';
include 'commonFunctions.php';

checkLogin();



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.css" />

    <link rel="stylesheet" href="css/style.css">
    <script src="js/jquery-3.5.1.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/moment.min.js"></script>
    <script src="js/dataTables.dateTime.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

    <title>WIP - ANP Solicitors <?php echo "- " . date('d-m-Y'); ?></title>


</head>

<body class="d-flex flex-column min-vh-100">

    <?php writeNavBar(); ?>
    <?php sessionCheck(); ?>


    <div class="container-fluid">
        <div class="mt-3">
            <form>
                <div class="row">
                    <div class="col-md-2">
                        <select id="searchBy" name="searchBy" class="form-select">
                            <option value="ClientName">Client Name</option>
                            <option value="FileNumber" selected>Alpha Code</option>
                            <option>FeeEarner</option>
                            <option value="PersonForNextTask">Person for Next Task</option>
                            <option value="ToBeClosed">Files to be Closed (Please leave search bar blank)</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <input id="valToSearch" name="valToSearch" class="form-control" placeholder="Enter Value to Search">

                    </div>
                    <div class="col-md-2">
                        <label>Show Archived Files</label>
                        <input type="checkbox" name="showArchived">
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>

                </div>
            </form>

        </div>


        <?php




        function display_data($data, $nextWork)
        {

            $output = '<table id="tblUser" cellpadding="5" cellspacing="0" border="0" class="table table-striped align-middle" >';
            $output .= '<thead>';
            $output .= '<tr>';
            // $output .= '<th></th>';
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


            while ($row = $data->fetch_assoc()) {
                $output .= '<tr>';
                // $output .= '<td class="dt-control"></td>';
                $output .= '<td data-priority=1 > <a target="_blank" href="./Home.php?filenumber=' . $row['FileNumber'] . '">' . $row['FileNumber'] . '</a></td>';
                $output .= '<td data-priority=2 >' . $row['FeeEarner'] . '</td>';
                $output .= '<td data-priority=3 >' . $row['Matter Description'] . '</td>';
                $output .= '<td data-priority=4 >' . $row['Client 1 Name'] . '</td>';
                $output .= '<td data-priority=5 >' . $row['Client 2 Name'] . '</td>';
                if ($nextWork == true) {
                    $output .= '<td data-priority=6 >' . $row['Person For Next Task'] . '</td>';
                    $output .= '<td data-priority=7 >' . $row['Tasks Required'] . '</td>';
                    $output .= '<td data-priority=8 >' . $row['Date Of Next Work'] . '</td>';
                } else {
                    $output .= '<td data-priority=6 >' . $row['Last Work Done By'] . '</td>';
                    $output .= '<td data-priority=7 >' . $row['Desc Of Last Work'] . '</td>';
                    $output .= '<td data-priority=8 >' . $row['Date Of Last Work'] . '</td>';
                }
                $output .= '<td data-priority=9 >' . $row['comments'] . '</td>';
                $output .= '</tr>';
            }

            $output .= '</tbody>';

            $output .= '</table>';
            return $output;
        }

        if (isset($_REQUEST['valToSearch'])) {
            $searchBy = $_REQUEST['searchBy'];
            $valToSearch = $_REQUEST['valToSearch'];
            $fileStatus = "wip.FileStatus = 'Open'";
            if (isset($_REQUEST['showArchived'])) {
                $fileStatus = "wip.FileStatus != 'To Be Closed'";
            }


            $filterFactor = "";
            if ($searchBy == 'ClientName') {
                $filterFactor = " WHERE (" . $fileStatus . ") AND (c1.ClientName LIKE '%" . $valToSearch . "%' OR c2.Clientname LIKE '%" . $valToSearch . "%')";
            } else if ($searchBy == 'ToBeClosed') {
                $filterFactor = " WHERE wip.FileStatus = 'To Be Closed'";
            } else {
                $filterFactor = " WHERE (" . $fileStatus . ") AND (wip." . $searchBy . " LIKE '%" . $valToSearch . "%')";
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
                    LEFT JOIN client_contact_details c2 ON wip.Client2Contact_ID <> 0 AND wip.Client2Contact_ID = c2.ID 
                    WHERE wip.FileStatus = 'Open' AND wip.PersonForNextTask = '" . $valToSearch .
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
            $showArchived = (isset($_REQUEST['showArchived'])) ? '&showArchived=on' : '';
            echo "<a class='btn btn-primary float-end' href='serverCalls/PrintReport.php?valToSearch=" . $_REQUEST['valToSearch'] . "&searchBy=" . $_REQUEST['searchBy'] . $showArchived . "'>Download Table</a>";

            if ($searchBy == 'PersonForNextTask') {
                echo display_data($data, true);
            } else {
                echo display_data($data, false);
            }
        }






        ?>
    </div>



    <script>
        $(document).ready(function() {


            var table = $('#tblUser').DataTable({
                searching: false,
                paging: true,
                responsive: {
                    details: {
                        type: 'column',
                        target: 'tr'
                    }
                },

            });
        });
    </script>

    <?php
    writeFooter();
    ?>
    <?php



    ?>
</body>

</html>