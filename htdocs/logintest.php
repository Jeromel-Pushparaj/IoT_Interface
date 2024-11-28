<?php

//this below code is a simple prototype how the login was saved in to the session id. without have in the login page.
include '_libs/load.php';

$user = "jeromel";
$pass = isset($_GET['pass']) ? $_GET['pass'] : "";
$result = null;

if(isset($_GET['logout'])) {
    Session::destroy();
    die("Session destroyed, <a href='logintest.php'>Login Again</a>");
}
/*
1. Check if session_token in PHP session is available
2. If yes, construct UserSession and see if its successful.
3. Check if the session is valid one
4. If valid, print "Session validated"
5. Else, print "Invlaid Session" and ask user to login.
*/


if (Session::get('is_loggedin')) {
    $username = Session::get('session_username');
    $userobj = new User($username);
    print("Welcome Back " . $userobj->getFirstname());
    //print("<br>".$userobj->getBio());
    print("<br>" . $userobj->getBio());
    $userobj->setBio("Making new things...");
    $userobj->setModel("Human");
//$userobj->thisIsNotAFunction();
//print("<br>".$userobj->getBio());

} else {
    printf("No session found, trying to login now. <br>");
    $result = User::login($user, $pass);

    if ($result) {
        $userobj = new User($user);
        echo "Login Success ", $userobj->getUsername();
        Session::set('is_loggedin', true);
        Session::set('session_username', $result);
    } else {
        echo "Login failed, $user <br>";
    }
}


echo <<<EOL
<br><br><a href="logintest.php?logout">Loginout</a>
EOL;
