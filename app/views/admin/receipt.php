<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Cafe Order Receipt</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&amp;display=swap" rel="stylesheet" />
</head>

<body class="bg-gray-100 font-roboto">
    <div class="max-w-sm mx-auto bg-white shadow-lg rounded-lg overflow-hidden mt-10">
        <div class="p-4">
            <div class="flex items-center justify-center">
                <img alt="Cafe logo with a steaming coffee cup" class="w-12 h-12" height="50" src="<?= BASEURL; ?>/img/logo-bonanza.png" width="50" />
                <div class="ml-4 text-center">
                    <h1 class="text-2xl font-bold">Cafe Bonanza</h1>
                </div>
            </div>
            <div class="text-center mt-2">
                <p class="text-sm text-gray-600">
                    Jl. Pancur, Sumpelan Utara, Lumutan, Kec. Prajekan, Kabupaten Bondowoso, East Java 68284
                </p>
            </div>
            <div class="mt-4">
                <p class="text-sm text-gray-600 mb-2">
                    OrderId: <span class="font-bold">#<?= $data['receipt']['OrderId']; ?></span>
                </p>
                <p class="text-sm text-gray-600 mb-2">
                    Customer: <span class="font-bold"><?= $data['receipt']['Customer']; ?></span>
                </p>
                <p class="text-sm text-gray-600 mb-2">
                    CreatedAt: <span class="font-bold"><?= $data['receipt']['CreatedAt']; ?></span>
                </p>
            </div>
            <div class="mt-2">
                <?php foreach ($data['receipt']['Items'] as $item): ?>
                    <div class="border-b py-2">
                        <p class="text-gray-700"><?= $item['MenuName']; ?></p>
                        <div class="flex justify-between">
                            <p class="text-gray-700"><?= $item['Quantity']; ?> x Rp <?= number_format($item['Price']); ?></p>
                            <p class="text-gray-700">Rp <?= number_format($item['Subtotal']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="mt-4">
                <div class="flex justify-between items-center  pt-2">
                    <p class="text-gray-700 font-bold">Total</p>
                    <p class="text-gray-700 font-bold">Rp <?= number_format($data['receipt']['Total']); ?></p>
                </div>
                <div class="flex justify-between items-center  pt-2">
                    <p class="text-gray-700 font-bold">
                        <?= $data['receipt']['PaymentMethod']; ?>
                    </p>
                    <p class="text-gray-700 font-bold">
                        Rp <?= number_format($data['receipt']['Paid']); ?>
                    </p>
                </div>
                <div class="flex justify-between items-center  pt-2">
                    <p class="text-gray-700 font-bold">Change</p>
                    <p class="text-gray-700 font-bold">Rp <?= number_format($data['receipt']['Change']); ?></p>
                </div>
            </div>
            <div class="mt-4 text-center">
                <p class="text-sm text-gray-600">Thank you for your visit!</p>
                <p class="text-sm text-gray-600">Follow us on social media</p>
                <div class="flex justify-center mt-2">
                    <a class="text-gray-600 mx-2" href="#"><i class="fab fa-facebook-f"></i></a>
                    <a class="text-gray-600 mx-2" href="#"><i class="fab fa-twitter"></i></a>
                    <a class="text-gray-600 mx-2" href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </div>
    <style>
        .max-w-sm {
            min-height: auto;
        }

        .mt-4:last-child {
            margin-bottom: 0;
        }

        .overflow-hidden {
            padding-bottom: 1rem;
        }
    </style>
</body>

</html>