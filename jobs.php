<?php
require_once("settings.php");

$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    die("Database connection failed.");
}

$query = "SELECT * FROM jobs ORDER BY ref";
$result = mysqli_query($conn, $query);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Group 6 Tech — Jobs</title>
  <link rel="stylesheet" href="styles/styles.css">
</head>
<body class="jobs">
  <a class="skip-link" href="#main">Skip to main content</a>
  <?php include 'header.inc'; ?>

  <main id="main">
    <h1>Open Positions</h1>

    <?php if (mysqli_num_rows($result) == 0): ?>
      <p>No job positions available.</p>
    <?php else: ?>
      <?php while ($job = mysqli_fetch_assoc($result)): ?>
        <section class="job" aria-labelledby="ref-<?php echo $job['ref']; ?>">
          <h2 id="ref-<?php echo $job['ref']; ?>">
            <?php echo htmlspecialchars($job['title']); ?>
            <small>(Ref: <?php echo htmlspecialchars($job['ref']); ?>)</small>
          </h2>

          <?php if (!empty($job['summary'])): ?>
            <p><?php echo nl2br(htmlspecialchars($job['summary'])); ?></p>
          <?php endif; ?>

          <?php if (!empty($job['responsibilities'])): ?>
            <h3>Key Responsibilities</h3>
            <ul>
              <?php foreach (explode(";", $job['responsibilities']) as $item): ?>
                <li><?php echo htmlspecialchars($item); ?></li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>

          <?php if (!empty($job['requirements'])): ?>
            <h3>Requirements</h3>
            <ul>
              <?php foreach (explode(";", $job['requirements']) as $item): ?>
                <li><?php echo htmlspecialchars($item); ?></li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>

          <p>
            <?php if (!empty($job['reports_to'])): ?>
              <strong>Reports to:</strong> <?php echo htmlspecialchars($job['reports_to']); ?>
            <?php endif; ?>

            <?php if (!empty($job['salary'])): ?>
              &nbsp;•&nbsp;
              <strong>Salary:</strong> <?php echo htmlspecialchars($job['salary']); ?>
            <?php endif; ?>
          </p>

          <?php if (!empty($job['why_love'])): ?>
            <h3>Why you’ll love this role</h3>
            <p><?php echo htmlspecialchars($job['why_love']); ?></p>
          <?php endif; ?>

          <p>
            <a href="apply.php?ref=<?php echo $job['ref']; ?>">
              Apply for <?php echo htmlspecialchars($job['title']); ?>
            </a>
          </p>
        </section>
      <?php endwhile; ?>
    <?php endif; ?>
  </main>

  <?php include 'footer.inc'; ?>
</body>
</html>
