        const slider = document.getElementById("slider");
        const sliderValue = document.getElementById("sliderValue");
        let lastValue = slider.value;

        slider.addEventListener("input", function () {
            sliderValue.textContent = this.value;
            if (this.value > lastValue) {
                console.log("Increased to:", this.value);
            } else if (this.value < lastValue) {
                console.log("Decreased to:", this.value);
            }
            lastValue = this.value;

            // Send the value to the server (Replace with your actual function)
            sendValueToServer(this.value);
        });

        function sendValueToServer(value) {
            console.log("Sending value to server:", value);
            // Example: You can use fetch() to send this value to your backend
            // fetch("your-server-endpoint", { method: "POST", body: JSON.stringify({ value }) })
        }