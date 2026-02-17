<?php
// Laravel Environment Check
// Upload to: backend/public/env-check.php
// Purpose: Check if .env file is readable

echo "<h1>🔍 Laravel Environment Check</h1>";
echo "<style>body{font-family:monospace;padding:20px;background:#1e1e1e;color:#d4d4d4;}
h1{color:#4ec9b0;}.success{color:#4ec9b0;}.error{color:#f48771;}
pre{background:#2d2d2d;padding:10px;border-radius:5px;}</style>";

echo "<h2>Current Directory</h2>";
echo "<pre>" . __DIR__ . "</pre>";

echo "<h2>Parent Directory (.env location)</h2>";
$envPath = dirname(__DIR__) . '/.env';
echo "<pre>$envPath</pre>";

echo "<h2>.env File Check</h2>";
if (file_exists($envPath)) {
    echo "<p class='success'>✅ .env file exists!</p>";
    echo "<p>File size: " . filesize($envPath) . " bytes</p>";
    echo "<p>Readable: " . (is_readable($envPath) ? '✅ Yes' : '❌ No') . "</p>";

    // Try to read it
    $content = file_get_contents($envPath);
    if ($content) {
        echo "<h3>Key Settings Found:</h3>";
        echo "<pre>";
        $lines = explode("\n", $content);
        foreach ($lines as $line) {
            $line = trim($line);
            if (strpos($line, 'APP_') === 0 || strpos($line, 'DB_') === 0) {
                // Hide sensitive values
                if (strpos($line, 'PASSWORD') !== false || strpos($line, 'KEY') !== false) {
                    $parts = explode('=', $line, 2);
                    echo $parts[0] . "=***hidden***\n";
                } else {
                    echo htmlspecialchars($line) . "\n";
                }
            }
        }
        echo "</pre>";
    }
} else {
    echo "<p class='error'>❌ .env file NOT FOUND!</p>";
    echo "<p>Expected location: $envPath</p>";
}

echo "<h2>Laravel Bootstrap Check</h2>";
$bootstrapApp = dirname(__DIR__) . '/bootstrap/app.php';
if (file_exists($bootstrapApp)) {
    echo "<p class='success'>✅ bootstrap/app.php exists</p>";
} else {
    echo "<p class='error'>❌ bootstrap/app.php missing!</p>";
}

echo "<h2>Vendor Autoload Check</h2>";
$autoload = dirname(__DIR__) . '/vendor/autoload.php';
if (file_exists($autoload)) {
    echo "<p class='success'>✅ vendor/autoload.php exists</p>";
} else {
    echo "<p class='error'>❌ vendor/autoload.php missing!</p>";
}

echo "<hr>";
echo "<p><strong>⚠️ DELETE THIS FILE after checking!</strong></p>";
?>