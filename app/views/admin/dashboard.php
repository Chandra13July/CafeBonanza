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

                <!-- Order and Profit Chart Section -->
                <div class="flex flex-col lg:flex-row space-y-6 lg:space-y-0 lg:space-x-6">
                    <!-- Chart Section -->
                    <div class="w-full lg:w-full bg-white p-4 rounded-lg shadow">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold text-gray-700">
                                Chart
                            </h3>
                            <div class="flex space-x-2">
                                <select class="border border-gray-300 rounded-md p-2" id="chartType" onchange="updateChart()">
                                    <option value="orders">Orders</option>
                                    <option value="profit">Profit</option>
                                    <option value="status">Status</option> <!-- Opsi untuk Status -->
                                </select>
                                <select class="border border-gray-300 rounded-md p-2" id="chartShape" onchange="updateChartShape()">
                                    <option value="line">Line</option>
                                    <option value="bar">Bar</option>
                                </select>
                            </div>
                        </div>
                        <canvas id="orderChart" style="max-height: 350px;"></canvas>
                        <script>
                            var monthlyOrders = <?php echo json_encode($data['monthlyOrders']); ?>;
                            var monthlyProfit = <?php echo json_encode($data['monthlyCompletedProfit1']); ?>;
                            var monthlyOrdersStatus = <?php echo json_encode($data['monthlyOrdersStatus']); ?>; // Data untuk status
                            var months = <?php echo json_encode($data['months']); ?>;

                            // Data untuk status pesanan
                            var pendingOrders = monthlyOrdersStatus.map(item => item.Pending);
                            var processingOrders = monthlyOrdersStatus.map(item => item.Processing);
                            var completedOrders = monthlyOrdersStatus.map(item => item.Completed);
                            var cancelledOrders = monthlyOrdersStatus.map(item => item.Cancelled);

                            var ctx = document.getElementById('orderChart').getContext('2d');

                            // Default chart (Orders)
                            var chartData = monthlyOrders;
                            var chartLabel = 'Total Orders';

                            // Default Chart Configuration
                            var chartConfig = {
                                type: 'line', // Default to line chart
                                data: {
                                    labels: months,
                                    datasets: [{
                                        label: chartLabel,
                                        data: chartData,
                                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                        borderColor: 'rgba(75, 192, 192, 1)',
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
                            };

                            var orderChart = new Chart(ctx, chartConfig);

                            function updateChart() {
                                var selectedType = document.getElementById('chartType').value;
                              

                                if (selectedType === 'orders') {
                                    chartData = monthlyOrders;
                                    chartLabel = 'Total Orders';
                                    chartConfig.data.datasets = [{
                                        label: chartLabel,
                                        data: chartData,
                                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                        borderColor: 'rgba(75, 192, 192, 1)',
                                        borderWidth: 1
                                    }];
                                } else if (selectedType === 'profit') {
                                    chartData = monthlyProfit;
                                    chartLabel = 'Total Profit';
                                    chartConfig.data.datasets = [{
                                        label: chartLabel,
                                        data: chartData,
                                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                        borderColor: 'rgba(54, 162, 235, 1)',
                                        borderWidth: 1
                                    }];
                                } else if (selectedType === 'status') {
                                    chartData = [pendingOrders, processingOrders, completedOrders, cancelledOrders];
                                    chartLabel = ''; // Tidak ada label tunggal untuk status
                                    chartConfig.data.datasets = [{
                                        label: 'Pending',
                                        data: pendingOrders,
                                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                        borderColor: 'rgba(255, 99, 132, 1)',
                                        borderWidth: 1
                                    }, {
                                        label: 'Processing',
                                        data: processingOrders,
                                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                        borderColor: 'rgba(54, 162, 235, 1)',
                                        borderWidth: 1
                                    }, {
                                        label: 'Completed',
                                        data: completedOrders,
                                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                        borderColor: 'rgba(75, 192, 192, 1)',
                                        borderWidth: 1
                                    }, {
                                        label: 'Cancelled',
                                        data: cancelledOrders,
                                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                                        borderColor: 'rgba(153, 102, 255, 1)',
                                        borderWidth: 1
                                    }];
                                }

                                orderChart.update(); // Memperbarui grafik sesuai dengan pilihan
                            }

                            function updateChartShape() {
                                var selectedShape = document.getElementById('chartShape').value;
                                orderChart.config.type = selectedShape; // Ganti tipe grafik sesuai pilihan
                                orderChart.update(); // Update grafik dengan tipe baru
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>