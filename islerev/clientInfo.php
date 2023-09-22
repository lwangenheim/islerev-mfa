<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$db   = 'islerev';
$user = 'root'; // Replace with your database username
$pass = ''; // Replace with your database password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

session_start();

// Check if the logout request has been made
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    // Unset the session variable and destroy the session
    unset($_SESSION['authenticated']);
    session_destroy();

    // Redirect to the login page
    header('Location: login.php');
    exit();
}

// Check for authentication
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: login.php');
    exit();
}

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $stmt = $pdo->query('SELECT id, name, email, address, bank_account_number, sort_code, phone, credit_card FROM clients');
    $clients = $stmt->fetchAll();
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Info - Islerev Motorcycles</title>
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
            <li><a href="clientInfo.php?logout=true">Logout</a></li>
        </ul>
    </nav>
    <main>
    <div class="container">
        <h2>Client Information</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Bank Account Number</th>
                    <th>Sort Code</th>
                    <th>Phone</th>
                    <th>Credit Card</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= htmlspecialchars($client['id']) ?></td>
                        <td><?= htmlspecialchars($client['name']) ?></td>
                        <td><?= htmlspecialchars($client['email']) ?></td>
                        <td><?= htmlspecialchars($client['address']) ?></td>
                        <td><?= htmlspecialchars($client['bank_account_number']) ?></td>
                        <td><?= htmlspecialchars($client['sort_code']) ?></td>
                        <td><?= htmlspecialchars($client['phone']) ?></td>
                        <td><?= htmlspecialchars($client['credit_card']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="logout-section">
            <a href="clientInfo.php?logout=true">Logout</a>
        </div>
    </div>

    <script src="scripts.js"></script>




    <footer>
        <p>&copy; 2023 Islerev Motorcycles - Isle of Man</p>
    </footer>
</body>
</html>
