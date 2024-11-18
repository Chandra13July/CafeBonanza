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
            overflow: hidden;
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
    </style>
</head>

<body class="bg-gray-100 text-gray-800">
    <div class="text-center my-12">
        <h1 class="text-4xl font-bold mb-2">MENU SPESIAL HARI INI</h1>
        <p class="text-lg text-gray-600">Ayo pesan hidangan dan minuman yang enak!</p>
    </div>

    <div class="px-4 sm:px-6 lg:px-8">
        <div id="menuGrid" class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-6 max-w-6xl mx-auto">
            <?php foreach ($data['MenuItems'] as $item): ?>
                <div class="card bg-white rounded-lg shadow-md p-4 text-center flex flex-col">
                    <img src="<?= BASEURL; ?>/<?= htmlspecialchars($item['ImageUrl']); ?>" alt="<?= htmlspecialchars($item['MenuName']); ?>" class="w-full h-48 rounded-md object-cover mb-4 cursor-pointer" onclick="openModal('<?= htmlspecialchars($item['ImageUrl']); ?>', '<?= htmlspecialchars($item['MenuName']); ?>', '<?= htmlspecialchars($item['Description']); ?>', '<?= number_format($item['Price'], 0, ',', '.'); ?>', '<?= $item['Stock']; ?>')">
                    <div class="flex flex-col items-start">
                        <h5 class="text-lg font-semibold text-left mb-2"><?= htmlspecialchars($item['MenuName']); ?></h5>
                        <p class="text-sm text-gray-600 mt-2 line-clamp-2 text-left"><?= htmlspecialchars($item['Description']); ?></p>
                        <div class="flex justify-between items-center mt-2 text-left w-full">
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
                    <button class="w-full bg-yellow-400 text-white py-2 rounded hover:bg-yellow-500">
                        Add to Cart
                    </button>
                    <button class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600">
                        Buy Now
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(imageUrl, title, description, price, stock) {
            document.getElementById('modalImage').src = "<?= BASEURL; ?>/" + imageUrl;
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalDescription').innerText = description;
            document.getElementById('modalPrice').innerText = "Rp " + price;
            document.getElementById('modalStock').innerText = "Stock: " + stock;
            document.getElementById('quantityInput').value = stock > 0 ? 1 : 0;

            // Set tombol quantity aktif atau tidak berdasarkan stok
            const decreaseBtn = document.querySelector('.quantity-btn:first-child');
            const increaseBtn = document.querySelector('.quantity-btn:last-child');
            if (stock <= 0) {
                decreaseBtn.disabled = true;
                increaseBtn.disabled = true;
            } else {
                decreaseBtn.disabled = false;
                increaseBtn.disabled = false;
            }

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
        }
    </script>
</body>

</html>