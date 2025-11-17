<?php

$jobs = [
  [
    'ref'            => 'CE7C1',
    'title'          => 'Cloud Engineer',
    'summary'        =>
      "We’re hiring a Cloud Engineer to design, automate, and operate secure, reliable cloud infrastructure. " .
      "You’ll build reusable platform components, improve observability and incident response, and partner with " .
      "product teams to ship fast with confidence.",
    'responsibilities' => [
      "Design and maintain scalable cloud infrastructure.",
      "Build reusable platform components that other teams can adopt.",
      "Set up monitoring, logging, alerting, and meaningful SLOs.",
      "Partner with product teams to enable reliable, frequent releases."
    ],
    'requirements' => [
      "Essential: 2–4 years working with cloud platforms; understanding of networking, security groups, and automation; clear communication.",
      "Preferable: Experience with CI/CD, infrastructure-as-code, and observability tools."
    ],
    'reports_to'    => 'Platform Engineering Manager',
    'salary'        => 'Competitive, commensurate with experience',
    'why_love'      =>
      "You’ll work in small squads with autonomy, supportive peers, and modern tooling so you can ship, learn, and improve continuously."
  ]
];

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

    <?php if (empty($jobs)): ?>
      <p>No open positions at the moment.</p>
    <?php else: ?>
      <?php foreach ($jobs as $job): ?>
        <section class="job" aria-labelledby="ref-<?php echo htmlspecialchars($job['ref']); ?>">
          <h2 id="ref-<?php echo htmlspecialchars($job['ref']); ?>">
            <?php echo htmlspecialchars($job['title']); ?>
            <small>(Ref: <?php echo htmlspecialchars($job['ref']); ?>)</small>
          </h2>

          <?php if (!empty($job['summary'])): ?>
            <p class="summary">
              <?php echo nl2br(htmlspecialchars($job['summary'])); ?>
            </p>
          <?php endif; ?>

          <?php if (!empty($job['responsibilities'])): ?>
            <h3>Key Responsibilities</h3>
            <ul>
              <?php foreach ($job['responsibilities'] as $item): ?>
                <li><?php echo htmlspecialchars($item); ?></li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>

          <?php if (!empty($job['requirements'])): ?>
            <h3>Requirements</h3>
            <ol>
              <?php foreach ($job['requirements'] as $item): ?>
                <li><?php echo htmlspecialchars($item); ?></li>
              <?php endforeach; ?>
            </ol>
          <?php endif; ?>

          <p>
            <?php if (!empty($job['reports_to'])): ?>
              <strong>Reports to:</strong>
              <?php echo htmlspecialchars($job['reports_to']); ?>
            <?php endif; ?>

            <?php if (!empty($job['salary'])): ?>
              &nbsp;•&nbsp;
              <strong>Salary:</strong>
              <?php echo htmlspecialchars($job['salary']); ?>
            <?php endif; ?>
          </p>

          <?php if (!empty($job['why_love'])): ?>
            <h3>Why you’ll love this role</h3>
            <p><?php echo htmlspecialchars($job['why_love']); ?></p>
          <?php endif; ?>

          <p>
            <a href="apply.php">
              Apply for <?php echo htmlspecialchars($job['title']); ?> now
            </a>
          </p>
        </section>
      <?php endforeach; ?>
    <?php endif; ?>
  </main>

  <?php include 'footer.inc'; ?>
</body>
</html>
