<?php
require_once "settings.php";
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) die("<p>Database connection failed.</p>");

$jobRef = isset($_GET['ref']) ? trim($_GET['ref']) : "";

// Get selected job if ref is provided
$job = null;
if ($jobRef !== "") {
    $ref = mysqli_real_escape_string($conn, $jobRef);
    $res = mysqli_query($conn, "SELECT * FROM jobs WHERE ref='$ref'");
    if ($res && mysqli_num_rows($res) > 0) {
        $job = mysqli_fetch_assoc($res);
    }
}

// Load all jobs for dropdown
$jobList = mysqli_query($conn, "SELECT * FROM jobs ORDER BY ref");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Apply<?php echo $job ? " — " . htmlspecialchars($job['title']) : ""; ?></title>
  <link rel="stylesheet" href="styles/styles.css">
</head>
<body class="apply">
  <a class="skip-link" href="#main">Skip to main content</a>
  <?php include 'header.inc'; ?>

  <main id="main">
    <h1>
      Apply
      <?php if ($job): ?>
        — <?php echo htmlspecialchars($job['title']); ?> (Ref: <?php echo htmlspecialchars($job['ref']); ?>)
      <?php endif; ?>
    </h1>

    <form class="app-form" action="process_eoi.php" method="post" novalidate>
      <table class="form-table" border="1" cellpadding="6">

        <tr><th colspan="2">Job Details</th></tr>
        <tr>
          <th><label for="jobRef">Job reference *</label></th>
          <td>
            <select id="jobRef" name="jobRef" required>
              <option value="">-- Select job --</option>
              
              <?php while ($j = mysqli_fetch_assoc($jobList)): ?>
                <option value="<?php echo htmlspecialchars($j['ref']); ?>"
                  <?php if ($job && $job['ref'] == $j['ref']) echo "selected"; ?>>
                  <?php echo htmlspecialchars($j['ref']); ?> — <?php echo htmlspecialchars($j['title']); ?>
                </option>
              <?php endwhile; ?>

            </select>
          </td>
        </tr>

        <tr><th colspan="2">Personal Details</th></tr>
        <tr><th>First name *</th><td><input name="firstName" maxlength="20" required></td></tr>
        <tr><th>Last name *</th><td><input name="lastName" maxlength="20" required></td></tr>
        <tr><th>Date of birth *</th><td><input name="dob" placeholder="dd/mm/yyyy" required></td></tr>

        <tr>
          <th>Gender *</th>
          <td>
            <label><input type="radio" name="gender" value="female" required> Female</label>
            <label><input type="radio" name="gender" value="male"> Male</label>
            <label><input type="radio" name="gender" value="other"> Other</label>
          </td>
        </tr>

        <tr><th colspan="2">Address</th></tr>
        <tr><th>Street *</th><td><input name="street" maxlength="40" required></td></tr>
        <tr><th>Suburb *</th><td><input name="suburb" maxlength="40" required></td></tr>

        <tr>
          <th>State *</th>
          <td>
            <select name="state" required>
              <option value="">-- Select --</option>
              <option value="VIC">VIC</option>
              <option value="NSW">NSW</option>
              <option value="QLD">QLD</option>
              <option value="NT">NT</option>
              <option value="WA">WA</option>
              <option value="SA">SA</option>
              <option value="TAS">TAS</option>
              <option value="ACT">ACT</option>
            </select>
          </td>
        </tr>

        <tr><th>Postcode *</th><td><input name="postcode" maxlength="4" required></td></tr>

        <tr><th colspan="2">Contact</th></tr>
        <tr><th>Email *</th><td><input name="email" required></td></tr>
        <tr><th>Phone *</th><td><input name="phone" required></td></tr>

        <tr><th colspan="2">Technical Skills</th></tr>
        <tr>
          <th>Required skills *</th>
          <td>
            <label><input type="checkbox" name="skills[]" value="html"> HTML</label>
            <label><input type="checkbox" name="skills[]" value="css"> CSS</label>
            <label><input type="checkbox" name="skills[]" value="git"> Git</label>
            <label><input type="checkbox" name="skills[]" value="ux"> UX</label>
          </td>
        </tr>

        <tr>
          <th>Other skills</th>
          <td><textarea name="other" rows="5" cols="40"></textarea></td>
        </tr>

        <tr>
          <td colspan="2" style="text-align:center">
            <button type="submit">Apply</button>
            <button type="reset">Clear</button>
          </td>
        </tr>

      </table>
    </form>
  </main>

  <?php include 'footer.inc'; ?>
</body>
</html>
