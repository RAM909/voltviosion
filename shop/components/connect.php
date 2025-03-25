<?php

$host = $_ENV['DB_HOST']; // e.g., dpg-xxxxxx-a.oregon-postgres.render.com
$dbname = $_ENV['DB_NAME']; // e.g., voltvision_db
$user = $_ENV['DB_USER']; // e.g., postgres
$password = $_ENV['DB_PASS']; // The password Render generated

$conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);

?>