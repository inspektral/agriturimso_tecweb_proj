<?php

class DBAccess
{

    private const HOST_DB = "localhost";

    private const USERNAME = "root";

    private const PASSWORD = "";

    private const DB_NAME = "agriturismo";

    private $conn;

    public function dbConnection()
    {
        $this->conn = mysqli_connect(static::HOST_DB, static::USERNAME, static::PASSWORD, static::DB_NAME);

        if (! $this->conn) {
            return false;
        } else {
            return true;
        }
    }

    public function verifyLogin($user, $psw)
    {
        $qre = 'SELECT * FROM utenti WHERE `Username` = "' . $user . '" AND `password` = "' . $psw . '"';
        $result = mysqli_query($this->conn, $qre);
        if (! $result) {
            return false;
        } else {
            return true;
        }
    }

    public function dbClose()
    {
        $this->conn->close();
    }
}
?>