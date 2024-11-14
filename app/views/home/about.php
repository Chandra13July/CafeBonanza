<style>
    body {
        font-family: 'Alexandria', sans-serif;
    }

    /* Custom styles for mobile */
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

    /* Styles for larger screens */
    .about-section img {
        max-width: 80%;
        height: auto;
        margin-left: auto;
    }
</style>
</head>

<body class="bg-gray-100 text-gray-800">
    <!-- Section 1 -->
    <section class="bg-white py-16 about-section">
        <div class="container mx-auto flex flex-col md:flex-row items-center px-4 md:px-16">
            <div class="md:w-1/2">
                <div class="mb-4">
                    <span class="bg-gray-200 text-gray-600 py-1 px-3 rounded-full text-base md:text-lg">Cafe Bonanza</span>
                </div>
                <h1 class="text-5xl md:text-6xl font-bold mb-4">Tentang Cafe Bonanza</h1>
                <p class="text-base md:text-lg mb-6">
                    Cafe Bonanza awalnya adalah garasi truk peninggalan kakek yang diubah oleh keluarga menjadi kafe yang hangat dan nyaman. Kini, Bonanza menggabungkan nuansa rustic dengan sentuhan modern, menyajikan kopi artisan dan hidangan lezat dalam suasana penuh sejarah keluarga.
                </p>
                <a href="#service-section" class="bg-black text-white py-2 px-6 inline-block" id="view-more">View More</a>
            </div>
            <div class="md:w-1/2 mt-8 md:mt-0 flex justify-start md:justify-end">
                <img alt="Interior of Cafe Bonanza with modern rustic design" class="rounded-lg shadow-lg" src="https://storage.googleapis.com/a1aa/image/auRIqXeUGIRYf0whzDV4Qd4jm7d0H8ersP0eDG7vesYoK85dC.jpg" />
            </div>
        </div>
    </section>

    <!-- Section 2 -->
    <section id="service-section" class="bg-gray-100 py-16 service-section">
        <div class="container mx-auto text-center px-4 md:px-16">
            <div class="mb-8">
                <span class="bg-white text-gray-600 py-1 px-3 text-sm">Servis Kami</span>
            </div>
            <h2 class="text-3xl font-bold mb-12">Apa yg Kami Sediakan untuk Anda?</h2>
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-8">
                <div class="bg-white p-8 shadow-lg">
                    <i class="fas fa-utensils text-4xl text-gray-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Menu yg Beragam</h3>
                    <p>
                        Cafe Bonanza menyajikan aneka kopi spesial, minuman segar, serta camilan seperti croissant dan sandwich. Semuanya dibuat dengan bahan berkualitas untuk pengalaman kuliner yang nikmat.
                    </p>
                </div>
                <div class="bg-white p-8 shadow-lg">
                    <i class="fas fa-chair text-4xl text-gray-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Tempat yg Nyaman</h3>
                    <p>
                        Cafe Bonanza hadir dengan suasana hangat dan nyaman, menggabungkan sentuhan rustic dan modern. Cocok untuk bersantai atau berkumpul.
                    </p>
                </div>
                <div class="bg-white p-8 shadow-lg">
                    <i class="fas fa-wifi text-4xl text-gray-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Free WiFi</h3>
                    <p>
                        Nikmati akses internet gratis di Cafe Bonanza, sempurna untuk bekerja, belajar, atau sekadar bersantai sambil menikmati kopi favorit Anda.
                    </p>
                </div>
                <div class="bg-white p-8 shadow-lg">
                    <i class="fas fa-coffee text-4xl text-gray-600 mb-4"></i>
                    <h3 class="text-xl font-bold mb-2">Kopi Berkualitas</h3>
                    <p>
                        Kami menggunakan biji kopi pilihan untuk memberikan Anda rasa kopi terbaik. Setiap cangkir disiapkan dengan perhatian penuh, sempurna untuk pecinta kopi sejati.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 3 -->
    <section class="bg-white py-16">
        <div class="container mx-auto px-4 md:px-16">
            <div class="text-left mb-8">
                <span class="bg-gray-200 text-gray-600 py-1 px-3 rounded-none text-sm md:text-lg">Misi Kami</span>
            </div>
            <div class="flex flex-col md:flex-row justify-start space-y-8 md:space-y-0 md:space-x-8">
                <div class="md:w-1/2 px-4">
                    <h3 class="text-3xl font-bold mb-4">Apa yg Ingin Kami Gapai?</h3>
                    <p class="text-sm mb-6">
                        Misi Cafe Bonanza adalah menciptakan sebuah tempat yang tidak hanya menyajikan kopi berkualitas dan hidangan lezat, tetapi juga memberikan pengalaman yang nyaman dan menyenangkan bagi setiap pengunjung. Kami berkomitmen untuk menjaga atmosfer yang hangat dan ramah, serta menyediakan suasana yang cocok untuk bersantai, berkumpul, atau bekerja.
                    </p>
                    <button class="bg-black text-white py-2 px-6 mt-4">Gabung Bersama Kami</button>
                </div>
                <div class="md:w-1/2 px-4">
                    <h3 class="text-xl font-bold mb-4">Apa yg Anda Persiapkan untuk Melayani Customer?</h3>
                    <p class="mb-4">
                        Di Cafe Bonanza, kami sangat menghargai setiap pelanggan yang datang, dan kami berkomitmen untuk memberikan pelayanan yang terbaik. Kami mempersiapkan berbagai hal dengan cermat dan penuh perhatian:
                    </p>
                    <ul class="list-disc list-inside mb-4">
                        <li>Suasana yang nyaman dan menyenangkan</li>
                        <li>Kualitas bahan baku terbaik</li>
                        <li>Kemudahan dan kecepatan</li>
                    </ul>
                    <p class="font-bold">Niko Kurmawan</p>
                    <p>Owner</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 4 -->
    <section class="bg-gray-100 py-16">
        <div class="container mx-auto">
            <div class="text-center mb-8">
                <span class="bg-gray-300 text-gray-600 py-1 px-3 rounded-full text-sm">Visi Kami</span>
            </div>
            <h2 class="text-3xl font-bold text-center mb-12"></h2>
            <div class="flex flex-col md:flex-row justify-center space-y-8 md:space-y-0 md:space-x-8">
                <div class="md:w-1/2 px-8">
                    <img alt="Cafe Bonanza staff serving customers" class="rounded-lg shadow-lg" height="400" src="https://storage.googleapis.com/a1aa/image/xJ7RmleqftvhskwH1ZigfZqwp5SGCHR1fyoa2OC830oYFe5dC.jpg" width="600" />
                </div>
                <div class="md:w-1/2 px-8">
                    <h3 class="text-4xl font-bold mb-4">Apa Tujuan Kami?</h3>
                    <p>
                        Menjadi kafe yang dikenal dan dihargai karena kualitas kopi terbaik, hidangan yang menakjubkan, dan atmosfer yang nyaman. Di mana setiap kunjungan menjadi momen berharga yang menyenangkan. Kami berusaha untuk memberikan komitmen untuk terus berinovasi, menjaga hubungan pelanggan yang baik, serta memberikan pelayanan yang ramah dan profesional. Cafe Bonanza menjadi tempat favorit yang selalu dinantikan oleh para pengunjung, baik untuk bersantai maupun berkumpul.
                    </p>
                    <button class="bg-black text-white py-2 px-6 mt-4">Gabung Bersama Kami</button>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Smooth scrolling for the "View More" button
        document.querySelector('#view-more').addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    </script>
</body>