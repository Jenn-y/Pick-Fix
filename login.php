<?php
include("includes/form-functions.php");

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'pick_fix');
$db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

session_start();
if ($_POST) {

    $username = mysqli_real_escape_string($db, $_POST['uname']);
    $password = mysqli_real_escape_string($db, $_POST['psw']);

    $query1 = mysqli_query($db, "select * from PROFESSIONALS where EMAIL = '{$username}' and PASSWORD = '{$password}'");
    $query2 = mysqli_query($db, "select * from USERS where EMAIL = '{$username}' and PASSWORD = '{$password}'");

    $row1 = mysqli_fetch_assoc($query1);
    $row2 = mysqli_fetch_assoc($query2);

    if ($row1 || $row2) {
        exit();
    }
    else {

    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <?php include('includes/head.php'); ?>
    <link rel="stylesheet" href="css/login.css">
    <title>Log in</title>
</head>
<body>
<main class="flex-container">
    <div>
        <form method="POST">
            <div class="login flex-container">
                <p>User Login</p>
                <?php input("email", "uname", "Email"); ?>
                <input type="password" placeholder="Password" name="psw">
                <button type="submit">Login</button>
                <div>
                    <span>Forgot</span>
                    <a href="#">Username / Password?</a>
                </div>
            </div>
        </form>
    </div>
    <div class="new-account flex-container">
        <a href="userRegistration.php">Create your Account <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i></a>
        <a href="professionalsRegistration.php">Join as a pro <i class="fa fa-star" aria-hidden="true"></i></a>
    </div>
</main>
</body>
</html>
