<?php
//TODO:This is way is much better: get the device name and grab the device detail From db(I think it is more reliable)
if (Session::isAuthenticated()) {
    if(isset($_POST['name']) && (isset($_POST['button']) || isset($_POST['timer']) || isset($_POST['slider']) || isset($_POST['indicator']) || isset($_POST['display']) ) ){
        $deviceName = $_POST['name'];
    }
}

?>


<section class="d-flex justify-content-center align-items-center vh-100">
	<div class="row py-lg-5 w-75">
			<div class="col-lg-8 col-md-10 mx-auto p-4 border rounded-1 text-center shadow">
				<h1 class="fw-bold"><?=$deviceName;?></h1>
				<p class="lead text-primary">Your controls here...!</p>
                <?php 
                if($_POST['button'] == 1){
                    Session::loadTemplate('properties/button');
                }
                if($_POST['display'] == 1){
                    Session::loadTemplate('properties/display');
                }
                if($_POST['indicator'] == 1){
                    Session::loadTemplate('properties/indicator');
                }
                if($_POST['slider'] == 1){
                    Session::loadTemplate('properties/slider');
                }
                if($_POST['timer'] == 1){
                    Session::loadTemplate('properties/timer');
                }
                ?>
			</div>
	</div>
</section>