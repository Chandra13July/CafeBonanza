<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Shopping Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <script>
        // Menunggu hingga DOM sepenuhnya dimuat sebelum menjalankan script
        document.addEventListener('DOMContentLoaded', function() {
            // Mengambil elemen-elemen penting dari DOM
            const qtyElements = document.querySelectorAll('.qty'); // Elemen jumlah item
            const totalElement = document.getElementById('total'); // Elemen untuk menampilkan total harga
            const buyButton = document.getElementById('buy-button'); // Tombol beli
            const selectAllCheckbox = document.getElementById('select-all'); // Checkbox untuk memilih semua item
            const itemCheckboxes = document.querySelectorAll('.item-checkbox'); // Semua checkbox item
            const selectAllLabel = document.getElementById('select-all-label'); // Label untuk checkbox "Pilih Semua"

            // Fungsi untuk menghitung total kuantitas dan harga
            const updateTotal = () => {
                let totalQty = 0; // Total kuantitas item yang dipilih
                let totalPrice = 0; // Total harga item yang dipilih

                // Mengiterasi semua checkbox item yang tercentang
                document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
                    const itemElement = checkbox.closest('.item'); // Elemen item terkait checkbox
                    const qtyElement = itemElement.querySelector('.qty'); // Elemen kuantitas
                    const priceElement = itemElement.querySelector('.item-price'); // Elemen harga

                    const qty = parseInt(qtyElement.innerText); // Mendapatkan jumlah item
                    const price = parseInt(priceElement.innerText.replace(/[^0-9]/g, '')); // Mendapatkan harga item

                    totalQty += qty; // Menambahkan jumlah ke total kuantitas
                    totalPrice += qty * price; // Menghitung total harga
                });

                // Memperbarui elemen total dan tombol beli
                totalElement.innerText = totalQty > 0 ? 'Rp' + totalPrice.toLocaleString('id-ID') : '-';
                buyButton.innerText = totalQty > 0 ? 'Beli (' + totalQty + ')' : 'Beli';
            };

            // Fungsi untuk memperbarui label "Pilih Semua" dengan jumlah item yang dipilih
            const updateSelectAllLabel = () => {
                const checkedCount = document.querySelectorAll('.item-checkbox:checked').length; // Menghitung jumlah item yang dipilih
                selectAllLabel.innerText = `Pilih Semua (${checkedCount})`;
            };

            // Menangani klik tombol pengurangan kuantitas
            document.querySelectorAll('.decrease').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault(); // Mencegah default action

                    const cartId = this.getAttribute('data-cart-id'); // Mendapatkan ID keranjang
                    const quantityElement = document.querySelector(`#quantity-${cartId}`); // Elemen kuantitas item
                    let quantity = parseInt(quantityElement.value);

                    // Jika jumlah lebih dari 1, kurangi kuantitas
                    if (quantity > 1) {
                        quantity--;
                        quantityElement.value = quantity;

                        const form = this.closest('form'); // Form terkait item
                        form.submit(); // Submit form untuk memperbarui kuantitas di server
                    }
                });
            });

            // Menangani klik tombol penambahan kuantitas
            document.querySelectorAll('.increase').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault(); // Mencegah default action

                    const cartId = this.getAttribute('data-cart-id'); // Mendapatkan ID keranjang
                    const quantityElement = document.querySelector(`#quantity-${cartId}`); // Elemen kuantitas item
                    let quantity = parseInt(quantityElement.value);
                    const stock = parseInt(this.closest('.item').querySelector('.stock').innerText.split(': ')[1]); // Mendapatkan stok item

                    // Jika jumlah kurang dari stok, tambah kuantitas
                    if (quantity < stock) {
                        quantity++;
                        quantityElement.value = quantity;

                        const form = this.closest('form'); // Form terkait item
                        form.submit(); // Submit form untuk memperbarui kuantitas di server
                    }
                });
            });

            // Memperbarui total dan label pada awal script
            updateTotal();

            // Menangani perubahan status checkbox "Pilih Semua"
            selectAllCheckbox.addEventListener('change', function() {
                itemCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked; // Menyetel status semua checkbox item

                    // Menambahkan atau menghapus input tersembunyi untuk item terpilih
                    var value = checkbox.value;
                    const html = `<input type="hidden" name="selected_items[]" value="${value}" id="selected${value}">`;
                    const formDeleteAll = document.querySelector("#deleteAll");
                    const exist = formDeleteAll.querySelector(`#selected${value}`);

                    if (exist) {
                        exist.remove();
                    } else if (checkbox.checked) {
                        formDeleteAll.innerHTML += html;
                    }
                });

                updateTotal(); // Memperbarui total
                updateSelectAllLabel(); // Memperbarui label "Pilih Semua"
            });

            // Menangani perubahan status checkbox item
            itemCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (!checkbox.checked) {
                        selectAllCheckbox.checked = false; // Hapus centang "Pilih Semua" jika salah satu item tidak dipilih
                    } else if (document.querySelectorAll('.item-checkbox:checked').length === itemCheckboxes.length) {
                        selectAllCheckbox.checked = true; // Centang "Pilih Semua" jika semua item dipilih
                    }
                    updateTotal(); // Memperbarui total
                    updateSelectAllLabel(); // Memperbarui label "Pilih Semua"
                });
            });

            // Menangani klik tombol hapus item
            document.querySelectorAll('.delete-item').forEach(button => {
                button.addEventListener('click', () => {
                    const item = button.closest('.item'); // Elemen item terkait
                    item.remove(); // Hapus item dari DOM
                    updateTotal(); // Memperbarui total
                    updateSelectAllLabel(); // Memperbarui label "Pilih Semua"
                });
            });

            // Menangani perubahan checkbox item untuk input tersembunyi
            itemCheckboxes.forEach((e) => {
                e.addEventListener("change", function() {
                    var value = e.value;
                    const html = `<input type="hidden" name="selected_items[]" value="${value}" id="selected${value}">`;
                    const formDeleteAll = document.querySelector("#deleteAll");
                    const exist = formDeleteAll.querySelector(`#selected${value}`);

                    if (exist) {
                        exist.remove();
                    } else if (e.checked) {
                        formDeleteAll.innerHTML += html;
                    }
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

<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto p-6 flex flex-col lg:flex-row">
        <div class="w-full lg:w-3/4 bg-white p-8 rounded-lg shadow-md mb-6 lg:mb-0 lg:mr-6">
            <h1 class="text-3xl font-bold mb-6">Keranjang</h1>
            <div class="border-b pb-6 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input class="mr-2" id="select-all" type="checkbox" />
                        <span id="select-all-label" class="font-semibold text-lg">
                            Pilih Semua (<?= isset($cartItems) ? count($cartItems) : 0 ?>)
                        </span>
                    </div>
                    <form id="deleteAll" action="<?= BASEURL; ?>/cart/deleteSelected" method="POST">
                        <button type="submit" class="text-red-500 cursor-pointer text-lg">Hapus</button>
                    </form>
                </div>
            </div>
            <div id="cartItems">
                <?php
                $user_id = $_SESSION['user_id'];
                $cartModel = new CartModel();
                $cartItems = $cartModel->getCart($user_id);

                foreach ($cartItems as $item) :
                ?>
                    <div class="item flex items-start mb-6" data-cart-id="<?= $item['CartId']; ?>">
                        <input class="mr-2 mt-2 item-checkbox checkbox-all" value="<?= $item['CartId'] ?>" name="checkboxAll[]" type="checkbox" />
                        <div class="flex-grow">
                            <div class="flex items-start">
                                <img alt="<?= htmlspecialchars($item['MenuName']); ?>" class="w-24 h-24 rounded-lg mr-6" src="<?= BASEURL; ?>/<?= htmlspecialchars($item['ImageUrl'] ?? 'img/user.png'); ?>" />
                                <div class="flex-grow">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="ml-2 text-lg"><?= htmlspecialchars($item['MenuName']) ?></span>
                                        <span class="text-xl font-bold text-gray-800 item-price">Rp<?= number_format($item['Price'], 0, ',', '.') ?></span>
                                    </div>
                                    <p class="ml-2 text-gray-600 truncate-2-lines"><?= htmlspecialchars($item['Description']) ?></p>
                                    <div class="flex items-center justify-between mt-2">
                                        <p class="ml-2 text-gray-600 stock">Stok: <?= htmlspecialchars($item['Stock']) ?></p>
                                        <div class="flex items-center">
                                            <form action="<?= BASEURL; ?>/cart/deleteItem/<?= $item['CartId']; ?>" method="POST">
                                                <button type="submit" class="ml-4 text-gray-500 cursor-pointer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            <form action="<?= BASEURL; ?>/cart/updateQuantity" method="POST" class="flex items-center ml-4 border rounded">
                                                <input type="hidden" name="cartId" value="<?= $item['CartId']; ?>" />
                                                <input type="hidden" name="quantity" value="<?= $item['Quantity']; ?>" id="quantity-<?= $item['CartId']; ?>" />
                                                <button type="submit" class="px-3 py-1 text-lg decrease" data-cart-id="<?= $item['CartId']; ?>">-</button>
                                                <span class="px-3 text-lg qty"><?= $item['Quantity'] ?></span>
                                                <button type="submit" class="px-3 py-1 text-lg increase" data-cart-id="<?= $item['CartId']; ?>">+</button>
                                            </form>
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
            <form action="<?= BASEURL; ?>/order/checkout" method="POST">
                <h2 class="text-2xl font-bold mb-6">Ringkasan belanja</h2>
                <div class="flex justify-between mb-6">
                    <span class="font-semibold text-lg">Total</span>
                    <span class="font-semibold text-lg" id="total">-</span>
                </div>
                <div class="mb-6">
                    <label for="payment-method" class="block text-lg font-semibold mb-2">Metode Pembayaran</label>
                    <select id="payment-method" name="payment-method" class="w-full p-3 border rounded-md text-lg">
                        <option value="Cash">Cash</option>
                        <option value="E-Wallet">Dompet Digital</option>
                    </select>
                </div>
                <button class="bg-green-500 text-white font-bold py-3 px-6 rounded w-full text-lg" id="buy-button">Beli</button>
            </form>
        </div>
    </div>
</body>

</html>