<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .nav-link {
            position: relative;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background: black;
            left: 50%;
            bottom: -4px;
            transition: width 0.3s, left 0.3s;
        }

        .nav-link:hover::after {
            width: 100%;
            left: 0;
        }

        .nav-link:hover {
            color: black;
        }

        .btn-effect {
            transition: background-color 0.3s;
        }

        .btn-effect:hover {
            background-color: black;
        }

        .fade-out {
            opacity: 0;
            transition: opacity 0.3s ease-out;
        }
    </style>
</head>

<body class="bg-white">
    <!-- Navbar -->
    <header class="bg-white border-b border-gray-300 sticky top-0 z-30">
        <nav class="flex justify-between items-center w-[92%] mx-auto py-4">
            <div>
                <h1 class="text-2xl font-bold text-black">
                    Cafe
                    <span class="text-black">Bonanza</span>
                </h1>
            </div>
            <div class="nav-links duration-500 md:static absolute bg-white md:min-h-fit min-h-[60vh] left-0 top-[-100%] md:w-auto w-full flex items-center px-5 md:flex md:top-0 hidden">
                <ul class="flex md:flex-row flex-col md:items-center md:gap-[4vw] gap-4">
                    <li>
                        <a class="text-gray-700 hover:text-black nav-link" href="<?= BASEURL; ?>/home/index">
                            Beranda
                        </a>
                    </li>
                    <li>
                        <a class="text-gray-700 hover:text-black nav-link" href="<?= BASEURL; ?>/home/about">
                            Tentang Kami
                        </a>
                    </li>
                    <li>
                        <a class="text-gray-700 hover:text-black nav-link" href="<?= BASEURL; ?>/home/menu">
                            Menu
                        </a>
                    </li>
                    <li>
                        <a class="text-gray-700 hover:text-black nav-link" href="<?= BASEURL; ?>/home/gallery">
                            Galeri
                        </a>
                    </li>
                    <li>
                        <a class="text-gray-700 hover:text-black nav-link" href="<?= BASEURL; ?>/home/contact">
                            Kontak
                        </a>
                    </li>
                </ul>
            </div>
            <div class="relative flex items-center gap-6">
                <?php if (isset($_SESSION['username'])): ?>
                    <div class="flex items-center relative">
                        <!-- Gambar Pengguna -->
                        <img onerror="this.src = '<?= BASEURL . '/img/user.png' ?>'" src="<?= !empty($_SESSION['ImageUrl']) ? BASEURL . '/' . $_SESSION['ImageUrl'] : BASEURL . '/img/user.png'; ?>" class="w-10 h-10 rounded-full mr-2 cursor-pointer" onclick="toggleDropdown()">

                        <!-- Tampilkan Nama Pengguna -->
                        <span class="text-gray-800 cursor-pointer" onclick="toggleDropdown()"><?= htmlspecialchars($_SESSION['username']); ?></span>

                        <!-- Menu Dropdown -->
                        <div id="dropdown-menu" class="absolute right-[-10px] top-full mt-2 w-48 bg-white rounded-lg shadow-lg hidden z-40">
                            <a href="<?= BASEURL; ?>/home/profile" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Profil</a>
                            <a href="<?= BASEURL; ?>/home/cart" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Keranjang</a>
                            <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-200" onclick="openLogoutModal()">Keluar</a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Tombol Masuk -->
                    <a class="bg-gray-700 text-white px-8 py-2 rounded-full btn-effect" href="<?= BASEURL; ?>/auth/login">
                        Masuk
                    </a>
                <?php endif; ?>

                <!-- Ikon Menu Mobile -->
                <ion-icon class="text-3xl cursor-pointer md:hidden" name="menu" onclick="onToggleMenu(this)"></ion-icon>
            </div>
        </nav>
    </header>

    <!-- Kontainer Notifikasi -->
    <div id="notification-container" class="fixed top-5 right-5 z-50"></div>

    <!-- Modal Konfirmasi Logout -->
    <div id="logout-modal" class="fixed inset-0 flex items-center justify-center z-50 hidden bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96">
            <h2 class="text-lg font-semibold mb-4">Konfirmasi Keluar</h2>
            <p>Apakah Anda yakin ingin keluar?</p>
            <div class="flex justify-end mt-6">
                <button class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md mr-2" onclick="closeLogoutModal()">Batal</button>
                <button class="bg-red-600 text-white px-4 py-2 rounded-md" onclick="confirmLogout()">Keluar</button>
            </div>
        </div>
    </div>

    <script>
        const navLinks = document.querySelector('.nav-links');

        function onToggleMenu(e) {
            e.name = e.name === 'menu' ? 'close' : 'menu';
            navLinks.classList.toggle('hidden');
            navLinks.classList.toggle('top-0');
            if (navLinks.classList.contains('hidden')) {
                navLinks.style.top = '-100%';
            } else {
                navLinks.style.top = '0';
            }
        }

        // Menampilkan Dropdown Menu
        function toggleDropdown() {
            const dropdownMenu = document.getElementById('dropdown-menu');
            dropdownMenu.classList.toggle('hidden');
        }

        // Menutup dropdown jika diklik di luar
        window.onclick = function(event) {
            if (!event.target.matches('.cursor-pointer')) {
                const dropdowns = document.getElementsByClassName("absolute");
                for (let i = 0; i < dropdowns.length; i++) {
                    const openDropdown = dropdowns[i];
                    if (!openDropdown.classList.contains('hidden')) {
                        openDropdown.classList.add('hidden');
                    }
                }
            }
        }

        // Fungsi Menampilkan Notifikasi
        function showNotification(message, type = 'info') {
            const container = document.getElementById('notification-container');
            const notification = document.createElement('div');
            notification.className = `p-4 mb-4 text-sm text-white rounded-lg shadow-md ${type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'}`;
            notification.textContent = message;

            // Menambahkan notifikasi ke kontainer
            container.appendChild(notification);

            // Secara otomatis menghapus notifikasi setelah 3 detik
            setTimeout(() => {
                notification.classList.add('fade-out');
                setTimeout(() => {
                    container.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Fungsi Modal Logout
        function openLogoutModal() {
            document.getElementById('logout-modal').classList.remove('hidden');
        }

        function closeLogoutModal() {
            document.getElementById('logout-modal').classList.add('hidden');
        }

        function confirmLogout() {
            window.location.href = '<?= BASEURL; ?>/auth/logout';
        }
    </script>
</body>

</html>