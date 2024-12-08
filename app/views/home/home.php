<body class="font-[Alexandria]">
    <section class="py-8 px-4 md:px-0">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center">
            <div class="w-full md:w-1/2">
                <img alt="A cozy café interior with wooden tables and chairs" class="mx-auto md:w-[800px] md:h-[600px]" src="<?= BASEURL; ?>/img/home1.png" width="400" height="300" />
            </div>
            <div class="w-full md:w-1/2 text-left md:pl-12 mt-4 md:mt-0">
                <h1 class="text-4xl md:text-6xl font-bold">CAFE BONANZA</h1>
                <p class="mt-2 md:mt-4 text-base md:text-lg">Cafe Bonanza is a home for anyone who needs a break. Come here anytime if you're feeling tired.</p>
                <button onclick="scrollToSection()" class="mt-4 md:mt-6 px-4 md:px-6 py-2 bg-black text-white font-bold">SEE MORE</button>
            </div>
        </div>
    </section>
    <section id="coffee-point-of-view" class="flex items-center justify-center bg-gray-200 h-24 px-4 md:px-0">
        <h2 class="text-xl font-semibold text-center">OUR PERSPECTIVE ON COFFEE</h2>
    </section>

    <div class="max-w-6xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-4 mt-4 px-4 md:px-0">
        <div class="text-left">
            <img alt="Morning coffee" class="mx-auto" height="500" src="<?= BASEURL; ?>/img/home2.png" width="300" />
            <p class="mt-2 text-sm font-thin text-left" style="width: 300px;">MORNING COFFEE</p>
            <p class="text-left text-xs" style="width: 300px;">2024</p>
        </div>
        <div class="text-left">
            <img alt="Coffee with nature vibes" class="mx-auto" height="500" src="<?= BASEURL; ?>/img/home3.png" width="300" />
            <p class="mt-2 text-sm font-thin text-left" style="width: 300px;">COFFEE AND NATURE</p>
            <p class="text-left text-xs" style="width: 300px;">2024</p>
        </div>
        <div class="text-left hidden md:block">
            <img alt="Coffee for work" class="mx-auto" height="500" src="<?= BASEURL; ?>/img/home4.png" width="300" />
            <p class="mt-2 text-sm font-thin text-left" style="width: 300px;">COFFEE FOR WORK</p>
            <p class="text-left text-xs" style="width: 300px;">2024</p>
        </div>
        <div class="text-left hidden md:block">
            <img alt="Sweet coffee for your day" class="mx-auto" height="500" src="<?= BASEURL; ?>/img/home5.png" width="300" />
            <p class="mt-2 text-sm font-thin text-left" style="width: 300px;">SWEET COFFEE FOR YOUR DAY</p>
            <p class="text-left text-xs" style="width: 300px;">2024</p>
        </div>
    </div>
    <br><br><br><br><br>
    <section class="bg-gray-200 py-12 text-center mt-8 px-4 md:px-0">
        <h2 class="text-4xl font-semibold mb-4">INTERESTED IN OUR COFFEE OR OTHER DISHES?</h2>
        <p class="mb-6">ORDER NOW AND MAKE YOUR DAY ENJOYABLE WITH IT, WE GUARANTEE YOU’LL LOVE IT!!</p>
        <button class="px-6 py-2 bg-black text-white font-bold" onclick="window.location.href='<?= BASEURL; ?>/home/menu'">
            ORDER NOW
        </button>
    </section>

    <section class="py-12 px-4 md:px-0">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:order-last">
                <img alt="The exterior of Bonanza Café building" class="w-full" src="https://storage.googleapis.com/a1aa/image/57UcfEn6mv2BJaZnTpLIBCSefCP8CjinnE83cVqvDBdZvRbnA.jpg" width="600" height="400" />
            </div>
            <div>
                <h2 class="text-5xl font-semibold mb-4">MORE ABOUT US</h2>
                <p class="mb-4 text-sm">BONANZA CAFÉ IS A PLACE WHERE FLAVOR AND WARMTH COME TOGETHER, DESIGNED TO PROVIDE AN UNFORGETTABLE EXPERIENCE FOR EVERY VISITOR.</p>
                <p class="mb-4 text-sm">AT BONANZA, WE SERVE A WIDE VARIETY OF DISHES MADE WITH HIGH-QUALITY INGREDIENTS, FROM LOCAL DELICACIES TO INTERNATIONAL CUISINES.</p>
                <p class="mb-4 text-sm">FEEL THE UNIQUE ATMOSPHERE AND TASTE OF BONANZA CAFÉ! VISIT "ABOUT US" TO LEARN MORE ABOUT OUR JOURNEY IN SERVING THE BEST.</p>
                <button class="px-6 py-2 bg-black text-white font-bold" onclick="window.location.href='<?= BASEURL; ?>/home/about'">
                    SEE MORE
                </button>
            </div>
        </div>
    </section>

    <section class="py-12 text-center px-4 md:px-0">
        <h2 class="text-4xl font-bold mb-8">GALLERY</h2>
        <div class="max-w-6xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-6">
            <?php foreach ($latestGalleries as $gallery): ?>
                <div class="w-full h-0 pb-[100%] relative">
                    <img alt="Gallery image" src="<?= BASEURL . '/' . htmlspecialchars($gallery['ImageUrl']) ?>" class="absolute top-0 left-0 w-full h-full object-cover" />
                </div>
            <?php endforeach; ?>
        </div>
        <button class="px-6 py-2 mt-8 bg-black text-white font-bold" onclick="window.location.href='<?= BASEURL; ?>/home/gallery'">
            SEE MORE
        </button>
    </section>

    <section class="py-12 bg-gray-100 text-center px-4 md:px-8">
        <h2 class="text-xl font-bold mb-8">Some Suggestions from Our Customers</h2>

        <div class="swiper-container">
            <div class="swiper-wrapper">
                <?php if (!empty($data['latestContacts'])): ?>
                    <?php foreach ($data['latestContacts'] as $contact): ?>
                        <div class="swiper-slide bg-white p-6 shadow-md flex flex-col justify-between min-h-[200px] mx-2 md:mx-4">
                            <i class="fas fa-quote-left text-gray-600 text-xl"></i>
                            <p class="my-4 text-sm text-gray-600"><?= htmlspecialchars($contact['Message']); ?></p>
                            <p class="font-bold mt-auto"><?= htmlspecialchars($contact['Name']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-600 text-center col-span-4">No suggestions available at the moment.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.swiper-container', {
            loop: true,
            autoplay: {
                delay: 2000, // Delay antara slide, 2 detik
                disableOnInteraction: false,
            },
            slidesPerView: 4, // Menampilkan 4 kartu sekaligus di desktop
            spaceBetween: 20, // Jarak antar slides
            speed: 600, // Kecepatan transisi slide (600ms lebih halus)
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            effect: 'slide', // Efek transisi default 'slide', bisa juga gunakan 'fade' atau 'cube'
            breakpoints: {
                640: {
                    slidesPerView: 2, // Menampilkan 2 kartu di mobile
                    spaceBetween: 10, // Menambahkan jarak antar slide di mobile
                },
                768: {
                    slidesPerView: 2, // Menampilkan 2 kartu di tablet
                    spaceBetween: 20, // Menambahkan jarak antar slide di tablet
                },
                1024: {
                    slidesPerView: 3, // Menampilkan 3 kartu di laptop
                    spaceBetween: 20,
                },
                1280: {
                    slidesPerView: 4, // Menampilkan 4 kartu di desktop
                    spaceBetween: 20,
                },
            },
        });
    </script>

    <style>
        /* Custom styles untuk memperlebar swiper-slide pada tampilan mobile */
        @media (max-width: 640px) {
            .swiper-slide {
                width: calc(50% - 10px);
                /* Menetapkan lebar menjadi 50% dari container dengan jarak antar slide */
            }
        }
    </style>

</body>