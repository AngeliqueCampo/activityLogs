<?php
require_once 'dbConfig.php';
require_once '../activity_logs/log_functions.php';

// register a new user
function registerUser($firstName, $lastName, $username, $email, $password) {
    global $pdo;

    try {
        $stmt = $pdo->prepare("
            INSERT INTO users (firstName, lastName, username, email, password)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$firstName, $lastName, $username, $email, $password]);
        return true;
    } catch (Exception $e) {
        error_log("Error registering user: " . $e->getMessage());
        return false;
    }
}

// user login
function loginUser($username, $password) {
    global $pdo;

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['userID'] = $user['userID'];
            $_SESSION['username'] = $user['username'];
            logActivity($user['userID'], 'LOGIN', 'User logged in');
            return $user['userID'];
        }
        return false;
    } catch (Exception $e) {
        error_log("Error logging in user: " . $e->getMessage());
        return false;
    }
}


// create a new applicant
function createApplicant($userID, $firstName, $lastName, $cause, $skills, $experience) {
    global $pdo;

    try {
        $stmt = $pdo->prepare("
            INSERT INTO applications (userID, firstName, lastName, cause, skills, experience, added_by)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$userID, $firstName, $lastName, $cause, $skills, $experience, $_SESSION['userID']]);

        logActivity($_SESSION['userID'], 'INSERT', "Created new applicant: $firstName $lastName");
        return [
            'message' => 'Applicant created successfully.',
            'statusCode' => 200
        ];
    } catch (Exception $e) {
        error_log("Error creating applicant: " . $e->getMessage());
        return [
            'message' => 'Failed to create applicant.',
            'statusCode' => 400
        ];
    }
}

// update an existing applicant
function updateApplicant($applicationID, $firstName, $lastName, $cause, $skills, $experience) {
    global $pdo;

    try {
        $stmt = $pdo->prepare("
            UPDATE applications 
            SET firstName = ?, lastName = ?, cause = ?, skills = ?, experience = ?, last_updated_by = ?
            WHERE applicationID = ?
        ");
        $stmt->execute([$firstName, $lastName, $cause, $skills, $experience, $_SESSION['userID'], $applicationID]);

        logActivity($_SESSION['userID'], 'UPDATE', "Updated applicant ID: $applicationID");
        return [
            'message' => 'Applicant updated successfully.',
            'statusCode' => 200
        ];
    } catch (Exception $e) {
        error_log("Error updating applicant: " . $e->getMessage());
        return [
            'message' => 'Failed to update applicant.',
            'statusCode' => 400
        ];
    }
}


// delete an applicant
function deleteApplicant($applicationID) {
    global $pdo;

    try {
        $stmt = $pdo->prepare("DELETE FROM applications WHERE applicationID = ?");
        $stmt->execute([$applicationID]);

        logActivity($_SESSION['userID'], 'DELETE', "Deleted applicant ID: $applicationID");
        return [
            'message' => 'Applicant deleted successfully.',
            'statusCode' => 200
        ];
    } catch (Exception $e) {
        error_log("Error deleting applicant: " . $e->getMessage());
        return [
            'message' => 'Failed to delete applicant.',
            'statusCode' => 400
        ];
    }
}

// fetch all applicants
function getAllApplicants() {
    global $pdo;

    try {
        $stmt = $pdo->prepare("
            SELECT 
                a.applicationID,
                a.firstName,
                a.lastName,
                a.cause,
                a.skills,
                a.experience,
                a.added_by,
                a.last_updated_by,
                a.submitted_at,
                u1.username AS added_by_username,
                u2.username AS last_updated_by_username
            FROM applications a
            LEFT JOIN users u1 ON a.added_by = u1.userID
            LEFT JOIN users u2 ON a.last_updated_by = u2.userID
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Error fetching applicants: " . $e->getMessage());
        return [];
    }
}

// search for applicants
function searchApplicants($searchTerm) {
    global $pdo;

    try {
        $stmt = $pdo->prepare("
            SELECT 
                a.applicationID, 
                a.firstName, 
                a.lastName, 
                a.cause, 
                a.skills, 
                a.experience, 
                u.username AS added_by_username, 
                u2.username AS last_updated_by_username
            FROM applications a
            LEFT JOIN users u ON a.added_by = u.userID
            LEFT JOIN users u2 ON a.last_updated_by = u2.userID
            WHERE 
                a.firstName LIKE ? 
                OR a.lastName LIKE ? 
                OR a.cause LIKE ? 
                OR a.skills LIKE ? 
                OR a.experience LIKE ?
        ");
        $searchTermWithWildcard = '%' . $searchTerm . '%';
        $stmt->execute([
            $searchTermWithWildcard,
            $searchTermWithWildcard,
            $searchTermWithWildcard,
            $searchTermWithWildcard,
            $searchTermWithWildcard
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Error searching applicants: " . $e->getMessage());
        return [];
    }
}
