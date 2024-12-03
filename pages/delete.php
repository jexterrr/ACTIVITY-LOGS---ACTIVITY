<?php
require_once '../includes/functions.php';

session_start();
$userId = $_SESSION['user_id'] ?? 'unknown';

// Get the applicant's ID
$id = $_POST['id'] ?? null;

if (!$id) {
    // Redirect to index if ID is missing
    header("Location: index.php");
    exit;
}

// Handle deletion
$response = deleteApplicant($id);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($response['statusCode'] === 200) {
        logActivity($userId, 'Delete');
        header("Location: ../index.php?success=deleted");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Delete Applicant</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        form button {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        form button:hover {
            background-color: #c82333;
        }

        a.cancel-link {
            text-decoration: none;
            padding: 10px 15px;
            background-color: #6c757d;
            color: white;
            border-radius: 5px;
            margin-left: 10px;
        }

        a.cancel-link:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Delete Applicant</h1>

        <!-- Confirmation Form -->
        <div class="form-container">
            <p>Are you sure you want to delete this applicant?</p>
            <p><strong><?= htmlspecialchars($applicant['firstName'] . ' ' . $applicant['lastName']) ?></strong></p>
            <form method="POST" action="delete.php">
                <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                <button type="submit">Yes, Delete</button>
                <a href="index.php" class="cancel-link">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>
