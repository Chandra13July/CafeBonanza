<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; position: relative; }
        #notification { opacity: 1; transition: opacity 0.5s ease; font-size: 0.875rem; }
        .opacity-0 { opacity: 0; }
    </style>
</head>
<body>
    <!-- Popup Notification -->
    <div id="notification" class="hidden fixed top-20 right-4 bg-green-500 text-white px-3 py-2 rounded shadow-lg text-sm transition-opacity duration-500 ease-out">
        Pesan berhasil dikirim!
    </div>

    <div class="container mx-auto py-12 px-4">
        <h1 class="text-3xl md:text-4xl font-bold text-center text-black">Hubungi Kami</h1>
        <p class="text-center text-gray-500 mt-4 text-base md:text-lg">Ingin berbisnis atau memiliki keluhan? Hubungi kami!</p>

        <div class="flex flex-col md:flex-row justify-center mt-12">
            <div class="bg-black text-white p-8 md:p-10 rounded-lg w-full md:w-2/5 lg:w-2/5">
                <h2 class="text-lg md:text-xl font-bold mb-6">Informasi Kontak</h2>
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
                <form id="contactForm" action="<?= BASEURL; ?>/home/btnContact" method="POST" class="space-y-4 md:space-y-6">
                    <div>
                        <label for="name" class="block text-gray-700 text-sm md:text-base">Nama</label>
                        <input type="text" id="name" name="name" class="w-full border border-gray-300 p-3 rounded text-sm md:text-base" required>
                    </div>
                    <div>
                        <label for="email" class="block text-gray-700 text-sm md:text-base">Email</label>
                        <input type="email" id="email" name="email" class="w-full border border-gray-300 p-3 rounded text-sm md:text-base" required>
                    </div>
                    <div>
                        <label for="type" class="block text-gray-700 text-sm md:text-base">Tipe</label>
                        <select id="type" name="type" class="w-full border border-gray-300 p-3 rounded text-sm md:text-base" required>
                            <option value="Kritik">Kritik</option>
                            <option value="Saran">Saran</option>
                            <option value="Pertanyaan">Pertanyaan</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label for="message" class="block text-gray-700 text-sm md:text-base">Pesan</label>
                        <textarea id="message" name="message" class="w-full border border-gray-300 p-3 rounded h-32 md:h-40 text-sm md:text-base" required></textarea>
                    </div>
                    <button type="submit" class="bg-black text-white py-3 px-6 rounded w-full text-sm md:text-base">KIRIM</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('contactForm').addEventListener('submit', function(event) {
            event.preventDefault();
            let formData = new FormData(this);

            fetch('<?= BASEURL; ?>/home/btnContact', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    // Display success notification
                    const notification = document.getElementById('notification');
                    notification.classList.remove('hidden');
                    setTimeout(() => {
                        notification.classList.add('hidden');
                    }, 3000);

                    // Clear input fields
                    document.getElementById('contactForm').reset();
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    </script>
</body>
</html>
