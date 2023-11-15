<?php


include './Connect.php';
$errorMsg = '';

$url = isset($_REQUEST['URL']) ? $_REQUEST['URL'] : null;
// echo "URL: " . $url;

echo (hash('sha512', 'Sharronfruit@ANP290!'));
if (isset($_REQUEST['userInitials']) && isset($_REQUEST['password'])) {
    $userInitials = $_REQUEST['userInitials'];
    $userEnteredPass = (hash('sha512', $_REQUEST['password']));

    $sql = "SELECT ID, password FROM users WHERE userInitials='$userInitials'";
    $data = mysqli_query($con, $sql);
    $data = $data->fetch_assoc();
    if ($data == null) {

        $errorMsg = 'Check User Initials and Password';
    } else {

        $password = $data['password'];

        if ($password == $userEnteredPass) {
            session_start();
            $_SESSION['userInitials'] = $userInitials;

            $_SESSION['LastActivity'] = time();
            if ($url === null) {
                echo 'In Null';
                //Header("Location: ./index.php");
            } else {
                Header("Location: $url");
            }
        } else {
            $errorMsg = 'Check Password';
        }
    }


    // print_r($password);
}

include './commonFunctions.php';

echo ' <!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 50vh;
            width: 50%;
            margin-left: 25%;
            margin-top: 12.5%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            border-radius: 20px;
            background-color: white;
            
        }
        body {
            background-color: #009CAB; /* Set your desired background color */
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="container">
            <div >
                <div class="mt-2 d-flex justify-content-center">
                    <h1>Login</h1>
                </div>
                <div class="mt-2 d-flex justify-content-center text-danger">
                ';
if ($errorMsg != '') {
    echo '* ' . $errorMsg;
}

echo '
                </div>
                <div class="m-3 d-flex justify-content-center">
                    <form method="POST" action="Login.php">
                        <div class="form-group">
                            <label for="userInitials">User Initials:</label>
                            <input type="text" class="form-control" name="userInitials" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary" name="URL" value=' . $url . '>Login</button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

';
