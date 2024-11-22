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
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h1 class="text-2xl font-bold">Dashboard</h1>
                            <p class="text-gray-500">Choose The Category</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-4 gap-6 mb-6">
                        <div class="bg-white p-4 rounded-lg shadow">
                            <div class="flex items-center space-x-2 mb-2">
                                <i class="fas fa-dollar-sign text-gray-500"></i>
                                <p class="text-gray-500">Keuntungan</p>
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
                                    <p class="text-gray-500">Total Customer</p>
                                </div>
                                <p class="text-sm text-gray-500"><?= intval($data['customerPercentage']); ?>%</p> 
                            </div>
                            <p class="text-2xl font-bold"><?= $data['totalCustomer']; ?></p> 
                            <div class="h-1 bg-gray-200 rounded-full mt-2">
                                <div class="h-1 rounded-full" style="width: <?= $data['customerPercentage']; ?>%; background-color: <?=
                                                                                                                                    ($data['customerPercentage'] <= 40) ? 'red' : (($data['customerPercentage'] <= 70) ? 'yellow' : 'green'); ?>;">
                                </div> 
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-shopping-bag text-gray-500"></i>
                                    <p class="text-gray-500">Total menu</p>
                                </div>
                                <p class="text-sm text-gray-500"><?= intval($data['menuPercentage']); ?>%</p>
                            </div>
                            <p class="text-2xl font-bold"><?= $data['totalMenu']; ?></p>
                            <div class="h-1 bg-gray-200 rounded-full mt-2">
                                <div class="h-1 rounded-full" style="width: <?= $data['menuPercentage']; ?>%; background-color: <?=
                                                                                                                                ($data['menuPercentage'] <= 40) ? 'red' : (($data['menuPercentage'] <= 70) ? 'yellow' : 'green'); ?>;">
                                </div>
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow">
                            <div class="flex items-center space-x-2 mb-2">
                                <i class="fas fa-box text-gray-500"></i>
                                <p class="text-gray-500">Orders</p>
                            </div>
                            <p class="text-2xl font-bold">765</p>
                            <div class="h-1 bg-gray-200 rounded-full mt-2">
                                <div class="h-1 bg-blue-500 rounded-full w-3/5"></div>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-6 mb-6">
                        <div class="col-span-2 bg-white p-4 rounded-lg shadow">
                            <div class="flex justify-between items-center mb-4">
                                <p class="font-semibold">Sales Analytics</p>
                            </div>
                            <div class="h-80">
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
                    labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
                    datasets: [{
                        label: 'Sales',
                        data: [150, 200, 175, 225, 180, 240, 190],
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgba(54, 162, 235, 1)'
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