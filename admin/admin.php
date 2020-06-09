<!doctype html>
<html lang="en">
<head>
    <?php include('../includes/head.php') ?>
    <link rel="stylesheet" href="admin.css">
    <title>Admin</title>
</head>
<body>

<header>
    <div class="inner-header flex-container center">
        <h1><a href="admin.php">Pick&Fix</a></h1>
        <a href="../includes/logout.php">Log out</a>
    </div>
</header>

<main class="center">
    <h2>Admin page</h2>
    <div class="flex-container">
        <div class="tables flex-container">
            <a href="cities/cities.php">Cities</a>
            <a href="services/services.php">Services</a>
            <a href="work_offers/work_offers.php">Work offers</a>
            <a href="users/users.php">Users</a>
            <a href="#">Payments</a>
        </div>

        <div>
            <h1>WELCOME TO ADMIN PAGE</h1>
            <ul>
                <li>X -List all the services a specific professional offers. services </li>
                <li>X - List services and the number of professionals that are offering them. services</li>
                <br>
                <li>List the professionals and the amount of money they earned in a given period.
                    combined - List the services and the minimum, maximum, average and total amount billed for a given period - payments </li>
                <li>List the people and the amount of money they paid in a given period. payments</li>
                <li>List the professionals and the amount of money they paid us for the usage of this system, for a
                    given period. payments</li>
                <br>
                <li>List all professionals for a specific service in the specific city. work_offers</li>
                <li> List all those professionals that offer the service regardless even out of their place of
                    residence. work_offers </li>
                <li>list cities outside of place of residence work_offers</li>
                <br>
                <li>make like in find professionals to display rating users</li>
                <li>number of ratings out of total accepted requests users</li>
            </ul>

        </div>
    </div>
</main>

</body>
</html>