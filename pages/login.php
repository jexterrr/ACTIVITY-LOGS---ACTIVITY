<?php
require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $stmt = $db->prepare("SELECT * FROM users_tbl WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: ../index.php');
            exit;
        } else {
            $message = ['type' => 'error', 'text' => 'Invalid username or password.'];
        }
    } catch (PDOException $e) {
        $message = ['type' => 'error', 'text' => 'Error: ' . $e->getMessage()];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        /* Centered Form Container */
        .form-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* Form Fields */
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        /* Form Button */
        button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Message Styling */
        .message {
            margin-top: 15px;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Link Styling */
        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Header */
        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="form-container" style="padding: 20px 50px 30px 30px;">
        <form method="POST">
            <h2>Login</h2>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <!-- Display Message -->
            <?php if (isset($message)): ?>
                <div class="message <?= $message['type'] ?>">
                    <?= $message['text'] ?>
                </div>
            <?php endif; ?>

            <button type="submit">Login</button>
            <p>Don't have an account? <a href="register.php">Create one here</a></p>
        </form>
    </div>
</body>
</html>
