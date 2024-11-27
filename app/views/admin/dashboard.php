<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            padding-left: 250px;
            padding-right: 10px;
        }
    </style>
</head>

<body class="bg-gradient-to-r from-pink-100 to-yellow-100 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-full">
        <div class="flex">
            <div class="w-full pl-6">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold">Dashboard</h1>
                        <p class="text-gray-500">Choose The Category</p>
                    </div>
                </div>

                <!-- Grid Section -->
                <div class="grid grid-cols-4 gap-6 mb-6">
                    <!-- Keuntungan Card -->
                    <div class="bg-white p-4 rounded-lg shadow">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-dollar-sign text-gray-500"></i>
                                <p class="text-gray-500">This Month's Profit</p>
                            </div>
                            <p class="text-sm text-gray-500"><?= intval($data['profitPercentage']); ?>%</p>
                        </div>
                        <p class="text-2xl font-bold">Rp <?= number_format($data['currentMonthProfit'], 0, ',', '.'); ?></p>
                        <div class="h-1 bg-gray-200 rounded-full mt-2">
                            <div class="h-1 rounded-full" style="width: <?= $data['profitPercentage']; ?>%; background-color: <?= ($data['profitPercentage'] <= 40) ? 'red' : (($data['profitPercentage'] <= 70) ? 'yellow' : 'green'); ?>;"></div>
                        </div>
                    </div>

                    <!-- Total Customer Card -->
                    <div class="bg-white p-4 rounded-lg shadow">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-user text-gray-500"></i>
                                <p class="text-gray-500">Customer</p>
                            </div>
                            <p class="text-sm text-gray-500"><?= intval($data['customerPercentage']); ?>%</p>
                        </div>
                        <p class="text-2xl font-bold"><?= $data['totalCustomer']; ?></p>
                        <div class="h-1 bg-gray-200 rounded-full mt-2">
                            <div class="h-1 rounded-full" style="width: <?= $data['customerPercentage']; ?>%; background-color: <?= ($data['customerPercentage'] <= 40) ? 'red' : (($data['customerPercentage'] <= 70) ? 'yellow' : 'green'); ?>;"></div>
                        </div>
                    </div>

                    <!-- Total Menu Card -->
                    <div class="bg-white p-4 rounded-lg shadow">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-shopping-bag text-gray-500"></i>
                                <p class="text-gray-500">Menu</p>
                            </div>
                            <p class="text-sm text-gray-500"><?= intval($data['menuPercentage']); ?>%</p>
                        </div>
                        <p class="text-2xl font-bold"><?= $data['totalMenu']; ?></p>
                        <div class="h-1 bg-gray-200 rounded-full mt-2">
                            <div class="h-1 rounded-full" style="width: <?= $data['menuPercentage']; ?>%; background-color: <?= ($data['menuPercentage'] <= 40) ? 'red' : (($data['menuPercentage'] <= 70) ? 'yellow' : 'green'); ?>;"></div>
                        </div>
                    </div>

                    <!-- Orders Card -->
                    <div class="bg-white p-4 rounded-lg shadow">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-box text-gray-500"></i>
                                <p class="text-gray-500">Order</p>
                            </div>
                            <p class="text-sm text-gray-500"><?= intval($data['orderPercentage']); ?>%</p>
                        </div>
                        <p class="text-2xl font-bold"><?= $data['totalOrders']; ?></p>
                        <div class="h-1 bg-gray-200 rounded-full mt-2">
                            <div class="h-1 rounded-full" style="width: <?= $data['orderPercentage']; ?>%; background-color: <?= ($data['orderPercentage'] <= 40) ? 'red' : (($data['orderPercentage'] <= 70) ? 'yellow' : 'green'); ?>;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>