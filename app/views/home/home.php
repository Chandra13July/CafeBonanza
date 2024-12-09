    <style>
        /* Padding dan ukuran kontainer untuk layar besar */
        @media (min-width: 768px) {
            .container {
                padding-left: 2cm;
                padding-right: 2cm;
                max-width: 6xl;
            }
        }
    </style>

    <script>
        // Fungsi untuk menggulir ke bagian tertentu di halaman
        function scrollToSection() {
            document.getElementById('coffee-point-of-view').scrollIntoView({
                behavior: 'smooth'
            });
        }
    </script>

    <body class="font-[Alexandria]">

        <!-- Hero Section: Pengenalan Cafe Bonanza -->
        <section class="py-8 px-4 md:px-6 container">
            <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center">
                <div class="w-full md:w-1/2">
                    <!-- Gambar utama halaman depan -->
                    <img alt="A cozy café interior" class="mx-auto w-full h-auto" src="<?= BASEURL; ?>/img/home1.png" />
                </div>
                <div class="w-full md:w-1/2 text-left md:pl-6 mt-4 md:mt-0">
                    <h1 class="text-4xl md:text-5xl font-bold">CAFE BONANZA</h1>
                    <p class="mt-2 md:mt-4 text-base md:text-lg">Cafe Bonanza is a home for anyone who needs a break. Come here anytime if you're feeling tired.</p>
                    <button class="mt-4 md:mt-6 px-4 md:px-6 py-2 bg-black text-white font-bold" onclick="scrollToSection()">SEE MORE</button>
                </div>
            </div>
        </section>

        <!-- Section: Filosofi Kopi -->
        <section class="flex items-center justify-center bg-gray-200 h-24 px-4 md:px-6 container" id="coffee-point-of-view">
            <h2 class="text-xl font-semibold text-center">OUR PERSPECTIVE ON COFFEE</h2>
        </section>

        <!-- Gallery: Koleksi Tema Kopi -->
        <div class="max-w-6xl mx-auto overflow-x-auto px-4 md:px-6 container">
            <div class="flex space-x-4 mt-4">
                <!-- Gambar 1: Morning Coffee -->
                <div class="flex-shrink-0 w-1/2 md:w-1/4">
                    <div class="text-left">
                        <img alt="Morning coffee" class="mx-auto w-full h-auto" src="https://storage.googleapis.com/a1aa/image/Oh6pbqdeUATdL6fpfcUKLLuewSowceMGfsUnS8vxKuUju7LeJA.jpg" />
                        <p class="mt-2 text-sm font-thin text-left">MORNING COFFEE</p>
                        <p class="text-left text-xs">2024</p>
                    </div>
                </div>

                <!-- Gambar 2: Coffee and Nature -->
                <div class="flex-shrink-0 w-1/2 md:w-1/4">
                    <div class="text-left">
                        <img alt="Coffee with nature vibes" class="mx-auto w-full h-auto" src="https://storage.googleapis.com/a1aa/image/WuDvHfbK5zXrLqmUmKptkHM0GoPk7p0WDVGWsSDqME6c3X8JA.jpg" />
                        <p class="mt-2 text-sm font-thin text-left">COFFEE AND NATURE</p>
                        <p class="text-left text-xs">2024</p>
                    </div>
                </div>

                <!-- Gambar 3: Coffee for Work -->
                <div class="flex-shrink-0 w-1/2 md:w-1/4">
                    <div class="text-left">
                        <img alt="Coffee for work" class="mx-auto w-full h-auto" src="https://storage.googleapis.com/a1aa/image/h7DAL3AwTN4KLRjlqX0coQ0FxAUKIDdAaxGve9howODc3X8JA.jpg" />
                        <p class="mt-2 text-sm font-thin text-left">COFFEE FOR WORK</p>
                        <p class="text-left text-xs">2024</p>
                    </div>
                </div>

                <!-- Gambar 4: Sweet Coffee for Your Day -->
                <div class="flex-shrink-0 w-1/2 md:w-1/4">
                    <div class="text-left">
                        <img alt="Sweet coffee for your day" class="mx-auto w-full h-auto" src="https://storage.googleapis.com/a1aa/image/1YFzMOdd3QbRKlVyXzxeXi2e0MC3BwKr4etVhEUlrr66dfiPB.jpg" />
                        <p class="mt-2 text-sm font-thin text-left">SWEET COFFEE FOR YOUR DAY</p>
                        <p class="text-left text-xs">2024</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call-to-Action: Pesan Sekarang -->
        <section class="bg-gray-200 py-12 text-center mt-8 px-4 md:px-6 container">
            <h2 class="text-4xl font-semibold mb-4">INTERESTED IN OUR COFFEE OR OTHER DISHES?</h2>
            <p class="mb-6">ORDER NOW AND MAKE YOUR DAY ENJOYABLE WITH IT, WE GUARANTEE YOU’LL LOVE IT!!</p>
            <button class="px-6 py-2 bg-black text-white font-bold" onclick="window.location.href='<?= BASEURL; ?>/home/menu'">ORDER NOW</button>
        </section>

        <!-- About Us: Tentang Kami -->
        <section class="py-12 px-4 md:px-6 container">
            <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:order-last">
                    <img alt="The exterior of Bonanza Café building" class="w-full h-auto" src="https://storage.googleapis.com/a1aa/image/kG4KqZqZ00b6HFn6vddt3fYlLbAfe9srYSpRVYNwaoL8dfiPB.jpg" />
                </div>
                <div>
                    <h2 class="text-5xl font-semibold mb-4">MORE ABOUT US</h2>
                    <p class="mb-4 text-sm">BONANZA CAFÉ IS A PLACE WHERE FLAVOR AND WARMTH COME TOGETHER, DESIGNED TO PROVIDE AN UNFORGETTABLE EXPERIENCE FOR EVERY VISITOR.</p>
                    <p class="mb-4 text-sm">AT BONANZA, WE SERVE A WIDE VARIETY OF DISHES MADE WITH HIGH-QUALITY INGREDIENTS, FROM LOCAL DELICACIES TO INTERNATIONAL CUISINES.</p>
                    <p class="mb-4 text-sm">FEEL THE UNIQUE ATMOSPHERE AND TASTE OF BONANZA CAFÉ! VISIT "ABOUT US" TO LEARN MORE ABOUT OUR JOURNEY IN SERVING THE BEST.</p>
                    <button class="px-6 py-2 bg-black text-white font-bold" onclick="window.location.href='<?= BASEURL; ?>/home/about'">SEE MORE</button>
                </div>
            </div>
        </section>

        <!-- Section: Testimoni Pelanggan -->
        <section class="py-12 px-4 md:px-6 container bg-gray-100">
            <h2 class="text-4xl font-semibold text-center mb-8">WHAT OUR CUSTOMERS SAY</h2>
            <div class="max-w-6xl mx-auto">

                <!-- Tampilan Desktop (4 kolom) -->
                <div class="hidden md:grid grid-cols-4 gap-6">
                    <!-- Testimoni 1 -->
                    <div class="bg-white p-6 shadow-md rounded-md">
                        <p class="italic">"The coffee here is absolutely amazing! The aroma and taste are unmatched."</p>
                        <p class="mt-4 font-bold">- Alief Chandra</p>
                    </div>

                    <!-- Testimoni 2 -->
                    <div class="bg-white p-6 shadow-md rounded-md">
                        <p class="italic">"Great place to hang out with friends. The cozy ambiance is a perfect touch."</p>
                        <p class="mt-4 font-bold">- Raffi</p>
                    </div>

                    <!-- Testimoni 3 -->
                    <div class="bg-white p-6 shadow-md rounded-md">
                        <p class="italic">"I love how friendly the staff is! They always make me feel welcome."</p>
                        <p class="mt-4 font-bold">- Raihan</p>
                    </div>

                    <!-- Testimoni 4 -->
                    <div class="bg-white p-6 shadow-md rounded-md">
                        <p class="italic">"Their desserts are a must-try! The perfect companion to a warm cup of coffee."</p>
                        <p class="mt-4 font-bold">- Finno</p>
                    </div>
                </div>

                <!-- Tampilan Mobile (scrollable horizontal) -->
                <div class="md:hidden overflow-x-auto mt-8">
                    <div class="flex space-x-6">
                        <!-- Testimoni 1 -->
                        <div class="bg-white p-6 shadow-md rounded-md flex-shrink-0 w-80">
                            <p class="italic">"The coffee here is absolutely amazing! The aroma and taste are unmatched."</p>
                            <p class="mt-4 font-bold">- Alief Chandra</p>
                        </div>

                        <!-- Testimoni 2 -->
                        <div class="bg-white p-6 shadow-md rounded-md flex-shrink-0 w-80">
                            <p class="italic">"Great place to hang out with friends. The cozy ambiance is a perfect touch."</p>
                            <p class="mt-4 font-bold">- Raffi</p>
                        </div>

                        <!-- Testimoni 3 -->
                        <div class="bg-white p-6 shadow-md rounded-md flex-shrink-0 w-80">
                            <p class="italic">"I love how friendly the staff is! They always make me feel welcome."</p>
                            <p class="mt-4 font-bold">- Raihan</p>
                        </div>

                        <!-- Testimoni 4 -->
                        <div class="bg-white p-6 shadow-md rounded-md flex-shrink-0 w-80">
                            <p class="italic">"Their desserts are a must-try! The perfect companion to a warm cup of coffee."</p>
                            <p class="mt-4 font-bold">- Finno</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </body>