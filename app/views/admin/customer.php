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
                <button class="bg-green-500 text-white px-4 py-2 rounded" onclick="openAddModal()">Add Customer</button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white table-auto">
                    <thead>
                        <tr class="bg-gray-100 text-left text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-4 font-thin text-center">No</th>
                            <th class="py-3 px-4 font-thin text-center">Username</th>
                            <th class="py-3 px-4 font-thin text-center">Email</th>
                            <th class="py-3 px-4 font-thin text-center">Phone</th>
                            <th class="py-3 px-4 font-thin text-center">Gender</th>
                            <th class="py-3 px-4 font-thin text-center">Date of Birth</th>
                            <th class="py-3 px-4 font-thin text-center">Address</th>
                            <th class="py-3 px-4 font-thin text-center">Image</th>
                            <th class="py-3 px-4 font-thin text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['customer'])): ?>
                            <?php foreach ($data['customer'] as $index => $customer): ?>
                                <tr class="text-sm text-gray-600">
                                    <td class="py-3 px-4 text-center"><?= $index + 1 ?></td>
                                    <td class="py-3 px-4 text-center"><?= htmlspecialchars($customer['Username']) ?></td>
                                    <td class="py-3 px-4 text-center"><?= htmlspecialchars($customer['Email']) ?></td>
                                    <td class="py-3 px-4 text-center"><?= htmlspecialchars($customer['Phone']) ?></td>
                                    <td class="py-3 px-4 text-center"><?= htmlspecialchars($customer['Gender']) ?></td>
                                    <td class="py-3 px-4 text-center">
                                        <?= !empty($customer['DateOfBirth']) ? date("d F Y", strtotime($customer['DateOfBirth'])) : 'N/A' ?>
                                    </td>
                                    <td class="py-3 px-4 text-center"><?= htmlspecialchars($customer['Address']) ?></td>
                                    <td class="py-3 px-4 text-center">
                                        <img src="<?= BASEURL; ?>/<?= htmlspecialchars($customer['ImageUrl']) ?>" alt="Employee Image" class="w-12 h-12 rounded-full mx-auto">
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <button class="bg-blue-500 text-white px-4 py-1 rounded-md hover:bg-blue-700 transition duration-200" onclick="openEditModal(<?= htmlspecialchars(json_encode($customer), ENT_QUOTES, 'UTF-8'); ?>)">
                                            <i class="fas fa-pen text-base"></i>
                                        </button>
                                        <button class="bg-red-500 text-white px-4 py-1 rounded-md hover:bg-red-700 transition duration-200 ml-2" onclick="openDeleteModal(<?= $customer['CustomerId']; ?>)">
                                            <i class="fas fa-trash text-base"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="py-3 px-4 text-center text-gray-500">No employee data available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Customer Modal Form -->
    <div id="addModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white rounded-lg w-3/4 max-w-4xl p-6">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Add New Customer</h2>
            <form id="addCustomerForm" method="POST" action="<?= BASEURL; ?>/customer/btnAddCustomer" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="mb-4">
                        <label for="username" class="block text-gray-700">Username</label>
                        <input type="text" id="username" name="username" class="w-full p-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Username" required>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-gray-700">Email</label>
                        <input type="email" id="email" name="email" class="w-full p-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Email" required>
                    </div>

                    <div class="mb-4">
                        <label for="phone" class="block text-gray-700">Phone</label>
                        <input type="text" id="phone" name="phone" class="w-full p-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Phone" required>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-gray-700">Password</label>
                        <input type="password" id="password" name="password" class="w-full p-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Password" required>
                    </div>
                </div>

                <div>
                    <div class="mb-4">
                        <label for="gender" class="block text-gray-700">Gender</label>
                        <select name="gender" id="gender" class="w-full p-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="dateOfBirth" class="block text-gray-700">Date of Birth</label>
                        <input type="date" id="dateOfBirth" name="dateOfBirth" class="w-full p-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <div class="mb-4">
                        <label for="address" class="block text-gray-700">Address</label>
                        <textarea id="address" name="address" class="w-full p-2 border-2 border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Address" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="imageUrl" class="block text-gray-700">Photo</label>
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

    <!-- Edit Customer Modal Form -->
    <div id="editModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white rounded-lg w-3/4 max-w-4xl p-6">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Edit Customer</h2>
            <form id="editCustomerForm" method="POST" action="<?= BASEURL; ?>/customer/btnEditCustomer" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" name="CustomerId" id="editCustomerId" value="<?= isset($customer['CustomerId']) ? $customer['CustomerId'] : ''; ?>">

                <div class="mb-4">
                    <label for="username" class="block text-gray-700">Username</label>
                    <input type="text" name="username" id="editUsername" class="w-full p-2 border border-gray-300 rounded"
                        value="<?= isset($customer['Username']) ? htmlspecialchars($customer['Username']) : ''; ?>" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" name="email" id="editEmail" class="w-full p-2 border border-gray-300 rounded"
                        value="<?= isset($customer['Email']) ? htmlspecialchars($customer['Email']) : ''; ?>" required>
                </div>
                <div class="mb-4">
                    <label for="phone" class="block text-gray-700">Phone</label>
                    <input type="text" name="phone" id="editPhone" class="w-full p-2 border border-gray-300 rounded"
                        value="<?= isset($customer['Phone']) ? htmlspecialchars($customer['Phone']) : ''; ?>" required>
                </div>
                <div class="mb-4">
                    <label for="gender" class="block text-gray-700">Gender</label>
                    <select name="gender" id="editGender" class="w-full p-2 border border-gray-300 rounded" required>
                        <option value="Male" <?= isset($customer['Gender']) && $customer['Gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?= isset($customer['Gender']) && $customer['Gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="dateOfBirth" class="block text-gray-700">Date of Birth</label>
                    <input type="date" name="dateOfBirth" id="editDateOfBirth" class="w-full p-2 border border-gray-300 rounded"
                        value="<?= isset($customer['DateOfBirth']) ? $customer['DateOfBirth'] : ''; ?>" required>
                </div>
                <div class="mb-4">
                    <label for="address" class="block text-gray-700">Address</label>
                    <textarea name="address" id="editAddress" class="w-full p-2 border border-gray-300 rounded" required><?= isset($customer['Address']) ? htmlspecialchars($customer['Address']) : ''; ?></textarea>
                </div>
                <div class="mb-4">
                    <label for="imageUrl" class="block text-gray-700">Photo</label>
                    <img id="editImagePreview" src="<?= isset($customer['ImageUrl']) ? BASEURL . '/' . $customer['ImageUrl'] : ''; ?>" alt="Customer Image" class="w-20 h-20 rounded-full mx-auto mb-2">
                    <input type="file" name="imageUrl" class="w-full p-2 border border-gray-300 rounded">
                </div>
                <div class="flex justify-end col-span-2">
                    <button type="button" onclick="closeEditModal()" class="bg-gray-500 text-white px-4 py-2 rounded mr-2">Cancel</button>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Hapus Customer -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white rounded-lg w-3/4 max-w-4xl p-6">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Hapus Customer</h2>
            <p class="mb-4 text-gray-600">Apakah Anda yakin ingin menghapus customer ini?</p>
            <form id="deleteCustomerForm" method="POST" action="<?= BASEURL; ?>/customer/btnDeleteCustomer">
                <input type="hidden" name="CustomerId" id="deleteCustomerId">
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

        function openEditModal(customer) {
            document.getElementById('editCustomerId').value = customer.CustomerId;
            document.getElementById('editUsername').value = customer.Username;
            document.getElementById('editEmail').value = customer.Email;
            document.getElementById('editPhone').value = customer.Phone;
            document.getElementById('editGender').value = customer.Gender;
            document.getElementById('editDateOfBirth').value = customer.DateOfBirth; 

            document.getElementById('editAddress').value = customer.Address;

            const imageUrl = customer.ImageUrl ? "<?= BASEURL; ?>/" + customer.ImageUrl : "";
            document.getElementById('editImagePreview').src = imageUrl;

            editModal.classList.remove('hidden');
        }

        function closeEditModal() {
            editModal.classList.add('hidden');
        }

        function openDeleteModal(customerId) {
            document.getElementById('deleteCustomerId').value = customerId;
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
                s
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

</html>