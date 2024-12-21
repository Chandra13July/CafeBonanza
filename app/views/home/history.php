<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Order History</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .message-text {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .details {
            overflow: hidden;
        }

        .grid>div {
            overflow: hidden;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans">

    <div class="container mx-auto p-6 md:p-8 max-w-screen-xl">
        <div class="text-3xl font-semibold mb-6 sm:mb-8 text-gray-900 text-center">Order History</div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6 sm:gap-8">
            <?php foreach ($orderHistory as $order) : ?>
                <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-2xl transition-shadow duration-300 ease-in-out cursor-pointer" onclick="toggleDetails(<?= $order['OrderId'] ?>)">
                    <div class="relative">
                        <?php
                        $statuses = [
                            'Pending' => 0,
                            'Processing' => 1,
                            'Completed' => 2,
                            'Cancelled' => 3,
                        ];

                        $currentStatus = $order['Status'];
                        $message = match ($currentStatus) {
                            'Pending' => 'Silakan lakukan pembayaran kepada admin untuk melanjutkan proses pesanan.',
                            'Processing' => 'Pesanan Anda sedang diproses, harap tunggu sebentar.',
                            'Completed' => 'Pesanan Anda telah berhasil diterima oleh customer.',
                            'Cancelled' => 'Pesanan Anda telah dibatalkan.',
                            default => '',
                        };

                        $bgColor = match ($currentStatus) {
                            'Pending' => 'bg-yellow-400',
                            'Processing' => 'bg-blue-500',
                            'Completed' => 'bg-green-500',
                            'Cancelled' => 'bg-red-500',
                            default => 'bg-gray-300',
                        };
                        $icon = match ($currentStatus) {
                            'Pending' => 'fa-hourglass-start',
                            'Processing' => 'fa-cogs',
                            'Completed' => 'fa-check',
                            'Cancelled' => 'fa-times',
                            default => '',
                        };
                        ?>
                        <div class="flex justify-between items-center mb-4">
                            <p class="text-lg sm:text-xl font-semibold text-gray-800">Order ID: <?= htmlspecialchars($order['OrderId']) ?></p>
                            <span class="text-sm text-gray-500"><?= date('d M Y, H:i', strtotime($order['CreatedAt'])) ?></span>
                        </div>
                        <div class="flex items-center mt-4">
                            <div class="w-8 h-8 rounded-full <?= $bgColor ?> flex items-center justify-center text-white text-sm mr-4">
                                <i class="fas <?= $icon ?>"></i>
                            </div>
                            <p class="message-text text-gray-600 italic text-sm sm:text-base">"<?= htmlspecialchars($message) ?>"</p>
                        </div>
                        <div class="hidden mt-4 details" id="details-<?= $order['OrderId'] ?>">
                            <hr class="my-2">
                            <h3 class="text-lg font-semibold mb-4">Detail Pesanan:</h3>
                            <div class="space-y-4">
                                <?php foreach ($order['items'] as $item) : ?>
                                    <div class="flex items-center space-x-4">
                                        <img src="<?= BASEURL; ?>/<?= htmlspecialchars($item['ImageUrl']) ?>" alt="<?= htmlspecialchars($item['MenuName']) ?>" class="w-16 h-16 object-cover rounded-lg">
                                        <div class="flex-1">
                                            <p class="text-gray-800 font-medium text-lg"><?= htmlspecialchars($item['MenuName']) ?></p>
                                            <p class="text-gray-600 text-sm"><?= htmlspecialchars($item['Description']) ?></p>
                                            <p class="text-gray-600">Rp<?= number_format($item['Price'], 0, ',', '.') ?> x <?= $item['Quantity'] ?> = Rp<?= number_format($item['Subtotal'], 0, ',', '.') ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function toggleDetails(orderId) {
            const details = document.getElementById('details-' + orderId);
            details.classList.toggle('hidden');
        }
    </script>
</body>

</html>