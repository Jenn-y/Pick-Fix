<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once('includes/db.php');

if (isset($_POST['membership'])) {
    $membership = $_POST['membership'];
    if($membership == 0) {
        if($_SESSION['role'] == 2) {
            header("Location: become-pro.php?plan=4");
        }
        else {
            header("Location: professionalsRegistration.php?plan=4");
        }
    }
    else {
        header("Location: pricing");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/head.php'); ?>
    <link href="css/header.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style>
        h1 {
            font-size: 30px;
            padding-bottom: 1.5rem;
            padding-top: 1.5rem;
        }
        h2 {
            font-size: 26px;
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
        h3 {
            font-size: 24px;
        }

        .border-col {
            border-right: 1px solid #ebecf0;
        }
        .border-row {
            border-top: 1px solid #ebecf0;
        }
        .membership {
            background: #f8f9fa;
            border: 1px solid #ebecf0;
        }
        .fa-check {
            font-size: 24px;
        }

        input[type=radio] {
            border: 0;
            width: 100%;
            height: 2em;
        }

        @media (max-width: 767px) {
            .border-col {
                border-right: none;
            }
            .border-row {
                border-top: none;
            }
        }

    </style>
</head>
<body>

        <h1 class="text-center bg-primary text-white">One step closer to being a professional! </h1>
        <h2 class="text-center">Pick & Fix offers two membership subscriptions for the professional users.</h2>
    <div class="container membership p-5">
        <div class="row">
            <div class="col-12 col-md-4 d-flex align-items-center">
                <h3 class="p-3 mb-0">Compare our membership models</h3>
            </div>
            <div class="col-6 col-md-4 border-col d-flex align-items-center justify-content-center bg-white">
                <h3>Free</h3>
            </div>
            <div class="col-6 col-md-4 d-flex align-items-center justify-content-center  bg-white">
                <h3>Premium</h3>
            </div>
        </div>
        <div class="row border-row">
            <div class="col-12 col-md-4 d-flex align-items-center">
                <p class="p-4 mb-0">Work as a professional</p>
            </div>
            <div class="col-6 col-md-4 border-col d-flex align-items-center justify-content-center bg-white">
                <h3><i class="fa fa-check text-success" aria-hidden="true"></i></h3>
            </div>
            <div class="col-6 col-md-4 d-flex align-items-center justify-content-center bg-white">
                <h3><i class="fa fa-check text-primary" aria-hidden="true"></i></h3>
            </div>
        </div>
        <div class="row border-row">
            <div class="col-12 col-md-4 d-flex align-items-center">
                <p class="p-4 mb-0">Send, Receive & Process requests</p>
            </div>
            <div class="col-6 col-md-4 border-col d-flex align-items-center justify-content-center bg-white">
                <h3><i class="fa fa-check text-success" aria-hidden="true"></i></h3>
            </div>
            <div class="col-6 col-md-4 d-flex align-items-center justify-content-center bg-white">
                <h3><i class="fa fa-check text-primary" aria-hidden="true"></i></h3>
            </div>
        </div>
        <div class="row border-row">
            <div class="col-12 col-md-4 d-flex align-items-center">
                <p class="p-4 mb-0">Be on top of the results</p>
            </div>
            <div class="col-6 col-md-4 border-col d-flex align-items-center justify-content-center bg-white">
                <h3></h3>
            </div>
            <div class="col-6 col-md-4 d-flex align-items-center justify-content-center bg-white">
                <h3><i class="fa fa-check text-primary" aria-hidden="true"></i></h3>
            </div>
        </div>
        <div class="row border-row">
            <div class="col-12 col-md-4 d-flex align-items-center">
                <p class="p-4 mb-0">Get displayed with a golden border</p>
            </div>
            <div class="col-6 col-md-4 border-col d-flex align-items-center justify-content-center bg-white">
                <h3></h3>
            </div>
            <div class="col-6 col-md-4 d-flex align-items-center justify-content-center bg-white">
                <h3><i class="fa fa-check text-primary" aria-hidden="true"></i></h3>
            </div>
        </div>
        <div class="row border-row">
            <div class="col-12 col-md-4 d-flex align-items-center">
                <p class="p-4 mb-0">Pick & Fix Trust</p>
            </div>
            <div class="col-6 col-md-4 border-col d-flex align-items-center justify-content-center bg-white">
                <h3></h3>
            </div>
            <div class="col-6 col-md-4 d-flex align-items-center justify-content-center bg-white">
                <h3><i class="fa fa-check text-primary" aria-hidden="true"></i></h3>
            </div>
        </div>
        <form method="post">
            <div class="row">
                <div class="col-12 col-md-4 d-flex align-items-center"></div>
                <div class="col-6 col-md-4 d-flex flex-column align-items-center justify-content-center p-5">
                    <input type="radio" name="membership" id="free" value="0">
                    <label for="free">Free</label>
                </div>
                <div class="col-6 col-md-4 d-flex flex-column align-items-center justify-content-center p-5">
                    <input type="radio" name="membership" id="premium" value="1">
                    <label for="premium">Premium</label>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-4"></div>
                <div class="col-6 col-md-4"></div>
                <div class="col-6 col-md-4 d-flex flex-column justify-content-center">
                    <button type="submit" class="btn btn-primary">Continue</button>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>