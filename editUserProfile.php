<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once("includes/db.php");


function checkRequiredField($value)
{
    return isset($value) && !empty($value);
}

if (isset($_SESSION['user_id'])) {
    $aid = $_SESSION['user_id'];

    $query = oci_parse($db, "select * from accounts where aid = '{$aid}'");
    oci_execute($query);
    $row = oci_fetch_assoc($query);
    if ($_POST){
        if(checkRequiredField($_POST['fname']) && checkRequiredField($_POST['lname']) && checkRequiredField($_POST['email']) && checkRequiredField($_POST['phone_number'])) {
            $result = oci_parse($db, "update accounts set fname = '{$_POST['fname']}', lname = '{$_POST['lname']}', email = '{$_POST['email']}', phone_number = '{$_POST['phone_number']}' where aid = '{$aid}'");
            oci_execute($result);
            oci_commit($db);
        }
        if(checkRequiredField($_POST['about'])) {
            $query2 = oci_parse($db, "update accounts set short_biography = '{$_POST['about']}' where aid = $aid");
            oci_execute($query2);
            oci_commit($db);
        }
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
                        ?>">
                    </div>
                    <div>
                        <label for="lastName">Last name</label>
                        <input id="lastName" name="lname" type="text" value="<?php
                        if (isset($row['LNAME'])){
                            echo $row['LNAME'];
                        }
                        ?>">
                    </div>
                    <div>
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" value="<?php
                        if (isset($row['EMAIL'])){
                            echo $row['EMAIL'];
                        }
                        ?>">
                    </div>
                    <div>
                        <label for="phoneNum">Phone number</label>
                        <input id="phoneNum" name="phone_number" type="number" value="<?php
                        if (isset($row['PHONE_NUMBER'])){
                            echo $row['PHONE_NUMBER'];
                        }
                        ?>">
                    </div>
                    <button type="submit" class="buttonStyle">SAVE</button>
                </fieldset>
            </form>

            <form method="post">
                <fieldset>
                    <legend>About</legend>
                    <div class="center">
                        <textarea class="center" name="about" id="about" placeholder="Please tell us a little about yourself"><?php
                            if(isset($row['SHORT_BIOGRAPHY'])) { echo "{$row['SHORT_BIOGRAPHY']}"; }
                            ?></textarea>
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