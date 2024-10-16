<?php

header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Headers:content-type');
include_once "./includes/includes.inc.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tirepos = json_decode($_POST['tirepos'], true);
    $newTires = json_decode($_POST['newTires'], true);
    $kmReading = htmlspecialchars($_POST['KmReading']);
    $sidenum = htmlspecialchars($_POST['sidenum']);

    var_dump($tirepos);

    for ($i = 0; $i < count($tirepos); $i++) {
        // clearing the input
        $tirepos[$i] = strtolower($tirepos[$i]);
        $tirepos[$i] = str_replace(" ", "_", $tirepos[$i]);

        $viewObj = new View();
        [$platenum, $lastKmReading] = $viewObj->getKmReadingAndPlateNum($sidenum); //array of platenum and km


        $tireDrivedKm = (int)$lastKmReading;

        $tireID = $viewObj->displayTireId($tirepos[$i], $sidenum); //disassembled tire id
        $newTireStatus = $viewObj->tireStatus($newTires[$i]);


        if ($newTireStatus == "inuse") {
            echo "The specifide tire is curently in use not in store try another tire id ";
            return "";
        }

        $contObj = new Controller();

        $result = $contObj->requestRireChange(
            $sidenum,
            $platenum,
            $newTires[$i],
            $tireID,
            $newTireStatus,
            $tirepos[$i],
            $tireDrivedKm,
            "special case",
            ""
        );
        // change old tire id to the new tire id in bus_details

        $result2 = $contObj->chengeOldTire($sidenum, $tirepos[$i], $newTires[$i]);

        if ($result2 != 1) {
            echo $result2;
            return "";
        }
    }
    echo "successfuly registered";
    return "";
}
