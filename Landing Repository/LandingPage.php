<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PIAMIS | Landing Page</title>
  <link rel="icon" type="image/x-icon" href="../Images/webbackg.png">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#4caf50',
            secondary: '#81c784',
            accent: '#a5d6a7',
            dark: '#002e2d',
            light: '#f5f8f5',
            // Custom colors from the image
            imageBg: '#e9f8f4', // Light greenish-blue background
            imageGreen: '#50c299', // Green for the circle and button
            imageOrange: '#ffc107', // Orange for dashed lines
          },
          fontFamily: {
            sans: ['Poppins', 'sans-serif'],
          },
          keyframes: {
            fadeInUp: {
              '0%': { opacity: 0, transform: 'translateY(20px)' },
              '100%': { opacity: 1, transform: 'translateY(0)' },
            },
            fadeIn: {
              '0%': { opacity: 0 },
              '100%': { opacity: 1 },
            },
            bounceCustom: {
              '0%, 100%': { transform: 'translateY(-5%)' },
              '50%': { transform: 'translateY(0)' },
            },
            float: {
              '0%': { transform: 'translateY(0px)' },
              '50%': { transform: 'translateY(-10px)' },
              '100%': { transform: 'translateY(0px)' },
            }
          },
          animation: {
            fadeInUp: 'fadeInUp 1s ease-out forwards',
            fadeIn: 'fadeIn 1s ease-out forwards',
            bounceCustom: 'bounceCustom 2s infinite ease-in-out',
            float: 'float 3s ease-in-out infinite',
          },
        },
      },
    }
  </script>
  <style>
    /* Custom styles for scroll-based animation */
    section {
      opacity: 0;
      transform: translateY(20px);
      transition: all 1.2s ease-out;
    }
    section.visible {
      opacity: 1;
      transform: translateY(0);
    }
    
    /* Animation for hover effects on cards */
    .animated-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .animated-card:hover {
      transform: translateY(-8px) scale(1.02);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    /* Style for the step-by-step boxes */
    .step-box {
      background-color: #ffffff;
      padding: 2.5rem; /* p-10 */
      border-radius: 0.75rem; /* rounded-xl */
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); /* shadow-md */
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      position: relative;
      min-height: 180px; /* Ensure consistent height */
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
    }
    .step-box:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    /* Arrow styling */
    .arrow-icon {
      color: #4caf50; /* primary color */
      font-size: 2.5rem; /* text-4xl */
      margin: 0 1rem;
      align-self: center;
      display: none; /* Hidden by default */
    }

    /* Show arrows on medium screens and up */
    @media (min-width: 768px) {
      .arrow-icon-md {
        display: block;
      }
      .how-it-works-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: stretch;
      }
      .how-it-works-grid > div {
        display: flex;
        align-items: center;
      }
    }
    /* Vertical arrows for small screens */
    @media (max-width: 767px) {
      .arrow-icon-sm {
        display: block;
        margin: 1rem auto;
        transform: rotate(90deg);
      }
    }

    /* Number circle */
    .step-number {
      position: absolute;
      top: -15px;
      left: 50%;
      transform: translateX(-50%);
      background-color: #4caf50; /* primary */
      color: white;
      width: 35px;
      height: 35px;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      font-weight: bold;
      font-size: 1.1rem;
      border: 3px solid #f5f8f5; /* light background */
      z-index: 10;
    }

    /* Underline animation for the hero text */
    .animated-underline {
      position: relative;
      display: inline-block;
    }
    .animated-underline::after {
      content: '';
      position: absolute;
      left: 0;
      bottom: -4px;
      width: 0;
      height: 4px;
      background-color: #50c299; /* Adjust to desired underline color */
      transition: width 0.5s ease-out;
      border-radius: 2px;
    }
    .animated-underline.active::after {
      width: 100%;
    }
    
    /* Timeline containers */
    .step-container {
      width: 50%;
      padding: 1rem 2rem;
      position: relative;
    }
    .step-container.left { float: left; text-align: right; clear: both; }
    .step-container.right { float: right; text-align: left; clear: both; }

    /* Step box design */
    .step-box {
      background: #fff;
      border-radius: 1rem;
      padding: 1.5rem;
      position: relative;
      transition: all 0.3s ease;
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      max-width: 350px;
      margin: auto;
      transform: scale(0.95);
      opacity: 0.9;
    }
    .step-box:hover { transform: scale(1); opacity: 1; }
    .step-box.expanded {
      transform: scale(1.05);
      box-shadow: 0 6px 16px rgba(0,0,0,0.12);
    }

    /* Number Circle */
    .step-number {
      position: absolute;
      top: -15px;
      left: -15px;
      width: 40px;
      height: 40px;
      background: #2563eb;
      color: #fff;
      font-weight: bold;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 3px 8px rgba(0,0,0,0.2);
    }

    /* Responsive */
    @media (max-width: 768px) {
      .step-container,
      .step-container.left,
      .step-container.right {
        width: 100%;
        text-align: center;
        float: none;
        padding: 2rem 0;
      }

      .step-box {
        max-width: 90%;
        margin: auto;
      }

      /* Center the step number on small screens */
      .step-number {
        left: 50%;
        transform: translateX(-50%);
      }
    }
  </style>
</head>

<body class="bg-light text-dark font-sans overflow-x-hidden">
  
  <!-- Add inside your header -->
<header class="w-full flex p-6 p-2 sm:px-6 md:px-20 items-center justify-between bg-white/80 backdrop-blur-md fixed top-0 z-50">
  <div class="flex items-center gap-3">
    <h3 class="text-green-400 font-semibold text-2xl">PIAMIS</h3>
    <span class="text-green-600 text-2xl">/</span>
  </div>

  <!-- Desktop Nav -->
  <nav class="hidden md:flex gap-6">
    <a href="#home" class="text-dark hover:text-primary transition smooth-scroll">Home</a>
    <a href="#why-choose-us" class="text-dark hover:text-primary transition smooth-scroll">Why Choose Us</a>
    <a href="#features" class="text-dark hover:text-primary transition smooth-scroll">Features</a>
    <a href="#how" class="text-dark hover:text-primary transition smooth-scroll">How It Works</a>
    <a href="#services" class="text-dark hover:text-primary transition smooth-scroll">Services</a>
    <a href="#team" class="text-dark hover:text-primary transition smooth-scroll">Team</a>
    <a href="#contact" class="text-dark hover:text-primary transition smooth-scroll">Contact</a>
  </nav>

  <!-- Buttons -->
  <div class="hidden md:flex gap-3">
    <button onclick="window.location.href='Loginpage.php'" class="text-gray-800 hover:text-yellow-400 transition-colors">
      Sign In
    </button>
    <button class="bg-green-500 text-white px-4 py-1 rounded-lg hover:bg-green-600 transition flex items-center">Get Started <span class="text-2xl ml-2 font-normal">></span></button>
  </div>

  <!-- Mobile menu button -->
  <button id="mobileMenuBtn" class="md:hidden text-3xl text-dark focus:outline-none">
    <i class="fas fa-bars"></i>
  </button>
</header>

<!-- Mobile Nav Menu -->
<div id="mobileMenu" class="fixed top-0 right-0 w-64 h-full bg-white shadow-lg transform translate-x-full transition-transform duration-300 z-40">
  <div class="flex justify-between items-center p-4 border-b">
    <h3 class="text-yellow-400 font-semibold text-xl">PIAMIS</h3>
    <button id="closeMenuBtn" class="text-2xl text-dark"><i class="fas fa-times"></i></button>
  </div>
  <nav class="flex flex-col p-6 space-y-4">
    <a href="#home" class="hover:text-primary smooth-scroll">Home</a>
    <a href="#why-choose-us" class="hover:text-primary smooth-scroll">Why Choose Us</a>
    <a href="#features" class="hover:text-primary smooth-scroll">Features</a>
    <a href="#how" class="hover:text-primary smooth-scroll">How It Works</a>
    <a href="#services" class="hover:text-primary smooth-scroll">Services</a>
    <a href="#team" class="hover:text-primary smooth-scroll">Team</a>
    <a href="#contact" class="hover:text-primary smooth-scroll">Contact</a>
    <hr>
    <button onclick="window.location.href='Loginpage.php'" class="w-full py-2 rounded bg-gray-100 hover:bg-gray-200">Sign In</button>
    <button class="w-full py-2 rounded bg-green-500 text-white hover:bg-green-600">Get Started</button>
  </nav>
</div>

  <section id="home" class="relative overflow-hidden bg-imageBg min-h-screen flex items-center justify-center text-dark pt-20">
    <div class="absolute top-10 left-10 w-20 h-20 border-t-4 border-l-4 border-dashed border-imageOrange rotate-45"></div>
    <div class="absolute bottom-20 right-10 w-24 h-24 border-b-4 border-r-4 border-dashed border-imageGreen -rotate-45"></div>
    <div class="absolute top-1/4 right-1/2 md:right-1/3 translate-x-1/2 -translate-y-1/2 text-imageOrange text-6xl animate-float">
        <i class="fas fa-arrow-circle-up rotate-45"></i>
    </div>

    <div class="container mx-auto flex flex-col md:flex-row items-center justify-between px-6 md:px-16 py-12 gap-12 relative z-10">
      <div class="md:w-2/3 text-center md:text-left animate-fadeInUp">
        <p class="text-md sm:text-xl font-medium text-gray-700 mb-4">Start your journey</p>
        <h1 class="font-extrabold text-xl md:text-6xl lg:text-6xl leading-tight mb-6">
        Because Every Patient<br class="hidden md:block">- <span class="animated-underline mb-2">Story Matters.</span>
        </h1>
        <p class="text-sm sm:text-lg text-gray-700 mb-10 max-w-lg mx-auto md:mx-0">
          We believe that every patient is more than just data — our system is built to protect, organize, and honor each individual’s journey toward better health.
        </p>
        <button class="bg-imageGreen text-white font-semibold px-10 py-4 rounded-full text-lg hover:bg-green-700 transition duration-300 transform hover:scale-105 shadow-lg">
          Get Started Today
        </button>
      </div>

      <div class="md:w-1/2 relative flex justify-center animate-fadeIn md:mt-0 mt-12">
        <img src="../Images/ggg.jpg" alt="Professional Woman with Laptop" class="max-w-full h-auto">
        
        <div class="absolute bg-white px-6 py-4 rounded-xl shadow-lg border-l-4 border-imageGreen -bottom-8 md:-bottom-4 left-1 sm:left-1/2 transform -translate-x-1/2 md:translate-x-0 md:-left-16 lg:-left-20 animate-bounceCustom">
          <div class="flex items-center space-x-3">
            <div class="bg-imageGreen rounded-full p-3">
              <i class="fas fa-headset text-white text-xl"></i>
            </div>
            <div>
              <p class="font-bold text-2xl text-dark px-6">Real-Time</p>
              <p class="text-gray-600 text-sm">Support</p>
            </div>
          </div>
        </div>

        <div class="absolute bg-white px-4 py-2 rounded-xl shadow-lg border-r-4 border-imageOrange -top-4 md:top-8 right-1/2 translate-x-1/2 md:-translate-x-0 md:-right-8 animate-float">
            <div class="flex items-center space-x-2">
                <span class="text-yellow-500"><i class="fas fa-star"></i></span>
                <p class="font-bold text-lg text-dark">4.8</p>
                <p class="text-gray-600 text-sm">Rating</p>
            </div>
        </div>
      </div>
    </div>
  </section>
  
  <section id="why-choose-us" class="py-20 px-6 md:px-16 bg-white text-center min-h-screen flex flex-col items-center justify-center">
    <h2 class="text-4xl font-bold mb-4">Why Choose PIAMIS?</h2>
    <p class="text-gray-600 mb-12 max-w-3xl">Discover the benefits that make PIAMIS the leading choice for modern healthcare management.</p>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-8 w-full max-w-8xl">
      <div class="bg-light p-8 rounded-xl shadow-md animated-card">
        <div class="text-5xl mb-4 text-primary">🔒</div>
        <h3 class="font-bold text-xl mb-2">Information Security</h3>
        <p class="text-gray-600">Your patient data is protected with state-of-the-art encryption and robust security protocols.</p>
      </div>
      
      <div class="bg-light p-8 rounded-xl shadow-md animated-card">
        <div class="text-5xl mb-4 text-primary">✨</div>
        <h3 class="font-bold text-xl mb-2">Intuitive Interface</h3>
        <p class="text-gray-600">Designed for ease of use, our system reduces training time and increases staff efficiency.</p>
      </div>

      <div class="bg-light p-8 rounded-xl shadow-md animated-card">
        <div class="text-5xl mb-4 text-primary">⏱️</div>
        <h3 class="font-bold text-xl mb-2">Time-Saving Automation</h3>
        <p class="text-gray-600">Automate routine tasks, from managing to inventory, freeing up valuable staff time.</p>
      </div>

      <div class="bg-light p-8 rounded-xl shadow-md animated-card">
        <div class="text-5xl mb-4 text-primary">📊</div>
        <h3 class="font-bold text-xl mb-2">Powerful Insights</h3>
        <p class="text-gray-600">Access accurate data analytics and reports to make informed decisions for your practice.</p>
      </div>

      <div class="bg-light p-8 rounded-xl shadow-md animated-card">
        <div class="text-5xl mb-4 text-primary">🌿</div>
        <h3 class="font-bold text-xl mb-2">Eco-Friendly Approach</h3>
        <p class="text-gray-600">Minimize paper waste with digital records and smart inventory management.</p>
      </div>

      <div class="bg-light p-8 rounded-xl shadow-md animated-card">
        <div class="text-5xl mb-4 text-primary">🤝</div>
        <h3 class="font-bold text-xl mb-2">Dedicated Support</h3>
        <p class="text-gray-600">Our expert team is always ready to provide support and training when you need it.</p>
      </div>
    </div>
  </section>
  

  
  <section id="features" class="py-20 px-6 md:px-16 bg-light text-center min-h-screen flex flex-col items-center justify-center">
    <h2 class="text-4xl font-bold mb-4">Key Features</h2>
    <p class="text-gray-600 mb-12 max-w-3xl">Overview of the seamless process flow that makes our system a vital tool for data management and healthcare providers.</p>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full max-w-6xl">
      <div class="bg-white p-10 rounded-xl shadow-md animated-card">
        <div class="text-5xl mb-4 text-primary">🌿</div>
        <h3 class="text-2xl font-semibold mb-3">Patient Information</h3>
        <p class="text-gray-600">Securely manage and access comprehensive patient informations with ease.</p>
      </div>
      <div class="bg-white p-10 rounded-xl shadow-md animated-card">
        <div class="text-5xl mb-4 text-primary">🍃</div>
        <h3 class="text-2xl font-semibold mb-3">Inventory Management</h3>
        <p class="text-gray-600">Track medical supplies with smart, green-conscious alerts to prevent shortages and waste.</p>
      </div>
      <div class="bg-white p-10 rounded-xl shadow-md animated-card">
        <div class="text-5xl mb-4 text-primary">🌱</div>
        <h3 class="text-2xl font-semibold mb-3">Reports & Analytics</h3>
        <p class="text-gray-600">Gain actionable insights through powerful, data-driven analytics and customizable reporting.</p>
      </div>
    </div>
  </section>
  
 
  
  <section id="how" class="py-20 px-6 md:px-16 bg-gradient-to-b from-white to-gray-50 min-h-screen flex flex-col items-center justify-center relative">
    <h2 class="text-4xl font-bold mb-4 text-gray-900">How It Works</h2>
    <p class="text-gray-600 mb-16 max-w-2xl text-center">
      Our simplified process ensures a seamless and efficient experience for both patients and staff.
    </p>

    <div class="relative w-full max-w-5xl">
      <!-- Vertical Line -->
      <div class="absolute left-1/2 transform -translate-x-1/2 w-1 bg-gradient-to-b from-primary/40 to-primary/10 h-full rounded"></div>

      <!-- Step 1 -->
      <div class="step-container left">
        <div class="step-box expanded">
          <span class="step-number">1</span>
          <h3 class="font-bold text-xl mb-2 text-primary">User Check-In</h3>
          <p class="text-gray-600 text-sm">Patients check in at the front desk giving their necessarry information.</p>
        </div>
      </div>

      <!-- Step 2 -->
      <div class="step-container right">
        <div class="step-box">
          <span class="step-number">2</span>
          <h3 class="font-bold text-xl mb-2 text-primary">Staff Processes Patient Info</h3>
          <p class="text-gray-600 text-sm">Staff securely enter patient details, building a comprehensive digital profile.</p>
        </div>
      </div>

      <!-- Step 3 -->
      <div class="step-container left">
        <div class="step-box">
          <span class="step-number">3</span>
          <h3 class="font-bold text-xl mb-2 text-primary">Data Saved to Database</h3>
          <p class="text-gray-600 text-sm">All information is securely stored in our encrypted central database.</p>
        </div>
      </div>

      <!-- Step 4 -->
      <div class="step-container right">
        <div class="step-box">
          <span class="step-number">4</span>
          <h3 class="font-bold text-xl mb-2 text-primary">Scan QR Code</h3>
          <p class="text-gray-600 text-sm">A unique QR code is scanned for faster student identification and tracking.</p>
        </div>
      </div>

      <!-- Step 5 -->
      <div class="step-container left">
        <div class="step-box">
          <span class="step-number">5</span>
          <h3 class="font-bold text-xl mb-2 text-primary">Admin Monitor All</h3>
          <p class="text-gray-600 text-sm">Administrators oversee all system activities from a centralized dashboard.</p>
        </div>
      </div>

      <!-- Step 6 -->
      <div class="step-container right">
        <div class="step-box">
          <span class="step-number">6</span>
          <h3 class="font-bold text-xl mb-2 text-primary">Manage Accounts</h3>
          <p class="text-gray-600 text-sm">Create, modify, and monitor student and staff accounts with ease.</p>
        </div>
      </div>

      <!-- Step 7 -->
      <div class="step-container left">
        <div class="step-box">
          <span class="step-number">7</span>
          <h3 class="font-bold text-xl mb-2 text-primary">Manage Inventory Stock</h3>
          <p class="text-gray-600 text-sm">Real-time tracking and management of all medical supplies.</p>
        </div>
      </div>

      <!-- Step 8 -->
      <div class="step-container right">
        <div class="step-box">
          <span class="step-number">8</span>
          <h3 class="font-bold text-xl mb-2 text-primary">Manage Reports</h3>
          <p class="text-gray-600 text-sm">Generate insightful reports for informed decision-making and audits.</p>
        </div>
      </div>
    </div>
  </section>


  <section id="services" class="py-20 px-6 md:px-16 bg-gray-100 text-center min-h-screen flex flex-col items-center justify-center">
    <h2 class="text-4xl font-bold mb-4">Our Services</h2>
    <p class="text-gray-600 mb-12 max-w-3xl">Beyond records and inventory — we support your whole practice with a suite of essential services.</p>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full max-w-6xl">
      <div class="p-8 bg-white rounded-xl shadow-md flex flex-col items-center animated-card">
        <div class="text-5xl mb-4">💻</div>
        <h3 class="font-bold text-xl mt-2 mb-2">Digital Data Records</h3>
        <p class="text-gray-600">Streamline documentation with secure, accessible digital records.</p>
      </div>
      <div class="p-8 bg-white rounded-xl shadow-md flex flex-col items-center animated-card">
        <div class="text-5xl mb-4">📊</div>
        <h3 class="font-bold text-xl mt-2 mb-2">Easy Tracking and Usage</h3>
        <p class="text-gray-600">Give a wonderfull experience of fast, easy and flexible Data tracking and Inventory management.</p>
      </div>
      <div class="p-8 bg-white rounded-xl shadow-md flex flex-col items-center animated-card">
        <div class="text-5xl mb-4">🛠️</div>
        <h3 class="font-bold text-xl mt-2 mb-2">Support & Training</h3>
        <p class="text-gray-600">Get dedicated support and training to ensure a smooth transition.</p>
      </div>
    </div>
  </section>
  
  
  <section id="team" class="py-20 px-6 md:px-16 bg-white text-white text-center min-h-screen flex flex-col items-center justify-center">
    <h2 class="text-4xl font-bold mb-4 text-primary">Meet Our Team</h2>
    <p class="text-gray-900 mb-12 max-w-3xl">Dedicated professionals committed to improving healthcare management.</p>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full max-w-6xl">
      <div class="p-8 bg-green-800 opacity-80 rounded-xl animated-card">
        <img src="../Images/Our Teams/Tumbado.jpg" class="text-5xl mb-4"></img>
        <h3 class="text-xl font-bold mt-2">Jobert Tumbado</h3>
        <p class="text-sm text-gray-300">Project Manager</p>
      </div>
      <div class="p-8 bg-green-800 opacity-80 rounded-xl animated-card">
        <img src="../Images/Our Teams/Malaran.jpg" class="text-5xl mb-4"></img>
        <h3 class="text-xl font-bold mt-2">Dave Malaran</h3>
        <p class="text-sm text-gray-300">Software Engineer</p>
      </div>
      <div class="p-8 bg-green-800 opacity-80 rounded-xl animated-card">
        <img src="../Images/Our Teams/Arisgado.jpg" class="text-5xl mb-4"></img>
        <h3 class="text-xl font-bold mt-2">Anthony Arisgado</h3>
        <p class="text-sm text-gray-300">Software Engineer</p>
      </div>
      <div class="p-8 bg-green-800 opacity-80 rounded-xl animated-card">
        <img src="../Images/Our Teams/DelaCruz.jpg" class="text-5xl mb-4"></img>
        <h3 class="text-xl font-bold mt-2">Elmar Reymond Dela Cruz</h3>
        <p class="text-sm text-gray-300">Research & Documentation Engineer</p>
      </div>
      <div class="p-8 bg-green-800 opacity-80 rounded-xl animated-card">
        <img src="../Images/Our Teams/Sumaylo.jpg" class="text-5xl mb-4"></img>
        <h3 class="text-xl font-bold mt-2">Mark Jhone Sumaylo</h3>
        <p class="text-sm text-gray-300">Documentation Support</p>
      </div>
      <div class="p-8 bg-green-800 opacity-80 rounded-xl animated-card">
        <img src="../Images/Our Teams/GCST.jpg" class="text-5xl mb-4"></img>
        <h3 class="text-xl font-bold mt-2">GCST</h3>
        <p class="text-sm text-gray-300">Granby Colleges of Science and Technology</p>
      </div>
    </div>
  </section>
  
  <footer class="bg-dark text-white py-12 px-6 md:px-16" id="contact">
    <div class="container mx-auto grid grid-cols-1 md:grid-cols-4 gap-8">
      <div>
        <h3 class="text-2xl font-bold mb-4 text-primary">PIAMIS</h3>
        <p class="text-gray-400 text-sm">Empowering healthcare with smart, sustainable solutions for a healthier future.</p>
      </div>
      <div>
        <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
        <ul class="space-y-2">
          <li><a href="#features" class="text-gray-400 hover:text-white transition">Features</a></li>
          <li><a href="#services" class="text-gray-400 hover:text-white transition">Services</a></li>
          <li><a href="#team" class="text-gray-400 hover:text-white transition">Our Team</a></li>
          <li><a href="#contact" class="text-gray-400 hover:text-white transition">Contact Us</a></li>
        </ul>
      </div>
      <div>
        <h4 class="text-lg font-semibold mb-4">Follow Us</h4>
        <div class="flex space-x-4">
          <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-facebook-f text-2xl"></i></a>
          <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-twitter text-2xl"></i></a>
          <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-linkedin-in text-2xl"></i></a>
          <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-instagram text-2xl"></i></a>
        </div>
      </div>
      <div>
        <h4 class="text-lg font-semibold mb-4">Contact Info</h4>
        <p class="text-gray-400 mb-2">Granby Colleges of Science and Technology</p>
        <p class="text-gray-400 mb-2">davemalaran2004@gmail.com</p>
        <p class="text-gray-400">0951 457 2814</p>
      </div>
    </div>
    <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-500 text-sm">
      <p>&copy; 2025 PIAMIS. Developed by Granby Colleges of Science and Technology 4th year students.</p>
    </div>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', () => {

      // Mobile menu toggle
      const mobileMenu = document.getElementById('mobileMenu');
      const mobileMenuBtn = document.getElementById('mobileMenuBtn');
      const closeMenuBtn = document.getElementById('closeMenuBtn');

      mobileMenuBtn.addEventListener('click', () => {
        mobileMenu.classList.remove('translate-x-full');
      });

      closeMenuBtn.addEventListener('click', () => {
        mobileMenu.classList.add('translate-x-full');
      });

      // Close menu on link click
      document.querySelectorAll('#mobileMenu a').forEach(link => {
        link.addEventListener('click', () => {
          mobileMenu.classList.add('translate-x-full');
        });
      });

      document.querySelectorAll(".step-box").forEach(box => {
        box.addEventListener("click", () => {
          document.querySelectorAll(".step-box").forEach(b => b.classList.remove("expanded"));
          box.classList.add("expanded");
        });
      });
      // Smooth scrolling for navigation links
      document.querySelectorAll('a.smooth-scroll').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
          e.preventDefault();
          const targetId = this.getAttribute('href');
          const targetElement = document.querySelector(targetId);
          if (targetElement) {
            window.scrollTo({
              top: targetElement.offsetTop - document.querySelector('header').offsetHeight, // Offset by header height
              behavior: 'smooth'
            });
          }
        });
      });

      // Scroll-based fade-in animation
      const sections = document.querySelectorAll("section");
      const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add("visible");
            // Add underline animation for the hero text when #home becomes visible
            if (entry.target.id === 'home') {
                const underlineSpan = entry.target.querySelector('.animated-underline');
                if (underlineSpan) {
                    underlineSpan.classList.add('active');
                }
            }
            observer.unobserve(entry.target); // Unobserve once animated
          }
        });
      }, {
        threshold: 0.2,
      });

      sections.forEach(section => observer.observe(section));
    });
  </script>
</body>
</html>