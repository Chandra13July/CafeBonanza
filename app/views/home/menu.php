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
        .quantity-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
        }
        .quantity-input {
            width: 60px;
            height: 28px;
            text-align: center;
            font-size: 18px;
            margin: 0 8px;
        }
        .modal-content {
            max-width: 400px;
            margin: auto;
            padding: 20px;
        }
        .close-button {
            position: absolute;
            top: 12px;
            left: 12px;
            cursor: pointer;
            font-size: 24px;
            color: #333;
        }
        .modal-header {
            position: relative;
            width: 100%;
            height: 200px;
            overflow: hidden;
        }
        #productImage {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="text-center my-12">
        <h1 class="text-4xl font-bold mb-2">MENU SPESIAL HARI INI</h1>
        <p class="text-lg text-gray-600">Ayo pesan hidangan dan minuman yang enak!</p>
    </div>

    <!-- Menu Grid -->
    <div class="px-4 sm:px-6 lg:px-8">
        <div id="menuGrid" class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-6 max-w-6xl mx-auto">
            <?php foreach ($data['MenuItems'] as $item): ?>
                <div class="card bg-white rounded-lg shadow-md p-4 text-center" data-name="<?= htmlspecialchars($item['MenuName']); ?>" data-price="<?= $item['Price']; ?>" data-id="<?= $item['MenuId']; ?>" data-stock="<?= $item['Stock']; ?>">
                    <img src="<?= BASEURL; ?>/<?= htmlspecialchars($item['ImageUrl']); ?>" alt="<?= htmlspecialchars($item['MenuName']); ?>" class="w-full h-48 rounded-md object-cover mb-4 cursor-pointer" onclick="openModal(<?= $item['MenuId']; ?>)">
                    <h5 class="text-lg font-semibold text-left cursor-pointer" onclick="openModal(<?= $item['MenuId']; ?>)"><?= htmlspecialchars($item['MenuName']); ?></h5>
                    <p class="text-sm text-gray-600 mt-2 line-clamp-2 text-left"><?= htmlspecialchars($item['Description']); ?></p>
                    <div class="flex justify-between items-center mt-2 text-left">
                        <span class="text-lg font-bold">Rp <?= number_format($item['Price'], 0, ',', '.'); ?></span>
                        <?php if ($item['Stock'] == 0): ?>
                            <span class="text-sm text-red-500">Habis</span>
                        <?php elseif ($item['Stock'] <= 5): ?>
                            <span class="text-sm text-yellow-500">Hampir Habis <?= $item['Stock']; ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden max-w-lg relative p-6" onclick="event.stopPropagation()">
            <span id="closeButton" class="close-button"><i class="fas fa-times-circle"></i></span>
            <div class="modal-content text-center">
                <div class="modal-header">
                    <img id="productImage" src="" alt="" class="w-full h-full object-cover">
                </div>
                <h5 id="productName" class="text-xl font-semibold mt-2"></h5>
                <p id="productDescription" class="text-sm text-gray-600 mt-2"></p>
                <p id="productStock" class="text-sm mt-2"></p>

                <!-- Quantity Controls -->
                <div class="flex justify-center items-center mt-4">
                    <button id="decreaseQuantity" class="quantity-btn bg-red-500 text-white">
                        <i class="fas fa-minus"></i>
                    </button>
                    <input id="quantityInput" type="text" value="1" class="quantity-input text-center" readonly>
                    <button id="increaseQuantity" class="quantity-btn bg-green-500 text-white">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>

                <!-- Form to add to cart -->
                <form action="<?= BASEURL; ?>/Cart/addToCart" method="POST">
                    <input type="hidden" name="MenuId" id="modalMenuId">
                    <input type="hidden" name="MenuName" id="modalMenuName">
                    <input type="hidden" name="Price" id="modalPrice">
                    <input type="hidden" name="Quantity" id="modalQuantity">
                    <input type="hidden" name="ImageUrl" id="modalImageUrl">

                    <div class="mt-4 flex justify-center space-x-4">
                        <button type="submit" class="bg-black text-white py-2 px-6 rounded-lg">Add to Cart</button>
                        <button type="button" id="buyNowButton" class="bg-black text-white py-2 px-6 rounded-lg">Buy Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let selectedItem = null;
        let quantity = 1;

        function openModal(itemId) {
            // Get item details from the page
            const item = document.querySelector(`.card[data-id="${itemId}"]`);
            const itemName = item.getAttribute('data-name');
            const itemPrice = item.getAttribute('data-price');
            const itemDescription = item.querySelector('p').textContent;
            const itemImage = item.querySelector('img').src;
            const itemStock = item.getAttribute('data-stock'); // Get stock from data-stock

            selectedItem = {
                MenuId: itemId,
                MenuName: itemName,
                Price: parseInt(itemPrice.replace(/[^\d]/g, '')), // Remove non-numeric characters
                Description: itemDescription,
                ImageUrl: itemImage,
                Stock: itemStock // Set stock directly from data-stock
            };

            // Populate modal with item details
            document.getElementById('productImage').src = itemImage;
            document.getElementById('productName').innerText = itemName;
            document.getElementById('productDescription').innerText = itemDescription;
            document.getElementById('productStock').innerText = `Stock: ${itemStock}`;
            document.getElementById('modalMenuId').value = itemId;
            document.getElementById('modalMenuName').value = itemName;
            document.getElementById('modalPrice').value = itemPrice;
            document.getElementById('modalQuantity').value = quantity;
            document.getElementById('modalImageUrl').value = itemImage;

            document.getElementById('confirmationModal').classList.remove('hidden');
        }

        // Close the modal when the close button is clicked
        document.getElementById('closeButton').addEventListener('click', () => {
            document.getElementById('confirmationModal').classList.add('hidden');
        });

        // Quantity buttons
        document.getElementById('increaseQuantity').addEventListener('click', () => {
            if (quantity < selectedItem.Stock) {
                quantity++;
                document.getElementById('quantityInput').value = quantity;
                document.getElementById('modalQuantity').value = quantity;
            } else {
                alert("Stock tidak cukup!");
            }
        });
        document.getElementById('decreaseQuantity').addEventListener('click', () => {
            if (quantity > 1) {
                quantity--;
                document.getElementById('quantityInput').value = quantity;
                document.getElementById('modalQuantity').value = quantity;
            }
        });

        // Buy now button (you can implement the checkout process here)
        document.getElementById('buyNowButton').addEventListener('click', () => {
            const quantityToBuy = parseInt(document.getElementById('modalQuantity').value);
            const currentStock = selectedItem.Stock;

            if (currentStock >= quantityToBuy) {
                alert('Pembelian berhasil, stock akan berkurang!');
                
                // Update the stock on client side
                selectedItem.Stock -= quantityToBuy;
                document.getElementById('productStock').innerText = `Stock: ${selectedItem.Stock}`;

                // Update stock in the card
                const itemCard = document.querySelector(`.card[data-id="${selectedItem.MenuId}"]`);
                itemCard.setAttribute('data-stock', selectedItem.Stock);
                
                // If stock is zero, mark as sold out
                if (selectedItem.Stock <= 0) {
                    itemCard.querySelector('.text-left').innerText = "Habis";
                }

                document.getElementById('confirmationModal').classList.add('hidden');
            } else {
                alert('Stock tidak cukup untuk pembelian!');
            }
        });
    </script>
</body>
</html>
