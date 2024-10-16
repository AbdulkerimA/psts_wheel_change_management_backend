<?php
header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Headers:content-type');
include_once "./includes/includes.inc.php";

$REQUSTMETHOD = $_SERVER['REQUEST_METHOD'];

switch ($REQUSTMETHOD) {
    case "POST":
        break;
    case "GET":
        //get all tire change history from db
        $viewObj = new View();
        $data = $viewObj->getTireChangeHistory();

        if ($data != "the table is empty") {
            // json object must be wraped by angle brackets([])
            echo '[';
            for ($i = 0; $i < count($data); $i++) {
                //var_dump($row['platenum']);
                echo ($i > 0 ? ',' : '') . json_encode($data[$i]);
            }
            echo ']';
        }
        break;
}
