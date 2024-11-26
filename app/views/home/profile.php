<html>
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"></link>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-4xl w-full flex justify-between gap-8">
        <div class="flex-1">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Profil Anda</h1>
            <p class="text-gray-600 mb-6">Kelola informasi profil Anda untuk mengontrol, melindungi, dan mengamankan akun.</p>
            <?php
            $username = !empty($data['username']) ? htmlspecialchars($data['username']) : 'Belum diisi';
            $email = !empty($data['email']) ? htmlspecialchars($data['email']) : 'Belum diisi';
            $phone = !empty($data['phone']) ? htmlspecialchars($data['phone']) : 'Belum diisi';
            $gender = !empty($data['gender']) ? htmlspecialchars($data['gender']) : 'Belum diisi';
            $dob = !empty($data['dob']) ? htmlspecialchars($data['dob']) : 'Belum diisi';
            $address = !empty($data['address']) ? htmlspecialchars($data['address']) : 'Belum diisi';
            $createdAt = !empty($data['CreatedAt']) ? date('d M Y', strtotime($data['CreatedAt'])) : 'Belum diisi';

            if ($email != 'Belum diisi') {
                list($usernamePart, $domain) = explode('@', $email);
                $maskedEmail = substr($usernamePart, 0, 3) . '****' . '@' . $domain;
            } else {
                $maskedEmail = 'Belum diisi';
            }

            if ($phone != 'Belum diisi') {
                $maskedPhone = substr($phone, 0, 4) . '****' . substr($phone, -4);
            } else {
                $maskedPhone = 'Belum diisi';
            }
            ?>
            <div class="mb-4">
                <span class="font-bold text-gray-800">Username:</span>
                <span class="text-gray-600 ml-2"><?= $username; ?></span>
            </div>
            <div class="mb-4">
                <span class="font-bold text-gray-800">Email:</span>
                <span class="text-gray-600 ml-2"><?= $maskedEmail; ?></span>
            </div>
            <div class="mb-4">
                <span class="font-bold text-gray-800">Phone:</span>
                <span class="text-gray-600 ml-2"><?= $maskedPhone; ?></span>
            </div>
            <div class="mb-4">
                <span class="font-bold text-gray-800">Gender:</span>
                <span class="text-gray-600 ml-2"><?= $gender; ?></span>
            </div>
            <div class="mb-4">
                <span class="font-bold text-gray-800">Date of Birth:</span>
                <span class="text-gray-600 ml-2"><?= $dob; ?></span>
            </div>
            <div class="mb-4">
                <span class="font-bold text-gray-800">Address:</span>
                <span class="text-gray-600 ml-2"><?= $address; ?></span>
            </div>
            <div class="mb-4">
                <span class="font-bold text-gray-800">Tanggal Registrasi:</span>
                <span class="text-gray-600 ml-2"><?= $createdAt; ?></span>
            </div>
            <div class="flex gap-4 mt-4">
                <button class="bg-blue-500 text-white py-2 px-4 rounded" onclick="document.getElementById('editModal').style.display='block'">Edit Profil</button>
            </div>
        </div>
        <div class="text-center">
            <?php
            if (!empty($_SESSION['ImageUrl'])) {
                if (strpos($_SESSION['ImageUrl'], 'http://localhost') !== false) {
                    $imageUrl = htmlspecialchars($_SESSION['ImageUrl']);
                } else {
                    $imageUrl = rtrim(BASEURL, '/') . '/' . htmlspecialchars($_SESSION['ImageUrl']);
                }
            } else {
                $imageUrl = BASEURL . '/img/user.png';
            }
            ?>
            <img id="profileImage" alt="Profile picture" src="<?= $imageUrl; ?>" class="w-48 h-48 rounded-full object-cover mb-4 mx-auto border-4 border-gray-300 hover:border-gray-500 transition-all">
            <input id="fileInput" class="hidden" type="file" accept=".jpg, .jpeg, .png" onchange="previewImage(event)">
            <button class="bg-gray-200 text-gray-700 py-2 px-4 rounded" onclick="document.getElementById('fileInput').click();">Pilih Foto Profil</button>
            <p class="text-sm text-gray-600 mt-2">Hanya file dengan format <strong>JPG, PNG, JPEG</strong></p>
        </div>
    </div>

    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden" id="editModal">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h2 class="text-xl font-bold">Edit Profil</h2>
                <span class="text-gray-500 cursor-pointer text-2xl" onclick="document.getElementById('editModal').style.display='none'">&times;</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="username" class="font-bold text-gray-800">Username</label>
                    <input id="username" name="username" type="text" value="<?= $username; ?>" class="w-full p-2 border rounded mt-1" />
                </div>
                <div>
                    <label for="email" class="font-bold text-gray-800">Email</label>
                    <input id="email" name="email" type="email" value="<?= $email; ?>" class="w-full p-2 border rounded mt-1" />
                </div>
                <div>
                    <label for="phone" class="font-bold text-gray-800">Phone</label>
                    <input id="phone" name="phone" type="text" value="<?= $phone; ?>" class="w-full p-2 border rounded mt-1" />
                </div>
                <div>
                    <label for="gender" class="font-bold text-gray-800">Gender</label>
                    <select id="gender" name="gender" class="w-full p-2 border rounded mt-1">
                        <option value="male" <?= $gender == 'male' ? 'selected' : ''; ?>>Male</option>
                        <option value="female" <?= $gender == 'female' ? 'selected' : ''; ?>>Female</option>
                    </select>
                </div>
                <div>
                    <label for="dob" class="font-bold text-gray-800">Date of Birth</label>
                    <input id="dob" name="dob" type="date" value="<?= $dob; ?>" class="w-full p-2 border rounded mt-1" />
                </div>
                <div>
                    <label for="address" class="font-bold text-gray-800">Address</label>
                    <input id="address" name="address" type="text" value="<?= $address; ?>" class="w-full p-2 border rounded mt-1" />
                </div>
            </div>
            <div class="flex justify-end gap-4 mt-6">
                <button class="bg-red-500 text-white py-2 px-4 rounded" onclick="document.getElementById('editModal').style.display='none'">Cancel</button>
                <button class="bg-green-500 text-white py-2 px-4 rounded">Save</button>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('profileImage');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }

        window.onclick = function(event) {
            var editModal = document.getElementById('editModal');
            if (event.target == editModal) {
                editModal.style.display = "none";
            }
        }
    </script>
</body>
</html>