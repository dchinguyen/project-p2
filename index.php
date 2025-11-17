<?php 
require_once "settings.php";
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) die("<p>Database connection failed.</p>");

$result = mysqli_query($conn, "SELECT * FROM jobs ORDER BY ref");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Group 6 Tech — Careers</title>
  <link rel="stylesheet" href="styles/styles.css">
</head>
<body class="home">
  <a class="skip-link" href="#main">Skip to main content</a>
  <?php include 'header.inc'; ?>

  <main id="main">

    <section class="hero" aria-labelledby="hero-title">
      <h1 id="hero-title">Build our Cloud. Build our future.</h1>
      <p class="tagline">Join Group 6 Tech and help us ship secure, reliable cloud services.</p>
      <p class="cta">
        <a class="button" href="jobs.php">See all roles</a>
        <a class="button button-secondary" href="apply.php">Apply now</a>
      </p>
    </section>

    <section aria-labelledby="featured-roles">
      <h2 id="featured-roles">Featured roles</h2>

      <ul class="card-list">
        <?php while($job = mysqli_fetch_assoc($result)): ?>
          <li class="card">
            <h3>
              <a href="jobs.php?ref=<?php echo htmlspecialchars($job['ref']); ?>">
                <?php echo htmlspecialchars($job['title']); ?>
              </a>
            </h3>

            <p>
              <?php echo htmlspecialchars(substr($job['summary'], 0, 120)); ?>…
            </p>

            <p class="card-buttons">
              <a class="button button-small"
                 href="jobs.php?ref=<?php echo htmlspecialchars($job['ref']); ?>">
                View details
              </a>

              <a class="button button-secondary button-small" 
                 href="apply.php?ref=<?php echo htmlspecialchars($job['ref']); ?>">
                Apply now
              </a>
            </p>
          </li>
        <?php endwhile; ?>
      </ul>
    </section>

    <aside aria-labelledby="contact-quick">
      <h2 id="contact-quick">Quick contact</h2>
      <p>Questions about the roles?</p>
      <p><a href="mailto:info@Group6Tech.com.au">info@Group6Tech.com.au</a></p>
    </aside>

  </main>

  <?php include 'footer.inc'; ?>
</body>
</html>
