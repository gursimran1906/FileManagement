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
      
    <title>Pending Next Work</title>
</head>
<body class="d-flex flex-column min-vh-100">
<?php writeNavBar(); ?>
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
                        
                            if($v == 1)
                            {
                            $output .= '<td>' . 'Yes' . '</td>';  
                            }
                            elseif($v == 0)
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
                $todayDate = date("Y-m-d");
                $FileLocationArchived = 'Archived';

                $sql = "SELECT *,
                DATE_FORMAT(DateOfNextWork,'%d/%m/%Y')  AS DateOfNextWork, 
                DATE_FORMAT(DateOfAMLReport,'%d/%m/%Y')  AS DateOfAMLReport,
                DATE_FORMAT(DateOfLastWork,'%d/%m/%Y') AS DateOfLastWork,
                DATE_FORMAT(NextKeyDate,'%d/%m/%Y')  AS NextKeyDate
                FROM wip WHERE DateOfNextWork >= '$todayDate'";
                $data = $con->query($sql);
                

                display_data($data);
                
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
<div class=" mt-auto p-3 mb-2 bg-light text-dark">
<footer class="mt-auto footer ">
<div class="footer-copyright">
<div class="container">
<div class="row">
<div class="col-md-12 text-center">
<p>Copyright ANP Solicitors Limited © 2022. All rights reserved.</p>
</div>
</div>
</div>
</div>

</footer>
</div>
</body>
</html>