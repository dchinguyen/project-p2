<?php
$job = [
  'ref'   => 'CE7C1',
  'title' => 'Cloud Engineer'
];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Apply — <?php echo htmlspecialchars($job['title']); ?> at Group 6 Tech</title>
  <link rel="stylesheet" href="styles/styles.css">
</head>
<body class="apply">
  <a class="skip-link" href="#main">Skip to main content</a>
  <?php include 'header.inc'; ?>

  <main id="main">
    <h1>
      Apply — <?php echo htmlspecialchars($job['title']); ?>
      (Ref: <?php echo htmlspecialchars($job['ref']); ?>)
    </h1>
    <p>Please complete all required fields. Server-side validation will run on submit.</p>

    <!-- novalidate: we want to exercise server-side checks for Part 2 -->
    <form class="app-form" action="process_eoi.php" method="post" novalidate="novalidate">
      <table class="form-table" border="1" cellpadding="6" cellspacing="0">
        <!-- Job details -->
        <tr>
          <th colspan="2" style="text-align:left">Job Details</th>
        </tr>
        <tr>
          <th><label for="jobRef">Job reference *</label></th>
          <td>
            <select id="jobRef" name="jobRef" required>
              <!-- If you add more jobs later, just add more <option> here -->
              <option value="<?php echo htmlspecialchars($job['ref']); ?>">
                <?php echo htmlspecialchars($job['ref']); ?> — <?php echo htmlspecialchars($job['title']); ?>
              </option>
            </select>
          </td>
        </tr>

        <!-- Personal information -->
        <tr>
          <th colspan="2" style="text-align:left">Personal Details</th>
        </tr>
        <tr>
          <th><label for="firstName">First name *</label></th>
          <td><input id="firstName" name="firstName" maxlength="20" required></td>
        </tr>
        <tr>
          <th><label for="lastName">Last name *</label></th>
          <td><input id="lastName" name="lastName" maxlength="20" required></td>
        </tr>
        <tr>
          <th><label for="dob">Date of birth (dd/mm/yyyy) *</label></th>
          <td><input id="dob" name="dob" placeholder="dd/mm/yyyy" required></td>
        </tr>
        <tr>
          <th>Gender *</th>
          <td>
            <label><input type="radio" name="gender" value="female" required> Female</label>
            <label><input type="radio" name="gender" value="male"> Male</label>
            <label><input type="radio" name="gender" value="other"> Other</label>
          </td>
        </tr>

        <!-- Address -->
        <tr>
          <th colspan="2" style="text-align:left">Address</th>
        </tr>
        <tr>
          <th><label for="street">Street address *</label></th>
          <td><input id="street" name="street" maxlength="40" required></td>
        </tr>
        <tr>
          <th><label for="suburb">Suburb/Town *</label></th>
          <td><input id="suburb" name="suburb" maxlength="40" required></td>
        </tr>
        <tr>
          <th><label for="state">State *</label></th>
          <td>
            <select id="state" name="state" required>
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
        <tr>
          <th><label for="postcode">Postcode (4 digits) *</label></th>
          <td><input id="postcode" name="postcode" maxlength="4" required></td>
        </tr>

        <!-- Contact -->
        <tr>
          <th colspan="2" style="text-align:left">Contact</th>
        </tr>
        <tr>
          <th><label for="email">Email *</label></th>
          <td><input id="email" name="email" required></td>
        </tr>
        <tr>
          <th><label for="phone">Phone *</label></th>
          <td><input id="phone" name="phone" required></td>
        </tr>

        <!-- Skills -->
        <tr>
          <th colspan="2" style="text-align:left">Technical Skills</th>
        </tr>
        <tr>
          <th>Required technical skills *</th>
          <td>
            <div class="checkboxes">
              <label><input type="checkbox" name="skills[]" value="html"> HTML</label>
              <label><input type="checkbox" name="skills[]" value="css"> CSS</label>
              <label><input type="checkbox" name="skills[]" value="git"> Git</label>
              <label><input type="checkbox" name="skills[]" value="ux"> Accessibility/UX</label>
            </div>
          </td>
        </tr>
        <tr>
          <th><label for="other">Other skills (optional)</label></th>
          <td>
            <textarea id="other" name="other" rows="5" cols="40"
              placeholder="Briefly describe any additional skills or experience…"></textarea>
          </td>
        </tr>

        <!-- Actions -->
        <tr>
          <td colspan="2" style="text-align:center">
            <button type="submit">Apply</button>
            <button type="reset">Clear form</button>
          </td>
        </tr>
      </table>
    </form>
  </main>

  <?php include 'footer.inc'; ?>
</body>
</html>
