<?php

class UserSession
{
    private $conn;
    public $token;
    public $data;
    public $uid;
    /**
     * This function will return a session ID if username and password is correct.
     *
     * @return SessionID
     */
    public static function authenticate($user, $pass)
    {
        $username = User::login($user, $pass);
        if ($username) {
            $userd = new User($user);
            $conn = Database::getConnection();
            $ip = $_SERVER['REMOTE_ADDR'];
            $agent = $_SERVER['HTTP_USER_AGENT'];
            $fingerprint = $_POST['fingerprint'];
            $token = md5(rand(0, 9999999) . $ip . $agent . time());
            $sql = "INSERT INTO `session` (`uid`, `token`, `login_time`, `ip`, `user_agent`, `active`, `fingerprint`)
            VALUES ('$userd->id', '$token', now(), '$ip', '$agent', '1', '$fingerprint')";
            if ($conn->query($sql)) {
                Session::set('session_token', $token);
                Session::set('fingerprint', $fingerprint);
                return $token;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /*
    * Authorize function have has 4 level of checks
        1.Check that the IP and User agent field is filled.
        2.Check if the session is correct and active.
        3.Check that the current IP is the same as the previous IP
        4.Check that the current user agent is the same as the previous user agent

        @return true else false;
    */
    public static function authorize($token)
    {
        try {
            $session = new UserSession($token);
            if (isset($_SERVER['REMOTE_ADDR']) and isset($_SERVER["HTTP_USER_AGENT"])) {
                if ($session->isValid() and $session->isActive()) {
                    if ($_SERVER['REMOTE_ADDR'] == $session->getIP()) {
                        if ($_SERVER['HTTP_USER_AGENT'] == $session->getUserAgent()) {
                            if ($session->getFingerprint() == $_SESSION['fingerprint']) {
                                Session::$user = $session->getUser();
                                return $session;
                            } else {
                                throw new Exception("FingerPrint doesn't match");
                            }
                        } else {
                            throw new Exception("User agent does't match");
                        }
                    } else {
                        throw new Exception("IP does't match");
                    }
                } else {
                    $session->removeSession();
                    throw new Exception("Invalid session");
                }
            } else {
                throw new Exception("IP and User_agent is null");
            }
        } catch (Exception $e) {
            throw new Exception("Something is wrong");
        }
    }


    /**
     * Cunstruct a user session with the given token
     *
     * @param SessionToken $token
     */
    public function __construct($token)
    {
        $this->conn = Database::getConnection();
        $this->token = $token;
        $this->data = null;
        $sql = "SELECT * FROM `session` WHERE `token`='$token' LIMIT 1";
        $result = $this->conn->query($sql);
        if ($result->num_rows) {
            $row = $result->fetch_assoc();
            $this->data = $row;
            $this->uid = $row['uid']; //Updating this from database
        } else {
            throw new Exception("Session is invalid.");
        }
    }

    public function getUser()
    {
        return new User($this->uid);
    }

    /**
     * Check if the validity of the session is within one hour, else it inactive.
     *
     * @return boolean
     */
    public function isValid()
    {
        if (isset($this->data['login_time'])) {
            $login_time = DateTime::createFromFormat('Y-m-d H:i:s', $this->data['login_time']);
            if (3600 > time() - $login_time->getTimestamp()) {
                return true;
            } else {
                return false;
            }
        } else {
            throw new Exception("login tiem is null");
        }
    }

    public function getIP()
    {
        return isset($this->data["ip"]) ? $this->data["ip"] : false;
    }

    public function getUserAgent()
    {
        return isset($this->data["user_agent"]) ? $this->data["user_agent"] : false;
    }

    public function deactivate()
    {
        if (!$this->conn) {
            $this->conn = Database::getConnection();
        }
        $sql = "UPDATE `session` SET `active` = 0 WHERE `uid`=$this->uid";

        return $this->conn->query($sql) ? true : false;
    }

    public function isActive()
    {
        if (isset($this->data['active'])) {
            return $this->data['active'] ? true : false;
        }
    }

    public function getFingerprint()
    {
        if (isset($this->data['fingerprint'])) {
            return $this->data['fingerprint'] ? true : false;
        }
    }

    //This function remove current session
    public function removeSession()
    {
        if (isset($this->data['id'])) {
            $id = $this->data['id'];
            if (!$this->conn) {
                $this->conn = Database::getConnection();
            }
            $sql = "DELETE FROM `session` WHERE `uid` = $id";
            if ($this->conn->query($sql)) {
                return true;
            } else {
                return false;
            }
        }
    }
}
