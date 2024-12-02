<?php
// database configuration
$host = 'localhost';       
$dbname = 'ngo';    
$username = 'root';        
$password = '';          

try {
    // create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // set error mode 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // handle connection error
    die("Database connection failed: " . $e->getMessage());
}
?>
