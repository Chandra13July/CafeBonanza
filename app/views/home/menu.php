<html>
<style>
    body {
        font-family: 'Roboto', sans-serif;
    }

    .zoom-container {
        position: relative;
        overflow: hidden;
        width: 100%;
        height: 12rem;
    }

    .zoom-image {
        transition: transform 0.3s ease;
        transform-origin: center center;
        cursor: zoom-in;
    }

    .zoom-container:hover .zoom-image {
        transform: scale(1.5);
        cursor: zoom-in;
    }
</style>
</head>

<body class="bg-gray-100 text-gray-800 p-4 sm:p-6 md:p-8 lg:p-10">
    <?php if (isset($_SESSION['success'])): ?>
        <div id="success-notification" class="bg-green-500 text-white p-2 rounded shadow-lg fixed top-4 right-4 text-sm z-50">
            <?= $_SESSION['success']; ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div id="error-notification" class="bg-red-500 text-white p-2 rounded shadow-lg fixed top-16 right-4 text-sm z-50">
            <?= $_SESSION['error']; ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div id="notification" class="hidden fixed top-0 left-0 right-0 p-4 text-center text-lg font-semibold z-100"></div>

    <div class="text-center my-12">
        <h1 class="text-4xl font-bold mb-2">SPECIAL MENU OF THE DAY</h1>
        <p class="text-lg text-gray-600">Order delicious dishes and drinks!</p>
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

    <div id="menuGrid" class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-6 max-w-6xl mx-auto">
        <?php if (!empty($data['MenuItems'])): ?>
            <?php foreach ($data['MenuItems'] as $item): ?>
                <div class="bg-white rounded-lg shadow-md text-center flex flex-col">
                    <div class="zoom-container relative overflow-hidden rounded-t-lg">
                        <img
                            src="<?= BASEURL; ?>/<?= htmlspecialchars($item['ImageUrl']) ? htmlspecialchars($item['ImageUrl']) : 'default_image.jpg'; ?>"
                            alt="<?= htmlspecialchars($item['MenuName']); ?>"
                            class="zoom-image w-full h-48 object-cover m-0 p-0 rounded-t-lg">
                    </div>

                    <div class="p-4 flex flex-col items-start rounded-b-lg">
                        <h5 class="text-lg font-semibold mb-2"><?= htmlspecialchars($item['MenuName']); ?></h5>
                        <p class="text-sm text-gray-600 mt-2 line-clamp-2 text-left"><?= htmlspecialchars($item['Description']); ?></p>

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
                                <button id="decrease-<?= $item['MenuId']; ?>" class="bg-gray-200 text-gray-700 px-2 py-1 rounded" onclick="decreaseQuantity(<?= $item['MenuId']; ?>)" <?= $item['Stock'] == 0 ? 'disabled' : '' ?>>-</button>
                                <input id="quantity-<?= $item['MenuId']; ?>" type="number" min="1" max="<?= $item['Stock']; ?>" value="1" class="w-12 text-center font-medium border border-gray-300 rounded" onchange="updateCartQuantity(<?= $item['MenuId']; ?>)" style="display: none;">
                                <span id="quantity-display-<?= $item['MenuId']; ?>" class="w-12 text-center font-medium border border-gray-300 rounded"><?= $item['Stock'] > 0 ? 1 : 0 ?></span>
                                <button id="increase-<?= $item['MenuId']; ?>" class="bg-gray-200 text-gray-700 px-2 py-1 rounded" onclick="increaseQuantity(<?= $item['MenuId']; ?>)" <?= $item['Stock'] == 0 ? 'disabled' : '' ?>>+</button>
                            </div>

                        </div>

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
            <p class="text-gray-600 text-center col-span-4">No items found in this category.</p>
        <?php endif; ?>
    </div>

    <script>
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

                image.style.transformOrigin = `${x}% ${y}%`; // Menentukan titik zoom
            });

            container.addEventListener('mouseleave', () => {
                image.style.transformOrigin = 'center center'; // Reset ke posisi awal
            });
        });

        function addToCart(menuId) {
            const quantity = parseInt(document.getElementById('quantity-' + menuId).value);
            const stock = parseInt(document.getElementById('quantity-' + menuId).max);

            // Cek apakah quantity valid, jika lebih dari stok setel ke stok maksimum
            if (quantity <= 0 || quantity > stock) {
                document.getElementById('quantity-' + menuId).value = stock; // Set ke stok maksimum
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

            // Pastikan quantity tidak melebihi stok jika stoknya 0
            if (stock === 0) {
                quantity = 0; // jika stok 0, quantity harus 0
            }

            quantityInput.value = quantity;
            quantityDisplay.innerText = quantity;
        }

        function increaseQuantity(menuId) {
            const quantityInput = document.getElementById('quantity-' + menuId);
            const quantityDisplay = document.getElementById('quantity-display-' + menuId);
            let quantity = parseInt(quantityInput.value);
            const stock = parseInt(quantityInput.max);

            // Hanya tambah jika stok lebih besar dari 0 dan quantity kurang dari stok
            if (stock > 0 && quantity < stock) {
                quantity += 1;
            }

            quantityInput.value = quantity;
            quantityDisplay.innerText = quantity;

            // Disable tombol + dan - jika stok 0
            const increaseButton = document.querySelector(`#increase-${menuId}`);
            const decreaseButton = document.querySelector(`#decrease-${menuId}`);
            if (stock === 0 || quantity >= stock) {
                increaseButton.disabled = true; // Disable tombol +
            } else {
                increaseButton.disabled = false; // Enable tombol + jika stok lebih besar dari quantity
            }
            if (stock === 0 || quantity <= 1) {
                decreaseButton.disabled = true; // Disable tombol -
            } else {
                decreaseButton.disabled = false; // Enable tombol - jika quantity lebih besar dari 1
            }
        }

        function decreaseQuantity(menuId) {
            const quantityInput = document.getElementById('quantity-' + menuId);
            const quantityDisplay = document.getElementById('quantity-display-' + menuId);
            let quantity = parseInt(quantityInput.value);
            const stock = parseInt(quantityInput.max);

            // Jangan izinkan quantity berkurang lebih rendah dari 1
            if (quantity > 1) {
                quantity -= 1;
            }

            // Pastikan quantity tidak bisa berkurang jika stoknya 0
            if (stock === 0) {
                quantity = 0; // Set ke 0 jika stoknya 0
            }

            quantityInput.value = quantity;
            quantityDisplay.innerText = quantity;

            // Disable tombol + dan - jika stok 0
            const increaseButton = document.querySelector(`#increase-${menuId}`);
            const decreaseButton = document.querySelector(`#decrease-${menuId}`);
            if (stock === 0 || quantity >= stock) {
                increaseButton.disabled = true; // Disable tombol +
            } else {
                increaseButton.disabled = false; // Enable tombol + jika stok lebih besar dari quantity
            }
            if (stock === 0 || quantity <= 1) {
                decreaseButton.disabled = true; // Disable tombol -
            } else {
                decreaseButton.disabled = false; // Enable tombol - jika quantity lebih besar dari 1
            }
        }

        function sortMenu() {
            const sortValue = document.getElementById('sortDropdown').value;
            const menuGrid = document.getElementById('menuGrid');
            const menuItems = Array.from(menuGrid.children);

            console.log("Sort Value: ", sortValue);

            menuItems.sort((a, b) => {
                const nameA = a.querySelector('h5').innerText.toLowerCase();
                const nameB = b.querySelector('h5').innerText.toLowerCase();
                const priceA = parseInt(a.querySelector('.text-lg.font-bold').innerText.replace('Rp ', '').replace(/\./g, ''));
                const priceB = parseInt(b.querySelector('.text-lg.font-bold').innerText.replace('Rp ', '').replace(/\./g, ''));
                console.log("Price A: ", priceA, "Price B: ", priceB);

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

        function filterMenu() {
            const searchValue = document.getElementById('searchInput').value.toLowerCase();
            const menuItems = document.querySelectorAll('#menuGrid > div');

            console.log("Search Value: ", searchValue);

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