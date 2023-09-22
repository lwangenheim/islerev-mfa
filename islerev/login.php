<?php
session_start();

// Your database connection code here...
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "islerev";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_POST['adminUser']) && isset($_POST['adminPass'])) {
    $adminUser = $_POST['adminUser'];
    $adminPass = $_POST['adminPass'];

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username=? AND password=?");
    $stmt->bind_param('ss', $adminUser, $adminPass);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $_SESSION['tmp_authenticated'] = true;
        $_SESSION['MFA_CODE'] = rand(100000, 999999); // Generating a 6-digit code
        echo "Your MFA code is: " . $_SESSION['MFA_CODE']; // Simulated "sending" of the code
    } else {
        echo "Invalid credentials!";
    }
}

// Check for MFA input
if (isset($_POST['MFA']) && $_SESSION['tmp_authenticated']) {
    if ($_POST['MFA'] == $_SESSION['MFA_CODE']) {
        $_SESSION['authenticated'] = true;
        header('Location: clientInfo.php');
        exit();
    } else {
        echo "Invalid MFA code!";
    }
}

?>

<!-- Display form based on session state -->
<?php if(!isset($_SESSION['tmp_authenticated'])): ?>
    <form action="login.php" method="post">
        Username: <input type="text" name="adminUser" required><br>
        Password: <input type="password" name="adminPass" required><br>
        <input type="submit" value="Login">
    </form>
<?php else: ?>
    <form action="login.php" method="post">
        Enter MFA Code: <input type="text" name="MFA" required><br>
        <input type="submit" value="Verify">
    </form>
<?php endif; ?>







<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Islerev Motorcycles</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Islerev Motorcycles</h1>
        <img src="images/logo.png" alt="Islerev Logo" width="200">
    </header>

    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="login.php">Admin Login</a></li>
        </ul>
    </nav>
  <main>    
    <form action="login.php" method="post">
        Username: <input type="text" name="adminUser"><br>
        Password: <input type="password" name="adminPass"><br>
        <input type="submit" value="Login">
    </form>



<footer>
        <p>&copy; 2023 Islerev Motorcycles - Isle of Man</p>
    </footer>
</body>
</html>

