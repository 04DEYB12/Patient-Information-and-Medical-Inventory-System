/*
    WHAT THIS FILE DOES
    - autoAppendDomain() : Auto append @gmail.com if no domain in email input and validate format.
    - validatePhoneNumber() : Validate Philippine mobile number. Check if the number starts with 09 and has 11 digits
    - showAlert() : Show toast notification.
*/

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

function validatePhoneNumber(input) {
    // Remove all non-digit characters
    let phoneNumber = input.value.replace(/\D/g, '');
    
    // Check if the number starts with 09 and has 11 digits
    const phMobileRegex = /^09\d{9}$/;
    
    if (!phMobileRegex.test(phoneNumber)) {
        showAlert('Please enter a valid Philippine mobile number (09XXXXXXXXX)', 'error');
        input.setCustomValidity('Please enter a valid Philippine mobile number (09XXXXXXXXX)');
        document.getElementById('NewPhone').classList.add('border-red-500');
        document.getElementById('saveButtonAtPhone').disabled = true;
        document.getElementById('saveButtonAtPhone').classList.add('cursor-not-allowed');
        return false;
    }
    
    // Format the phone number: 0912 345 6789
    const formattedNumber = phoneNumber.replace(/(\d{4})(\d{3})(\d{4})/, '$1 $2 $3');
    input.value = formattedNumber;
    input.setCustomValidity('');
    document.getElementById('NewPhone').classList.remove('border-red-500');
    document.getElementById('saveButtonAtPhone').disabled = false;
    document.getElementById('saveButtonAtPhone').classList.remove('cursor-not-allowed');
    return true;
}

function showAlert(message, type = 'success') {
    // Create toast container if it doesn't exist
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'fixed top-4 right-4 z-[9999] space-y-3 w-80';
        // Ensure container is on top of all other elements
        toastContainer.style.position = 'fixed';
        toastContainer.style.top = '1rem';
        toastContainer.style.right = '1rem';
        document.body.appendChild(toastContainer);
    }
    
    // Create toast element
    const toast = document.createElement('div');
    const typeStyles = {
        success: 'bg-green-50 border-green-200 text-green-800',
        error: 'bg-red-50 border-red-200 text-red-800',
        warning: 'bg-yellow-50 border-yellow-200 text-yellow-800',
        info: 'bg-blue-50 border-blue-200 text-blue-800'
    };
    
    const iconMap = {
        success: 'check-circle',
        error: 'error',
        warning: 'error',
        info: 'info-circle'
    };
    
    const toastType = type in typeStyles ? type : 'info';
    
    // Add toast HTML
    toast.className = `flex items-start p-4 rounded-lg border ${typeStyles[toastType]} shadow-xl transform transition-all duration-300 ease-in-out opacity-0 translate-x-8`;
    // Ensure toast is above other content
    toast.style.position = 'relative';
    toast.style.zIndex = '10000';
    toast.style.boxShadow = '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)';
    toast.style.backdropFilter = 'blur(4px)';
    toast.style.webkitBackdropFilter = 'blur(4px)';
    toast.innerHTML = `
        <i class='bx bx-${iconMap[toastType]} text-xl mr-3 mt-0.5'></i>
        <div class='flex-1'>
            <div class='font-medium'>${type.charAt(0).toUpperCase() + type.slice(1)}</div>
            <div class='text-sm mt-1'>${message}</div>
        </div>
        <button type='button' class='ml-2 text-gray-500 hover:text-gray-700' onclick='this.parentElement.remove()'>
            <i class='bx bx-x text-xl'></i>
        </button>
    `;
    
    // Add to container
    toastContainer.prepend(toast);
    
    // Trigger animation
    setTimeout(() => {
        toast.classList.remove('opacity-0', 'translate-x-8');
        toast.classList.add('opacity-100');
    }, 10);
    
    // Auto-remove after delay
    const autoRemove = setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-x-8');
        setTimeout(() => toast.remove(), 300);
    }, 5000);
    
    // Pause auto-remove on hover
    toast.addEventListener('mouseenter', () => clearTimeout(autoRemove));
    toast.addEventListener('mouseleave', () => {
        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-x-8');
            setTimeout(() => toast.remove(), 300);
        }, 1000);
    });
}