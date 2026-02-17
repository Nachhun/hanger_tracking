<?php
// Storage Permission Fixer
// Upload to: backend/public/fix-permissions.php
// Will attempt to set proper permissions

echo "<h1>🔧 Storage Permission Fixer</h1>";
echo "<style>body{font-family:monospace;padding:20px;background:#1e1e1e;color:#d4d4d4;}
h1{color:#4ec9b0;}.success{color:#4ec9b0;}.error{color:#f48771;}
pre{background:#2d2d2d;padding:10px;border-radius:5px;}</style>";

$basePath = dirname(__DIR__);
$dirsToFix = [
    'storage',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/cache/data',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'storage/app',
    'bootstrap/cache',
];

echo "<h2>Checking Permissions...</h2>";
echo "<pre>";

foreach ($dirsToFix as $dir) {
    $fullPath = $basePath . '/' . $dir;

    if (!is_dir($fullPath)) {
        echo "❌ Directory doesn't exist: $dir\n";
        echo "   Creating...\n";
        if (mkdir($fullPath, 0775, true)) {
            echo "   ✅ Created with 775\n";
        } else {
            echo "   ❌ Failed to create\n";
        }
        continue;
    }

    $perms = substr(sprintf('%o', fileperms($fullPath)), -4);
    $writable = is_writable($fullPath);

    echo ($writable ? "✅" : "❌") . " $dir (perms: $perms, writable: " . ($writable ? "yes" : "no") . ")\n";

    if (!$writable || $perms !== '0775') {
        echo "   Attempting to fix permissions...\n";
        if (chmod($fullPath, 0775)) {
            echo "   ✅ Set to 775\n";
        } else {
            echo "   ❌ Failed - you need to do this via cPanel File Manager\n";
        }
    }
}

echo "</pre>";

echo "<h2>📋 Manual Fix (If Above Failed)</h2>";
echo "<p>In cPanel File Manager:</p>";
echo "<ol>";
echo "<li>Navigate to <code>backend/</code> folder</li>";
echo "<li>Right-click <code>storage</code> folder</li>";
echo "<li>Click 'Change Permissions'</li>";
echo "<li>Set to <strong>775</strong> (rwxrwxr-x)</li>";
echo "<li>Check 'Recurse into subdirectories'</li>";
echo "<li>Click 'Change Permissions'</li>";
echo "<li>Repeat for <code>bootstrap/cache</code> folder</li>";
echo "</ol>";

echo "<hr>";
echo "<h2>✅ Next Step</h2>";
echo "<p>After fixing permissions, try accessing Laravel again:</p>";
echo "<p><a href='index.php'>Test Laravel index.php</a></p>";
echo "<p><strong>⚠️ DELETE THIS FILE after use!</strong></p>";
?>