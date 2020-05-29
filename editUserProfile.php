<?php
session_start();
include('includes/db.php');

if (isset($_SESSION['user_id'])) {
    $aid = $_SESSION['user_id'];

    $query = oci_parse($db, "select * from accounts where aid = '{$aid}'");
    oci_execute($query);
    $row = oci_fetch_assoc($query);
    if ($_POST){
        $result = oci_parse($db, "update accounts set fname = '{$_POST['fname']}', lname = '{$_POST['lname']}', email = '{$_POST['email']}', phone_number = '{$_POST['phone_number']}' where aid = '{$aid}'");
        oci_execute($result);
        oci_commit($db);
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <?php include('includes/head.php'); ?>
    <link rel="stylesheet" href="css/editProfessionalsProfile.css">
    <link href="css/header.css" rel="stylesheet">
    <link rel="stylesheet" href="css/footer.css">
    <title>Edit Profile</title>
</head>
<body>

<div id="page-container">
    <?php include('includes/profile-header.php'); ?>

    <main>
        <div class="main center">
            <div id="profilePhoto">
                <img src="images/default-user.png" alt="default-user-image">
                <button class="buttonStyle">Change Profile Photo</button>
            </div>
            <form method="post">
                <fieldset class="general">
                    <legend>General</legend>
                    <div>
                        <label for="firstName">First name</label>
                        <input id="firstName" name="fname" type="text" value="<?php
                        if (isset($row['FNAME'])){
                            echo $row['FNAME'];
                        }
                        ?>"">
                    </div>
                    <div>
                        <label for="lastName">Last name</label>
                        <input id="lastName" name="lname" type="text" value="<?php
                        if (isset($row['LNAME'])){
                            echo $row['LNAME'];
                        }
                        ?>"">
                    </div>
                    <div>
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" value="<?php
                        if (isset($row['EMAIL'])){
                            echo $row['EMAIL'];
                        }
                        ?>"">
                    </div>
                    <div>
                        <label for="phoneNum">Phone number</label>
                        <input id="phoneNum" name="phone_number" type="number" value="<?php
                        if (isset($row['PHONE_NUMBER'])){
                            echo $row['PHONE_NUMBER'];
                        }
                        ?>"">
                    </div>
                    <button type="submit" class="buttonStyle">SAVE</button>
                </fieldset>

                <fieldset>
                    <legend>Password</legend>
                    <div>
                        <label for="currentPass">Current Password</label>
                        <input id="currentPass" placeholder="Enter your current password" type="password">
                        <h5><a href="#">  Forgot your password?</a></h5>
                    </div>
                    <div>
                        <label for="newPass">New password</label>
                        <input id="newPass" placeholder="Enter your new password" type="password">
                    </div>
                    <div>
                        <label for="repeatPass">Repeat New Password</label>
                        <input id="repeatPass" placeholder="Repeat your new password" type="password">
                    </div>
                    <button type="submit" class="buttonStyle">SAVE</button>
                </fieldset>
            </form>
        </div>
    </main>
    <?php include('includes/footer.php'); ?>
</div>
</body>
</html>