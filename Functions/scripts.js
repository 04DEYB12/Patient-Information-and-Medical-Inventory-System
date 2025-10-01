// Auto append @gmail.com if no domain in email input and validate format
function autoAppendDomain(input) {
    const errorElement = input.nextElementSibling?.querySelector('.error-text') || 
                        document.getElementById(input.getAttribute('aria-describedby') || '');
    
    // Get the current value and clean it up
    let prefix = input.value.trim();
    
    // Remove any @gmail.com if present
    prefix = prefix.replace(/@gmail\.com/gi, "");
    
    // Check if the prefix is valid (only letters, numbers, dots, underscores, and hyphens)
    const isValid = /^[a-zA-Z0-9._-]+$/.test(prefix);
    
    if (prefix) {
        if (!isValid) {
            // Show error for invalid characters
            if (errorElement) {
                errorElement.textContent = "Only letters, numbers, ., _, and - are allowed before @gmail.com";
                errorElement.style.display = 'block';
                input.setCustomValidity('Invalid email format');
            }
            return;
        }
        
        // If valid, update the value with @gmail.com
        input.value = prefix + "@gmail.com";
        
        // Set cursor position
        const cursorPos = input.selectionStart;
        input.selectionStart = input.selectionEnd = Math.min(cursorPos, prefix.length);
        
        // Clear any previous error
        if (errorElement) {
            errorElement.style.display = 'none';
            input.setCustomValidity('');
        }
    } else if (errorElement) {
        // Clear error if field is empty
        errorElement.style.display = 'none';
        input.setCustomValidity('');
    }
}