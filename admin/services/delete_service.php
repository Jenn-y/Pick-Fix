<?php
include('../../includes/db.php');

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = mysqli_query($db, "UPDATE services SET status=0 WHERE sid = {$id}");
    if($result) {
        header('Location: services.php');
    }
}
