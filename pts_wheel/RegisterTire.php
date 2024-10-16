<?php

header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Headers:content-type');
include_once "./includes/includes.inc.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tireId = htmlspecialchars($_POST['id']);
    $type = htmlspecialchars($_POST['type']);
    $status = htmlspecialchars($_POST['status']);

    $contobj = new Controller();
    $result = $contobj->registerTire($tireId, $status, $type);

    echo "successfuly registerd";
} else {
    echo "wrong direction fellow";
}
