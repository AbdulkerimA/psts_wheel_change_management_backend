<?php
header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Headers:content-type');

// include our classes
include_once "./includes/includes.inc.php";

// Include the Composer autoload file
require 'vendor/autoload.php';

use Matrix\Builder;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

use function PHPSTORM_META\type;

$data = array();

if (isset($_FILES['excel_file'])) {
    $file = $_FILES['excel_file']['tmp_name'];

    // Load the Excel file
    $spreadsheet = IOFactory::load($file);

    // Get the first worksheet
    $worksheet = $spreadsheet->getActiveSheet();

    // Get the highest row and column
    $highestRow = $worksheet->getHighestRow();
    $highestColumn = $worksheet->getHighestColumn();

    // Output data
    for ($row = 1; $row <= $highestRow; ++$row) {

        $getRow = array();
        // Loop through columns
        for ($col = 'A'; $col <= $highestColumn; ++$col) {
            $value = $worksheet->getCell($col . $row)->getValue();
            array_push(
                $getRow,
                htmlspecialchars($value)
            );
        }
        array_push($data, $getRow);
    }
}


// creat controllerobh
$contObj = new Controller();
$uniquePlatenums = array();

for ($i = 1; $i < $highestRow; $i++) {
    $date =  Date::excelToDateTimeObject($data[$i][10])->format('Y-m-d');
    $result = $contObj->uploadExcel(
        $data[$i][0], //platenum
        $data[$i][1], //sidenum
        $data[$i][2], // assebled
        $data[$i][3], // disassebled
        $data[$i][4], // isnew?
        $data[$i][5], //tire position
        $data[$i][6], //km reading
        $data[$i][7], // reson
        $date
    );

    $result2 = $contObj->registerBus(
        "driver",
        $data[$i][0], // plate num
        $data[$i][1], // sidenum
        $data[$i][5] == "front_right" ? $data[$i][2] : "",
        $data[$i][5] == "front_left" ? $data[$i][2] : "",
        $data[$i][5] == "back_right1" ? $data[$i][2] : "",
        $data[$i][5] == "back_right2" ? $data[$i][2] : "",
        $data[$i][5] == "back_left1" ? $data[$i][2] : "",
        $data[$i][5] == "back_left2" ? $data[$i][2] : "",
        $data[$i][6],
        "temp_bus_detail",
        $date
    );
    // store only unique plate numbers
    if (in_array($data[$i][0], $uniquePlatenums)) {
        continue;
    } else {
        array_push(
            $uniquePlatenums,
            $data[$i][0]
        );
    }
}




//for test only
//var_dump($uniquePlatenums);

function buidBusProfile($platenums)
{
    $viewobj = new View();
    for ($i = 0; $i < count($platenums); $i++) {
        $busData = $viewobj->getTempBusDetail($platenums[$i]);
        filterData($busData);
    }
}

function filterData($profile)
{
    $fullProfile = array();
    if ($profile != "error") {
        while ($row = $profile->fetch_assoc()) {
            //echo count($fullProfile) . "<br />";
            //echo " from inside : " . count($fullProfile) . "<br />";
            if (count($fullProfile) > 0) {
                if (in_array($row["plate_num"], $fullProfile[0])) {
                    //var_dump(in_array($row["plate_num"], $fullProfile[0]));
                    if ($row["front_right"] != "") {
                        $fullProfile[0]['fr'] = $row["front_right"];
                    }
                    if ($row["front_left"] != "") {
                        $fullProfile[0]['fl'] = $row["front_left"];
                    }
                    if ($row["back_right_one"] != "") {
                        $fullProfile[0]['br1'] = $row["back_right_one"];
                    }
                    if ($row["back_right_two"] != "") {
                        $fullProfile[0]['br2'] = $row["back_right_two"];
                    }
                    if ($row["back_left_one"] != "") {
                        $fullProfile[0]['bl1'] = $row["back_left_one"];
                    }
                    if ($row["back_left_two"] != "") {
                        $fullProfile[0]['bl2'] = $row["back_left_two"];
                    }
                } else {
                    //var_dump(in_array($row["plate_num"], $fullProfile[0]));
                    continue;
                }
            } else {
                //echo "from inside : " . count($fullProfile) . "<br />";
                array_push(
                    $fullProfile,
                    array(
                        "driverName" => $row["driver_name"],
                        "platenum" => $row['plate_num'],
                        "sidenum" => $row["side_num"],
                        "fl" => $row["front_left"],
                        "fr" => $row['front_right'],
                        "br1" => $row["back_right_one"],
                        "br2" => $row["back_right_two"],
                        "bl1" => $row["back_left_one"],
                        "bl2" => $row["back_left_two"],
                        "km" => $row["km_reading"]
                    )
                );
                /*var_dump(in_array($row["plate_num"], $fullProfile));
                var_dump($fullProfile);
                break;*/
            }
        }
    }
    //var_dump($fullProfile);
    registerTheBus($fullProfile[0]);
    //echo "<br />";
}

function registerTheBus($fullProfile)
{
    $contObj = new Controller();

    $result = $contObj->registerBus(
        $fullProfile['driverName'],
        $fullProfile['platenum'],
        $fullProfile['sidenum'],
        $fullProfile['fr'],
        $fullProfile['fl'],
        $fullProfile['br1'],
        $fullProfile['br2'],
        $fullProfile['bl1'],
        $fullProfile['bl2'],
        $fullProfile['km']
    );

    echo $result;
}

buidBusProfile($uniquePlatenums);
/*echo $result;
echo "\n" . $result2;*/
?>

<!DOCTYPE html>
<html>

<head>
    <title>Import Excel File</title>
</head>

<body>
    <h2>Upload Excel File</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="excel_file" required>
        <button type="submit">Upload and Display</button>
    </form>
</body>

</html>