<?php
// Load environment variables
require_once 'vendor/autoload.php';  // phpdotenv required, can be installed via Composer
Dotenv\Dotenv::createImmutable(__DIR__)->load();

// Retrieve database credentials from environment variables
$db_host = $_ENV['DB_HOST'];
$db_name = $_ENV['DB_NAME'];
$db_user = $_ENV['DB_USER'];
$db_password = $_ENV['DB_PASSWORD'];

/* 
For learning purposes, it's easy to use the following code 
(less secure, less flexible, cannot be used across different environments without modification)
instead of loading environment variables and using them for connection to your DB:

$db_host = 'localhost';
$db_name = 'mydatabase'; // change to your database's name
$db_user = 'root';
$db_password = '';
*/

try {
    // PDO connection string
    $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8";
    $pdo = new PDO($dsn, $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}