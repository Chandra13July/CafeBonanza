<style>
    body {
        font-family: 'Roboto', sans-serif;
        position: relative;
    }
</style>

<?php if (isset($_SESSION['success'])): ?>
    <div id="success-notification" class="bg-green-500 text-white p-2 rounded shadow-lg absolute top-4 right-4 text-sm z-50">
        <?= $_SESSION['success']; ?>
        <?php unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<body>
    <div class="container mx-auto py-12 px-4">
        <h1 class="text-3xl md:text-4xl font-bold text-center text-black">Contact Us</h1>
        <p class="text-center text-gray-500 mt-4 text-base md:text-lg">Do you want to do business or have a complaint? Contact us!</p>

        <div class="flex flex-col md:flex-row justify-center mt-12">
            <div class="bg-black text-white p-8 md:p-10 rounded-lg w-full md:w-2/5 lg:w-2/5">
                <h2 class="text-lg md:text-xl font-bold mb-6">Contact Information</h2>
                <div class="flex items-center mb-6 text-sm md:text-base">
                    <i class="fas fa-coffee mr-3"></i>
                    <span>Bonanza Cafe</span>
                </div>
                <div class="flex items-center mb-6 text-sm md:text-base">
                    <i class="fas fa-phone-alt mr-3"></i>
                    <span>0859-6441-6174</span>
                </div>
                <div class="flex items-center mb-6 text-sm md:text-base">
                    <i class="fas fa-envelope mr-3"></i>
                    <span>bonanzacafe3@gmail.com</span>
                </div>
                <div class="flex items-start mb-6 text-sm md:text-base">
                    <i class="fas fa-map-marker-alt mr-3 mt-1"></i>
                    <span>Jl. Pancur, Sumpelan Utara, Lumutan, Kec. Prajekan, Kabupaten Bondowoso, East Java 68284</span>
                </div>
                <div class="flex items-center text-sm md:text-base">
                    <i class="fas fa-clock mr-3"></i>
                    <span>Open daily from 7 PM to 2 AM</span>
                </div>
            </div>
            <div class="w-full md:w-2/5 lg:w-2/5 md:ml-8 mt-8 md:mt-0">
                <form id="contactForm" action="<?= BASEURL; ?>/home/btnContact" method="POST" class="space-y-4 md:space-y-6">
                    <div>
                        <label for="name" class="block text-gray-700 text-sm md:text-base">Name</label>
                        <input type="text" id="name" name="name" class="w-full border border-gray-300 p-3 rounded text-sm md:text-base" required>
                    </div>
                    <div>
                        <label for="email" class="block text-gray-700 text-sm md:text-base">Email</label>
                        <input type="email" id="email" name="email" class="w-full border border-gray-300 p-3 rounded text-sm md:text-base" required>
                    </div>
                    <div>
                        <label for="type" class="block text-gray-700 text-sm md:text-base">Type</label>
                        <select id="type" name="type" class="w-full border border-gray-300 p-3 rounded text-sm md:text-base" required>
                            <option value="Suggestion">Suggestion</option>
                            <option value="Complaint">Complaint</option>
                            <option value="Inquiry">Inquiry</option>
                        </select>
                    </div>
                    <div>
                        <label for="message" class="block text-gray-700 text-sm md:text-base">Message</label>
                        <textarea id="message" name="message" class="w-full border border-gray-300 p-3 rounded h-32 md:h-40 text-sm md:text-base" required></textarea>
                    </div>
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="text-left text-red-500 mb-4">
                            <?= $_SESSION['error']; ?>
                            <?php unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>
                    <button type="submit" class="bg-black text-white py-3 px-6 rounded w-full text-sm md:text-base">SEND</button>
                </form>
            </div>
        </div>

        <div class="mt-12">
            <h2 class="text-2xl md:text-3xl font-bold text-center text-black">Our Location</h2>
            <div class="flex justify-center mt-6">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3757.222521733524!2d113.97323327476617!3d-7.810565392209918!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd6d7af286fdc79%3A0xc368336045bbf16!2sBONANZA%20CAFE%20%26%20BILLIARD!5e1!3m2!1sen!2sid!4v1732250543448!5m2!1sen!2sid" width="1200" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>

    </div>

    <script>
        window.onload = function() {
            const notification = document.getElementById('success-notification');
            if (notification) {
                setTimeout(() => {
                    notification.classList.add('hidden');
                    window.location.href = "<?= BASEURL; ?>/home/contact";
                }, 2000);
            }
        };
    </script>
</body>