<?php
session_start();

$servername = "127.0.0.1";
$username = "root";
$password = "root1234";
$dbname = "sistema_protocolo_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Define o modo de erro do PDO para exceção
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("A conexão falhou: " . $e->getMessage());
}
?>