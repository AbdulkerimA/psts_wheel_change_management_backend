<?php

header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Headers:content-type');
include_once "./includes/includes.inc.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sideNum = htmlspecialchars($_POST['sidenum']);

    echo "$sideNum";
} else {
    echo "wrong direction fellow";
}
