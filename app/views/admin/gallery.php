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
                <button class="bg-green-500 text-white px-4 py-2 rounded" onclick="openAddModal()">Add Gallery</button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white table-auto">
                    <thead>
                        <tr class="bg-gray-100 text-left text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-4 font-thin text-center">No</th>
                            <th class="py-3 px-4 font-thin text-center">Title</th>
                            <th class="py-3 px-4 font-thin text-center">Description</th>
                            <th class="py-3 px-4 font-thin text-center">Image</th>
                            <th class="py-3 px-4 font-thin text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['gallery'])): ?>
                            <?php foreach ($data['gallery'] as $index => $gallery): ?>
                                <tr class="text-sm text-gray-600">
                                    <td class="py-3 px-4 text-center"><?= $index + 1 ?></td>
                                    <td class="py-3 px-4 text-center"><?= htmlspecialchars($gallery['Title']) ?></td>
                                    <td class="py-3 px-4 text-center" style="word-wrap: break-word; max-width: 200px; text-align: center; vertical-align: middle;">
                                        <?= htmlspecialchars($gallery['Description']) ?>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <img src="<?= BASEURL; ?>/<?= htmlspecialchars($gallery['ImageUrl']) ?>" alt="Gallery Image" class="w-12 h-12 object-cover mx-auto">
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <button class="bg-blue-500 text-white px-4 py-1 rounded-md hover:bg-blue-700 transition duration-200" onclick="openEditModal(<?= htmlspecialchars(json_encode($gallery), ENT_QUOTES, 'UTF-8'); ?>)">
                                            <i class="fas fa-pen text-base"></i>
                                        </button>
                                        <button class="bg-red-500 text-white px-4 py-1 rounded-md hover:bg-red-700 transition duration-200 ml-2" onclick="openDeleteModal(<?= $gallery['GalleryId']; ?>)">
                                            <i class="fas fa-trash text-base"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="py-3 px-4 text-center text-gray-500">No gallery data available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Gallery Modal Form -->
    <div id="addModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white rounded-lg w-3/4 max-w-4xl p-6">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Add New Gallery Item</h2>
            <form id="addGalleryForm" method="POST" action="<?= BASEURL; ?>/gallery/btnAddGallery" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="mb-4">
                        <label for="title" class="block text-gray-700">Title</label>
                        <input type="text" id="title" name="title" class="w-full p-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Gallery Title" required>
                    </div>
                    <div class="mb-4">
                        <label for="imageUrl" class="block text-gray-700">Image</label>
                        <input type="file" id="imageUrl" name="imageUrl" class="w-full p-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div>
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700">Description</label>
                        <textarea id="description" name="description" class="w-full p-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Gallery Description" required></textarea>
                    </div>
                </div>
                <div class="flex justify-end col-span-2">
                    <button type="button" onclick="closeAddModal()" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Gallery Modal Form -->
    <div id="editModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white rounded-lg w-3/4 max-w-4xl p-6">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Edit Gallery Item</h2>
            <form id="editGalleryForm" method="POST" action="<?= BASEURL; ?>/gallery/btnEditGallery" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" name="GalleryId" id="editGalleryId" value="<?= isset($gallery['GalleryId']) ? $gallery['GalleryId'] : ''; ?>">

                <div>
                    <div class="mb-4">
                        <label for="title" class="block text-gray-700">Title</label>
                        <input type="text" name="title" id="editTitle" class="w-full p-2 border border-gray-300 rounded"
                            value="<?= isset($gallery['Title']) ? htmlspecialchars($gallery['Title']) : ''; ?>" required>
                    </div>
                    <div class="mb-4">
                        <label for="imageUrl" class="block text-gray-700">Image</label>
                        <img id="editImagePreview" src="<?= isset($gallery['ImageUrl']) ? BASEURL . '/' . $gallery['ImageUrl'] : ''; ?>" alt="Gallery Image" class="w-20 h-20 rounded-full mx-auto mb-2">
                        <input type="file" name="imageUrl" class="w-full p-2 border border-gray-300 rounded">
                    </div>
                </div>
                <div>
                    <div class="mb-4">
                        <label for="description" class="block text-gray-700">Description</label>
                        <textarea name="description" id="editDescription" class="w-full p-2 border border-gray-300 rounded" required><?= isset($gallery['Description']) ? htmlspecialchars($gallery['Description']) : ''; ?></textarea>
                    </div>
                </div>
                <div class="flex justify-end col-span-2">
                    <button type="button" onclick="closeEditModal()" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Gallery Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white rounded-lg w-3/4 max-w-4xl p-6">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Delete Gallery Item</h2>
            <p class="mb-4 text-gray-600">Are you sure you want to delete this gallery item?</p>
            <form id="deleteGalleryForm" method="POST" action="<?= BASEURL; ?>/gallery/btnDeleteGallery">
                <input type="hidden" name="GalleryId" id="deleteGalleryId">
                <div class="flex justify-end">
                    <button type="button" onclick="closeDeleteModal()" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="//cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script>
        let table = new DataTable('table');
        const deleteModal = document.getElementById('deleteModal');
        const addModal = document.getElementById('addModal');
        const editModal = document.getElementById('editModal');

        function openAddModal() {
            addModal.classList.remove('hidden');
        }

        function closeAddModal() {
            addModal.classList.add('hidden');
        }

        function openEditModal(gallery) {
            document.getElementById('editGalleryId').value = gallery.GalleryId;
            document.getElementById('editTitle').value = gallery.Title;
            document.getElementById('editDescription').value = gallery.Description;
            const imageUrl = gallery.ImageUrl ? "<?= BASEURL; ?>/" + gallery.ImageUrl : "";
            document.getElementById('editImagePreview').src = imageUrl;
            editModal.classList.remove('hidden');
        }

        function closeEditModal() {
            editModal.classList.add('hidden');
        }

        function openDeleteModal(galleryId) {
            document.getElementById('deleteGalleryId').value = galleryId;
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