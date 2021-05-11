<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class DBAccess {
    private const HOST_DB = "127.0.0.1";
    private const USERNAME = "lbrescan";
    private const PASSWORD = "Eephejokohculee1";
    private const DB_NAME = "lbrescan";

    private $connection;

    public function openDBConnection() {
        $this->connection = new mysqli(DBAccess::HOST_DB, DBAccess::USERNAME, DBAccess::PASSWORD, DBAccess::DB_NAME);

        return $this->connection->connect_errno;
    }

    public function closeDBConnection() {
        $this->connection->close();
    }

    public function loginUser($username, $password) {
        $query = "SELECT `email` FROM `Users` WHERE `email` = \"$username\" AND `password` = \"$password\"";
        echo $query;
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
                "email" => $row["email"],
                "nome" => $row["nome"],
                "cognome" => $row["cognome"]
            );
        }
        return $user;
    }

    public function signupUser($name, $lastname, $email, $password) {
        $query = "INSERT INTO `Users` VALUES ($email, $password, $name, $lastname)";
        $this->connection->query($query); 
        return array(
            "isSuccessful" => $this->connection->affected_rows == 1,
            "userEmail" => $email
        );
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