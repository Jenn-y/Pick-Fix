<?php
if(isset($_GET['id']) && $_POST) {
    $id = $_GET['id'];
    $result = mysqli_query($db, "UPDATE cities SET name = '{$_POST['name']}' WHERE cid = $id");

    if($result) {
        header('Location: cities.php');
    }
}

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = mysqli_query($db, "SELECT name FROM cities WHERE cid = $id");
    $row = mysqli_fetch_assoc($query);
}


?>

<form method="post">
    <label for="name">City name: </label>
    <input type="text" name="name" value="<?php
    if (isset($row["name"])){
        echo $row["name"];
    }
     ?>">

    <button type="submit">SAVE</button>
</form>
