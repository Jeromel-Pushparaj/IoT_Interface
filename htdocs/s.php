<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Copy Text Example</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="p-4">
<?php
for($i=0; $i<5;$i++){
?>
    <div class="container">
        <label for="textArea" class="form-label">Copy this text:</label>
        <div class="input-group">
            <textarea id="textArea" class="form-control" rows="3">This is the text to be copied.</textarea>
            <button class="btn btn-primary" onclick="copyText()">Copy</button>
        </div>
        <p id="copyStatus" class="mt-2 text-success"></p>
    </div>
<?php
}
?>
    <script>
        function copyText() {
            let textArea = document.getElementById("textArea");
            textArea.select();
            textArea.setSelectionRange(0, 99999); // For mobile devices
            document.execCommand("copy");

            // Show a message
            let status = document.getElementById("copyStatus");
            status.textContent = "Copied!";
            setTimeout(() => { status.textContent = ""; }, 2000);
        }
    </script>

</body>
</html>

<?php 
  
?>
