<?php
// include 'libs/include/Device.class.php';

if (Session::isAuthenticated()) {
if(isset($_POST['devicename']) && (isset($_POST['ToggleButton']) || isset($_POST['Slider']) || isset($_POST['Display']) || isset($_POST['Indicator']) || isset($_POST['Timer'])) && isset($_POST['description'])){
  $parameters = ['ToggleButton', 'Slider', 'Display', 'Indicator', 'Timer'];
  $passedParams = [
    'ToggleButton' => 0,
    'Slider' => 0,      
    'Display' => 0,
    'Indicator' => 0,
    'Timer' => 0
  ];

  foreach ($parameters as $param) {
      if (isset($_POST[$param])) {
          $passedParams[$param] = $_POST[$param];
      }
  }

  $result = Device::addDevice($_POST['devicename'], $_POST['description'], $passedParams['ToggleButton'], $passedParams['Slider'], $passedParams['Display'], $passedParams['Indicator'], $passedParams['Timer']);
  if(!$result){
   header("Location: /device.php");
   die();
  }else{
   printf("Error creating device");
  }
  print($result);
}

Session::loadTemplate("create/sidebar");
Session::loadTemplate("create/main");

}
?>




