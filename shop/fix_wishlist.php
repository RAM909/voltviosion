<?php
include 'components/connect.php';

// This script checks and fixes the wishlist table if it's missing

try {
    // Check if wishlist table exists
    $check_table = "
    SELECT EXISTS (
        SELECT FROM information_schema.tables 
        WHERE table_schema = 'public' 
        AND table_name = 'wishlist'
    );";
    
    $result = $conn->query($check_table);
    $exists = $result->fetchColumn();
    
    if (!$exists) {
        echo "<h2>Creating wishlist table...</h2>";
        
        // Create the wishlist table
        $create_table = "
        CREATE TABLE wishlist (
            id SERIAL PRIMARY KEY,
            user_id INTEGER NOT NULL,
            pid INTEGER NOT NULL,
            name VARCHAR(100) NOT NULL,
            price INTEGER NOT NULL,
            image VARCHAR(100) NOT NULL,
            dimensions VARCHAR(500) DEFAULT NULL
        );";
        
        $conn->exec($create_table);
        echo "<p>Wishlist table created successfully!</p>";
    } else {
        // Check if dimensions column exists
        $check_column = "
        SELECT EXISTS (
            SELECT FROM information_schema.columns 
            WHERE table_name = 'wishlist' 
            AND column_name = 'dimensions'
        );";
        
        $result = $conn->query($check_column);
        $column_exists = $result->fetchColumn();
        
        if (!$column_exists) {
            echo "<h2>Adding dimensions column to wishlist table...</h2>";
            
            // Add dimensions column
            $add_column = "ALTER TABLE wishlist ADD COLUMN dimensions VARCHAR(500) DEFAULT NULL;";
            $conn->exec($add_column);
            echo "<p>Dimensions column added to wishlist table!</p>";
        } else {
            echo "<h2>Wishlist table is in good shape!</h2>";
        }
    }
    
    echo "<p><a href='home.php'>Return to home</a></p>";
} catch (PDOException $e) {
    echo "<h2>Error:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<p><a href='home.php'>Return to home</a></p>";
}
?> 