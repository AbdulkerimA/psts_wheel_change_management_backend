<?php

header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Headers:content-type');

include_once "./includes/includes.inc.php";

$requstmethod = $_SERVER['REQUEST_METHOD'];

switch ($requstmethod) {
    case "POST":
        //do
        $sidenum = htmlspecialchars($_POST['sidenum']);
        $oldTireId = htmlspecialchars($_POST['oldTireId']);

        $controllObj = new Controller();
        $result = $controllObj->setServicedFlag($sidenum, $oldTireId);

        echo $result;
        break;
    case "GET":
        $viewobj = new View();
        $data = $viewobj->getAllApprovedRequests();

        //check for aveliable data
        if ($data != "There is no approved Request") {
            echo '[';
            for ($i = 0; $i < count($data); $i++) {
                //var_dump($row['platenum']);
                echo ($i > 0 ? ',' : '') . json_encode($data[$i]);
            }
            echo ']';
        } else {
            echo json_encode(array());
        }
        break;
}
