<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

class DBAccess {
    private const HOST_DB = "localhost";
    private const USERNAME = "root";
    private const PASSWORD = "";
    private const DB_NAME = "agriturismo";
    /*
    private const HOST_DB = "127.0.0.1";
    private const USERNAME = "lbrescan";
    private const PASSWORD = "Eephejokohculee1";
    private const DB_NAME = "lbrescan";*/

    private $connection;

    public function openDBConnection() {
        $this->connection = @new mysqli(DBAccess::HOST_DB, DBAccess::USERNAME, DBAccess::PASSWORD, DBAccess::DB_NAME);
        return $this->connection->connect_errno;
    }

    public function closeDBConnection() {
        $this->connection->close();
    }

    /*
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
    
    
    public function loginUser($mail, $psw)
    {
        $qre = 'SELECT * FROM Users WHERE `email` = "' . $mail . '" AND `password` = "' . $psw . '"';
        $result = mysqli_query($this->connection, $qre);   
        return mysqli_fetch_assoc($result);
    }
    
    public function checkMail($mail){
        $qre = 'SELECT * FROM Users WHERE `email` = "' . $mail. '"';
        $result = mysqli_query($this->connection, $qre);
        return (mysqli_num_rows($result) == 0) ? false : true;
    }
    
    public function insertUser($nome, $cogn, $mail, $pwd){
        $qre = 'INSERT INTO `Users` (`nome`, `cognome`, `password`, `email`) VALUES ' . "('".$nome."', '".$cogn."', '".$mail."', '".$pwd."')";
        mysqli_query($this->connection, $qre);
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