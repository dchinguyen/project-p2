<?php
require_once "settings.php";
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) die("<p>Database connection failed.</p>");

$ref = isset($_GET['ref']) ? trim($_GET['ref']) : "";

$singleJob = null;
if ($ref !== "") {
    $r = mysqli_real_escape_string($conn, $ref);
    $q = mysqli_query($conn, "SELECT * FROM jobs WHERE ref='$r'");
    if ($q && mysqli_num_rows($q) > 0) {
        $singleJob = mysqli_fetch_assoc($q);
    }
}

$allJobs = mysqli_query($conn, "SELECT * FROM jobs ORDER BY ref");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Jobs — Group 6 Tech</title>
  <link rel="stylesheet" href="styles/styles.css">
</head>
<body class="jobs">
  <?php include "header.inc"; ?>

  <main id="main">

    <?php if ($singleJob): ?>

      <section class="job-detail">
        <h1><?php echo htmlspecialchars($singleJob['title']); ?>
          <small>(Ref: <?php echo htmlspecialchars($singleJob['ref']); ?>)</small>
        </h1>

        <p class="summary"><?php echo nl2br(htmlspecialchars($singleJob['summary'])); ?></p>

        <?php
        $resp = json_decode($singleJob['responsibilities'], true);
        if ($resp && is_array($resp)):
        ?>
          <h2>Key Responsibilities</h2>
          <ul>
            <?php foreach ($resp as $r): ?>
              <li><?php echo htmlspecialchars($r); ?></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>

        <?php
        $req = json_decode($singleJob['requirements'], true);
        if ($req && is_array($req)):
        ?>
          <h2>Requirements</h2>
          <ol>
            <?php foreach ($req as $r): ?>
              <li><?php echo htmlspecialchars($r); ?></li>
            <?php endforeach; ?>
          </ol>
        <?php endif; ?>

        <p>
          <strong>Reports to:</strong> <?php echo htmlspecialchars($singleJob['reports_to']); ?> •
          <strong>Salary:</strong> <?php echo htmlspecialchars($singleJob['salary']); ?>
        </p>

        <?php if (!empty($singleJob['why_love'])): ?>
          <h2>Why you'll love this role</h2>
          <p><?php echo nl2br(htmlspecialchars($singleJob['why_love'])); ?></p>
        <?php endif; ?>

        <p>
          <a class="button" href="apply.php?ref=<?php echo htmlspecialchars($singleJob['ref']); ?>">
            Apply now
          </a>
          <a class="button button-secondary" href="jobs.php">Back to all jobs</a>
        </p>
      </section>

    <?php else: ?>

      <h1>Open Positions</h1>

      <ul class="job-list">
        <?php while ($job = mysqli_fetch_assoc($allJobs)): ?>
          <li class="job-card">
            <h2>
              <a href="jobs.php?ref=<?php echo htmlspecialchars($job['ref']); ?>">
                <?php echo htmlspecialchars($job['title']); ?>
              </a>
            </h2>

            <p><?php echo htmlspecialchars(substr($job['summary'], 0, 150)); ?>…</p>

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

    <?php endif; ?>

  </main>

  <?php include "footer.inc"; ?>
</body>
</html>
