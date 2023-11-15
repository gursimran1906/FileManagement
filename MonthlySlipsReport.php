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
    <div class="m-auto d-flex justify-content-center">
            
            <form class="form" action="serverCalls/DownloadMonthlySlips.php" method="post" id="searchForm">
                <label for="from">From</label>
                <input type="date" name="fromDate" title="from" required>
                <label for="to">to</label>
                <input type="date" name="toDate" title="to" required>
                <button type="submit" class="btn btn-primary">Search</button>
                
            </form>

        </div> 
    </div>  

    <?php
    writeFooter();
    ?>
    <?php



    ?>
</body>

</html>