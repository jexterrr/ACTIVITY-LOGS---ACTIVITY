<?php
require_once '../includes/functions.php';

$response = readApplicants();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>All Applicants</title>
</head>
<body>
    <h1>All Applicants</h1>
    <?php if ($response['statusCode'] === 200 && !empty($response['querySet'])): ?>
        <table border="1">
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
                <?php foreach ($response['querySet'] as $applicant): ?>
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
                            <a href="update.php?id=<?= htmlspecialchars($applicant['id']) ?>">Edit</a>
                            <form method="POST" action="delete.php" style="display:inline;">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($applicant['id']) ?>">
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No records found.</p>
    <?php endif; ?>
</body>
</html>
