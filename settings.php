<?php
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'project2_db';

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS);
if (!$conn) { die('DB connect failed: '.mysqli_connect_error()); }

mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS `$DB_NAME` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
mysqli_select_db($conn, $DB_NAME);
