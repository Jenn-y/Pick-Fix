<?php
// function that creates and checks input
function create_input($type, $name, $placeholder, $required = false)
{
    // start input
    echo "<input type=\"$type\" placeholder=\"$placeholder\" name=\"$name\"";

    // check if sticky
    if (isset($_POST["$name"])) {
        echo "value={$_POST["$name"]}";
    }

    // close input
    if ($required) {
        echo " required>";
    } else {
        echo ">";
    }
}

function fetch_profile_image($user_id, $type)
{
    if ($type == null) {
        return "images/default-user.png";
    }
    return "images/profiles/" . $user_id . "." . $type;
}

function checkRequiredField($value)
{
    return isset($value) && !empty($value);
}

function check_if_logged_in()
{
    if (empty($_SESSION)) {
        echo '<script> location.replace("includes/redirect.php"); </script>';
    }
}
