<?php

function checkRequiredField($value)
{
    return isset($value) && !empty($value);
}

if ($_POST) {
    if (checkRequiredField($_POST['area_code']) && checkRequiredField($_POST['phone_number'])) {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "pick_fix";

        // Create connection
        $conn = mysqli_connect($servername, $username, $password, $database);
        $sql = "INSERT INTO ACCOUNTS (AREA_CODE, PHONE_NUMBER)
        VALUES ('{$_POST['area_code']}', '{$_POST['phone_number']}')";
        if (mysqli_query($conn, $sql)) {
            exit("SUCCESS !!!");
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <?php include('includes/head.php'); ?>
    <link href="CSS/professionalsRegistration.css" rel="stylesheet">
    <link href="CSS/footer.css" rel="stylesheet">

    <title>Become a Pro</title>
</head>
<body id="become-pro">
<div id="page-container">
    <div id="header">
        <header>
            <h1><a href="index.php">pick&fix</a></h1>
            <nav>
                <a href="pro-profile.php">Back to Profile</a>
            </nav>
        </header>
        <hr>
    </div>

    <main>

        <div class="backShape">
            <div class="textBlockForProfessionals">
                <h3>WANT TO JOIN US?</h3>
                <h4>WHY</h4>
                <p>We offer You a chance to join our community of professionals who provide their services and
                    broad set of skills on our platform.
                    <br><strong>Start receiving job requests in one place - easy and fast!</strong></p>
                <h4>HOW</h4>
                <p>Fill out the registration form below, and with a monthly fee of only 3,99BAM
                    enjoy all the benefits of our platform. Sign up for your 14-day FREE trial today!</p>
                <div class="arrow_box"></div>
            </div>
        </div>

        <div class="registrationWrapper">
            <div class="registrationBlockLeft">

                <h1 id="signUp">Register as a professional</h1>
                <form method="post">


                    <div class="checkboxWrapper">
                        <div>
                            <label>Available for work in:</label>
                            <div class="checkbox">
                                <input id="tuzla" type="checkbox">
                                <label for="tuzla">Tuzla</label><br>
                                <input type="checkbox" id="sarajevo">
                                <label for="sarajevo">Sarajevo</label><br>
                                <input type="checkbox" id="bihac">
                                <label for="bihac">Bihac</label><br>
                                <input type="checkbox" id="travnik">
                                <label for="travnik">Travnik</label><br>
                                <input type="checkbox" id="mostar">
                                <label for="mostar">Mostar</label><br>
                                <input type="checkbox" id="zenica">
                                <label for="zenica">Zenica</label><br>
                                <input type="checkbox" id="zivinice">
                                <label for="zivinice">Zivinice</label>
                            </div>
                        </div>

                        <div>
                            <label>Categories of work</label>
                            <div class="checkbox">
                                <input id="furniture" type="checkbox">
                                <label for="furniture">Furniture assembly</label><br>
                                <input type="checkbox" id="generalRepairman">
                                <label for="generalRepairman">General Repairman</label><br>
                                <input type="checkbox" id="plumbing">
                                <label for="plumbing">Plumbing</label><br>
                                <input type="checkbox" id="faucets">
                                <label for="faucets">Faucets</label><br>
                                <input type="checkbox" id="toilets">
                                <label for="toilets">Toilets</label><br>
                                <input type="checkbox" id="electricity">
                                <label for="electricity">Electricity</label><br>
                                <input type="checkbox" id="movingHelp">
                                <label for="movingHelp">Moving Help</label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label>Phone Number<br>
                            <input name="area_code" id="area_code" type="number" placeholder="Area code"
                                   value="<?= isset($_POST['area_code']) ?? isset($_GET['area_code']) ?>" required>
                            <input name="phone_number" id="phone_number" type="number" placeholder="Phone Number"
                                   value="<?= $_POST['phone_number'] ?? isset($_GET['phone_number']) ?>" required>
                        </label>
                    </div>

            </div>
            <div class="verticalLine"></div>

            <div class="registrationBlockRight">
                <h3 class="title">Credit card detail</h3><br>

                <div class="acceptedCards">
                    <label>Accepted Cards</label>
                    <div class="icon-container">
                        <i class="fa fa-cc-visa" style="color:navy;"></i>
                        <i class="fa fa-cc-amex" style="color:blue;"></i>
                        <i class="fa fa-cc-mastercard" style="color:red;"></i>
                        <i class="fa fa-cc-discover" style="color:orange;"></i>
                    </div>
                </div>
                <input type="text" class="card-number" placeholder="Card Number">
                <div class="dateAndCvv">
                    <div class="month">
                        <select name="Month">
                            <option value="january">January</option>
                            <option value="february">February</option>
                            <option value="march">March</option>
                            <option value="april">April</option>
                            <option value="may">May</option>
                            <option value="june">June</option>
                            <option value="july">July</option>
                            <option value="august">August</option>
                            <option value="september">September</option>
                            <option value="october">October</option>
                            <option value="november">November</option>
                            <option value="december">December</option>
                        </select>
                    </div>
                    <div class="year">
                        <select name="Year">
                            <option value="2016">2016</option>
                            <option value="2017">2017</option>
                            <option value="2018">2018</option>
                            <option value="2019">2019</option>
                            <option value="2020">2020</option>
                            <option value="2021">2021</option>
                            <option value="2022">2022</option>
                            <option value="2023">2023</option>
                            <option value="2024">2024</option>
                        </select>
                    </div>
                    <div class="cvv-input">
                        <input type="text" placeholder="CVV">
                    </div>
                </div>
                <div class="submission">
                    <p>By creating an account you agree to our <a href="#">Terms & Privacy</a></p>
                    <button type="submit" class="buttonStyle">START WORKING</button>
                </div>
                </form>
            </div>
        </div>
    </main>

    <?php include('includes/footer.php'); ?>
</div>
</body>
</html>