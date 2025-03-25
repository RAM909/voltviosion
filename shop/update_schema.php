<?php
// Include database connection
include 'components/connect.php';

// Set execution time to 5 minutes
set_time_limit(300);

// Only allow running on Render or in development
if (!getenv('RENDER') && $_SERVER['REMOTE_ADDR'] !== '127.0.0.1') {
    die("This script can only be run on Render or locally.");
}

try {
    // Read PostgreSQL schema file
    $schema_file = __DIR__ . '/../shop_db_pg.sql';
    
    if (!file_exists($schema_file)) {
        // If the file doesn't exist, create a minimal version
        $sql = "
        -- Create admins table if not exists
        CREATE TABLE IF NOT EXISTS admins (
          id SERIAL PRIMARY KEY,
          name VARCHAR(20) NOT NULL,
          password VARCHAR(50) NOT NULL
        );
        
        -- Create admins record if not exists
        INSERT INTO admins (name, password) 
        VALUES ('admin', '6216f8a75fd5bb3d5f22b6f9958cdede3fc086c2')
        ON CONFLICT (id) DO NOTHING;
        
        -- Create other tables if they don't exist
        CREATE TABLE IF NOT EXISTS cart (
          id SERIAL PRIMARY KEY,
          user_id INTEGER NOT NULL,
          pid INTEGER NOT NULL,
          name VARCHAR(100) NOT NULL,
          price INTEGER NOT NULL,
          quantity INTEGER NOT NULL DEFAULT 1,
          image VARCHAR(100) NOT NULL,
          dimensions VARCHAR(500) DEFAULT NULL
        );
        
        CREATE TABLE IF NOT EXISTS orders (
          id SERIAL PRIMARY KEY,
          user_id INTEGER NOT NULL,
          name VARCHAR(20) NOT NULL,
          number VARCHAR(10) NOT NULL,
          email VARCHAR(50) NOT NULL,
          method VARCHAR(50) NOT NULL,
          address VARCHAR(500) NOT NULL,
          total_products VARCHAR(1000) NOT NULL,
          total_price INTEGER NOT NULL,
          placed_on DATE NOT NULL DEFAULT CURRENT_DATE,
          status VARCHAR(20) NOT NULL DEFAULT 'pending',
          payment_status VARCHAR(20) NOT NULL DEFAULT 'pending',
          payment_id VARCHAR(255) NOT NULL
        );
        
        CREATE TABLE IF NOT EXISTS order_items (
          id SERIAL PRIMARY KEY,
          order_id INTEGER NOT NULL,
          product_id INTEGER NOT NULL,
          quantity INTEGER NOT NULL DEFAULT 1
        );
        
        CREATE TABLE IF NOT EXISTS products (
          id SERIAL PRIMARY KEY,
          name VARCHAR(100) NOT NULL,
          details VARCHAR(500) NOT NULL,
          category VARCHAR(100) NOT NULL,
          price INTEGER NOT NULL,
          image_01 VARCHAR(100) NOT NULL,
          image_02 VARCHAR(100) NOT NULL,
          image_03 VARCHAR(100) NOT NULL,
          dimensions VARCHAR(500) DEFAULT NULL
        );
        
        CREATE TABLE IF NOT EXISTS product_pairs (
          id SERIAL PRIMARY KEY,
          product_id_1 INTEGER NOT NULL,
          product_id_2 INTEGER NOT NULL,
          count INTEGER DEFAULT 1
        );
        
        CREATE TABLE IF NOT EXISTS users (
          id SERIAL PRIMARY KEY,
          name VARCHAR(20) NOT NULL,
          email VARCHAR(50) NOT NULL,
          password VARCHAR(50) NOT NULL
        );
        
        CREATE TABLE IF NOT EXISTS wishlist (
          id SERIAL PRIMARY KEY,
          user_id INTEGER NOT NULL,
          pid INTEGER NOT NULL,
          name VARCHAR(100) NOT NULL,
          price INTEGER NOT NULL,
          image VARCHAR(100) NOT NULL
        );
        ";
    } else {
        // Read the existing schema file
        $sql = file_get_contents($schema_file);
    }
    
    // Execute the schema
    $conn->exec($sql);
    
    echo "<h1>Database Update Successful</h1>";
    echo "<p>The database schema has been updated successfully.</p>";
    echo "<p><a href='/shop/home.php'>Return to the shop</a></p>";
    
} catch (PDOException $e) {
    echo "<h1>Database Update Error</h1>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?> 