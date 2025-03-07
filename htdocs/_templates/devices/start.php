<?php
if(isset($_GET['delete'])){
    $deviceno = $_GET['delete'];
    if(!Device::deleteDevice($deviceno)){
        header("location: /device");
        die();
    }

}
?>
<section class="d-flex justify-content-center align-items-center vh-100">
    <div>
        <h1 class="mb-4">Do Something Great With Your Device!</h1> 
        <a href="create" class="btn btn-dark my-2">Add Device</a>
        <div class="d-flex flex-row">
            <?php
            $devicenames = Device::getDevice()->getdevicename();
            $devicedesc = Device::getDevice()->getdesc();
            $devicenum = Device::getDevice()->getdeviceno();
            $deviceid = Device::getDevice()->getdeviceid();
            $n = Device::numOfDevice();
            // print($devicedesc . " " . $devicenames);
            for ($i = 0; $i < $n; $i++) {
                
            ?>
            <div class="card shadow m-2" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title"><?php 
                    if(is_string($devicenames)){
                        echo htmlspecialchars($devicenames);
                    }else{
                        echo htmlspecialchars($devicenames[$i]);
                    }
                    ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted">About Machine</h6>
                    <p class="card-text"><?php 
                     
                    if(is_string($devicedesc)){
                        echo htmlspecialchars($devicedesc);
                    }else{
                        echo htmlspecialchars($devicedesc[$i]);
                    }
                    ?></p>
                    <a href="#" class="btn btn-dark my-2"><i class="bi bi-pencil"></i></a>
                    <a href="/device?delete=<?php echo $devicenum[$i]; ?>" class="btn btn-dark my-2"><i class="bi bi-trash"></i></a>
                    <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#infoModal<?php echo $i; ?>">Info</button>
                </div>
            </div>

            <!-- Modal for each machine -->
            <div class="modal fade" id="infoModal<?php echo $i; ?>" tabindex="-1" aria-labelledby="infoModalLabel<?php echo $i; ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="infoModalLabel<?php echo $i; ?>">
                            <?php 
                                if(is_string($devicenames)){
                                    echo htmlspecialchars($devicenames);
                                }else{
                                    echo htmlspecialchars($devicenames[$i]);
                                }
                            ?>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="model-body">
                        <div class="container">
                            <!-- TODO: Fix Copy to clipboard of this device key  -->
                            <input type="text" id="textbox" value="<?php echo is_int($deviceid) ?  Devicekey::getDevice_key()->getkey($deviceid) : Devicekey::getDevice_key()->getkey($deviceid[$i]) ;?>" readonly />
                            <button class="copy-btn" onclick="copyText()"><i class="bi bi-copy"></i></button>
                        </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
</section>
