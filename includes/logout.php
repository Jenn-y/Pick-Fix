<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once("includes/db.php");

$_SESSION = [];
session_destroy();

header('Location: ../index.php');

