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
            <h3>Report queries and functionalities that have been implemented for the admin page are as follows:</h3>
            <br>
            <p>	Cities: add, edit, delete a city, with the option to re-add a city if deleted <br> <br>
                	Services: add, edit, delete a service along with category description, with the option to re-add a service if deleted
                <br>
                o	List all services and the number of professionals offering them <br> <br>
                	Work offers: <br>
                o	List of all professionals with displayed number of accepted, rejected, total requests, general rating and the percentage of times being rated by customers for completed jobs
                <br>
                o	If service selected fields number of accepted, rejected, total requests as well as rating and percentage of time being rated is displayed per that service constraint.
                <br>
                o	It is possible to also choose only a city and get specific information or choose both city and a service to be displayed a report based on that
                <br>
                o	List of professionals that offer their services even outside of their city of residence <br>
                o	List of users that ask for services outside of their city of residence along with displayed cities service requested in
                <br>
                o	List of all services and their ratings <br>
                o	List of all cities and ratings in them <br> <br>
                	Users: <br>
                o	List all registered users <br>
                o	Filter users by type: <br>
                	List all regular users <br>
                	List all professionals and display all services and all cities for each <br>
                	List professionals who requested services of another professional and the number of times that happened
                <br> <br>
                	Payments: <br>
                o	List of professionals and the amount of money earned in a given period <br>
                o	Minimum, maximum, average and total amount billed per service in a given period along with the number of accepted jobs
                <br>
                o	List of professionals and the amount of money spent on a fee payments for the usage of the platform in a given period
                <br>
                Number of professionals that continued their payment after the 1st payment plan expiration <br>
                o	List of regular users and the amount of money they spent on the platform in a given period <br>
            </p>
        </div>
    </div>
</main>

</body>
</html>