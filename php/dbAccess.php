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

        return $this->connection->connect_errno ? false : true;
    }

    public function closeDBConnection() {
        $this->connection->close();
    }

    public function loginUser($email, $password) {
        $query = "SELECT `email` FROM `Users` WHERE `email` = ? AND `password` = ?";
        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

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
    } */
    
    
    // public function checkLogin($mail, $psw)
    // {
    //     $qre = 'SELECT * FROM utenti WHERE `email` = "' . $mail . '" AND `password` = "' . $psw . '"';
    //     $result = mysqli_query($this->connection, $qre);   
    //     return mysqli_fetch_assoc($result);
    // }
    
    // public function checkMail($mail){
    //     $qre = 'SELECT * FROM utenti WHERE `email` = "' . $mail. '"';
    //     $result = mysqli_query($this->connection, $qre);
    //     return (mysqli_num_rows($result) == 0) ? false : true;
    // }
    
    // public function insertUser($nome, $cogn, $mail, $pwd){
    //     $qre = 'INSERT INTO `utenti` (`nome`, `cognome`, `password`, `email`) VALUES ' . "('".$nome."', '".$cogn."', '".$mail."', '".$pwd."')";
    //     mysqli_query($this->connection, $qre);
    // }

    public function signupUser($name, $lastname, $email, $password) {
        $query = "INSERT INTO `Users` VALUES (?, ?, ?, ?)"; 
        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param("ssss", $email, $password, $name, $lastname);
        $stmt->execute();
        $result = $stmt->get_result();
        return array(
            "isSuccessful" => $this->connection->affected_rows == 1,
            "userEmail" => $email
        );
    }

    public function getCharacters() {
        $query = "SELECT * FROM protagonisti ORDER BY ID ASC;";
        $result = mysli_query($this->connection, $query);

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
    
    public function closeConnection(){
        $this->connection->close();
    }
}
?>