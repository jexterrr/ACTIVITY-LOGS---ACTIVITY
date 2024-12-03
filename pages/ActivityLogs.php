<?php
require_once '../includes/functions.php';

// Fetch all activity logs with username
try {
    global $db;
    $stmt = $db->prepare("
        SELECT 
            users_tbl.username,
            activity_logs_tbl.type,
            activity_logs_tbl.createDate
        FROM 
            activity_logs_tbl
        INNER JOIN 
            users_tbl 
        ON 
            activity_logs_tbl.user_id = users_tbl.id
        ORDER BY 
            activity_logs_tbl.createDate DESC
    ");
    $stmt->execute();
    $activityLogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching activity logs: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Activity Logs</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        /* Custom styling for Activity Logs table */
        .table-container {
            max-width: 1200px;
            margin: 20px auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Activity Logs</h1>

        <!-- Activity Logs Table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Activity Type</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($activityLogs)): ?>
                        <?php foreach ($activityLogs as $log): ?>
                            <tr>
                                <td><?= htmlspecialchars($log['username']) ?></td>
                                <td><?= htmlspecialchars($log['type']) ?></td>
                                <td><?= htmlspecialchars($log['createDate']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center;">No activity logs found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
