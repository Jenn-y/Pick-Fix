<?php
include('../../includes/db.php');
$query1 = mysqli_query($db, "SELECT * FROM cities WHERE status=1");
?>
<!doctype html>
<html lang="en">
<head>
    <?php include('../../includes/head.php') ?>
    <link rel="stylesheet" href="../admin.css">
    <title>Admin | Cities</title>
</head>
<body>

<header>
    <div class="inner-header flex-container center">
        <h1><a href="#">Pick&Fix</a></h1>
        <a href="#">Log out</a>
    </div>
</header>

<main class="center">
    <h2>Admin page</h2>
    <div class="flex-container">
        <div class="tables flex-container">
            <a href="cities.php" id="stay">Cities <i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
            <a href="#">Services</a>
            <a href="#">Work offers</a>
            <a href="#">Users</a>
        </div>
        <div class="rows">
            <table>
                <tr>
                    <th>CID</th>
                    <th>Name</th>
                </tr>
                <?php while($row = mysqli_fetch_assoc($query1)): ?>
                    <tr>
                        <td><?= $row["cid"]; ?></td>
                        <td><?= $row["name"]; ?></td>
                        <td><a href="delete_city.php?id=<?=$row['cid']; ?>">delete</a></td>
                    </tr>
                <?php endwhile; ?>

            </table>
            <?php include('insert_city.php'); ?>
        </div>
    </div>
</main>
</body>
</html>