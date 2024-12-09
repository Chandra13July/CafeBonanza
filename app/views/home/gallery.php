<style>
    body {
        background: #ecf4fb;
    }

    .img-gallery {
        position: relative;
    }

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

    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
    }

    /* Menambahkan aturan untuk menyamakan ukuran foto dan video */
    .gallery-media {
        width: 100%;
        height: 250px; /* Sama untuk foto dan video */
        object-fit: cover;
        border-radius: 8px;
    }
</style>

<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto py-10 text-center px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold mb-4">Our Cafe Gallery</h1>
        <p class="text-gray-500 mb-10">Explore the cozy and inviting ambiance of our cafe through our collection of images and videos.</p>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($data['galleryItems'] as $item): ?>
                <?php
                $fileExt = strtolower(pathinfo($item['ImageUrl'], PATHINFO_EXTENSION));
                $isVideo = in_array($fileExt, ['mp4', 'avi', 'mov', 'mkv']);
                ?>
                <div class="gallery-item img-gallery rounded-lg overflow-hidden shadow-lg">
                    <?php if ($isVideo): ?>
                        <video controls class="gallery-media">
                            <source src="<?= BASEURL; ?>/<?= htmlspecialchars($item['ImageUrl']); ?>" type="video/<?= $fileExt; ?>">
                            Your browser does not support the video tag.
                        </video>
                    <?php else: ?>
                        <img
                            src="<?= BASEURL; ?>/<?= htmlspecialchars($item['ImageUrl']); ?>"
                            alt="<?= htmlspecialchars($item['Title']); ?>"
                            class="gallery-media" />
                    <?php endif; ?>
                    <div class="img-info bg-gray-800 text-white p-4">
                        <div class="img-title text-lg font-bold"><?= htmlspecialchars($item['Title']); ?></div>
                        <div class="img-description text-sm"><?= htmlspecialchars($item['Description']); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
