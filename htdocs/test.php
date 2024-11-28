<pre>
<?php
session_start();

include_once 'libs/includes/pc.class.php';
include_once 'libs/load.php';
print_r($_SERVER);
print($_SERVER['REQUEST_URI']);
print_r($_GET);
print_r($_POST);
print_r($_COOKIE);
// $path = $_SERVER['DOCUMENT_ROOT'] . get_config('base_path'). "/_templates/$name.php";
$__site_config_path = dirname(is_link($_SERVER['DOCUMENT_ROOT']) ? readlink($_SERVER['DOCUMENT_ROOT']) : $_SERVER['DOCUMENT_ROOT']).'/../projects/photogramconfig.json';

printf($path);
printf($__site_config_path);


// if(signup("jeromel", "9993949393", "jeromeal@gam.com", "password")==true) {
//     echo "success";
// } else {
//     echo "Fail";

// }


// pc::testfuction();

// if (signup("jeromelss", "jplojadlj;fld", "pushparajff@gmail.com", "7894561231")) {
//     echo "Success";
// } else {
//     echo "Fail";
// }
// $desktop->getvoltage("650 W",array(1,3,4,5,6,7,6,8));

$p = new Post(1);
print($p->getPostText());

?>
