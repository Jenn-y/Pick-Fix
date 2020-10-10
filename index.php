<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/head.php'); ?>
    <link href="css/header.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <link rel="stylesheet" href="css/footer.css">

    <title>Pick&Fix</title>

    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () { // wait until DOM is ready
            document.getElementById("team-overview").addEventListener("click", function (event) { // add event listener
                if (event.target.classList.contains("show-member")) { // check if clicked item has show-member class
                    var memberNumber = event.target.getAttribute("data-member"); //get the value of data-member attribute
                    document.getElementsByClassName('active')[0].classList.remove('active'); //find an element with "active" class and remove that class
                    document.getElementById("member" + memberNumber).classList.add('active'); //find appropriate element for member details by ID and add the active class to it
                }
            });
        });
    </script>
</head>
<body>
<div class="page-container">
    <?php
    include('includes/header.php');
    ?>
    <div class="welcome">
        <div class="color-overlay"></div>
        <h1>The easy, reliable way to take care of your home.</h1>
        <a href="<?= empty($_SESSION) ? "login" : "findProfessionals" ?>">Get Started</a>
    </div>

    <main>
        <div class="text center">
            <h2>Pick & Fix Services</h2>
            <p>Instantly book highly rated pros for all in-house services at the best price. <span><a
                            href="<?= empty($_SESSION) ? "login" : "findProfessionals" ?>">See All <i
                                class="fa fa-angle-right" aria-hidden="true"></i></a></span></p>
        </div>

        <section class="popular-services flex-container center">
            <a href="<?= empty($_SESSION) ? "login" : "findProfessionals" ?>">
                <img src="images/repairman.jpg" alt="Repairman">
                <p>General repairman <i class="fa fa-angle-right" aria-hidden="true"></i></p>
            </a>

            <a href="<?= empty($_SESSION) ? "login" : "findProfessionals" ?>">
                <img src="images/electrics-resized.jpg" alt="Electrics">
                <p>Electrical <i class="fa fa-angle-right" aria-hidden="true"></i></p>
            </a>

            <a href="<?= empty($_SESSION) ? "login" : "findProfessionals" ?>">
                <img src="images/faucet-resized.jpg" alt="Faucet">
                <p>Plumbing <i class="fa fa-angle-right" aria-hidden="true"></i></p>
            </a>

            <a href="<?= empty($_SESSION) ? "login" : "findProfessionals" ?>">
                <img src="images/furniture-resized.jpg" alt="Furniture">
                <p>Furniture <i class="fa fa-angle-right" aria-hidden="true"></i></p>
            </a>

        </section>

        <section class="user-steps" id="how-it-works">
            <h2>You need a service? <i class="fa fa-angle-double-down" aria-hidden="true"></i></h2>
            <div class="step-description center">
                <h2>1</h2>
                <div>
                    <h4>Find a professional</h4>
                    <p>Good news - you are on the right place!
                        Just register on our website and you will be taken to the page to find the right professional
                        for your required service.</p>
                </div>
                <img src="images/find-step.jpg" alt="first-step-find-professional">
            </div>
            <div class="step-description center">
                <h2>2</h2>
                <div>
                    <h4>Describe your problem</h4>
                    <p>Once a professional is chosen, fill out the form with specified problem description and contact
                        details to
                        let the professional know your requirements.</p>
                </div>
                <img src="images/describe-problem.png" alt="first-step-find-professional">
            </div>
            <div class="step-description center">
                <h2>3</h2>
                <div>
                    <h4>Book a professional and solve your problem</h4>
                    <p>If you agree with the calculated estimate price - hit SEND!
                        Your chosen professional will contact you and your problem is solved. <br>
                        It is that easy now. <br> No more waiting, time to <i class="fa fa-angle-right"
                                                                              aria-hidden="true"></i>
                        <a href="<?= empty($_SESSION) ? "login" : "findProfessionals" ?>"
                           style="text-decoration: none;"> REGISTER</a></p>
                </div>
                <img src="images/step3.jpg" alt="first-step-find-professional">
            </div>
        </section>

        <section class="section-for-professionals">
            <div>
                <img src="images/zoom.png" alt="zoom-icon">
                <img src="images/electronics.png" alt="electronics-icon">
                <img src="images/plumbing.png" alt="plumbing-icon">
                <img src="images/roller.png" alt="painting-icon">
                <img src="images/washing.png" alt="washing-icon">
            </div>
            <h2>Are You a Home Improvement or Service Pro?</h2>
            <p>Find out how Pick&Fix can help your business</p>
            <a href="pricing">Learn More <i class="fa fa-angle-right" aria-hidden="true"></i></a>
        </section>

        <section class="our-story" id="story">
            <div>
                <h1>Our story</h1> <br>
                <h4>Great companies are born from dreams and strength of will.</h4><br>
                <p>The story begins with a date: March 15th, 2018, with a place: Sarajevo, and with 3 names: Armin
                    Salihovic,
                    Dzenita Djulovic, and Hana Lihovac. For years people struggled with finding a right professional for
                    the
                    job that needs to be done. They were losing time, patience, and money. Not anymore, as we have
                    developed a web
                    application that provides users everything they need to find the professional - all in one place.
                    Our secret is
                    in knowing the struggles that users were having in finding the right professionals and using most
                    advanced technology for full user experience, integrated into constant research towards the
                    development
                    of new solutions. <br><br> <b>Join us and let's grow together! </b>
                </p>
            </div>
            <img id="rotate-left" src="images/our-story-image.png" alt="Our work">
            <img id="rotate-right" src="images/our-story-image.png" alt="Our work">
        </section>

        <section class="team-section">
            <div id="team-overview">
                <p>CLICK TO GET TO KNOW US <i class="fa fa-angle-double-down" aria-hidden="true"></i></p>
                <img class="show-member" src="images/teamMember.png" alt="Image" data-member="1">
                <img class="show-member" src="images/armin.png" alt="Image" data-member="2">
                <img class="show-member" src="images/hana.png" alt="Image" data-member="3">
            </div>

            <div id="member1" class="team-member center active">
                <img id="small" src="images/teamMember.png" alt="Image">
                <div>
                    <h2> Jenn </h2>
                    <p> Ambitious and hard-working young engineer coming from Zivinice
                        and currently enrolled in bachelor studies of Computer Science and Information Systems.
                        Passionate about giving back to the community and inspiring new generations of leaders
                        through her volunteering and activism in a broad range of organizations. Strong advocate of both
                        formal and informal education with the objective
                        to help other young people in realizing their potential and using the available opportunities.
                        Connect on <i class="fa fa-angle-right" aria-hidden="true"></i> <a
                                href="https://www.linkedin.com/in/dzenita-djulovic/"
                                style="text-decoration: none; color: white;" target="_blank">LinkedIn</a>
                    </p>

                </div>
            </div>

            <div id="member2" class="team-member center">
                <img id="small" src="images/armin.png" alt="Image">
                <div>
                    <h2> Armin </h2>
                    <p>Easygoing second year student at SSST University studying Computer Science. Passionate about
                        Computers and Electronics.</p>
                </div>
            </div>

            <div id="member3" class="team-member center">
                <img id="small" src="images/hana.png" alt="Image">
                <div>
                    <h2> Hana </h2>
                    <p>Dedicated Computer Science student pursuing a diploma degree at SSST.
                        Having a great interest in mathematics and computer science developing skills
                        into data science professionals, particularly in the fields of machine learning
                        and artificial intelligence.
                    </p>
                </div>
            </div>
        </section>

        <section class="vetted-professionals flex-container center" id="contact">
            <i class="fa fa-lock" aria-hidden="true"></i>
            <h2>Vetted, Background-Checked Professionals</h2>
            <p>Pick & Fix - all services booked directly through the Pick & Fix platform are performed by
                experienced, background-checked professionals</p>
            <p>who are highly rated by customers like you. <a
                        href="<?= empty($_SESSION) ? "login" : "findProfessionals" ?>"
                        style="text-decoration: none;"><span>Learn more. </span></a></p>
        </section>
    </main>

    <?php include('includes/footer.php'); ?>
</div>
</body>
</html>
