<?php
// login.php
session_start();
require_once '../config/Database.php';
require_once '../classes/UserFactory.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $database = Database::getInstance();
    $db = $database->getConnection();
    $userFactory = new UserFactory($db);
    
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email)) $errors[] = "Email is required";
    if (empty($password)) $errors[] = "Password is required";
    
    if (empty($errors)) {
        $user = $userFactory->login($email, $password);
        
        if ($user) {
            if ($user->getRole() === 'teacher' && !$user->isActive()) {
                $errors[] = "Your teacher account is pending approval.";
            } else {
                $_SESSION['user_id'] = $user->getId();
                $_SESSION['username'] = $user->getUsername();
                $_SESSION['role'] = $user->getRole();
                
                // Redirect based on role
                switch ($user->getRole()) {
                    case 'admin':
                        header('Location: /Youdemy/pages/admin/admindash.php');
                        break;
                    case 'teacher':
                        header('Location: /Youdemy/pages/teacher/teacherdash.php');
                        break;
                    case 'student':
                        header('Location: /Youdemy/pages/student/studentdash.php');
                        break;
                }
                exit();
            }
        } else {
            $errors[] = "Invalid email or password";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Youdemy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h1 class="text-2xl font-bold mb-6 text-center">Log in to continue your learning journey
        </h1>
        
        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700 mb-2">Email</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
            </div>
            
            <div>
                <label class="block text-gray-700 mb-2">Password</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
            </div>
            
            <button type="submit" class="w-full bg-green-500 text-white py-2 rounded-full  hover:bg-blue-700">
                Login
            </button>
        </form>
        
        <p class="mt-4 text-center text-gray-600">
            Don't have an account? <a href="register.php" class="text-blue-700 hover:underline">Register</a>
        </p>
    </div>
</body>
</html>