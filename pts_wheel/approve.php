<?php

header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Headers:content-type');

include_once "./includes/includes.inc.php";

$requstmethod = $_SERVER['REQUEST_METHOD'];

switch ($requstmethod) {
    case "POST":
        //do
        $sidenum = htmlspecialchars($_POST['sidenum']);
        $newTireId = htmlspecialchars($_POST['newTireId']);
        $oldTireId = htmlspecialchars($_POST['oldTireId']);

        $controllObj = new Controller();
        $result = $controllObj->setToApproved($sidenum, $newTireId, $oldTireId);

        echo $result;
        break;
    case "GET":
        $viewobj = new View();
        $data = $viewobj->getAllTireRequsts();

        //check for aveliable data
        if ($data != "there is no request") {
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
