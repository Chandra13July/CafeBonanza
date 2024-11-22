<?php if (isset($_SESSION['success'])): ?>
    <div id="success-notification" class="bg-green-500 text-white p-2 rounded shadow-lg absolute top-4 right-4 text-sm z-50">
        <?= $_SESSION['success']; ?>
        <?php unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<body class="bg-white flex items-center justify-center min-h-screen">
    <div class="container relative">
        <div class="flex flex-col md:flex-row w-full h-full">
            <div class="w-full md:w-3/5 hidden md:flex items-center justify-center">
                <div class="text-center">
                    <img src="<?= BASEURL; ?>/img/auth1.png" alt="Illustration of two people sitting at a table, drinking coffee and talking" class="w-full h-auto" />
                </div>
            </div>
            <div class="w-full md:w-2/5 flex flex-col justify-center px-4 md:px-8 max-w-md mx-auto">
                <div class="mb-6 flex items-center">
                    <img src="<?= BASEURL; ?>/img/logo-bonanza.png" alt="Bonanza Logo" class="h-12 w-12" />
                    <span class="ml-4 text-2xl font-semibold">Cafe Bonanza</span>
                </div>
                <div class="mb-6">
                    <h1 class="text-3xl font-bold mb-2 text-gray-800">RESET PASSWORD</h1>
                    <p class="text-gray-500">Enter your new password.</p>
                </div>
                <form id="reset-password-form" action="<?= BASEURL; ?>/auth/btnResetPassword" method="POST" class="flex flex-col gap-2.5">
                    <div class="mb-2.5 w-full relative">
                        <label class="block text-gray-700" for="password">New Password</label>
                        <div class="flex items-center border border-gray-300 rounded-md p-2 w-full relative">
                            <i class="fas fa-lock text-gray-400 mr-2"></i>
                            <input type="password" name="password" id="password" placeholder="New Password" class="w-full outline-none text-base" required />
                            <i id="toggle-password" class="fas fa-eye cursor-pointer text-gray-400 absolute right-2" onclick="togglePassword('password')"></i>
                        </div>
                    </div>
                    <div class="mt-1 mb-3 w-full relative">
                        <label class="block text-gray-700" for="confirm-password">Confirm Password</label>
                        <div class="flex items-center border border-gray-300 rounded-md p-2 w-full relative">
                            <i class="fas fa-lock text-gray-400 mr-2"></i>
                            <input type="password" name="confirm_password" id="confirm-password" placeholder="Confirm Password" class="w-full outline-none text-base" required />
                            <i id="toggle-confirm-password" class="fas fa-eye cursor-pointer text-gray-400 absolute right-2" onclick="togglePassword('confirm-password')"></i>
                        </div>
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="text-left text-red-500 mt-2">
                                <?= $_SESSION['error']; ?>
                                <?php unset($_SESSION['error']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="w-full bg-black text-white py-3 rounded-md hover:bg-gray-800 transition duration-300">RESET PASSWORD</button>
                </form>
                <div class="text-center mt-6">
                    <p class="text-gray-500">
                        Back to <a href="<?= BASEURL; ?>/auth/login" class="text-blue-500 hover:underline">Login?</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            const notification = document.getElementById('success-notification');
            if (notification) {
                setTimeout(() => {
                    notification.classList.add('hidden');
                    window.location.href = "<?= BASEURL; ?>/auth/login";
                }, 2000);
            }
        };

        function togglePassword(id) {
            const passwordField = document.getElementById(id);
            const toggleIcon = document.getElementById('toggle-' + id);

            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }
    </script>
</body>