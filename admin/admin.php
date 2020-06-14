<!doctype html>
<html lang="en">
<head>
    <?php include('../includes/head.php') ?>
    <link rel="stylesheet" href="admin.css">
    <link rel="icon" href="../images/hammer.png">
    <title>Admin</title>
</head>
<body id="admin">

<header>
    <div class="inner-header flex-container center">
        <h1><a href="admin.php">Pick&Fix</a></h1>
        <a href="../includes/logout.php">Log out</a>
    </div>
</header>

<main class="center">
    <h1 id="title">WELCOME TO ADMIN PAGE</h1>
    <div class="flex-container">
        <div class="tables flex-container">
            <a href="cities/cities.php">Cities</a>
            <a href="services/services.php">Services</a>
            <a href="work_offers/work_offers.php">Work offers</a>
            <a href="users/users.php">Users</a>
            <a href="payments/payments.php">Payments</a>
        </div>

        <div>

            <h2>Report queries and functionalities that are available on admin page are as follows:</h2>

            <div class="flex-container">
                <a href="services/services.php" id="stay"><i class="fa fa-angle-right"
                                                             aria-hidden="true"></i>Services</a>
                <div>
                    <ul>
                        <li>Add a new service with a category description</li>
                        <li>Edit existing service with its category description</li>
                        <li>Delete a service</li>
                        <li>Re-add one of the deleted services</li>
                        <li>See the number of professionals per service</li>
                    </ul>
                </div>
            </div>
            <div class="flex-container">
                <a href="cities/cities.php" id="stay"><i class="fa fa-angle-right" aria-hidden="true"></i>Cities</a>
                <div>
                    <ul>
                        <li>Add a new city</li>
                        <li>Edit existing city name</li>
                        <li>Delete a city</li>
                        <li>Re-add one of the deleted cities</li>
                        <li>See the number of professionals per city</li>
                    </ul>
                </div>
            </div>
            <div class="flex-container">
                <a href="work_offers/work_offers.php" id="stay"><i class="fa fa-angle-right" aria-hidden="true"></i>Work
                    Offers</a>
                <div>
                    <ul>
                        <li>List of all professionals with displayed number of accepted, rejected, total requests,
                            general
                            rating and the percentage of times being rated by customers for completed jobs
                        </li>
                        <li>Option to get specific query results by choosing a target service, city or both</li>
                        <li>List of users that ask for services outside of their city of residence along with
                            displayed cities where services were requested in
                        </li>
                        <li>List of professionals that offer their services even outside of their city of residence
                            along with displayed cities they offer their services in
                        </li>
                        <li>Track all job requests in a given period</li>
                        <li>Rating per service</li>
                        <li>Rating per city</li>
                    </ul>
                </div>
            </div>
            <div class="flex-container">
                <a href="users/users.php" id="stay"><i class="fa fa-angle-right" aria-hidden="true"></i>Users</a>
                <div>
                    <ul>
                        <li>Option to filter users by type</li>
                        <li>List of all registered users</li>
                        <li>List of all regular users</li>
                        <li>List of professionals who requested services of another professional along with counted
                            number of times
                        </li>
                        <li>List of all services each professional offers</li>
                        <li>List of all cities each professional offers their services in</li>
                    </ul>
                </div>
            </div>
            <div class="flex-container">
                <a href="payments/payments.php" id="stay"><i class="fa fa-angle-right"
                                                             aria-hidden="true"></i>Payments</a>
                <div>
                    <ul>
                        <li>Number of professionals per payment plan</li>
                        <li>Number of professionals that continued their payment after the 1st payment plan expiration
                        </li>
                        <li>List of professionals and the amount of money earned in a given period</li>
                        <li>Minimum, maximum, average and total amount billed per service in a given period
                            along with the number of completed jobs
                        </li>
                        <li>Minimum, maximum, average and total amount billed per city in a given period
                            along with the number of completed jobs
                        </li>
                        <li>List of professionals and the mount of money they paid for their usage fees of the platform
                            in a given period
                        </li>
                        <li>List of regular users and the amount of money they spent on the platform in a given period
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>

</body>
</html>