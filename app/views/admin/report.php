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
    <div class="bg-white shadow-md rounded-lg overflow-hidden p-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-700">Employee Table</h2>
            <button class="bg-green-500 text-white px-4 py-2 rounded" onclick="openAddModal()">Add Employee</button>
        </div>

        <div class="overflow-x-auto p-4">
            <table id="employeeTable" class="min-w-full bg-white table-auto">
                <thead>
                    <tr class="bg-gray-100 text-left text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-4 font-thin text-center">No</th>
                        <th class="py-3 px-4 font-thin text-center">Username</th>
                        <th class="py-3 px-4 font-thin text-center">Email</th>
                        <th class="py-3 px-4 font-thin text-center">Phone</th>
                        <th class="py-3 px-4 font-thin text-center">Gender</th>
                        <th class="py-3 px-4 font-thin text-center">Date of Birth</th>
                        <th class="py-3 px-4 font-thin text-center">Address</th>
                        <th class="py-3 px-4 font-thin text-center">Role</th>
                        <th class="py-3 px-4 font-thin text-center">Image</th>
                        <th class="py-3 px-4 font-thin text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data['employee'])): ?>
                        <?php foreach ($data['employee'] as $index => $employee): ?>
                            <tr class="text-sm text-gray-600">
                                <td class="py-3 px-4 text-center"><?= $index + 1 ?></td>
                                <td class="py-3 px-4 text-center"><?= htmlspecialchars($employee['Username'] ?? '') ?></td>
                                <td class="py-3 px-4 text-center"><?= htmlspecialchars($employee['Email'] ?? '') ?></td>
                                <td class="py-3 px-4 text-center"><?= htmlspecialchars($employee['Phone'] ?? 'N/A') ?></td>
                                <td class="py-3 px-4 text-center"><?= htmlspecialchars($employee['Gender'] ?? 'N/A') ?></td>
                                <td class="py-3 px-4 text-center">
                                    <?= !empty($employee['DateOfBirth']) ? date("d F Y", strtotime($employee['DateOfBirth'])) : 'N/A' ?>
                                </td>
                                <td class="py-3 px-4 text-center"><?= htmlspecialchars($employee['Address'] ?? 'N/A') ?></td>
                                <td class="py-3 px-4 text-center"><?= htmlspecialchars($employee['Role'] ?? 'N/A') ?></td>
                                <td class="py-3 px-4 text-center">
                                    <img src="<?= BASEURL; ?>/<?= htmlspecialchars($employee['ImageUrl'] ?? '/img/user.png') ?>" alt="Employee Image" class="w-12 h-12 rounded-full mx-auto">
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <button class="bg-blue-500 text-white px-4 py-1 rounded-md hover:bg-blue-700 transition duration-200" onclick="openEditModal(<?= htmlspecialchars(json_encode($employee), ENT_QUOTES, 'UTF-8'); ?>)">
                                        <i class="fas fa-pen text-base"></i>
                                    </button>
                                    <button class="bg-red-500 text-white px-4 py-1 rounded-md hover:bg-red-700 transition duration-200 ml-2" onclick="openDeleteModal(<?= $employee['EmployeeId']; ?>)">
                                        <i class="fas fa-trash text-base"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="py-3 px-4 text-center text-gray-500">No employee data available</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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

                function openEditModal(employee) {
                    document.getElementById('editEmployeeId').value = employee.EmployeeId;
                    document.getElementById('editUsername').value = employee.Username;
                    document.getElementById('editEmail').value = employee.Email;
                    document.getElementById('editPhone').value = employee.Phone;
                    document.getElementById('editRole').value = employee.Role;
                    document.getElementById('editGender').value = employee.Gender;
                    document.getElementById('editDateOfBirth').value = employee.DateOfBirth; 
                    document.getElementById('editAddress').value = employee.Address;

                    const imageUrl = employee.ImageUrl ? "<?= BASEURL; ?>/" + employee.ImageUrl : "";
                    document.getElementById('editImagePreview').src = imageUrl;

                    editModal.classList.remove('hidden');
                }

                function closeEditModal() {
                    editModal.classList.add('hidden');
                }

                function openDeleteModal(employeeId) {
                    document.getElementById('deleteEmployeeId').value = employeeId;
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