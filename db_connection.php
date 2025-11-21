<?php
$host = $_ENV['DB_HOST'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASSWORD'];
$name = $_ENV['DB_NAME'];

$conn = new mysqli($host, $user, $pass, $name);

if ($conn->connect_error) {
    die("Erro de Conexão com o Banco: " . $conn->connect_error);
}