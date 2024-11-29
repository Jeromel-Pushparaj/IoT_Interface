<section class="d-flex justify-content-center align-items-center vh-100">
    <div>
        <h1 class="mb-4">Device Control</h1> 
        <div class="d-flex flex-row">
            <?php
            $devicenames = Device::getDevice()->getdevicename();
            $devicedesc = Device::getDevice()->getdesc();
            $devicenum = Device::getDevice()->getdeviceno();
            $n = Device::numOfDevice();
            

            for ($i = 0; $i < $n; $i++) {
                $formid = "devicename" . $i;

            ?>
            <form id="<?= $formid; ?>" action="properties.php" method="POST">
            <div class="card shadow m-2" style="width: 18rem; cursor:pointer;" onclick="submitForm('<?= $formid; ?>')">
                <div class="card-body">
                    <h5 class="card-title">
                    <?php 
                    if(is_string($devicenames)){
                        echo $devicenames;
                        ?>
                        <input type="hidden" name="name" value="<?php echo $devicenames; ?>" >
                        <?php
                    }else{
                        echo $devicenames[$i];
                        ?>
                        <input type="hidden" name="name" value="<?php echo $devicenames[$i]; ?>" >
                        <?php
                    }
                    ?>
                    </h5>
                    <h6 class="card-subtitle mb-2 text-muted">About Device</h6>
                    <p class="card-text">
                    <?php 
                     
                     if(is_string($devicedesc)){
                         echo $devicedesc;
                     }else{
                         echo $devicedesc[$i];
                     }
                     ?>
                    </p>
                    
                    <?php 
                    $properties = Device::deviceProperties($devicenames[$i]);
                    
                    if(in_array("button", $properties)){?>
                        <a class="btn btn-dark my-2"><i class="bi bi-toggles"></i></a>
                        <input type="hidden" name="button" value="1">
                    <?php } ?>

                    <?php if(in_array("timer", $properties)){?>
                    <a class="btn btn-dark my-2"><i class="bi bi-alarm"></i></a>
                    <input type="hidden" name="timer" value="1">
                    <?php } ?>
                    <?php if(in_array("indicator", $properties)){ ?>
                    <a class="btn btn-dark my-2"><i class="bi bi-lightbulb"></i></a>
                    <input type="hidden"  name="indicator" value="1">
                    <?php } ?>

                    <?php if(in_array("slider", $properties)){?>
                    <a class="btn btn-dark my-2"><i class="bi bi-sliders"></i></a>
                    <input type="hidden" name="slider" value="1">
                    <?php } ?>
                    <?php if(in_array("display", $properties)){?>
                    <a class="btn btn-dark my-2"><i class="bi bi-display"></i></a>
                    <input type="hidden"  name="display" value="1">
                    <?php } ?>
                </div>
            </div>
            </form>
            <?php
            }
            ?>
        </div>
    </div>
    <script>
        function submitForm(formId) {
            document.getElementById(formId).submit();
        }
</script>
</section>


