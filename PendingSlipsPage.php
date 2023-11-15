<?php
include 'commonFunctions.php';
checkLogin();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <title>Pending Slips</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <meta name="author" content="Gursimran Singh">
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="vh-100">
        <?php writeNavBar(); ?>
        <?php sessionCheck(); ?>


        <div class="h-100">
            <div class="m-auto d-flex justify-content-center">
                <form class="form" action="serverCalls/DownloadPendingSlip.php" method="post" id="searchForm">
                    <label for="dateOfLastSlipsDone">Date of Cashier's last visit</label><br>
                    <input type="date" name="dateOfLastSlipsDone" title="Enter the Date for the last slips done" required>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>


        </div>
    </div>
    <?php
    writeFooter();
    ?>



</body>

</html>