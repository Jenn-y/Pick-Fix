<!doctype html>
<html lang="en">
<head>
    <?php include('../includes/head.php') ?>
    <link rel="stylesheet" href="admin.css">
    <link rel="icon" href="../images/hammer.png">
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
            <a href="payments/payments.php">Payments</a>
        </div>

        <div>
            <h1>WELCOME TO ADMIN PAGE</h1>
            <h3></h3>
            <ul>
                <li>X - List all the services a specific professional offers. services </li>
                <li>X - List services and the number of professionals that are offering them. services</li>
                <br>
                <li>X - List the professionals and the amount of money they earned in a given period.</li>
                <li>X - List the services and the minimum, maximum, average and total amount billed for a given period + total amount of accepted requests </li>
                <li>X - List the people and the amount of money they paid in a given period.</li>
                <li>X - List the professionals and the amount of money they paid us for the usage of this system, for a given period.</li>
                <br>
                <li>X - List all professionals for a specific service in the specific city. work_offers</li>
                <li>x - List all those professionals that offer the service regardless even out of their place of residence. work_offers </li>
                <li>list services and count num of requests order by that</li>
                <br>
                <li>make like in find professionals to display rating users</li>
                <li>number of ratings out of total accepted requests users</li>
            </ul>
        </div>
    </div>
</main>

</body>
</html>