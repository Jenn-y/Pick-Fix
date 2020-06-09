<!doctype html>
<html lang="en">
<head>
    <?php include('head.php'); ?>
    <link rel="stylesheet" href="../css/login.css">
    <style>
        main {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
    </style>
    <script>
        function redirect() {
            window.setTimeout(function(){
                // Move to a new location or you can do something else
                window.location.href = "../login.php";
            }, 5000);
        }
    </script>
</head>
<body onload="redirect()">
<main>
    <h1>You need to be logged in to do that</h1>
    <h2>Redirecting you to login in 5 seconds . . .</h2>
</main>
</body>
</html>