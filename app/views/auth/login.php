<?php if (isset($_SESSION['success'])): ?>
    <div id="success-notification" class="bg-green-500 text-white p-2 rounded shadow-lg absolute top-4 right-4 text-sm z-50">
        <?= $_SESSION['success']; ?>
        <?php unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<body class="bg-white flex items-center justify-center h-screen">
    <div class="container relative">
        <div class="flex flex-col md:flex-row w-full h-full">
            <div class="w-full md:w-3/5 hidden md:flex items-center justify-center">
                <div class="text-center">
                    <img src="<?= BASEURL; ?>/img/auth1.png" alt="Illustration of two people sitting at a table, drinking coffee and talking" class="w-5/5 h-auto" width="626" height="626" />
                </div>
            </div>
            <div class="w-full md:w-2/5 flex flex-col justify-center px-4 md:px-8 max-w-md mx-auto">
                <div class="mb-4 flex items-center">
                    <img src="<?= BASEURL; ?>/img/logo-bonanza.png" alt="Bonanza Logo" width="50" height="50" />
                    <span class="ml-4 text-2xl font-semibold">Cafe Bonanza</span>
                </div>
                <div class="mb-4">
                    <h1 class="text-3xl font-bold mb-2">LOGIN TO YOUR ACCOUNT</h1>
                    <p class="text-gray-500">Welcome back! Log in to your account to continue.</p>
                </div>
                <form id="login-form" action="<?= BASEURL; ?>/auth/btnLogin" method="POST" class="flex flex-col items-center">
                    <div class="mb-4 w-full">
                        <label class="block text-gray-700" for="email">Email</label>
                        <div class="flex items-center border border-gray-300 rounded-md p-2 w-full">
                            <i class="fas fa-envelope text-gray-400 mr-2"></i>
                            <input type="email" name="email" id="email" placeholder="Email" value="<?= isset($_SESSION['login_data']['email']) ? htmlspecialchars($_SESSION['login_data']['email']) : ''; ?>" class="w-full outline-none text-base" required />
                        </div>
                    </div>
                    <div class="mb-4 w-full relative">
                        <label class="block text-gray-700" for="password">Password</label>
                        <div class="flex items-center border border-gray-300 rounded-md p-2 w-full">
                            <i class="fas fa-lock text-gray-400 mr-2"></i>
                            <input type="password" name="password" id="password" placeholder="Password" class="w-full outline-none text-base" required />
                            <i id="toggle-password" class="fas fa-eye text-gray-400 absolute right-2 top-9 cursor-pointer"></i>
                        </div>
                        <div class="text-right mt-2">
                            <a href="<?= BASEURL; ?>/auth/forgot" class="text-blue-500 text-sm hover:underline">Forgot Password?</a>
                        </div>
                    </div>
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="text-left text-red-500 mb-4">
                            <?= $_SESSION['error']; ?>
                            <?php unset($_SESSION['error']); ?>
                        </div>
                    <?php endif; ?>
                    <button type="submit" class="w-full bg-black text-white py-2 rounded-md">LOGIN</button>
                </form>
                <div class="text-center mt-6">
                    <p class="text-gray-500">Don't have an account? <a href="<?= BASEURL; ?>/auth/signup" class="text-blue-500 hover:underline">Sign Up</a></p>
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
                    window.location.href = "<?= BASEURL; ?>/home"; 
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