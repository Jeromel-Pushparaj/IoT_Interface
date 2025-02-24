function copyText() {
    let textbox = document.getElementById("textbox");
    textbox.select();
    textbox.setSelectionRange(0, 99999); // Mobile support
    document.execCommand("copy");

    // Optional: Show a copied message
    alert("Copied: " + textbox.value);
}