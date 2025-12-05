<?php
session_start();
include 'Connection.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <title>PIAIMS | Login Page</title>
  <link rel="icon" type="image/x-icon" href="../Images/webbackg.png">
  <script src="../Functions/scripts.js"></script>
<style>
  /* General Styles */
  body {
    margin: 0; 
    background-color:rgba(51, 109, 19, 0.3);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #000;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }
  .container {
    width: 380px;
    background:rgb(255, 255, 255);
    border-radius: 14px;
    box-shadow: 0 0 5px rgba(85, 85, 85, 0.46);
    padding: 30px 35px 70px 35px;
  }
  h2 {
    color: darkgreen;
    margin-bottom: 24px;
    font-weight: 700;
    font-size: 34px;
    text-align: center;
    user-select: none;
    font-family: Arial, Helvetica, sans-serif;
  }
  /* Input Groups */
  .input-group {
    margin-bottom: 22px;
    position: relative;
  }
  label {
    display: block;
    font-weight: 600;
    color: black;
    margin-bottom: 8px;
    margin-left: 8px;
    font-size: 15px;
    user-select: none;
  }
  input[type="text"],
  input[type="password"],
  input[type="email"],
  input[type="tel"] {
    width: 100%;
    padding: 13px 14px;
    font-size: 16px;
    font-weight: 500;
    background: transparent;
    border: 2px solid rgba(46, 117, 8, 0.56);
    border-radius: 6px;
    color: darkgreen;
    outline-offset: 2px;
    outline-color: transparent;
    transition: border-color 0.3s ease, outline-color 0.3s ease;
    box-sizing: border-box;
  }
  input[type="text"]:focus,
  input[type="password"]:focus,
  input[type="email"]:focus,
  input[type="tel"]:focus {
    border-color: transparent;
    outline-color: transparent;
    border: 2px solid darkgreen;
  }
  input::placeholder {
    color: darkgreen;
    opacity: 1;
    font-weight: 400;
  }
  /* Password & Forgot container */
  .password-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 6px;
  }
  /* Show password styled as radio toggle */
  .show-pass-container {
    display: flex;
    align-items: center;
    user-select: none;
    font-size: 14px;
    font-weight: 600;
    color: darkgreen;
  }
  .show-pass-container input[type="checkbox"] {
    accent-color: yellow;
    margin-right: 8px;
    width: 18px;
    height: 18px;
    cursor: pointer;
  }
  /* Forgot password link */
  .forgot-pass {
    color: darkgreen;
    font-weight: bold;
    font-size: 14px;
    cursor: pointer;
    user-select: none;
    text-decoration: none;
    transition: color 0.3s ease;
  }
  .forgot-pass:hover {
    color: darkgreen;
    font-weight: bold;
  }
  /* Buttons */
  .btn {
    width: 100%;
    padding: 14px 0;
    background-color: darkgreen;
    border: none;
    border-radius: 7px;
    color: white;
    font-size: 17px;
    font-weight: 700;
    cursor: pointer;
    transition: background-color 0.3s ease;
    user-select: none;
  }
  .btn:hover {
    background-color: #004d00;
  }
  /* OTP input container */
  .otp-container {
    display: flex;
    justify-content: space-between;
    gap: 10px;
    margin-top: 6px;
  }
  .otp-container input {
    flex: 1;
    max-width: 45px;
    padding: 12px 0;
    font-size: 24px;
    font-weight: 700;
    text-align: center;
    background: transparent;
    border: 2px solid yellow;
    border-radius: 6px;
    color: black;
    outline-offset: 2px;
    outline-color: transparent;
    transition: border-color 0.3s ease, outline-color 0.3s ease;
    user-select: none;
  }
  .otp-container input:focus {
    border-color: #ccbb00;
    outline-color: #ccbb00;
  }
  /* Modal overlay */
  .modal-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: rgba(0, 0, 0, 0.65);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.25s ease;
  }
  .modal-overlay.active {
    opacity: 1;
    pointer-events: auto;
  }
  /* Modal box */
  .modal {
    background: white;
    border-radius: 12px;
    width: 360px;
    padding: 30px 30px 35px 30px;
    box-shadow: 0 0 30px rgba(255 255 0 / 0.3);
    position: relative;
  }
  .modal h3 {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 20px;
    color: darkgreen;
    user-select: none;
  }
  /* Modal close button */
  .modal-close {
    position: absolute;
    top: 16px;
    right: 18px;
    font-size: 22px;
    font-weight: 700;
    cursor: pointer;
    color: darkgreen;
    user-select: none;
    transition: color 0.3s ease;
  }
  .modal-close:hover {
    color: #006600;
  }
  /* Modal input styles */
  .modal .input-group label {
    color: black;
    font-weight: 600;
    font-size: 14px;
  }
  .modal input[type="text"],
  .modal input[type="password"],
  .modal input[type="email"],
  .modal input[type="tel"] {
    font-size: 16px;
    padding: 12px 14px;
    background: transparent;
    border: 2px solid yellow;
    border-radius: 6px;
    color: darkgreen;
    width: 100%;
    box-sizing: border-box;
    outline-offset: 2px;
    outline-color: transparent;
    transition: border-color 0.3s ease, outline-color 0.3s ease;
  }
  .modal input:focus {
    border-color: transparent;
    outline-color: transparent;
    border: 2px solid darkgreen;
  }
  /* Error text under inputs */
  .error-text {
    color: #d32f2f;
    background-color: #ffebee;
    font-size: 14px;
    margin: 8px 0;
    padding: 10px 15px;
    border-radius: 4px;
    border-left: 4px solid #d32f2f;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
  }
  
  .error-text:before {
    content: '⚠️';
    font-size: 16px;
  }
  /* Modal buttons */
  .modal .btn {
    margin-top: 20px;
  }
  /* Loading spinner inside modal */
  .loading-container {
    display: none;
    margin-top: 20px;
    text-align: center;
  }
  .loading-container.active {
    display: block;
  }
  .spinner {
    border: 4px solid #ccc;
    border-top: 4px solid darkgreen;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    margin: 0 auto 10px;
    animation: spin 1s linear infinite;
  }
  @keyframes spin {
    0% { transform: rotate(0deg);}
    100% { transform: rotate(360deg);}
  }
  .loading-text {
    font-weight: 600;
    color: darkgreen;
  }
</style>
</head>
<body>

  <!-- Login Container -->
  <div class="container" role="main" aria-label="Login form">
    <a href="LandingPage.php" style="text-decoration: none; color: darkgreen;"><i class='bx bx-left-arrow-alt' style="color: darkgreen; font-size: 2rem; font-weight: normal;"></i></a>
    <h2>Login</h2>
    
    <div id="Validation_ErrorMessage" class="error-text" style="display:none;" role="alert" aria-live="assertive"></div>

    <div class="input-group">
      <label for="login-email">Email</label>
      <input
        type="text"
        id="login-email"
        placeholder="example@gmail.com"
        oninput="autoAppendDomain(this)"
        aria-describedby="login-email-desc"
      />
      <div id="login-email-desc" class="error-text" style="display:none;"></div>
    </div>

    <div class="input-group">
      <label for="login-password">Password</label>
      <input
        type="password"
        id="login-password"
        placeholder="Enter password"
        autocomplete="current-password"
        aria-describedby="login-pass-desc"
      />
      <div class="password-row">
        <label class="show-pass-container" for="show-password">
          <input type="checkbox" id="show-password" />
          Show password
        </label>
        <a href="#" class="forgot-pass" id="forgotPassBtn" tabindex="0">Forgot Password?</a>
      </div>
      <div id="login-pass-desc" class="error-text" style="display:none;"></div>
    </div>

    <button class="btn" id="loginBtn" type="button" onclick="Login()">Login</button>
  </div>

  <!-- Modals -->
  <!-- 1. Forgot Password Email Modal -->
  <div class="modal-overlay" id="modal-email" role="dialog" aria-modal="true" aria-labelledby="modal-email-title" tabindex="-1" >
    <div class="modal">
      <span class="modal-close" role="button" aria-label="Close modal" tabindex="0" id="closeEmailModal">&times;</span>
      <h3 id="modal-email-title">Forgot Password</h3>
      <div class="input-group">
        <label for="forgot-email">Enter your email</label>
        <input
          type="text"
          id="forgot-email"
          placeholder="example@gmail.com"
          oninput="autoAppendDomain(this)"
          autocomplete="email"
          aria-describedby="forgot-email-error"
        />
        <div id="forgot-email-error" class="error-text" style="display:none;"></div>
      </div>
      <button class="btn" id="sendOtpBtn" type="button" onclick="SendOtp()">Send OTP</button>
    </div>
  </div>

  <!-- 2. OTP Modal -->
  <div class="modal-overlay" id="modal-otp" role="dialog" aria-modal="true" aria-labelledby="modal-otp-title" tabindex="-1" >
    <div class="modal">
      <span class="modal-close" role="button" aria-label="Close modal" tabindex="0" id="closeOtpModal">&times;</span>
      <h3 id="modal-otp-title">Verify OTP</h3>
      <p style="color:#444; font-size:14px; margin-bottom:16px; user-select:none;">Enter the 6-digit OTP sent to your email</p>
      <div class="otp-container" role="group" aria-label="OTP input fields">
        <input type="text" maxlength="1" class="otp" inputmode="numeric" pattern="[0-9]*" aria-label="OTP digit 1" />
        <input type="text" maxlength="1" class="otp" inputmode="numeric" pattern="[0-9]*" aria-label="OTP digit 2" />
        <input type="text" maxlength="1" class="otp" inputmode="numeric" pattern="[0-9]*" aria-label="OTP digit 3" />
        <input type="text" maxlength="1" class="otp" inputmode="numeric" pattern="[0-9]*" aria-label="OTP digit 4" />
        <input type="text" maxlength="1" class="otp" inputmode="numeric" pattern="[0-9]*" aria-label="OTP digit 5" />
        <input type="text" maxlength="1" class="otp" inputmode="numeric" pattern="[0-9]*" aria-label="OTP digit 6" />
      </div>
      <div id="otp-error" class="error-text" style="display:none; margin-top:8px;"></div>
      <button class="btn" id="verifyOtpBtn" style="margin-top: 20px;" onclick="VerifyOtp()">Verify OTP</button>
    </div>
  </div>

  <!-- 3. Change Password Modal -->
  <div class="modal-overlay" id="modal-change-password" role="dialog" aria-modal="true" aria-labelledby="modal-change-title" tabindex="-1" >
    <div class="modal">
      <span class="modal-close" role="button" aria-label="Close modal" tabindex="0" id="closeChangeModal">&times;</span>
      <h3 id="modal-change-title">Change Password</h3>

      <div class="input-group">
        <label for="new-password">New Password</label>
        <input
          type="password"
          id="new-password"
          placeholder="Enter new password"
          autocomplete="new-password"
        />
      </div>

      <div class="input-group">
        <label for="confirm-password">Confirm Password</label>
        <input
          type="password"
          id="confirm-password"
          placeholder="Confirm new password"
          autocomplete="new-password"
          aria-describedby="confirm-pass-error"
        />
        <div id="confirm-pass-error" class="error-text" style="display:none;"></div>
      </div>

      <button class="btn" id="changePassBtn" onclick="ChangePassword()">Change Password</button>
    </div>
  </div>

<script>
  // Modal handling
  const modalEmail = document.getElementById("modal-email");
  const modalOtp = document.getElementById("modal-otp");
  const modalChange = document.getElementById("modal-change-password");

  const forgotPassBtn = document.getElementById("forgotPassBtn");
  const closeEmailModal = document.getElementById("closeEmailModal");
  const closeOtpModal = document.getElementById("closeOtpModal");
  const closeChangeModal = document.getElementById("closeChangeModal");

  function Login() {
    const formData = new FormData();
    formData.append('action', 'login');
    formData.append('email', document.getElementById('login-email').value);
    formData.append('password', document.getElementById('login-password').value);

    fetch('../Functions/UserFunctions.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Refresh the user list to show updated Role
            // alert('Login successfully!');
            window.location.href = data.redirect;
        } else {
          document.getElementById('Validation_ErrorMessage').textContent = 'Error: ' + (data.error || 'Failed to login');
          document.getElementById('Validation_ErrorMessage').style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Error logging in:', error);
        alert('An error occurred while logging in: ' + error.message);
    });
  }
  
  function SendOtp() {
    const formData = new FormData();
    const sendOtpBtn = document.getElementById('sendOtpBtn');
    const errorDiv = document.getElementById('forgot-email-error');
    const email = document.getElementById('forgot-email').value.trim();
    
    // Validate email
    if (!email) {
        errorDiv.textContent = 'Please enter your email address';
        errorDiv.style.display = 'block';
        return;
    }
    
    // Show loading state
    sendOtpBtn.disabled = true;
    sendOtpBtn.innerHTML = 'Sending...';
    errorDiv.style.display = 'none';

    formData.append('action', 'send_otp');
    formData.append('email', email);

    fetch('../Functions/UserFunctions.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message and switch to OTP modal
            showModal(modalOtp);
            closeModal(modalEmail);
        } else {
            errorDiv.textContent = data.error || 'Failed to send OTP';
            errorDiv.style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Error sending OTP:', error);
        console.log('Error response:', error.response);
        errorDiv.textContent = 'An error occurred while sending OTP. Please try again.';
        errorDiv.style.display = 'block';
    })
    .finally(() => {
        // Reset button state
        sendOtpBtn.disabled = false;
        sendOtpBtn.innerHTML = 'Send OTP';
    });
  }

  function VerifyOtp() {
    const formData = new FormData();
    const errorMessage = document.getElementById('otp-error');
    const verifyOtpBtn = document.getElementById('verifyOtpBtn');
    formData.append('action', 'verify_otp');
    formData.append('email', document.getElementById('forgot-email').value);
    
    const otpInputs = document.querySelectorAll('.otp');
    const otpValue = Array.from(otpInputs).map(input => input.value).join('');
    formData.append('otp', otpValue);
    
    // Show loading state
    verifyOtpBtn.disabled = true;
    verifyOtpBtn.innerHTML = 'Verifying...';
    errorMessage.style.display = 'none';

    fetch('../Functions/UserFunctions.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message and switch to OTP modal
            showModal(modalChange);
            closeModal(modalOtp);
        } else {
            errorMessage.textContent = data.error || 'Failed to verify OTP';
            errorMessage.style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Error verifying OTP:', error);
        console.log('Error response:', error.response);
        errorMessage.textContent = 'An error occurred while verifying OTP. Please try again.';
        errorMessage.style.display = 'block';
    })
    .finally(() => {
        // Reset button state
        verifyOtpBtn.disabled = false;
        verifyOtpBtn.innerHTML = 'Verify OTP';
    });
  }

  function ChangePassword(){
    const newPass = document.getElementById('new-password').value;
    const confirmPass = document.getElementById('confirm-password').value;
    const confirmPassError = document.getElementById('confirm-pass-error');
    const changePassBtn = document.getElementById('changePassBtn');
    
    if(newPass !== confirmPass){
      confirmPassError.textContent = 'Passwords do not match';
      confirmPassError.style.display = 'block';
      return;
    }
    
    // Show loading state
    changePassBtn.disabled = true;
    changePassBtn.innerHTML = 'Changing Password...';
    confirmPassError.style.display = 'none';

    
    const formData = new FormData();
    formData.append('action', 'change_password');
    formData.append('email', document.getElementById('forgot-email').value);
    formData.append('new_password', newPass);
    
    fetch('../Functions/UserFunctions.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message and switch to OTP modal
            alert(data.message);
            window.location.href = '../Landing Repository/Loginpage.php';
        } else {
            errorMessage.textContent = data.error || 'Failed to Change Password';
            errorMessage.style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Error verifying OTP:', error);
        console.log('Error response:', error.response);
        errorMessage.textContent = 'An error occurred while changing password. Please try again.';
        errorMessage.style.display = 'block';
    })
    .finally(() => {
        // Reset button state
        changePassBtn.disabled = false;
        changePassBtn.innerHTML = 'Change Password';
    });
    
  }

  // Show/hide password toggle
  document.getElementById("show-password").addEventListener("change", function() {
    const passInput = document.getElementById("login-password");
    passInput.type = this.checked ? "text" : "password";
  });


  // Open Forgot Password modal
  forgotPassBtn.addEventListener("click", e => {
    e.preventDefault();
    showModal(modalEmail);
  });
  // Close modals handlers
  closeEmailModal.addEventListener("click", () => closeModal(modalEmail));
  closeOtpModal.addEventListener("click", () => closeModal(modalOtp));
  closeChangeModal.addEventListener("click", () => closeModal(modalChange));

  // Close modals on ESC key
  window.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      closeModal(modalEmail);
      closeModal(modalOtp);
      closeModal(modalChange);
    }
  });

  function showModal(modal) {
    modal.classList.add("active");
    modal.querySelector("input, button").focus();
  }
  function closeModal(modal) {
    modal.classList.remove("active");
    clearModalInputs(modal);
  }

  // OTP input auto-focus next
  const otpInputs = modalOtp.querySelectorAll(".otp");
  otpInputs.forEach((input, idx) => {
    input.addEventListener("input", (e) => {
      e.target.value = e.target.value.replace(/[^0-9]/g, ""); // numeric only
      if (e.target.value && idx < otpInputs.length - 1) {
        otpInputs[idx + 1].focus();
      }
    });
    input.addEventListener("keydown", (e) => {
      if (e.key === "Backspace" && !e.target.value && idx > 0) {
        otpInputs[idx - 1].focus();
      }
    });
  });
</script>

</body>
</html>
