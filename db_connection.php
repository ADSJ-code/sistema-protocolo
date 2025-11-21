<?php
$host = getenv('DB_HOST') ?: 'db';
$user = getenv('DB_USER') ?: 'protocolouser';
$pass = getenv('DB_PASSWORD') ?: '123456';
$name = getenv('DB_NAME') ?: 'protocolodb';

$conn = new mysqli($host, $user, $pass, $name);

if ($conn->connect_error) {
    die($conn->connect_error);
}