<?php
require_once '../includes/functions.php';

session_start();
$userId = $_SESSION['user_id'] ?? 'unknown';

$message = null; // To store the system message

// Get the applicant's ID
$id = $_GET['id'] ?? null;

if (!$id) {
    // Redirect to index if ID is missing
    header("Location: index.php");
    exit;
}

// Fetch applicant details
$applicant = null;
$applicantResponse = getApplicantById($id);

if ($applicantResponse['statusCode'] === 200) {
    $applicant = $applicantResponse['querySet'];
} else {
    // Redirect to index if applicant is not found
    header("Location: index.php?error=not_found");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input data before updating
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

        $response = updateApplicant($id, $data);

        // Redirect to index with a success or error message
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // After successfully updating the applicant
            if ($response['statusCode'] === 200) {
                logActivity($userId, 'Update');
                header("Location: ../index.php?success=updated");
                exit;
            }        
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
    <title>Update Applicant</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }

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
    </style>
</head>
<body>
    <div class="container">
        <h1>Update Applicant</h1>
        <!-- Display System Message -->
        <?php if ($message): ?>
            <div class="message <?= $message['type'] ?>">
                <?= htmlspecialchars($message['text']) ?>
            </div>
        <?php endif; ?>


        <!-- Form -->
        <div class="form-container" style="padding: 30px 50px 30px 30px;">
            <form method="POST">
                <input type="hidden" name="id" value="<?= htmlspecialchars($applicant['id']) ?>">
                <input type="text" name="firstName" value="<?= htmlspecialchars($applicant['firstName']) ?>" placeholder="First Name" required>
                <input type="text" name="lastName" value="<?= htmlspecialchars($applicant['lastName']) ?>" placeholder="Last Name" required>
                <input type="email" name="email" value="<?= htmlspecialchars($applicant['email']) ?>" placeholder="Email" required>
                <input type="text" name="phone" value="<?= htmlspecialchars($applicant['phone']) ?>" placeholder="Phone" required>
                <input type="number" name="yearsOfExperience" value="<?= htmlspecialchars($applicant['yearsOfExperience']) ?>" placeholder="Years of Experience" required>
                <input type="text" name="specialization" value="<?= htmlspecialchars($applicant['specialization']) ?>" placeholder="Specialization" required>
                <input type="text" name="favoriteDBMS" value="<?= htmlspecialchars($applicant['favoriteDBMS']) ?>" placeholder="Favorite DBMS">
                <input type="text" name="favoriteFrontendFramework" value="<?= htmlspecialchars($applicant['favoriteFrontendFramework']) ?>" placeholder="Favorite Frontend Framework">
                <button type="submit">Update</button>
            </form>
        </div>
    </div>
</body>
</html>
