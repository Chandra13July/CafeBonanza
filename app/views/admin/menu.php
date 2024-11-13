<html lang="en">

<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-100 flex">

    <?php if (isset($_SESSION['success'])): ?>
        <div id="success-notification" class="bg-green-500 text-white p-2 rounded shadow-lg absolute top-4 right-4 text-sm z-50">
            <?= htmlspecialchars($_SESSION['success']); ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div id="error-notification" class="bg-red-500 text-white p-2 rounded shadow-lg absolute top-4 right-4 text-sm z-50">
            <?= htmlspecialchars($_SESSION['error']); ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="flex-1 ml-64 p-4">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="flex justify-between items-center p-4">
                <input class="w-1/3 p-2 border border-gray-300 rounded" placeholder="Search..." type="text" />
                <button class="bg-green-500 text-white px-4 py-2 rounded" onclick="openAddModal()">Add Menu</button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white table-auto">
                    <thead>
                        <tr class="bg-gray-100 text-left text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-4 font-thin text-center">No</th>
                            <th class="py-3 px-4 font-thin text-center">MenuName</th>
                            <th class="py-3 px-4 font-thin text-center">Description</th>
                            <th class="py-3 px-4 font-thin text-center">Price</th>
                            <th class="py-3 px-4 font-thin text-center">Stock</th>
                            <th class="py-3 px-4 font-thin text-center">Category</th>
                            <th class="py-3 px-4 font-thin text-center">Image</th>
                            <th class="py-3 px-4 font-thin text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['menu'])): ?>
                            <?php foreach ($data['menu'] as $index => $menu): ?>
                                <tr class="text-sm text-gray-600">
                                    <td class="py-3 px-4 text-center"><?= $index + 1 ?></td>
                                    <td class="py-3 px-4 text-center"><?= htmlspecialchars($menu['MenuName']) ?></td>
                                    <td class="py-3 px-4 text-center"><?= htmlspecialchars($menu['Description']) ?></td>
                                    <td class="py-3 px-4 text-center"><?= htmlspecialchars($menu['Price']) ?></td>
                                    <td class="py-3 px-4 text-center"><?= htmlspecialchars($menu['Stock']) ?></td>
                                    <td class="py-3 px-4 text-center"><?= htmlspecialchars($menu['Category']) ?></td>
                                    <td class="py-3 px-4 text-center">
                                        <img src="<?= BASEURL; ?>/<?= htmlspecialchars($menu['ImageUrl']) ?>" alt="Menu Image" class="w-12 h-12 object-cover mx-auto">
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <button class="bg-blue-500 text-white px-4 py-1 rounded-md hover:bg-blue-700 transition duration-200" onclick="openEditModal(<?= htmlspecialchars(json_encode($menu), ENT_QUOTES, 'UTF-8'); ?>)">
                                            <i class="fas fa-pen text-base"></i>
                                        </button>
                                        <button class="bg-red-500 text-white px-4 py-1 rounded-md hover:bg-red-700 transition duration-200 ml-2" onclick="openDeleteModal(<?= $menu['MenuId']; ?>)">
                                            <i class="fas fa-trash text-base"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="py-3 px-4 text-center text-gray-500">No menu data available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Menu Modal Form -->
    <div id="addModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white rounded-lg w-3/4 max-w-4xl p-6">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Add New Menu</h2>
            <form id="addMenuForm" method="POST" action="<?= BASEURL; ?>/menu/btnAddMenu" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Kiri: Nama Menu, Deskripsi, Harga, Stock -->
                <div class="md:col-span-1">
                    <div class="mb-4">
                        <label for="menuName" class="block text-gray-700">Nama Menu</label>
                        <input type="text" id="menuName" name="menuName" class="w-full p-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nama Menu" required>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700">Deskripsi</label>
                        <textarea id="description" name="description" class="w-full p-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Deskripsi Menu" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="price" class="block text-gray-700">Harga</label>
                        <input type="number" id="price" name="price" class="w-full p-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Harga Menu" required>
                    </div>
                    <div class="mb-4">
                        <label for="stock" class="block text-gray-700">Stock</label>
                        <input type="number" id="stock" name="stock" class="w-full p-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Jumlah Stock" required>
                    </div>
                </div>

                <!-- Kanan: Kategori, Foto -->
                <div class="md:col-span-1">
                    <div class="mb-4">
                        <label for="category" class="block text-gray-700">Kategori</label>
                        <select name="category" id="category" class="w-full p-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Pilih Kategori</option>
                            <option value="Food">Food</option>
                            <option value="Drinks">Drinks</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="imageUrl" class="block text-gray-700">Foto</label>
                        <input type="file" id="imageUrl" name="imageUrl" class="w-full p-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex justify-end col-span-2">
                    <button type="button" onclick="closeAddModal()" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>

<!-- Edit Menu Modal Form -->
<div id="editModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex justify-center items-center">
    <div class="bg-white rounded-lg w-3/4 max-w-4xl p-6">
        <h2 class="text-2xl font-semibold text-gray-700 mb-4">Edit Menu</h2>
        <form id="editMenuForm" method="POST" action="<?= BASEURL; ?>/menu/btnEditMenu" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="hidden" name="MenuId" id="MenuId" value="<?= isset($menu['MenuId']) ? $menu['MenuId'] : ''; ?>">

            <!-- Kiri: Nama Menu, Deskripsi, Harga, Stock -->
            <div class="md:col-span-1">
                <div class="mb-4">
                    <label for="menuName" class="block text-gray-700">Nama Menu</label>
                    <input type="text" name="menuName" id="menuName" class="w-full p-2 border border-gray-300 rounded"
                        value="<?= isset($menu['menuName']) ? htmlspecialchars($menu['menuName']) : ''; ?>" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700">Deskripsi</label>
                    <textarea name="description" id="description" class="w-full p-2 border border-gray-300 rounded" required><?= isset($menu['description']) ? htmlspecialchars($menu['description']) : ''; ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="price" class="block text-gray-700">Harga</label>
                    <input type="number" name="price" id="price" class="w-full p-2 border border-gray-300 rounded"
                        value="<?= isset($menu['price']) ? $menu['price'] : ''; ?>" required>
                </div>
                <div class="mb-4">
                    <label for="stock" class="block text-gray-700">Stock</label>
                    <input type="number" name="stock" id="stock" class="w-full p-2 border border-gray-300 rounded"
                        value="<?= isset($menu['stock']) ? $menu['stock'] : ''; ?>" required>
                </div>
            </div>

            <!-- Kanan: Kategori, Foto -->
            <div class="md:col-span-1">
                <div class="mb-4">
                    <label for="category" class="block text-gray-700">Kategori</label>
                    <select name="category" id="category" class="w-full p-2 border border-gray-300 rounded" required>
                        <option value="Food" <?= isset($menu['category']) && $menu['category'] === 'Food' ? 'selected' : ''; ?>>Food</option>
                        <option value="Drinks" <?= isset($menu['category']) && $menu['category'] === 'Drinks' ? 'selected' : ''; ?>>Drinks</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="imageUrl" class="block text-gray-700">Foto</label>
                    <img id="imagePreview" src="<?= isset($menu['imageUrl']) ? BASEURL . '/' . $menu['imageUrl'] : ''; ?>" alt="Menu Image" class="w-20 h-20 rounded-full mx-auto mb-2">
                    <input type="file" name="imageUrl" class="w-full p-2 border border-gray-300 rounded">
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex justify-end col-span-2">
                <button type="button" onclick="closeEditModal()" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Save</button>
            </div>
        </form>
    </div>
</div>

    <!-- Modal Hapus Menu -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white rounded-lg w-3/4 max-w-4xl p-6">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Hapus Menu</h2>
            <p class="mb-4 text-gray-600">Apakah Anda yakin ingin menghapus menu ini?</p>
            <form id="deleteMenuForm" method="POST" action="<?= BASEURL; ?>/menu/btnDeleteMenu">
                <input type="hidden" name="MenuId" id="deleteMenuId">
                <div class="flex justify-end">
                    <button type="button" onclick="closeDeleteModal()" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Batal</button>
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const deleteModal = document.getElementById('deleteModal');
        const addModal = document.getElementById('addModal');
        const editModal = document.getElementById('editModal');

        function openAddModal() {
            addModal.classList.remove('hidden');
        }

        function closeAddModal() {
            addModal.classList.add('hidden');
        }

        function openEditModal(menu) {
            document.getElementById('MenuId').value = menu.MenuId; // pastikan 'menuId' adalah ID input tersembunyi di form
            document.getElementById('menuName').value = menu.MenuName;
            document.getElementById('description').value = menu.Description;
            document.getElementById('price').value = menu.Price;
            document.getElementById('stock').value = menu.Stock;
            document.getElementById('category').value = menu.Category;

            // Handle the image preview, if an image URL is provided
            const imageUrl = menu.ImageUrl ? "<?= BASEURL; ?>/" + menu.ImageUrl : "";
            document.getElementById('imagePreview').src = imageUrl;

            // Show the modal by removing the 'hidden' class
            editModal.classList.remove('hidden');
        }

        function closeEditModal() {
            const editModal = document.getElementById('editModal'); // Ensure this is properly selected
            editModal.classList.add('hidden');
        }

        function openDeleteModal(menuid) {
            document.getElementById('deleteMenuId').value = menuid;
            deleteModal.classList.remove('hidden');
        }

        function closeDeleteModal() {
            deleteModal.classList.add('hidden');
        }

        window.onload = function() {
            // Check for success notification
            const successNotification = document.getElementById('success-notification');
            if (successNotification) {
                setTimeout(() => {
                    successNotification.style.display = 'none';
                }, 2000); // Hide after 2 seconds
            }

            // Check for error notification
            const errorNotification = document.getElementById('error-notification');
            if (errorNotification) {
                setTimeout(() => {
                    errorNotification.style.display = 'none';
                }, 2000); // Hide after 2 seconds
            }
        }
    </script>
</body>

</html>