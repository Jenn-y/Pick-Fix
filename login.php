<?php
session_start();

include('includes/form-functions.php');
if ($_POST) {
    include('includes/db.php');

    $email = $_POST['username'];
    $password = $_POST['password'];

    $query = oci_parse($db, "select * from accounts where email = '{$email}' and password = '{$password}'");

    $row = oci_fetch_assoc($query);
    oci_execute($row);
    oci_execute($query);

    if ($row) {
        $_SESSION['user_id'] = $row['AID'];
        $_SESSION['fname'] = $row['FNAME'];
        $_SESSION['role'] = $row['ROLE'];

        header('Location: pro-profile.php');
        exit();
    } else {
        $_SESSION['msg'] = 'Incorrect username and/or password';
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
        <form method="POST" action="pro-profile.php">
            <div class="login flex-container">
                <p>User Login</p>
                <?php create_input("email", "email", "Email"); ?>
                <input type="password" placeholder="Password" name="password">
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
