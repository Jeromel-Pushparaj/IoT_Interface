<?php
//TODO:This is way is much better: get the device name and grab the device detail From db(I think it is more reliable)
if (Session::isAuthenticated()) {
    if(isset($_POST['name']) && isset($_POST['id']) && isset($_POST['dkey']) && (isset($_POST['button']) || isset($_POST['timer']) || isset($_POST['slider']) || isset($_POST['indicator']) || isset($_POST['display']) ) ){
        $deviceName = $_POST['name'];
        $deviceId = $_POST['id'];
        $devicekey = $_POST['dkey'];
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
                    ?>
                    <div class="toggle-container">
                        <label class="switch">
                        <input type="checkbox" id="toggleSwitch<?=$deviceId?>"><!-- use this if you are demoing  onclick="toggleAction()"> -->
                        <span class="slider"></span>
                        </label>
                        <span id="toggleStatus<?=$deviceId?>">Off</span>
                    </div>

                    <?php
                    // Session::loadTemplate('properties/button');
                }
                if($_POST['display'] == 1){

                    ?>
                    <div class="sensor-display">
        <h3>Device display</h3>
        <div class="sensor-value" id="sensorValue<?=$deviceId?>"></div>
        <div class="unit" id="unit"></div>
        <!-- <div class="status normal" id="status">Connected</div> -->
    </div>
                    <?php
                    // Session::loadTemplate('properties/display');
                }
                if($_POST['indicator'] == 1){
                    ?>
                    <div class="status-container">
        <div id="statusCircle<?=$deviceId?>" class="status-circle"></div>
        <span id="statusText<?=$deviceId?>" class="status-text">Offline</span>
    </div>
                    <?php
                    // Session::loadTemplate('properties/indicator');
                }
                if($_POST['slider'] == 1){
                    ?>
                    <div class="slider-container">
                        <input class="slider-input" type="range" id="slider<?=$deviceId?>" min="0" step="1" max="100" value="50">
                        <div class="value-display">Value: <span id="sliderValue<?=$deviceId?>">50</span></div>
                    </div>
                <?php
                    // Session::loadTemplate('properties/slider');
                }
                if($_POST['timer'] == 1){
                    Session::loadTemplate('properties/timer');
                }
                ?>
			</div>
	</div>
</section>

<script>
    // Function to toggle the button state
function toggleAction() {
    const toggleSwitch = document.getElementById('toggleSwitch<?=$deviceId?>');
    const toggleStatus = document.getElementById('toggleStatus<?=$deviceId?>');

    let buttonState = toggleSwitch.checked ? 1 : 0;
    toggleStatus.innerText = buttonState ? 'On' : 'Off';

    console.log(`Toggle is ${buttonState ? 'ON' : 'OFF'}`);

    // Send POST request with JSON payload
    fetch('http://localhost/api/webapi/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'devicekey':'<?=$devicekey?>'
        },
        body: JSON.stringify({ "buttonstate": buttonState })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log(`Response for ${buttonState ? 'ON' : 'OFF'}:`, data);
    })
    .catch(error => {
        console.error(`Error during ${buttonState ? 'ON' : 'OFF'} action:`, error);
    });
}

// Function to check device status periodically
function checkbDeviceStatus() {
    fetch('http://localhost/api/webapi/status', {
        method: "POST",
        headers:{
            'Content-Type':'application/json',
            'devicekey':'<?=$devicekey?>'
        },
        body: JSON.stringify({ "state": "buttonstate" })
    })  // Endpoint to check current status
    .then(response => response.json())
    .then(data => {
        const toggleSwitch = document.getElementById('toggleSwitch<?=$deviceId?>');
        let deviceState = data.buttonstate;  // Assuming API returns { "buttonstate": 1 or 0 }

        // Update toggle switch only if state has changed
        if (toggleSwitch.checked !== (deviceState === 1)) {
            toggleSwitch.checked = deviceState === 1;
            document.getElementById('toggleStatus<?=$deviceId?>').innerText = deviceState ? 'On' : 'Off';
        }
    })
    .catch(error => console.error('Error fetching status:', error));
}

function updateDisplay() {
    fetch('http://localhost/api/webapi/status', {
        method: "POST",
        headers: {
            'Content-Type': 'application/json',
            'devicekey': '<?=$devicekey?>'
        },
        body: JSON.stringify({ "state": "display" })
    })
    .then(response => response.json()) // Convert response to JSON
    .then(data => {
        console.log("Received display state:", data); // Debugging

        // Get the sensor value div
        const sensorValue = document.getElementById("sensorValue<?=$deviceId?>");
        // const statusElement = document.getElementById("status");

        // Update the display text dynamically
        if (data.display) {
            sensorValue.innerText = data.display; // Set received display value
        } else {
            sensorValue.innerText = "No Data"; // Fallback if state is missing
        }

        // Update status
        // if (data.state) {
        //     statusElement.innerText = "Connected";
        //     statusElement.classList.remove("error");
        //     statusElement.classList.add("normal");
        // } else {
        //     statusElement.innerText = "Disconnected";
        //     statusElement.classList.remove("normal");
        //     statusElement.classList.add("error");
        // }
    })
    .catch(error => {
        console.error("Error fetching display state:", error);
        // document.getElementById("status").innerText = "Disconnected";
        // document.getElementById("status").classList.remove("normal");
        // document.getElementById("status").classList.add("error");
    });
}

function updateDeviceStatus() {
    fetch('http://localhost/api/webapi/status', {
        method: "POST",
        headers: {
            'Content-Type': 'application/json',
            'devicekey': '<?=$devicekey?>'
        },
        body: JSON.stringify({ "state": "indicator" })
    })
    .then(response => response.json()) // Convert response to JSON
    .then(data => {
        console.log("Received device status:", data); // Debugging

        // Get the status elements
        const statusCircle = document.getElementById("statusCircle<?=$deviceId?>");
        const statusText = document.getElementById("statusText<?=$deviceId?>");

        // Update the UI based on the received status
        if (data.indicator === 1) {
            statusCircle.style.backgroundColor = "green";
            statusText.innerText = "Online";
        } else if (data.indicator === 0) {
            statusCircle.style.backgroundColor = "red";
            statusText.innerText = "Offline";
        } else {
            statusCircle.style.backgroundColor = "gray";
            statusText.innerText = "Unknown";
        }
    })
    .catch(error => {
        console.error("Error fetching device status:", error);
        
        // Handle errors by showing offline status
        const statusCircle = document.getElementById("statusCircle<?=$deviceId?>");
        const statusText = document.getElementById("statusText<?=$deviceId?>");
        statusCircle.style.backgroundColor = "gray";
        statusText.innerText = "Disconnected";
    });
}

const slider = document.getElementById("slider<?=$deviceId?>");
const sliderValue = document.getElementById("sliderValue<?=$deviceId?>");
let lastValue = slider.value;

slider.addEventListener("input", function () {
    sliderValue.textContent = this.value;

    if (this.value > lastValue) {
        console.log("Increased to:", this.value);
    } else if (this.value < lastValue) {
        console.log("Decreased to:", this.value);
    }

    lastValue = this.value;

    // Send the updated value to the server
    sendValueToServer(this.value);
});

function sendValueToServer(value) {
    console.log("Sending value to server:", value);

    fetch("http://localhost/api/webapi/update", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "devicekey": "<?=$devicekey?>"
        },
        body: JSON.stringify({ "slidervalue": value })
    })
    .then(response => response.json())
    .then(data => {
        console.log("Server response:", data);
    })
    .catch(error => console.error("Error sending slider value:", error));
}

// Function to periodically check the slider value from the server
function fetchSliderValue() {
    fetch("http://localhost/api/webapi/status", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "devicekey": "<?=$devicekey?>"
        },
        body: JSON.stringify({ state: "slidervalue" })
    })
    .then(response => response.json())
    .then(data => {
        console.log("Fetched slider value:", data);

        if (data.slidervalue !== undefined) {
            slider.value = data.slidervalue;
            sliderValue.textContent = data.slidervalue;
        }
    })
    .catch(error => console.error("Error fetching slider value:", error));
}

// Fetch the slider value every 5 seconds
setInterval(fetchSliderValue, 5000);

// Call updateDeviceStatus every 5 seconds
setInterval(updateDeviceStatus, 500);


// Call updateDisplay every 3 seconds
setInterval(updateDisplay, 3000);

// Attach event listener to toggle switch
document.getElementById('toggleSwitch<?=$deviceId?>').addEventListener('change', toggleAction);

setInterval(checkbDeviceStatus, 500);
</script>