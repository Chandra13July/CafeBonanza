<html lang="en">

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
    /* Black color */
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
                    <span class="text-black">
                        Bonanza
                    </span>
                </h1>
            </div>
            <div class="nav-links duration-500 md:static absolute bg-white md:min-h-fit min-h-[60vh] left-0 top-[-100%] md:w-auto w-full flex items-center px-5 md:flex md:top-0 hidden">
                <ul class="flex md:flex-row flex-col md:items-center md:gap-[4vw] gap-4">
                    <li>
                        <a class="text-gray-700 hover:text-black nav-link" href="<?= BASEURL; ?>/home/home">
                            Home
                        </a>
                    </li>
                    <li>
                        <a class="text-gray-700 hover:text-black nav-link" href="<?= BASEURL; ?>/home/about">
                            About
                        </a>
                    </li>
                    <li>
                        <a class="text-gray-700 hover:text-black nav-link" href="<?= BASEURL; ?>/home/menu">
                            Menu
                        </a>
                    </li>
                    <li>
                        <a class="text-gray-700 hover:text-black nav-link" href="<?= BASEURL; ?>/home/gallery">
                            Gallery
                        </a>
                    </li>
                    <li>
                        <a class="text-gray-700 hover:text-black nav-link" href="<?= BASEURL; ?>/home/contact">
                            Contact
                        </a>
                    </li>
                </ul>
            </div>
            <div class="flex items-center gap-6">
                <a class="bg-gray-700 text-white px-8 py-2 rounded-full btn-effect" href="#">
                    Sign In
                </a>
                <ion-icon class="text-3xl cursor-pointer md:hidden" name="menu" onclick="onToggleMenu(this)">
                </ion-icon>
            </div>
        </nav>
    </header>
    <script>
        const navLinks = document.querySelector('.nav-links');

        function onToggleMenu(e) {
            e.name = e.name === 'menu' ? 'close' : 'menu';
            navLinks.classList.toggle('hidden'); // Menyembunyikan/menampilkan navbar
            navLinks.classList.toggle('top-0'); // Menampilkan navbar dari atas
            if (navLinks.classList.contains('hidden')) {
                navLinks.style.top = '-100%'; // Menyembunyikan navbar ke atas
            } else {
                navLinks.style.top = '0'; // Menampilkan navbar
            }
        }
    </script>
</body>

</html>