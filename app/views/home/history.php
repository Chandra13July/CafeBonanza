<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>History</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    </link>
</head>

<body class="bg-gray-50 font-sans">
    <div class="container mx-auto p-6 max-w-screen-xl flex flex-wrap">
        <!-- Daftar Order -->
        <div class="w-full lg:w-2/3 pr-4 mb-8 lg:mb-0 h-[80vh] overflow-y-auto custom-scroll">
            <div class="text-3xl font-semibold mb-6 text-gray-900">Order History</div>
            <div id="orderHistory">
                <?php foreach ($orderHistory as $order) : ?>
                    <div class="max-w-2xl mx-auto grid grid-cols-1 lg:grid-cols-1 gap-6 mb-6 bg-white px-6 py-8 rounded-xl shadow-lg transition-shadow duration-300 relative ml-2">
                        <div>
                            <!-- Menampilkan Order ID dan CreatedAt -->
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-xl font-semibold text-black">Order ID: <?= htmlspecialchars($order['OrderId']) ?></span>
                                <span class="text-sm text-gray-500"><?= date('d M Y, H:i', strtotime($order['CreatedAt'])) ?></span>
                            </div>
                        </div>

                        <!-- Menampilkan status di bawah CreatedAt, di sebelah kanan -->
                        <div class="flex justify-end w-full mb-3">
                            <div class="grid grid-cols-4 gap-4">
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

                                // Iterasi melalui status untuk menampilkan semua status
                                foreach ($statuses as $status => $index) :
                                    // Tentukan warna background dan ikon untuk status ini
                                    $bgColor = match ($status) {
                                        'Pending' => 'bg-yellow-400',
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
                                    
                                    // Status yang telah tercapai atau sedang aktif akan terang, lainnya redup
                                    $activeClass = $index <= $currentStatusIndex ? 'scale-110' : 'opacity-50'; 
                                ?>
                                    <div class="flex flex-col items-center <?= $activeClass ?>">
                                        <div class="w-10 h-10 rounded-full <?= $bgColor ?> border-4 border-white flex items-center justify-center">
                                            <i class="fas <?= $icon ?> text-white"></i>
                                        </div>
                                        <span class="text-xs text-gray-600 mt-2"><?= $status ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <style>
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
