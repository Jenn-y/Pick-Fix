<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('Includes/head.php'); ?>
    <link href="CSS/index-header.css" rel="stylesheet">
    <link href="CSS/pro-profile.css" rel="stylesheet">

    <title>Requests</title>
</head>
<body>
<div id="page-container">
    <?php include('Includes/header-signed-in.php'); ?>

    <main class="center">
        <h2>Requests</h2>

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
    </main>

    <?php include('Includes/footer.php'); ?>
</div>
</body>
</html>