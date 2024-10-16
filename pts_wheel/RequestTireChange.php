<?php

header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Headers:content-type');
include_once "./includes/includes.inc.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newtireId = htmlspecialchars($_POST['exchengetireid']);
    $sidenum = htmlspecialchars($_POST['sidenum']);
    $wheelpos = htmlspecialchars($_POST['wheelpos']);
    $reson = htmlspecialchars($_POST['reson']);
    $currentKmReading = htmlspecialchars($_POST['currentKmReading']);

    // clearing the input
    $wheelpos = strtolower($wheelpos);
    $wheelpos = str_replace(" ", "_", $wheelpos);
    $wheelpos = str_replace("1", "one", $wheelpos);
    $wheelpos = str_replace("2", "two", $wheelpos);


    // get bus plate and km reading
    $viewObj = new View();
    [$platenum, $lastKmReading] = $viewObj->getKmReadingAndPlateNum($sidenum); //array of platenum and km
    $tireID = $viewObj->displayTireId($wheelpos, $sidenum); //disassembled tire id
    $newTireStatus = $viewObj->tireStatus($newtireId);
    $oldTireKmReading = $viewObj->getTireKmReading($tireID);

    //echo $newTireStatus;

    // check for validation
    if (calculateMinRequirment($oldTireKmReading, $currentKmReading)) {
        // procced the proccess

        if ($newTireStatus == "inuse") {
            echo "The specifide tire is curently in use not in store try another tire id ";
            return "";
        }

        $contObj = new Controller();

        $result = $contObj->requestRireChange(
            $sidenum,
            $platenum,
            $newtireId,
            $tireID,
            $newTireStatus,
            $wheelpos,
            $tireDrivedKm == 0 ? $lastKmReading : ($currentKmReading > $lastKmReading ? $currentKmReading : $lastKmReading),
            $reson,
            ""
        );
        // change old tire id to the new tire id in bus_details

        $result2 = $contObj->chengeOldTire($sidenum, $wheelpos, $newtireId);

        if ($result2 != 1) {
            echo $result2;
            return "";
        }
        echo "successfuly registered";
        return "";
    } // 
    else {
        echo "your km reading is below the minimum requirment";
        return "";
    }

    //
} else {
    echo "wrong direction fellow";
}

// function to chake minimum requirment of km reding to change tire
function calculateMinRequirment($lastKmReading, $currentKmReading)
{
    $valid = false;
    global $tireDrivedKm;
    $tireDrivedKm = $lastKmReading != "The provided Tire id is not found" ? $currentKmReading - $lastKmReading : 0;
    $tireDrivedKm >= 80000 ? $valid = true : null;
    return $valid;
}
