// Define the URL with query parameters
function toggleAction() {
    const toggleSwitch = document.getElementById('toggleSwitch');
    const toggleStatus = document.getElementById('toggleStatus');

    if (toggleSwitch.checked) {
        toggleStatus.innerText = 'On';
        // Trigger your "on" action here
        console.log('Toggle is ON');

        // Send GET request for "On" action
        fetch('http://192.168.196.213/led1/on')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Response for ON:', data);
            })
            .catch(error => {
                console.error('Error during ON action:', error);
            });
    } else {
        toggleStatus.innerText = 'Off';
        // Trigger your "off" action here
        console.log('Toggle is OFF');

        // Send GET request for "Off" action
        fetch('http://192.168.1.5/led1/off')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Response for OFF:', data);
            })
            .catch(error => {
                console.error('Error during OFF action:', error);
            });
    }
}

    // function updateSensorDisplay(value, unit, status) {
    //     document.getElementById('sensorValue').innerText = value;
    //     document.getElementById('unit').innerText = unit;

    //     const statusElement = document.getElementById('status');
    //     statusElement.innerText = status;

    //     // Update status class based on status
    //     if (status.toLowerCase() === 'normal') {
    //         statusElement.className = 'status normal';
    //     } else if (status.toLowerCase() === 'alert') {
    //         statusElement.className = 'status alert';
    //     }
    // }

    // // Example: Updating the display
    // setInterval(() => {
    //     // Simulated data
    //     const randomValue = (Math.random() * 100).toFixed(2);
    //     const randomStatus = randomValue > 70 ? 'Alert' : 'Normal';

    //     updateSensorDisplay(randomValue, '°C', randomStatus);
    // }, 2000);

            // Function to check device status
            function checkDeviceStatus() {
                // Replace the URL with your server's endpoint
                const url = '';
    
                fetch('http://192.168.196.213/status')
                    .then(response => {
                        if (response.ok) {
                            // 200 OK response
                            updateStatus(true);
                        } else {
                            // Any other response code
                            updateStatus(false);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching device status:', error);
                        updateStatus(false); // Treat errors as "Offline"
                    });
            }
    
            // Function to update the status indicator
            function updateStatus(isOnline) {
                const statusCircle = document.getElementById('statusCircle');
                const statusText = document.getElementById('statusText');
    
                if (isOnline) {
                    statusCircle.style.backgroundColor = 'green';
                    statusText.textContent = 'Online';
                } else {
                    statusCircle.style.backgroundColor = 'red';
                    statusText.textContent = 'Offline';
                }
            }
    
            // Periodically check the device status
            setInterval(checkDeviceStatus, 1000); // Check every 5 seconds
    
            // Initial check when the page loads
            checkDeviceStatus();
