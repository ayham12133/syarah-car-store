<?php
/**
 * Setup script for Syarah Car Store
 * Run this script after cloning the repository to set up basic configuration
 */

echo "🚗 Syarah Car Store Setup Script\n";
echo "================================\n\n";

// Check if we're in the right directory
if (!file_exists('composer.json')) {
    echo "❌ Error: Please run this script from the project root directory.\n";
    exit(1);
}

echo "✅ Project root directory detected.\n\n";

// Check PHP version
$phpVersion = phpversion();
echo "📋 PHP Version: $phpVersion\n";

if (version_compare($phpVersion, '7.4.0', '<')) {
    echo "❌ Error: PHP 7.4 or higher is required.\n";
    exit(1);
}

echo "✅ PHP version is compatible.\n\n";

// Check if Composer is installed
if (!file_exists('vendor/autoload.php')) {
    echo "📦 Installing Composer dependencies...\n";
    $output = shell_exec('composer install 2>&1');
    if ($output === null) {
        echo "❌ Error: Failed to run composer install. Please install Composer first.\n";
        exit(1);
    }
    echo "✅ Composer dependencies installed.\n\n";
} else {
    echo "✅ Composer dependencies already installed.\n\n";
}

// Check database configuration
echo "🗄️  Database Configuration Check:\n";
$configFile = 'common/config/main-local.php';
if (!file_exists($configFile)) {
    echo "⚠️  Warning: Database configuration file not found.\n";
    echo "   Please create $configFile with your database settings.\n";
    echo "   You can copy from common/config/main-local.php.dist\n\n";
} else {
    echo "✅ Database configuration file exists.\n\n";
}

// Check upload directories
echo "📁 Checking upload directories...\n";
$uploadDirs = [
    'common/web/uploads',
    'dashboard/web/uploads',
    'storefront/web/uploads'
];

foreach ($uploadDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        echo "✅ Created directory: $dir\n";
    } else {
        echo "✅ Directory exists: $dir\n";
    }
}

// Create .htaccess for uploads
foreach ($uploadDirs as $dir) {
    $htaccessFile = $dir . '/.htaccess';
    if (!file_exists($htaccessFile)) {
        file_put_contents($htaccessFile, "Options -Indexes\n");
        echo "✅ Created .htaccess for: $dir\n";
    }
}

echo "\n";

// Check if migrations have been run
echo "🔄 Database Migration Check:\n";
$migrationTable = 'migration';
// This is a simple check - in a real scenario, you'd check the database
echo "⚠️  Please run 'php yii migrate' to set up the database.\n\n";

// Final instructions
echo "🎉 Setup Complete!\n";
echo "==================\n\n";
echo "Next steps:\n";
echo "1. Configure your database in common/config/main-local.php\n";
echo "2. Run database migrations: php yii migrate\n";
echo "3. Create an admin user: php yii admin/create admin admin@example.com password\n";
echo "4. Start the queue worker: php yii queue/listen\n";
echo "5. Access the applications:\n";
echo "   - Storefront: http://localhost/yii2CarStore/storefront/web/\n";
echo "   - Dashboard: http://localhost/yii2CarStore/dashboard/web/\n\n";
echo "📚 For detailed setup instructions, see README.md\n";
echo "🐛 For issues, check the troubleshooting section in README.md\n\n";
echo "Happy coding! 🚗💨\n";
