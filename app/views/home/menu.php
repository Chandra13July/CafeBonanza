<body class="bg-gray-100 text-gray-800">

    <!-- Success and Error Notifications -->
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

    <!-- Content -->
    <div class="text-center my-12">
        <h1 class="text-4xl font-bold mb-2">SPECIAL MENU OF THE DAY</h1>
        <p class="text-lg text-gray-600">Order delicious dishes and drinks!</p>
    </div>

    <div class="flex flex-wrap gap-4 justify-center items-center mb-6">
        <input type="text" id="searchInput" placeholder="Search menu..." class="px-4 py-2 border rounded-lg shadow-sm focus:ring focus:ring-blue-200">

        <select id="categoryFilter" class="px-4 py-2 border rounded-lg shadow-sm focus:ring focus:ring-blue-200" onchange="filterByCategory()">
            <option value="all">Select Category</option>
            <option value="all">All Menu</option>
            <option value="food">Food</option>
            <option value="drink">Drink</option>
        </select>

        <select id="priceFilter" class="px-4 py-2 border rounded-lg shadow-sm focus:ring focus:ring-blue-200" onchange="filterMenu()">
            <option value="default">Sort by</option>
            <option value="low-high">Price: Low to High</option>
            <option value="high-low">Price: High to Low</option>
            <option value="a-z">A to Z</option>
            <option value="z-a">Z to A</option>
        </select>
    </div>

    <!-- Menu Grid -->
    <div id="menuGrid" class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-6 max-w-6xl mx-auto">
        <?php if (!empty($data['MenuItems'])): ?>
            <?php foreach ($data['MenuItems'] as $item): ?>
                <div class="card bg-white rounded-lg shadow-md text-center flex flex-col">
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

    <!-- Modal -->
    <div id="modal" class="modal-overlay fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50" onclick="closeModal(event)">
        <div class="modal bg-white rounded-lg w-11/12 sm:w-96 p-4 animate-fadeIn relative" onclick="event.stopPropagation()">
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
                <div class="quantity-controls flex items-center justify-center mt-4 gap-4">
                    <button class="quantity-btn w-8 h-8 bg-gray-200 text-gray-700 rounded-full" onclick="updateQuantity(-1)">-</button>
                    <input id="quantityInput" type="text" class="quantity-input w-12 text-center text-lg" value="1" readonly>
                    <button class="quantity-btn w-8 h-8 bg-gray-200 text-gray-700 rounded-full" onclick="updateQuantity(1)">+</button>
                </div>
                <div class="flex gap-4 mt-6">
                    <form action="<?= BASEURL; ?>/home/btnAddCart" method="POST" class="w-full" onsubmit="return addToCart()">
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

            document.getElementById('modal').classList.remove('hidden');
            document.body.classList.add('modal-open');
        }

        function closeModal(event) {
            if (event.target.id === 'modal') {
                document.getElementById('modal').classList.add('hidden');
                document.body.classList.remove('modal-open');
            }
        }

        function updateQuantity(change) {
            const quantityInput = document.getElementById('quantityInput');
            const stock = parseInt(document.getElementById('modalStock').innerText.replace('Stock: ', ''));
            let currentQuantity = parseInt(quantityInput.value);

            currentQuantity += change;
            if (currentQuantity < 1) currentQuantity = 1;
            if (currentQuantity > stock) currentQuantity = stock;
            quantityInput.value = stock > 0 ? currentQuantity : 0;

            document.getElementById('modalQuantity').value = currentQuantity;
        }

        function addToCart() {
            let quantity = document.getElementById('quantityInput').value;
            const stock = parseInt(document.getElementById('modalStock').innerText.replace('Stock: ', ''));

            if (quantity > stock) {
                alert("Sorry, we don't have enough stock.");
                return false;
            }
            return true;
        }
        document.getElementById('searchInput').addEventListener('input', searchMenu);

        function searchMenu() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const menuItems = document.querySelectorAll('.card');
            menuItems.forEach(item => {
                const title = item.querySelector('h5').innerText.toLowerCase();
                if (title.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function filterMenu() {
            const filter = document.getElementById('priceFilter').value;
            const menuItems = [...document.querySelectorAll('.card')];

            if (filter === 'low-high') {
                menuItems.sort((a, b) => {
                    const priceA = parseInt(a.querySelector('.price').innerText.replace('Rp ', '').replace('.', ''));
                    const priceB = parseInt(b.querySelector('.price').innerText.replace('Rp ', '').replace('.', ''));
                    return priceA - priceB;
                });
            } else if (filter === 'high-low') {
                menuItems.sort((a, b) => {
                    const priceA = parseInt(a.querySelector('.price').innerText.replace('Rp ', '').replace('.', ''));
                    const priceB = parseInt(b.querySelector('.price').innerText.replace('Rp ', '').replace('.', ''));
                    return priceB - priceA;
                });
            } else if (filter === 'a-z') {
                menuItems.sort((a, b) => a.querySelector('h5').innerText.localeCompare(b.querySelector('h5').innerText));
            } else if (filter === 'z-a') {
                menuItems.sort((a, b) => b.querySelector('h5').innerText.localeCompare(a.querySelector('h5').innerText));
            }

            const menuGrid = document.getElementById('menuGrid');
            menuGrid.innerHTML = '';
            menuItems.forEach(item => menuGrid.appendChild(item));
        }
    </script>
</body>