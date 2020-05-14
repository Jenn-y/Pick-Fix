<?php
if(isset($_GET['id']) && $_POST) {
    $id = $_GET['id'];
    $result = mysqli_query($db, "UPDATE services SET category = '{$_POST['category']}' WHERE sid = $id");

    if($result) {
        header('Location: services.php');
    }
}

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = mysqli_query($db, "SELECT category FROM services WHERE sid = $id");
    $row = mysqli_fetch_assoc($query);
}


?>

<form method="post">
    <label for="category">Service category name: </label>
    <input type="text" name="category" value="<?php
    if (isset($row["category"])){
        echo $row["category"];
    }
    ?>">

    <button type="submit">SAVE</button>
</form>
