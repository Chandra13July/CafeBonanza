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
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

                <!-- Order Chart and Popular Menu Section -->
                <div class="flex flex-col lg:flex-row space-y-6 lg:space-y-0 lg:space-x-6">
                    <!-- Order Chart Section -->
                    <div class="w-full lg:w-2/3 bg-white p-4 rounded-lg shadow">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold text-gray-700">
                                Chart
                            </h3>
                            <div class="flex space-x-2">
                                <select class="border border-gray-300 rounded-md p-2" id="chartType" onchange="updateChart()">
                                    <option value="orders">
                                        Orders
                                    </option>
                                    <option value="profit">
                                        Profit
                                    </option>
                                </select>
                                <select class="border border-gray-300 rounded-md p-2" id="chartShape" onchange="updateChartShape()">
                                    <option value="line">
                                        Line
                                    </option>
                                    <option value="bar">
                                        Bar
                                    </option>
                                    <option value="pie">
                                        Pie
                                    </option>
                                </select>
                                <select class="border border-gray-300 rounded-md p-2" id="chartRange" onchange="updateChartRange()">
                                    <option value="1-6">
                                        January - June
                                    </option>
                                    <option value="7-12">
                                        July - December
                                    </option>
                                </select>
                            </div>
                        </div>
                        <canvas id="orderChart">
                        </canvas>
                        <script>
                            var ctx = document.getElementById('orderChart').getContext('2d');
                            var chartData = {
                                orders: {
                                    '2022': [100, 120, 130, 140, 150, 160, 170, 180, 190, 200, 210, 220],
                                    '2023': [130, 140, 150, 160, 170, 180, 190, 200, 210, 220, 230, 240]
                                },
                                profit: {
                                    '2022': [100, 500, 1000, 1500, 2000, 2500, 3000, 3500, 4000, 4500, 5000, 5500],
                                    '2023': [200, 600, 1100, 1600, 2100, 2600, 3100, 3600, 4100, 4600, 5100, 5600]
                                }
                            };
                            var orderChart = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: ['January', 'February', 'March', 'April', 'May', 'June'],
                                    datasets: [{
                                        label: 'Orders',
                                        data: chartData.orders['2022'].slice(0, 6),
                                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                        borderColor: 'rgba(255, 99, 132, 1)',
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });

                            function updateChart() {
                                var selectedType = document.getElementById('chartType').value;
                                var selectedRange = document.getElementById('chartRange').value;
                                var range = selectedRange.split('-').map(Number);
                                orderChart.data.datasets[0].data = chartData[selectedType]['2022'].slice(range[0] - 1, range[1]);
                                orderChart.data.datasets[0].label = selectedType.charAt(0).toUpperCase() + selectedType.slice(1);
                                orderChart.update();
                            }

                            function updateChartShape() {
                                var selectedShape = document.getElementById('chartShape').value;
                                var selectedType = document.getElementById('chartType').value;
                                var selectedRange = document.getElementById('chartRange').value;
                                var range = selectedRange.split('-').map(Number);
                                orderChart.destroy();
                                orderChart = new Chart(ctx, {
                                    type: selectedShape,
                                    data: {
                                        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'].slice(range[0] - 1, range[1]),
                                        datasets: [{
                                            label: selectedType.charAt(0).toUpperCase() + selectedType.slice(1),
                                            data: chartData[selectedType]['2022'].slice(range[0] - 1, range[1]),
                                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                            borderColor: 'rgba(255, 99, 132, 1)',
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        scales: {
                                            y: {
                                                beginAtZero: true
                                            }
                                        }
                                    }
                                });
                            }

                            function updateChartRange() {
                                updateChart();
                                updateChartShape();
                            }
                        </script>
                    </div>
                    <!-- Popular Menu Section -->
                    <div class="w-full lg:w-1/3 bg-white p-6 rounded-lg shadow-lg">
                        <h3 class="text-xl font-semibold text-gray-700 mb-6">
                            Popular Menu
                        </h3>
                        <ul>
                            <!-- Example Menu Item -->
                            <li class="flex items-center justify-between text-gray-600 mb-4">
                                <!-- Menu Item Image -->
                                <img alt="A delicious looking dish with vibrant colors and garnishes" class="w-12 h-12 rounded-full mr-4" height="100" src="https://storage.googleapis.com/a1aa/image/QLzeXzpybyVcIaZ6O4Pz9fMmyqVP5ivNdGlVEkAVU8bxKb3TA.jpg" width="100" />
                                <!-- Menu Item Name and Orders -->
                                <div class="flex-1">
                                    <span class="font-medium">
                                        Menu Item 1
                                    </span>
                                </div>
                                <span class="font-semibold text-gray-800">
                                    200 Orders
                                </span>
                            </li>
                            <!-- Example Menu Item -->
                            <li class="flex items-center justify-between text-gray-600 mb-4">
                                <!-- Menu Item Image -->
                                <img alt="A crispy and golden-brown dish with a side of dipping sauce" class="w-12 h-12 rounded-full mr-4" height="100" src="https://storage.googleapis.com/a1aa/image/71MAX9pCiZ52I5EwfHf7963ScAE5wmEt9JaX7XUi5Ie9V2unA.jpg" width="100" />
                                <!-- Menu Item Name and Orders -->
                                <div class="flex-1">
                                    <span class="font-medium">
                                        Menu Item 2
                                    </span>
                                </div>
                                <span class="font-semibold text-gray-800">
                                    150 Orders
                                </span>
                            </li>
                            <!-- Example Menu Item -->
                            <li class="flex items-center justify-between text-gray-600 mb-4">
                                <!-- Menu Item Image -->
                                <img alt="A flavorful dish with a mix of spices and herbs" class="w-12 h-12 rounded-full mr-4" height="100" src="https://storage.googleapis.com/a1aa/image/Cy57PXVBYAYiFNVsCNE2pZKxaMQQCaqI3w8uzRk1WWkry29E.jpg" width="100" />
                                <!-- Menu Item Name and Orders -->
                                <div class="flex-1">
                                    <span class="font-medium">
                                        Menu Item 3
                                    </span>
                                </div>
                                <span class="font-semibold text-gray-800">
                                    120 Orders
                                </span>
                            </li>
                            <!-- Example Menu Item -->
                            <li class="flex items-center justify-between text-gray-600 mb-4">
                                <!-- Menu Item Image -->
                                <img alt="A beautifully plated dish with fresh ingredients" class="w-12 h-12 rounded-full mr-4" height="100" src="https://storage.googleapis.com/a1aa/image/1I6Z5v6vTGoUMBRWVKDF6iL5qLtsaeCW7S4kVCVqwqsfKb3TA.jpg" width="100" />
                                <!-- Menu Item Name and Orders -->
                                <div class="flex-1">
                                    <span class="font-medium">
                                        Menu Item 4
                                    </span>
                                </div>
                                <span class="font-semibold text-gray-800">
                                    100 Orders
                                </span>
                            </li>
                            <!-- Example Menu Item -->
                            <li class="flex items-center justify-between text-gray-600 mb-4">
                                <!-- Menu Item Image -->
                                <img alt="A savory dish with a rich and creamy sauce" class="w-12 h-12 rounded-full mr-4" height="100" src="https://storage.googleapis.com/a1aa/image/efxfWueptucCnR1aVWk5nvchcrl2XSgfXXkgWweWFmdeXlt7JA.jpg" width="100" />
                                <!-- Menu Item Name and Orders -->
                                <div class="flex-1">
                                    <span class="font-medium">
                                        Menu Item 5
                                    </span>
                                </div>
                                <span class="font-semibold text-gray-800">
                                    90 Orders
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>