<?php
require_once 'dbConfig.php';
require_once 'models.php';

session_start();

// sets session message
function setSessionMessage($message, $status) {
    $_SESSION['message'] = $message;
    $_SESSION['status'] = $status;
}

// redirects to specific location and halts execution
function redirectTo($location) {
    header("Location: $location");
    exit();
}

// handle incoming POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['register'])) {
        handleUserRegistration();
    }

    if (isset($_POST['login'])) {
        handleUserLogin();
    }

    if (isset($_POST['createApplicant'])) {
        handleCreateApplicant();
    }

    if (isset($_POST['deleteApplicant'])) {
        handleDeleteApplicant();
    }

    if (isset($_POST['updateApplicant'])) {
        handleUpdateApplicant();
    }
}

// handles user registration logic
function handleUserRegistration() {
    global $pdo;

    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $username = trim($_POST['username']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($password) >= 6) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->fetchColumn() > 0) {
            setSessionMessage("Username already exists. Please choose a different username.", 400);
        } else {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            if (registerUser($firstName, $lastName, $username, $email, $hashedPassword)) {
                setSessionMessage("Account successfully registered.", 200);
                redirectTo('../users/login.php');
            } else {
                setSessionMessage("Registration failed. Please try again.", 400);
            }
        }
    } else {
        setSessionMessage("Invalid email or password too short (minimum 6 characters).", 400);
    }
    redirectTo('../users/register.php');
}

// handles user login logic
function handleUserLogin() {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        if ($userID = loginUser($username, $password)) {
            $_SESSION['username'] = $username;
            $_SESSION['userID'] = $userID;
            setSessionMessage("Login successful.", 200);
            redirectTo('../index.php');
        } else {
            setSessionMessage("Invalid username or password.", 400);
            redirectTo('../users/login.php');
        }
    } else {
        setSessionMessage("Please fill out all fields.", 400);
        redirectTo('../users/login.php');
    }
}

// handles applicant creation logic
function handleCreateApplicant() {
    global $pdo;

    $userID = $_SESSION['userID'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $cause = $_POST['cause'];
    $skills = $_POST['skills'];
    $experience = $_POST['experience'];
    $addedBy = $userID;

    if (createApplicant($userID, $firstName, $lastName, $cause, $skills, $experience, $addedBy)) {
        setSessionMessage("Applicant successfully created.", 200);
    } else {
        setSessionMessage("Failed to create applicant. Please try again.", 400);
    }
    redirectTo('../application/create.php');
}

// handles applicant deletion logic
function handleDeleteApplicant() {
    $applicationID = $_POST['applicationID'];
    $userID = $_SESSION['userID'];

    if (deleteApplicant($applicationID)) {
        setSessionMessage("Applicant successfully deleted.", 200);
    } else {
        setSessionMessage("Failed to delete applicant. Please try again.", 400);
    }
    redirectTo('../application/read.php');
}

// handles applicant update logic
function handleUpdateApplicant() {
    $applicationID = $_POST['applicationID'];
    $userID = $_SESSION['userID'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $cause = $_POST['cause'];
    $skills = $_POST['skills'];
    $experience = $_POST['experience'];

    if (updateApplicant($applicationID, $firstName, $lastName, $cause, $skills, $experience, $userID)) {
        setSessionMessage("Applicant successfully updated.", 200);
    } else {
        setSessionMessage("Failed to update applicant. Please try again.", 400);
    }
    redirectTo('../application/read.php');
}
?>
