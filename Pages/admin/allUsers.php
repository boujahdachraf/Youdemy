<?php
require("../../Config/db.php");
require("../../Classe/Admin.php");
$db = new Database();
$conn = $db->getConnection();
$admin = new Admin($conn);
$users = $admin->getAllusers();

if (isset($_POST['update'])) {

    $admin->changeUserStatu($_POST['id'], $_POST['status']);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    </script>
</head>

<body>
    <div>
        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Product name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Color
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Category
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Price
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) { ?>

                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <?php echo $user['id'] ?>
                            </th>
                            <td class="px-6 py-4">
                                <?php echo $user['username'] ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php echo $user['email'] ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php echo $user['role'] ?>
                            </td>
                            <td class="px-6 py-4">
                                <form method="POST">
                                    <!-- Hidden input field for user ID -->
                                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

                                    <!-- Dropdown for user status -->
                                    <select name="status">
                                        <option value="active" <?php echo ($user['status'] === 'active') ? 'selected' : ''; ?>>Active</option>
                                        <option value="suspended" <?php echo ($user['status'] === 'suspended') ? 'selected' : ''; ?>>Suspended</option>
                                        <option value="deleted" <?php echo ($user['status'] === 'deleted') ? 'selected' : ''; ?>>Deleted</option>
                                    </select>

                                    <!-- Submit button -->
                                    <button name="update" type="submit">Update Status</button>
                                </form>
                            </td>
                        </tr>

                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>