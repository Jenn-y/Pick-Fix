<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('Includes/head.php'); ?>
    <link href="CSS/index-header.css" rel="stylesheet">
    <link href="CSS/index-style.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/footer.css">
    <title>Pick&Fix</title>
</head>
<body>
<div class="page-container">
    <?php include('Includes/header-signed-in.php'); ?>

    <div class="welcome">
        <div class="color-overlay"></div>
        <h1>The easy, reliable way to take care of your home.</h1>
        <a href="findProfessionals.php">Get Started</a>
    </div>

    <main>
        <div class="text center">
            <h2>Pick & Fix Tasks</h2>
            <p>Instantly book highly rated pros for cleaning and handyman tasks at a fixed price. <span>See All <i class="fa fa-angle-right" aria-hidden="true"></i></span></p>
        </div>

        <section class="popular-services flex-container center">
            <a href="#">
                <img src="Images/repairman.jpg" alt="Repairman">
                <p>General repairman <i class="fa fa-angle-right" aria-hidden="true"></i></p>
            </a>

            <a href="#">
                <img src="Images/electrics-resized.jpg" alt="Electrics">
                <p>Electrician <i class="fa fa-angle-right" aria-hidden="true"></i></p>
            </a>

            <a href="#">
                <img src="Images/faucet-resized.jpg" alt="Faucet">
                <p>Faucets <i class="fa fa-angle-right" aria-hidden="true"></i></p>
            </a>

            <a href="#">
                <img src="Images/furniture-resized.jpg" alt="Furniture">
                <p>Furniture assembly <i class="fa fa-angle-right" aria-hidden="true"></i></p>
            </a>
            <a href="#">
                <img src="Images/repairman.jpg" alt="Repairman">
                <p>General repairman <i class="fa fa-angle-right" aria-hidden="true"></i></p>
            </a>

            <a href="#">
                <img src="Images/electrics-resized.jpg" alt="Electrics">
                <p>Electrician <i class="fa fa-angle-right" aria-hidden="true"></i></p>
            </a>

            <a href="#">
                <img src="Images/faucet-resized.jpg" alt="Faucet">
                <p>Faucets <i class="fa fa-angle-right" aria-hidden="true"></i></p>
            </a>

            <a href="#">
                <img src="Images/furniture-resized.jpg" alt="Furniture">
                <p>Furniture assembly <i class="fa fa-angle-right" aria-hidden="true"></i></p>
            </a>
        </section>

        <section class="vetted-professionals flex-container center">
            <i class="fa fa-lock" aria-hidden="true"></i>
            <h2>Vetted, Background-Checked Professionals</h2>
            <p>Pick & Fix tasks booked and paid for directly through the Pick & Fix platform are performed by experienced, background-checked professionals</p>
            <p>who are highly rated by customers like you. <span> Learn more.</span></p>
        </section>
    </main>

    <?php include('Includes/footer.php'); ?>
</div>
</body>
</html>