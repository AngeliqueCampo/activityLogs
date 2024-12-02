<?php
session_start();
require_once '../core/models.php';

// redirect to login if the user not authenticated
if (!isset($_SESSION['userID'])) {
    header('Location: ../users/login.php');
    exit();
}

// fetch all applicants for the masterlist display
$applicants = getAllApplicants(); 

// display status message if available
$message = '';
$statusColor = '';
if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
    $message = $_SESSION['message'];
    $statusColor = ($_SESSION['status'] == 200) ? 'green' : 'red';
    unset($_SESSION['message'], $_SESSION['status']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Applicant</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <style>
        body {
            font-family: 'Raleway', sans-serif;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            margin: 0;
            padding-top: 50px; 
            min-height: 100vh;
        }

        .status-message {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 16px;
            font-weight: bold;
            color: <?php echo $statusColor; ?>;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 10px 15px;
            border: 1px solid <?php echo $statusColor; ?>;
            border-radius: 5px;
        }

        .container {
            max-width: 900px;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            border: 2px solid #333;
            text-align: left; 
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
            text-align: center; 
        }

        form {
            text-align: left;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input, textarea {
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

        .search-button {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            font-size: 16px;
            margin-top: 10px;
            cursor: pointer;
        }

        .search-button:hover {
            background-color: #0056b3;
        }

        .back-link-container {
        text-align: center;
        margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
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

    <!-- status message -->
    <?php if ($message): ?>
        <div class="status-message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <div class="container">
        <h2>Create New Applicant</h2>
        <form action="../core/handleForms.php" method="POST">
            <input type="hidden" name="userID" value="<?php echo $_SESSION['userID']; ?>">

            <label for="firstName">First Name:</label>
            <input type="text" name="firstName" required>

            <label for="lastName">Last Name:</label>
            <input type="text" name="lastName" required>

            <label for="cause">Cause:</label>
            <input type="text" name="cause" required>

            <label for="skills">Skills:</label>
            <textarea name="skills" rows="4"></textarea>

            <label for="experience">Experience:</label>
            <textarea name="experience" rows="4"></textarea>

            <button type="submit" name="createApplicant">Create Applicant</button>
        </form>

        <!-- search button -->
        <button class="search-button" onclick="location.href='search.php'">Search Applicant</button>

        <h2>Applicant Masterlist</h2>
        <table>
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Cause</th>
                    <th>Skills</th>
                    <th>Experience</th>
                    <th>Added By</th>
                    <th>Last Updated By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applicants as $applicant): ?>
                <tr>
                    <td><?php echo htmlspecialchars($applicant['firstName']); ?></td>
                    <td><?php echo htmlspecialchars($applicant['lastName']); ?></td>
                    <td><?php echo htmlspecialchars($applicant['cause']); ?></td>
                    <td><?php echo htmlspecialchars($applicant['skills']); ?></td>
                    <td><?php echo htmlspecialchars($applicant['experience']); ?></td>
                    <td><?php echo htmlspecialchars($applicant['added_by_username']); ?></td>
                    <td><?php echo htmlspecialchars($applicant['last_updated_by_username']); ?></td>
                    <td>
                        <a href="update.php?id=<?php echo $applicant['applicationID']; ?>">Edit</a>
                        <a href="delete.php?id=<?php echo $applicant['applicationID']; ?>">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- back to homepage -->
        <div class="back-link-container">
            <a class="back-link" href="../index.php">← Back to Homepage</a>
        </div>
    </div>

</body>
</html>
