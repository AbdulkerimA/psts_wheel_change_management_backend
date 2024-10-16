<?php

header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Headers:content-type');

include_once "./includes/includes.inc.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = htmlspecialchars($_POST['user']);
    $password = htmlspecialchars($_POST['pass']);

    $viewobj = new View();
    $result = $viewobj->login($username, $password);
    echo $result;
} else {
    echo "wrong direction fellow";
}
