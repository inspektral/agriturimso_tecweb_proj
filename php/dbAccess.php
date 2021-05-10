<?php
class DBAccess {
    private const HOST_DB = "localhost";
    private const USERNAME = "lbrescan";
    private const PASSWORD = "Eephejokohculee1";
    private const DB_NAME = "lbrescan";

    private $connection;
    private $error = '';

    public function openDBConnection() {
        if (!($this->connection = new mysqli(self::HOST_DB, self::USERNAME, self::PASSWORD, self::DB_NAME))) {
            error_log("Debugging errno: " . $connection->connect_errno."Debugging error: " . $connection->connect_error);
            $this->error = "Momentaneamente i dati non sono disponibili. Riprovare più tardi.";
        }
    }

    public function getLastError() {
        return $this->error;
    }

    public function closeDBConnection() {
        $this->connection->close();
    }

    public function loginUser($username, $password) {
        $query = "SELECT `email`, `nome`, `cognome` FROM `Users` WHERE `email` = $username AND `password` = $password";
        $result = $this->connection->query($query);
        // $stmt = $this->connection->prepare($query);
        // if (!$stmt) {
        //     print_r($this->connection->error_list);
        // }
        // $stmt->bind_param("ss", "demo", "demo");
        // $stmt->execute();
        // $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            return null;
        }

        $user = array();
        while ($row = $result->fetch_assoc()) {
            $user = array(
                "username" => $row["email"],
                "nome" => $row["nome"],
                "cognome" => $row["cognome"]
            );
        }
        return $user;
    }

    public function getCharacters() {
        $query = "SELECT * FROM protagonisti ORDER BY ID ASC;";
        $result = mysli_query($connection, $query);

        if (mysql_num_rows($result) == 0) {
            return null;
        }

        $characters = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $character = array(
                "nome" => $row["Nome"],
                "img" => $row["NomeImmagine"],
                "alt" => $row["AltImmagine"],
                "desc" => $row["Descrizione"]
            );

            array_push($characters, $character);
        }
        return $characters;
    }
}
?>