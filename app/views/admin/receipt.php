<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #f3f4f6;
            font-family: 'Roboto', sans-serif;
        }

        @media print {
            body {
                background: none !important;
                margin: 0 !important;
            }

            #receipt {
                box-shadow: none !important;
                border: none !important;
                background: white !important;
                width: 100%;
                margin: 0 auto;
            }

            button,
            .mt-4 {
                display: none !important;
            }
        }

        .font-roboto {
            font-family: 'Roboto', sans-serif;
        }

        .bg-gray-100 {
            background-color: #f3f4f6;
        }

        .bg-white {
            background-color: #ffffff;
        }

        .text-gray-600 {
            color: #718096;
        }

        .text-gray-700 {
            color: #4a5568;
        }

        .text-amber-600 {
            color: #d97706;
        }

        .text-blue-600:hover {
            color: #1d4ed8;
        }

        .hover\:text-blue-800:hover {
            color: #1e40af;
        }

        .hover\:text-sky-700:hover {
            color: #0c4a6e;
        }

        .hover\:text-pink-800:hover {
            color: #9d174d;
        }

        .bg-amber-600:hover {
            background-color: #d97706;
        }

        .bg-amber-600 {
            background-color: #f59e0b;
        }

        .hover\:bg-amber-700:hover {
            background-color: #b45309;
        }
    </style>
</head>

<body class="bg-gray-100 font-roboto">
    <div class="max-w-sm mx-auto h-auto p-0 rounded-lg overflow-hidden mt-10" id="receipt">
        <div class="p-6 bg-white shadow-lg">
            <div class="flex items-center justify-center">
                <img alt="Cafe logo with a steaming coffee cup" class="w-16 h-16"
                    src="<?= BASEURL; ?>/img/logo-bonanza.png" />
                <div class="ml-4 text-center">
                    <h1 class="text-2xl font-bold text-amber-600">Cafe Bonanza</h1>
                </div>
            </div>
            <div class="text-center mt-2">
                <p class="text-sm text-gray-600">
                    Jl. Pancur, Sumpelan Utara, Lumutan, Kec. Prajekan, Kabupaten Bondowoso, East Java 68284
                </p>
            </div>
            <div class="mt-6">
                <div class="flex justify-between">
                    <p class="text-sm text-gray-600 mb-2">
                        OrderId: <span class="font-bold">#<?= $data['receipt']['OrderId']; ?></span>
                    </p>
                    <p class="text-sm text-gray-600 mb-2">
                        CreatedAt: <span class="font-bold"><?= $data['receipt']['CreatedAt']; ?></span>
                    </p>
                </div>
                <p class="text-sm text-gray-600 mb-2">
                    Customer: <span class="font-bold"><?= $data['receipt']['Customer']; ?></span>
                </p>
            </div>
            <div class="mt-4">
                <?php foreach ($data['receipt']['Items'] as $item): ?>
                    <div class="border-b border-gray-300 bg-gray-50 rounded-lg py-2 px-3">
                        <p class="text-gray-700"><?= $item['MenuName']; ?></p>
                        <div class="flex justify-between">
                            <p class="text-gray-700"><?= $item['Quantity']; ?> x Rp <?= number_format($item['Price']); ?></p>
                            <p class="text-gray-700 font-bold">Rp <?= number_format($item['Subtotal']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="mt-6">
                <div class="flex justify-between items-center pt-2">
                    <p class="text-gray-700 font-bold text-bold-important">Total</p>
                    <p class="text-gray-700 font-bold text-bold-important">Rp <?= number_format($data['receipt']['Total']); ?></p>
                </div>
                <div class="flex justify-between items-center pt-2">
                    <p class="text-gray-700 font-bold"><?= $data['receipt']['PaymentMethod']; ?></p>
                    <p class="text-gray-700 font-bold">Rp <?= number_format($data['receipt']['Paid']); ?></p>
                </div>
                <div class="flex justify-between items-center pt-2">
                    <p class="text-gray-700 font-bold">Change</p>
                    <p class="text-gray-700 font-bold">Rp <?= number_format($data['receipt']['Change']); ?></p>
                </div>
            </div>
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">Thank you for your visit!</p>
                <p class="text-sm text-gray-600">Follow us on social media</p>
                <div class="flex justify-center mt-3">
                    <a class="text-blue-600 mx-2 hover:text-blue-800" href="#"><i class="fab fa-facebook-f text-lg"></i></a>
                    <a class="text-sky-500 mx-2 hover:text-sky-700" href="#"><i class="fab fa-twitter text-lg"></i></a>
                    <a class="text-pink-600 mx-2 hover:text-pink-800" href="#"><i class="fab fa-instagram text-lg"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center mt-4">
        <button onclick="printReceipt()" class="bg-amber-600 text-white px-4 py-2 rounded shadow hover:bg-amber-700 focus:outline-none">
            Print Receipt
        </button>
    </div>

    <script>
        function printReceipt() {
            const printContent = document.getElementById("receipt").outerHTML;
            const originalContent = document.body.innerHTML;

            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
            location.reload();
        }
    </script>
</body>

</html>