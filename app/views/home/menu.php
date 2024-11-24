<body class="bg-gray-100 text-gray-800">
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
                placeholder="Search menu by name or description..."
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
            if (stock <= 0) {
                alert("Sorry, this item is out of stock.");
                return;
            }

            document.getElementById('modalMenuId').value = menuid;
            document.getElementById('modalImage').src = "<?= BASEURL; ?>/" + imageUrl;
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalDescription').innerText = description;
            document.getElementById('modalPrice').innerText = "Rp " + price;
            document.getElementById('modalStock').innerText = "Stock: " + stock;
            document.getElementById('quantityInput').value = stock > 0 ? 1 : 0;
            document.getElementById('modalQuantity').value = stock > 0 ? 1 : 0;

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

        function updateQuantity(amount) {
            const quantityInput = document.getElementById('quantityInput');
            let quantity = parseInt(quantityInput.value);
            const stock = parseInt(document.getElementById('modalStock').innerText.replace('Stock: ', ''));
            quantity += amount;
            if (quantity <= 0) quantity = 1;
            if (quantity > stock) quantity = stock;
            quantityInput.value = quantity;
            document.getElementById('modalQuantity').value = quantity;
        }

        function addToCart() {
            const quantity = parseInt(document.getElementById('modalQuantity').value);
            if (quantity <= 0) {
                alert("Please select a valid quantity.");
                return false;
            }
            return true;
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

        function filterByCategory() {
            const category = document.getElementById('categoryDropdown').value;
            console.log("Selected Category:", category);

            const menuItems = document.querySelectorAll('#menuGrid > div');
            menuItems.forEach(item => {
                const itemCategory = item.getAttribute('data-category');
                console.log("Item Category:", itemCategory);

                if (category === 'all' || itemCategory === category) {
                    console.log("Show Item:", itemCategory);
                    item.style.display = '';
                } else {
                    console.log("Hide Item:", itemCategory);
                    item.style.display = 'none';
                }
            });
        }
    </script>
</body>