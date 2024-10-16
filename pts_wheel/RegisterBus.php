<?php

header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Headers:content-type');
include_once "./includes/includes.inc.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sideNum = htmlspecialchars($_POST['sidenum']);
    $plate = htmlspecialchars($_POST['platenum']);
    $driverName = htmlspecialchars($_POST['driver']);
    $fr = htmlspecialchars($_POST['fr']);
    $fl = htmlspecialchars($_POST['fl']);
    $brone = htmlspecialchars($_POST['br1']);
    $brtwo = htmlspecialchars($_POST['br2']);
    $blone = htmlspecialchars($_POST['bl1']);
    $bltwo = htmlspecialchars($_POST['bl2']);
    $kmReading = htmlspecialchars($_POST['km']);

    // for test only
    //echo "$driverName $sideNum $plate $kmReading $fr $fl $brone $brtwo $blone $bltwo";

    // sending the data to the db
    $contObj = new Controller();
    $result = $contObj->registerBus($driverName, $plate, $sideNum, $fr, $fl, $brone, $brtwo, $blone, $bltwo, $kmReading);

    echo "sucessfuly registered";
} else {
    echo "wrong direction fellow";
}
