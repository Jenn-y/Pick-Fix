<?php

$query_services = oci_parse($db, 'SELECT * FROM services WHERE date_deleted IS NULL ORDER BY category');
oci_execute($query_services);
$array[] = '';
while ($row_of_services = oci_fetch_assoc($query_services)){
        $array[] = $row_of_services['CATEGORY'];
};
$num_rows = oci_fetch_all($query_services, $res);
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
            <a href="http://localhost/Pick-Fix/index.php">Home</a>
            <a href="http://localhost/Pick-Fix/findProfessionals.php">Find a Professional</a>
            <a href="http://localhost/Pick-Fix/become-pro.php">Become a Professional</a>
            <a href="http://localhost/Pick-Fix/pro-profile.php">My Profile</a>
        </div>

        <div id="logo"><a href="index-signed-in.php"><h1>Pick & Fix</h1></a></div>
        <nav id="services">
            <div class="dropdown">
                <a class="dropdown-link" href="../findProfessionals.php"><i class="fa fa-angle-right"
                                                                            aria-hidden="true"></i> All Services</a>
                <div class="dropdown-content">
                    <div>
                        <?php for ($i = 0; $i < $num_rows/2; $i++) { ?>
                        <a href="#"><?= $array[$i]; ?></a>
                        <?php } ?>
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
            <div class="dropdown">
                <p class="dropdown-link"><i class="fa fa-user" aria-hidden="true"></i><?php echo ' ' . $row['FNAME'] . ' ' . $row['LNAME'] ?></p>
                <div class="dropdown-content" id="signed-profile">
                    <a href="pro-profile.php">My profile</a>
                    <a href="editProfessionalsProfile.php">Edit profile</a>
                    <a href="pro-profile-requests.php">Requests</a>
                    <a href="includes/logout.php">Log out</a>
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
