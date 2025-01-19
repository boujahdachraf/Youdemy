<?php
require("../../Config/db.php");
require("../../Classe/User.php");

// Database connection
$db = new Database();
$conn = $db->getConnection();

// Handle form submission
if (isset($_POST['create'])) {

    // var_dump($_POST);
    // exit;

    // Get form data
    $username = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $status = 'active'; // Default status

    try {
        // Call the create method
        $user = new User();
        $user-
        $result = User::create($conn, $username, $email, $role, $password, $status);

        if ($result === true) {
            // Redirect to the same page to prevent form resubmission
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            // Display error message if user already exists
            echo "<script>alert('$result');</script>";
        }
    } catch (Exception $e) {
        // Handle exceptions
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<div class="max-w-4xl max-sm:max-w-lg mx-auto font-[sans-serif] p-6">
    <div class="text-center mb-12 sm:mb-16">
        <h4 class="text-gray-600 text-base mt-6">Sign up into your account</h4>
    </div>

    <form method="post">
        <div class="grid sm:grid-cols-1 gap-6">
            <div>
                <label class="text-gray-600 text-sm mb-2 block">Full Name</label>
                <input name="fullname" type="text"
                    class="bg-gray-100 w-full text-gray-800 text-sm px-4 py-3 rounded focus:bg-transparent outline-blue-500 transition-all"
                    placeholder="Enter last name" />
            </div>
            <div>
                <label class="text-gray-600 text-sm mb-2 block">Email</label>
                <input name="email" type="text"
                    class="bg-gray-100 w-full text-gray-800 text-sm px-4 py-3 rounded focus:bg-transparent outline-blue-500 transition-all"
                    placeholder="Enter email" />
                <div>
                    <label class="text-gray-600 text-sm mb-2 block">Password</label>
                    <input name="password" type="password"
                        class="bg-gray-100 w-full text-gray-800 text-sm px-4 py-3 rounded focus:bg-transparent outline-blue-500 transition-all"
                        placeholder="Enter password" />
                </div>
            </div>
            <div class="flex flex-row gap-5 content-center items-center">
                <div class="flex items-center">
                    <input checked id="default-radio-2" type="radio" value="Enseignant" name="role"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="default-radio-2" class="ms-2 text-sm font-medium text-gray-900 ">Enseignant</label>
                </div>
                <div class="flex items-center">
                    <input id="default-radio-2" type="radio" value="etudiant" name="role"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="default-radio-2" class="ms-2 text-sm font-medium text-gray-900 ">Etudiant</label>
                </div>
            </div>

            <div class="mt-8">
                <button type="submit" name="create"
                    class="mx-auto block py-3 px-6 text-sm tracking-wider rounded text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                    Sign up
                </button>
            </div>
    </form>
</div>