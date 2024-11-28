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
print_r($result);
?>
</pre>
