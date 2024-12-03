<?php
require_once '../includes/functions.php';

// Assuming the user ID is stored in the session
session_start();
$userId = $_SESSION['user_id'] ?? 'unknown'; // Replace 'unknown' with a default value if no user is logged in

$message = null; // To store the system message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate form data
    $firstName = filter_var($_POST['firstName'], FILTER_SANITIZE_STRING);
    $lastName = filter_var($_POST['lastName'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $yearsOfExperience = filter_var($_POST['yearsOfExperience'], FILTER_SANITIZE_NUMBER_INT);
    $specialization = filter_var($_POST['specialization'], FILTER_SANITIZE_STRING);
    $favoriteDBMS = filter_var($_POST['favoriteDBMS'], FILTER_SANITIZE_STRING);
    $favoriteFrontendFramework = filter_var($_POST['favoriteFrontendFramework'], FILTER_SANITIZE_STRING);

    // Check if email is valid
    if (!$email) {
        $message = [
            'text' => 'Please provide a valid email address.',
            'type' => 'error'
        ];
    } else {
        // Prepare data for insertion
        $data = [
            ':firstName' => $firstName,
            ':lastName' => $lastName,
            ':email' => $email,
            ':phone' => $phone,
            ':yearsOfExperience' => $yearsOfExperience,
            ':specialization' => $specialization,
            ':favoriteDBMS' => $favoriteDBMS,
            ':favoriteFrontendFramework' => $favoriteFrontendFramework
        ];

        // Call function to create a new applicant
        $response = createApplicant($data);

        if ($response['statusCode'] === 200) {
            // Log the "Create" activity
            logActivity($userId, 'Create');

            // Redirect to index if creation is successful
            header("Location: ../index.php?success=created");
            exit;
        } else {
            $message = [
                'text' => $response['message'],
                'type' => 'error'
            ];
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Create Applicant</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Form container styling */
        .form-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        form input, form button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            font-size: 14px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        form button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        form button:hover {
            background-color: #0056b3;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }

        /* Message Box */
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-size: 14px;
            text-align: center;
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

    </style>
</head>
<body>
    <div class="container">
        <h1>Create New Applicant</h1>
        <!-- Display System Message -->
        <?php if ($message): ?>
            <div class="message <?= $message['type'] ?>">
                <?= htmlspecialchars($message['text']) ?>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <div class="form-container" style="padding: 30px 50px 30px 30px;">
            <form method="POST" action="create.php">
                <input type="text" name="firstName" placeholder="First Name" required>
                <input type="text" name="lastName" placeholder="Last Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="text" name="phone" placeholder="Phone" required>
                <input type="number" name="yearsOfExperience" placeholder="Years of Experience" required>
                <input type="text" name="specialization" placeholder="Specialization" required>
                <input type="text" name="favoriteDBMS" placeholder="Favorite DBMS">
                <input type="text" name="favoriteFrontendFramework" placeholder="Favorite Frontend Framework">
                <button type="submit">Create</button>
            </form>
        </div>
    </div>
</body>
</html>
