<?php
include('../../includes/db.php');

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    /*$result = mysqli_query($db, "DELETE FROM cities WHERE cid = {$id}");*/
    $result = mysqli_query($db, "UPDATE cities SET status=0 WHERE cid = {$id}");
    if($result) {
        header('Location: cities.php');
    }
}
