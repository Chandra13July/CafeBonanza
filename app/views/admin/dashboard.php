<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            padding-left: 250px;
            padding-right: 10px;
            /* Adding slight padding to avoid any overflow issues */
        }
    </style>
</head>

<body class="bg-gradient-to-r from-pink-100 to-yellow-100 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-full">
        <div class="flex">
            <!-- Main Content -->
            <div class="w-full pl-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold">Dashboard</h1>
                        <p class="text-gray-500">Choose The Category</p>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-6 mb-6">
                    <div class="bg-white p-4 rounded-lg shadow">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-dollar-sign text-gray-500"></i>
                                <p class="text-gray-500">Keuntungan</p>
                            </div>
                            <p class="text-red-500">-2.33%</p>
                        </div>
                        <p class="text-2xl font-bold">Rp 21,375</p>
                        <div class="h-1 bg-gray-200 rounded-full mt-2">
                            <div class="h-1 bg-red-500 rounded-full w-1/2"></div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-user text-gray-500"></i>
                                <p class="text-gray-500">Customer</p>
                            </div>
                            <p class="text-green-500">+32.40%</p>
                        </div>
                        <p class="text-2xl font-bold">1,012</p>
                        <div class="h-1 bg-gray-200 rounded-full mt-2">
                            <div class="h-1 bg-green-500 rounded-full w-3/4"></div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-shopping-bag text-gray-500"></i>
                                <p class="text-gray-500">Total Penjualan</p>
                            </div>
                            <p class="text-green-500">+25%</p>
                        </div>
                        <p class="text-2xl font-bold">24,254</p>
                        <div class="h-1 bg-gray-200 rounded-full mt-2">
                            <div class="h-1 bg-green-500 rounded-full w-2/3"></div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-6">
                    <div class="col-span-2 bg-white p-4 rounded-lg shadow">
                        <div class="flex justify-between items-center mb-4">
                            <p class="font-semibold">Sales Analytics</p>
                        </div>
                        <div class="h-60"> <!-- Adjusted height for graph area -->
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <div class="flex justify-between items-center mb-4">
                            <p class="font-semibold">Menu Terlaris</p>
                        </div>
                        <ul class="space-y-4">
                            <li class="flex justify-between items-center">
                                <div class="flex items-center space-x-2">
                                    <img alt="Cappuccino image" class="w-10 h-10 rounded-full" src="https://storage.googleapis.com/a1aa/image/9x2lK5eRbPTxP6BheyiH8WnLu0eVl0b17aOW8nUGfQBqRDedC.jpg" />
                                    <div>
                                        <p class="font-semibold">Cappuccino</p>
                                        <p class="text-gray-500">$85.00</p>
                                    </div>
                                </div>
                                <p class="font-semibold">240</p>
                            </li>
                            <!-- Additional items... -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['09:00 AM', '12:00 PM', '04:00 PM', '08:00 PM', '12:00 AM'],
                datasets: [{
                    label: 'Sales',
                    data: [50, 100, 150, 200, 250],
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(255, 99, 132, 1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>