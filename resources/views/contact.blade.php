<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - VisuMonsun ID</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 flex flex-col min-h-screen">

    <!-- Header / Navigation Bar -->
    <header class="bg-white shadow-md w-full p-4 flex justify-between items-center z-20">
        <!-- Logo -->
        <div class="flex items-center space-x-3">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>
            <h1 class="text-xl font-bold text-gray-800">VisuMonsun ID</h1>
        </div>
        
        <!-- Navigasi (Contact Aktif) -->
        <nav class="hidden md:flex items-center space-x-6">
            <a href="/" class="text-gray-500 hover:text-blue-600 transition duration-300">Dashboard</a>
            <a href="/statistics" class="text-gray-500 hover:text-blue-600 transition duration-300">Data Statistics</a>
            <a href="/forecast" class="text-gray-500 hover:text-blue-600 transition duration-300">Forecast</a>
            <a href="/about" class="text-gray-500 hover:text-blue-600 transition duration-300">About</a>
            <a href="/contact" class="text-blue-600 font-semibold border-b-2 border-blue-600 pb-1">Contact</a>
        </nav>
        
        <!-- Tombol EN -->
        <div class="flex items-center space-x-4">
             <button class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-50 transition duration-300">EN</button>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow p-4 lg:p-6 space-y-6">
        <div class="max-w-4xl mx-auto">
            <!-- Page Title -->
            <h1 class="text-4xl font-bold text-gray-800 text-center mb-8">Get In Touch</h1>
            <p class="text-center text-gray-600 text-lg mb-10">Have questions about our data, methodology, or partnership opportunities? We'd love to hear from you.</p>
            
            <!-- Contact Form & Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                
                <!-- Contact Form -->
                <div class="bg-white p-8 rounded-xl shadow-lg">
                    <h2 class="text-2xl font-bold mb-6">Send Us a Message</h2>
                    <form action="#" method="POST" class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700">Full Name</label>
                            <input type="text" id="name" name="name" class="mt-1 w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="John Doe">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700">Email</label>
                            <input type="email" id="email" name="email" class="mt-1 w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="you@example.com">
                        </div>
                         <div>
                            <label for="subject" class="block text-sm font-semibold text-gray-700">Subject</label>
                            <input type="text" id="subject" name="subject" class="mt-1 w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Data Inquiry">
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-semibold text-gray-700">Message</label>
                            <textarea id="message" name="message" rows="5" class="mt-1 w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Your message..."></textarea>
                        </div>
                        <div>
                            <button type="submit" class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Contact Info (BAGIAN YANG DIPERBAIKI) -->
                <div class="space-y-6">
                    <div class="bg-white p-8 rounded-xl shadow-lg flex items-start space-x-4">
                        <!-- Icon -->
                        <div class="mt-1">
                            <svg class="w-8 h-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">Email</h3>
                            <p class="text-gray-600 mt-1">Contact us for data inquiries or technical support.</p>
                            <a href="mailto:info@visumonsun.id" class="text-blue-600 font-medium hover:underline mt-2 inline-block">info@visumonsun.id</a>
                        </div>
                    </div>

                     <div class="bg-white p-8 rounded-xl shadow-lg flex items-start space-x-4">
                        <!-- Icon -->
                        <div class="mt-1">
                            <svg class="w-8 h-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">Address</h3>
                            <p class="text-gray-600 mt-1">VisuMonsun Research Institute</p>
                            <p class="text-gray-600 mt-1">123 Climate Science Rd, Jakarta, Indonesia</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="w-full bg-white p-4 text-center text-gray-500 text-sm mt-8 shadow-inner border-t border-gray-200">
        © 2025 VisuMonsun ID. All data provided by Copernicus Climate Change Service.
    </footer>
    
    <!-- JavaScript Kustom Anda -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>