<?php
session_start();

include("includes/form-functions.php");
include("includes/db.php");


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
        <form method="POST" action="includes/validateLogin.php">
            <div class="login flex-container">
                <p>User Login</p>
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Please enter email">
                <label for="password">Password</label>
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
