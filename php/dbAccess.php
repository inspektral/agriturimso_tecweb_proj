<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
class DBAccess {
    private const HOST_DB = "localhost";
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
            $room["name"] = $row["name"];
            $room["people"] = $row["people"];
            $room["price"] = $row["price"];
            $room["meters"] = $row["meters"];
            $room["mainImg"] = $row["mainImg"];
            $room["mainImgLongdesc"] = $row["mainImgLongdesc"];
            $room["firstGallery"] = $row["firstGallery"];
            $room["secondGallery"] = $row["secondGallery"];
            $room["thirdGallery"] = $row["thirdGallery"];
            $room["fourthGallery"] = $row["fourthGallery"];
            $room["services"] = [
                "tv"=>$row["tv"],"balcony"=>$row["balcony"],"gardenView"=>$row["gardenView"],"airCondition"=>$row["airCondition"],"heat"=>$row["heat"],"parquet"=>$row["parquet"],
                "shower"=>$row["shower"],"shampoo"=>$row["shampoo"],"wc"=>$row["wc"],"bath"=>$row["bath"],"bidet"=>$row["bidet"],"paper"=>$row["paper"],"towels"=>$row["towels"],
                "wardrobe"=>$row["wardrobe"]
            ];
            $room["additionalServices"] = [
                "parking"=>$row["parking"],"wifi"=>$row["wifi"],"privateBathRoom"=>$row["privateBathRoom"]
            ];

            array_push($rooms, $room);
        }
        return $rooms;
    }

    public function addRoom($name,$people,$price,$meters,$mainImg,$mainImgLongdesc,$first,$second,$third,$fourth,$services,$additionalServices) {
        $query = "INSERT INTO `Rooms` (`name`,`people`,`price`,`meters`,`mainImg`,`mainImgLongdesc`,`firstGallery`,`secondGallery`,`thirdGallery`,`fourthGallery`,`tv`,`balcony`,`gardenView`,
            `airCondition`,`heat`,`parquet`,`shower`,`shampoo`,`wc`,`bath`,`bidet`,`paper`,`towels`,`wardrobe`,`parking`,`wifi`,`privateBathRoom`) 
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param("sidissssssiiiiiiiiiiiiiiiii", $name,$people,$price,$meters,$mainImg,$mainImgLongdesc,$first,$second,$third,$fourth,$services["tv"],$services["balcony"],
            $services["gardenView"],$services["airCondition"],$services["heat"],$services["parquet"],$services["shower"],$services["shampoo"],$services["wc"],$services["bath"],
            $services["bidet"],$services["paper"],$services["towels"],$services["wardrobe"],$additionalServices["parking"],$additionalServices["wifi"],
            $additionalServices["privateBathRoom"]
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
    
    public function isFree($dateFrom, $dateTo, $nameRoom) {

        $t1=  $dateFrom."";
        $t2=  $dateTo."";

        echo "sono qui " . $t1 . " - ".$t2 . "<br/>"; 

        $tmp1 = DateTime::createFromFormat('d/m/Y', $t1)->format('Y-m-d');
        $tmp2 = DateTime::createFromFormat('d/m/Y', $t2)->format('Y-m-d');

        echo $tmp1 . " - ".$tmp2 . "<br/>";
        $query = "SELECT * FROM `prenotazioni` WHERE `giornoA` >= ? AND `giornoDa` <= ? AND `nameCamera` = ?";   
        
        $stmt = $this->connection->prepare($query);
        if (!$stmt) {
            return null;
        }
        $stmt->bind_param("sss", $tmp1, $tmp2, $nameRoom);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows <= 0) {
            return true;
        }else{
            return false;
        }
    }

    public function closeConnection(){
        $this->connection->close();
    }
}
?>