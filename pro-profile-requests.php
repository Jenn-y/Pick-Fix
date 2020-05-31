<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once("includes/db.php");

$aid = $_SESSION['user_id'];
$q = "SELECT * FROM accounts WHERE aid={$aid}";
$query = oci_parse($db, $q);
oci_execute($query);

$row = oci_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('includes/head.php'); ?>
    <link href="css/header.css" rel="stylesheet">
    <link href="css/pro-profile.css" rel="stylesheet">
    <link rel="stylesheet" href="css/footer.css">

    <title>Requests</title>
</head>
<body id="requests">
<div id="page-container">
    <?php include('includes/profile-header.php'); ?>

    <main class="center">
        <?php if ($row['ROLE'] == 1){ ?>
        <h2>Received Requests</h2>

        <div class="shadow">
            <table class="requests">
                <tr>
                    <th>Request</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
                <tr>
                    <td>Request 1</td>
                    <td>DD.MM.YYYY</td>
                    <td><a href="#">Approve</a><a href="#">Reject</a></td>
                </tr>
                <tr>
                    <td>Request 2</td>
                    <td>DD.MM.YYYY</td>
                    <td><a href="#">Approve</a><a href="#">Reject</a></td>
                </tr>
                <tr>
                    <td>Request 3</td>
                    <td>DD.MM.YYYY</td>
                    <td><a href="#">Approve</a><a href="#">Reject</a></td>
                </tr>
                <tr>
                    <td>Request 4</td>
                    <td>DD.MM.YYYY</td>
                    <td><a href="#">Approve</a><a href="#">Reject</a></td>
                </tr>
                <tr>
                    <td>Request 5</td>
                    <td>DD.MM.YYYY</td>
                    <td><a href="#">Approve</a><a href="#">Reject</a></td>
                </tr>
                <tr>
                    <td>Request 6</td>
                    <td>DD.MM.YYYY</td>
                    <td><a href="#">Approve</a><a href="#">Reject</a></td>
                </tr>
            </table>
        </div>
        <?php } ?>

        <h2 id="sentRequests">Sent Requests</h2>

        <div class="shadow">
            <table class="sent-requests">
                <tr>
                    <th>Request</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
                <tr>
                    <td>Request 1</td>
                    <td>DD.MM.YYYY</td>
                    <td><a href="#">Pending</a></td>
                </tr>
                <tr>
                    <td>Request 2</td>
                    <td>DD.MM.YYYY</td>
                    <td><a href="#">Pending</a></td>
                </tr>
                <tr>
                    <td>Request 3</td>
                    <td>DD.MM.YYYY</td>
                    <td><a href="#">Pending</a></td>
                </tr>
                <tr>
                    <td>Request 4</td>
                    <td>DD.MM.YYYY</td>
                    <td><a href="#">Pending</a></td>
                </tr>
                <tr>
                    <td>Request 5</td>
                    <td>DD.MM.YYYY</td>
                    <td><a href="#">Pending</a></td>
                </tr>
                <tr>
                    <td>Request 6</td>
                    <td>DD.MM.YYYY</td>
                    <td><a href="#">Pending</a></td>
                </tr>
            </table>
        </div>
    </main>

    <?php include('includes/footer.php'); ?>
</div>
</body>
</html>