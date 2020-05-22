<?php

session_start();

include('includes/db.php');

$_SESSION = [];
session_destroy();

header('Location: ../index.php');

