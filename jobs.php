<?php include("header.inc"); include("nav.inc"); ?>
<main>
<h2>Available Job Openings</h2>
<?php
require_once("settings.php");
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    echo "<p>Database connection failed.</p>";
} else {
    $query = "SELECT * FROM jobs";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='job-card'>
                    <h3>{$row['title']} ({$row['jobRef']})</h3>
                    <p>{$row['description']}</p>
                    <p><strong>Salary:</strong> {$row['salary']}</p>
                    <a href='apply.php?jobRef={$row['jobRef']}'>Apply Now</a>
                  </div>";
        }
    } else {
        echo "<p>No jobs available.</p>";
    }
    mysqli_close($conn);
}
?>
<p><a href="manage.php">Go to Manager Page →</a></p>
</main>
<?php include("footer.inc"); ?>
