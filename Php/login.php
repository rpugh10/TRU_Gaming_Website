<?php
require __DIR__. "/config.php";
$username = $_POST["username"];
$password = $_POST["password"];

if($username === "" || $password === ""){
    die("Missing username or password.");
}

$sql = "
SELECT * 
FROM USERS 
WHERE username = ?
";
$stmt = $pdo->prepare($sql);
$stmt -> execute([$username]);
$user = $stmt->fetch();

if($user && password_verify($password, $user["password_hash"])){
    session_regenerate_id(true);
    $_SESSION["user_id"] = $user["id"];
    $_SESSION["username"] = $user["username"];
    echo "Success";
}else{
    echo "Invalid username or password.";
}