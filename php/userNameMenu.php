<?php 

class userNameMenu{
    private $strAccedi = '<ul><li><a href="accedi.php" class="reg">Accedi</a></li><li><a href="registrati.php" class="reg">Registrati</a></li></ul>';

    public function getAccedi($isReg = false){        
        if(!$isReg){
            return  $this->strAccedi;
        }else{
            return '<ul><li><a href="accedi.php" class="reg">Accedi</a></li></ul>';
        }
    }
    
    public function loginSucc(){
        $this->strAccedi = "<ul><li class='userName'><p>Benvenuto: " . $_SESSION["user"] . "</p></li><li><a href='php/logout.php' class='reg'>Logout</a></li></ul>";
        return $this->getAccedi();
    }
    
}
?>