<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
// error_reporting(E_ALL);

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

    public function loginUser($email, $password) {
        $query = "SELECT `email`,`nome`,`cognome` FROM `Users` WHERE `email` = ? AND `password` = ?";
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
    }
    
    public function signupUser($name, $lastname, $email, $password) {
        $query = "INSERT INTO `Users` (`nome`, `cognome`, `email`, `password`) VALUES (?, ?, ?, ?)";
        $stmt = $this->connection->prepare($query);

        if (!$stmt) {
            return null;
        }
        $stmt->bind_param("ssss", $name, $lastname, $email, $password);
        $stmt->execute();

        return array(
            "isSuccessful" => $stmt->affected_rows === 1,
            "userEmail" => $email
        );
    }

    public function getNews() {
        $query = "SELECT * FROM `News` ORDER BY `date` DESC LIMIT 5;";
        $result = $this->connection->query($query);

        if ($result->num_rows === 0) {
            return null;
        }

        $news = array();
        while($row = $result->fetch_assoc()) {
            $item = array(
                "date" => $row["date"],
                "description" => $row["description"]
            );
            array_push($news, $item);
        }
        return $news;
    }

    public function addNews($description) {
        $query = "INSERT INTO `News` (`date`,`description`) VALUES (CURRENT_TIMESTAMP(), ?);";
        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param("s", $description);
        $stmt->execute();

        return array(
            "isSuccessful" => $stmt->affected_rows === 1
        );
    }

    public function getRooms() {
        $query = "SELECT * FROM `Rooms`;";
        $result = $this->connection->query($query);

        if ($result->num_rows === 0) {
            return null;
        }

        $rooms = array();
        while($row = $result->fetch_assoc()) {
            $room = array();
            foreach ($row as $key => $value) {
                $room[$key] = $value;
            }
            array_push($rooms, $room);
        }
        return $rooms;
    }

    public function addRoom($name,$people,$price,$mainImg,$mainImgLongdesc,$first,$second,$third,$fourth,$services) {
        $query = "INSERT INTO `Rooms` (`name`,`people`,`price`,`mainImg`,`mainImgLongdesc`,`firstGallery`,`secondGallery`,`thirdGallery`,`fourthGallery`,`tv`,`balcony`,`gardenView`,
            `airCondition`,`heat`,`parquet`,`shower`,`shampoo`,`wc`,`bath`,`bidet`,`paper`,`towels`,`wardrobe`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param("sidssssssiiiiiiiiiiiiii", $name,$people,$price,$mainImg,$mainImgLongdesc,$first,$second,$third,$fourth,$services["tv"],$services["balcony"],
            $services["gardenView"],$services["airCondition"],$services["heat"],$services["parquet"],$services["shower"],$services["shampoo"],$services["wc"],$services["bath"],
            $services["bidet"],$services["paper"],$services["towels"],$services["wardrobe"]
        );
        $stmt->execute();

        return array(
            "isSuccessful" => $stmt->affected_rows === 1
        );
    }

    public function removeRoom($name) {
        $query = "DELETE FROM `Rooms` WHERE `name` = ?;";
        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param("s", $name);
        $stmt->execute();

        return array(
            "isSuccessful" => $stmt->affected_rows === 1
        );
    }

    public function setComments($email, $testo, $voto){
        $query= "INSERT INTO `Recensioni` (`email`, `testo`,`timestamp`,`voto`) VALUES (?, ?, CURRENT_TIMESTAMP(), ?);";
        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            
            return null;
        } 
        $stmt->bind_param("sss", $email, $testo, $voto);
        $stmt->execute();
        return array(
            "isSuccessful" => $stmt->affected_rows === 1
        );
    }


    public function getComments(){
        $query = "SELECT * FROM `Recensioni` ORDER BY `timestamp`;";
        $result = $this->connection->query($query);

        if ($result->num_rows === 0) {
            return null;
        }
        $commenti = array();
        while($row = $result->fetch_assoc()) {
            $item = array(
                "email" => $row["email"],
                "testo" => $row["testo"],
                "timestamp" => $row["timestamp"],
                "voto" => $row["voto"],

            );
            array_push($commenti, $item);
        }
        return $commenti;

    }


    public function deleteComment($email, $timestamp) {
        $query = "DELETE FROM `Recensioni` WHERE `email` = ? AND `timestamp` = ? ;";
        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param("ss", $email, $timestamp);
        $stmt->execute();

        return array(
            "isSuccessful" => $stmt->affected_rows === 1
        );
    }
    
    public function isFree($dateFrom, $dateTo) {
        $query = "SELECT * FROM `prenotazioni` WHERE `giornoDa` <= ? AND `giornoA` >= ?";   
        
        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param("ss", $dateFrom, $dateTo);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 0) {
            return true;
        }else{
            return false;
        }
    }
    
    public function prenotaCamera($user, $dateFrom, $dateTo, $camera) {
        
        $query = "INSERT INTO `prenotazioni` (`email`, `giornoDa`, `giornoA`, `camera`) VALUES ('?', '?', '?', '?');";
        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            return null;
        }
        //$stmt->bind_param("ssss", $description);
        $stmt->execute();
        
        return array(
            "isSuccessful" => $stmt->affected_rows === 1
        );
    }

    public function closeConnection(){
        $this->connection->close();
    }
}
?>