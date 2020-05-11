<?php

function checkRequiredField($value)
{
    return isset($value) && !empty($value);
}
if($_POST) {
    if (checkRequiredField($_POST['first_name']) && checkRequiredField($_POST['last_name']) && checkRequiredField($_POST['email'])
        && checkRequiredField($_POST['password'])) {

        $conn = mysqli_connect('localhost', 'root', '', 'pick_fix');

        $sql = "INSERT INTO users (fname, lname, email, password, area_code, phone_number, city)
            VALUES ('{$_POST['first_name']}', '{$_POST['last_name']}', '{$_POST['email']}', '{$_POST['password']}', {$_POST['area_code']}, {$_POST['phone']}, '{$_POST['city']}')";

        $result = mysqli_query($conn, $sql);
        if ($result) {
            exit("SUCCESSFULLY REGISTERED !!!");
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
                <input type="text" name="first_name" placeholder="First name" value="<?php ?>" required>
                <input type="text" name="last_name" placeholder="Last name" value="<?= isset($_POST['last_name']) ?? $_GET['last_name'] ?>" required>
            </div>
            <label for="email">Email</label>
            <input type="text" name="email" placeholder="Email" value="<?= isset($_POST['email']) ?? $_GET['email'] ?>" required>
            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Password" required>

            <label for="area_code">Area code</label>
            <input type="number" name="area_code" placeholder="Area code" value="<?= isset($_POST['area_code']) ?? $_GET['area_code'] ?>">

            <label for="phone">Phone</label>
            <input type="tel" name="phone" placeholder="Phone number" value="<?= isset($_POST['phone']) ?? $_GET['phone'] ?>">
            <label for="city">City</label>
            <select name="city" id="city">
                <option value="tuzla">Tuzla</option>
                <option value="sarajevo">Sarajevo</option>
                <option value="bihac">Bihac</option>
                <option value="travnik">Travnik</option>
                <option value="mostar">Mostar</option>
                <option value="zenica">Zenica</option>
                <option value="zivinice">Zivinice</option>
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
