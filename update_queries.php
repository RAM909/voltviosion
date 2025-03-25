<?php
/**
 * This script updates all PHP files to replace $conn->prepare with db_prepare
 * Run this script once to update all files
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
    $content = file_get_contents($file);
    
    // Skip files that are already updated or don't need updating
    if (strpos($content, '$conn->prepare') === false || strpos($content, 'db_prepare') !== false) {
        continue;
    }
    
    // Replace $conn->prepare with db_prepare
    $updated = str_replace('$conn->prepare', 'db_prepare', $content);
    
    // Save the updated content
    file_put_contents($file, $updated);
    
    echo "Updated: $file\n";
    $count++;
}

echo "Total files updated: $count\n";
?> 