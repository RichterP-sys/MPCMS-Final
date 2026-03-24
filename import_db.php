<?php
// Import database to Railway MySQL

$host = 'nozomi.proxy.rlwy.net';
$port = 35252;
$database = 'railway';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to Railway MySQL successfully!\n";
    
    $sql = file_get_contents('mpcms_backup.sql');
    
    if ($sql === false) {
        die("Error: Could not read mpcms_backup.sql file\n");
    }
    
    echo "Importing database...\n";
    $pdo->exec($sql);
    
    echo "Database imported successfully!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
