<html>
<style>
    /* Styling untuk elemen body menggunakan font Roboto */
    body {
        font-family: 'Roboto', sans-serif;
    }

    /* Kontainer untuk gambar zoom */
    .zoom-container {
        position: relative;
        overflow: hidden;
        width: 100%;
        height: 12rem;
        /* tinggi kontainer gambar */
    }

    /* Gambar yang bisa di-zoom dengan efek transisi */
    .zoom-image {
        transition: transform 0.3s ease;
        /* animasi transformasi */
        transform-origin: center center;
        /* titik referensi zoom di tengah */
        cursor: zoom-in;
        /* cursor berubah saat hover */
    }

    /* Efek zoom saat hover pada kontainer */
    .zoom-container:hover .zoom-image {
        transform: scale(1.5);
        /* zoom gambar saat hover */
        cursor: zoom-in;
    }
</style>

<body class="bg-gray-100 text-gray-800 p-4 sm:p-6 md:p-8 lg:p-10">
    <!-- Menampilkan notifikasi jika ada session success -->
    <?php if (isset($_SESSION['success'])): ?>
        <div id="success-notification" class="bg-green-500 text-white p-2 rounded shadow-lg fixed top-4 right-4 text-sm z-50">
            <?= $_SESSION['success']; ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <!-- Menampilkan notifikasi jika ada session error -->
    <?php if (isset($_SESSION['error'])): ?>
        <div id="error-notification" class="bg-red-500 text-white p-2 rounded shadow-lg fixed top-16 right-4 text-sm z-50">
            <?= $_SESSION['error']; ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Div untuk notifikasi umum -->
    <div id="notification" class="hidden fixed top-0 left-0 right-0 p-4 text-center text-lg font-semibold z-100"></div>

    <!-- Judul dan deskripsi halaman menu -->
    <div class="text-center my-12">
        <h1 class="text-4xl font-bold mb-2">SPECIAL MENU OF THE DAY</h1>
        <p class="text-lg text-gray-600">Order delicious dishes and drinks!</p>

        <!-- Form untuk filter pencarian dan dropdown untuk menyortir menu -->
        <div class="mt-4 flex justify-center items-center gap-4">
            <input
                id="searchInput"
                type="text"
                class="w-full max-w-md p-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring focus:ring-yellow-400"
                placeholder="Search..."
                oninput="filterMenu()">

            <select
                id="sortDropdown"
                class="p-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring focus:ring-yellow-400"
                onchange="sortMenu()">
                <option>Select Sort Option</option>
                <option value="az">A-Z</option>
                <option value="za">Z-A</option>
                <option value="price-low">Lowest Price</option>
                <option value="price-high">Highest Price</option>
            </select>
        </div>
    </div>

    <!-- Grid untuk menampilkan daftar menu -->
    <div id="menuGrid" class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-6 max-w-6xl mx-auto">
        <?php if (!empty($data['MenuItems'])): ?>
            <!-- Looping untuk menampilkan setiap item menu -->
            <?php foreach ($data['MenuItems'] as $item): ?>
                <div class="bg-white rounded-lg shadow-md text-center flex flex-col">
                    <!-- Kontainer untuk gambar zoom -->
                    <div class="zoom-container relative overflow-hidden rounded-t-lg">
                        <img
                            src="<?= BASEURL; ?>/<?= htmlspecialchars($item['ImageUrl']) ? htmlspecialchars($item['ImageUrl']) : 'default_image.jpg'; ?>"
                            alt="<?= htmlspecialchars($item['MenuName']); ?>"
                            class="zoom-image w-full h-48 object-cover m-0 p-0 rounded-t-lg">
                    </div>

                    <div class="p-4 flex flex-col items-start rounded-b-lg">
                        <!-- Nama menu dan deskripsi -->
                        <h5 class="text-lg font-semibold mb-2"><?= htmlspecialchars($item['MenuName']); ?></h5>
                        <p class="text-sm text-gray-600 mt-2 line-clamp-2 text-left"><?= htmlspecialchars($item['Description']); ?></p>

                        <!-- Menampilkan harga dan stok menu -->
                        <div class="flex justify-between items-center mt-2 w-full">
                            <span class="text-lg font-bold">Rp <?= number_format($item['Price'], 0, ',', '.'); ?></span>
                            <span class="text-sm text-gray-600 ml-4">Stock: <?= $item['Stock']; ?></span>
                        </div>
                        <div class="flex justify-between items-center mt-2 w-full">
                            <div>
                                <?php if ($item['TotalSold'] > 0): ?>
                                    <span class="text-sm text-gray-600"><?= $item['TotalSold']; ?> Sold</span>
                                <?php endif; ?>
                            </div>

                            <div class="flex items-center space-x-2">
                                <!-- Tombol untuk mengurangi atau menambah kuantitas menu -->
                                <button id="decrease-<?= $item['MenuId']; ?>" class="bg-gray-200 text-gray-700 px-2 py-1 rounded" onclick="decreaseQuantity(<?= $item['MenuId']; ?>)" <?= $item['Stock'] == 0 ? 'disabled' : '' ?>>-</button>
                                <input id="quantity-<?= $item['MenuId']; ?>" type="number" min="1" max="<?= $item['Stock']; ?>" value="1" class="w-12 text-center font-medium border border-gray-300 rounded" onchange="updateCartQuantity(<?= $item['MenuId']; ?>)" style="display: none;">
                                <span id="quantity-display-<?= $item['MenuId']; ?>" class="w-12 text-center font-medium border border-gray-300 rounded"><?= $item['Stock'] > 0 ? 1 : 0 ?></span>
                                <button id="increase-<?= $item['MenuId']; ?>" class="bg-gray-200 text-gray-700 px-2 py-1 rounded" onclick="increaseQuantity(<?= $item['MenuId']; ?>)" <?= $item['Stock'] == 0 ? 'disabled' : '' ?>>+</button>
                            </div>

                        </div>

                        <!-- Tombol untuk menambahkan menu ke keranjang -->
                        <div class="quantity-controls flex items-center justify-center space-x-2 mt-4 w-full">
                            <?php if ($item['Stock'] > 0): ?>
                                <button
                                    class="w-full py-2 px-4 rounded bg-yellow-400 text-white hover:bg-yellow-500"
                                    onclick="addToCart(<?= $item['MenuId']; ?>)">
                                    Add to Cart
                                </button>
                            <?php else: ?>
                                <span class="w-full py-2 px-4 rounded bg-gray-300 text-gray-500 text-center cursor-not-allowed">
                                    Out of Stock
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Menampilkan pesan jika tidak ada menu yang ditemukan -->
            <p class="text-gray-600 text-center col-span-4">No items found in this category.</p>
        <?php endif; ?>
    </div>

    <script>
        // Menangani notifikasi success dan error yang muncul sementara
        window.onload = function() {
            const successNotification = document.getElementById('success-notification');
            if (successNotification) {
                setTimeout(() => {
                    successNotification.classList.add('hidden');
                }, 1000);
            }

            const errorNotification = document.getElementById('error-notification');
            if (errorNotification) {
                setTimeout(() => {
                    errorNotification.classList.add('hidden');
                }, 1000);
            }
        };

        // Fungsi untuk menangani efek zoom pada gambar saat mouse bergerak
        document.querySelectorAll('.zoom-container').forEach(container => {
            const image = container.querySelector('.zoom-image');
            container.addEventListener('mousemove', (e) => {
                const {
                    left,
                    top,
                    width,
                    height
                } = container.getBoundingClientRect();
                const x = ((e.clientX - left) / width) * 100;
                const y = ((e.clientY - top) / height) * 100;
                image.style.transformOrigin = `${x}% ${y}%`;
            });

            container.addEventListener('mouseleave', () => {
                image.style.transformOrigin = 'center center';
            });
        });

        // Fungsi untuk menambahkan menu ke keranjang belanja
        function addToCart(menuId) {
            const quantity = parseInt(document.getElementById('quantity-' + menuId).value);
            const stock = parseInt(document.getElementById('quantity-' + menuId).max);

            if (quantity <= 0 || quantity > stock) {
                document.getElementById('quantity-' + menuId).value = stock;
                alert("Jumlah melebihi stok, diatur ke stok maksimum: " + stock);
                return;
            }

            const form = document.createElement("form");
            form.method = "POST";
            form.action = "<?= BASEURL; ?>/cart/btnAddCart";

            const menuInput = document.createElement("input");
            menuInput.type = "hidden";
            menuInput.name = "menu_id";
            menuInput.value = menuId;
            form.appendChild(menuInput);

            const quantityInput = document.createElement("input");
            quantityInput.type = "hidden";
            quantityInput.name = "quantity";
            quantityInput.value = quantity;
            form.appendChild(quantityInput);

            document.body.appendChild(form);
            form.submit();
        }

        // Fungsi untuk memperbarui kuantitas pada keranjang belanja
        function updateCartQuantity(menuId) {
            const quantityInput = document.getElementById('quantity-' + menuId);
            const quantityDisplay = document.getElementById('quantity-display-' + menuId);
            let quantity = parseInt(quantityInput.value);
            const stock = parseInt(quantityInput.max);

            if (quantity <= 0) {
                quantity = 1;
            } else if (quantity > stock) {
                quantity = stock;
            }

            if (stock === 0) {
                quantity = 0;
            }

            quantityInput.value = quantity;
            quantityDisplay.innerText = quantity;
        }

        // Fungsi untuk menambah kuantitas menu
        function increaseQuantity(menuId) {
            const quantityInput = document.getElementById('quantity-' + menuId);
            const quantityDisplay = document.getElementById('quantity-display-' + menuId);
            let quantity = parseInt(quantityInput.value);
            const stock = parseInt(quantityInput.max);

            if (stock > 0 && quantity < stock) {
                quantity += 1;
            }

            quantityInput.value = quantity;
            quantityDisplay.innerText = quantity;

            const increaseButton = document.querySelector(`#increase-${menuId}`);
            const decreaseButton = document.querySelector(`#decrease-${menuId}`);
            if (stock === 0 || quantity >= stock) {
                increaseButton.disabled = true;
            } else {
                increaseButton.disabled = false;
            }
            if (stock === 0 || quantity <= 1) {
                decreaseButton.disabled = true;
            } else {
                decreaseButton.disabled = false;
            }
        }

        // Fungsi untuk mengurangi kuantitas menu
        function decreaseQuantity(menuId) {
            const quantityInput = document.getElementById('quantity-' + menuId);
            const quantityDisplay = document.getElementById('quantity-display-' + menuId);
            let quantity = parseInt(quantityInput.value);
            const stock = parseInt(quantityInput.max);

            if (quantity > 1) {
                quantity -= 1;
            }

            if (stock === 0) {
                quantity = 0;
            }

            quantityInput.value = quantity;
            quantityDisplay.innerText = quantity;

            const increaseButton = document.querySelector(`#increase-${menuId}`);
            const decreaseButton = document.querySelector(`#decrease-${menuId}`);
            if (stock === 0 || quantity >= stock) {
                increaseButton.disabled = true;
            } else {
                increaseButton.disabled = false;
            }
            if (stock === 0 || quantity <= 1) {
                decreaseButton.disabled = true;
            } else {
                decreaseButton.disabled = false;
            }
        }

        // Fungsi untuk menyortir menu berdasarkan pilihan (A-Z, harga, dll)
        function sortMenu() {
            const sortValue = document.getElementById('sortDropdown').value;
            const menuGrid = document.getElementById('menuGrid');
            const menuItems = Array.from(menuGrid.children);

            menuItems.sort((a, b) => {
                const nameA = a.querySelector('h5').innerText.toLowerCase();
                const nameB = b.querySelector('h5').innerText.toLowerCase();
                const priceA = parseInt(a.querySelector('.text-lg.font-bold').innerText.replace('Rp ', '').replace(/\./g, ''));
                const priceB = parseInt(b.querySelector('.text-lg.font-bold').innerText.replace('Rp ', '').replace(/\./g, ''));

                switch (sortValue) {
                    case 'az':
                        return nameA.localeCompare(nameB);
                    case 'za':
                        return nameB.localeCompare(nameA);
                    case 'price-low':
                        return priceA - priceB;
                    case 'price-high':
                        return priceB - priceA;
                    default:
                        return 0;
                }
            });

            menuGrid.innerHTML = '';
            menuItems.forEach(item => menuGrid.appendChild(item));
        }

        // Fungsi untuk menyaring menu berdasarkan pencarian
        function filterMenu() {
            const searchValue = document.getElementById('searchInput').value.toLowerCase();
            const menuItems = document.querySelectorAll('#menuGrid > div');

            menuItems.forEach(item => {
                const title = item.querySelector('h5').innerText.toLowerCase();
                const description = item.querySelector('p').innerText.toLowerCase();
                if (title.includes(searchValue) || description.includes(searchValue)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
</body>