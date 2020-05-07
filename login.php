<!doctype html>

<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'pick_fix');
$db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

session_start();
if ($_POST) {


    $username = mysqli_real_escape_string($db, $_POST['uname']);
    $password = mysqli_real_escape_string($db, $_POST['psw']);

    $query = mysqli_query($db, "select * from PROFESSIONALS where EMAIL = '{$username}' and PASSWORD = '{$password}'");

    $row = mysqli_fetch_assoc($query);

    if ($row) {
        exit();
    } else {
        $_SESSION['msg'] = 'Incorrect username and/or password';
        echo "INCORREEEEEEEECT!";
    }
}

?>
<html lang="en">
<head>
    <?php include('Includes/head.php'); ?>
    <link rel="stylesheet" href="CSS/login.css">
    <title>Log in</title>
</head>
<body>
<main class="flex-container">
    <div>
        <form method="POST">
            <div class="login flex-container">
                <p>User Login</p>
                <input type="email" placeholder="Email" name="uname" required>
                <input type="password" placeholder="Password" name="psw" required>
                <button type="submit">Login</button>
                <div>
                    <span>Forgot</span>
                    <a href="#">Username / Password?</a>
                </div>
            </div>
        </form>
    </div>
    <div class="new-account flex-container">
        <a href="#">Create your Account <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i></a>
        <a href="userRegistration.php">Join as a pro <i class="fa fa-star" aria-hidden="true"></i></a>
    </div>
</main>
</body>
</html>
