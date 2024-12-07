<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>History</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    </link>
    <script>
        function showDetail(orderId) {
            const allDetails = document.querySelectorAll('.order-detail');
            allDetails.forEach(detail => detail.style.display = 'none');

            const detail = document.getElementById('detail-' + orderId);
            if (detail) {
                detail.style.display = 'block';
            }
        }
    </script>
</head>

<body class="bg-gray-50 font-sans">
    <div class="container mx-auto p-4 max-w-screen-xl flex flex-wrap">
        <!-- Daftar Order -->
        <div class="w-full lg:w-2/3 pr-2 mb-4 lg:mb-0 h-[80vh] overflow-y-auto custom-scroll">
            <div class="text-2xl font-bold mb-6 text-gray-900">Order History</div>
            <div id="orderHistory">
                <?php foreach ($orderHistory as $order) : ?>
                    <div class="max-w-2xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4 bg-white px-4 py-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 hover:bg-gray-50 relative ml-2">
                        <!-- CreatedAt di pojok kanan atas -->
                        <div class="absolute top-0 right-0 text-sm text-gray-600 mt-2 mr-4">
                            <span><?= date('d M Y', strtotime($order['CreatedAt'])) ?></span>
                        </div>
                        <div class="cursor-pointer" onclick="showDetail(<?= $order['OrderId'] ?>)">
                            <div class="flex items-center mb-2">
                                <span class="text-lg font-semibold text-black">Order ID: <?= htmlspecialchars($order['OrderId']) ?></span>
                            </div>
                            <div class="flex items-center mb-2">
                                <span class="font-medium text-black">Total: Rp<?= number_format($order['Total'], 0, ',', '.') ?></span>
                            </div>
                            <div class="flex items-center mb-2">
                                <span class="font-medium text-black">Payment: <?= htmlspecialchars($order['PaymentMethod']) ?></span>
                            </div>
                        </div>
                        <div class="flex items-center mb-3 relative">
                            <div class="flex justify-between w-full relative z-10 pointer-events-none">
                                <?php
                                // Array status dengan indeks masing-masing
                                $statuses = [
                                    'Pending' => 0,
                                    'Processing' => 1,
                                    'Completed' => 2,
                                    'Cancelled' => 3,
                                ];

                                // Menentukan status saat ini berdasarkan index status
                                $currentStatus = $order['Status'];
                                $currentStatusIndex = $statuses[$currentStatus]; // Mendapatkan index status saat ini

                                // Iterasi melalui semua status yang ada
                                foreach ($statuses as $status => $index) :
                                    // Menandai status yang aktif dan yang sudah dilalui
                                    $isActive = $index <= $currentStatusIndex;

                                    // Tentukan warna background dan ikon untuk status ini
                                    $bgColor = match ($status) {
                                        'Pending' => 'bg-yellow-500',
                                        'Processing' => 'bg-blue-500',
                                        'Completed' => 'bg-green-500',
                                        'Cancelled' => 'bg-red-500',
                                        default => 'bg-gray-300',
                                    };
                                    $icon = match ($status) {
                                        'Pending' => 'fa-hourglass-start',
                                        'Processing' => 'fa-cogs',
                                        'Completed' => 'fa-check',
                                        'Cancelled' => 'fa-times',
                                        default => '',
                                    };
                                ?>
                                    <div class="flex flex-col items-center w-1/4 group">
                                        <div class="w-8 h-8 rounded-full <?= $bgColor ?> border-4 border-white flex items-center justify-center <?= $isActive ? '' : 'opacity-50' ?> group-hover:scale-110 transition-transform duration-200">
                                            <i class="fas <?= $icon ?> text-white"></i>
                                        </div>
                                        <span class="text-xs text-gray-600 mt-1 group-hover:text-black"><?= $status ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Detail Order -->
        <div class="w-full lg:w-1/3 pl-2 h-[80vh] overflow-y-auto custom-scroll">
            <div id="orderDetails">
                <?php foreach ($orderHistory as $order) : ?>
                    <div id="detail-<?= $order['OrderId'] ?>" class="order-detail mb-6 p-4 bg-white rounded-lg shadow-lg" style="display: none;">
                        <div class="text-2xl font-bold mb-6 border-b pb-4">Order Details</div>
                        <div class="space-y-6">
                            <?php foreach ($order['items'] as $item) : ?>
                                <div class="flex items-start mb-4 p-4 bg-gray-50 rounded-lg shadow-sm">
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

        .custom-scroll::-webkit-scrollbar {
            display: none;
        }

        .custom-scroll {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</body>

</html>