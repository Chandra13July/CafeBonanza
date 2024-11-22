<style>
    body {
        font-family: 'Alexandria', sans-serif;
    }

    @media (max-width: 640px) {
        .about-section h1 {
            font-size: 2rem;
        }

        .about-section p {
            font-size: 0.875rem;
        }

        .about-section button {
            padding: 0.5rem 1.5rem;
            font-size: 0.875rem;
        }

        .about-section img {
            width: 100%;
            height: auto;
            margin: auto;
        }
    }

    .about-section img {
        max-width: 80%;
        height: auto;
        margin-left: auto;
    }
</style>

<body class="bg-gray-100 text-gray-800">
    <section class="bg-white py-16 about-section">
        <div class="container mx-auto flex flex-col md:flex-row items-center px-4 md:px-16">
            <div class="md:w-1/2">
                <div class="mb-4">
                    <span class="bg-gray-200 text-gray-600 py-1 px-3 rounded-full text-base md:text-lg">Cafe Bonanza</span>
                </div>
                <h1 class="text-5xl md:text-6xl font-bold mb-4">About Cafe Bonanza</h1>
                <p class="text-base md:text-lg mb-6">
                    Cafe Bonanza started as an old truck garage inherited from our grandfather, which our family transformed into a cozy and warm café. Today, Bonanza blends rustic charm with modern touches, offering artisan coffee and delicious dishes in a setting rich with family history.
                </p>
                <a href="#service-section" class="bg-black text-white py-2 px-6 inline-block" id="view-more">View More</a>
            </div>
            <div class="md:w-1/2 mt-8 md:mt-0 flex justify-start md:justify-end">
                <img alt="Interior of Cafe Bonanza with modern rustic design" class="rounded-lg shadow-lg" src="https://storage.googleapis.com/a1aa/image/auRIqXeUGIRYf0whzDV4Qd4jm7d0H8ersP0eDG7vesYoK85dC.jpg" />
            </div>
        </div>
    </section>
    <section id="service-section" class="bg-gray-100 py-16 service-section">
        <div class="container mx-auto text-center px-4 md:px-16">
            <div class="mb-8">
                <span class="bg-white text-gray-600 py-1 px-3 text-sm">Our Services</span>
            </div>
            <h2 class="text-3xl font-bold mb-12">What Do We Offer You?</h2>
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-8">
                <div class="bg-white p-8 shadow-lg">
                    <i class="fas fa-utensils text-4xl text-gray-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Diverse Menu</h3>
                    <p>
                        Cafe Bonanza serves a variety of specialty coffees, refreshing beverages, and snacks like croissants and sandwiches. Everything is crafted with high-quality ingredients for a delightful dining experience.
                    </p>
                </div>
                <div class="bg-white p-8 shadow-lg">
                    <i class="fas fa-chair text-4xl text-gray-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Cozy Atmosphere</h3>
                    <p>
                        Cafe Bonanza offers a warm and inviting ambiance, combining rustic charm and modern comfort. Perfect for relaxing or gathering with friends.
                    </p>
                </div>
                <div class="bg-white p-8 shadow-lg">
                    <i class="fas fa-wifi text-4xl text-gray-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Free WiFi</h3>
                    <p>
                        Enjoy free internet access at Cafe Bonanza, ideal for work, study, or simply relaxing with your favorite coffee.
                    </p>
                </div>
                <div class="bg-white p-8 shadow-lg">
                    <i class="fas fa-coffee text-4xl text-gray-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Quality Coffee</h3>
                    <p>
                        We use carefully selected coffee beans to provide you with the best coffee flavors. Each cup is prepared with utmost care, perfect for true coffee lovers.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="bg-white py-16">
        <div class="container mx-auto px-4 md:px-16">
            <div class="text-left mb-8">
                <span class="bg-gray-200 text-gray-600 py-1 px-3 rounded-none text-sm md:text-lg">Our Mission</span>
            </div>
            <div class="flex flex-col md:flex-row justify-start space-y-8 md:space-y-0 md:space-x-8">
                <div class="md:w-1/2 px-4">
                    <h3 class="text-3xl font-bold mb-4">What Are We Aiming For?</h3>
                    <p class="text-sm mb-6">
                        Cafe Bonanza's mission is to create a place that not only offers quality coffee and delicious dishes but also provides a comfortable and enjoyable experience for every guest. We are committed to maintaining a warm and welcoming atmosphere, suitable for relaxing, gathering, or working.
                    </p>
                    <button class="bg-black text-white py-2 px-6 mt-4">Join Us</button>
                </div>
                <div class="md:w-1/2 px-4">
                    <h3 class="text-xl font-bold mb-4">What Do We Prepare to Serve Customers?</h3>
                    <p class="mb-4">
                        At Cafe Bonanza, we deeply value every customer who visits, and we are committed to providing the best service. We carefully and attentively prepare various aspects:
                    </p>
                    <ul class="list-disc list-inside mb-4">
                        <li>A cozy and pleasant atmosphere</li>
                        <li>The best quality ingredients</li>
                        <li>Convenience and efficiency</li>
                    </ul>
                    <p class="font-bold">Niko Kurmawan</p>
                    <p>Owner</p>
                </div>
            </div>
        </div>
    </section>
    <section class="bg-gray-100 py-16">
        <div class="container mx-auto">
            <div class="text-center mb-8">
                <span class="bg-gray-300 text-gray-600 py-1 px-3 rounded-full text-sm">Our Vision</span>
            </div>
            <h2 class="text-3xl font-bold text-center mb-12"></h2>
            <div class="flex flex-col md:flex-row justify-center space-y-8 md:space-y-0 md:space-x-8">
                <div class="md:w-1/2 px-8">
                    <img alt="Cafe Bonanza staff serving customers" class="rounded-lg shadow-lg" height="400" src="https://storage.googleapis.com/a1aa/image/xJ7RmleqftvhskwH1ZigfZqwp5SGCHR1fyoa2OC830oYFe5dC.jpg" width="600" />
                </div>
                <div class="md:w-1/2 px-8">
                    <h3 class="text-4xl font-bold mb-4">What Is Our Goal?</h3>
                    <p>
                        To become a café known and valued for its premium coffee, delightful dishes, and cozy ambiance, where every visit becomes a memorable and enjoyable moment. We strive to innovate, maintain good customer relationships, and deliver friendly and professional service. Cafe Bonanza aims to be a favorite destination for relaxation and gathering.
                    </p>
                    <button class="bg-black text-white py-2 px-6 mt-4">
                        <a href="<?= BASEURL; ?>/auth/login" class="text-white">Join Us</a>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.querySelector('#view-more').addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    </script>
</body>