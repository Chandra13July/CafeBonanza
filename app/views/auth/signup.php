<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Signup Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
</head>

<?php if (isset($_SESSION['success'])): ?>
    <div id="success-notification" class="bg-green-500 text-white p-2 rounded shadow-lg absolute top-4 right-4 text-sm z-50">
        <?= $_SESSION['success']; ?>
        <?php unset($_SESSION['success']);
        unset($_SESSION['signup_success']); ?>
    </div>
<?php endif; ?>

<body class="bg-white flex items-center justify-center h-screen">
    <div class="container relative">
        <div class="flex flex-col md:flex-row w-full h-full">
            <!-- Left Side -->
            <div class="w-full md:w-3/5 hidden md:flex items-center justify-center">
                <div class="text-center">
                    <img src="<?= BASEURL; ?>/img/auth/auth.png" alt="Illustration of two people sitting at a table, drinking coffee and talking" class="w-5/5 h-auto" width="626" height="626" />
                </div>
            </div>
            <!-- Right Side -->
            <div class="w-full md:w-2/5 flex flex-col justify-center px-4 md:px-8 max-w-md mx-auto">
                <div class="mb-4 flex items-center">
                    <img src="<?= BASEURL; ?>/img/auth/logo-bonanza.png" alt="Bonanza Logo" width="50" height="50" />
                    <span class="ml-4 text-2xl font-semibold">Cafe Bonanza</span>
                </div>
                <div class="mb-4">
                    <h1 class="text-3xl font-bold mb-2">AYO BUAT AKUNMU</h1>
                    <p class="text-gray-500">Bergabung dengan kami dan mulai cerita anda dengan kami</p>
                </div>

                <form id="signup-form" action="<?= BASEURL; ?>/auth/btnSignup" method="POST" class="flex flex-col items-center">
                    <div class="mb-4 w-full">
                        <label class="block text-gray-700" for="username">Nama</label>
                        <div class="flex items-center border border-gray-300 rounded-md p-2 w-full">
                            <i class="fas fa-user text-gray-400 mr-2"></i>
                            <input type="text" name="username" id="username" placeholder="Username" value="<?= isset($_SESSION['signup_data']['username']) ? htmlspecialchars($_SESSION['signup_data']['username']) : ''; ?>" class="w-full outline-none text-base" required />
                        </div>
                    </div>
                    <div class="mb-4 w-full">
                        <label class="block text-gray-700" for="email">Email</label>
                        <div class="flex items-center border border-gray-300 rounded-md p-2 w-full">
                            <i class="fas fa-envelope text-gray-400 mr-2"></i>
                            <input type="email" name="email" id="email" placeholder="Email" value="<?= isset($_SESSION['signup_data']['email']) ? htmlspecialchars($_SESSION['signup_data']['email']) : ''; ?>" class="w-full outline-none text-base" required />
                        </div>
                    </div>
                    <div class="mb-4 w-full relative">
                        <label class="block text-gray-700" for="password">Password</label>
                        <div class="flex items-center border border-gray-300 rounded-md p-2 w-full">
                            <i class="fas fa-lock text-gray-400 mr-2"></i>
                            <input type="password" name="password" id="password" placeholder="Password" class="w-full outline-none text-base" required />
                            <i id="toggle-password" class="fas fa-eye absolute right-2 top-9 cursor-pointer"></i>
                        </div>
                    </div>
                    <div class="mb-4 w-full relative">
                        <label class="block text-gray-700" for="confirm-password">Konfirmasi password</label>
                        <div class="flex items-center border border-gray-300 rounded-md p-2 w-full">
                            <i class="fas fa-lock text-gray-400 mr-2"></i>
                            <input type="password" name="confirm_password" id="confirm-password" placeholder="Confirm Password" class="w-full outline-none text-base" required />
                            <i id="toggle-confirm-password" class="fas fa-eye absolute right-2 top-9 cursor-pointer"></i>
                        </div>
                    </div>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="text-red-500 mb-4">
                            <?= $_SESSION['error']; ?>
                            <?php unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>

                    <button type="submit" class="w-full bg-black text-white py-2 rounded-md">DAFTAR</button>
                </form>

                <div class="text-center mt-6">
                    <p class="text-gray-500">Sudah punya akun? <a href="<?= BASEURL; ?>/auth/login" class="text-blue-500 hover:underline">Masuk</a></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Hide the success notification after a few seconds and redirect to the login page
        window.onload = function() {
            const notification = document.getElementById('success-notification');
            if (notification) {
                setTimeout(() => {
                    notification.classList.add('hidden');
                    window.location.href = "<?= BASEURL; ?>/auth/login"; // Redirect to the login page
                }, 3000); // 3000 ms = 3 seconds
            }
        };

        // Toggle Password Visibility
        const togglePassword = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            togglePassword.classList.toggle('fa-eye-slash');
        });

        const toggleConfirmPassword = document.getElementById('toggle-confirm-password');
        const confirmPasswordInput = document.getElementById('confirm-password');

        toggleConfirmPassword.addEventListener('click', () => {
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);
            toggleConfirmPassword.classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>