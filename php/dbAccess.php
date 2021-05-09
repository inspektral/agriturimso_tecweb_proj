<?php
class DBAccess {
    private const HOST_DB = "localhost";
    private const USERNAME = "lbrescan";
    private const PASSWORD = "Eephejokohculee1";
    private const DB_NAME = "lbrescan";

    private $connection;

    public function openDBConnection() {
        $connection = mysqli_connect(HOST_DB, USERNAME, PASSWORD, DB_NAME);
        
        return mysqli_connection_errno($connection) ? false : true;
    }

    public function closeDBConnection() {

    }

    public function loginUser($username, $password) {
        $query = 'SELECT `email`, `nome`, `cognome` FROM users WHERE `email` = "' . $username . '" AND `password` = "' . $password . '"';
        $result = mysli_query($connection, $query);

        if (mysql_num_rows($result) == 0) {
            return null;
        }

        $user = array(
            "email" => "",
            "nome" => "",
            "cognome" => ""
        );
        while ($row = mysqli_fetch_assoc($result)) {
            $user["email"] = $row["email"];
            $user["nome"] = $row["nome"];
            $user["cognome"] = $row["cognome"];
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