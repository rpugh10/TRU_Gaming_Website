<?php
session_start();
$env = parse_ini_file(__DIR__ . '/../.env');
$host = "localhost";
$dbname = "TRUGamingSite";
$user = $env['DB_USERNAME'];
$password = $env['DB_PASSWORD'];
try{
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname",
        $user,
        $password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Database connection established successfully.";
}catch(PDOException $e){
    die("Could not connect to the database $dbname :" . $e->getMessage());
}
?>