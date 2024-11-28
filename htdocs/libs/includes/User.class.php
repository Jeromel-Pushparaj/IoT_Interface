<?php

require_once "Database.class.php";
include_once __DIR__ . "/../traits/SQLGetterSetter.trait.php";
class User
{
    private $conn;
    public $id;
    public $username;
    public $table;
    public function __call($name, $arguments)
    {
        $property = preg_replace("/[^0-9a-zA-Z]/", "", substr($name, 3));
        $property = strtolower(preg_replace('/\B([A-Z])/', '_$1', $property));
        if (substr($name, 0, 3) == "get") {
            return $this->_get_data($property);
        } elseif (substr($name, 0, 3) == "set") {
            return $this->_set_data($property, $arguments[0]);
        } else {
            throw new Exception("User::__call() -> $name, function unavailable.");
        }

    }
    use SQLGetterSetter;
    /**
     * Undocumented function
     *
     * @param [type] $user
     * @param [type] $pass
     * @param [type] $email
     * @param [type] $phone
     * @return void
     */
    public static function signup($user, $pass, $email, $phone)
    {
        $options = [
            'cost' => 9,
        ];
        $pass = password_hash($pass, PASSWORD_BCRYPT, $options);
        $conn = Database::getConnection();

        // Trim the phone number to remove leading/trailing spaces
        $phone = trim($phone);
        // $pass = md5($pass);


        $sql = $conn->prepare("INSERT INTO `auth` (`username`, `password`, `email`, `phone`, `blocked`, `active`)
        VALUES (?, ?, ?, ?, '0', '1');");

        if (!$sql) {
            return "Error in preparing SQL statement: " . $conn->error;
        }

        // Bind the parameters
        $sql->bind_param("ssss", $user, $pass, $email, $phone);
        // Before the bind_param statement

        // The bind_param statement
        $sql->bind_param("ssss", $user, $pass, $email, $phone);


        $error = false;
        try {
            // Execute the INSERT query
            if ($sql->execute() === true) {
                $error = false;
            } else {
                // Handle the duplicate entry error
                if ($conn->errno === 1062) {
                    $error = "Username already taken. Please choose a different username.";
                } else {
                    $error = $sql->error;
                }
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        $sql->close();
        $conn->close();
        return $error;
    }
    public static function login($user, $pass)
    {
        
        $conn = Database::getConnection();

        // Prepare the SQL statement with a placeholder for the username
        $sql = $conn->prepare("SELECT * FROM `auth` WHERE `username` = ?");
        
        if (!$sql) {
            return "Error in preparing SQL statement: " . $conn->error;
        }
    
        // Bind the username parameter
        $sql->bind_param("s", $user);
    
        // Execute the query
        $sql->execute();
    
        // Fetch the result
        $result = $sql->get_result();
    
        // Check if the username exists and if the password matches
        if($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if(password_verify($pass, $row['password'])) {
                /*
                1. Generate Session Token
                2. Insert Session Token
                3. Build session and give session to user.
                */
                // $id = $row['id'];
                return true;
            } else {
                return false; // Password incorrect
            }
        } else {
            return false; // User not found
        }
    
        $sql->close();
        $conn->close();
    }
    
//User object can be constructed with either UserID or Username.
public function __construct($username)
{
    // Initialize the database connection
    $this->conn = Database::getConnection();
    $this->table = 'auth';
    // Sanitize the input
    $username = trim($username);
    // Check if $username is numeric, indicating it's an ID, otherwise treat it as a username
    if (is_numeric($username)) {
        // Search by user ID
        $sql = "SELECT `id`, `username` FROM `auth` WHERE `id` = ? LIMIT 1";
    } else {
        // Search by username
        $sql = "SELECT `id`, `username` FROM `auth` WHERE `username` = ? LIMIT 1";
    }
    // Prepare the statement
    $stmt = $this->conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Error preparing SQL statement: " . $this->conn->error);
    }
    // Bind the parameter
    $stmt->bind_param("s", $username);
    // Execute the statement
    $stmt->execute();
    // Fetch the result
    $result = $stmt->get_result();
    // Check if a user is found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Assign the fetched values to the object properties
        $this->id = $row['id'];
        $this->username = $row['username']; // Can be useful if fetched by ID
    } else {
        // User not found
        throw new Exception("Username or ID doesn't exist.");
    }
    // Close statement and connection
    $stmt->close();
}


   
    public function setDob($year, $month, $day)
    {
        if (checkdate($month, $day, $year)) { //checking data is valid
            return $this->_set_data('dob', "$year.$month.$day");
        } else {
            return false;
        }
    }

    // public function getUsername()
    // {
    //     return $this->username;
    // }


    //     public function authenticate() {}

    //     public function setBio($bio)
    //     {
    //         //TODO: Write UPDATE command to change new bio
    //         return $this->setData('bio', $bio);
    //     }

    //     public function getBio()
    //     {
    //         //TODO: Write SELECT command to get the bio.
    //         return $this->getData('bio');
    //     }

    //     public function setAvatar($link)
    //     {
    //         return $this->setData('avatar', $link);
    //     }

    //     public function getAvatar()
    //     {
    //         return $this->getData('avatar');
    //     }

    //     public function setFirstname($name)
    //     {
    //         return $this->setData("firstname", $name);
    //     }

    //     public function getFirstname()
    //     {
    //         return $this->getData('firstname');
    //     }

    //     public function setLastname($name)
    //     {
    //         return $this->setData("lastname", $name);
    //     }

    //     public function getLastname()
    //     {
    //         return $this->getData('lastname');
    //     }



    //     public function getDob()
    //     {
    //         return $this->getData('dob');
    //     }

    //     public function setInstagramlink($link)
    //     {
    //         return $this->setData('instagram', $link);
    //     }

    //     public function getInstagramlink()
    //     {
    //         return $this->getData('instagram');
    //     }

    //     public function setTwitterlink($link)
    //     {
    //         return $this->setData('twitter', $link);
    //     }

    //     public function getTwitterlink()
    //     {
    //         return $this->getData('twitter');
    //     }
    //     public function setFacebooklink($link)
    //     {
    //         return $this->setData('facebook', $link);
    //     }

    //     public function getFacebooklink()
    //     {
    //         return $this->getData('facebook');
    //     }
}
