<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('includes/form-functions.php');
if ($_POST) {
    include_once("includes/db.php");

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = oci_parse($db, "select * from accounts where email = '{$email}' and password = '{$password}'");
    oci_execute($query);
    $row = oci_fetch_assoc($query);

    if ($row) {
        $_SESSION['user_id'] = $row['AID'];
        $_SESSION['fname'] = $row['FNAME'];
        $_SESSION['lname'] = $row['LNAME'];
        $_SESSION['role'] = $row['ROLE'];


        header('Location: findProfessionals.php');
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
        <form method="POST">
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
