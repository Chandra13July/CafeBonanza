<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Profile Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"/>
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .warning {
            color: red;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body class="bg-cover bg-center" style="background-image: url('footerhome.png');">
    <div class="flex items-center justify-center min-h-screen bg-gray-900 bg-opacity-50">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-2xl relative">
            <button class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                <i class="fas fa-arrow-left"></i>
            </button>
            <h1 class="text-3xl font-bold text-gray-700 mb-2">PROFIL ANDA</h1>
            <p class="text-gray-500 mb-6">
                Kelola informasi profil Anda untuk mengontrol, melindungi, dan mengamankan akun
            </p>
            <div class="flex items-center justify-center mb-6">
                <img id="profileImage" alt="Profile picture of a person with glasses and short hair" class="rounded-full w-32 h-32" height="128" src="https://storage.googleapis.com/a1aa/image/8an6zVV4oqqUN1bEirRyARggo1LGd5eASfeU9s750pef2XDeE.jpg" width="128"/>
            </div>
            <div class="flex items-center justify-center mb-6">
                <!-- Hidden file input for profile photo -->
                <input type="file" id="profilePhotoInput" accept="image/*" class="hidden" onchange="previewImage(event)">
                <button class="border border-gray-300 rounded-full px-4 py-2 text-gray-700 hover:bg-gray-100" onclick="document.getElementById('profilePhotoInput').click();">
                    Pilih Foto Profil
                </button>
            </div>
            <!-- Warning message for invalid file type -->
            <p id="fileTypeWarning" class="warning hidden">Harap pilih file dengan format JPG atau PNG.</p>
            
            <form>
                <div class="grid grid-cols-1 gap-4">
                    <div class="flex items-center">
                        <label class="w-1/3 text-gray-700" for="fullName">Full Name</label>
                        <input class="w-2/3 border border-gray-300 rounded-lg p-2" id="fullName" type="text"/>
                    </div>
                    <div class="flex items-center">
                        <label class="w-1/3 text-gray-700" for="email">Email</label>
                        <input class="w-2/3 border border-gray-300 rounded-lg p-2" id="email" type="email"/>
                    </div>
                    <div class="flex items-center">
                        <label class="w-1/3 text-gray-700" for="phone">Nomor Telepon</label>
                        <input class="w-2/3 border border-gray-300 rounded-lg p-2" id="phone" type="tel"/>
                    </div>
                    <div class="flex items-center">
                        <label class="w-1/3 text-gray-700" for="dob">Tanggal Lahir</label>
                        <div class="relative w-2/3">
                            <input class="w-full border border-gray-300 rounded-lg p-2" id="dob" type="text" placeholder="Pilih tanggal"/>
                            <i class="fas fa-calendar-alt absolute right-3 top-3 text-gray-500 cursor-pointer" onclick="document.getElementById('dob')._flatpickr.open();"></i>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <label class="w-1/3 text-gray-700" for="gender">Jenis Kelamin</label>
                        <select class="w-2/3 border border-gray-300 rounded-lg p-2" id="gender">
                            <option value="">Pilih</option>
                            <option value="male">Laki-laki</option>
                            <option value="female">Perempuan</option>
                        </select>
                    </div>
                    <div class="flex items-center">
                        <label class="w-1/3 text-gray-700" for="address">Alamat</label>
                        <input class="w-2/3 border border-gray-300 rounded-lg p-2" id="address" type="text"/>
                    </div>
                </div>
                <div class="flex items-center justify-center mt-6">
                    <button class="bg-blue-500 text-white rounded-lg px-20 py-2 hover:bg-blue-600" type="submit">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Initialize Flatpickr for the date input
        flatpickr("#dob", {
            dateFormat: "Y-m-d",
            allowInput: true
        });

        // Function to preview selected image and check file type
        function previewImage(event) {
            const file = event.target.files[0];
            const warning = document.getElementById('fileTypeWarning');
            
            if (file && (file.type === "image/jpeg" || file.type === "image/png")) {
                // Hide warning and display the selected image
                warning.classList.add('hidden');
                const reader = new FileReader();
                reader.onload = function() {
                    const output = document.getElementById('profileImage');
                    output.src = reader.result;
                };
                reader.readAsDataURL(file);
            } else {
                // Show warning and clear the file input if the file is not JPG/PNG
                warning.classList.remove('hidden');
                event.target.value = "";  // Reset the file input
            }
        }
    </script>
</body>
</html>