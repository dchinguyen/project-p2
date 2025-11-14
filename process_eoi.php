<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: apply.php'); exit;
}

include 'settings.php';

$jobRef   = isset($_POST['jobRef'])   ? trim($_POST['jobRef'])   : '';
$first    = isset($_POST['firstName'])? trim($_POST['firstName']): '';
$last     = isset($_POST['lastName']) ? trim($_POST['lastName']) : '';
$dob      = isset($_POST['dob'])      ? trim($_POST['dob'])      : '';
$gender   = isset($_POST['gender'])   ? trim($_POST['gender'])   : '';
$street   = isset($_POST['street'])   ? trim($_POST['street'])   : '';
$suburb   = isset($_POST['suburb'])   ? trim($_POST['suburb'])   : '';
$state    = isset($_POST['state'])    ? trim($_POST['state'])    : '';
$postcode = isset($_POST['postcode']) ? trim($_POST['postcode']) : '';
$email    = isset($_POST['email'])    ? trim($_POST['email'])    : '';
$phone    = isset($_POST['phone'])    ? trim($_POST['phone'])    : '';
$skills   = isset($_POST['skills'])   ? $_POST['skills']         : [];
$other    = isset($_POST['other'])    ? trim($_POST['other'])    : '';

$errors = [];

if ($jobRef !== 'CE7C1') $errors[] = 'Invalid job reference.';

if (!preg_match('/^[A-Za-z]{1,20}$/', $first)) $errors[] = 'First name must be A–Z and ≤ 20 chars.';
if (!preg_match('/^[A-Za-z]{1,20}$/', $last))  $errors[] = 'Last name must be A–Z and ≤ 20 chars.';

if (!preg_match('#^(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[0-2])/[0-9]{4}$#', $dob)) {
  $errors[] = 'DOB must be dd/mm/yyyy.';
} else {
  list($d,$m,$y) = explode('/', $dob);
  if (!checkdate((int)$m,(int)$d,(int)$y)) $errors[] = 'DOB is not a real date.';
}

if (!in_array($gender, ['female','male','other'], true)) $errors[] = 'Invalid gender.';

if ($street==='' || strlen($street)>40) $errors[] = 'Street required (≤ 40 chars).';
if ($suburb==='' || strlen($suburb)>40) $errors[] = 'Suburb required (≤ 40 chars).';
$validStates = ['VIC','NSW','QLD','NT','WA','SA','TAS','ACT'];
if (!in_array($state, $validStates, true)) $errors[] = 'Invalid state.';
if (!preg_match('/^\d{4}$/', $postcode)) $errors[] = 'Postcode must be exactly 4 digits.';

function pc_ok($state,$pc){
  $p=(int)$pc;
  if ($state==='ACT') return ($p>=200 && $p<=299) || ($p>=2600 && $p<=2639) || ($p>=2900 && $p<=2920);
  if ($state==='NSW') return ($p>=1000 && $p<=1999) || ($p>=2000 && $p<=2599) || ($p>=2619 && $p<=2899) || ($p>=2921 && $p<=2999);
  if ($state==='VIC') return ($p>=3000 && $p<=3999) || ($p>=8000 && $p<=8999);
  if ($state==='QLD') return ($p>=4000 && $p<=4999) || ($p>=9000 && $p<=9999);
  if ($state==='SA')  return ($p>=5000 && $p<=5799);
  if ($state==='WA')  return ($p>=6000 && $p<=6797);
  if ($state==='TAS') return ($p>=7000 && $p<=7799);
  if ($state==='NT')  return ($p>=800 && $p<=899) || ($p>=900 && $p<=999);
  return false;
}
if (preg_match('/^\d{4}$/', $postcode) && !pc_ok($state,$postcode)) {
  $errors[] = 'Postcode does not match the selected state.';
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email address.';
if (!preg_match('/^[0-9 ]{8,12}$/', $phone)) $errors[] = 'Phone must be 8–12 digits (spaces allowed).';

/* skills: at least one required */
if (!is_array($skills) || count($skills)<1) $errors[] = 'Select at least one required technical skill.';

if ($errors) {
  http_response_code(422);
  echo "<!doctype html><html lang='en'><head><meta charset='utf-8'>
        <title>Application errors</title><link rel='stylesheet' href='styles/styles.css'></head><body>
        <main style='max-width:60rem;margin:2rem auto;padding:1rem;border:1px solid #ddd'>
        <h1>There were problems with your application</h1><ul>";
  foreach ($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>';
  echo "</ul><p><a href='apply.php'>Go back and fix the form</a></p></main></body></html>";
  exit;
}

$createSql = "
CREATE TABLE IF NOT EXISTS eoi (
  EOInumber INT AUTO_INCREMENT PRIMARY KEY,
  jobRef VARCHAR(10) NOT NULL,
  firstName VARCHAR(20) NOT NULL,
  lastName  VARCHAR(20) NOT NULL,
  dob DATE NOT NULL,
  gender ENUM('female','male','other') NOT NULL,
  street   VARCHAR(40) NOT NULL,
  suburb   VARCHAR(40) NOT NULL,
  state    ENUM('VIC','NSW','QLD','NT','WA','SA','TAS','ACT') NOT NULL,
  postcode CHAR(4) NOT NULL,
  email    VARCHAR(254) NOT NULL,
  phone    VARCHAR(12) NOT NULL,
  skill_html TINYINT DEFAULT 0,
  skill_css  TINYINT DEFAULT 0,
  skill_git  TINYINT DEFAULT 0,
  skill_ux   TINYINT DEFAULT 0,
  otherSkills TEXT,
  status ENUM('New','Current','Final') NOT NULL DEFAULT 'New',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";
mysqli_query($conn, $createSql);

$s_html = in_array('html',$skills,true) ? 1 : 0;
$s_css  = in_array('css', $skills,true) ? 1 : 0;
$s_git  = in_array('git', $skills,true) ? 1 : 0;
$s_ux   = in_array('ux',  $skills,true) ? 1 : 0;

list($d,$m,$y) = explode('/', $dob);
$dob_sql = sprintf('%04d-%02d-%02d', (int)$y,(int)$m,(int)$d);

$sql = "INSERT INTO eoi
(jobRef, firstName, lastName, dob, gender, street, suburb, state, postcode, email, phone,
 skill_html, skill_css, skill_git, skill_ux, otherSkills)
VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) { die('Prepare failed: '.mysqli_error($conn)); }

$phone_digits = preg_replace('/\s+/', '', $phone); // store digits only
mysqli_stmt_bind_param(
  $stmt, 'ssssssssssssssss',
  $jobRef, $first, $last, $dob_sql, $gender, $street, $suburb, $state, $postcode,
  $email, $phone_digits, $s_html, $s_css, $s_git, $s_ux, $other
);
$ok = mysqli_stmt_execute($stmt);
if (!$ok) { die('Insert failed: '.mysqli_stmt_error($stmt)); }

$eoi_id = mysqli_insert_id($conn);
?>

echo "<!doctype html><html lang='en'><head><meta charset='utf-8'>
      <meta name='viewport' content='width=device-width, initial-scale=1'>
      <title>Application received</title>
      <link rel='stylesheet' href='styles/styles.css'></head><body>
      <main style='max-width:60rem;margin:2rem auto;padding:1rem;border:1px solid #ddd'>
      <h1>Thanks! Your application has been received.</h1>
      <p><strong>EOI Number:</strong> ".(int)$eoi_id."</p>
      <p>Role: Cloud Engineer (Ref: CE7C1)</p>
      <p><a href='index.php'>Return to Home</a></p>
      </main></body></html>";
      
