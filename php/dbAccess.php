<?php
class DBAccess {
    private const HOST_DB = "localhost";
    private const USERNAME = "lbrescan";
    private const PASSWORD = "Eephejokohculee1";
    private const DB_NAME = "lbrescan";

    private $connection;

    public function openDBConnection() {
        $this->connection = mysqli_connect(static::HOST_DB, static::USERNAME, static::PASSWORD, static::DB_NAME);
        
        return (!$this->connection) ? false : true;
    }

    public function closeDBConnection() {
        mysqli_close($this->connection);
    }

   /*  public function loginUser($username, $password) {
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
    } */
    
    
    public function checkLogin($mail, $psw)
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