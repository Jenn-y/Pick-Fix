<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once("includes/db.php");


function checkRequiredField($value)
{
    return isset($value) && !empty($value);
}
$incorrect_password = false;
$incorrect_new_password = false;
$success = false;
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
        if(checkRequiredField($_POST['current_password']) && checkRequiredField($_POST['new_password']) && checkRequiredField($_POST['new_password_repeat'])) {
            $query2 = oci_parse($db, "select * from accounts where password = {$_POST['current_password']} AND aid={$_SESSION['user_id']}");
            oci_execute($query2);

            if(oci_fetch($query2)) {
                if($_POST['new_password'] == $_POST['new_password_repeat']) {
                    $query2 = oci_parse($db, "update accounts set password = '{$_POST['new_password']}' where aid = {$_SESSION['user_id']}");
                    oci_execute($query2);
                    oci_commit($db);
                    $success = true;
                }
                else $incorrect_new_password = true;
            }
            else $incorrect_password = true;
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <?php include('includes/head.php'); ?>
    <link rel="stylesheet" href="css/editProfile.css">
    <link href="css/header.css" rel="stylesheet">
    <link rel="stylesheet" href="css/footer.css">
    <title>Edit Profile</title>
</head>
<body>

<div id="page-container">
    <?php include('includes/profile-header.php'); ?>

    <main>
        <div class="main center">
            <?php if($incorrect_password): ?>
                <div class="flex-container center">
                    <p class="center">Incorrect password!</p>
                </div>
            <?php endif; ?>
            <?php if($incorrect_new_password): ?>
                <div class="flex-container center">
                    <p class="center">Repeated password is incorrect!</p>
                </div>
            <?php endif; ?>
            <?php if($success): ?>
                <div class="flex-container center">
                    <p class="center">Success!</p>
                </div>
            <?php endif; ?>
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

            <form method="post">
                <fieldset>
                    <legend>Password</legend>
                    <div>
                        <label for="currentPass">Current Password</label>
                        <input name="current_password" id="currentPass" placeholder="Enter your current password" type="password">
                    </div>
                    <div>
                        <label for="newPass">New password</label>
                        <input name="new_password" id="newPass" placeholder="Enter your new password" type="password">
                    </div>
                    <div>
                        <label for="repeatPass">Repeat New Password</label>
                        <input name="new_password_repeat" id="repeatPass" placeholder="Repeat your new password" type="password">
                    </div>
                    <button type="submit" class="buttonStyle">SAVE</button>
                </fieldset>
            </form>

            <?php if($row['ROLE'] == 1): ?>
                <fieldset>
                    <legend>Credit card</legend>
                    <button class="buttonStyle">Update Credit Card Information</button>
                </fieldset>

                <fieldset>
                    <legend>Category</legend>
                    <div>
                        <label for="dropdown1" id="dropdown2-label">Add category</label>
                        <select id="dropdown2">
                            <option value="select" disabled>Select one of the categories</option>
                            <?php while($row2 = oci_fetch_assoc($query2)): ?>
                                <option value="<?= $row2['CATEGORY']; ?>"><?= $row2['CATEGORY']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div>
                        <label for="dropdown1" id="dropdown2-label">Delete category</label>
                        <select id="dropdown2">
                            <option value="select" disabled>Select one of the categories</option>
                            <option value="fax1">Furniture repairment</option>
                            <option value="fax2">Plumbing</option>
                            <option value="fax3">Electricity</option>
                            <option value="fax4">Toilets</option>
                            <option value="fax5">Moving help</option>
                        </select>
                    </div>
                    <button type="submit" class="buttonStyle">SAVE</button>
                </fieldset>

                <fieldset>
                    <legend>City</legend>
                    <div>
                        <label for="dropdown1" id="dropdown1-label">Add city</label>
                        <select id="dropdown1">
                            <option value="select" disabled>Select one of the cities</option>
                            <option value="fax1">Tuzla</option>
                            <option value="fax2">Zivinice</option>
                            <option value="fax3">Bihac</option>
                            <option value="fax4">Sarajevo</option>
                            <option value="fax5">Mostar</option>
                        </select>
                    </div>
                    <div>
                        <label for="dropdown1" id="dropdown1-label">Delete city</label>
                        <select id="dropdown1">
                            <option value="select" disabled>Select one of the cities</option>
                            <option value="fax1">Tuzla</option>
                            <option value="fax2">Zivinice</option>
                            <option value="fax3">Bihac</option>
                            <option value="fax4">Sarajevo</option>
                            <option value="fax5">Mostar</option>
                        </select>
                    </div>
                    <button type="submit" class="buttonStyle">SAVE</button>
                </fieldset>
            <?php endif; ?>
        </div>
    </main>
    <?php include('includes/footer.php'); ?>
</div>
</body>
</html>