// function generateApiKey() {
//     let key = Math.random().toString(36).substring(2, 10).toUpperCase() + '-' + 
//               Math.random().toString(36).substring(2, 10).toUpperCase();
//     document.getElementById("apiKey").value = key;
// }

function copyToClipboard() {
    let apiKey = document.getElementById("apiKey");
    apiKey.select();
    document.execCommand("copy");
    alert("API Key copied to clipboard!");
}

// // Generate API key on page load
// generateApiKey();