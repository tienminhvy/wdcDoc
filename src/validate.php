<?php 
    if(define('isSet', 1)){
        die('Direct access is not allowed!');
    }

    class userChecking
    {
        public function __construct($username, $email, $password)
        {
            $this->username = $username;
            $this->email = $email;
            $this->password = $password;
        }
        
        public function checkUsername()
        {
            if ($this->username == '') {
                $GLOBALS['errUsername'] = 'Username must be fill out!';
                return false;
            } elseif (strlen($this->username) > 50) {
                $GLOBALS['errUsername'] = 'Username must be less than 50 characters';
                return false;
            } else {return true;}
        }
        
        public function checkEmail()
        {
            if ($this->email == '') {
                $GLOBALS['errEmail'] = 'Email must be fill out!';
                return false;
            } elseif (strlen($this->email) > 50) {
                $GLOBALS['errEmail'] = 'Email must be less than 50 characters';
                return false;
            } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $GLOBALS['errEmail'] = 'Invalid email';
                return false;
            } else {return true;}
        }

        public function checkPassword()
        {
            if ($this->password == '') {
                $GLOBALS['errPassword'] = 'Password must be fill out!';
                return false;
            } elseif (strlen($this->password) > 50) {
                $GLOBALS['errPassword'] = 'Password must be less than 50 characters';
                return false;
            } else {return true;}
        }
        private function __createHashPW()
        {
            $hashed = password_hash($this->password, PASSWORD_BCRYPT);
            $this->hashed = $hashed;
        }
        public function getHashPW()
        {
            $this->__createHashPW();
            return $this->hashed;
        }
    }
?>