<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            position: relative;
        }
    </style>
</head>

<body>
    <?php if (isset($_SESSION['flash'])): ?>
        <div id="notification" class="fixed top-20 right-4 bg-green-500 text-white px-3 py-2 rounded shadow-lg text-sm transition-opacity duration-500 ease-out">
            <?= $_SESSION['flash']; ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const notification = document.getElementById('notification');
            
            if (notification) {
                setTimeout(() => {
                    notification.classList.add('opacity-0'); // Add class to fade out
                    setTimeout(() => notification.remove(), 500); // Remove element after transition
                }, 3000);
            }
        });
    </script>

    <style>
        #notification {
            opacity: 1;
            transition: opacity 0.5s ease;
            font-size: 0.875rem; /* Use smaller font size */
        }
        .opacity-0 {
            opacity: 0;
        }
    </style>

    <div class="container mx-auto py-12 px-4">
        <h1 class="text-3xl md:text-4xl font-bold text-center text-black">Contact Us</h1>
        <p class="text-center text-gray-500 mt-4 text-base md:text-lg">Have questions or comments? Write a message to us!</p>
        <div class="flex flex-col md:flex-row justify-center mt-12">
            <div class="bg-black text-white p-8 md:p-10 rounded-lg w-full md:w-2/5 lg:w-2/5">
                <h2 class="text-lg md:text-xl font-bold mb-6">Contact Information</h2>
                <p class="mb-6 text-sm md:text-base">If you have any questions or concerns, you can contact us by filling out the contact form, calling us, visiting our office, finding us on other social networks, or you can send us a personal email at:</p>
                <div class="flex items-center mb-6 text-sm md:text-base">
                    <i class="fas fa-phone-alt mr-3"></i>
                    <span>0859-6441-6174</span>
                </div>
                <div class="flex items-center mb-6 text-sm md:text-base">
                    <i class="fas fa-envelope mr-3"></i>
                    <span>bonanzacafe3@gmail.com</span>
                </div>
                <div class="flex items-start text-sm md:text-base">
                    <i class="fas fa-map-marker-alt mr-3 mt-1"></i>
                    <span>Jl. Pancur, Sumpelan Utara, Lumutan, Kec. Prajekan, Kabupaten Bondowoso, Jawa Timur 68284</span>
                </div>
            </div>
            <div class="w-full md:w-2/5 lg:w-2/5 md:ml-8 mt-8 md:mt-0">
                <form action="<?= BASEURL; ?>/home/btnContact" method="POST" class="space-y-4 md:space-y-6">
                    <div>
                        <label for="name" class="block text-gray-700 text-sm md:text-base">Name</label>
                        <input type="text" id="name" name="name" class="w-full border border-gray-300 p-3 rounded text-sm md:text-base" required>
                    </div>
                    <div>
                        <label for="email" class="block text-gray-700 text-sm md:text-base">Email</label>
                        <input type="email" id="email" name="email" class="w-full border border-gray-300 p-3 rounded text-sm md:text-base" required>
                    </div>
                    <div>
                        <label for="phone" class="block text-gray-700 text-sm md:text-base">Phone Number</label>
                        <input type="text" id="phone" name="phone" class="w-full border border-gray-300 p-3 rounded text-sm md:text-base">
                    </div>
                    <div>
                        <label for="message" class="block text-gray-700 text-sm md:text-base">Message</label>
                        <textarea id="message" name="message" class="w-full border border-gray-300 p-3 rounded h-32 md:h-40 text-sm md:text-base" required></textarea>
                    </div>
                    <button type="submit" class="bg-black text-white py-3 px-6 rounded w-full text-sm md:text-base">SEND</button>
                </form>
            </div>
        </div>
        <div class="mt-12">
            <h2 class="text-2xl md:text-3xl font-bold text-center text-black mb-6">Our Location</h2>
            <div class="flex justify-center">
                <div class="w-full md:w-5/6 lg:w-4/5">
                    <iframe class="w-full h-64 md:h-96 rounded-lg shadow-lg" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.1234567890123!2d113.12345678901234!3d-7.123456789012345!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7e12345678901%3A0x1234567890123456!2sJl.%20Pancur%2C%20Sumpelan%20Utara%2C%20Lumutan%2C%20Kec.%20Prajekan%2C%20Kabupaten%20Bondowoso%2C%20Jawa%20Timur%2068284!5e0!3m2!1sen!2sid!4v1611234567890!5m2!1sen!2sid" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
</body>

</html>