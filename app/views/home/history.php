<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function showDetail(orderId) {
            // Menyembunyikan semua detail order
            const allDetails = document.querySelectorAll('.order-detail');
            allDetails.forEach(detail => {
                detail.style.display = 'none';
            });

            // Menampilkan detail untuk order yang diklik
            const detail = document.getElementById('detail-' + orderId);
            if (detail) {
                detail.style.display = 'block';
            }
        }
    </script>
</head>

<body class="bg-gray-50 font-sans">
    <div class="container mx-auto p-4 max-w-screen-xl flex flex-wrap">
        <!-- Daftar Order di kiri (2/3) -->
        <div class="w-full lg:w-2/3 pr-2 mb-4 lg:mb-0">
            <div class="text-2xl font-bold mb-6">Daftar Order</div>
            <div id="orderHistory">
                <?php foreach ($orderHistory as $order) : ?>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4 bg-white p-6 rounded-xl shadow-lg hover:shadow-2xl cursor-pointer transition-shadow duration-300 ease-in-out hover:bg-gray-50" onclick="showDetail(<?= $order['OrderId'] ?>)">
                        <!-- Bagian Kiri -->
                        <div>
                            <div class="text-lg font-bold text-gray-800 mb-2">Order ID: <?= htmlspecialchars($order['OrderId']) ?></div>
                            <div class="text-xl font-semibold text-gray-900 mb-2">Total: Rp<?= number_format($order['Total'], 0, ',', '.') ?></div>
                            <div class="font-semibold text-blue-600 mt-2">Status: <?= htmlspecialchars($order['Status']) ?></div>
                            <div class="text-sm text-gray-600 mt-2">Created At: <?= date('d-m-Y H:i:s', strtotime($order['CreatedAt'])) ?></div>
                        </div>
                        <!-- Bagian Kanan -->
                        <div>
                            <div class="text-sm text-gray-600 mb-2">Customer ID: <?= htmlspecialchars($order['CustomerId']) ?></div>
                            <div class="text-sm text-gray-600 mb-2">Paid: Rp<?= number_format($order['Paid'], 0, ',', '.') ?></div>
                            <div class="text-sm text-gray-600 mb-2">Change: Rp<?= number_format($order['Change'], 0, ',', '.') ?></div>
                            <div class="text-sm text-gray-600 mb-2">Payment Method: <?= htmlspecialchars($order['PaymentMethod']) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Detail Order di kanan (1/3) -->
        <div class="w-full lg:w-1/3 pl-2">
            <div id="orderDetails">
                <?php foreach ($orderHistory as $order) : ?>
                    <div id="detail-<?= $order['OrderId'] ?>" class="order-detail" style="display: none;">
                        <div class="text-2xl font-bold mb-6">Detail Order ID: <?= htmlspecialchars($order['OrderId']) ?></div>
                        <div class="space-y-4">
                            <?php foreach ($order['items'] as $item) : ?>
                                <div class="flex items-start mb-4">
                                    <img alt="<?= htmlspecialchars($item['MenuName']); ?>" class="w-24 h-24 rounded-lg mr-6" src="<?= BASEURL; ?>/<?= htmlspecialchars($item['ImageUrl'] ?? 'img/user.png'); ?>" />
                                    <div class="flex-grow">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-lg font-semibold"><?= htmlspecialchars($item['MenuName']) ?></span>
                                        </div>
                                        <p class="text-sm text-gray-600 truncate-2-lines"><?= htmlspecialchars($item['Description']) ?></p>
                                        <div class="text-sm text-gray-800 mt-2">
                                            <p>Rp <?= number_format($item['Price'], 0, ',', '.') ?> X <?= htmlspecialchars($item['Quantity']) ?> = Rp <?= number_format($item['Subtotal'], 0, ',', '.') ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <style>
        .truncate-2-lines {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</body>

</html>