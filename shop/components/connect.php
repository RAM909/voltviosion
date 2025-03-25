<?php
// Get database credentials from environment variables
$db_host = getenv('DB_HOST') ?: 'localhost';
$db_name = getenv('DB_NAME') ?: 'shop_db';
$db_user = getenv('DB_USER') ?: 'root';
$db_pass = getenv('DB_PASS') ?: '';

// Check if we're using PostgreSQL (on Render) or MySQL (locally)
if (getenv('RENDER')) {
    // PostgreSQL connection
    $db_string = "pgsql:host=$db_host;dbname=$db_name";
} else {
    // MySQL connection (for local development)
    $db_string = "mysql:host=$db_host;dbname=$db_name";
}

try {
    $conn = new PDO($db_string, $db_user, $db_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>