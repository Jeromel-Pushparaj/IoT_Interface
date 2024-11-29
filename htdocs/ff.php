<?php
include 'libs/load.php';

// function hello($n, $no=0){
//   print($no . $n);
// }

// $n;

// hello(2, $n);

$result = Device::deviceProperties('light');
?>
<pre>
<?php
$s = $_SERVER['DOCUMENT_ROOT']."/js/".basename($_SERVER['PHP_SELF'], '.php').".js";
$ss = "/js/" . basename($_SERVER['PHP_SELF'], '.php').".js";
$sss = $_SERVER['DOCUMENT_ROOT']."/css/".basename($_SERVER['PHP_SELF'], '.php').".css";
print_r($sss);
?>
</pre>
