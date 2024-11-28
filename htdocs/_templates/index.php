<?php

if (Session::isAuthenticated()) {
    Session::loadTemplate('index/calltoaction');
} else {
    Session::loadTemplate('index/welcome');
}
