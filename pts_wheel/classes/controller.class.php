<?php
class Controller extends Model
{
    public function registerBus(
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
        $tableName = "bus_detail",
        $date = ""
    ) {
        $this->registerB($tableName, $driver, $plate, $sidenum, $fr, $fl, $br1, $br2, $bl1, $bl2, $km, $date);
    }

    // update old tire id by new tire id
    public function chengeOldTire($sidenum, $wheelpos, $newTireId)
    {
        $result = $this->updateTireId($sidenum, $wheelpos, $newTireId);

        if ($result == 1) {
            return 1;
        } else {
            return $result;
        }
    }

    //set to approved in db
    public function setToApproved($sidenum, $newTireId, $oldTireId)
    {
        $result = $this->setApproved($sidenum, $newTireId, $oldTireId);

        return $result == "success" ? "successfuly approved" : $result;
    }

    // set Serviced flag in db
    public function setServicedFlag($sidenum, $oldTireId)
    {
        $result = $this->setServiseDone($oldTireId, $sidenum);

        return $result;
    }
    // register tire 
    public function registerTire($id, $status, $type)
    {
        $this->registerT($id, $status, $type);
    }

    public function requestRireChange(
        $sidenum,
        $platenum,
        $assembledTireId,
        $disAssembledTireId,
        $tireStatus,
        $tirePosition,
        $kmReading,
        $reson,
        $date
    ) {
        $result = $this->recoredTirechanteHistory(
            $sidenum,
            $platenum,
            $assembledTireId,
            $disAssembledTireId,
            $tireStatus,
            $tirePosition,
            $kmReading,
            false,
            $reson,
            $date
        );
    }


    // upload excel to the db
    public function uploadExcel(
        $platenum,
        $sidenum,
        $assembledTireId,
        $disAssembledTireId,
        $tireStatus,
        $tirePosition,
        $kmReading,
        $reson,
        $date
    ) {
        $result = $this->uploadFromExcelFile(
            $sidenum,
            $platenum,
            $assembledTireId,
            $disAssembledTireId,
            $tireStatus,
            $tirePosition,
            $kmReading,
            false,
            $reson,
            $date
        );

        return $result;
    }
}
