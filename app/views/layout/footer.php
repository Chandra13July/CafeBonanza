    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
    </head>

    <body class="bg-gray-200 min-h-screen flex flex-col justify-end">
        <footer class="bg-white py-12">
            <div class="container mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 px-4 md:px-12 lg:px-24">
                <div class="aboutus">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">About Us</h2>
                    <p class="text-gray-600 text-base">Welcome to Cafe Bonanza! We offer a unique dining experience with a variety of delicious dishes and quality beverages.</p>
                    <ul class="flex space-x-4 mt-4">
                        <li><a href="#" class="text-gray-800 hover:text-gray-600 transition duration-300 text-lg"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="#" class="text-gray-800 hover:text-gray-600 transition duration-300 text-lg"><i class="fab fa-youtube"></i></a></li>
                        <li><a href="#" class="text-gray-800 hover:text-gray-600 transition duration-300 text-lg"><i class="fab fa-tiktok"></i></a></li>
                        <li><a href="#" class="text-gray-800 hover:text-gray-600 transition duration-300 text-lg"><i class="fab fa-twitter"></i></a></li>
                    </ul>
                </div>
                <div class="quicklinks">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Navigation</h2>
                    <ul class="space-y-2">
                        <li><a href="<?= BASEURL; ?>/home/index" class="text-gray-600 hover:text-gray-800 transition duration-300 text-base">Home</a></li>
                        <li><a href="<?= BASEURL; ?>/home/about" class="text-gray-600 hover:text-gray-800 transition duration-300 text-base">About</a></li>
                        <li><a href="<?= BASEURL; ?>/home/menu" class="text-gray-600 hover:text-gray-800 transition duration-300 text-base">Menu</a></li>
                        <li><a href="<?= BASEURL; ?>/home/gallery" class="text-gray-600 hover:text-gray-800 transition duration-300 text-base">Gallery</a></li>
                        <li><a href="<?= BASEURL; ?>/home/contact" class="text-gray-600 hover:text-gray-800 transition duration-300 text-base">Contact</a></li>
                    </ul>
                </div>
                <div class="quicklinks">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Services</h2>
                    <ul class="space-y-2">
                        <li class="text-gray-600 text-base">Free Wi-Fi</li>
                        <li class="text-gray-600 text-base">Live Music</li>
                        <li class="text-gray-600 text-base">Billiards</li>
                    </ul>
                </div>
                <div class="blog">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Our Blog</h2>
                    <ul class="space-y-2">
                        <li class="text-gray-600 hover:text-gray-800 transition duration-300 text-base">Culinary Tips & Tricks</a></li>
                        <li class="text-gray-600 hover:text-gray-800 transition duration-300 text-base">Specialty Coffee Guide</a></li>
                        <li class="text-gray-600 hover:text-gray-800 transition duration-300 text-base">Best Dishes at Cafe Bonanza</a></li>
                    </ul>
                </div>
                <div class="contact">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Contact Us</h2>
                    <ul class="space-y-4">
                        <li class="flex items-center space-x-2">
                            <i class="fas fa-phone text-gray-800 text-lg"></i>
                            <a href="tel:0878-6630-1974" class="text-gray-600 hover:text-gray-800 transition duration-300 text-base">0859-6441-6174</a>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fas fa-envelope text-gray-800 text-lg"></i>
                            <a href="mailto:chandra13july2004@gmail.com" class="text-gray-600 hover:text-gray-800 transition duration-300 text-base">bonanzacafe3@gmail.com</a>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fas fa-map-marker-alt text-gray-800 text-lg"></i>
                            <a href="#" class="text-gray-600 hover:text-gray-800 transition duration-300 text-base">Jl. Pancur, Sumpelan Utara, Lumutan, Kec. Prajekan, Kabupaten Bondowoso, East Java 68284</a>
                        </li>
                        <li class="flex items-center space-x-2">
                            <i class="fas fa-clock text-gray-800 text-lg"></i>
                            <span class="text-gray-600 text-base">Monday-Sunday: 19:00-2:00</span>
                        </li>
                    </ul>
                </div>
            </div>
        </footer>
        <div class="bg-white py-4 text-center text-gray-400 border-t border-gray-300">
            <p class="text-base">&copy; 2024 Cafe Bonanza. All Rights Reserved</p>
        </div>
    </body>