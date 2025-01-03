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
                <h2 class="text-xl font-semibold text-gray-700">Contact Table</h2>
            </div>

            <div class="overflow-x-auto p-4">
                <table class="min-w-full bg-white table-auto">
                    <thead>
                        <tr class="bg-gray-100 text-left text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-4 font-thin text-center">No</th>
                            <th class="py-3 px-4 font-thin text-center">Name</th>
                            <th class="py-3 px-4 font-thin text-center">Email</th>
                            <th class="py-3 px-4 font-thin text-center">Type</th>
                            <th class="py-3 px-4 font-thin text-center">Message</th>
                            <th class="py-3 px-4 font-thin text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['contacts'])): ?>
                            <?php foreach ($data['contacts'] as $index => $contact): ?>
                                <tr class="text-sm text-gray-600">
                                    <td class="py-3 px-4 text-center"><?= $index + 1 ?></td>
                                    <td class="py-3 px-4 text-center"><?= htmlspecialchars($contact['Name']) ?></td>
                                    <td class="py-3 px-4 text-center"><?= htmlspecialchars($contact['Email']) ?></td>
                                    <td class="py-3 px-4 text-center"><?= htmlspecialchars($contact['Type']) ?></td>
                                    <td class="py-3 px-4 text-center"><?= htmlspecialchars($contact['Message']) ?></td>
                                    <td class="py-3 px-4 text-center">
                                        <button class="bg-red-500 text-white px-4 py-1 rounded-md hover:bg-red-700 transition duration-200" onclick="openDeleteModal(<?= $contact['ContactId']; ?>)">
                                            <i class="fas fa-trash text-base"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="py-3 px-4 text-center text-gray-500">No contact data available</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex justify-center items-center">
                <div class="bg-white rounded-lg w-3/4 max-w-4xl p-6">
                    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Delete Contact</h2>
                    <p class="mb-4 text-gray-600">Are you sure you want to delete this contact?</p>
                    <form id="deleteContactForm" method="POST" action="<?= BASEURL; ?>/contact/btnDeleteContact">
                        <input type="hidden" name="ContactId" id="deleteContactId">
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

                function openDeleteModal(contactId) {
                    document.getElementById('deleteContactId').value = contactId;
                    document.getElementById('deleteModal').classList.remove('hidden');
                }

                function closeDeleteModal() {
                    document.getElementById('deleteModal').classList.add('hidden');
                }

                window.onload = function() {
                    const successNotification = document.getElementById('success-notification');
                    if (successNotification) {
                        setTimeout(() => {
                            successNotification.style.display = 'none';
                        }, 1000);
                    }

                    const errorNotification = document.getElementById('error-notification');
                    if (errorNotification) {
                        setTimeout(() => {
                            errorNotification.style.display = 'none';
                        }, 1000);
                    }
                }
            </script>
</body>