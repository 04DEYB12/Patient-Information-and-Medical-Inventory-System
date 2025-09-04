<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PIAIMS | Landing Page</title>
  <link rel="icon" type="image/x-icon" href="../Images/webbackg.png">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@400;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#4caf50',
            secondary: '#81c784',
            accent: '#a5d6a7',
            dark: '#002e2d',
            light: '#eaf6ea',
          },
          fontFamily: {
            sans: ['Inter', 'sans-serif'],
            comfortaa: ['Comfortaa', 'cursive'],
          },
          animation: {
            'sway': 'sway 122200s ease-in-out infinite',
          },
          keyframes: {
            sway: {
              '0%, 100%': { transform: 'rotate(0deg)' },
              '50%': { transform: 'rotate(5deg)' },
            },
          },
        },
      },
    }
  </script>
  <style type="text/tailwindcss">
    @layer utilities {
      .scrollbar-hide::-webkit-scrollbar {
        display: none;
      }
      .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
      }
    }
  </style>
</head>

<body class="font-sans bg-light text-dark">
  <header class="bg-dark text-white">
    <div class="container mx-auto px-4">
      <nav class="flex items-center justify-between py-4">
        <div class="font-comfortaa font-bold text-xl tracking-wider select-none text-yellow-400 ml-10" tabindex="0">
          Patient Information & Medical Inventory System
        </div>
        <ul class="flex items-center space-x-8">
          <li><a href="#" class="font-comfortaa font-semibold hover:text-accent transition-colors" tabindex="0">Home</a></li>
          <li><a href="#" class="font-comfortaa font-semibold hover:text-accent transition-colors" tabindex="0">User Guide</a></li>
          <li><a href="#" class="font-comfortaa font-semibold hover:text-accent transition-colors" tabindex="0">About Us</a></li>
          <li><a href="#" class="font-comfortaa font-semibold hover:text-accent transition-colors" tabindex="0">Feedbacks</a></li>
          <li><a href="#" class="font-comfortaa font-semibold hover:text-accent transition-colors" tabindex="0">Contacts</a></li>
          <button onclick="window.location.href='Loginpage.php'" 
                  class="bg-transparent text-yellow-400 font-comfortaa font-bold py-2 px-4 rounded hover:bg-opacity-10 hover:bg-white transition-colors">
            Sign In
          </button>
        </ul>
      </nav>
    </div>
  </header>

  <section class="flex flex-col md:flex-row items-center justify-center bg-dark text-white py-20 px-4 md:px-8 lg:px-16 gap-12">
    <div class="max-w-2xl">
      <h1 class="font-comfortaa font-bold text-4xl md:text-5xl lg:text-6xl mb-6">Grow Healthier Together</h1>
      <p class="text-xl mb-8">Empowering care with vibrant patient records and eco-friendly inventory management for a flourishing healthcare system.</p>
      <button onclick="window.location.href='Loginpage.php'" class="bg-white text-primary font-bold py-3 px-8 rounded-full shadow-lg hover:shadow-xl hover:scale-105 transition-all transform">Get Started</button>
    </div>
    <div class="w-full max-w-2xl h-96 md:h-[500px] bg-cover bg-center bg-no-repeat" style="background-image: url('../Images/webbackg.png');">
    </div>
  </section>

<main class="bg-light">
  <!-- Features Section -->
  <div class="text-center max-w-4xl mx-auto py-16 px-4">
    <h2 class="font-comfortaa text-4xl font-bold mb-4">Key Features</h2>
    <p class="text-lg text-gray-600 mb-12">Overview for the process flow of the system.</p>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <article class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 transform hover:-translate-y-1" tabindex="0" aria-describedby="feature1desc">
        <div class="text-5xl mb-4" aria-hidden="true">🌿</div>
        <h3 class="font-comfortaa text-2xl font-bold mb-3">Patient Information</h3>
        <p id="feature1desc" class="text-gray-600">Nurture your patients' health by securely storing and managing their profiles and care plans.</p>
      </article>

      <article class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 transform hover:-translate-y-1" tabindex="0" aria-describedby="feature2desc">
        <div class="text-5xl mb-4 text-green-700" aria-hidden="true">🍃</div>
        <h3 class="font-comfortaa text-2xl font-bold mb-3">Inventory Management</h3>
        <p id="feature2desc" class="text-gray-600">Keep your medical supplies fresh and stocked with green-conscious tracking and smart alerts.</p>
      </article>

      <article class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow duration-300 transform hover:-translate-y-1" tabindex="0" aria-describedby="feature3desc">
        <div class="text-5xl mb-4" aria-hidden="true">🌱</div>
        <h3 class="font-comfortaa text-2xl font-bold mb-3">Reports & Analytics</h3>
        <p id="feature3desc" class="text-gray-600">Cultivate insights through reports that help optimize resources and patient care growth.</p>
      </article>
    </div>
  </div>

  <!-- How It Works Section -->
  <section class="py-16 px-4 bg-white">
    <div class="max-w-4xl mx-auto text-center">
      <h2 class="font-comfortaa text-4xl font-bold mb-4">How It Works</h2>
      <p class="text-lg text-gray-600 mb-12 max-w-2xl mx-auto">Simple steps to streamline your healthcare operations and keep everything growing smoothly.</p>
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <article class="bg-light p-6 rounded-2xl shadow-md hover:shadow-lg transition-shadow" tabindex="0">
          <div class="text-5xl mb-4" aria-hidden="true">📝</div>
          <h3 class="font-comfortaa text-xl font-bold mb-2">1. Register & Manage Patients</h3>
          <p class="text-gray-600">Easily add and update comprehensive patient profiles and medical histories.</p>
        </article>
        
        <article class="bg-light p-6 rounded-2xl shadow-md hover:shadow-lg transition-shadow" tabindex="0">
          <div class="text-5xl mb-4" aria-hidden="true">📦</div>
          <h3 class="font-comfortaa text-xl font-bold mb-2">2. Track Inventory</h3>
          <p class="text-gray-600">Monitor supply stock levels with automatic alerts for replenishments and expirations.</p>
        </article>
        
        <article class="bg-light p-6 rounded-2xl shadow-md hover:shadow-lg transition-shadow" tabindex="0">
          <div class="text-5xl mb-4" aria-hidden="true">📈</div>
          <h3 class="font-comfortaa text-xl font-bold mb-2">3. Analyze & Improve</h3>
          <p class="text-gray-600">Generate detailed reports to make data-driven decisions for better patient care.</p>
        </article>
      </div>
    </div>
  </section>

  <!-- Testimonials Section -->
  <section class="py-16 px-4 bg-green-900 text-white">
    <div class="max-w-6xl mx-auto">
      <h2 class="font-comfortaa text-4xl font-bold text-center mb-12 text-yellow-400">What Our Doctors Say</h2>
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <article class="bg-white bg-opacity-15 p-6 rounded-2xl backdrop-blur-sm hover:bg-opacity-25 transition-all" tabindex="0">
          <p class="italic mb-4">"HealthTrack transformed our clinic's workflow. Patient info and inventory are so easy to manage now!"</p>
          <div class="flex items-center">
            <div class="w-12 h-12 rounded-full bg-accent mr-3"></div>
            <span class="font-bold text-yellow-300">Dr. Emon</span>
          </div>
        </article>
        
        <article class="bg-white bg-opacity-15 p-6 rounded-2xl backdrop-blur-sm hover:bg-opacity-25 transition-all" tabindex="0">
          <p class="italic mb-4">"The automated alerts for supplies helped us avoid shortages and improve patient care."</p>
          <div class="flex items-center">
            <div class="w-12 h-12 rounded-full bg-accent mr-3"></div>
            <span class="font-bold text-yellow-300">Dr. Boknoy</span>
          </div>
        </article>
        
        <article class="bg-white bg-opacity-15 p-6 rounded-2xl backdrop-blur-sm hover:bg-opacity-25 transition-all" tabindex="0">
          <p class="italic mb-4">"The reports and analytics give us crucial insights that we previously missed."</p>
          <div class="flex items-center">
            <div class="w-12 h-12 rounded-full bg-accent mr-3"></div>
            <span class="font-bold text-yellow-300">Dr. Ondoy</span>
          </div>
        </article>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="py-16 px-4 bg-primary text-white text-center">
    <div class="max-w-3xl mx-auto">
      <h2 class="font-comfortaa text-4xl font-bold mb-4 text-yellow-300">Ready to Grow Your Practice?</h2>
      <p class="text-xl mb-8 max-w-2xl mx-auto">Join HealthTrack today and nurture a healthier, more efficient healthcare environment.</p>
      <button onclick="window.location.href='Loginpage.php'" 
              class="bg-yellow-400 text-dark font-bold py-3 px-8 rounded-full hover:bg-yellow-300 hover:scale-105 transition-all transform">
        Sign In
      </button>
    </div>
  </section>
</main>

<footer class="bg-dark text-white text-center py-6">
  <p>&copy; Granby Colleges of Science and Technology.</p>
</footer>
</body>
</html>