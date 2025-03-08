<section class="d-flex justify-content-center align-items-center vh-100">
    <div class="api-card">
        <h4>Your API Key</h4>
        <div class="api-key-container">
            <!-- TODO:If key exist disable the gernate button and pop up the delete button -->
            <input type="text" id="apiKey" class="api-key" value="<?php $key = ApiKey::getApikey()->getkey(); echo isset($key)? $key : "create a new key";?>" readonly>
            <button class="copy-btn" onclick="copyToClipboard()">
                <img src="https://cdn-icons-png.flaticon.com/512/54/54628.png" alt="Copy" width="20">
            </button>
        </div>
        <a class="btn btn-primary w-100 mt-3" href="/profile?genkey">Generate New Key</a>
    </div>
</section>