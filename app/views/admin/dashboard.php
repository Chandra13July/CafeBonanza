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

        .single-line-description {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            /* Membatasi menjadi satu baris */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            width: 100%;
            /* Pastikan elemen memiliki lebar penuh untuk bekerja dengan baik */
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
                    <div class="w-full lg:w-2/3 bg-white p-6 rounded-lg shadow-lg">
                        <div class="flex justify-between items-center mb-4">
                            <h3 id="chartTitle1" class="text-xl font-semibold text-gray-700">
                                Crowd Chart Daily
                            </h3>

                            <!-- Dropdown untuk memilih tipe grafik (Per Hari, Per Bulan per Minggu) -->
                            <select id="chartSelector" class="border border-gray-300 rounded-md p-2 text-sm" onchange="updateChart1()">
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>
                        <div id="grafik" class="bg-gray-200 rounded-lg" style="height: 400px;">
                            <canvas id="chartCanvas"></canvas>
                        </div>
                        <!-- Menyimpan data JSON dalam elemen HTML tersembunyi -->
                        <div id="monthlyOrdersData" style="display: none;"
                            data-orders='<?php echo json_encode($monthlyOrdersByWeek); ?>'>
                        </div>
                    </div>

                    <!-- Include Chart.js library -->
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

                    <style>
                        #chartCanvas {
                            background-color: white;
                        }
                    </style>

                    <script>
                        let chartInstance;

                        // Data yang diterima dari controller
                        const weeklyData = <?php echo json_encode($weeklyOrders); ?>;
                        const monthlyDataByWeek = JSON.parse(document.getElementById('monthlyOrdersData').getAttribute('data-orders'));

                        // Data untuk grafik mingguan
                        const weeklyChartData = {
                            labels: <?php echo json_encode($daysOfWeek); ?>,
                            datasets: [{
                                label: 'Jumlah Pesanan',
                                data: weeklyData.map(day => day.totalOrders),
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        };

                        // Data untuk grafik per bulan per minggu
                        const monthlyChartData = {
                            labels: monthlyDataByWeek.map(week => `Minggu ${week.weekInMonth}`),
                            datasets: [{
                                label: 'Jumlah Pesanan',
                                data: monthlyDataByWeek.map(week => week.totalOrders),
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        };

                        // Fungsi untuk menentukan status keramaian
                        function getStatusMessage(totalPesanan) {
                            if (totalPesanan >= 6) {
                                return "Sangat Ramai 🚀";
                            } else if (totalPesanan >= 4) {
                                return "Cukup Ramai 😊";
                            } else if (totalPesanan >= 2) {
                                return "Tidak Terlalu Ramai 😌";
                            } else if (totalPesanan >= 1) {
                                return "Hampir Sepi 😶";
                            } else {
                                return "Sepi 😔";
                            }
                        }

                        // Konfigurasi chart
                        const chartOptions = {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            },
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            const totalPesanan = tooltipItem.raw;
                                            const status = getStatusMessage(totalPesanan);
                                            return `Pesanan: ${totalPesanan} - ${status}`;
                                        }
                                    }
                                },
                                legend: {
                                    labels: {
                                        color: 'black'
                                    }
                                }
                            }
                        };

                        // Fungsi untuk membuat chart
                        function createChart1(chartData) {
                            const ctx = document.getElementById('chartCanvas').getContext('2d');

                            // Hancurkan grafik yang ada jika sudah ada sebelumnya
                            if (chartInstance) {
                                chartInstance.destroy();
                            }

                            // Buat grafik baru
                            chartInstance = new Chart(ctx, {
                                type: 'bar',
                                data: chartData,
                                options: chartOptions
                            });
                        }

                        // Fungsi untuk memperbarui grafik berdasarkan tipe yang dipilih
                        function updateChart1() {
                            const selectedChartType = document.getElementById('chartSelector').value;

                            // Update chart title berdasarkan tipe grafik yang dipilih
                            const chartTitle = document.getElementById('chartTitle1');
                            if (selectedChartType === 'weekly') {
                                createChart1(weeklyChartData); // Grafik Mingguan
                                chartTitle.innerText = "Crowd Chart Daily";
                            } else if (selectedChartType === 'monthly') {
                                createChart1(monthlyChartData); // Grafik Per Bulan per Minggu
                                chartTitle.innerText = "Crowd Chart Monthly";
                            }
                        }

                        // Membuat chart pertama kali (default: per hari)
                        document.addEventListener('DOMContentLoaded', function() {
                            document.getElementById('chartSelector').value = 'weekly';
                            updateChart1(); // Menampilkan grafik sesuai pilihan saat halaman dimuat
                        });

                        // Simpan pilihan chart ke localStorage saat dropdown berubah
                        document.getElementById('chartSelector').addEventListener('change', function() {
                            localStorage.setItem('selectedChartType', this.value);
                        });
                    </script>

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
                                            <p class="text-sm text-gray-600 single-line-description">
                                                <?= htmlspecialchars(substr($menu['Description'], 0, 40)) ?><?php if (strlen($menu['Description']) > 40) echo '...'; ?>
                                            </p> <!-- Menambahkan batasan 40 karakter dengan ellipsis jika lebih -->
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
                                            <p class="text-sm text-gray-600 single-line-description">
                                                <?= htmlspecialchars(substr($menu['Description'], 0, 40)) ?><?php if (strlen($menu['Description']) > 40) echo '...'; ?>
                                            </p> <!-- Menambahkan batasan 40 karakter dengan elipsis jika lebih -->
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

                <!-- Card Section (1 & 2 combined, Card 3 on the right) -->
                <div class="flex space-x-6 mt-8">
                    <div class="w-full lg:w-2/3 bg-white p-6 rounded-lg shadow-md transition-shadow hover:shadow-xl">
                        <!-- Card 1 -->
                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-4">
                                <h4 id="chartTitle2" class="text-xl font-semibold text-gray-800">Donut Chart Category</h4>
                                <select id="chartSelector1" class="border border-gray-300 rounded-md p-2 text-sm focus:ring focus:ring-blue-300" onchange="updatePieChart()">
                                    <option value="category">Category</option>
                                    <option value="status">Status</option>
                                    <option value="payment">Payment Method</option>
                                </select>
                            </div>
                            <!-- Donut Chart -->
                            <canvas id="donutChart" width="300" height="300" class="mx-auto"></canvas>
                        </div>
                    </div>

                    <!-- Include Chart.js -->
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    <script>
                        // Data dari PHP untuk kategori, status, dan metode pembayaran
                        const categoryChartData = {
                            labels: <?php echo json_encode($donutChartCategoryOrder['categories'] ?? []); ?>,
                            datasets: [{
                                label: 'Jumlah Pesanan',
                                data: <?php echo json_encode($donutChartCategoryOrder['totalSold'] ?? []); ?>,
                                backgroundColor: ['#FF5733', '#3498DB'],
                                borderColor: ['#FF5733', '#3498DB'],
                                borderWidth: 1
                            }]
                        };

                        const statusChartData = {
                            labels: <?php echo json_encode($donutChartStatusData['status'] ?? []); ?>,
                            datasets: [{
                                label: 'Jumlah Pesanan',
                                data: <?php echo json_encode($donutChartStatusData['statusData'] ?? []); ?>,
                                backgroundColor: ['#e74c3c', '#f1c40f', '#2ecc71', '#95a5a6'],
                                borderColor: ['#e74c3c', '#f1c40f', '#2ecc71', '#95a5a6'],
                                borderWidth: 1
                            }]
                        };

                        const paymentChartData = {
                            labels: <?php echo json_encode($donutChartPaymentMethod['paymentMethods'] ?? []); ?>,
                            datasets: [{
                                label: 'Jumlah Pesanan',
                                data: <?php echo json_encode($donutChartPaymentMethod['paymentData'] ?? []); ?>,
                                backgroundColor: ['#8e44ad', '#27ae60'],
                                borderColor: ['#8e44ad', '#27ae60'],
                                borderWidth: 1
                            }]
                        };

                        // Debugging Data
                        console.log("Categories:", categoryChartData.labels);
                        console.log("Total Sold:", categoryChartData.datasets[0].data);
                        console.log("Status:", statusChartData.labels);
                        console.log("Status Data:", statusChartData.datasets[0].data);
                        console.log("Payment Methods:", paymentChartData.labels);
                        console.log("Payment Data:", paymentChartData.datasets[0].data);

                        // Inisialisasi chart dengan data kategori
                        var ctx = document.getElementById('donutChart').getContext('2d');
                        var donutChart = new Chart(ctx, {
                            type: 'doughnut',
                            data: categoryChartData,
                            options: {
                                responsive: true,
                                maintainAspectRatio: true,
                                cutout: '60%' // Membuat bentuk donut
                            }
                        });

                        // Fungsi untuk memperbarui chart dan judul
                        function updatePieChart() {
                            var selectedValue = document.getElementById('chartSelector1').value;
                            var chartTitle = document.getElementById('chartTitle2');

                            if (selectedValue === 'category') {
                                donutChart.data = categoryChartData;
                                chartTitle.textContent = "Donut Chart Category";
                            } else if (selectedValue === 'status') {
                                donutChart.data = statusChartData;
                                chartTitle.textContent = "Donut Chart Status Order";
                            } else if (selectedValue === 'payment') {
                                donutChart.data = paymentChartData;
                                chartTitle.textContent = "Donut Chart Payment Method";
                            } else {
                                console.error("Invalid data for chart rendering.");
                            }

                            donutChart.update();
                        }
                    </script>

                    <!-- Kartu Kalender -->
                    <div class="w-full lg:w-2/6 bg-white p-6 rounded-lg shadow-lg">
                        <h3 class="text-xl font-semibold text-gray-700 flex justify-between mb-4">
                            <span>Calendar</span>
                            <select id="monthSelector" class="border border-gray-300 rounded-md p-2 text-sm">
                                <option value="0">January</option>
                                <option value="1">February</option>
                                <option value="2">March</option>
                                <option value="3">April</option>
                                <option value="4">May</option>
                                <option value="5">June</option>
                                <option value="6">July</option>
                                <option value="7">August</option>
                                <option value="8">September</option>
                                <option value="9">October</option>
                                <option value="10">November</option>
                                <option value="11">December</option>
                            </select>
                        </h3>
                        <div id="calendarContainer">
                            <div id="calendarDisplay" class="text-gray-600">Loading date...</div>
                            <div id="holidayMessage" class="mt-4 text-sm"></div>
                        </div>
                    </div>

                    <script>
                        const nationalHolidays = [{
                                date: '2024-01-01',
                                name: 'Tahun Baru 2024 Masehi'
                            },
                            {
                                date: '2024-02-08',
                                name: 'Isra\' Mi\'raj Nabi Muhammad SAW'
                            },
                            {
                                date: '2024-02-09',
                                name: 'Cuti Bersama Imlek 2575 Kongzili'
                            },
                            {
                                date: '2024-02-10',
                                name: 'Tahun Baru Imlek 2575 Kongzili'
                            },
                            {
                                date: '2024-03-11',
                                name: 'Hari Raya Nyepi Tahun Baru Saka 1946'
                            },
                            {
                                date: '2024-03-12',
                                name: 'Cuti Bersama Hari Raya Nyepi'
                            },
                            {
                                date: '2024-03-29',
                                name: 'Wafat Isa Al Masih'
                            },
                            {
                                date: '2024-03-31',
                                name: 'Hari Paskah'
                            },
                            {
                                date: '2024-04-08',
                                name: 'Cuti Bersama Hari Raya Idul Fitri'
                            },
                            {
                                date: '2024-04-10',
                                name: 'Hari Raya Idul Fitri 1445 H'
                            },
                            {
                                date: '2024-04-11',
                                name: 'Hari Raya Idul Fitri 1445 H'
                            },
                            {
                                date: '2024-04-12',
                                name: 'Cuti Bersama Hari Raya Idul Fitri'
                            },
                            {
                                date: '2024-04-15',
                                name: 'Cuti Bersama Hari Raya Idul Fitri'
                            },
                            {
                                date: '2024-05-01',
                                name: 'Hari Buruh Internasional'
                            },
                            {
                                date: '2024-05-09',
                                name: 'Kenaikan Isa Al Masih'
                            },
                            {
                                date: '2024-05-10',
                                name: 'Cuti Bersama Kenaikan Isa Al Masih'
                            },
                            {
                                date: '2024-05-23',
                                name: 'Hari Raya Waisak 2568 BE'
                            },
                            {
                                date: '2024-05-24',
                                name: 'Cuti Bersama Hari Raya Waisak'
                            },
                            {
                                date: '2024-06-01',
                                name: 'Hari Lahir Pancasila'
                            },
                            {
                                date: '2024-06-17',
                                name: 'Hari Raya Idul Adha 1445 H'
                            },
                            {
                                date: '2024-06-18',
                                name: 'Cuti Bersama Hari Raya Idul Adha'
                            },
                            {
                                date: '2024-07-07',
                                name: 'Tahun Baru Islam 1446 H'
                            },
                            {
                                date: '2024-08-17',
                                name: 'Hari Kemerdekaan Republik Indonesia ke 79'
                            },
                            {
                                date: '2024-09-16',
                                name: 'Maulid Nabi Muhammad SAW'
                            },
                            {
                                date: '2024-12-25',
                                name: 'Hari Raya Natal'
                            },
                            {
                                date: '2024-12-26',
                                name: 'Cuti Bersama Hari Raya Natal'
                            }
                        ];

                        const nationalCommemorations = [{
                                date: '2024-01-10',
                                name: 'Hari Kewirausahaan Nasional'
                            },
                            {
                                date: '2024-01-14',
                                name: 'Hari Tata Ruang Nasional'
                            },
                            {
                                date: '2024-02-14',
                                name: 'Hari Valentine'
                            },
                            {
                                date: '2024-02-20',
                                name: 'Hari Pengendalian Inflasi Nasional'
                            },
                            {
                                date: '2024-03-08',
                                name: 'Hari Perempuan Internasional'
                            },
                            {
                                date: '2024-04-02',
                                name: 'Hari Otomotif Nasional'
                            },
                            {
                                date: '2024-04-21',
                                name: 'Hari Kartini'
                            },
                            {
                                date: '2024-05-20',
                                name: 'Hari Kebangkitan Nasional'
                            },
                            {
                                date: '2024-05-21',
                                name: 'Hari Kerja'
                            },
                            {
                                date: '2024-06-1',
                                name: 'Hari Anak Internasional'
                            },
                            {
                                date: '2024-07-30',
                                name: 'Hari Bekas Tentara'
                            },
                            {
                                date: '2024-08-17',
                                name: 'Hari Kemerdekaan Republik Indonesia'
                            },
                            {
                                date: '2024-09-27',
                                name: 'Hari Pariwisata Dunia'
                            },
                            {
                                date: '2024-10-10',
                                name: 'Hari Kesehatan Mental Sedunia'
                            },
                            {
                                date: '2024-10-28',
                                name: 'Hari Sumpah Pemuda'
                            },
                            {
                                date: '2024-11-10',
                                name: 'Hari Pahlawan'
                            },
                            {
                                date: '2024-11-25',
                                name: 'Hari Penghapusan Kekerasan terhadap Perempuan'
                            },
                            {
                                date: '2024-12-22',
                                name: 'Hari Ibu'
                            }
                        ];

                        function displayCalendar(selectedMonth = null, selectedYear = null) {
                            const calendarContainer = document.getElementById('calendarDisplay');
                            const holidayMessageContainer = document.getElementById('holidayMessage');
                            const commemorationMessageContainer = document.getElementById('commemorationMessage');

                            const months = [
                                'January', 'February', 'March', 'April', 'May', 'June',
                                'July', 'August', 'September', 'October', 'November', 'December'
                            ];

                            const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

                            const currentDate = new Date();
                            const year = selectedYear || currentDate.getFullYear();
                            const month = selectedMonth !== null ? selectedMonth : currentDate.getMonth();

                            const firstDay = new Date(year, month).getDay();
                            const lastDate = new Date(year, month + 1, 0).getDate();

                            let calendarHTML = `<h3 class="text-lg font-semibold text-center">${months[month]} ${year}</h3>`;

                            // Header untuk nama hari
                            calendarHTML += '<div class="grid grid-cols-7 gap-2 text-center text-sm font-semibold">';
                            daysOfWeek.forEach(day => {
                                calendarHTML += `<div class="text-gray-700">${day}</div>`;
                            });
                            calendarHTML += '</div>';

                            // Grid kalender
                            calendarHTML += '<div class="grid grid-cols-7 gap-2">';
                            for (let i = 0; i < firstDay; i++) {
                                calendarHTML += '<div></div>';
                            }

                            for (let date = 1; date <= lastDate; date++) {
    const dayOfWeek = (firstDay + date - 1) % 7;
    const isSunday = dayOfWeek === 0;
    const isFriday = dayOfWeek === 5;
    const formattedDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;

    const holiday = nationalHolidays.find(holiday => holiday.date === formattedDate);
    const commemoration = nationalCommemorations.find(day => day.date === formattedDate);

    let textColor = 'text-gray-600';
    let additionalClass = 'font-normal';

    if (isSunday || holiday) {
        textColor = 'text-red-500';
        additionalClass = 'font-bold';
    } else if (isFriday) {
        textColor = 'text-green-500';
        additionalClass = 'font-bold';
    }

    const todayDate = new Date();
    const isToday = todayDate.getFullYear() === year &&
        todayDate.getMonth() === month &&
        todayDate.getDate() === date;

    if (isToday) {
        additionalClass += ' bg-yellow-200';
    }

    additionalClass += ' cursor-pointer date-hover fade-in';

    let marker = '';
    if (holiday) {
        marker = '<span class="inline-block w-2 h-2 rounded-full bg-red-500 mr-2"></span>';
    } else if (commemoration) {
        marker = '<span class="inline-block w-2 h-2 rounded-full bg-green-500 mr-2"></span>';
    }

    calendarHTML += `
        <div class="p-2 text-center flex items-center justify-center ${textColor} ${additionalClass}" 
             title="${holiday ? holiday.name : commemoration ? commemoration.name : ''}" 
             data-date="${formattedDate}">
            ${marker}<span>${date}</span>
        </div>`;

    if (dayOfWeek === 6) {
        calendarHTML += '</div><div class="grid grid-cols-7 gap-2">';
    }
}

                            calendarHTML += '</div>';

                            calendarContainer.innerHTML = calendarHTML;

                            // Menampilkan pesan Hari Libur Nasional
                            const currentMonthHolidays = nationalHolidays.filter(holiday => {
                                const holidayDate = new Date(holiday.date);
                                return holidayDate.getFullYear() === year && holidayDate.getMonth() === month;
                            });

                            if (currentMonthHolidays.length > 0) {
                                holidayMessageContainer.innerHTML = `<h4 class="text-lg font-semibold">Hari Libur Nasional:</h4>` + currentMonthHolidays.map(holiday => {
                                    const holidayDate = new Date(holiday.date);
                                    const date = holidayDate.getDate();
                                    const month = holidayDate.getMonth() + 1;
                                    return `<p class="text-sm">${date} ${months[month - 1]}: ${holiday.name}</p>`;
                                }).join('');
                            } else {
                                holidayMessageContainer.innerHTML = '';
                            }

                            // Menampilkan pesan Hari Peringatan Nasional
                            const currentMonthCommemorations = nationalCommemorations.filter(day => {
                                const commemorationDate = new Date(day.date);
                                return commemorationDate.getFullYear() === year && commemorationDate.getMonth() === month;
                            });

                            if (currentMonthCommemorations.length > 0) {
                                commemorationMessageContainer.innerHTML = `<h4 class="text-lg font-semibold">Hari Peringatan Nasional:</h4>` + currentMonthCommemorations.map(day => {
                                    const commemorationDate = new Date(day.date);
                                    const date = commemorationDate.getDate();
                                    const month = commemorationDate.getMonth() + 1;
                                    return `<p class="text-sm">${date} ${months[month - 1]}: ${day.name}</p>`;
                                }).join('');
                            } else {
                                commemorationMessageContainer.innerHTML = '';
                            }
                        }

                        document.getElementById('monthSelector').addEventListener('change', function() {
                            const selectedMonth = parseInt(this.value, 10);
                            displayCalendar(selectedMonth);
                        });

                        document.addEventListener('DOMContentLoaded', function() {
                            const currentMonth = new Date().getMonth();
                            document.getElementById('monthSelector').value = currentMonth;
                            displayCalendar();
                        });
                    </script>

                </div>
            </div>
</body>

</html>