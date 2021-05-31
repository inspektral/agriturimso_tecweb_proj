<?php 
class UserMenu {
    private $initialUl = "<div id=\"user\"><ul>";
    private $finalUl = "</ul></div>";
    private $logout = "<li><a href=\"logout.php\" class='reg'>Logout</a></li>";
    private $welcomeMessage = "<li class='userName'><p>Benvenuto: <EmailPlaceholder /></p></li>";
    private $signupLink = "<li><a href=\"signup.php\" class=\"reg\">Registrati</a></li>";
    private $loginLink = "<li><a href=\"login.php\" class=\"reg\">Accedi</a></li>";
    
    private $signupItem = "<li id=\"currentLink\">Registrati</li>";
    private $loginItem = "<li id=\"currentLink\">Accedi</li>";
    
    private $signupLinkOtherFolder = "<li><a href=\"..\signup.php\" class=\"reg\">Registrati</a></li>";
    private $loginLinkOtherFolder = "<li><a href=\"..\login.php\" class=\"reg\">Accedi</a></li>";


    public function getWelcomeMessage($email) {
        $this->welcomeMessage = str_replace("<EmailPlaceholder />", $email, $this->welcomeMessage);
        return $this->initialUl.$this->welcomeMessage.$this->logout.$this->finalUl;
    }

    public function getAuthenticationButtons($isOtherFolder = false, $isSignup = false, $isLogin = false) {
        $signup = $isSignup ? $this->signupItem : $this->signupLink;
        $login = $isLogin ? $this->loginItem : $this->loginLink;
        
        $signup = $isOtherFolder ? $this->signupLinkOtherFolder : $signup;
        $login = $isOtherFolder ? $this->loginLinkOtherFolder : $login;
        
        return $this->initialUl.$signup.$login.$this->finalUl;
    }
}
?>