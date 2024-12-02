<?php
require_once '../core/dbConfig.php';
require_once '../activity_logs/log_functions.php';

// fetch all activity logs
$logs = fetchActivityLogs();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <style>
        body {
            font-family: 'Raleway', sans-serif;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding-top: 20px;
            margin: 0;
            overflow-y: auto;
        }

        .container {
            max-width: 900px;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            border: 2px solid #333;
            text-align: center;
        }

        .logout-top-right {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .logout-top-right a {
            color: #d9534f;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
        }

        .logout-top-right a:hover {
            text-decoration: underline;
        }

        h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        p {
            font-size: 16px;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- logout link -->
    <div class="logout-top-right">
        <a href="../users/logout.php">Logout</a>
    </div>
    <div class="container">
        <h1>Activity Logs</h1>

        <?php if (!empty($logs)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Log ID</th>
                        <th>Username</th>
                        <th>Action Type</th>
                        <th>Description</th>
                        <th>Search Keyword</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><?= htmlspecialchars($log['logID']); ?></td>
                            <td><?= htmlspecialchars($log['username']); ?></td>
                            <td><?= htmlspecialchars($log['action_type']); ?></td>
                            <td><?= htmlspecialchars($log['description']); ?></td>
                            <td><?= htmlspecialchars($log['search_keyword'] ?: 'N/A'); ?></td>
                            <td><?= htmlspecialchars($log['timestamp']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No activity logs found.</p>
        <?php endif; ?>
        <p><br></p>
        <a class="back-link" href="../index.php">← Back to Homepage</a>
    </div>
</body>
</html>
