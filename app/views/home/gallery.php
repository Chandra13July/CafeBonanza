<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Image Gallery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            background: #ecf4fb;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="max-w-7xl mx-auto py-10 relative z-10 text-center px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold mb-10">Our Gallery</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 img-gallery">
            <img class="w-full cursor-pointer transition-transform transform hover:scale-75 hover:rotate-6 hover:rounded-lg hover:shadow-2xl" height="200" onclick="openFullImg(this.src)" src="<?= BASEURL; ?>/img/gallery/gallery1.png" width="200" />
            <img class="w-full cursor-pointer transition-transform transform hover:scale-75 hover:rotate-6 hover:rounded-lg hover:shadow-2xl" height="200" onclick="openFullImg(this.src)" src="<?= BASEURL; ?>/img/gallery/gallery2.png" width="200" />
            <img class="w-full cursor-pointer transition-transform transform hover:scale-75 hover:rotate-6 hover:rounded-lg hover:shadow-2xl" height="200" onclick="openFullImg(this.src)" src="<?= BASEURL; ?>/img/gallery/gallery3.png" width="200" />
            <img class="w-full cursor-pointer transition-transform transform hover:scale-75 hover:rotate-6 hover:rounded-lg hover:shadow-2xl" height="250" onclick="openFullImg(this.src)" src="<?= BASEURL; ?>/img/gallery/gallery4.png" width="200" />
            <img class="w-full cursor-pointer transition-transform transform hover:scale-75 hover:rotate-6 hover:rounded-lg hover:shadow-2xl" height="200" onclick="openFullImg(this.src)" src="<?= BASEURL; ?>/img/gallery/gallery5.png" width="200" />
            <img class="w-full cursor-pointer transition-transform transform hover:scale-75 hover:rotate-6 hover:rounded-lg hover:shadow-2xl" height="200" onclick="openFullImg(this.src)" src="<?= BASEURL; ?>/img/gallery/gallery6.png" width="200" />
            <img class="w-full cursor-pointer transition-transform transform hover:scale-75 hover:rotate-6 hover:rounded-lg hover:shadow-2xl" height="200" onclick="openFullImg(this.src)" src="<?= BASEURL; ?>/img/gallery/gallery7.png" width="200" />
            <img class="w-full cursor-pointer transition-transform transform hover:scale-75 hover:rotate-6 hover:rounded-lg hover:shadow-2xl" height="200" onclick="openFullImg(this.src)" src="<?= BASEURL; ?>/img/gallery/gallery8.png" width="200" />
            <img class="w-full cursor-pointer transition-transform transform hover:scale-75 hover:rotate-6 hover:rounded-lg hover:shadow-2xl" height="200" onclick="openFullImg(this.src)" src="<?= BASEURL; ?>/img/gallery/gallery9.png" width="200" />
            <img class="w-full cursor-pointer transition-transform transform hover:scale-75 hover:rotate-6 hover:rounded-lg hover:shadow-2xl" height="200" onclick="openFullImg(this.src)" src="<?= BASEURL; ?>/img/gallery/gallery10.png" width="200" />
            <img class="w-full cursor-pointer transition-transform transform hover:scale-75 hover:rotate-6 hover:rounded-lg hover:shadow-2xl" height="200" onclick="openFullImg(this.src)" src="<?= BASEURL; ?>/img/gallery/gallery11.png" width="200" />
            <img class="w-full cursor-pointer transition-transform transform hover:scale-75 hover:rotate-6 hover:rounded-lg hover:shadow-2xl" height="200" onclick="openFullImg(this.src)" src="<?= BASEURL; ?>/img/gallery/gallery12.png" width="200" />
        </div>
    </div>
    <div class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center hidden z-20" id="fullImgBox">
        <div class="relative">
            <img alt="Full view of the selected image" class="w-11/12 max-w-lg" height="500" id="fullImg" src="https://storage.googleapis.com/a1aa/image/xfeLUJO7e6Ub1oSRUDA5GGGmHNQwH8evsbMK3tWgD4sQgvyOB.jpg" width="500" />
            <span class="absolute top-0 right-12 text-black text-4xl font-bold cursor-pointer bg-opacity-50 p-2" onclick="closeFullImg()">×</span>
        </div>
    </div>
    <script>
        var fullImgBox = document.getElementById("fullImgBox");
        var fullImg = document.getElementById("fullImg");

        function openFullImg(pic) {
            fullImgBox.style.display = "flex";
            fullImg.src = pic;
            document.body.classList.add('overflow-hidden');
        }

        function closeFullImg() {
            fullImgBox.style.display = "none";
            document.body.classList.remove('overflow-hidden');
        }
    </script>
</body>

</html>