<?php

session_start();

include('includes/db.php');

if ($_POST) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = oci_parse($db, "select * from accounts where email = '{$email}' and password = '{$password}'");
    oci_execute($query);
    var_dump("select * from accounts where email = '{$email}' and password = '{$password}'");
    var_dump(oci_error());

    $row = oci_fetch_assoc($query);

    if ($row) {
        $_SESSION['user_id'] = $row['AID'];
        $_SESSION['user_first_name'] = $row['FNAME'];
        $_SESSION['user_last_name'] = $row['LNAME'];
        header('Location: pro-profile.php');
        exit();
    } else {
        die('Incorrect username and/or password');
    }

}