<?php 
class UserMenu {
    private $initialUl = "<div id=\"user\"><ul>";
    private $finalUl = "</ul></div>";
    private $logout = "<li><a href=\"logout.php\" class='reg'>Logout</a></li>";
    private $welcomeMessage = "<li><p class=\"reg\">Benvenuto: <EmailPlaceholder /></p></li>";
    private $signupLink = "<li><a href=\"signup.php\" class=\"reg\">Registrati</a></li>";
    private $loginLink = "<li><a href=\"login.php\" class=\"reg\">Accedi</a></li>";
    private $signupItem = "<li id=\"currentLink\">Registrati</li>";
    private $loginItem = "<li id=\"currentLink\">Accedi</li>";


    public function getWelcomeMessage($email) {
        $this->welcomeMessage = str_replace("<EmailPlaceholder />", $email, $this->welcomeMessage);
        return $this->initialUl.$this->welcomeMessage.$this->logout.$this->finalUl;
    }

    public function getAuthenticationButtons($isSignup = false, $isLogin = false) {
        $signup = $isSignup ? $this->signupItem : $this->signupLink;
        $login = $isLogin ? $this->loginItem : $this->loginLink;
        return $this->initialUl.$signup.$login.$this->finalUl;
    }
}
?>