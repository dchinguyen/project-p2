<?php
$host = 'localhost';
$user = 'root';
$pwd  = '';
$sql_db = 'project2_db';

$conn = mysqli_connect($host, $user, $pwd);
if (!$conn) die('Database connection failed.');

mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS `$sql_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
mysqli_select_db($conn, $sql_db);
?>
