<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Profil Anda</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&amp;display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .profile-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            width: 90%;
            display: flex;
            justify-content: space-between;
            gap: 30px;
        }

        .profile-info {
            flex: 1;
        }

        .profile-info h1 {
            font-size: 2rem;
            color: #333333;
            margin-bottom: 10px;
        }

        .profile-info p {
            color: #666666;
            margin-bottom: 20px;
            font-size: 1rem;
        }

        .profile-section {
            margin-bottom: 1rem;
        }

        .profile-label {
            font-weight: 700;
            color: #333;
            font-size: 1.125rem;
        }

        .profile-value {
            font-weight: 400;
            color: #555;
            font-size: 1rem;
            margin-left: 10px;
        }

        .profile-picture {
            text-align: center;
            min-width: 250px;
            margin-left: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .profile-picture img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 3px solid #ccc;
            transition: all 0.3s ease-in-out;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .profile-picture img:hover {
            border-color: #6b7280;
        }

        .profile-picture button {
            padding: 12px 20px;
            border-radius: 8px;
            border: 1px solid #ccc;
            background-color: #f9fafb;
            cursor: pointer;
            transition: all 0.3s ease;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .profile-picture button:hover {
            background-color: #e5e7eb;
            border-color: #6b7280;
        }

        .profile-picture p {
            font-size: 0.875rem;
            color: #666666;
            margin-top: 10px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .modal-body {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .modal-body label {
            font-weight: 700;
            color: #333;
        }

        .modal-body input,
        .modal-body select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
            width: 100%;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .modal-footer button {
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        .modal-footer .save-btn {
            background-color: #4caf50;
            color: white;
        }

        .modal-footer .cancel-btn {
            background-color: #f44336;
            color: white;
        }

        .modal-footer .delete-btn {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>

<body>
    <div class="profile-container">
        <!-- Informasi Profil -->
        <div class="profile-info">
            <h1>Profil Anda</h1>
            <p>Kelola informasi profil Anda untuk mengontrol, melindungi, dan mengamankan akun.</p>
            <?php
            $username = !empty($data['username']) ? htmlspecialchars($data['username']) : 'Belum diisi';
            $email = !empty($data['email']) ? htmlspecialchars($data['email']) : 'Belum diisi';
            $phone = !empty($data['phone']) ? htmlspecialchars($data['phone']) : 'Belum diisi';
            $gender = !empty($data['gender']) ? htmlspecialchars($data['gender']) : 'Belum diisi';
            $dob = !empty($data['dob']) ? htmlspecialchars($data['dob']) : 'Belum diisi';
            $address = !empty($data['address']) ? htmlspecialchars($data['address']) : 'Belum diisi';
            ?>
            <div class="profile-section">
                <span class="profile-label">Username:</span>
                <span class="profile-value"><?= $username; ?></span>
            </div>
            <div class="profile-section">
                <span class="profile-label">Email:</span>
                <span class="profile-value"><?= $email; ?></span>
            </div>
            <div class="profile-section">
                <span class="profile-label">Phone:</span>
                <span class="profile-value"><?= $phone; ?></span>
            </div>
            <div class="profile-section">
                <span class="profile-label">Gender:</span>
                <span class="profile-value"><?= $gender; ?></span>
            </div>
            <div class="profile-section">
                <span class="profile-label">Date of Birth:</span>
                <span class="profile-value"><?= $dob; ?></span>
            </div>
            <div class="profile-section">
                <span class="profile-label">Address:</span>
                <span class="profile-value"><?= $address; ?></span>
            </div>
            <div class="flex gap-4 mt-4">
                <button class="bg-blue-500 text-white py-2 px-4 rounded" onclick="document.getElementById('editModal').style.display='block'">Edit Profil</button>
                <button class="bg-red-500 text-white py-2 px-4 rounded" onclick="document.getElementById('deleteModal').style.display='block'">Hapus Akun</button>
            </div>
        </div>
        <!-- Foto Profil -->
        <div class="profile-picture" style="margin: auto; text-align: center;">
            <?php
            $imageUrl = !empty($_SESSION['ImageUrl']) ?  htmlspecialchars($_SESSION['ImageUrl']) : BASEURL . '/img/user.png';
            ?>
            <img alt="Profile picture" src="<?= $imageUrl; ?>" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;" />
            <input id="fileInput" style="display: none;" type="file" accept=".jpg, .jpeg, .png" />
            <button onclick="document.getElementById('fileInput').click();" style="margin-top: 10px;">Pilih Foto Profil</button>
            <p style="font-size: 14px; color: gray;">Hanya file dengan format <strong>JPG, PNG, JPEG</strong><br />dan ukuran <strong>1:1</strong> diperbolehkan.</p>
        </div>
    </div>
    <!-- Modal Edit Profil -->
    <div class="modal" id="editModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Profil</h2>
                <span class="close" onclick="document.getElementById('editModal').style.display='none'">&times;</span>
            </div>
            <div class="modal-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="username">Username</label>
                        <input id="username" name="username" type="text" value="<?= $username; ?>" />
                    </div>
                    <div>
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" value="<?= $email; ?>" />
                    </div>
                    <div>
                        <label for="phone">Phone</label>
                        <input id="phone" name="phone" type="text" value="<?= $phone; ?>" />
                    </div>
                    <div>
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender">
                            <option value="male" <?= $gender == 'male' ? 'selected' : ''; ?>>Male</option>
                            <option value="female" <?= $gender == 'female' ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>
                    <div>
                        <label for="dob">Date of Birth</label>
                        <input id="dob" name="dob" type="date" value="<?= $dob; ?>" />
                    </div>
                    <div>
                        <label for="address">Address</label>
                        <input id="address" name="address" type="text" value="<?= $address; ?>" />
                    </div>
                </div>
            </div>
            <div class="modal-footer mt-4">
                <button class="cancel-btn" onclick="document.getElementById('editModal').style.display='none'">Cancel</button>
                <button class="save-btn">Save</button>
            </div>
        </div>
    </div>
    <!-- Modal Hapus Akun -->
    <div class="modal" id="deleteModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Hapus Akun</h2>
                <span class="close" onclick="document.getElementById('deleteModal').style.display='none'">&times;</span>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus akun Anda? Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer mt-4">
                <button class="cancel-btn" onclick="document.getElementById('deleteModal').style.display='none'">Cancel</button>
                <button class="delete-btn">Hapus</button>
            </div>
        </div>
    </div>
    <script>
        // Close the modal when clicking outside of it
        window.onclick = function(event) {
            var editModal = document.getElementById('editModal');
            var deleteModal = document.getElementById('deleteModal');
            if (event.target == editModal) {
                editModal.style.display = "none";
            }
            if (event.target == deleteModal) {
                deleteModal.style.display = "none";
            }
        }
    </script>
</body>

</html>