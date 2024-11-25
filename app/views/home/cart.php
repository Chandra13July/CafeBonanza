<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Shopping Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const qtyElements = document.querySelectorAll('.qty');
            const totalElement = document.getElementById('total');
            const buyButton = document.getElementById('buy-button');
            const price = 22500;
            const selectAllCheckbox = document.getElementById('select-all');
            const itemCheckboxes = document.querySelectorAll('.item-checkbox');
            const selectAllLabel = document.getElementById('select-all-label');

            const updateTotal = () => {
                let totalQty = 0;
                document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
                    const qtyElement = checkbox.closest('.item').querySelector('.qty');
                    totalQty += parseInt(qtyElement.innerText);
                });
                totalElement.innerText = totalQty > 0 ? 'Rp' + (totalQty * price).toLocaleString('id-ID') : '-';
                buyButton.innerText = totalQty > 0 ? 'Beli (' + totalQty + ')' : 'Beli';
            };

            const updateSelectAllLabel = () => {
                const checkedCount = document.querySelectorAll('.item-checkbox:checked').length;
                selectAllLabel.innerText = `Pilih Semua (${checkedCount})`;
            };

            document.querySelectorAll('.decrease').forEach(button => {
                button.addEventListener('click', () => {
                    const qtyElement = button.nextElementSibling;
                    let qty = parseInt(qtyElement.innerText);
                    if (qty > 1) {
                        qtyElement.innerText = --qty;
                        updateTotal();
                    }
                });
            });

            document.querySelectorAll('.increase').forEach(button => {
                button.addEventListener('click', () => {
                    const qtyElement = button.previousElementSibling;
                    const stockElement = button.closest('.item').querySelector('.stock');
                    const stock = parseInt(stockElement.innerText.split(': ')[1]);
                    let qty = parseInt(qtyElement.innerText);
                    if (qty < stock) {
                        qtyElement.innerText = ++qty;
                        updateTotal();
                    }
                });
            });
            updateTotal();

            selectAllCheckbox.addEventListener('change', function() {
                itemCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
                updateTotal();
                updateSelectAllLabel();
            });

            itemCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (!checkbox.checked) {
                        selectAllCheckbox.checked = false;
                    } else if (document.querySelectorAll('.item-checkbox:checked').length === itemCheckboxes.length) {
                        selectAllCheckbox.checked = true;
                    }
                    updateTotal();
                    updateSelectAllLabel();
                });
            });

            document.querySelectorAll('.delete-item').forEach(button => {
                button.addEventListener('click', () => {
                    const item = button.closest('.item');
                    item.remove();
                    updateTotal();
                    updateSelectAllLabel();
                });
            });

            document.getElementById('delete-all').addEventListener('click', () => {
                document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
                    const item = checkbox.closest('.item');
                    item.remove();
                });
                updateTotal();
                updateSelectAllLabel();
            });

            document.querySelectorAll('.wishlist-item').forEach(button => {
                button.addEventListener('click', () => {
                    button.classList.toggle('text-red-500');
                    button.classList.toggle('text-gray-500');
                });
            });
        });
    </script>
    <style>
        .truncate-2-lines {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 75%;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto p-6 flex flex-col lg:flex-row">
        <div class="w-full lg:w-3/4 bg-white p-8 rounded-lg shadow-md mb-6 lg:mb-0 lg:mr-6">
            <h1 class="text-3xl font-bold mb-6">Keranjang</h1>
            <div class="border-b pb-6 mb-6">
                <div class="flex items-center">
                    <input class="mr-2" id="select-all" type="checkbox" />
                    <span id="select-all-label" class="font-semibold text-lg">
                        Pilih Semua (<?= isset($cartItems) ? count($cartItems) : 0 ?>)
                    </span>
                    <a class="ml-auto text-green-500 cursor-pointer text-lg" id="delete-all">Hapus</a>
                </div>
            </div>
            <div id="cartItems">
                <?php
                $user_id = $_SESSION['user_id'];
                $cartModel = new CartModel();
                $cartItems = $cartModel->getCart($user_id);

                foreach ($cartItems as $item) :
                ?>
                    <div class="item flex items-start mb-6">
                        <input class="mr-2 mt-2 item-checkbox" type="checkbox" />
                        <div class="flex-grow">
                            <div class="flex items-start">
                                <img alt="<?= htmlspecialchars($item['MenuName']); ?>" class="w-24 h-24 rounded-lg mr-6" src="<?= BASEURL; ?>/<?= htmlspecialchars($item['ImageUrl'] ?? 'img/user.png'); ?>" />
                                <div class="flex-grow">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="ml-2 text-lg"><?= htmlspecialchars($item['MenuName']) ?></span>
                                        <span class="text-xl font-bold text-gray-800">Rp<?= number_format($item['Price'], 0, ',', '.') ?></span>
                                    </div>
                                    <p class="ml-2 text-gray-600 truncate-2-lines"><?= htmlspecialchars($item['Description']) ?></p>
                                    <div class="flex items-center justify-between mt-2">
                                        <p class="ml-2 text-gray-600 stock">Stok: <?= htmlspecialchars($item['Stock']) ?></p>
                                        <div class="flex items-center">
                                            <i class="fas fa-heart text-gray-500 mr-4 wishlist-item cursor-pointer"></i>
                                            <i class="fas fa-trash text-gray-500 mr-4 delete-item cursor-pointer"></i>
                                            <div class="flex items-center border rounded">
                                                <button class="px-2 decrease">-</button>
                                                <span class="px-2 qty"><?= htmlspecialchars($item['Quantity']) ?></span>
                                                <button class="px-2 increase">+</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="w-full lg:w-1/4 bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-6">Ringkasan belanja</h2>
            <div class="flex justify-between mb-6">
                <span class="font-semibold text-lg">Total</span>
                <span class="font-semibold text-lg" id="total">-</span>
            </div>
            <button class="bg-green-500 text-white font-bold py-3 px-6 rounded w-full text-lg" id="buy-button">Beli</button>
        </div>
    </div>
</body>

</html>