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
    $is_postgres = true;
} else {
    // MySQL connection (for local development)
    $db_string = "mysql:host=$db_host;dbname=$db_name";
    $is_postgres = false;
}

try {
    $conn = new PDO($db_string, $db_user, $db_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Function to convert MySQL style queries to PostgreSQL style
function convertToPostgres($query) {
    global $is_postgres;
    
    if (!$is_postgres) {
        return $query; // Return original query if not using Postgres
    }
    
    // Replace backticks with double quotes for table/column names
    $query = str_replace('`', '"', $query);
    
    // MySQL uses LIMIT x, y but PostgreSQL uses LIMIT x OFFSET y
    if (preg_match('/LIMIT\s+(\d+)\s*,\s*(\d+)/i', $query, $matches)) {
        $offset = $matches[1];
        $limit = $matches[2];
        $query = preg_replace('/LIMIT\s+(\d+)\s*,\s*(\d+)/i', "LIMIT $limit OFFSET $offset", $query);
    }
    
    // Handle CURRENT_TIMESTAMP for PostgreSQL
    $query = str_replace('CURRENT_TIMESTAMP()', 'CURRENT_TIMESTAMP', $query);
    
    // Handle any AUTO_INCREMENT references (PostgreSQL uses SERIAL)
    $query = preg_replace('/AUTO_INCREMENT/i', '', $query);
    
    return $query;
}

// Create a wrapper for PDO prepare that automatically converts queries
function db_prepare($query) {
    global $conn;
    return $conn->prepare(convertToPostgres($query));
}

// Function to ensure user_id is valid for database operations
function validate_user_id($user_id) {
    global $is_postgres;
    
    // For PostgreSQL, we need to convert empty strings to NULL
    if ($is_postgres && ($user_id === '' || $user_id === null)) {
        return null;
    }
    
    // For MySQL, empty strings will be converted to 0
    return $user_id;
}

// Fix user_id in session if needed
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = validate_user_id($_SESSION['user_id']);
}
?>