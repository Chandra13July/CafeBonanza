<body class="bg-gray-100 flex">
    <?php if (isset($_SESSION['success'])): ?>
        <div id="success-notification" class="bg-green-500 text-white p-2 rounded shadow-lg absolute top-4 right-4 text-sm z-50">
            <?= htmlspecialchars($_SESSION['success']); ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div id="error-notification" class="bg-red-500 text-white p-2 rounded shadow-lg absolute top-4 right-4 text-sm z-50">
            <?= htmlspecialchars($_SESSION['error']); ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="flex-1 ml-64 p-4">
        <div class="bg-white shadow-md rounded-lg overflow-hidden p-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-gray-700">Order Report</h2>
            </div>

            <!-- Filter and Print Form -->
            <div class="mb-4 flex gap-4">
                <form method="POST" action="<?= BASEURL; ?>/report/filterReport" class="flex items-center gap-4">
                    <div>
                        <label for="startDate" class="text-gray-600">Start Date:</label>
                        <input type="date" name="startDate" id="startDate" class="border rounded p-1" required>
                    </div>
                    <div>
                        <label for="endDate" class="text-gray-600">End Date:</label>
                        <input type="date" name="endDate" id="endDate" class="border rounded p-1" required>
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded">Filter</button>
                </form>

                <!-- Tombol Print -->
                <div class="flex items-center">
                    <button id="printButton" class="bg-green-500 text-white px-6 py-2 rounded">Print</button>

                    <!-- Tombol Export ke Excel -->
                    <button id="exportButton" class="bg-yellow-500 text-white px-6 py-2 rounded ml-2">Export to Excel</button>
                </div>
            </div>

            <!-- Report Table -->
            <div class="overflow-x-auto p-4">
                <table id="reportTable" class="min-w-full bg-white table-auto">
                    <thead>
                        <tr class="bg-gray-100 text-left text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-4 font-thin text-center">No</th>
                            <th class="py-3 px-4 font-thin text-center">Customer</th>
                            <th class="py-3 px-4 font-thin text-center">Total</th>
                            <th class="py-3 px-4 font-thin text-center">Paid</th>
                            <th class="py-3 px-4 font-thin text-center">Change</th>
                            <th class="py-3 px-4 font-thin text-center">Payment Method</th>
                            <th class="py-3 px-4 font-thin text-center">Status</th>
                            <th class="py-3 px-4 font-thin text-center">Date</th>
                            <th class="py-3 px-4 font-thin text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['orders'])): ?>
                            <?php foreach ($data['orders'] as $index => $order): ?>
                                <tr class="text-sm text-gray-600 text-center"> <!-- Tambahkan text-center di sini -->
                                    <td class="py-3 px-4"><?= $index + 1 ?></td>
                                    <td class="py-3 px-4"><?= htmlspecialchars($order['Customer'] ?? 'N/A') ?></td>
                                    <td class="py-3 px-4"><?= 'Rp ' . number_format($order['Total'] ?? 0, 0, ',', '.') ?></td>
                                    <td class="py-3 px-4"><?= 'Rp ' . number_format($order['Paid'] ?? 0, 0, ',', '.') ?></td>
                                    <td class="py-3 px-4"><?= 'Rp ' . number_format($order['Change'] ?? 0, 0, ',', '.') ?></td>
                                    <td class="py-3 px-4"><?= htmlspecialchars($order['PaymentMethod'] ?? 'N/A') ?></td>
                                    <td class="py-3 px-4"><?= htmlspecialchars($order['Status'] ?? 'N/A') ?></td>
                                    <td class="py-3 px-4">
                                        <?= !empty($order['CreatedAt']) ? date("d F Y, H:i", strtotime($order['CreatedAt'])) : 'N/A' ?>
                                    </td>
                                    <td class="py-3 px-4">
                                        <!-- Tombol Show dengan Ikon Mata -->
                                        <a href="<?= BASEURL; ?>/report/orderReceipt/<?= $order['OrderId']; ?>"
                                            class="bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-700 transition duration-200 w-14 text-center inline-block">
                                            <i class="fas fa-eye text-base"></i> <!-- Ikon Mata (Show) -->
                                        </a>

                                        <!-- Tombol Edit dengan Ikon Pensil -->
                                        <button class="bg-green-500 text-white px-3 py-1 rounded-md hover:bg-green-700 transition duration-200 ml-2 w-14 text-center inline-block"
                                            onclick="openEditModal(<?= htmlspecialchars(json_encode($order), ENT_QUOTES, 'UTF-8'); ?>)">
                                            <i class="fas fa-pen text-base"></i> <!-- Ikon Pensil (Edit) -->
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="py-3 px-4 text-center text-gray-500">No order data available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
        <script src="//cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>

        <script>
            // Fungsi untuk mencetak laporan
            document.getElementById('printButton').addEventListener('click', function() {
                // Ambil tabel asli
                var originalTable = document.getElementById('reportTable');
                // Salin tabel baru tanpa kolom action
                var tempTable = document.createElement('table');
                tempTable.innerHTML = originalTable.outerHTML;

                // Hapus kolom action (misalnya kolom terakhir)
                var rows = tempTable.querySelectorAll('tr');
                rows.forEach(row => {
                    // Hapus sel terakhir (kolom action)
                    if (row.lastElementChild) {
                        row.removeChild(row.lastElementChild);
                    }
                });

                var printContent = tempTable.outerHTML; // Ambil konten tabel modifikasi
                var printWindow = window.open('', '', 'height=800,width=1200'); // Buka jendela baru untuk print
                printWindow.document.write(`
        <html>
        <head>
            <title>Order Report</title>
            <style>
                body {
                    font-family: 'Arial', sans-serif;
                    margin: 20px;
                    font-size: 14px;
                    line-height: 1.6;
                    color: #333;
                }
                h2 {
                    text-align: center;
                    margin-bottom: 20px;
                    color: #555;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 10px;
                    text-align: center;
                }
                th {
                    background-color: #f2f2f2;
                    color: #333;
                    font-weight: bold;
                }
                td {
                    background-color: #fff;
                }
                tr:nth-child(even) td {
                    background-color: #f9f9f9;
                }
                @media print {
                    body {
                        margin: 0;
                        padding: 0;
                    }
                    table {
                        page-break-inside: auto;
                    }
                    tr {
                        page-break-inside: avoid;
                        page-break-after: auto;
                    }
                }
            </style>
        </head>
        <body>
            <h2>Order Report</h2>
            ${printContent} <!-- Masukkan konten tabel -->
        </body>
        </html>
    `);
                printWindow.document.close(); // Tutup dokumen jendela
                printWindow.print(); // Cetak
            });

            // Fungsi untuk mengekspor tabel ke Excel
            document.getElementById('exportButton').addEventListener('click', function() {
                var wb = XLSX.utils.table_to_book(document.getElementById('reportTable'), {
                    sheet: "Order Report"
                });
                XLSX.writeFile(wb, 'Order_Report.xlsx');
            });

            // Menampilkan tabel menggunakan DataTables
            new DataTable('#reportTable');
        </script>
    </div>
</body>