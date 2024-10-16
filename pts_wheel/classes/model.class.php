<?php
class Model extends Db
{
    protected function getUser($username)
    {
        $stmt = "select * from users where username = '$username'";
        $result = $this->conn()->query($stmt);
        return $result;
    }

    //get tire change history
    protected function getTireChangeHistoryTB()
    {
        $stmt = "SELECT * FROM `tire_change_history`";
        $result = $this->conn()->query($stmt);

        if ($result->num_rows > 0) {
            return $result;
        } else {
            return "the table is empty";
        }
    }

    protected function registerB(
        $tableName,
        $driver,
        $plate,
        $sidenum,
        $fr,
        $fl,
        $br1,
        $br2,
        $bl1,
        $bl2,
        $km,
        $date
    ) {
        if ($tableName == "bus_detail") {
            $stmt = "insert into $tableName values('$driver','$sidenum','$plate',
            '$fr','$fl','$br1','$br2','$bl1','$bl2',$km)";
        } else {
            $stmt = "insert into $tableName values('$driver','$sidenum','$plate',
            '$fr','$fl','$br1','$br2','$bl1','$bl2',$km,'$date')";
        }

        $result = $this->conn()->query($stmt);
        return $result;
    }

    // get temp_bus_detail data
    protected function getTempBD($platenum)
    {
        $stmt = "SELECT * FROM `temp_bus_detail` WHERE `plate_num` = '$platenum'";
        $data = $this->conn()->query($stmt);

        if ($data->num_rows > 0) {
            return $data;
        } else {
            return "error";
        }
    }
    // change disassembled tire id by assembled tire id
    protected function updateTireId($sidenum, $wheelPos, $newTireId)
    {
        $stmt = "UPDATE `bus_detail` SET `$wheelPos`='$newTireId' WHERE `side_num` = '$sidenum'";

        $result = mysqli_query($this->conn(), $stmt);

        if (mysqli_affected_rows($this->conn()) > 0) {
            return 1;
        } else {
            return $result;
        }
    }

    // register tires (wheen new tire is stored in the store)
    protected function registerT($id, $status, $type)
    {
        $stmt = "insert into tire_detail VALUES('$id','$status','$type',0)";
        $result = $this->conn()->query($stmt);

        return $result;
    }

    // get km reading and plate num
    protected function getbusplateAndKm($sidenum)
    {
        $stmt = "SELECT `plate_num`,`km_reading` FROM `bus_detail` WHERE side_num = '$sidenum' ";
        $result = $this->conn()->query($stmt);

        return $result;
    }

    // get tire id using wheel position

    protected function getTireId($pos, $sidenum)
    {
        $stmt = "SELECT $pos FROM `bus_detail` where side_num = $sidenum";
        $result = $this->conn()->query($stmt);
        return $result;
    }

    //get tire status (new , used , in use, wasted)

    protected function getTireStatus($tireId)
    {
        $stmt = "SELECT `status` FROM `tire_detail` WHERE id = $tireId";

        $result = $this->conn()->query($stmt);

        return $result;
    }

    // get the tire kmReading
    protected function getTKmReading($tireid)
    {
        $stmt = "SELECT `kmReading` FROM `tire_detail` WHERE `id` = $tireid";

        $result = $this->conn()->query($stmt);

        return $result;
    }

    // recored tire change history
    protected function recoredTirechanteHistory(
        $sidenum,
        $platenum,
        $assembledTireId,
        $disAssembledTireId,
        $tireStatus,
        $tirePosition,
        $kmReading,
        $isapproved,
        $reson,
        $date
    ) {
        $stmt = "INSERT INTO `tire_change_history`
        (`platenum`, `sidenum`, `assembled_tire_id`, `disassembled_tire_id`, `isnew`, `tirepos`,
        `km_reading`, `reason`, `isapproved`) 
         VALUES ('$platenum','$sidenum','$assembledTireId','$disAssembledTireId',
        '$tireStatus','$tirePosition','$kmReading','$reson','$isapproved')";

        $result = $this->conn()->query($stmt);

        return $result;
    }

    // get all not approved tire change requestes
    protected function getNotApproved()
    {
        $stmt = "SELECT * FROM `tire_change_history` WHERE isapproved = 'false'";
        $result = $this->conn()->query($stmt);

        if ($result->num_rows > 0) {
            return $result;
        } else {
            return "there is no request";
        }
    }

    // get all approved tire change requests
    protected function getApproved()
    {
        $stmt = "SELECT `sidenum`,`disassembled_tire_id` FROM `tire_change_history` 
                WHERE `isapproved` = '1' AND `servicedone` = '0'";

        $result = $this->conn()->query($stmt);

        if ($result->num_rows > 0) {
            return $result;
        } else {
            return "empty";
        }
    }

    // set to approve in db
    protected function setApproved($sidenum, $newTireId, $oldTireId)
    {
        $stmt = "UPDATE `tire_change_history` SET `isapproved`='1' WHERE `sidenum` = '$sidenum'
         AND `assembled_tire_id`= '$newTireId' AND `disassembled_tire_id` = '$oldTireId'";
        $result = $this->conn()->query($stmt);

        return $result;
    }

    // set service done flag
    protected function setServiseDone($tireId, $sidenum)
    {
        $stmt = "UPDATE `tire_change_history` SET `servicedone`='1' 
                WHERE `sidenum` = '$sidenum' AND `disassembled_tire_id` = '$tireId'";
        $result = $this->conn()->query($stmt);

        return $result;
    }

    // upload excel file to db
    protected function uploadFromExcelFile(
        $sidenum,
        $platenum,
        $assembledTireId,
        $disAssembledTireId,
        $tireStatus,
        $tirePosition,
        $kmReading,
        $isapproved,
        $reson,
        $date
    ) {
        $stmt = "INSERT INTO `tire_change_history`
        (`platenum`, `sidenum`, `assembled_tire_id`, `disassembled_tire_id`, `isnew`, `tirepos`,
        `km_reading`, `reason`, `isapproved`, `servicedone`, `date`) 
         VALUES ('$platenum','$sidenum','$assembledTireId','$disAssembledTireId',
        '$tireStatus','$tirePosition','$kmReading','$reson','1','1','$date')";

        $result = $this->conn()->query($stmt);

        return $result;
    }
}
