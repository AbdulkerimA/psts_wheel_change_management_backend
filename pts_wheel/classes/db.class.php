<?php
class Db
{
    private $hostname = 'localhost';
    private $username = 'root';
    private $password = '';
    private $db = 'wheel_exchange_db';

    protected function conn()
    {
        $conn = new mysqli($this->hostname, $this->username, $this->password, $this->db);

        if ($conn->connect_errno) {
            return "Connection Error";
        } else {
            return $conn;
        }
    }
}
