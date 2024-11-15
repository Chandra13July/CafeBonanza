<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cafe Gallery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <style>
        body { background: #ecf4fb; }
        .img-gallery { position: relative; }
        .img-info {
            opacity: 1;
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 10px;
            text-align: left;
            border-radius: 0 0 8px 8px;
        }
        .img-gallery img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 8px;
        }
        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto py-10 text-center px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold mb-4">Our Cafe Gallery</h1>
        <p class="text-gray-500 mb-10">Explore the cozy and inviting ambiance of our cafe through our collection of images.</p>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($data['galleryItems'] as $item): ?>
                <div class="gallery-item img-gallery">
                    <img
                        src="<?= BASEURL; ?>/<?= htmlspecialchars($item['ImageUrl']); ?>"
                        alt="<?= htmlspecialchars($item['Title']); ?>" />
                    <div class="img-info">
                        <div class="img-title text-lg font-bold"><?= htmlspecialchars($item['Title']); ?></div>
                        <div class="img-description text-sm"><?= htmlspecialchars($item['Description']); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
