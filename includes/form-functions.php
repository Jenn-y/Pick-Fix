<?php
// function that creates and checks input
function create_input($type, $name, $placeholder, $required = false) {
    // start input
    echo "<input type=\"$type\" placeholder=\"$placeholder\" name=\"$name\"";

    // check if sticky
    if(isset($_POST["$name"])) {
        echo "value={$_POST["$name"]}";
    }

    // close input
    if($required) {
        echo " required>";
    } else {
        echo ">";
    }
}
