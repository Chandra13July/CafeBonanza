<style>
    @media print {

        #exportFormat,
        #exportButton,
        #success-notification,
        #error-notification,
        .modal {
            display: none;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .table-auto {
            width: 100%;
            border-collapse: collapse;
        }

        .table-auto th,
        .table-auto td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        .table-auto th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .table-auto td {
            background-color: #ffffff;
        }

        /* Adjust page size for printing */
        .flex-1 {
            width: 100%;
            margin: 0;
            padding: 20px;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        /* Adjust margins */
        .overflow-x-auto {
            margin-top: 20px;
        }
    }
</style>

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
            <div class="mb-4 flex gap-4 justify-between">
                <form method="POST" action="<?= BASEURL; ?>/report/filterReport" class="flex items-center gap-4 ml-4">
                    <div class="flex items-center gap-2">
                        <label for="startDate" class="text-gray-600">Start Date:</label>
                        <input type="date" name="startDate" id="startDate" class="border rounded p-1">
                    </div>
                    <div class="flex items-center gap-2">
                        <label for="endDate" class="text-gray-600">End Date:</label>
                        <input type="date" name="endDate" id="endDate" class="border rounded p-1">
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded flex items-center">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                    <button type="button" onclick="printReport()" class="bg-green-500 text-white px-6 py-2 rounded flex items-center ml-4">
                        <i class="fas fa-print mr-2"></i> Print
                    </button>

                </form>

                <div class="flex items-center gap-4 justify-end">
                    <div class="flex items-center gap-2">
                        <label for="exportFormat" class="text-gray-600">Export Format:</label>
                        <select id="exportFormat" class="border rounded p-1">
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                            <option value="json">Json</option>
                        </select>

                        <button id="exportButton" class="bg-blue-500 text-white px-6 py-2 rounded flex items-center">
                            <i class="fas fa-download mr-2"></i> Export
                        </button>
                    </div>
                </div>
            </div>

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
                                <tr class="text-sm text-gray-600 text-center">
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
                                        <a href="<?= BASEURL; ?>/report/orderReceipt/<?= $order['OrderId']; ?>"
                                            class="bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-700 transition duration-200 w-14 text-center inline-block">
                                            <i class="fas fa-eye text-base"></i>
                                        </a>

                                        <button class="bg-green-500 text-white px-3 py-1 rounded-md hover:bg-green-700 transition duration-200 ml-2 w-14 text-center inline-block"
                                            onclick="openEditModal(<?= htmlspecialchars(json_encode($order), ENT_QUOTES, 'UTF-8'); ?>)">
                                            <i class="fas fa-pen text-base"></i>
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

        <div id="editModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex justify-center items-center">
            <div class="bg-white rounded-lg w-3/4 max-w-4xl p-6">
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">Edit Order</h2>
                <form id="editOrderForm" method="POST" action="<?= BASEURL; ?>/report/btnEditOrder" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="hidden" name="OrderId" id="editOrderId" value="<?= isset($order['OrderId']) ? $order['OrderId'] : ''; ?>">

                    <div class="mb-4">
                        <label for="customer" class="block text-gray-700">Customer</label>
                        <input type="text" name="customer" id="editCustomer" class="w-full p-2 border border-gray-300 rounded"
                            value="<?= isset($order['Customer']) ? htmlspecialchars($order['Customer']) : ''; ?>" readonly required>
                    </div>
                    <div class="mb-4">
                        <label for="total" class="block text-gray-700">Total</label>
                        <input type="number" name="total" id="editTotal" class="w-full p-2 border border-gray-300 rounded"
                            value="<?= isset($order['Total']) ? $order['Total'] : ''; ?>" required readonly>
                    </div>
                    <div class="mb-4">
                        <label for="paid" class="block text-gray-700">Paid</label>
                        <input type="number" name="paid" id="editPaid" class="w-full p-2 border border-gray-300 rounded"
                            value="<?= isset($order['Paid']) ? $order['Paid'] : ''; ?>" required>
                    </div>
                    <div class="mb-4">
                        <label for="change" class="block text-gray-700">Change</label>
                        <input type="number" name="change" id="editChange" class="w-full p-2 border border-gray-300 rounded"
                            value="<?= isset($order['Change']) ? $order['Change'] : ''; ?>" required readonly>
                    </div>
                    <div class="mb-4">
                        <label for="paymentMethod" class="block text-gray-700">Payment Method</label>
                        <select name="paymentMethod" id="editPaymentMethod" class="w-full p-2 border border-gray-300 rounded" required>
                            <option value="Cash" <?= isset($order['PaymentMethod']) && $order['PaymentMethod'] === 'Cash' ? 'selected' : ''; ?>>Cash</option>
                            <option value="E-Wallet" <?= isset($order['PaymentMethod']) && $order['PaymentMethod'] === 'E-Wallet' ? 'selected' : ''; ?>>E-Wallet</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="status" class="block text-gray-700">Status</label>
                        <select name="status" id="editStatus" class="w-full p-2 border border-gray-300 rounded" required>
                            <option value="Pending" <?= isset($order['Status']) && $order['Status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="Processing" <?= isset($order['Status']) && $order['Status'] === 'Processing' ? 'selected' : ''; ?>>Processing</option>
                            <option value="Completed" <?= isset($order['Status']) && $order['Status'] === 'Completed' ? 'selected' : ''; ?>>Completed</option>
                            <option value="Cancelled" <?= isset($order['Status']) && $order['Status'] === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>
                    <div class="flex justify-end col-span-2">
                        <button type="button" onclick="closeEditModal()" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Save</button>
                    </div>
                </form>
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
        <script src="//cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>

        <script>
            const editModal = document.getElementById("editModal");

            function openEditModal(order) {
                document.getElementById('editOrderId').value = order.OrderId;
                document.getElementById('editCustomer').value = order.Customer;
                document.getElementById('editTotal').value = order.Total;
                document.getElementById('editPaid').value = order.Paid;
                document.getElementById('editChange').value = order.Change;
                document.getElementById('editPaymentMethod').value = order.PaymentMethod;
                document.getElementById('editStatus').value = order.Status;
                editModal.classList.remove('hidden');
            }

            function closeEditModal() {
                editModal.classList.add('hidden');
            }

            function calculateChange() {
                const total = parseInt(document.getElementById('editTotal').value) || 0;
                const paid = parseInt(document.getElementById('editPaid').value) || 0;

                // Jika Paid kosong, maka kosongkan Change
                if (document.getElementById('editPaid').value === "") {
                    document.getElementById('editChange').value = '';
                    return;
                }

                // Jika pembayaran lebih kecil dari total, tampilkan nilai negatif di Change
                if (paid < total) {
                    const change = paid - total;
                    document.getElementById('editChange').value = change;
                    document.getElementById('editPaid').setCustomValidity("Pembayaran harus lebih besar dari Total");
                } else {
                    // Jika pembayaran cukup, hitung sisa perubahan
                    const change = paid - total;
                    document.getElementById('editChange').value = change;
                    document.getElementById('editPaid').setCustomValidity(""); // Reset validasi
                }
            }

            document.getElementById('editTotal').addEventListener('input', calculateChange);
            document.getElementById('editPaid').addEventListener('input', calculateChange);

            document.getElementById("editOrderForm").addEventListener("submit", function(event) {
                const paid = parseInt(document.getElementById('editPaid').value) || 0;
                const total = parseInt(document.getElementById('editTotal').value) || 0;
                let status = document.getElementById('editStatus').value;

                // Otomatis ubah status menjadi "Processing" jika pembayaran terpenuhi
                if (paid >= total && status === "Pending") {
                    document.getElementById('editStatus').value = "Processing";
                    status = "Processing"; // Update status setelah perubahan
                }

                const previousStatus = document.getElementById('editStatus').dataset.previousStatus || "Pending";

                // Validasi agar tidak mundur dari "Processing" atau "Completed" ke "Pending"
                if ((previousStatus === "Processing" || previousStatus === "Completed") && status === "Pending") {
                    alert("Status tidak bisa mundur dari 'Processing' atau 'Completed' ke 'Pending'.");
                    event.preventDefault();
                    return;
                }

                // Simpan status terbaru untuk referensi
                document.getElementById('editStatus').dataset.previousStatus = status;
            });

            function printReport() {
                // Menyembunyikan elemen yang tidak ingin dicetak
                document.getElementById("exportFormat").style.display = 'none';
                document.getElementById("exportButton").style.display = 'none';

                // Mencetak halaman
                window.print();

                // Menampilkan kembali elemen setelah pencetakan
                document.getElementById("exportFormat").style.display = 'inline-block';
                document.getElementById("exportButton").style.display = 'inline-block';
            }

            document.getElementById("exportButton").addEventListener("click", function() {
                const selectedFormat = document.getElementById("exportFormat").value;

                if (selectedFormat === "pdf") {
                    window.location.href = "<?= BASEURL; ?>/report/exportPdf";
                } else if (selectedFormat === "excel") {
                    window.location.href = "<?= BASEURL; ?>/report/exportExcel";
                } else if (selectedFormat === "csv") {
                    window.location.href = "<?= BASEURL; ?>/report/exportCsv";
                } else if (selectedFormat === "json") {
                    window.location.href = "<?= BASEURL; ?>/report/exportJson";
                }
            });

            window.onload = function() {
                const successNotification = document.getElementById('success-notification');
                if (successNotification) {
                    setTimeout(() => {
                        successNotification.style.display = 'none';
                    }, 1000);
                }

                const errorNotification = document.getElementById('error-notification');
                if (errorNotification) {
                    setTimeout(() => {
                        errorNotification.style.display = 'none';
                    }, 1000);
                }
            }

            new DataTable('#reportTable');
        </script>
    </div>
</body>