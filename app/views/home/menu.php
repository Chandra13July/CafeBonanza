<html>
<head>
    <title>Special Menu of the Day</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"></link>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .wishlist-icon {
            cursor: pointer;
            font-size: 1.5rem;
            color: transparent;
            -webkit-text-stroke: 1px black;
        }
        .wishlist-icon.active {
            color: red;
            -webkit-text-stroke: 1px red;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 p-4 sm:p-6 md:p-8 lg:p-10">
    <!-- Success Notification -->
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
                <option>Select SortMenu</option>
                <option value="az">A-Z</option>
                <option value="za">Z-A</option>
                <option value="price-low">Harga Paling Murah</option>
                <option value="price-high">Harga Paling Mahal</option>
            </select>
        </div>
    </div>

    <div id="menuGrid" class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-6 max-w-6xl mx-auto">
        <?php if (!empty($data['MenuItems'])): ?>
            <?php foreach ($data['MenuItems'] as $item): ?>
                <div class="bg-white rounded-lg shadow-md text-center flex flex-col">
                    <!-- Display Image -->
                    <img src="<?= BASEURL; ?>/<?= htmlspecialchars($item['ImageUrl']) ? htmlspecialchars($item['ImageUrl']) : 'default_image.jpg'; ?>"
                        alt="<?= htmlspecialchars($item['MenuName']); ?>"
                        class="w-full h-48 object-cover cursor-pointer rounded-t-lg m-0 p-0">

                    <div class="p-4 flex flex-col items-start">
                        <div class="flex justify-between items-center w-full">
                            <h5 class="text-lg font-semibold mb-2"><?= htmlspecialchars($item['MenuName']); ?></h5>
                            <i class="fas fa-heart wishlist-icon ml-2" onclick="toggleWishlist(this)"></i>
                        </div>
                        <p class="text-sm text-gray-600 mt-2 line-clamp-2 text-left"><?= htmlspecialchars($item['Description']); ?></p>

                        <div class="flex justify-between items-center mt-2 w-full">
                            <span class="text-lg font-bold">Rp <?= number_format($item['Price'], 0, ',', '.'); ?></span>
                            <span class="text-sm text-gray-600 ml-4">Stock: <?= $item['Stock']; ?></span>
                        </div>
                        <!-- Display Total Sold -->
                        <div class="flex justify-between items-center mt-2 w-full">
                            <span class="text-sm text-gray-600">Sold: <?= $item['TotalSold']; ?> items</span>
                            <!-- Quantity Input with + and - buttons -->
                            <div class="flex items-center space-x-2">
                                <button class="bg-gray-200 text-gray-700 px-2 py-1 rounded" onclick="decreaseQuantity(<?= $item['MenuId']; ?>)">-</button>
                                <input id="quantity-<?= $item['MenuId']; ?>" type="number" min="1" max="<?= $item['Stock']; ?>" value="1" class="w-12 text-center font-medium border border-gray-300 rounded" onchange="updateCartQuantity(<?= $item['MenuId']; ?>)" style="display: none;">
                                <span id="quantity-display-<?= $item['MenuId']; ?>" class="w-12 text-center font-medium border border-gray-300 rounded"><?= $item['Stock'] > 0 ? 1 : 0 ?></span>
                                <button class="bg-gray-200 text-gray-700 px-2 py-1 rounded" onclick="increaseQuantity(<?= $item['MenuId']; ?>)">+</button>
                            </div>
                        </div>

                        <!-- Add to Cart -->
                        <div class="quantity-controls flex items-center justify-center space-x-2 mt-4 w-full">
                            <button class="w-full bg-yellow-400 text-white py-2 px-4 rounded hover:bg-yellow-500" onclick="addToCart(<?= $item['MenuId']; ?>)">
                                Add to Cart
                            </button>
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
                }, 2000);
            }

            const errorNotification = document.getElementById('error-notification');
            if (errorNotification) {
                setTimeout(() => {
                    errorNotification.classList.add('hidden');
                }, 2000);
            }
        };

        function addToCart(menuId) {
            const quantity = parseInt(document.getElementById('quantity-' + menuId).value);
            const stock = parseInt(document.getElementById('quantity-' + menuId).max);

            if (quantity <= 0 || quantity > stock) {
                alert("Please select a valid quantity.");
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

        function toggleWishlist(element) {
            element.classList.toggle('active');
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

            quantityInput.value = quantity;
            quantityDisplay.innerText = quantity;
        }

        function increaseQuantity(menuId) {
            const quantityInput = document.getElementById('quantity-' + menuId);
            const quantityDisplay = document.getElementById('quantity-display-' + menuId);
            let quantity = parseInt(quantityInput.value);
            const stock = parseInt(quantityInput.max);

            if (quantity < stock) {
                quantity += 1;
            }

            quantityInput.value = quantity;
            quantityDisplay.innerText = quantity;
        }

        function decreaseQuantity(menuId) {
            const quantityInput = document.getElementById('quantity-' + menuId);
            const quantityDisplay = document.getElementById('quantity-display-' + menuId);
            let quantity = parseInt(quantityInput.value);

            if (quantity > 1) {
                quantity -= 1;
            }

            quantityInput.value = quantity;
            quantityDisplay.innerText = quantity;
        }

        function sortMenu() {
            const sortValue = document.getElementById('sortDropdown').value;
            const menuGrid = document.getElementById('menuGrid');
            const menuItems = Array.from(menuGrid.children); // Ambil semua elemen menu sebagai array

            menuItems.sort((a, b) => {
                const nameA = a.querySelector('h5').innerText.toLowerCase();
                const nameB = b.querySelector('h5').innerText.toLowerCase();
                const priceA = parseInt(a.querySelector('.text-lg.font-bold').innerText.replace('Rp ', '').replace(/\./g, ''));
                const priceB = parseInt(b.querySelector('.text-lg.font-bold').innerText.replace('Rp ', '').replace(/\./g, ''));

                if (sortValue === 'az') {
                    return nameA.localeCompare(nameB);
                } else if (sortValue === 'za') {
                    return nameB.localeCompare(nameA);
                } else if (sortValue === 'price-low') {
                    return priceA - priceB;
                } else if (sortValue === 'price-high') {
                    return priceB - priceA;
                }
            });

            menuGrid.innerHTML = '';
            menuItems.forEach(item => menuGrid.appendChild(item));
        }

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
</html>