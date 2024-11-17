<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>
<body class="bg-white flex justify-center items-center min-h-screen">
    <div class="w-full max-w-lg p-6">
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
    <!-- Gambar Profil dan Tombol Pilih Foto -->
  <div class="absolute top-10 right-40 flex flex-col items-center">
    <img alt="Profile picture of a person" class="w-40 h-40 rounded-full mb-4" height="160" src="<?= !empty($_SESSION['ImageUrl']) ? BASEURL . '/' . $_SESSION['ImageUrl'] : BASEURL . '/img/user.png'; ?>" width="160"/>
    <button class="border border-gray-300 rounded-md p-2">
      Pilih Foto Profil
    </button>
  </div>
</body>
</html>
