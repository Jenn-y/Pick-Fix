<!doctype html>
<html lang="en">
<head>
    <?php include('Includes/head.php'); ?>
    <link rel="stylesheet" href="CSS/login.css">
    <title>Log in</title>
</head>
<body>
    <main class="flex-container">
        <div>
            <form action="action_page.php" method="post">
                <div class="login flex-container">
                    <p>User Login</p>
                    <input type="text" placeholder="Username" name="uname" required>
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
