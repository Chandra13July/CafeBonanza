<body class="font-[Alexandria]">

    <!-- Bagian Beranda -->
    <section class="py-8 px-4 md:px-0">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center">
            <div class="w-full md:w-1/2">
                <img alt="Interior kafe yang nyaman dengan meja dan kursi kayu" class="mx-auto md:w-[800px] md:h-[600px]" src="<?= BASEURL; ?>/img/home1.png" width="400" height="300" />
            </div>
            <div class="w-full md:w-1/2 text-left md:pl-12 mt-4 md:mt-0">
                <h1 class="text-4xl md:text-6xl font-bold">CAFE BONANZA</h1>
                <p class="mt-2 md:mt-4 text-base md:text-lg">Cafe Bonanza adalah rumah bagi siapa saja yang ingin beristirahat. Datanglah ke sini kapan saja jika merasa lelah.</p>
                <button onclick="scrollToSection()" class="mt-4 md:mt-6 px-4 md:px-6 py-2 bg-black text-white font-bold">LIHAT SELENGKAPNYA</button>
            </div>
        </div>
    </section>

    <!-- Bagian OUR COFFE POINT OF VIEW -->
    <section id="coffee-point-of-view" class="flex items-center justify-center bg-gray-200 h-24 px-4 md:px-0">
        <h2 class="text-xl font-semibold text-center">PANDANGAN KAMI TENTANG KOPI</h2>
    </section>

    <div class="max-w-6xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-4 mt-4 px-4 md:px-0">
        <div class="text-left">
            <img alt="Kopi pagi" class="mx-auto" height="500" src="<?= BASEURL; ?>/img/home2.png" width="300" />
            <p class="mt-2 text-sm font-thin text-left" style="width: 300px;">KOPI DI PAGI HARI</p>
            <p class="text-left text-xs" style="width: 300px;">2024</p>
        </div>
        <div class="text-left">
            <img alt="Kopi dengan nuansa alam" class="mx-auto" height="500" src="<?= BASEURL; ?>/img/home3.png" width="300" />
            <p class="mt-2 text-sm font-thin text-left" style="width: 300px;">KOPI DAN ALAM</p>
            <p class="text-left text-xs" style="width: 300px;">2024</p>
        </div>
        <!-- Removed extra coffee images to keep only two -->
        <div class="text-left hidden md:block">
            <img alt="Kopi untuk bekerja" class="mx-auto" height="500" src="<?= BASEURL; ?>/img/home4.png" width="300" />
            <p class="mt-2 text-sm font-thin text-left" style="width: 300px;">KOPI UNTUK BEKERJA</p>
            <p class="text-left text-xs" style="width: 300px;">2024</p>
        </div>
        <div class="text-left hidden md:block">
            <img alt="Kopi manis untuk hari Anda" class="mx-auto" height="500" src="<?= BASEURL; ?>/img/home5.png" width="300" />
            <p class="mt-2 text-sm font-thin text-left" style="width: 300px;">KOPI MANIS UNTUK HARIMU</p>
            <p class="text-left text-xs" style="width: 300px;">2024</p>
        </div>
    </div>

    <br><br><br><br><br>
    <!-- Bagian MINAT KE KOPI ATAU HIDANGAN LAIN -->
    <section class="bg-gray-200 py-12 text-center mt-8 px-4 md:px-0">
        <h2 class="text-4xl font-semibold mb-4">TERTARIK DENGAN KOPI KAMI ATAU HIDANGAN LAIN?</h2>
        <p class="mb-6">AYO PESAN DAN BUAT HARIMU MENYENANGKAN DENGAN ITU, KAMI PASTIKAN KAMU MENIKMATINYA!!</p>
        <button class="px-6 py-2 bg-black text-white font-bold" onclick="window.location.href='<?= BASEURL; ?>/home/menu'">
            PESAN SEKARANG
        </button>
    </section>

    <!-- Bagian Tentang Kami -->
    <section class="py-12 px-4 md:px-0">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Kolom Gambar Terlebih Dahulu di Mobile -->
            <div class="md:order-last">
                <img alt="Eksterior bangunan Kafe Bonanza" class="w-full" src="https://storage.googleapis.com/a1aa/image/57UcfEn6mv2BJaZnTpLIBCSefCP8CjinnE83cVqvDBdZvRbnA.jpg" width="600" height="400" />
            </div>
            <!-- Kolom Teks -->
            <div>
                <h2 class="text-5xl font-semibold mb-4">LEBIH TENTANG KAMI</h2>
                <p class="mb-4 text-sm">BONANZA CAFÉ ADALAH TEMPAT DI MANA KELEZATAN DAN KEHANGATAN BERPADU, DIRANCANG UNTUK MEMBERI PENGALAMAN YANG TAK TERLUPAKAN BAGI SETIAP PENGUNJUNG.</p>
                <p class="mb-4 text-sm">DI BONANZA, KAMI MENGHADIRKAN BERAGAM HIDANGAN DENGAN BAHAN BERKUALITAS, MULAI DARI PILIHAN MAKANAN LOKAL HINGGA SAJIAN INTERNASIONAL.</p>
                <p class="mb-4 text-sm">RASAKAN SUASANA DAN CITA RASA KHAS BONANZA CAFÉ! KUNJUNGI "TENTANG KAMI" UNTUK MENGENAL LEBIH DEKAT PERJALANAN KAMI DALAM MENYAJIKAN YANG TERBAIK.</p>
                <button class="px-6 py-2 bg-black text-white font-bold" onclick="window.location.href='<?= BASEURL; ?>/home/about'">
                    LIHAT SELENGKAPNYA
                </button>
            </div>
        </div>
    </section>

    <!-- Bagian Galeri -->
    <section class="py-12 text-center px-4 md:px-0">
        <h2 class="text-4xl font-bold mb-8">GALERI</h2>
        <div class="max-w-6xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-6">
            <!-- Empat gambar: grid 2x2 di mobile dan satu baris di desktop -->
            <div class="aspect-w-1 aspect-h-1">
                <img alt="Interior kafe dengan desain modern" src="https://storage.googleapis.com/a1aa/image/egw1dIWfRbluY0QIFAqR3dXjCQOGHOKqQxB4IGjNdwWr3otTA.jpg" class="w-full h-full object-cover" />
            </div>
            <div class="aspect-w-1 aspect-h-1">
                <img alt="Interior kafe dengan desain modern" src="https://storage.googleapis.com/a1aa/image/egw1dIWfRbluY0QIFAqR3dXjCQOGHOKqQxB4IGjNdwWr3otTA.jpg" class="w-full h-full object-cover" />
            </div>
            <div class="aspect-w-1 aspect-h-1">
                <img alt="Interior kafe dengan desain modern" src="https://storage.googleapis.com/a1aa/image/egw1dIWfRbluY0QIFAqR3dXjCQOGHOKqQxB4IGjNdwWr3otTA.jpg" class="w-full h-full object-cover" />
            </div>
            <div class="aspect-w-1 aspect-h-1">
                <img alt="Interior kafe dengan desain modern" src="https://storage.googleapis.com/a1aa/image/egw1dIWfRbluY0QIFAqR3dXjCQOGHOKqQxB4IGjNdwWr3otTA.jpg" class="w-full h-full object-cover" />
            </div>
        </div>
        <button class="px-6 py-2 mt-8 bg-black text-white font-bold" onclick="window.location.href='<?= BASEURL; ?>/home/gallery'">
            LIHAT LEBIH BANYAK
        </button>
    </section>
    <section class="py-12 bg-gray-100 text-center px-4 md:px-0">
        <h2 class="text-xl font-bold mb-8">SOME REVIEWS OF OUR CUSTOMER</h2>
        <div class="max-w-6xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 shadow-md">
                <i class="fas fa-quote-left text-gray-600"></i>
                <p class="my-4">"kopinya mantap tapi musicnya terlalu keras!"</p>
                <p class="font-bold">- Alief</p>
            </div>
            <div class="bg-white p-6 shadow-md">
                <i class="fas fa-quote-left text-gray-600"></i>
                <p class="my-4">"tempatnya terlalu ramai tapi makanannya enak"</p>
                <p class="font-bold">- Raihan</p>
            </div>
            <div class="bg-white p-6 shadow-md">
                <i class="fas fa-quote-left text-gray-600"></i>
                <p class="my-4">"kasirnya ramah tapi kurang interaktif"</p>
                <p class="font-bold">- Raffi</p>
            </div>
            <div class="bg-white p-6 shadow-md">
                <i class="fas fa-quote-left text-gray-600"></i>
                <p class="my-4">"kafe yg bagus juga menyenangkan"</p>
                <p class="font-bold">- Finno</p>
            </div>
        </div>
    </section>

    <!-- JavaScript for Smooth Scrolling -->
    <script>
        function scrollToSection() {
            const section = document.getElementById("coffee-point-of-view");
            section.scrollIntoView({
                behavior: "smooth"
            });
        }
    </script>

</body>
