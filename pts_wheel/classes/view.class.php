<?php
class View extends Model
{
    public function login($Lusername, $Lpassword)
    {
        $data = $this->getUser($Lusername);

        if ($data->num_rows > 0) {

            while ($row = $data->fetch_assoc()) {
                if ($Lusername === $row["username"] && $Lpassword === $row["password"]) {
                    return "correct";
                } else {
                    // if username is correct and password is in correct return the following
                    return "Invalid username or password";
                }
            }
        } else {
            // if both user name and passeord are incorrect return the following 
            return "Invalid username or password";
        }
    }

    // get bus km reading and plate number using side num
    public function getKmReadingAndPlateNum($sidenum)
    {
        $data = $this->getbusplateAndKm($sidenum);
        $result = array();

        if ($data->num_rows == 1) {
            while ($row = $data->fetch_assoc()) {
                array_push(
                    $result,
                    $row['plate_num'],
                    $row['km_reading']
                );
            }
        } else {
            array_push(
                $result,
                "side number not found",
                "side number not found"
            );
        }

        return $result;
    }

    public function getTireKmReading($tireid)
    {
        $data = $this->getTKmReading($tireid);

        if ($data->num_rows) {
            $row = $data->fetch_assoc();
            return $row['kmReading'];
        } else {
            return "The provided Tire id is not found";
        }
    }
    // display tire id
    public function displayTireId($pos, $sidenum)
    {
        $data = $this->getTireId($pos, $sidenum);

        if ($data->num_rows == 1) {
            $row = $data->fetch_assoc();
            return $row[$pos];
        } else {
            return "the bus is not registered";
        }
    }

    //display tire status
    public function tireStatus($tireId)
    {
        $data = $this->getTireStatus($tireId);

        if ($data->num_rows == 1) {
            $row = $data->fetch_assoc();
            return $row['status'];
        } else {
            return "This tire is not aveliable in the store";
        }
    }

    //return all tire change history
    public function getTireChangeHistory()
    {
        $tableData = array();
        $data = $this->getTireChangeHistoryTB();

        if ($data != "the table is empty") {
            // extract the data
            while ($row = $data->fetch_assoc()) {
                array_push(
                    $tableData,
                    $row
                );
            }

            return $tableData;
        } else {
            return "the table is empty";
        }
    }


    // returns all temp_bus_detail info
    public function getTempBusDetail($platenum)
    {
        $data = $this->getTempBD($platenum);

        return $data;
    }
    // display all tire change requests
    public function getAllTireRequsts()
    {
        $cleanData = array();
        $data = $this->getNotApproved();

        if ($data != "there is no request") {
            while ($row = $data->fetch_assoc()) {
                array_push(
                    $cleanData,
                    array(
                        "sidenum" => $row["sidenum"],
                        "platenum" => $row["platenum"],
                        "newTireId" => $row["assembled_tire_id"],
                        "oldTireId" => $row["disassembled_tire_id"],
                        "kmReading" => $row["km_reading"],
                        "reson" => $row['reason']
                    )
                );
            }
        } else {
            return $data;
        }
        return $cleanData;
    }

    public function getAllApprovedRequests()
    {
        $cleanedData = array();
        $data = $this->getApproved();

        if ($data != "empty") {
            while ($row = $data->fetch_assoc()) {
                array_push(
                    $cleanedData,
                    array(
                        "sidenum" => $row['sidenum'],
                        "tireid" => $row['disassembled_tire_id']
                    )
                );
            }
        } else {
            return "There is no approved Request";
        }
        return $cleanedData;
    }
}
