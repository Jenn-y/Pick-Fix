<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once("includes/db.php");
$query_services = oci_parse($db, 'SELECT * FROM services WHERE date_deleted IS NULL ORDER BY category');
oci_execute($query_services);
$array[] = '';
$num_rows = 0;
while ($row_of_services = oci_fetch_assoc($query_services)){
    $array[] = $row_of_services['CATEGORY'];
    $num_rows++;
}
?>

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

        <div id="logo"><a href="index.php"><h1>Pick & Fix</h1></a></div>
        <nav id="services">
            <div class="dropdown">
                <a class="dropdown-link" href="login.php"><i class="fa fa-angle-right"
                                                                         aria-hidden="true"></i> All Services</a>
                <div class="dropdown-content">
                    <div>
                        <?php for ($i = 1; $i < $num_rows/2+1; $i++): ?>
                            <a href="#"><?php echo $array[$i]; ?></a>
                        <?php endfor; ?>
                    </div>
                    <div>
                        <?php for ($i = $num_rows/2+1; $i < $num_rows+1; $i++): ?>
                            <a href="#"><?php echo $array[$i]; ?></a>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </nav>

        <?php if(empty($_SESSION)): ?>
        <div id="side-menu" class="side-nav">
            <a href="#" class="btn-close" onclick="closeMenu()">&times;</a>
            <a href="#">Home</a>
            <a href="index.php#how-it-works" onclick="closeMenu()">How It Works?</a>
            <a href="index.php#story" onclick="closeMenu()">Our Story</a>
            <a href="index.php#team-overview" onclick="closeMenu()">Team Members</a>
            <a href="login.php">Find a Professional</a>
            <a href="login.php">Login</a>
            <a href="userRegistration.php">Register</a>
            <a href="pricing.php">Join As a Pro</a>
            <a href="index.php#contact" onclick="closeMenu()">Contact Us</a>
        </div>

        <nav id="login">
            <ul>
                <li><a href="login.php"><i class="fa fa-sign-in"></i> Log In</a></li>
                <li><a href="pricing.php"><i class="fa fa-star" aria-hidden="true"></i> Join as a Pro</a></li>
            </ul>
        </nav>
    </div>
    <?php else: ?>
        <nav id="login">
            <div class="dropdown">
                <p class="dropdown-link"><i class="fa fa-user" aria-hidden="true"></i><?php echo ' ' . $_SESSION['fname']. ' ' . $_SESSION['lname'] ?></p>
                <div class="dropdown-content" id="signed-profile">
                    <a href="profile.php">My profile</a>
                    <a href="editProfile.php">Edit profile</a>
                    <a href="requests.php">Requests</a>
                    <a href="includes/logout.php">Log out</a>
                </div>
            </div>
        </nav>
        </div>
    <?php endif; ?>

    <?php if(basename($_SERVER['REQUEST_URI']) == "profile.php" || basename($_SERVER['REQUEST_URI']) == "editProfile.php" || basename($_SERVER['REQUEST_URI']) == "requests.php"): ?>
        <div id="side-menu" class="side-nav">
            <a href="#" class="btn-close" onclick="closeMenu()">&times;</a>
            <a href="index.php">Home</a>
            <a href="profile.php">My profile</a>
            <a href="editProfile.php">Edit profile</a>
            <a href="requests.php" onclick="closeMenu()">My Requests</a>
            <a href="findProfessionals.php">Find a Professional</a>
            <?php if ($row['ROLE'] == 2): ?>
                <a href="become-pro.php?id=<?= $_SESSION['user_id']?>">Become a Professional</a>
            <?php endif; ?>
            <a href="index.php">Log out</a>
        </div>
    <?php else: ?>
        <div id="side-menu" class="side-nav">
            <a href="#" class="btn-close" onclick="closeMenu()">&times;</a>
            <a href="index.php">Home</a>
            <a href="findProfessionals.php">Find a Professional</a>
            <?php if ($row['ROLE'] == 2): ?>
                <a href="profile.php">My Profile</a>
            <?php endif; ?>
            <a href="become-pro.php">Become a Professional</a>

        </div>
    <?php endif; ?>

    <script>
        function openMenu(x) {
            document.getElementById('side-menu').style.width = '300px';
        }
        function closeMenu(){
            document.getElementById('side-menu').style.width = '0';
        }
    </script>
</header>
