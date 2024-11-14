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
                                    <td class="py-3 px-4 text-center">
                                        <?= 'Rp ' . number_format($menu['Price'], 0, ',', '.'); ?>
                                    </td>
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
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Add New Menu Item</h2>
            <form id="addMenuForm" method="POST" action="<?= BASEURL; ?>/menu/btnAddMenu" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="mb-4">
                        <label for="menuName" class="block text-gray-700">Menu Name</label>
                        <input type="text" id="menuName" name="menuName" class="w-full p-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Menu Name" required>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700">Description</label>
                        <textarea id="description" name="description" class="w-full p-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Description" required></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="price" class="block text-gray-700">Price</label>
                        <input type="number" id="price" name="price" class="w-full p-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Price" required>
                    </div>
                </div>
                <div>
                    <div class="mb-4">
                        <label for="category" class="block text-gray-700">Category</label>
                        <select name="category" id="category" class="w-full p-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select Category</option>
                            <option value="Food">Food</option>
                            <option value="Drink">Drink</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="stock" class="block text-gray-700">Stock</label>
                        <input type="number" id="stock" name="stock" class="w-full p-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Stock" required>
                    </div>

                    <div class="mb-4">
                        <label for="imageUrl" class="block text-gray-700">Image</label>
                        <input type="file" id="imageUrl" name="imageUrl" class="w-full p-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
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
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Edit Menu Item</h2>
            <form id="editMenuForm" method="POST" action="<?= BASEURL; ?>/menu/btnEditMenu" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" name="MenuId" id="editMenuId" value="<?= isset($menu['MenuId']) ? $menu['MenuId'] : ''; ?>">

                <div class="mb-4">
                    <label for="menuName" class="block text-gray-700">Menu Name</label>
                    <input type="text" name="menuName" id="editMenuName" class="w-full p-2 border border-gray-300 rounded"
                        value="<?= isset($menu['MenuName']) ? htmlspecialchars($menu['MenuName']) : ''; ?>" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700">Description</label>
                    <textarea name="description" id="editDescription" class="w-full p-2 border border-gray-300 rounded" required><?= isset($menu['Description']) ? htmlspecialchars($menu['Description']) : ''; ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="price" class="block text-gray-700">Price</label>
                    <input type="number" name="price" id="editPrice" class="w-full p-2 border border-gray-300 rounded"
                        value="<?= isset($menu['Price']) ? $menu['Price'] : ''; ?>" required>
                </div>
                <div class="mb-4">
                    <label for="category" class="block text-gray-700">Category</label>
                    <select name="category" id="editCategory" class="w-full p-2 border border-gray-300 rounded" required>
                        <option value="Food" <?= isset($menu['Category']) && $menu['Category'] === 'Food' ? 'selected' : ''; ?>>Food</option>
                        <option value="Drink" <?= isset($menu['Category']) && $menu['Category'] === 'Drink' ? 'selected' : ''; ?>>Drink</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="stock" class="block text-gray-700">Stock</label>
                    <input type="number" name="stock" id="editStock" class="w-full p-2 border border-gray-300 rounded"
                        value="<?= isset($menu['Stock']) ? $menu['Stock'] : ''; ?>" required>
                </div>
                <div class="mb-4">
                    <label for="imageUrl" class="block text-gray-700">Image</label>
                    <img id="editImagePreview" src="<?= isset($menu['ImageUrl']) ? BASEURL . '/' . $menu['ImageUrl'] : ''; ?>" alt="Menu Image" class="w-20 h-20 rounded-full mx-auto mb-2">
                    <input type="file" name="imageUrl" class="w-full p-2 border border-gray-300 rounded">
                </div>
                <div class="flex justify-end col-span-2">
                    <button type="button" onclick="closeEditModal()" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Menu Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white rounded-lg w-3/4 max-w-4xl p-6">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Delete Menu Item</h2>
            <p class="mb-4 text-gray-600">Are you sure you want to delete this menu item?</p>
            <form id="deleteMenuForm" method="POST" action="<?= BASEURL; ?>/menu/btnDeleteMenu">
                <input type="hidden" name="MenuId" id="deleteMenuId">
                <div class="flex justify-end">
                    <button type="button" onclick="closeDeleteModal()" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
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
            document.getElementById('editMenuId').value = menu.MenuId;
            document.getElementById('editMenuName').value = menu.MenuName;
            document.getElementById('editDescription').value = menu.Description;
            document.getElementById('editPrice').value = menu.Price;
            document.getElementById('editCategory').value = menu.Category;
            document.getElementById('editStock').value = menu.Stock;
            const imageUrl = menu.ImageUrl ? "<?= BASEURL; ?>/" + menu.ImageUrl : "";
            document.getElementById('editImagePreview').src = imageUrl;
            editModal.classList.remove('hidden');
        }

        function closeEditModal() {
            editModal.classList.add('hidden');
        }

        function openDeleteModal(menuId) {
            document.getElementById('deleteMenuId').value = menuId;
            deleteModal.classList.remove('hidden');
        }

        function closeDeleteModal() {
            deleteModal.classList.add('hidden');
        }

        window.onload = function() {
            const successNotification = document.getElementById('success-notification');
            if (successNotification) {
                setTimeout(() => {
                    successNotification.style.display = 'none';
                }, 2000);
            }
            const errorNotification = document.getElementById('error-notification');
            if (errorNotification) {
                setTimeout(() => {
                    errorNotification.style.display = 'none';
                }, 2000);
            }
        }
    </script>

</body>