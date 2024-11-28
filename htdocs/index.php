<?php

include 'libs/load.php';


if (isset($_GET['logout'])) {
    if (Session::isset("session_token")) {
        $Session = new UserSession(Session::get("session_token"));
        if ($Session->removeSession()) {
            echo "<h3> Pervious Session is removing from db </h3>";
        } else {
            echo "<h3>Pervious Session not removing from db </h3>";
        }
    }
    Session::destroy();
    header("Location: /");
    die();
} else {
    Session::renderPage();
}

