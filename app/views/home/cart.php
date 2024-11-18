<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Shopping Cart</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <style>
    body {
      font-family: 'Roboto', sans-serif;
    }
    .cart-item {
      display: grid;
      grid-template-columns: 2fr 1fr 1fr 1fr;
      gap: 1rem;
      align-items: center;
      padding: 1rem 0;
      border-bottom: 1px solid #e0e0e0;
    }
    .cart-item img {
      max-width: 100%;
      height: auto;
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
    }
    .cart-item .quantity-controls span {
      width: 30px;
      text-align: center;
    }
    .cart-item .col-price,
    .cart-item .col-subtotal {
      text-align: center;
    }
    .cart-item .col-product {
      display: flex;
      align-items: center;
    }
    .cart-item .col-product img {
      max-width: 50px;
      max-height: 50px;
    }
  </style>
</head>
<body class="bg-white">
  <header class="bg-black text-white text-center py-4">
    <h1 class="text-lg">Shopping Cart</h1>
  </header>

  <main class="max-w-4xl mx-auto mt-8 bg-white p-6 rounded-lg shadow-lg">
    <div class="grid grid-cols-5 gap-4 text-center font-semibold border-b py-2">
      <div class="col-span-2">Product</div>
      <div>Jumlah</div>
      <div>Harga(Rp)</div>
      <div>Subtotal(Rp)</div>
    </div>

    <div class="space-y-4" id="cartItems">
      <?php 
        // Fetch the cart items for the logged-in user
        $customerId = $_SESSION['customerId']; // Assuming session management for logged-in user
        $cartModel = new CartModel();
        $cartItems = $cartModel->getCart($customerId); 

        foreach ($cartItems as $item): 
      ?>
        <div class="cart-item" data-id="<?php echo $item['CartId']; ?>">
          <div class="col-product flex items-center">
            <img src="https://storage.googleapis.com/a1aa/image/uxeaCkhBWp0DCq6sV8d3V1jZvCXwxsFj6y9delXYfxEPxfCPB.jpg" alt="Coffe kuning" class="w-16 h-16 rounded-lg"/>
            <div class="ml-4 text-left">
              <h3 class="text-lg font-semibold"><?php echo $item['MenuName']; ?></h3>
              <p class="text-sm text-gray-500">Cocok untuk memulai hari</p>
            </div>
          </div>
          <div class="text-center quantity-controls">
            <button onclick="changeQuantity(<?php echo $item['CartId']; ?>, 'decrease')">-</button>
            <span id="quantity-<?php echo $item['CartId']; ?>"><?php echo $item['Quantity']; ?></span>
            <button onclick="changeQuantity(<?php echo $item['CartId']; ?>, 'increase')">+</button>
          </div>
          <div class="col-price text-center" id="price-<?php echo $item['CartId']; ?>"><?php echo number_format($item['Price']); ?></div>
          <div class="col-subtotal text-center" id="subtotal-<?php echo $item['CartId']; ?>"><?php echo number_format($item['TotalPrice']); ?></div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center mt-8 space-y-4 md:space-y-0 md:space-x-4">
      <select class="border px-4 py-2">
        <option>Metode Pembayaran</option>
      </select>
      <button class="bg-black text-white px-6 py-2">Check out</button>
      <div class="border px-4 py-2" id="totalAmount">Total: <?php echo number_format(array_sum(array_column($cartItems, 'TotalPrice'))); ?></div>
    </div>
  </main>

  <script>
    // Function to update the subtotal and total amount
    function updateCart() {
      let totalAmount = 0;
      const items = document.querySelectorAll('.cart-item');

      items.forEach(item => {
        const id = item.getAttribute('data-id');
        const quantity = parseInt(document.getElementById(`quantity-${id}`).textContent);
        const price = parseInt(document.getElementById(`price-${id}`).textContent.replace('.', ''));

        const subtotal = price * quantity;
        document.getElementById(`subtotal-${id}`).textContent = subtotal.toLocaleString();
        totalAmount += subtotal;
      });

      document.getElementById('totalAmount').textContent = `Total: ${totalAmount.toLocaleString()}`;
    }

    // Function to handle quantity change
    function changeQuantity(id, action) {
      const quantityElement = document.getElementById(`quantity-${id}`);
      let quantity = parseInt(quantityElement.textContent);

      if (action === 'increase') {
        quantity += 1;
      } else if (action === 'decrease' && quantity > 1) {
        quantity -= 1;
      }

      quantityElement.textContent = quantity;
      updateCart();
    }

    // Initialize the cart with the current values
    updateCart();
  </script>
</body>
</html>
