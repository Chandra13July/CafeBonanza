<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<body class="bg-gray-100 min-h-screen flex">
    <aside class="fixed top-0 left-0 h-full w-64 bg-gray-900 text-white flex flex-col p-6 no-scrollbar overflow-y-auto transition-all duration-400">
        <div class="flex items-center mb-6">
            <img alt="Logo of Cafe Bonanza" class="w-10 h-10 rounded-full" src="<?= BASEURL; ?>/img/logo-bonanza1.png" width="50" height="50" />
            <h2 class="ml-4 text-xl font-semibold">Cafe Bonanza</h2>
        </div>

        <ul class="flex-1">
            <li class="mb-4">
                <a class="flex items-center p-3 rounded hover:bg-white hover:text-gray-900 transition" href="<?= BASEURL; ?>/dashboard/index">
                    <i class="fas fa-tachometer-alt mr-4"></i>
                    Dashboard
                </a>
            </li>
            <h4 class="text-gray-400 mb-2">Data Master</h4>
            <li class="mb-4">
                <a class="flex items-center p-3 rounded hover:bg-white hover:text-gray-900 transition" href="<?= BASEURL; ?>/employee/index">
                    <i class="fas fa-user-tie mr-4"></i>
                    Employee
                </a>
            </li>
            <li class="mb-4">
                <a class="flex items-center p-3 rounded hover:bg-white hover:text-gray-900 transition" href="<?= BASEURL; ?>/customer/index">
                    <i class="fas fa-users mr-4"></i>
                    Customer
                </a>
            </li>
            <li class="mb-4">
                <a class="flex items-center p-3 rounded hover:bg-white hover:text-gray-900 transition" href="<?= BASEURL; ?>/menu/index">
                    <i class="fas fa-utensils mr-4"></i>
                    Menu
                </a>
            </li>
            <li class="mb-4">
                <a class="flex items-center p-3 rounded hover:bg-white hover:text-gray-900 transition" href="<?= BASEURL; ?>/gallery/index">
                    <i class="fas fa-images mr-4"></i>
                    Gallery
                </a>
            </li>
            <li class="mb-4">
                <a class="flex items-center p-3 rounded hover:bg-white hover:text-gray-900 transition" href="<?= BASEURL; ?>/contact/index">
                    <i class="fas fa-envelope mr-4"></i>
                    Contact
                </a>
            </li>
            <h4 class="text-gray-400 mb-2">Report</h4>
            <li class="mb-4">
                <a class="flex items-center p-3 rounded hover:bg-white hover:text-gray-900 transition" href="<?= BASEURL; ?>/report/index">
                    <i class="fas fa-chart-line mr-4"></i>
                    Laporan Penjualan
                </a>
            </li>
        </ul>
        <div class="mt-auto">
            <button class="flex items-center p-3 rounded hover:bg-white hover:text-gray-900 transition" onclick="openLogoutModal()">
                <i class="fas fa-sign-out-alt mr-4"></i>
                Logout
            </button>
        </div>
    </aside>
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
            // Redirect to the logout URL
            window.location.href = '<?= BASEURL; ?>/auth/logoutAdmin';
        }
    </script>
</body>