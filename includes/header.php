<header>
    <div id="inner-header">

        <span class="open-slide">
            <a href="#" onclick="openMenu()">
                <svg width="30" height="30">
                <path d="M0,5 30,5" stroke="#000" stroke-width="3"/>
                <path d="M0,14 30,14" stroke="#000" stroke-width="4"/>
                <path d="M0,23 30,23" stroke="#000" stroke-width="3"/>
                </svg>
            </a>
        </span>

        <div id="side-menu" class="side-nav">
            <a href="#" class="btn-close" onclick="closeMenu()">&times;</a>
            <a href="#">Home</a>
            <a href="http://localhost/Pick-Fix/index.php#how-it-works" onclick="closeMenu()">How It Works?</a>
            <a href="http://localhost/Pick-Fix/index.php#story" onclick="closeMenu()">Our Story</a>
            <a href="http://localhost/Pick-Fix/index.php#team-overview" onclick="closeMenu()">Team Members</a>
            <a href="http://localhost/Pick-Fix/login.php">Find a Professional</a>
            <a href="http://localhost/Pick-Fix/login.php">Login</a>
            <a href="http://localhost/Pick-Fix/userRegistration.php">Register</a>
            <a href="http://localhost/Pick-Fix/professionalsRegistration.php">Join As a Pro</a>
            <a href="http://localhost/Pick-Fix/index.php#contact" onclick="closeMenu()">Contact Us</a>
        </div>

        <div id="logo"><a href="index.php"><h1>Pick & Fix</h1></a></div>
        <nav id="services">

            <div class="dropdown">
                <a class="dropdown-link" href="findProfessionals.php"><i class="fa fa-angle-right"
                                                                         aria-hidden="true"></i> All Services</a>
                <div class="dropdown-content">
                    <div>
                        <a href="#">Appliances</a>
                        <a href="#">Carpet</a>
                        <a href="#">Chimney</a>
                        <a href="#">Driveways</a>
                        <a href="#">Electrical</a>
                        <a href="#">Furniture</a>
                    </div>
                    <div>
                        <a href="#">General Repairman</a>
                        <a href="#">Glass and Screens</a>
                        <a href="#">Lighting</a>
                        <a href="#">Painting</a>
                        <a href="#">Plumbing</a>
                        <a href="#">Windows and Doors</a>
                    </div>
                </div>
            </div>
        </nav>
        <nav id="login">
            <ul>
                <li><a href="login.php"><i class="fa fa-sign-in"></i> Log In</a></li>
                <li><a href="professionalsRegistration.php"><i class="fa fa-star" aria-hidden="true"></i> Join as a Pro</a></li>
            </ul>
        </nav>
    </div>

    <script>
        function openMenu(){
            document.getElementById('side-menu').style.width = '250px';
        }
        function closeMenu(){
            document.getElementById('side-menu').style.width = '0';
        }
    </script>
</header>
