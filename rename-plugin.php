<?php
/**
 * Simple Plugin Renamer
 * 
 * A lightweight script to rename the WordPress plugin boilerplate
 * without requiring Composer dependencies
 */

// Check if PHP CLI
if (php_sapi_name() !== 'cli') {
    echo "This script must be run from the command line.";
    exit(1);
}

// Parse command line arguments
$args = array_slice($_SERVER['argv'], 1);
$pluginName = $args[0] ?? null;

// Display banner
echo "\n";
echo "WP Boilerplate Plugin Renamer\n";
echo "Rename your WordPress plugin boilerplate\n";
echo "\n";

// Check if plugin name is provided
if (!$pluginName) {
    echo "Error: Plugin name is required.\n";
    echo "Usage: php rename-plugin.php YourPluginName\n";
    echo "\n";
    echo "Example: php rename-plugin.php AwesomePlugin\n";
    exit(1);
}

// Validate plugin name
if (!preg_match('/^[A-Za-z][A-Za-z0-9]*$/', $pluginName)) {
    echo "Error: Plugin name must start with a letter and contain only alphanumeric characters.\n";
    exit(1);
}

// Generate plugin data
$pluginData = generatePluginData($pluginName);

// Display plugin data
echo "\n";
echo "Plugin Setup Information:\n";
echo "\n";

echo "Plugin Name: " . $pluginData['pluginName'] . "\n";
echo "Plugin Slug: " . $pluginData['pluginSlug'] . "\n";
echo "Text Domain: " . $pluginData['textDomain'] . "\n";
echo "Namespace: " . $pluginData['namespace'] . "\n";
echo "Prefix: " . $pluginData['prefix'] . "\n";
echo "Constant Prefix: " . $pluginData['constantPrefix'] . "\n";
echo "\n";

// Confirm with user
echo "Do you want to proceed with these settings? (y/n): ";
$handle = fopen("php://stdin", "r");
$line = fgets($handle);
if (trim(strtolower($line)) != 'y') {
    echo "Renaming aborted.\n";
    exit(1);
}

// Start the renaming process
echo "\n";
echo "Starting renaming process...\n";

// Rename main plugin file
$mainFile = 'wp-boilerplate.php';
$newMainFile = $pluginData['pluginSlug'] . '.php';

echo "Renaming main plugin file to {$newMainFile}...\n";

if (file_exists($mainFile)) {
    $content = file_get_contents($mainFile);
    $content = replacePluginData($content, $pluginData);
    file_put_contents($newMainFile, $content);
}

// Process all PHP files
echo "Processing PHP files...\n";
processDirectory('.', $pluginData);

// Update composer.json
echo "Updating composer.json...\n";

if (file_exists('composer.json')) {
    $composerJson = json_decode(file_get_contents('composer.json'), true);
    $composerJson['name'] = strtolower($pluginData['pluginSlug']) . '/plugin';
    $composerJson['description'] = $pluginData['pluginName'] . ' - A WordPress plugin';
    $composerJson['autoload']['psr-4'][$pluginData['namespace'] . '\\'] = 'app/';
    file_put_contents('composer.json', json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

// Update custom autoloader
echo "Updating custom autoloader...\n";

if (file_exists('app/autoloader.php')) {
    $autoloaderContent = file_get_contents('app/autoloader.php');
    $autoloaderContent = str_replace(
        "\$prefix = 'WpBoilerplate\\\\';",
        "\$prefix = '{$pluginData['namespace']}\\\\';",
        $autoloaderContent
    );
    file_put_contents('app/autoloader.php', $autoloaderContent);
}

// Update package.json
echo "Updating package.json...\n";

if (file_exists('package.json')) {
    $packageJson = json_decode(file_get_contents('package.json'), true);
    $packageJson['name'] = strtolower($pluginData['pluginSlug']);
    $packageJson['description'] = $pluginData['pluginName'] . ' - A WordPress plugin';
    file_put_contents('package.json', json_encode($packageJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

// Update readme.txt
echo "Updating readme.txt...\n";

if (file_exists('readme.txt')) {
    $readmeContent = file_get_contents('readme.txt');
    $readmeContent = str_replace(
        "=== WP Boilerplate Plugin ===",
        "=== {$pluginData['pluginName']} ===",
        $readmeContent
    );
    file_put_contents('readme.txt', $readmeContent);
}

// Complete
echo "\n";
echo "Renaming complete! Your plugin is now set up as {$pluginData['pluginName']}.\n";
echo "\n";
echo "Next steps:\n";
echo "1. Run 'npm install' to install JavaScript dependencies\n";
echo "2. Run 'npm run dev' to build assets\n";
echo "3. Activate the plugin in WordPress\n";
echo "\n";

/**
 * Generate plugin data from plugin name
 *
 * @param string $pluginName Plugin name
 * @return array Plugin data
 */
function generatePluginData($pluginName) {
    // Generate plugin slug (lowercase with hyphens)
    $pluginSlug = strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $pluginName));
    $pluginSlug = preg_replace('/[^a-z0-9\-]/', '', $pluginSlug);
    
    // Generate text domain (lowercase with underscores)
    $textDomain = str_replace('-', '_', $pluginSlug);
    
    // Generate prefix (lowercase with underscores)
    $prefix = $textDomain;
    
    // Generate constant prefix (uppercase with underscores)
    $constantPrefix = strtoupper($prefix);
    
    // Generate namespace (PascalCase)
    $namespace = str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $pluginSlug)));
    
    return [
        'pluginName' => $pluginName,
        'pluginSlug' => $pluginSlug,
        'textDomain' => $textDomain,
        'prefix' => $prefix,
        'constantPrefix' => $constantPrefix,
        'namespace' => $namespace,
        'oldPluginName' => 'WP Boilerplate',
        'oldPluginSlug' => 'wp-boilerplate',
        'oldTextDomain' => 'wp_boilerplate',
        'oldPrefix' => 'wp_boilerplate',
        'oldConstantPrefix' => 'WP_BOILERPLATE',
        'oldNamespace' => 'WpBoilerplate',
    ];
}

/**
 * Replace plugin data in content
 *
 * @param string $content Content
 * @param array $pluginData Plugin data
 * @return string Updated content
 */
function replacePluginData($content, $pluginData) {
    // Replace plugin name
    $content = str_replace(
        ['WP Boilerplate', 'wp-boilerplate', 'wp_boilerplate', 'WP_BOILERPLATE', 'WpBoilerplate'],
        [$pluginData['pluginName'], $pluginData['pluginSlug'], $pluginData['textDomain'], $pluginData['constantPrefix'], $pluginData['namespace']],
        $content
    );

    // Replace namespace
    $content = preg_replace(
        '/namespace WpBoilerplate\\\\(.*?);/',
        'namespace ' . $pluginData['namespace'] . '\\\\$1;',
        $content
    );

    // Replace use statements
    $content = preg_replace(
        '/use WpBoilerplate\\\\(.*?);/',
        'use ' . $pluginData['namespace'] . '\\\\$1;',
        $content
    );

    // Replace class references
    $content = preg_replace(
        '/new \\\\WpBoilerplate\\\\(.*?)\\(/',
        'new \\\\' . $pluginData['namespace'] . '\\\\$1(',
        $content
    );

    // Replace function names
    $content = preg_replace(
        '/function wp_boilerplate_([a-z_]+)/',
        'function ' . $pluginData['prefix'] . '_$1',
        $content
    );

    // Replace constants
    $content = preg_replace(
        '/WP_BOILERPLATE_([A-Z_]+)/',
        $pluginData['constantPrefix'] . '_$1',
        $content
    );

    return $content;
}

/**
 * Process a directory recursively
 *
 * @param string $dir Directory path
 * @param array $pluginData Plugin data
 * @return void
 */
function processDirectory($dir, $pluginData) {
    $files = scandir($dir);
    
    foreach ($files as $file) {
        if ($file === '.' || $file === '..' || $file === '.git' || $file === 'vendor' || $file === 'node_modules') {
            continue;
        }

        $path = $dir . '/' . $file;

        if (is_dir($path)) {
            processDirectory($path, $pluginData);
        } else {
            // Only process PHP files
            if (pathinfo($path, PATHINFO_EXTENSION) === 'php' && $file !== 'rename-plugin.php' && $file !== 'wp-boilerplate-rename') {
                $content = file_get_contents($path);
                $newContent = replacePluginData($content, $pluginData);

                if ($content !== $newContent) {
                    file_put_contents($path, $newContent);
                    echo "Updated: {$path}\n";
                }
            }
        }
    }
}
