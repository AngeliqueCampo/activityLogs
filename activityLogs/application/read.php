<?php
session_start();
require_once '../core/models.php';

// redirect to login if the user not authenticated
if (!isset($_SESSION['userID'])) {
    header('Location: ../users/login.php');
    exit();
}

// fetch all applicants
$applicants = getAllApplicants($pdo);

// function to get username by userID
function getUsernameByID($userID, $pdo) {
    $stmt = $pdo->prepare("SELECT Username FROM users WHERE UserID = ?");
    $stmt->execute([$userID]);
    $user = $stmt->fetch();
    return $user ? $user['Username'] : 'Unknown';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Applicants</title>
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

        h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        form {
            text-align: left;
            margin-bottom: 20px;
        }

        input[type="text"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        button {
            background-color: #5cb85c;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #4cae4c;
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
    </style>
</head>
<body>
    <!-- logout link -->
    <div class="logout-top-right">
        <a href="../users/logout.php">Logout</a>
    </div>

    <div class="container">
        <h2>Applicants</h2>

        <form action="search.php" method="GET">
            <input type="text" name="search" placeholder="Search applicants...">
            <button type="submit">Search</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Cause</th>
                    <th>Skills</th>
                    <th>Experience</th>
                    <th>Added by</th>
                    <th>Last Updated by</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($applicants)): ?>
                    <?php foreach ($applicants as $applicant): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($applicant['firstName']); ?></td>
                        <td><?php echo htmlspecialchars($applicant['lastName']); ?></td>
                        <td><?php echo htmlspecialchars($applicant['cause']); ?></td>
                        <td><?php echo htmlspecialchars($applicant['skills']); ?></td>
                        <td><?php echo htmlspecialchars($applicant['experience']); ?></td>
                        <td><?php echo htmlspecialchars($applicant['added_by_username'] ?? 'Unknown'); ?></td>
                        <td><?php echo htmlspecialchars($applicant['last_updated_by_username'] ?? 'Unknown'); ?></td>

                        <td>
                            <a href="update.php?id=<?php echo $applicant['applicationID']; ?>">Edit</a>
                            <a href="delete.php?id=<?php echo $applicant['applicationID']; ?>">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No applicants found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    <div class="back-link-container">
        <p><br></p>
        <a class="back-link" href="../index.php">← Back to Homepage</a>
    </div>
    </div>
</body>
</html>
