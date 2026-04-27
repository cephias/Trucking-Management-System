<?php

$host = '127.0.0.1';
$dbname = 'kai_trucking';
$username = 'root'; 
$password = '#Zoom0111' ; 

try {
    $pdo = new PDO("mysql:host=$host;port=3308;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
