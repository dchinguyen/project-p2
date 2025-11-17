<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: apply.php");
    exit;
}

require_once "settings.php";
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) die("<p>Database connection failed.</p>");

$jobRef   = trim($_POST['jobRef']);
$first    = trim($_POST['firstName']);
$last     = trim($_POST['lastName']);
$dob      = trim($_POST['dob']);
$gender   = trim($_POST['gender']);
$street   = trim($_POST['street']);
$suburb   = trim($_POST['suburb']);
$state    = trim($_POST['state']);
$postcode = trim($_POST['postcode']);
$email    = trim($_POST['email']);
$phone    = trim($_POST['phone']);
$skills   = isset($_POST['skills']) ? $_POST['skills'] : [];
$other    = trim($_POST['other']);

$errors = [];

$ref = mysqli_real_escape_string($conn, $jobRef);
$checkJob = mysqli_query($conn, "SELECT * FROM jobs WHERE ref='$ref'");
if (!$checkJob || mysqli_num_rows($checkJob) == 0) {
    $errors[] = "Invalid job reference.";
} else {
    $job = mysqli_fetch_assoc($checkJob);
}

if (!preg_match('/^[A-Za-z]{1,20}$/', $first)) $errors[] = 'Invalid first name.';
if (!preg_match('/^[A-Za-z]{1,20}$/', $last)) $errors[] = 'Invalid last name.';
if (!preg_match('#^(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[0-2])/[0-9]{4}$#', $dob)) $errors[] = 'Invalid DOB.';
if (!in_array($gender, ['male','female','other'])) $errors[] = 'Invalid gender.';
if (strlen($street) < 1 || strlen($street) > 40) $errors[] = 'Invalid street.';
if (strlen($suburb) < 1 || strlen($suburb) > 40) $errors[] = 'Invalid suburb.';
if (!in_array($state, ['VIC','NSW','QLD','NT','WA','SA','TAS','ACT'])) $errors[] = 'Invalid state.';
if (!preg_match('/^[0-9]{4}$/', $postcode)) $errors[] = 'Invalid postcode.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email.';
if (!preg_match('/^[0-9 ]{8,12}$/', $phone)) $errors[] = 'Invalid phone.';
if (!is_array($skills) || count($skills) < 1) $errors[] = 'Select at least one skill.';

if ($errors) {
    http_response_code(422);
    echo "<main style='width:60%;margin:auto;padding:20px;background:#fff;border:1px solid #ddd;border-radius:8px'>";
    echo "<h2>Errors:</h2><ul>";
    foreach ($errors as $e) echo "<li>$e</li>";
    echo "</ul><a href='apply.php'>Back</a></main>";
    exit;
}

$dob_parts = explode('/', $dob);
$dob_sql = sprintf('%04d-%02d-%02d', $dob_parts[2], $dob_parts[1], $dob_parts[0]);

$s_html = in_array('html', $skills) ? 1 : 0;
$s_css  = in_array('css', $skills) ? 1 : 0;
$s_git  = in_array('git', $skills) ? 1 : 0;
$s_ux   = in_array('ux', $skills) ? 1 : 0;

$sql = "INSERT INTO eoi 
(jobRef, firstName, lastName, dob, gender, street, suburb, state, postcode, email, phone,
skill_html, skill_css, skill_git, skill_ux, otherSkills)
VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'sssssssssssiiiss',
    $jobRef, $first, $last, $dob_sql, $gender, $street, $suburb, $state, $postcode,
    $email, $phone, $s_html, $s_css, $s_git, $s_ux, $other
);

mysqli_stmt_execute($stmt);
$eoi_id = mysqli_insert_id($conn);

echo "
<main style='width:60%;margin:auto;padding:20px;background:#fff;border:1px solid #ddd;border-radius:8px'>
<h2>Thank you! Your EOI has been submitted😀😀😀.</h2>
<p><strong>EOI Number:</strong> $eoi_id</p>
<p><strong>Role:</strong> ".htmlspecialchars($job['title'])." (Ref: ".htmlspecialchars($job['ref']).")</p>
<a href='index.php'>Return to Home</a>
</main>";
?>
