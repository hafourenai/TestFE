<?php

$host = "127.0.0.1";
$username = "root";
$password = ""; 
$database = "attendance_db";
$port = 8111;

$conn = new mysqli(
    $host,
    $username,
    $password,
    $database,
    $port
);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}