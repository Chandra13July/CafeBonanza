<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            padding-left: 500px;
            padding-right: 10px;
            background-color: #ffffff; /* Set background color to white */
        }

        .profile-background {
            background-color: white; /* White background for the profile section */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: absolute;
            top: 10%;
            right: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 300px; /* Adjusted width for the profile box */
        }

        .profile-background img {
            margin-bottom: 15px; /* Added some space below the image */
        }

        .profile-background button {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 10px; /* Added margin bottom for spacing */
        }

        .profile-background p {
            font-size: 0.875rem;
            color: #4a4a4a;
            margin-top: 10px;
        }
    </style>
</head>
<body class="flex justify-center items-center min-h-screen">
    <div class="w-full max-w-lg p-6 profile-container">
        <h1 class="text-3xl font-bold mb-2">PROFIL ANDA</h1>
        <p class="text-base mb-6">
            Kelola informasi profil Anda untuk mengontrol, melindungi, dan mengamankan akun
        </p>
        <form class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1" for="nama-user">Nama User</label>
                <input class="w-full border border-gray-300 rounded-md p-2" id="nama-user" type="text" value="<?= htmlspecialchars($data['username']); ?>" readonly />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="email">Email</label>
                <input class="w-full border border-gray-300 rounded-md p-2" id="email" type="email" value="<?= htmlspecialchars($data['email']); ?>" readonly />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="phone">Phone</label>
                <input class="w-full border border-gray-300 rounded-md p-2" id="phone" type="text" value="<?= htmlspecialchars($data['phone']); ?>" readonly />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="gender">Gender</label>
                <input class="w-full border border-gray-300 rounded-md p-2" id="gender" type="text" value="<?= htmlspecialchars($data['gender']); ?>" readonly />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="dob">Date of Birth</label>
                <input class="w-full border border-gray-300 rounded-md p-2" id="dob" type="date" value="<?= htmlspecialchars($data['dob']); ?>" readonly />
            </div>
            <div>
                <label class="block text-sm font-medium mb-1" for="address">Address</label>
                <textarea class="w-full border border-gray-300 rounded-md p-2" id="address" rows="2" readonly><?= htmlspecialchars($data['address']); ?></textarea>
            </div>
        </form>
    </div>

    <!-- Gambar Profil dan Tombol Pilih Foto with White Background -->
    <div class="profile-background">
        <img alt="Profile picture of a person" class="w-40 h-40 rounded-full mb-4" height="160" src="<?= !empty($_SESSION['ImageUrl']) ? BASEURL . '/' . $_SESSION['ImageUrl'] : BASEURL . '/img/user.png'; ?>" width="160"/>
        <button class="border border-gray-300 rounded-md p-2">
            Pilih Foto Profil
        </button>
        <!-- Keterangan Format dan Ukuran File (Centered) -->
        <p class="text-sm text-gray-500 mt-2 text-center">
            Hanya file dengan format <strong>JPG, PNG, JPEG</strong> dan ukuran <strong>1:1</strong> diperbolehkan.
        </p>
    </div>
</body>
</html>
