<?php
session_start();
include 'Connection.php'; 

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $Login_email = $_POST['Login_email'];
  $Login_password = $_POST['Login_password'];

  if(!empty($Login_email) && !empty($Login_password)) {
    $query = "SELECT * FROM clinicpersonnel cp JOIN userrole ur ON cp.RoleID = ur.RoleID WHERE cp.EmailAddress = ? LIMIT 1";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $Login_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
      $user_data = $result->fetch_assoc();
      // Verify hashed password
      if (password_verify($Login_password, $user_data['PasswordHash'])  || $Login_password == $user_data['PasswordHash']  ) {
        if($user_data['Status'] == 'Active') {
          $_SESSION['User_ID'] = $user_data['PersonnelID'];
          $_SESSION['role'] = $user_data['RoleName'];

          session_regenerate_id(true);
          header("Location: ../PIAIMS Repository/Dashboard.php");
          
          exit();
        }else {
          echo "<script>document.getElementById('Validation_ErrorMessage').textContent = 'Account Status is Inactive!'; document.getElementById('Validation_ErrorMessage').style.display = 'block';</script>";
        }
        
      }else{
        echo "<script>document.getElementById('Validation_ErrorMessage').textContent = 'Incorrect Password!'; document.getElementById('Validation_ErrorMessage').style.display = 'block';</script>";
      }
    }else{
      echo "<script>document.getElementById('Validation_ErrorMessage').textContent = 'Account Not Found!'; document.getElementById('Validation_ErrorMessage').style.display = 'block';</script>";
    }  
  }else {
    echo "<script>document.getElementById('Validation_ErrorMessage').textContent = 'Please fill in all fields!'; document.getElementById('Validation_ErrorMessage').style.display = 'block';</script>";
    exit();
  }
}
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
    color: red;
    font-size: 13px;
    margin-top: 4px;
    user-select: none;
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
  <form action="#" method="POST">
    <a href="LandingPage.php" style="text-decoration: none; color: darkgreen;"><i class='bx bx-left-arrow-alt' style="color: darkgreen; font-size: 2rem; font-weight: normal;"></i></a>
    <h2>Login</h2>
    
    <div id="Validation_ErrorMessage" class="error-text" style="display:none;"></div>

    <div class="input-group">
      <label for="login-email">Email</label>
      <input
        type="text"
        id="login-email"
        placeholder="example@gmail.com"
        oninput="autoAppendDomain(this)"
        aria-describedby="login-email-desc"
        name="Login_email"
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
        name="Login_password"
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

    <button class="btn" id="loginBtn" type="submit">Login</button>
  </form>
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
      <button class="btn" id="sendOtpBtn">Send OTP</button>
      <div class="loading-container" id="loadingEmail">
        <div class="spinner"></div>
        <div class="loading-text">Sending OTP...</div>
      </div>
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
      <button class="btn" id="verifyOtpBtn" style="margin-top: 20px;">Verify OTP</button>
      <div class="loading-container" id="loadingOtp">
        <div class="spinner"></div>
        <div class="loading-text">Verifying OTP...</div>
      </div>
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
          aria-describedby="new-pass-error"
        />
        <div id="new-pass-error" class="error-text" style="display:none;"></div>
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

      <button class="btn" id="changePassBtn">Change Password</button>
      <div class="loading-container" id="loadingChangePass">
        <div class="spinner"></div>
        <div class="loading-text">Updating password...</div>
      </div>
    </div>
  </div>

<script>
  

  // Show/hide password toggle
  document.getElementById("show-password").addEventListener("change", function() {
    const passInput = document.getElementById("login-password");
    passInput.type = this.checked ? "text" : "password";
  });

  // Modal handling
  const modalEmail = document.getElementById("modal-email");
  const modalOtp = document.getElementById("modal-otp");
  const modalChange = document.getElementById("modal-change-password");

  const forgotPassBtn = document.getElementById("forgotPassBtn");
  const closeEmailModal = document.getElementById("closeEmailModal");
  const closeOtpModal = document.getElementById("closeOtpModal");
  const closeChangeModal = document.getElementById("closeChangeModal");

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
  function clearModalInputs(modal) {
    modal.querySelectorAll("input").forEach(i => i.value = "");
    modal.querySelectorAll(".error-text").forEach(e => e.style.display = "none");
    hideLoading(modal);
  }
  // Show/hide loading inside modal
  function showLoading(modal) {
    const loading = modal.querySelector(".loading-container");
    if (loading) loading.classList.add("active");
  }
  function hideLoading(modal) {
    const loading = modal.querySelector(".loading-container");
    if (loading) loading.classList.remove("active");
  }

  // Send OTP button click (simulate async)
  document.getElementById("sendOtpBtn").addEventListener("click", () => {
    const emailInput = document.getElementById("forgot-email");
    const errorDiv = document.getElementById("forgot-email-error");
    errorDiv.style.display = "none";

    if (!validateEmail(emailInput.value.trim())) {
      errorDiv.textContent = "Please enter a valid email.";
      errorDiv.style.display = "block";
      return;
    }
    showLoading(modalEmail);

    // Simulate sending OTP (2s delay)
    setTimeout(() => {
      hideLoading(modalEmail);
      closeModal(modalEmail);
      showModal(modalOtp);
    }, 2000);
  });

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

  // Verify OTP button click (simulate async)
  document.getElementById("verifyOtpBtn").addEventListener("click", () => {
    const otpError = document.getElementById("otp-error");
    otpError.style.display = "none";

    const otpCode = Array.from(otpInputs).map(i => i.value).join("");
    if (otpCode.length < 6) {
      otpError.textContent = "Please enter the 6-digit OTP.";
      otpError.style.display = "block";
      return;
    }
    showLoading(modalOtp);

    // Simulate OTP verification delay (2s)
    setTimeout(() => {
      hideLoading(modalOtp);
      // For demonstration, accept any OTP
      closeModal(modalOtp);
      showModal(modalChange);
    }, 2000);
  });

  // Change Password button click
  document.getElementById("changePassBtn").addEventListener("click", () => {
    const newPass = document.getElementById("new-password");
    const confirmPass = document.getElementById("confirm-password");

    const newPassError = document.getElementById("new-pass-error");
    const confirmPassError = document.getElementById("confirm-pass-error");

    newPassError.style.display = "none";
    confirmPassError.style.display = "none";

    if (newPass.value.length < 6) {
      newPassError.textContent = "Password must be at least 6 characters.";
      newPassError.style.display = "block";
      return;
    }
    if (newPass.value !== confirmPass.value) {
      confirmPassError.textContent = "Passwords do not match.";
      confirmPassError.style.display = "block";
      return;
    }
    showLoading(modalChange);

    // Simulate password update delay (2s)
    setTimeout(() => {
      hideLoading(modalChange);
      closeModal(modalChange);
      alert("Password changed successfully!");
    }, 2000);
  });

  // Basic email validation
  function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email.toLowerCase());
  }
</script>

</body>
</html>
