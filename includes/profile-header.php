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

        <div id="side-menu" class="side-nav">
            <a href="#" class="btn-close" onclick="closeMenu()">&times;</a>
            <a href="index.php">Home</a>
            <a href="pro-profile.php" onclick="closeMenu()">My Profile</a>
            <a href="editProfile.php">Edit profile</a>
            <a href="pro-profile-requests.php" onclick="closeMenu()">My Requests</a>
            <a href="findProfessionals.php">Find a Professional</a>
            <?php if ($row['ROLE'] == 2){ ?>
            <a href="become-pro.php?id=<?= $_SESSION['user_id']?>">Become a Professional</a>
            <?php } ?>
            <a href="index.php">Log out</a>
        </div>

        <div id="logo"><a href="index-signed-in.php"><h1>Pick & Fix</h1></a></div>
        <nav id="services">
            <div class="dropdown">
                <a class="dropdown-link" href="../findProfessionals.php"><i class="fa fa-angle-right"
                                                                            aria-hidden="true"></i> All Services</a>
                <div class="dropdown-content">
                    <div>
                        <?php for ($i = 1; $i < $num_rows/2+1; $i++) { ?>
                            <a href="#"><?php echo $array[$i]; ?></a>
                        <?php } ?>
                    </div>
                    <div>
                        <?php for ($i = $num_rows/2+1; $i < $num_rows+1; $i++) { ?>
                            <a href="#"><?php echo $array[$i]; ?></a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </nav>
        <nav id="login">
            <div class="dropdown">
                <p class="dropdown-link"><i class="fa fa-user" aria-hidden="true"></i> <?php echo ' ' . $_SESSION['fname'] . ' ' . $_SESSION['lname'] ?></p>
                <div class="dropdown-content" id="signed-profile">
                    <a href="pro-profile.php">My profile</a>
                    <a href="editProfile.php">Edit profile</a>
                    <a href="pro-profile-requests.php">Requests</a>
                    <a href="index.php">Log out</a>
                </div>
            </div>
        </nav>
    </div>

    <script>
        function openMenu(){
            document.getElementById('side-menu').style.width = '300px';
        }
        function closeMenu(){
            document.getElementById('side-menu').style.width = '0';
        }
    </script>
</header>
