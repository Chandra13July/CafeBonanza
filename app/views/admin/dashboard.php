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
                    <!-- Profit Card -->
                    <div class="bg-white p-4 rounded-lg shadow">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-dollar-sign text-gray-500"></i>
                                <p class="text-gray-500">This Month's Profit</p>
                            </div>
                            <p class="text-sm text-gray-500"><?= min(intval($data['profitPercentage']), 100); ?>%</p>
                        </div>
                        <p class="text-2xl font-bold">Rp <?= number_format($data['currentMonthProfit'], 0, ',', '.'); ?></p>
                        <div class="h-1 bg-gray-200 rounded-full mt-2">
                            <div class="h-1 rounded-full" style="width: <?= min($data['profitPercentage'], 100); ?>%; background-color: <?= ($data['profitPercentage'] <= 40) ? 'red' : (($data['profitPercentage'] <= 70) ? 'yellow' : 'green'); ?>;"></div>
                        </div>
                        <p class="text-xs mt-1 flex items-center">
                            <?php
                            if ($data['profitPercentage'] == 0) {
                                echo '<i class="fas fa-frown text-red-500 mr-1"></i>Still far from the target!';
                            } elseif ($data['profitPercentage'] <= 40) {
                                echo '<i class="fas fa-exclamation-triangle text-yellow-500 mr-1"></i>Needs more effort!';
                            } elseif ($data['profitPercentage'] <= 70) {
                                echo '<i class="fas fa-thumbs-up text-yellow-500 mr-1"></i>Good progress!';
                            } elseif ($data['profitPercentage'] < 100) {
                                echo '<i class="fas fa-smile text-green-500 mr-1"></i>Almost there, keep it up!';
                            } else {
                                echo '<i class="fas fa-check-circle text-green-500 mr-1"></i>Target achieved!';
                            }
                            ?>
                        </p>
                    </div>

                    <!-- Customer Card -->
                    <div class="bg-white p-4 rounded-lg shadow">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-user text-gray-500"></i>
                                <p class="text-gray-500">Customers</p>
                            </div>
                            <p class="text-sm text-gray-500"><?= min(intval($data['customerPercentage']), 100); ?>%</p>
                        </div>
                        <p class="text-2xl font-bold"><?= $data['totalCustomer']; ?></p>
                        <div class="h-1 bg-gray-200 rounded-full mt-2">
                            <div class="h-1 rounded-full" style="width: <?= min($data['customerPercentage'], 100); ?>%; background-color: <?= ($data['customerPercentage'] <= 40) ? 'red' : (($data['customerPercentage'] <= 70) ? 'yellow' : 'green'); ?>;"></div>
                        </div>
                        <p class="text-xs mt-1 flex items-center">
                            <?php
                            if ($data['customerPercentage'] == 0) {
                                echo '<i class="fas fa-frown text-red-500 mr-1"></i>No customers yet!';
                            } elseif ($data['customerPercentage'] <= 40) {
                                echo '<i class="fas fa-exclamation-triangle text-yellow-500 mr-1"></i>Needs more customers!';
                            } elseif ($data['customerPercentage'] <= 70) {
                                echo '<i class="fas fa-thumbs-up text-yellow-500 mr-1"></i>Customer growth is good!';
                            } elseif ($data['customerPercentage'] < 100) {
                                echo '<i class="fas fa-smile text-green-500 mr-1"></i>Almost there, keep attracting customers!';
                            } else {
                                echo '<i class="fas fa-check-circle text-green-500 mr-1"></i>Customer target achieved!';
                            }
                            ?>
                        </p>
                    </div>

                    <!-- Menu Card -->
                    <div class="bg-white p-4 rounded-lg shadow">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-shopping-bag text-gray-500"></i>
                                <p class="text-gray-500">Menu</p>
                            </div>
                            <p class="text-sm text-gray-500"><?= min(intval($data['menuPercentage']), 100); ?>%</p>
                        </div>
                        <p class="text-2xl font-bold"><?= $data['totalMenu']; ?></p>
                        <div class="h-1 bg-gray-200 rounded-full mt-2">
                            <div class="h-1 rounded-full" style="width: <?= min($data['menuPercentage'], 100); ?>%; background-color: <?= ($data['menuPercentage'] <= 40) ? 'red' : (($data['menuPercentage'] <= 70) ? 'yellow' : 'green'); ?>;"></div>
                        </div>
                        <p class="text-xs mt-1 flex items-center">
                            <?php
                            if ($data['menuPercentage'] == 0) {
                                echo '<i class="fas fa-frown text-red-500 mr-1"></i>No menu sold yet!';
                            } elseif ($data['menuPercentage'] <= 40) {
                                echo '<i class="fas fa-exclamation-triangle text-yellow-500 mr-1"></i>Menu needs more attention!';
                            } elseif ($data['menuPercentage'] <= 70) {
                                echo '<i class="fas fa-thumbs-up text-yellow-500 mr-1"></i>Menu is gaining popularity!';
                            } elseif ($data['menuPercentage'] < 100) {
                                echo '<i class="fas fa-smile text-green-500 mr-1"></i>Almost there, keep introducing new menu!';
                            } else {
                                echo '<i class="fas fa-check-circle text-green-500 mr-1"></i>Menu target achieved!';
                            }
                            ?>
                        </p>
                    </div>

                    <!-- Orders Card -->
                    <div class="bg-white p-4 rounded-lg shadow">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-box text-gray-500"></i>
                                <p class="text-gray-500">Orders</p>
                            </div>
                            <p class="text-sm text-gray-500"><?= min(intval($data['orderPercentage']), 100); ?>%</p>
                        </div>
                        <p class="text-2xl font-bold"><?= $data['totalOrders']; ?></p>
                        <div class="h-1 bg-gray-200 rounded-full mt-2">
                            <div class="h-1 rounded-full" style="width: <?= min($data['orderPercentage'], 100); ?>%; background-color: <?= ($data['orderPercentage'] <= 40) ? 'red' : (($data['orderPercentage'] <= 70) ? 'yellow' : 'green'); ?>;"></div>
                        </div>
                        <p class="text-xs mt-1 flex items-center">
                            <?php
                            if ($data['orderPercentage'] == 0) {
                                echo '<i class="fas fa-frown text-red-500 mr-1"></i>No orders yet!';
                            } elseif ($data['orderPercentage'] <= 40) {
                                echo '<i class="fas fa-exclamation-triangle text-yellow-500 mr-1"></i>Needs more orders!';
                            } elseif ($data['orderPercentage'] <= 70) {
                                echo '<i class="fas fa-thumbs-up text-yellow-500 mr-1"></i>Orders are increasing!';
                            } elseif ($data['orderPercentage'] < 100) {
                                echo '<i class="fas fa-smile text-green-500 mr-1"></i>Almost there, keep increasing orders!';
                            } else {
                                echo '<i class="fas fa-check-circle text-green-500 mr-1"></i>Order target achieved!';
                            }
                            ?>
                        </p>
                    </div>
                </div>

                <!-- Order and Profit Chart Section -->
                <div class="flex flex-col lg:flex-row space-y-6 lg:space-y-0 lg:space-x-6">
                    <!-- Chart Section -->
                    <div class="w-full lg:w-full bg-white p-4 rounded-lg shadow">
                        <div class="flex justify-between items-center mb-4">
                            <h3 id="chartTitle" class="text-xl font-semibold text-gray-700">
                                Order Chart
                            </h3>
                            <div class="flex items-center space-x-2">
                                <select class="border border-gray-300 rounded-md p-2 text-sm" id="chartType" onchange="updateChart()">
                                    <option value="orders">Orders</option>
                                    <option value="profit">Profit</option>
                                </select>
                            </div>
                        </div>
                        <canvas id="orderChart" style="max-height: 350px;"></canvas>
                        <script>
                            var monthlyOrders = <?php echo json_encode($data['monthlyOrders']); ?>;
                            var monthlyProfit = <?php echo json_encode($data['monthlyCompletedProfit1']); ?>;
                            var months = <?php echo json_encode($data['months']); ?>;

                            var ctx = document.getElementById('orderChart').getContext('2d');

                            // Default chart (Orders)
                            var chartData = monthlyOrders;
                            var chartLabel = 'Total Orders';

                            // Default Chart Configuration (with enhanced animations and interactions)
                            var chartConfig = {
                                type: 'line', // Default to line chart
                                data: {
                                    labels: months,
                                    datasets: [{
                                        label: chartLabel,
                                        data: chartData,
                                        backgroundColor: 'rgba(75, 192, 192, 0.2)', // Color di bawah garis
                                        borderColor: 'rgba(75, 192, 192, 1)', // Garis chart
                                        borderWidth: 2,
                                        pointRadius: 6,
                                        pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                                        pointBorderColor: '#fff',
                                        pointBorderWidth: 3,
                                        pointHoverRadius: 8,
                                        pointHoverBackgroundColor: '#fff',
                                        pointHoverBorderColor: 'rgba(75, 192, 192, 1)',
                                        pointHoverBorderWidth: 4,
                                        fill: true // Mengisi area di bawah garis
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    animation: {
                                        duration: 2500, // Durasi animasi lebih panjang
                                        easing: 'easeInOutQuad', // Easing yang lebih halus
                                        onComplete: function() {
                                            // Optional: Tambahkan efek setelah animasi selesai
                                            console.log('Animation Complete');
                                        },
                                        animateScale: true, // Efek animasi skala
                                        animateRotate: true, // Efek animasi rotasi
                                        onProgress: function(animation) {
                                            // Efek selama animasi berlangsung (misalnya, menggambar garis atau titik)
                                            console.log('Animation Progress: ' + animation.currentStep / animation.numSteps);
                                        }
                                    },
                                    hover: {
                                        mode: 'nearest',
                                        intersect: false,
                                        animationDuration: 500 // Durasi animasi saat hover
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            grid: {
                                                drawBorder: false,
                                                drawOnChartArea: true,
                                                color: 'rgba(0,0,0,0.05)', // Subtle grid lines
                                                lineWidth: 1
                                            },
                                            ticks: {
                                                fontColor: 'rgba(0,0,0,0.7)', // Tick font color
                                                fontSize: 12
                                            }
                                        },
                                        x: {
                                            grid: {
                                                drawOnChartArea: false // No grid lines on x-axis
                                            },
                                            ticks: {
                                                fontColor: 'rgba(0,0,0,0.7)', // Tick font color
                                                fontSize: 12
                                            }
                                        }
                                    },
                                    plugins: {
                                        tooltip: {
                                            enabled: true,
                                            mode: 'index',
                                            intersect: false,
                                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                            titleColor: '#fff',
                                            bodyColor: '#fff',
                                            bodyFont: {
                                                size: 14,
                                                weight: 'bold'
                                            },
                                            displayColors: false, // Disable color display in tooltip
                                            padding: 10, // Padding inside tooltip
                                            caretSize: 6, // Size of the tooltip caret
                                            borderColor: 'rgba(0, 0, 0, 0.2)', // Border color for tooltip
                                            borderWidth: 1
                                        },
                                        legend: {
                                            display: true,
                                            position: 'top',
                                            labels: {
                                                fontColor: '#333',
                                                fontSize: 14,
                                                fontStyle: 'bold'
                                            }
                                        }
                                    }
                                }
                            };

                            var orderChart = new Chart(ctx, chartConfig);

                            function updateChart() {
                                var selectedType = document.getElementById('chartType').value;
                                var chartTitle = document.getElementById('chartTitle');

                                if (selectedType === 'orders') {
                                    chartData = monthlyOrders;
                                    chartLabel = 'Total Orders';
                                    chartTitle.textContent = 'Order Chart'; // Ganti judul menjadi Order Chart
                                    chartConfig.data.datasets = [{
                                        label: chartLabel,
                                        data: chartData,
                                        backgroundColor: 'rgba(75, 192, 192, 0.2)', // Color di bawah garis
                                        borderColor: 'rgba(75, 192, 192, 1)', // Garis chart
                                        borderWidth: 2,
                                        pointRadius: 6,
                                        pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                                        pointBorderColor: '#fff',
                                        pointBorderWidth: 3,
                                        pointHoverRadius: 8,
                                        pointHoverBackgroundColor: '#fff',
                                        pointHoverBorderColor: 'rgba(75, 192, 192, 1)',
                                        pointHoverBorderWidth: 4,
                                        fill: true // Mengisi area di bawah garis
                                    }];
                                } else if (selectedType === 'profit') {
                                    chartData = monthlyProfit;
                                    chartLabel = 'Total Profit';
                                    chartTitle.textContent = 'Profit Chart'; // Ganti judul menjadi Profit Chart
                                    chartConfig.data.datasets = [{
                                        label: chartLabel,
                                        data: chartData,
                                        backgroundColor: 'rgba(54, 162, 235, 0.2)', // Color di bawah garis
                                        borderColor: 'rgba(54, 162, 235, 1)', // Garis chart
                                        borderWidth: 2,
                                        pointRadius: 6,
                                        pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                                        pointBorderColor: '#fff',
                                        pointBorderWidth: 3,
                                        pointHoverRadius: 8,
                                        pointHoverBackgroundColor: '#fff',
                                        pointHoverBorderColor: 'rgba(54, 162, 235, 1)',
                                        pointHoverBorderWidth: 4,
                                        fill: true // Mengisi area di bawah garis
                                    }];
                                }

                                orderChart.update(); // Update chart based on selection
                            }
                        </script>
                    </div>
                </div>

                <!-- Menu Popular Section -->
                <div class="flex flex-col lg:flex-row space-y-6 lg:space-y-0 lg:space-x-6 mt-8">
                    <!-- Grafik Section (3/4) -->
                    <div class="w-full lg:w-3/4 bg-white p-6 rounded-lg shadow-lg">
                        <div class="flex justify-between items-center mb-4">
                            <h3 id="chartTitle" class="text-xl font-semibold text-gray-700">
                                Weekly Crowd Chart
                            </h3>
                            <div class="flex items-center space-x-2">
                                <select class="border border-gray-300 rounded-md p-2 text-sm" id="chartType" onchange="updateChart()">
                                    <option value="weekly">Weekly Crowd Chart</option>
                                    <option value="hourly">Hourly Traffic Graph</option>
                                </select>
                            </div>
                        </div>
                        <div id="grafik" class="h-64 bg-gray-200 rounded-lg"></div>
                    </div>

                    <!-- Popular Menu Section (1/4) -->
                    <div class="w-full lg:w-2/6 bg-white p-6 rounded-lg shadow-lg ml-4">
                        <h3 class="text-xl font-semibold text-gray-700 flex justify-between mb-4">
                            <span id="menuTitle">Top Menu</span>
                            <select class="border border-gray-300 rounded-md p-2 text-sm" id="menuType" onchange="updateMenuList()">
                                <option value="best-menu">Top Menu</option>
                                <option value="best-customer">Loyal Customer</option>
                                <option value="stock-out">Stock Status</option>
                            </select>
                        </h3>

                        <div id="menuList" class="space-y-4">
                            <!-- Best Menu items -->
                            <?php foreach ($popularMenu as $menu) : ?>
                                <div class="flex items-center space-x-4 menu-item" data-type="best-menu">
                                    <div class="flex space-x-4 w-full">
                                        <img src="<?= BASEURL; ?>/<?= htmlspecialchars($menu['ImageUrl']) ?>" alt="<?= htmlspecialchars($menu['MenuName']) ?>" class="w-10 h-10 object-cover mx-auto rounded-full">
                                        <div class="flex-1 space-y-1">
                                            <h4 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($menu['MenuName']) ?></h4>
                                            <p class="text-sm text-gray-600"><?= htmlspecialchars($menu['Description']) ?></p> <!-- Menambahkan Description di bawah MenuName -->
                                            <div class="flex justify-between items-center text-sm mt-1">
                                                <p class="text-gray-700 font-semibold">Sold: <?= htmlspecialchars($menu['totalQuantity']) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <!-- Best Customer items -->
                            <div id="bestCustomerItems" style="display: none;" class="space-y-4 mt-4"> <!-- Added mt-4 for margin-top -->
                                <?php foreach ($popularCustomer as $customer) : ?>
                                    <div class="flex items-center space-x-4 menu-item" data-type="best-customer">
                                        <div class="flex space-x-4 w-full">
                                            <img src="<?= BASEURL; ?>/<?= !empty($customer['ImageUrl']) ? htmlspecialchars($customer['ImageUrl']) : 'img/user.png' ?>" alt="<?= htmlspecialchars($customer['Username']) ?>" class="w-10 h-10 object-cover mx-auto rounded-full">
                                            <div class="flex-1 space-y-1">
                                                <h4 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($customer['Username']) ?></h4>
                                                <p class="text-sm text-gray-600"><?= htmlspecialchars($customer['Email']) ?></p> <!-- Added Email below username -->
                                                <div class="flex justify-between items-center text-sm mt-1">
                                                    <p class="text-gray-700 font-semibold">Orders: <?= htmlspecialchars($customer['totalOrders']) ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Stock Menu items -->
                            <?php foreach ($stockStatusMenu as $menu) : ?>
                                <div class="flex items-center space-x-4 menu-item" data-type="stock-out">
                                    <div class="flex space-x-4 w-full">
                                        <img src="<?= BASEURL; ?>/<?= htmlspecialchars($menu['ImageUrl']) ?>" alt="<?= htmlspecialchars($menu['MenuName']) ?>" class="w-10 h-10 object-cover mx-auto rounded-full">
                                        <div class="flex-1 space-y-1">
                                            <h4 class="text-lg font-semibold text-gray-800"><?= htmlspecialchars($menu['MenuName']) ?></h4>
                                            <p class="text-sm text-gray-600"><?= htmlspecialchars($menu['Description']) ?></p> <!-- Added Description below MenuName -->
                                            <div class="flex justify-between items-center text-sm mt-1">
                                                <?php if ($menu['Stock'] == 0): ?>
                                                    <p class="text-red-500 font-semibold">Stok Habis</p>
                                                <?php elseif ($menu['Stock'] <= 5): ?>
                                                    <p class="text-yellow-500 font-semibold">Stok Hampir Habis (Tersisa <?= htmlspecialchars($menu['Stock']) ?>)</p>
                                                <?php else: ?>
                                                    <p class="text-gray-700 font-semibold">Stok: <?= htmlspecialchars($menu['Stock']) ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            // Pastikan data stockStatusMenu sudah diterima dengan benar
                            console.log('Data Stock Status Menu:', <?= json_encode($stockStatusMenu); ?>);

                            updateMenuList(); // This will ensure that the correct items are displayed initially
                        });

                        function updateMenuList() {
                            const selectedType = document.getElementById('menuType').value;
                            const menuTitle = document.getElementById('menuTitle');
                            const menuItems = document.querySelectorAll('.menu-item');
                            const bestCustomerItems = document.getElementById('bestCustomerItems');
                            const stockItems = document.querySelectorAll('.stock-out'); // Select stock-out items

                            console.log("Selected Type:", selectedType); // Cek apa yang dipilih di dropdown

                            // Update the title based on selection
                            if (selectedType === 'best-menu') {
                                menuTitle.textContent = 'Top Menu';
                                bestCustomerItems.style.display = 'none'; // Hide Best Customer items
                                stockItems.forEach(item => item.style.display = 'none');
                            } else if (selectedType === 'best-customer') {
                                menuTitle.textContent = 'Loyal Customer';
                                bestCustomerItems.style.display = 'block'; // Show Best Customer items
                                stockItems.forEach(item => item.style.display = 'none');
                            } else if (selectedType === 'stock-out') {
                                menuTitle.textContent = 'Stock Status';
                                bestCustomerItems.style.display = 'block'; // Hide Best Customer items
                                stockItems.forEach(item => item.style.display = 'flex'); // Show Stock Habits items
                            }

                            // Filter menu items based on the selected type
                            menuItems.forEach(item => {
                                if (selectedType === 'best-menu' && item.dataset.type === 'best-menu') {
                                    item.style.display = 'flex'; // Show Best Menu items
                                } else if (selectedType === 'best-customer' && item.dataset.type === 'best-customer') {
                                    item.style.display = 'flex'; // Show Best Customer items
                                } else if (selectedType === 'stock-out' && item.dataset.type === 'stock-out') {
                                    item.style.display = 'flex'; // Show Stock Habits items
                                } else {
                                    item.style.display = 'none'; // Hide other items
                                }
                            });
                        }

                        // Initialize the display on page load (to show only best-menu initially)
                        document.addEventListener('DOMContentLoaded', () => {
                            updateMenuList(); // This will ensure that the correct items are displayed initially
                        });
                    </script>

                </div>
            </div>
</body>

</html>