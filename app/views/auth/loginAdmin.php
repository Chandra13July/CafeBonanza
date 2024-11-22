<body class="bg-white flex items-center justify-center h-screen">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-green-500 text-white p-2 rounded shadow-lg absolute top-4 right-4 text-sm z-50" id="success-notification">
            <?= $_SESSION['success']; ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="bg-yellow-500 text-white p-2 rounded shadow-lg absolute top-4 right-4 text-sm z-50" id="flash-notification">
            <?= $_SESSION['flash_message']; ?>
            <?php unset($_SESSION['flash_message']); ?>
        </div>
    <?php endif; ?>

    <div class="container relative">
        <div class="flex flex-col md:flex-row w-full h-full">
            <div class="w-full md:w-3/5 hidden md:flex items-center justify-center">
                <div class="text-center">
                    <img class="w-full h-auto" src="<?= BASEURL; ?>/img/auth2.png" alt="Auth Illustration" />
                </div>
            </div>
            <div class="w-full md:w-2/5 flex flex-col justify-center px-4 md:px-8 max-w-md mx-auto">
                <div class="mb-4 flex items-center">
                    <img alt="Bonanza Logo" height="50" src="<?= BASEURL; ?>/img/logo-bonanza.png" width="50" />
                    <span class="ml-4 text-2xl font-semibold">Cafe Bonanza</span>
                </div>
                <div class="mb-4">
                    <h1 class="text-3xl font-bold mb-2">LOG IN TO YOUR ACCOUNT</h1>
                    <p class="text-gray-500">Welcome! Please log in to continue</p>
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
                        <label class="block text-gray-700" for="password">Password</label>
                        <div class="flex items-center border border-gray-300 rounded-md p-2 w-full relative">
                            <i class="fas fa-lock text-gray-400 mr-2"></i>
                            <input class="w-full outline-none text-base" id="password" name="password" placeholder="Password" required type="password" />
                            <i class="fas fa-eye text-gray-400 absolute right-2 top-1/2 transform -translate-y-1/2 cursor-pointer" id="toggle-password"></i>
                        </div>
                        <div class="text-right mt-2">
                            <a href="<?= BASEURL; ?>/auth/forgotAdmin" class="text-blue-500 text-sm hover:underline">Forgot Password?</a>
                        </div>
                    </div>
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="text-left text-red-500 mb-4">
                            <?= $_SESSION['error']; ?>
                            <?php unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>
                    <button class="w-full bg-black text-white py-2 rounded-md" type="submit">
                        LOG IN
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            const successNotification = document.getElementById('success-notification');
            if (successNotification) {
                setTimeout(() => {
                    successNotification.classList.add('hidden');
                    window.location.href = "<?= isset($_SESSION['redirect_url']) ? BASEURL . $_SESSION['redirect_url'] : BASEURL . '/auth/loginAdmin'; ?>";
                }, 2000);
            }

            const flashNotification = document.getElementById('flash-notification');
            if (flashNotification) {
                setTimeout(() => {
                    flashNotification.classList.add('hidden');
                }, 2000);
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