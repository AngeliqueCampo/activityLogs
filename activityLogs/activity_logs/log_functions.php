<?php
require_once '../core/dbConfig.php';

// fetch all activity logs from db
function fetchActivityLogs() {
    global $pdo;

    try {
        $stmt = $pdo->query("
            SELECT 
                l.logID, 
                u.username, 
                l.action_type, 
                l.description, 
                l.search_keyword, 
                l.timestamp
            FROM activity_logs l
            JOIN users u ON l.userID = u.userID
            ORDER BY l.timestamp DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Failed to fetch activity logs: " . $e->getMessage());
        return [];
    }
}

// log new activity in db
function logActivity($userID, $actionType, $description, $searchKeyword = null) {
    global $pdo;

    // valid action types for logging
    $validActionTypes = ['INSERT', 'UPDATE', 'DELETE', 'SEARCH', 'LOGIN', 'LOGOUT'];

    // check if provided action type is valid
    if (!in_array($actionType, $validActionTypes)) {
        error_log("Invalid action type: $actionType");
        return false;
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO activity_logs (userID, action_type, description, search_keyword) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$userID, $actionType, $description, $searchKeyword]);

        return true; // logging successful
    } catch (Exception $e) {
        error_log("Error logging activity: " . $e->getMessage());
        return false; // logging failed
    }
}
