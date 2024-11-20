<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Spesial Hari Ini</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Alexandria', sans-serif;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 50;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .modal {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            width: 90%;
            max-width: 360px;
            animation: fadeIn 0.3s ease-in-out;
            position: relative;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        body.modal-open {
            overflow: hidden;
        }

        .close-btn {
            position: absolute;
            top: 12px;
            right: 12px;
            font-size: 20px;
            color: #333;
            cursor: pointer;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 10px;
        }

        .quantity-btn {
            width: 32px;
            height: 32px;
            background: #f0f0f0;
            color: #333;
            font-size: 18px;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            user-select: none;
        }

        .quantity-input {
            width: 50px;
            text-align: center;
            font-size: 16px;
            border: none;
            background: transparent;
            pointer-events: none;
        }

        #notification {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            padding: 16px;
            text-align: center;
            display: none;
            z-index: 100;
            font-size: 16px;
            font-weight: 600;
        }

        .love-icon {
            font-size: 20px;
            transition: color 0.3s ease;
        }

        .love-icon.loved {
            color: red;
        }
    </style>
</head>

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


<body class="bg-gray-100 text-gray-800">
    <div id="notification" class="hidden"></div>

    <div class="text-center my-12">
        <h1 class="text-4xl font-bold mb-2">MENU SPESIAL HARI INI</h1>
        <p class="text-lg text-gray-600">Ayo pesan hidangan dan minuman yang enak!</p>
    </div>

    <div class="px-4 sm:px-6 lg:px-8">
        <div id="menuGrid" class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-6 max-w-6xl mx-auto">
            <?php foreach ($data['MenuItems'] as $item): ?>
                <div class="card bg-white rounded-lg shadow-md p-4 text-center flex flex-col">
                    <img src="<?= BASEURL; ?>/<?= htmlspecialchars($item['ImageUrl']); ?>" alt="<?= htmlspecialchars($item['MenuName']); ?>" class="w-full h-48 rounded-md object-cover mb-4 cursor-pointer" onclick="openModal('<?= htmlspecialchars($item['MenuId']); ?>','<?= htmlspecialchars($item['ImageUrl']); ?>', '<?= htmlspecialchars($item['MenuName']); ?>', '<?= htmlspecialchars($item['Description']); ?>', '<?= number_format($item['Price'], 0, ',', '.'); ?>', '<?= $item['Stock']; ?>')">
                    <div class="flex flex-col items-start">
                        <div class="flex justify-between items-center w-full">
                            <h5 class="text-lg font-semibold mb-2"><?= htmlspecialchars($item['MenuName']); ?></h5>
                            <i class="fas fa-heart text-gray-300 cursor-pointer love-icon" onclick="toggleLove(this)"></i>
                        </div>
                        <p class="text-sm text-gray-600 mt-2 line-clamp-2 text-left"><?= htmlspecialchars($item['Description']); ?></p>
                        <div class="flex justify-between items-center mt-2 w-full">
                            <span class="text-lg font-bold">Rp <?= number_format($item['Price'], 0, ',', '.'); ?></span>
                            <span class="text-sm text-gray-600 ml-4">Stock: <?= $item['Stock']; ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal" class="modal-overlay">
        <div class="modal">
            <button class="close-btn" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
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
                <div class="quantity-controls">
                    <button class="quantity-btn" onclick="updateQuantity(-1)">-</button>
                    <input id="quantityInput" type="text" class="quantity-input" value="1" readonly>
                    <button class="quantity-btn" onclick="updateQuantity(1)">+</button>
                </div>
                <div class="flex gap-4 mt-6">
                    <form action="<?= BASEURL; ?>/home/btnAddCart" method="POST" class="w-full" onsubmit="return addToCart()">
                        <input type="hidden" name="menu_id" value="" id="modalMenuId">
                        <input type="hidden" id="modalQuantity" name="quantity" value="1">
                        <button type="submit" class="w-full bg-yellow-400 text-white py-2 rounded hover:bg-yellow-500 text-center">
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
            // Menghapus notifikasi sukses setelah 3 detik
            const successNotification = document.getElementById('success-notification');
            if (successNotification) {
                setTimeout(() => {
                    successNotification.classList.add('hidden');
                }, 3000);
            }

            // Menghapus notifikasi error setelah 3 detik
            const errorNotification = document.getElementById('error-notification');
            if (errorNotification) {
                setTimeout(() => {
                    errorNotification.classList.add('hidden');
                }, 3000);
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
        }

        function closeModal() {
            document.getElementById('modal').style.display = "none";
            document.body.classList.remove('modal-open');
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

            if (quantity < 1) {
                showNotification('Jumlah harus lebih dari 0!', false);
                return false;
            }

            if (quantity > stock) {
                showNotification('Jumlah melebihi stok yang tersedia!', false);
                return false;
            }

            showNotification('Item berhasil ditambahkan ke keranjang!');
            return true;
        }

        function toggleLove(element) {
            element.classList.toggle('loved');
            if (element.classList.contains('loved')) {
                showNotification('Ditambahkan ke daftar favorit!', true);
            } else {
                showNotification('Dihapus dari daftar favorit!', false);
            }
        }
    </script>
</body>

</html>