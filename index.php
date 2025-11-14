<?php /* index.php — Home */ ?>
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
        <a class="button" href="jobs.php#ref-CE7C1">See the role</a>
        <a class="button button-secondary" href="apply.php">Apply now</a>
      </p>
    </section>

    <section aria-labelledby="featured-roles">
      <h2 id="featured-roles">Featured role</h2>
      <ul class="card-list">
        <li class="card">
          <h3><a href="jobs.php#ref-CE7C1">Cloud Engineer</a></h3>
          <p>Design, secure, and automate our cloud platform for scale and reliability.</p>
        </li>
      </ul>
    </section>

    <aside aria-labelledby="contact-quick">
      <h2 id="contact-quick">Quick contact</h2>
      <p>Questions about the role?</p>
      <p><a href="mailto:info@companyname.com.au">info@Group6Tech.com.au</a></p>
    </aside>
  </main>

  <?php include 'footer.inc'; ?>
</body>
</html>
