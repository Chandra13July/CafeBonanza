<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Shopping Cart</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <style>
    .description {
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: normal;
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

    /* Styling untuk bagian Order Summary */
    .order-summary {
      background-color: #f9fafb;
      padding: 1.5rem;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .order-summary h3 {
      font-size: 1.5rem;
      font-weight: bold;
      text-align: center;
      margin-bottom: 1.5rem;
      border-bottom: 2px solid #ddd;
      padding-bottom: 0.5rem;
    }

    .order-summary .total {
      font-size: 1.25rem;
      font-weight: bold;
      margin-top: 1rem;
      text-align: center;
    }

    .order-summary select,
    .order-summary button {
      width: 100%;
      padding: 1rem;
      margin-bottom: 1rem;
      font-size: 1rem;
      text-align: center;
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
                  <input type="checkbox" class="cart-checkbox" id="checkbox-<?php echo $item['CartId']; ?>" data-id="<?php echo $item['CartId']; ?>" onchange="updateTotalPrice()">
                  <img src="<?= BASEURL; ?>/<?= htmlspecialchars($item['ImageUrl'] ?? '/img/user.png'); ?>"
                    alt="<?= htmlspecialchars($item['MenuName'] ?? 'Produk Tidak Diketahui'); ?>" />
                  <div>
                    <h3><?php echo $item['MenuName']; ?></h3>
                    <p class="description text-sm text-gray-500"><?php echo $item['Description']; ?></p>
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
              <input type="checkbox" id="checkAll" class="mr-2" onchange="checkAll()"> Semua
            </label>
          </div>
        </div>
      </div>

      <div class="form-right">
        <div class="order-summary">
          <h3>Order Summary</h3>
          <div class="transaction-details">
            <select class="rounded-md p-2">
              <option value="pickUp">Ambil di Tempat</option>
              <option value="delivery">Pengiriman</option>
            </select>
            <div class="total">
              <p>Total Harga</p>
              <p>Rp <span id="totalPrice">0</span></p>
            </div>
            <button class="bg-black text-white py-3 rounded-md">Lanjutkan ke Pembayaran</button>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script>
    function checkAll() {
      const isChecked = document.getElementById('checkAll').checked;
      document.querySelectorAll('.cart-checkbox').forEach(checkbox => {
        checkbox.checked = isChecked;
      });
      updateTotalPrice();
    }

    function changeQuantity(cartId, action) {
      const quantityElement = document.getElementById('quantity-' + cartId);
      let quantity = parseInt(quantityElement.innerText);
      if (action === 'increase') {
        quantity++;
      } else if (action === 'decrease' && quantity > 1) {
        quantity--;
      }
      quantityElement.innerText = quantity;

      updateSubtotal(cartId, quantity);
      updateTotalPrice();
    }

    function updateSubtotal(cartId, quantity) {
      const price = parseInt(document.getElementById('price-' + cartId).innerText.replace(/[^0-9]/g, ''));
      const subtotal = price * quantity;
      document.getElementById('formatted-subtotal-' + cartId).innerText = subtotal.toLocaleString();
    }

    function updateTotalPrice() {
      let total = 0;
      document.querySelectorAll('.cart-item').forEach(item => {
        const cartId = item.getAttribute('data-id');
        const checkbox = document.getElementById('checkbox-' + cartId);
        if (checkbox.checked) {
          const quantity = parseInt(document.getElementById('quantity-' + cartId).innerText);
          const price = parseInt(document.getElementById('price-' + cartId).innerText.replace(/[^0-9]/g, ''));
          total += price * quantity;
        }
      });
      document.getElementById('totalPrice').innerText = total.toLocaleString();
    }
  </script>
</body>

</html>