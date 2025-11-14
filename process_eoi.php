<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: apply.php');
    exit;
}

require_once("settings.php");

$jobRef   = $_POST["jobRef"] ?? "";
$first    = trim($_POST["firstName"] ?? "");
$last     = trim($_POST["lastName"] ?? "");
$dob      = trim($_POST["dob"] ?? "");
$gender   = $_POST["gender"] ?? "";
$street   = trim($_POST["street"] ?? "");
$suburb   = trim($_POST["suburb"] ?? "");
$state    = $_POST["state"] ?? "";
$postcode = trim($_POST["postcode"] ?? "");
$email    = trim($_POST["email"] ?? "");
$phone    = trim($_POST["phone"] ?? "");
$skills   = $_POST["skills"] ?? [];
$other    = trim($_POST["other"] ?? "");

$errors = [];

if ($jobRef !== "CE7C") $errors[] = "Invalid job reference.";
if (!preg_match('/^[A-Za-z ]{1,20}$/', $first)) $errors[] = "Invalid first name.";
if (!preg_match('/^[A-Za-z ]{1,20}$/', $last)) $errors[] = "Invalid last name.";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email.";
if (!preg_match('/^[0-9 ]{8,12}$/', $phone)) $errors[] = "Phone must be 8–12 digits.";
if (!is_array($skills) || count($skills) < 1) $errors[] = "Please select at least one skill.";

if ($errors) {
    http_response_code(422);
    echo "<h2>There were problems with your application:</h2><ul>";
    foreach ($errors as $e) echo "<li>" . htmlspecialchars($e) . "</li>";
    echo "</ul><a href='apply.php'>Go back</a>";
    exit;
}

$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) die("<p>Database connection failed.</p>");

$sql = "INSERT INTO eoi (jobRef, fname, lname, email, phone, status)
        VALUES (?, ?, ?, ?, ?, 'New')";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "sssss", $jobRef, $first, $last, $email, $phone);
mysqli_stmt_execute($stmt);

$eoi_id = mysqli_insert_id($conn);

mysqli_stmt_close($stmt);
mysqli_close($conn);
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EOI Submitted</title>
</head>
<body>

<div style="max-width:600px;margin:40px auto;padding:20px;border:1px solid #ddd;">
    <h1>Thanks! Your application has been received.</h1>

    <p><strong>EOI Number:</strong> <?= (int)$eoi_id ?></p>
    <p><strong>Role:</strong> Cloud Engineer (Ref: CE7C)</p>

    <a href="index.php">Return to Home</a>
</div>

</body>
</html>
