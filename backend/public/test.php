<?php
// Simple test to verify Laravel is accessible
// Upload to: backend/public/test.php
// Access: https://e-tracking.apratifoods.asia/backend/public/test.php

echo "<h1>✅ Laravel Public Directory is Accessible!</h1>";
echo "<p>Server time: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>PHP Version: " . phpversion() . "</p>";

// Test database connection
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=apratifoodscom_E-tracking',
        'apratifoodscom_dbusr',
        'rm^*yO579hdW!A$n'
    );
    echo "<p style='color:green'>✅ Database connected!</p>";

    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $count = $stmt->fetchColumn();
    echo "<p>Users in database: $count</p>";
} catch (Exception $e) {
    echo "<p style='color:red'>❌ Database error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><strong>Next test:</strong> Access Laravel directly:</p>";
echo "<p><a href='index.php'>Test Laravel index.php</a></p>";
?>