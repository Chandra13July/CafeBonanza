<body class="bg-white-100 min-h-screen flex">
    <aside class="fixed top-0 left-0 h-full w-64 bg-gray-900 text-white flex flex-col p-6 no-scrollbar overflow-y-auto transition-all duration-400">
        <div class="flex items-center mb-6">
            <img alt="Logo of Cafe Bonanza" class="w-10 h-10 rounded-full" src="<?= BASEURL; ?>/img/logo-bonanza1.png" width="50" height="50" />
            <h2 class="ml-4 text-xl font-semibold">Cafe Bonanza</h2>
        </div>

        <!-- Add Profile Link -->
        <h4 class="text-gray-400 mb-2">Profile</h4>
        <ul>
            <li class="mb-4">
                <a class="flex items-center p-3 rounded hover:bg-white hover:text-gray-900 transition" href="<?= BASEURL; ?>/home/profile">
                    <i class="fas fa-user mr-4"></i>
                    Profile Saya
                </a>
            </li>

            <!-- Existing links under Profile -->
            <li class="mb-4">
                <a class="flex items-center p-3 rounded hover:bg-white hover:text-gray-900 transition" href="<?= BASEURL; ?>/home/change-password">
                    <i class="fas fa-key mr-4"></i>
                    Ganti Password
                </a>
            </li>
            <li class="mb-4">
                <a class="flex items-center p-3 rounded hover:bg-white hover:text-gray-900 transition" href="<?= BASEURL; ?>/home/wishlist">
                    <i class="fas fa-heart mr-4"></i>
                    Wish List
                </a>
            </li>
            <li class="mb-4">
                <a class="flex items-center p-3 rounded hover:bg-white hover:text-gray-900 transition" href="<?= BASEURL; ?>/home/history">
                    <i class="fas fa-history mr-4"></i>
                    History
                </a>
            </li>
        </ul>
        <!-- Logout Button at Bottom -->
        <div class="mt-auto">
            <a class="flex items-center p-3 rounded hover:bg-white hover:text-gray-900 transition" href="<?= BASEURL; ?>/auth/logout" onclick="openLogoutModal()">
                <i class="fas fa-sign-out-alt mr-4"></i>
                Logout
            </a>
        </div>
    </aside>

    <!-- Logout Modal -->
    <div id="logout-modal" class="fixed inset-0 flex items-center justify-center z-50 hidden bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96">
            <h2 class="text-lg font-semibold mb-4">Logout Confirmation</h2>
            <p>Are you sure you want to logout?</p>
            <div class="flex justify-end mt-6">
                <button class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md mr-2" onclick="closeLogoutModal()">Cancel</button>
                <button class="bg-red-600 text-white px-4 py-2 rounded-md" onclick="confirmLogout()">Logout</button>
            </div>
        </div>
    </div>

    <script>
        function openLogoutModal() {
            document.getElementById('logout-modal').classList.remove('hidden');
        }

        function closeLogoutModal() {
            document.getElementById('logout-modal').classList.add('hidden');
        }

        function confirmLogout() {
            window.location.href = '<?= BASEURL; ?>/auth/logout';
        }
    </script>
</body>