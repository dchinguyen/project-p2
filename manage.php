<?php
include("header.inc");
include("nav.inc");
require_once("settings.php");
session_start();

$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) die("<main><p>Database connection failed.</p></main>");

if (!isset($_SESSION['logged_in'])) {

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {

        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        $query = "SELECT * FROM managers WHERE username = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user) {

            $now = time();
            $last_attempt = strtotime($user['last_attempt']);

            if ($user['login_attempts'] >= 3 && ($now - $last_attempt < 300)) {
                echo "<main><p class='error-msg'>Account locked for 5 minutes due to too many failed attempts.</p></main>";
            }
            elseif (hash('sha256', $password) == strtolower($user['password'])) {

                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $username;

                mysqli_query($conn, "UPDATE managers SET login_attempts = 0 WHERE username = '$username'");

                header("Location: manage.php");
                exit;

            } else {
                mysqli_query($conn, "UPDATE managers 
                                     SET login_attempts = login_attempts + 1, last_attempt = NOW() 
                                     WHERE username = '$username'");
                echo "<main><p class='error-msg'>Invalid credentials.</p></main>";
            }

        } else {
            echo "<main><p class='error-msg'>User not found.</p></main>";
        }
    }

    echo '
    <main class="login-container">
        <h2>Cloud Engineer Manager Login</h2>
        <form method="POST" action="manage.php" class="login-form">
            <input type="text" name="username" placeholder="Username" required class="input-field">
            <input type="password" name="password" placeholder="Password" required class="input-field">
            <button type="submit" name="login" class="btn-primary">Login</button>
        </form>
    </main>';

    include("footer.inc");
    exit;
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: manage.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    mysqli_query($conn, "DELETE FROM eoi WHERE EOInumber = $id");
    header("Location: manage.php");
    exit;
}

if (isset($_POST['update_status'])) {
    $id = (int) $_POST['eoi_id'];
    $status = $_POST['status'];
    mysqli_query($conn, "UPDATE eoi SET status = '$status' WHERE EOInumber = $id");
    header("Location: manage.php");
    exit;
}

$jobRef = trim($_GET['jobRef'] ?? "");
$fname  = trim($_GET['fname'] ?? "");
$lname  = trim($_GET['lname'] ?? "");
$sort   = $_GET['sort'] ?? "EOInumber";

$where = [];
if ($jobRef !== "") $where[] = "jobRef = '" . mysqli_real_escape_string($conn, $jobRef) . "'";
if ($fname  !== "") $where[] = "fname LIKE '%" . mysqli_real_escape_string($conn, $fname) . "%'";
if ($lname  !== "") $where[] = "lname LIKE '%" . mysqli_real_escape_string($conn, $lname) . "%'";

$whereSQL = $where ? "WHERE " . implode(" AND ", $where) : "";
$query = "SELECT * FROM eoi $whereSQL ORDER BY $sort";
$result = mysqli_query($conn, $query);
?>

<main class="manage-container">
    <h2>EOI Management – Cloud Engineer Recruitment</h2>
    <p class="welcome">Welcome, <?php echo $_SESSION['username']; ?>! 
        <a href="manage.php?logout=true" class="logout-link">Logout</a>
    </p>

    <form method="GET" action="manage.php" class="search-bar">
        <input type="text" name="jobRef" placeholder="Job Ref" value="<?php echo htmlspecialchars($jobRef); ?>">
        <input type="text" name="fname" placeholder="First Name" value="<?php echo htmlspecialchars($fname); ?>">
        <input type="text" name="lname" placeholder="Last Name" value="<?php echo htmlspecialchars($lname); ?>">
        <select name="sort">
            <option value="EOInumber" <?php if ($sort == "EOInumber") echo "selected"; ?>>Sort by ID</option>
            <option value="jobRef" <?php if ($sort == "jobRef") echo "selected"; ?>>Sort by Job Ref</option>
            <option value="lname" <?php if ($sort == "lname") echo "selected"; ?>>Sort by Last Name</option>
        </select>
        <button type="submit" class="btn-primary">Search</button>
    </form>

    <table class="styled-table">
        <tr>
            <th>ID</th>
            <th>Job Ref</th>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {

                echo "<tr>
                        <td>{$row['EOInumber']}</td>
                        <td>{$row['jobRef']}</td>
                        <td>{$row['fname']} {$row['lname']}</td>
                        <td>{$row['email']}</td>
                        <td>
                            <form method='POST' class='status-form'>
                                <input type='hidden' name='eoi_id' value='{$row['EOInumber']}'>
                                <select name='status'>
                                    <option ".($row['status']=='New'?'selected':'').">New</option>
                                    <option ".($row['status']=='Current'?'selected':'').">Current</option>
                                    <option ".($row['status']=='Final'?'selected':'').">Final</option>
                                </select>
                                <button type='submit' name='update_status' class='btn-update'>Update</button>
                            </form>
                        </td>
                        <td>
                            <a href='manage.php?delete={$row['EOInumber']}' class='btn-delete'>Delete</a>
                        </td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No EOI records found.</td></tr>";
        }
        ?>
    </table>
</main>

<?php include("footer.inc"); ?>
