<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Shopping Cart</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <style>
    * {
      box-sizing: border-box;
    }

    html,
    body {
      height: 100%;
      margin: 0;
      padding: 0;
      overflow-x: hidden;
    }

    body {
      font-family: 'Roboto', sans-serif;
      background-color: white;
      width: 100%;
    }

    .cart-item {
      display: grid;
      grid-template-columns: 2fr 1fr 1fr 1fr;
      align-items: center;
      gap: 1rem;
      padding: 0.5rem;
      border-bottom: 1px solid #e0e0e0;
    }

    .cart-item img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border: 1px solid #ccc;
      border-radius: 8px;
    }

    .cart-item .product-info {
      display: flex;
      align-items: center;
      gap: 1rem;
      text-align: left;
    }

    .cart-item .product-info h3 {
      font-size: 1rem;
      font-weight: bold;
    }

    .cart-item .product-info p {
      font-size: 0.875rem;
      color: #6b7280;
    }

    .cart-item .quantity-controls {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .cart-item .quantity-controls button {
      width: 30px;
      height: 30px;
      background-color: #f1f1f1;
      border: 1px solid #ccc;
      display: inline-flex;
      justify-content: center;
      align-items: center;
      cursor: pointer;
      font-size: 1rem;
    }

    .cart-item .quantity-controls span {
      width: 40px;
      text-align: center;
    }

    .cart-item .price,
    .cart-item .subtotal {
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 1rem;
    }

    .container {
      max-width: 100%;
      margin: 0 auto;
      padding: 2rem;
    }

    .total-section {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      align-items: center;
      padding-top: 2rem;
    }

    .total-section select,
    .total-section button {
      width: 200px;
      padding: 1rem;
      margin-bottom: 1rem;
      font-size: 1rem;
      text-align: center;
    }

    .total-section .total {
      font-size: 1.25rem;
      font-weight: bold;
    }

    .cart-items {
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
    }

    .form-section {
      display: flex;
      justify-content: space-between;
      gap: 2rem;
      margin-bottom: 2rem;
    }

    .form-section .form-left {
      width: 65%;
      margin-left: 6rem;
    }

    .form-section .form-right {
      width: 28%;
    }

    .form-left,
    .form-right {
      padding: 1.5rem;
      border-radius: 8px;
      border: 1px solid #e0e0e0;
    }

    /* Style untuk checkbox */
    .cart-checkbox {
      margin-right: 1rem;
    }

    /* Style untuk checkbox "Check All" */
    .check-all-container {
      display: flex;
      justify-content: flex-end;
      margin-top: 1rem;
    }

    .check-all-container label {
      font-size: 1rem;
      font-weight: bold;
      cursor: pointer;
    }

    /* Update untuk title Shopping Cart */
    .shopping-cart-title {
      font-size: 1.75rem;
      font-weight: bold;
      margin-bottom: 1.5rem;
    }

    /* Styling table header */
    .cart-table-header {
      display: grid;
      grid-template-columns: 2fr 1fr 1fr 1fr;
      font-weight: bold;
      padding: 1rem;
      border-bottom: 2px solid #ccc;
      background-color: white;
      align-items: center;
      text-align: center;
    }

    .cart-table-header div {
      padding: 0.5rem;
    }
  </style>
</head>

<body class="bg-white">
  <header class="bg-black text-white text-center py-6">
    <h1 class="text-2xl font-bold">Shopping Cart</h1>
  </header>

  <main class="container mt-10 bg-white p-8 rounded-lg shadow-lg">
    <div class="form-section">
      <div class="form-left">
        <h3 class="shopping-cart-title">Shopping Cart</h3>
        <div class="cart-table-header">
          <div>Nama Produk</div>
          <div>Jumlah</div>
          <div>Harga</div>
          <div>Subtotal</div>
        </div>
        <div class="cart-items">
          <div id="cartItems">
            <?php
            $user_id = $_SESSION['user_id'];
            $cartModel = new CartModel();
            $cartItems = $cartModel->getCart($user_id);

            foreach ($cartItems as $item):
            ?>
              <div class="cart-item" data-id="<?php echo $item['CartId']; ?>">
                <div class="product-info">
                  <input type="checkbox" class="cart-checkbox" id="checkbox-<?php echo $item['CartId']; ?>" data-id="<?php echo $item['CartId']; ?>">
                  <img src="<?= BASEURL; ?>/<?= htmlspecialchars($item['ImageUrl'] ?? '/img/user.png'); ?>"
                    alt="<?= htmlspecialchars($item['MenuName'] ?? 'Produk Tidak Diketahui'); ?>" />
                  <div>
                    <h3><?php echo $item['MenuName']; ?></h3>
                    <p class="text-sm text-gray-500">Stok: <?php echo $item['Stock']; ?></p>
                  </div>
                </div>
                <div class="quantity-controls">
                  <button onclick="changeQuantity(<?php echo $item['CartId']; ?>, 'decrease')">-</button>
                  <span id="quantity-<?php echo $item['CartId']; ?>"><?php echo $item['Quantity']; ?></span>
                  <button onclick="changeQuantity(<?php echo $item['CartId']; ?>, 'increase')">+</button>
                </div>
                <div class="price">
                  <span>Rp</span>
                  <span id="price-<?php echo $item['CartId']; ?>">
                    <?php echo number_format($item['Price'], 0, ',', '.'); ?>
                  </span>
                </div>
                <div class="subtotal">
                  <span>Rp</span>
                  <span id="formatted-subtotal-<?php echo $item['CartId']; ?>">
                    <?php echo number_format($item['TotalPrice'], 0, ',', '.'); ?>
                  </span>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          <div class="check-all-container">
            <label>
              <input type="checkbox" id="checkAll" class="mr-2"> Semua
            </label>
          </div>
        </div>
      </div>

      <div class="form-right">
        <h3>Data Transaksi</h3>
        <div class="transaction-details">
          <select class="border px-6 py-3 rounded-lg mb-4">
            <option>Metode Pembayaran</option>
            <option>Transfer Bank</option>
            <option>OVO</option>
            <option>Dana</option>
          </select>
          <button class="bg-black text-white px-8 py-3 rounded-lg text-xl">Check Out</button>
          <div class="total">
            Total:
            <span id="totalAmount"><?php echo number_format(array_sum(array_column($cartItems, 'TotalPrice')), 0, ',', '.'); ?></span>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script>
    function updateCart() {
      let totalAmount = 0;
      const items = document.querySelectorAll('.cart-item');

      items.forEach(item => {
        const checkbox = item.querySelector('.cart-checkbox');
        if (checkbox.checked) { // Hanya hitung jika checkbox dicentang
          const id = item.dataset.id;
          const quantity = parseInt(document.getElementById(`quantity-${id}`).textContent);
          const price = parseInt(document.getElementById(`price-${id}`).textContent.replace(/\./g, '').trim());
          const subtotal = quantity * price;

          // Format subtotal agar tetap konsisten
          document.getElementById(`formatted-subtotal-${id}`).textContent = new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
          }).format(subtotal);

          totalAmount += subtotal;
        }
      });

      // Update totalAmount dengan format
      document.getElementById('totalAmount').textContent = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
      }).format(totalAmount);
    }

    function changeQuantity(id, action) {
      const quantityElement = document.getElementById(`quantity-${id}`);
      const stockElement = document.querySelector(`.cart-item[data-id="${id}"] .product-info p`);
      const stock = parseInt(stockElement.textContent.replace(/\D/g, '')); // Ambil stok dari teks

      let quantity = parseInt(quantityElement.textContent);

      if (action === 'increase' && quantity < stock) {
        quantity++;
      } else if (action === 'decrease' && quantity > 1) {
        quantity--;
      } else if (action === 'increase' && quantity >= stock) {
        alert('Jumlah tidak boleh melebihi stok.');
      }

      quantityElement.textContent = quantity;
      updateCart();
    }

    document.getElementById('checkAll').addEventListener('change', function(e) {
      const checkboxes = document.querySelectorAll('.cart-checkbox');
      checkboxes.forEach(checkbox => {
        checkbox.checked = e.target.checked;
      });
      updateCart(); // Update total saat "Check All" berubah
    });

    // Tambahkan event listener untuk setiap checkbox individu
    document.querySelectorAll('.cart-checkbox').forEach(checkbox => {
      checkbox.addEventListener('change', updateCart);
    });

    updateCart(); // Initial calculation when page loads
  </script>

</body>

</html>