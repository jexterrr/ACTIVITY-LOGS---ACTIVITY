<?php
require_once 'includes/functions.php';

$message = null;
$searchTerm = '';

// Handle system messages
if (isset($_GET['success'])) {
    if ($_GET['success'] == 'created') {
        $message = ['text' => 'Applicant successfully created.', 'type' => 'success'];
    } elseif ($_GET['success'] == 'updated') {
        $message = ['text' => 'Applicant successfully updated.', 'type' => 'success'];
    } elseif ($_GET['success'] == 'deleted') {
        $message = ['text' => 'Applicant successfully deleted.', 'type' => 'success'];
    }
}

if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
}

// Fetch all applicants
$applicantsResponse = getAllApplicants($searchTerm);
$applicants = $applicantsResponse['statusCode'] === 200 ? $applicantsResponse['querySet'] : [];
if ($applicantsResponse['statusCode'] !== 200) {
    $message = ['text' => $applicantsResponse['message'], 'type' => 'error'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant List</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Applicant List</h1>

        <!-- Display System Message -->
        <?php if ($message): ?>
            <div class="message <?= $message['type'] ?>">
                <?= htmlspecialchars($message['text']) ?>
            </div>
        <?php endif; ?>

        <!-- Actions Section -->
        <div class="actions">
            <form method="GET" class="search-bar">
                <input type="text" name="search" value="<?= htmlspecialchars($searchTerm) ?>" placeholder="Search by name">
                <button type="submit">Search</button>
            </form>
            <a href="pages/create.php" class="add-applicant" style="margin: 15px;">Add Applicant</a>
            <a href="pages/ActivityLogs.php" class="btn btn-logs">View Activity Logs</a>
            <a href="pages/logout.php" class="btn btn-logout" style="margin: 15px;">Logout</a>
        </div>


        <!-- Applicant Table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Years of Experience</th>
                        <th>Specialization</th>
                        <th>Favorite DBMS</th>
                        <th>Favorite Frontend Framework</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($applicants)): ?>
                        <?php foreach ($applicants as $applicant): ?>
                            <tr>
                                <td><?= htmlspecialchars($applicant['id']) ?></td>
                                <td><?= htmlspecialchars($applicant['firstName']) ?></td>
                                <td><?= htmlspecialchars($applicant['lastName']) ?></td>
                                <td><?= htmlspecialchars($applicant['email']) ?></td>
                                <td><?= htmlspecialchars($applicant['phone']) ?></td>
                                <td><?= htmlspecialchars($applicant['yearsOfExperience']) ?></td>
                                <td><?= htmlspecialchars($applicant['specialization']) ?></td>
                                <td><?= htmlspecialchars($applicant['favoriteDBMS']) ?></td>
                                <td><?= htmlspecialchars($applicant['favoriteFrontendFramework']) ?></td>
                                <td>
                                    <a href="pages/update.php?id=<?= htmlspecialchars($applicant['id']) ?>" class="btn btn-update">Update</a>
                                    <form action="pages/delete.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($applicant['id']) ?>">
                                        <button type="submit" class="btn btn-delete">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" style="text-align: center;">No applicants found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
