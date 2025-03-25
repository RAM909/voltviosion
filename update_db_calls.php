<?php
/**
 * This script updates PHP files to replace $conn->prepare calls with db_prepare
 * and adds user_id validation where needed
 */

// Directory to scan (shop directory and all subdirectories)
$directory = __DIR__ . '/shop';

// Function to scan directories
function scanDirectory($dir) {
    $files = [];
    $scan = scandir($dir);
    
    foreach ($scan as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        
        $path = $dir . '/' . $file;
        
        if (is_dir($path)) {
            $files = array_merge($files, scanDirectory($path));
        } elseif (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
            $files[] = $path;
        }
    }
    
    return $files;
}

// Get all PHP files
$files = scanDirectory($directory);
$count = 0;

// Process each file
foreach ($files as $file) {
    // Skip the connect.php file
    if (strpos($file, 'connect.php') !== false) {
        continue;
    }
    
    $content = file_get_contents($file);
    $updated = false;
    
    // Replace $conn->prepare with db_prepare
    if (strpos($content, '$conn->prepare') !== false) {
        $content = str_replace('$conn->prepare', 'db_prepare', $content);
        $updated = true;
    }
    
    // Add user_id validation in PHP files that contain user_id
    if (strpos($content, '$user_id') !== false && 
        strpos($content, 'validate_user_id($user_id)') === false) {
        
        // Look for the pattern where user_id is set
        $patterns = [
            '/\$user_id\s*=\s*\'\';\s*\}\s*;/',
            '/\$user_id\s*=\s*\$_SESSION\[\'user_id\'\];\s*\}\s*else\s*\{\s*\$user_id\s*=\s*\'\';\s*\}\s*;/'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $content)) {
                $replacement = "$0\n\n// Validate user_id for PostgreSQL compatibility\n\$user_id = validate_user_id(\$user_id);";
                $content = preg_replace($pattern, $replacement, $content);
                $updated = true;
                break;
            }
        }
    }
    
    // Remove session_start() if it comes after connect.php include
    if (strpos($content, "include 'components/connect.php';") !== false &&
        strpos($content, 'session_start();') !== false) {
        $content = str_replace('session_start();', '// Session is already started in connect.php
// session_start();', $content);
        $updated = true;
    }
    
    // Save the updated content if changes were made
    if ($updated) {
        file_put_contents($file, $content);
        echo "Updated: $file\n";
        $count++;
    }
}

echo "Total files updated: $count\n";
?> 