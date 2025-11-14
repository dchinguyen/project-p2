
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Apply — Cloud Engineer at Group 6 Tech</title>
  <link rel="stylesheet" href="styles/styles.css">
</head>
<body class="apply">
  <a class="skip-link" href="#main">Skip to main content</a>
  <?php include 'header.inc'; ?>

  <main id="main">
    <h1>Apply — Cloud Engineer (Ref: CE7C1)</h1>
    <p>Please complete all required fields. Server-side validation will run on submit.</p>

    <!-- novalidate: we want to exercise server-side checks for Part 2 -->
    <form class="app-form" action="process_eoi.php" method="post" novalidate="novalidate">
      <fieldset class="section" aria-labelledby="job-details-h">
        <legend id="job-details-h">Job Details</legend>
        <label for="jobRef">Job reference *</label>
        <select id="jobRef" name="jobRef" required>
          <option value="CE7C1">CE7C1 — Cloud Engineer</option>
        </select>
      </fieldset>

      <fieldset class="section" aria-labelledby="personal-h">
        <legend id="personal-h">Personal Information</legend>
        <div class="grid-2">
          <div>
            <label for="firstName">First name *</label>
            <input id="firstName" name="firstName" maxlength="20" required>
          </div>
          <div>
            <label for="lastName">Last name *</label>
            <input id="lastName" name="lastName" maxlength="20" required>
          </div>
          <div>
            <label for="dob">Date of birth (dd/mm/yyyy) *</label>
            <input id="dob" name="dob" placeholder="dd/mm/yyyy" required>
          </div>
          <div>
            <fieldset>
              <legend>Gender *</legend>
              <label><input type="radio" name="gender" value="female" required> Female</label>
              <label><input type="radio" name="gender" value="male"> Male</label>
              <label><input type="radio" name="gender" value="other"> Other</label>
            </fieldset>
          </div>
        </div>
      </fieldset>

      <fieldset class="section" aria-labelledby="address-h">
        <legend id="address-h">Address</legend>
        <label for="street">Street address *</label>
        <input id="street" name="street" maxlength="40" required>

        <div class="grid-3">
          <div>
            <label for="suburb">Suburb/Town *</label>
            <input id="suburb" name="suburb" maxlength="40" required>
          </div>
          <div>
            <label for="state">State *</label>
            <select id="state" name="state" required>
              <option>VIC</option><option>NSW</option><option>QLD</option><option>NT</option>
              <option>WA</option><option>SA</option><option>TAS</option><option>ACT</option>
            </select>
          </div>
          <div>
            <label for="postcode">Postcode (4 digits) *</label>
            <input id="postcode" name="postcode" maxlength="4" required>
          </div>
        </div>
      </fieldset>

      <fieldset class="section" aria-labelledby="contact-h">
        <legend id="contact-h">Contact</legend>
        <div class="grid-2">
          <div>
            <label for="email">Email *</label>
            <input id="email" name="email" required>
          </div>
          <div>
            <label for="phone">Phone (8–12 digits or spaces) *</label>
            <input id="phone" name="phone" required>
          </div>
        </div>
      </fieldset>

      <fieldset class="section" aria-labelledby="skills-h">
        <legend id="skills-h">Skills</legend>
        <fieldset>
          <legend>Required technical skills *</legend>
          <div class="checkboxes">
            <label><input type="checkbox" name="skills[]" value="html"> HTML</label>
            <label><input type="checkbox" name="skills[]" value="css"> CSS</label>
            <label><input type="checkbox" name="skills[]" value="git"> Git</label>
            <label><input type="checkbox" name="skills[]" value="ux"> Accessibility/UX</label>
          </div>
        </fieldset>

        <label for="other">Other skills (optional)</label>
        <textarea id="other" name="other" rows="5" cols="40"
                  placeholder="Briefly describe any additional skills or experience…"></textarea>
      </fieldset>

      <p class="form-actions">
        <button type="submit">Apply</button>
        <button type="reset">Clear form</button>
      </p>
    </form>
  </main>

  <?php include 'footer.inc'; ?>
</body>
</html>
