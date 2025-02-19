// Get the text input
const input = document.getElementById('floatingInputUsername');

// Add keypress event listener
input.addEventListener('keypress', function(event){
    
    // Get the key code
    let keycode = event.which || event.keyCode;
    
    // Check if key pressed is a special character
    if(keycode < 48 || 
     (keycode > 57 && keycode < 65) || 
     (keycode > 90 && keycode < 97) ||
     keycode > 122
    ){
        // Restrict the special characters
        event.preventDefault();  
        return false;
    }
});  