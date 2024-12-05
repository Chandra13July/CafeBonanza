    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        #editModal {
            display: none;
            /* Pastikan modal tersembunyi secara default */
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
        }

        #editModal>div {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
    </head>

    <body class="bg-gray-100  flex justify-center items-center min-h-screen">
        <div class="bg-white mx-auto p-8 rounded-lg shadow-lg max-w-4xl w-full flex justify-between gap-8">
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
                    <button class="bg-blue-500 text-white py-2 px-4 rounded" onclick="openModal()">Edit Profil</button>
                </div>
            </div>
            <div class="text-center">
                <?php
                // Gunakan URL gambar yang ada di session, jika tidak ada, gunakan gambar default
                $imageUrl = !empty($_SESSION['ImageUrl']) ? htmlspecialchars($_SESSION['ImageUrl']) : BASEURL . '/img/user.png';
                ?>
                <img id="profileImage" alt="Profile picture" src="<?= $imageUrl; ?>" class="w-48 h-48 rounded-full object-cover mb-4 mx-auto border-4 border-gray-300 hover:border-gray-500 transition-all">
                <input id="fileInput" class="hidden" type="file" accept=".jpg, .jpeg, .png" onchange="previewImage(event)">
                <button class="bg-gray-200 text-gray-700 py-2 px-4 rounded" onclick="document.getElementById('fileInput').click();">Pilih Foto Profil</button>
                <p class="text-sm text-gray-600 mt-2">Hanya file dengan format <strong>JPG, PNG, JPEG</strong></p>
            </div>
        </div>

        <!-- Modal Edit Profil -->
        <div class="fixed inset-0 bg-black bg-opacity-50 hidden" id="editModal">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg">
                <form action="<?= BASEURL; ?>/profile/btnEditProfile" method="POST">
                    <div class="flex justify-between items-center border-b pb-3 mb-4">
                        <h2 class="text-xl font-bold">Edit Profil</h2>
                        <span class="text-gray-500 cursor-pointer text-2xl" onclick="closeModal()">&times;</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="username" class="font-bold text-gray-800">Username</label>
                            <input id="username" name="username" type="text" value="<?= $username; ?>" class="w-full p-2 border rounded mt-1" required />
                        </div>
                        <div>
                            <label for="email" class="font-bold text-gray-800">Email</label>
                            <input id="email" name="email" type="email" value="<?= $email; ?>" class="w-full p-2 border rounded mt-1" required />
                        </div>
                        <div>
                            <label for="phone" class="font-bold text-gray-800">Phone</label>
                            <input id="phone" name="phone" type="text" value="<?= $phone; ?>" class="w-full p-2 border rounded mt-1" required />
                        </div>
                        <div>
                            <label for="gender" class="font-bold text-gray-800">Gender</label>
                            <select id="gender" name="gender" class="w-full p-2 border rounded mt-1" required>
                                <option value="male" <?= $gender == 'male' ? 'selected' : ''; ?>>Male</option>
                                <option value="female" <?= $gender == 'female' ? 'selected' : ''; ?>>Female</option>
                            </select>
                        </div>
                        <div>
                            <label for="dob" class="font-bold text-gray-800">Date of Birth</label>
                            <input id="dob" name="dateOfBirth" type="date" value="<?= $dob; ?>" class="w-full p-2 border rounded mt-1" required />
                        </div>
                        <div>
                            <label for="address" class="font-bold text-gray-800">Address</label>
                            <input id="address" name="address" type="text" value="<?= $address; ?>" class="w-full p-2 border rounded mt-1" required />
                        </div>
                    </div>
                    <div class="flex justify-end gap-4 mt-6">
                        <button type="button" class="bg-red-500 text-white py-2 px-4 rounded" onclick="closeModal()">Cancel</button>
                        <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded">Save</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function openModal() {
                document.getElementById('editModal').style.display = 'flex';
            }

            function closeModal() {
                document.getElementById('editModal').style.display = 'none';
            }

            window.onclick = function(event) {
                var editModal = document.getElementById('editModal');
                if (event.target == editModal) {
                    closeModal();
                }
            };

            function uploadImage(event) {
                const file = event.target.files[0];
                if (!file) return;

                const formData = new FormData();
                formData.append("image", file);

                fetch("<?= BASEURL; ?>/profile/btnEditImage", {
                        method: "POST",
                        body: formData,
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('profileImage').src = data.imageUrl;
                            alert('Gambar berhasil diubah!');
                        } else {
                            alert('Gagal mengunggah gambar!');
                        }
                    })
                    .catch(error => {
                        console.error("Error uploading image:", error);
                        alert('Terjadi kesalahan saat mengunggah gambar.');
                    });
            }

            function previewImage(event) {
                var reader = new FileReader();
                reader.onload = function() {
                    var output = document.getElementById('profileImage');
                    output.src = reader.result;
                };
                reader.readAsDataURL(event.target.files[0]);

                uploadImage(event);
            }
        </script>
    </body>