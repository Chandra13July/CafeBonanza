<head>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-800">
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
    </div>

    <div class="flex flex-wrap gap-4 justify-center items-center">
        <div class="flex items-center space-x-2">
            <input type="text" id="searchInput" placeholder="Search menu..." class="px-4 py-2 border rounded-lg shadow-sm focus:ring focus:ring-blue-200">
        </div>
        <div>
            <select id="categoryFilter" class="px-4 py-2 border rounded-lg shadow-sm focus:ring focus:ring-blue-200" onchange="filterByCategory()">
                <option value="all">Select Category</option>
                <option value="all">All Menu</option>
                <option value="food">Food</option>
                <option value="drink">Drink</option>
            </select>
        </div>
        <div>
            <select id="priceFilter" class="px-4 py-2 border rounded-lg shadow-sm focus:ring focus:ring-blue-200" onchange="filterMenu()">
                <option value="default">Sort by</option>
                <option value="low-high">Price: Low to High</option>
                <option value="high-low">Price: High to Low</option>
                <option value="a-z">A to Z</option>
                <option value="z-a">Z to A</option>
            </select>
        </div>
    </div>

    <br>

    <div id="menuGrid" class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-6 max-w-6xl mx-auto">
        <?php if (!empty($data['MenuItems'])): ?>
            <?php foreach ($data['MenuItems'] as $item): ?>
                <div class="bg-white rounded-lg shadow-md text-center flex flex-col">
                    <img src="<?= BASEURL; ?>/<?= htmlspecialchars($item['ImageUrl']); ?>"
                        alt="<?= htmlspecialchars($item['MenuName']); ?>"
                        class="w-full h-48 object-cover cursor-pointer rounded-t-lg m-0 p-0"
                        onclick="openModal('<?= htmlspecialchars($item['MenuId']); ?>', '<?= htmlspecialchars($item['ImageUrl']); ?>', '<?= htmlspecialchars($item['MenuName']); ?>', '<?= htmlspecialchars($item['Description']); ?>', '<?= number_format($item['Price'], 0, ',', '.'); ?>', '<?= $item['Stock']; ?>')">
                    <div class="p-4 flex flex-col items-start">
                        <h5 class="text-lg font-semibold mb-2"><?= htmlspecialchars($item['MenuName']); ?></h5>
                        <p class="text-sm text-gray-600 mt-2 line-clamp-2 text-left"><?= htmlspecialchars($item['Description']); ?></p>
                        <div class="flex justify-between items-center mt-2 w-full">
                            <span class="text-lg font-bold">Rp <?= number_format($item['Price'], 0, ',', '.'); ?></span>
                            <span class="text-sm text-gray-600 ml-4">Stock: <?= $item['Stock']; ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-gray-600 text-center col-span-4">No items found in this category.</p>
        <?php endif; ?>
    </div>

    <div id="modal" class="modal-overlay fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 flex items-center justify-center hidden z-50" onclick="closeModal(event)">
        <div class="modal bg-white rounded-lg overflow-hidden w-90 max-w-xs animate-fadeIn relative" onclick="event.stopPropagation()">
            <div class="modal-header mb-4">
                <img id="modalImage" src="" alt="Menu Image" class="w-full h-48 object-cover rounded-t-md">
            </div>
            <div class="modal-content p-4">
                <h2 id="modalTitle" class="text-xl font-bold mb-2"></h2>
                <p id="modalDescription" class="text-gray-600 mb-4"></p>
                <div class="flex justify-between items-center mb-2">
                    <span id="modalPrice" class="text-lg font-bold"></span>
                    <span id="modalStock" class="text-sm text-gray-600"></span>
                </div>
                <div class="quantity-controls flex items-center justify-center space-x-2 mb-6">
                    <button class="quantity-btn w-8 h-8 bg-gray-100 text-gray-800 text-xl rounded-full" onclick="updateQuantity(-1)">-</button>
                    <input id="quantityInput" type="text" class="quantity-input w-12 text-center font-medium border-none bg-transparent" value="1" readonly>
                    <button class="quantity-btn w-8 h-8 bg-gray-100 text-gray-800 text-xl rounded-full" onclick="updateQuantity(1)">+</button>
                </div>
                <div class="flex gap-4">
                    <form action="<?= BASEURL; ?>/cart/btnAddCart" method="POST" class="w-full" onsubmit="return addToCart()">
                        <input type="hidden" name="menu_id" value="" id="modalMenuId">
                        <input type="hidden" id="modalQuantity" name="quantity" value="1">
                        <button type="submit" class="w-full bg-yellow-400 text-white py-2 rounded hover:bg-yellow-500">
                            Add to Cart
                        </button>
                    </form>
                    <a id="buyNowLink" href="#" class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600 text-center flex items-center justify-center">
                        Buy Now
                    </a>
                </div>
            </div>
        </div>
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

        function openModal(menuid, imageUrl, title, description, price, stock) {
            document.getElementById('modalMenuId').value = menuid;
            document.getElementById('modalImage').src = "<?= BASEURL; ?>/" + imageUrl;
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalDescription').innerText = description;
            document.getElementById('modalPrice').innerText = "Rp " + price;
            document.getElementById('modalStock').innerText = "Stock: " + stock;
            document.getElementById('quantityInput').value = stock > 0 ? 1 : 0;
            document.getElementById('modalQuantity').value = stock > 0 ? 1 : 0;

            const buyNowLink = document.getElementById('buyNowLink');
            buyNowLink.href = `<?= BASEURL; ?>/checkout?menu_id=${title}&quantity=1`;

            document.getElementById('modal').style.display = "flex";
            document.body.classList.add('modal-open');
            document.body.style.overflow = 'hidden'; 
        }

        function closeModal(event) {
            if (event.target === document.getElementById('modal')) {
                document.getElementById('modal').style.display = "none";
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
            }
        }

        function updateQuantity(change) {
            const quantityInput = document.getElementById('quantityInput');
            const stock = parseInt(document.getElementById('modalStock').innerText.replace('Stock: ', ''));
            let currentQuantity = parseInt(quantityInput.value);

            currentQuantity += change;

            if (currentQuantity < 1) currentQuantity = 1;
            if (currentQuantity > stock) currentQuantity = stock;

            quantityInput.value = currentQuantity;
            document.getElementById('modalQuantity').value = currentQuantity;
        }

        function addToCart() {
            const quantity = document.getElementById('modalQuantity').value;
            if (quantity < 1) {
                alert('Please choose at least 1 item');
                return false;
            }
            return true;
        }
    </script>
</body>
