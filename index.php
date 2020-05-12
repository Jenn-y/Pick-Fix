<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/head.php'); ?>
    <link href="CSS/header.css" rel="stylesheet">
    <link href="CSS/index.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/footer.css">
    <link rel="stylesheet" href="CSS/test.css">


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
    <?php include('includes/header.php'); ?>

    <div class="welcome">
        <div class="color-overlay"></div>
        <h1>The easy, reliable way to take care of your home.</h1>
        <a href="findProfessionals.php">Get Started</a>
    </div>

    <main>
        <div class="text center">
            <h2>Pick & Fix Tasks</h2>
            <p>Instantly book highly rated pros for cleaning and handyman tasks at a fixed price. <span>See All <i
                            class="fa fa-angle-right" aria-hidden="true"></i></span></p>
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

        </section>

        <section class="user-steps">
            <h2>You need a service? <i class="fa fa-angle-double-down" aria-hidden="true"></i></h2>
            <div class="step-description">
                <h1>1</h1>
                <div>
                    <h4>Find a professional</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In condimentum orci sed interdum
                        egestas.
                        Suspendisse lobortis odio vitae purus tincidunt, vel tempor lacus vestibulum. Ut eu lacus dui.
                        Quisque</p>
                </div>
                <img src="Images/find-step.jpg" alt="first-step-find-professional">
            </div>
            <div class="step-description">
                <h1>2</h1>
                <div>
                    <h4>Describe your problem</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In condimentum orci sed interdum
                        egestas.
                        Suspendisse lobortis odio vitae purus tincidunt, vel tempor lacus vestibulum. Ut eu lacus dui.
                        Quisque</p>
                </div>
                <img src="Images/find-step.jpg" alt="first-step-find-professional">
            </div>
            <div class="step-description">
                <h1>3</h1>
                <div>
                    <h4>Book a professional and solve your problem</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In condimentum orci sed interdum
                        egestas.
                        Suspendisse lobortis odio vitae purus tincidunt, vel tempor lacus vestibulum. Ut eu lacus dui.
                        Quisque</p>
                </div>
                <img src="Images/find-step.jpg" alt="first-step-find-professional">
            </div>
        </section>

        <section class="our-story">
            <div>
                <h1>Our story</h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In condimentum orci sed interdum
                    egestas.
                    Suspendisse lobortis odio vitae purus tincidunt, vel tempor lacus vestibulum. Ut eu lacus dui.
                    Quisque
                    faucibus nisl ac dui rutrum accumsan. Fusce ultrices massa vel sem tincidunt ultricies. Nunc
                    rutrum
                    tristique tincidunt. Fusce ullamcorper urna vel ante elementum placerat. Cras sed tortor at
                    neque
                    suscipit placerat vel quis leo. Cras dapibus commodo nunc ac accumsan. Morbi eget nunc semper,
                    feugiat
                    tortor vitae, vehicula velit. Maecenas scelerisque sollicitudin massa at rhoncus. Vivamus
                    eleifend
                    lectus dolor, vitae pulvinar lorem tincidunt ut.</p>
            </div>
            <img id="rotate-left" src="Images/our-story-image.png" alt="Our work">
            <img id="rotate-right" src="Images/our-story-image.png" alt="Our work">
        </section>

        <section class="team-section">
            <div id="team-overview">
                <h1>CLICK TO GET TO KNOW US <i class="fa fa-angle-double-down"
                                               aria-hidden="true"></i></h1>
                <img class="show-member" src="Images/teamMember.png" alt="Image" data-member="1">
                <img class="show-member" src="Images/teamMember.png" alt="Image" data-member="2">
                <img class="show-member" src="Images/teamMember.png" alt="Image" data-member="3">
            </div>

            <div id="member1" class="team-member active">
                <img id="small" src="Images/teamMember.png" alt="Image">
                <div>
                    <h2> Jenn </h2>
                    <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. In condimentum orci sed interdum
                        egestas.
                        Suspendisse lobortis odio vitae purus tincidunt, vel tempor lacus vestibulum. Ut eu lacus dui.
                        Quisque
                        faucibus nisl ac dui rutrum accumsan. Fusce ultrices massa vel sem tincidunt ultricies. Nunc
                        rutrum
                        tristique tincidunt. Fusce ullamcorper urna vel ante elementum placerat. Cras sed tortor at
                        neque
                        suscipit placerat vel quis leo. Cras dapibus commodo nunc ac accumsan. Morbi eget nunc semper,
                        feugiat
                        tortor vitae, vehicula velit. Maecenas scelerisque sollicitudin massa at rhoncus. Vivamus
                        eleifend
                        lectus dolor, vitae pulvinar lorem tincidunt ut.
                    </p>
                </div>
            </div>

            <div id="member2" class="team-member">
                <img id="small" src="Images/teamMember.png" alt="Image">
                <div>
                    <h2> Armin </h2>
                    <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. In condimentum orci sed interdum
                        egestas.
                        Suspendisse lobortis odio vitae purus tincidunt, vel tempor lacus vestibulum. Ut eu lacus dui.
                        Quisque
                        faucibus nisl ac dui rutrum accumsan. Fusce ultrices massa vel sem tincidunt ultricies. Nunc
                        rutrum
                        tristique tincidunt. Fusce ullamcorper urna vel ante elementum placerat. Cras sed tortor at
                        neque
                        suscipit placerat vel quis leo. Cras dapibus commodo nunc ac accumsan. Morbi eget nunc semper,
                        feugiat
                        tortor vitae, vehicula velit. Maecenas scelerisque sollicitudin massa at rhoncus. Vivamus
                        eleifend
                        lectus dolor, vitae pulvinar lorem tincidunt ut.
                    </p>
                </div>
            </div>

            <div id="member3" class="team-member">
                <img id="small" src="Images/teamMember.png" alt="Image">
                <div>
                    <h2> Hana </h2>
                    <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. In condimentum orci sed interdum
                        egestas.
                        Suspendisse lobortis odio vitae purus tincidunt, vel tempor lacus vestibulum. Ut eu lacus dui.
                        Quisque
                        faucibus nisl ac dui rutrum accumsan. Fusce ultrices massa vel sem tincidunt ultricies. Nunc
                        rutrum
                        tristique tincidunt. Fusce ullamcorper urna vel ante elementum placerat. Cras sed tortor at
                        neque
                        suscipit placerat vel quis leo. Cras dapibus commodo nunc ac accumsan. Morbi eget nunc semper,
                        feugiat
                        tortor vitae, vehicula velit. Maecenas scelerisque sollicitudin massa at rhoncus. Vivamus
                        eleifend
                        lectus dolor, vitae pulvinar lorem tincidunt ut.
                    </p>
                </div>
            </div>
        </section>

        <section class="vetted-professionals flex-container center">
            <i class="fa fa-lock" aria-hidden="true"></i>
            <h2>Vetted, Background-Checked Professionals</h2>
            <p>Pick & Fix tasks booked and paid for directly through the Pick & Fix platform are performed by
                experienced, background-checked professionals</p>
            <p>who are highly rated by customers like you. <span> Learn more.</span></p>
        </section>
    </main>

    <?php include('includes/footer.php'); ?>
</div>
</body>
</html>