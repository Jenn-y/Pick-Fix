<?php
include("includes/form-functions.php");
include("includes/db.php");

$query3 = oci_parse($db, 'SELECT * FROM cities WHERE date_deleted IS NULL ORDER BY cname');
oci_execute($query3);

function checkRequiredField($value)
{
    return isset($value) && !empty($value);
}
if($_POST) {
    if (checkRequiredField($_POST['first_name']) && checkRequiredField($_POST['last_name']) && checkRequiredField($_POST['email'])
        && checkRequiredField($_POST['password'])) {

        $sql = "INSERT INTO accounts (fname, lname, email, password, area_code, phone_number, primary_city, role)
                VALUES ('{$_POST['first_name']}', '{$_POST['last_name']}', '{$_POST['email']}', '{$_POST['password']}', {$_POST['area_code']}, {$_POST['phone']}, '{$_POST['city']}', 2)";

        $result = oci_parse($db, $sql);
        oci_execute($result);
        oci_commit($db);
        if ($result) {
            header('Location: findProfessionals.php');
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <?php include('includes/head.php'); ?>
    <link rel="stylesheet" href="css/register.css">
    <title>Register</title>
</head>
<body>
    <main class="flex-container">
        <form method="post">
            <h1>Register</h1>
            <div class="full-name flex-container">
                <label>Full name</label>
                <?php create_input("text", "first_name", "First name", true); ?>
                <?php create_input("text", "last_name", "Last name", true); ?>
            </div>
            <label for="email">Email</label>
            <?php create_input("text", "email", "Email",true); ?>
            <label for="password">Password</label>
            <?php create_input("password", "password", "Password",true); ?>

            <label for="area_code">Area code</label>
            <?php create_input("number", "area_code", "Area code",true); ?>

            <label for="phone">Phone</label>
            <?php create_input("tel", "phone", "Phone number",true); ?>

            <label for="city">City</label>
            <select name="city" id="city">
                <?php while($row3 = oci_fetch_assoc($query3)): ?>
                    <option value="<?= $row3['CNAME']; ?>"><?= $row3['CNAME']; ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Register</button>
            <div class="already-member flex-container">
                <div>
                    <span>Already have an account?</span>
                    <a href="login.php">Sign in!</a>
                </div>
            </div>
        </form>
    </main>
</body>
</html>
