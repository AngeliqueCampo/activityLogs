<?php
session_start();
require_once '../core/models.php';

// redirect to login if user not authenticated
if (!isset($_SESSION['userID'])) {
    header('Location: ../users/login.php');
    exit();
}

// validate and retrieve application ID from GET request
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid applicant ID.");
}

$applicationID = $_GET['id'];
$applicant = null;

// fetch all applicants and find matching application ID
$applicants = getAllApplicants();
if (!$applicants || !is_array($applicants)) {
    die("Error fetching applicants.");
}

foreach ($applicants as $app) {
    if ($app['applicationID'] == $applicationID) {
        $applicant = $app;
        break;
    }
}

if (!$applicant) {
    die("Applicant not found.");
}

// check if there's a message to display from a previous operation
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
    <title>Update Applicant</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <style>
        body {
            font-family: 'Raleway', sans-serif;
            background-color: #f9f9f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            max-width: 600px;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            border: 2px solid #333;
        }
        h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
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
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            display: block;
            margin: 0 auto;
        }
        button:hover {
            background-color: #0056b3;
        }
        .status-message {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php if ($message): ?>
        <div class="status-message" style="color: <?php echo $statusColor; ?>;">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <div class="container">
        <h2>Update Applicant</h2>
        <form action="../core/handleForms.php" method="POST">
            <input type="hidden" name="applicationID" value="<?php echo htmlspecialchars($applicant['applicationID']); ?>">

            <label for="firstName">First Name:</label>
            <input type="text" name="firstName" value="<?php echo htmlspecialchars($applicant['firstName']); ?>" required>

            <label for="lastName">Last Name:</label>
            <input type="text" name="lastName" value="<?php echo htmlspecialchars($applicant['lastName']); ?>" required>

            <label for="cause">Cause:</label>
            <input type="text" name="cause" value="<?php echo htmlspecialchars($applicant['cause']); ?>" required>

            <label for="skills">Skills:</label>
            <textarea name="skills" rows="4" required><?php echo htmlspecialchars($applicant['skills']); ?></textarea>

            <label for="experience">Experience:</label>
            <textarea name="experience" rows="4" required><?php echo htmlspecialchars($applicant['experience']); ?></textarea>

            <!-- Hidden field for last updated by -->
            <input type="hidden" name="lastUpdatedBy" value="<?php echo $_SESSION['userID']; ?>">

            <button type="submit" name="updateApplicant">Update Applicant</button>
        </form>
    </div>
</body>
</html>
