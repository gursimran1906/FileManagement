<?php
  include 'Connect.php';
  include 'commonFunctions.php';
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
<!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
      
      <!-- JavaScript Bundle with Popper -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
      
    <title>Reports</title>
</head>
<body class="d-flex flex-column min-vh-100">
<nav class="navbar bg-light">
            
            <div class="d-flex bd-highlight mb-3">
                    <div class="mr-auto p-2 bd-highlight"><a class="navbar-brand p-2 flex-grow-1 bd-highlight" href="index.php">
                                    <img src="images/Logo.png" alt="ANP LOGO" width="100" height="75" class="d-inline-block align-text-top">
                                    ANP Solicitors <?php  echo "- ".date('d-m-Y'); ?>
                                    </a></div>
                    <div class="p-2 bd-highlight">            
                        <a class="btn btn-primary nav-item p-2 bd-highlight" href="Reports.php" role="button" id="ReportsButton" target="_blank">Reports</a>
                        </div>
                    <div class="p-2 bd-highlight">            
                        <a class="btn btn-primary nav-item p-2 bd-highlight" href="OpenFilePage.php" role="button" id="OpenNewFileButton" target="_blank">Open New File</a>
                    </div>
                    <div class="p-2 bd-highlight">            
                        <a class="btn btn-primary nav-item p-2 bd-highlight" href="PendingWork.php" role="button"  target="_blank">Pending Next Work</a>
                    </div>
            </div>
        </nav>
<div class="container-fluid">


<?php                 
         
function display_data($data) {
    
            $output = '<table id="tblUser" class="table table-striped align-middle" >';
            foreach($data as $key => $var) {
                if($key === 0)
                {
                    $output .= '<thead><tr>';
                }
                foreach($var as $k => $v) {
                    if ($key === 0) {
                        $output .= '<th>' . $k . '</th>';
                        
                    }}
                    break;
            }
            $output .= '</thead> <tbody>';
            foreach($data as $key => $var) {
                   
                    if ($key != 0){
                        $output .= '<tr>';
                    foreach($var as $k => $v) {
                        
                            if($v === '1')
                            {
                            $output .= '<td>' . 'Yes' . '</td>'; 
                            }
                            elseif($v === '0')
                            {
                                $output .= '<td>' . 'No' . '</td>';
                            }
                            else 
                            {
                                $output .= '<td>' . $v . '</td>';
                            }

                    }
                    $output .= '</tr>';
                }
            }
            $output .= '</tbody>';
               
            foreach($data as $key => $var) {
               
                    $output .= '<tfoot><tr>';
                 foreach($var as $k => $v) {
                    if ($key === 0) {
                        $output .= '<th>' . $k . '</th>';
                        
                    }}
                    $output .= '</tr>';
                    break;

            }
        
            $output .= '</tfoot></table>';
            echo $output;
            }

                $sql = "SELECT *, 
                DATE_FORMAT(DateOfNextWork,'%d/%m/%Y')  AS DateOfNextWork, 
                DATE_FORMAT(DateOfAMLReport,'%d/%m/%Y')  AS DateOfAMLReport,
                DATE_FORMAT(DateOfLastWork,'%d/%m/%Y') AS DateOfLastWork,
                DATE_FORMAT(NextKeyDate,'%d/%m/%Y')  AS NextKeyDate FROM wip ";
                $data = $con->query($sql);
                
                 display_data($data);
                // while ($row = mysqli_fetch_array($data)) {
                //     echo $row['FileNumber'].' \n';
                // }
                

            
                
                ?>
</div>
   


<script>
     
    $(document).ready(function () {
    // Setup - add a text input to each footer cell
    $('#tblUser tfoot th').each(function () {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });
    

    var table = $('#tblUser').DataTable({
    
        initComplete: function () {
            // Apply the search
            this.api()
                .columns()
                .every(function () {
                    var that = this;
 
                    $('input', this.footer()).on('keyup change clear', function () {
                        if (that.search() !== this.value) {
                            that.search(this.value).draw();
                            
                        }
                    });
                });
        }
    }); 
});
</script>
<?php 
    writeFooter();
?>
</body>
</html>