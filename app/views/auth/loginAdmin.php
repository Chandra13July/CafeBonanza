<body class="bg-white flex items-center justify-center h-screen">
    <!-- Notifikasi Sukses -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-green-500 text-white p-2 rounded shadow-lg absolute top-4 right-4 text-sm z-50" id="success-notification">
            <?= $_SESSION['success']; ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <!-- Notifikasi Flash Message -->
    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="bg-yellow-500 text-white p-2 rounded shadow-lg absolute top-4 right-4 text-sm z-50" id="flash-notification">
            <?= $_SESSION['flash_message']; ?>
            <?php unset($_SESSION['flash_message']); ?>
        </div>
    <?php endif; ?>

    <div class="container relative">
        <div class="flex flex-col md:flex-row w-full h-full">
            <!-- Left Side -->
            <div class="w-full md:w-3/5 hidden md:flex items-center justify-center">
                <div class="text-center">
                    <img class="w-5/5 h-auto" height="626" src="<?= BASEURL; ?>/img/auth2.png" width="626" />
                </div>
            </div>

            <!-- Right Side -->
            <div class="w-full md:w-2/5 flex flex-col justify-center px-4 md:px-8 max-w-md mx-auto">
                <div class="mb-4 flex items-center">
                    <img alt="Bonanza Logo" height="50" src="<?= BASEURL; ?>/img/logo-bonanza.png" width="50" />
                    <span class="ml-4 text-2xl font-semibold">Cafe Bonanza</span>
                </div>
                <div class="mb-4">
                    <h1 class="text-3xl font-bold mb-2">MASUK KE AKUNMU</h1>
                    <p class="text-gray-500">Selamat datang! Masuk untuk melanjutkan</p>
                </div>
                <form action="<?= BASEURL; ?>/auth/btnLoginAdmin" class="flex flex-col items-center" id="login-form" method="POST">
                    <div class="mb-4 w-full">
                        <label class="block text-gray-700" for="email">Email</label>
                        <div class="flex items-center border border-gray-300 rounded-md p-2 w-full">
                            <i class="fas fa-envelope text-gray-400 mr-2"></i>
                            <input class="w-full outline-none text-base" id="email" name="email" placeholder="Email" required type="email" />
                        </div>
                    </div>
                    <div class="mb-4 w-full relative">
                        <label class="block text-gray-700" for="password">Kata Sandi</label>
                        <div class="flex items-center border border-gray-300 rounded-md p-2 w-full">
                            <i class="fas fa-lock text-gray-400 mr-2"></i>
                            <input class="w-full outline-none text-base" id="password" name="password" placeholder="Sandi" required type="password" />
                            <i class="fas fa-eye text-gray-400 absolute right-2 top-9 cursor-pointer" id="toggle-password"></i>
                        </div>
                    </div>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="text-red-500 mb-4 w-full">
                            <div class="flex items-center">
                                <!-- Icon Cross (X) -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 18L18 6M6 6l12 12"></path>
                                </svg>

                                <!-- Error Message -->
                                <span><?= $_SESSION['error']; ?></span>
                            </div>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <button class="w-full bg-black text-white py-2 rounded-md" type="submit">
                        MASUK
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            const notification = document.getElementById('success-notification');
            const redirectUrl = "<?= isset($_SESSION['redirect_url']) ? BASEURL . $_SESSION['redirect_url'] : BASEURL . '/auth/loginAdmin'; ?>";
            <?php unset($_SESSION['redirect_url']); ?>

            if (notification) {
                setTimeout(() => {
                    notification.classList.add('hidden');
                    window.location.href = redirectUrl;
                }, 3000); // 3 detik
            }
        };

        const togglePassword = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            togglePassword.classList.toggle('fa-eye-slash');
        });
    </script>
</body>