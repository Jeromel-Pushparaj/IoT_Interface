<?php
if(isset($_GET['genkey'])){
        $result = ApiKey::getApikey()->generateKey();
}
Session::loadTemplate('profile/apikey');
Session::loadTemplate('profile/sidebar')

?>