<?php
include('../../includes/db.php');

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = mysqli_query($db, "DELETE FROM cities WHERE cid = {$id}");
    if($result) {
        header('Location: cities.php');
    }
}
