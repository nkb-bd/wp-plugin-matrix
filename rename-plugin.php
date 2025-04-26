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

// Global variables
global $dryRun, $force;

// Parse command line arguments
$args = array_slice($_SERVER['argv'], 1);
$pluginName = null;
$dryRun = false;
$force = false;

// Parse arguments
$nonOptionArgs = [];
foreach ($args as $arg) {
    if ($arg === '--dry-run') {
        $dryRun = true;
    } elseif ($arg === '--force') {
        $force = true;
    } elseif (substr($arg, 0, 2) !== '--') {
        $nonOptionArgs[] = $arg;
    }
}

// Combine non-option arguments to form the plugin name
if (!empty($nonOptionArgs)) {
    $pluginName = implode(' ', $nonOptionArgs);
}

// Display banner
echo "\n";
echo "WP Boilerplate Plugin Renamer\n";
echo "Rename your WordPress plugin boilerplate\n";
echo "\n";

if ($dryRun) {
    echo "DRY RUN MODE: No files will be modified\n";
    echo "\n";
}

// Check if plugin name is provided
if (!$pluginName) {
    echo "Error: Plugin name is required.\n";
    echo "Usage: php rename-plugin.php YourPluginName\n";
    echo "\n";
    echo "Example: php rename-plugin.php AwesomePlugin\n";
    exit(1);
}

// Validate plugin name
if (!preg_match('/^[A-Za-z]/', $pluginName)) {
    echo "Error: Plugin name must start with a letter.\n";
    exit(1);
}

// Check if plugin name contains at least one alphanumeric character
if (!preg_match('/[A-Za-z0-9]/', $pluginName)) {
    echo "Error: Plugin name must contain at least one alphanumeric character.\n";
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
echo "Namespace: " . $pluginData['namespace'] . " (was: {$pluginData['oldNamespace']})\n";
echo "Prefix: " . $pluginData['prefix'] . " (was: {$pluginData['oldPrefix']})\n";
echo "Constant Prefix: " . $pluginData['constantPrefix'] . " (was: {$pluginData['oldConstantPrefix']})\n";
echo "\n";

// Show examples of what will be replaced
echo "Examples of replacements:\n";
echo "  - Function: {$pluginData['oldPrefix']}_get_option() → {$pluginData['prefix']}_get_option()\n";
echo "  - Namespace: {$pluginData['oldNamespace']}\\Core → {$pluginData['namespace']}\\Core\n";
echo "  - Constant: {$pluginData['oldConstantPrefix']}_VERSION → {$pluginData['constantPrefix']}_VERSION\n";
echo "\n";

// Confirm with user if not in force mode
if (!$force) {
    echo "Do you want to proceed with these settings? " . ($dryRun ? "(Dry run) " : "") . "(y/n): ";
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    if (trim(strtolower($line)) != 'y') {
        echo "Renaming aborted.\n";
        exit(1);
    }
}

// Start the renaming process
echo "\n";
echo "Starting " . ($dryRun ? "dry run of " : "") . "renaming process...\n";

// Rename main plugin file
// First, find the main plugin file (it might have been renamed already)
$mainFile = null;
$possibleMainFiles = ['wp-boilerplate.php', 'wp-plugin-matrix-boiler-plate.php', 'test-plugin.php'];

foreach ($possibleMainFiles as $file) {
    if (file_exists($file)) {
        $mainFile = $file;
        break;
    }
}

// If main file not found, try to find any PHP file in the root directory that contains Plugin Name:
// This is a fallback in case the main file has been renamed to something else
if (!$mainFile) {
    $phpFiles = glob('*.php');
    foreach ($phpFiles as $file) {
        // Skip the rename script itself
        if ($file === basename(__FILE__)) {
            continue;
        }

        $content = file_get_contents($file);
        if (strpos($content, 'Plugin Name:') !== false) {
            $mainFile = $file;
            break;
        }
    }
}

$newMainFile = $pluginData['pluginSlug'] . '.php';

echo "Renaming main plugin file to {$newMainFile}...\n";

if ($mainFile) {
    echo "  Found main plugin file: {$mainFile}\n";
    $content = file_get_contents($mainFile);
    $newContent = replacePluginData($content, $pluginData, 'php', $mainFile);

    if ($dryRun) {
        echo "  Would rename {$mainFile} to {$newMainFile}\n";
        echo "  Would replace " . countReplacements($content, $newContent) . " occurrences\n";
    } else {
        file_put_contents($newMainFile, $newContent);

        // Remove the old file if it's different from the new file
        if ($mainFile !== $newMainFile) {
            unlink($mainFile);
            echo "  Removed old file: {$mainFile}\n";
        }
    }
} else {
    echo "  Error: Could not find main plugin file.\n";
}

// Process all PHP files
echo "Processing PHP files...\n";
processDirectory('.', $pluginData);

// Update composer.json
echo "Updating composer.json...\n";

if (file_exists('composer.json')) {
    $composerJson = json_decode(file_get_contents('composer.json'), true);
    $oldName = $composerJson['name'] ?? 'wp-boilerplate/plugin';
    $oldDesc = $composerJson['description'] ?? 'WP Boilerplate - A WordPress plugin';
    $newName = strtolower($pluginData['pluginSlug']) . '/plugin';
    $newDesc = $pluginData['pluginName'] . ' - A WordPress plugin';

    if ($dryRun) {
        echo "  Would change name from '{$oldName}' to '{$newName}'\n";
        echo "  Would change description from '{$oldDesc}' to '{$newDesc}'\n";
        echo "  Would update autoload namespace from '{$pluginData['oldNamespace']}\\' to '{$pluginData['namespace']}\\' \n";
    } else {
        $composerJson['name'] = $newName;
        $composerJson['description'] = $newDesc;
        if (isset($composerJson['autoload']['psr-4'])) {
            // Remove old namespace
            unset($composerJson['autoload']['psr-4'][$pluginData['oldNamespace'] . '\\']);
            // Add new namespace
            $composerJson['autoload']['psr-4'][$pluginData['namespace'] . '\\'] = 'app/';
        }
        file_put_contents('composer.json', json_encode($composerJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}

// Update custom autoloader
echo "Updating custom autoloader...\n";

if (file_exists('app/autoloader.php')) {
    $autoloaderContent = file_get_contents('app/autoloader.php');
    $newAutoloaderContent = str_replace(
        "\$prefix = '{$pluginData['oldNamespace']}\\';",
        "\$prefix = '{$pluginData['namespace']}\\';",
        $autoloaderContent
    );

    if ($dryRun) {
        echo "  Would update namespace prefix in app/autoloader.php\n";
        echo "  Would replace '{$pluginData['oldNamespace']}\\' with '{$pluginData['namespace']}\\' \n";
    } else {
        file_put_contents('app/autoloader.php', $newAutoloaderContent);
    }
}

// Update package.json
echo "Updating package.json...\n";

if (file_exists('package.json')) {
    $packageJson = json_decode(file_get_contents('package.json'), true);
    $oldName = $packageJson['name'] ?? 'wp-boilerplate';
    $oldDesc = $packageJson['description'] ?? 'WP Boilerplate - A WordPress plugin';
    $newName = strtolower($pluginData['pluginSlug']);
    $newDesc = $pluginData['pluginName'] . ' - A WordPress plugin';

    if ($dryRun) {
        echo "  Would change name from '{$oldName}' to '{$newName}'\n";
        echo "  Would change description from '{$oldDesc}' to '{$newDesc}'\n";
    } else {
        $packageJson['name'] = $newName;
        $packageJson['description'] = $newDesc;
        file_put_contents('package.json', json_encode($packageJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}

// Update readme.txt
echo "Updating readme.txt...\n";

if (file_exists('readme.txt')) {
    $readmeContent = file_get_contents('readme.txt');
    $newReadmeContent = str_replace(
        "=== WP Boilerplate Plugin ===",
        "=== {$pluginData['pluginName']} ===",
        $readmeContent
    );

    if ($dryRun) {
        echo "  Would update plugin name in readme.txt\n";
        echo "  Would replace 'WP Boilerplate Plugin' with '{$pluginData['pluginName']}'\n";
    } else {
        file_put_contents('readme.txt', $newReadmeContent);
    }
}

// Rebuild assets if not in dry run mode
if (!$dryRun) {
    echo "\n";
    echo "Rebuilding assets...\n";

    // Check if npm is installed
    $npmInstalled = shell_exec('which npm');
    if ($npmInstalled) {
        // Check if node_modules exists
        if (!is_dir('node_modules')) {
            echo "Installing npm dependencies...\n";
            echo shell_exec('npm install');
        }

        // Build assets
        echo "Building assets with npm...\n";
        echo shell_exec('npm run dev');
        echo "Assets rebuilt successfully!\n";
    } else {
        echo "Warning: npm not found. Assets were not rebuilt.\n";
        echo "You will need to manually run 'npm install' and 'npm run dev' to rebuild assets.\n";
    }
}

// Complete
echo "\n";
if ($dryRun) {
    echo "Dry run complete! No files were modified.\n";
    echo "Run the command without --dry-run to perform the actual renaming.\n";
} else {
    echo "Renaming complete! Your plugin is now set up as {$pluginData['pluginName']}.\n";
    echo "\n";
    echo "Next steps:\n";
    if (!isset($npmInstalled) || !$npmInstalled) {
        echo "1. Run 'npm install' to install JavaScript dependencies\n";
        echo "2. Run 'npm run dev' to build assets\n";
    }
    echo "3. Activate the plugin in WordPress\n";
}
echo "\n";

/**
 * Count the number of replacements between two strings
 *
 * @param string $oldContent Original content
 * @param string $newContent New content
 * @return int Number of replacements
 */
function countReplacements($oldContent, $newContent) {
    // Simple diff - count the number of different characters
    $diff = 0;
    $oldLines = explode("\n", $oldContent);
    $newLines = explode("\n", $newContent);

    // Count changed lines
    $changedLines = 0;
    $maxLines = max(count($oldLines), count($newLines));

    for ($i = 0; $i < $maxLines; $i++) {
        $oldLine = isset($oldLines[$i]) ? $oldLines[$i] : '';
        $newLine = isset($newLines[$i]) ? $newLines[$i] : '';

        if ($oldLine !== $newLine) {
            $changedLines++;
        }
    }

    return $changedLines;
}

/**
 * Generate plugin data from plugin name
 *
 * @param string $pluginName Plugin name
 * @return array Plugin data
 */
function generatePluginData($pluginName) {
    // Generate plugin slug (lowercase with hyphens)
    // First, add hyphens before uppercase letters in camelCase words
    $withHyphens = preg_replace('/([a-z])([A-Z])/', '$1-$2', $pluginName);
    // Then convert spaces to hyphens and make lowercase
    $pluginSlug = strtolower(str_replace(' ', '-', $withHyphens));
    // Remove any characters that aren't alphanumeric or hyphens
    $pluginSlug = preg_replace('/[^a-z0-9\-]/', '', $pluginSlug);
    // Fix multiple consecutive hyphens
    $pluginSlug = preg_replace('/-+/', '-', $pluginSlug);

    // Generate text domain (lowercase with underscores)
    $textDomain = str_replace('-', '_', $pluginSlug);

    // Generate prefix (lowercase with underscores)
    $prefix = $textDomain;

    // Generate constant prefix (uppercase with underscores)
    $constantPrefix = strtoupper($prefix);

    // Generate namespace (PascalCase)
    // Remove spaces and special characters first
    $cleanName = preg_replace('/[^a-zA-Z0-9\s]/', '', $pluginName);
    // Convert to PascalCase
    $namespace = str_replace(' ', '', ucwords($cleanName));

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
 * @param string $fileType File type (php, js, scss, etc.)
 * @param string $path File path (optional)
 * @return string Updated content
 */
function replacePluginData($content, $pluginData, $fileType = 'php', $path = '') {
    // Common replacements for all file types
    $content = str_replace(
        ['WP Boilerplate', 'wp-boilerplate', 'wp_boilerplate', 'WP_BOILERPLATE', 'WpBoilerplate'],
        [$pluginData['pluginName'], $pluginData['pluginSlug'], $pluginData['textDomain'], $pluginData['constantPrefix'], $pluginData['namespace']],
        $content
    );

    // PHP-specific replacements
    if ($fileType === 'php') {
        // Replace WordPress plugin header
        // Handle different possible formats of the plugin header

        // Debug: Print the first 200 characters of the file to see the actual format
        if (strpos($content, 'Plugin Name') !== false) {
            echo "\nFound Plugin Name in file. First 200 chars:\n";
            echo substr($content, 0, 200) . "\n";
            echo "Replacing with: {$pluginData['pluginName']}\n\n";
        }

        // First try exact pattern matching for common formats
        $exactPatterns = [
            '/\* Plugin Name: .*?\n/' => '* Plugin Name: ' . $pluginData['pluginName'] . "\n",
            '/Plugin Name: .*?\n/' => 'Plugin Name: ' . $pluginData['pluginName'] . "\n",
        ];

        foreach ($exactPatterns as $pattern => $replacement) {
            $newContent = preg_replace($pattern, $replacement, $content, 1, $count);
            if ($count > 0) {
                $content = $newContent;
                echo "  Replaced Plugin Name using pattern: " . $pattern . "\n";
            }
        }

        // If no replacements were made, try more generic patterns
        if (strpos($content, 'Plugin Name') !== false && strpos($content, $pluginData['pluginName']) === false) {
            echo "  Using fallback replacement for Plugin Name\n";

            // Try a more aggressive replacement
            $content = preg_replace(
                '/(Plugin Name:)([^\n]*)/i',
                '$1 ' . $pluginData['pluginName'],
                $content,
                1
            );
        }

        // Replace Text Domain
        $textDomainPatterns = [
            '/\* Text Domain: .*?\n/' => '* Text Domain: ' . $pluginData['textDomain'] . "\n",
            '/Text Domain: .*?\n/' => 'Text Domain: ' . $pluginData['textDomain'] . "\n"
        ];

        foreach ($textDomainPatterns as $pattern => $replacement) {
            $newContent = preg_replace($pattern, $replacement, $content, 1, $count);
            if ($count > 0) {
                $content = $newContent;
                echo "  Replaced Text Domain using pattern: " . $pattern . "\n";
            }
        }

        // If no replacements were made for Text Domain, try more generic pattern
        if (strpos($content, 'Text Domain') !== false && strpos($content, $pluginData['textDomain']) === false) {
            $content = preg_replace(
                '/(Text Domain:)([^\n]*)/i',
                '$1 ' . $pluginData['textDomain'],
                $content,
                1
            );
        }

        // Replace Description
        $descriptionPatterns = [
            '/\* Description: .*?\n/' => '* Description: ' . $pluginData['pluginName'] . ' - A WordPress plugin' . "\n",
            '/Description: .*?\n/' => 'Description: ' . $pluginData['pluginName'] . ' - A WordPress plugin' . "\n"
        ];

        foreach ($descriptionPatterns as $pattern => $replacement) {
            $newContent = preg_replace($pattern, $replacement, $content, 1, $count);
            if ($count > 0) {
                $content = $newContent;
                echo "  Replaced Description using pattern: " . $pattern . "\n";
            }
        }

        // If no replacements were made for Description, try more generic pattern
        if (strpos($content, 'Description') !== false) {
            $content = preg_replace(
                '/(Description:)([^\n]*)/i',
                '$1 ' . $pluginData['pluginName'] . ' - A WordPress plugin',
                $content,
                1
            );
        }

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
    }

    // JavaScript-specific replacements
    if ($fileType === 'js' || $fileType === 'vue') {
        // Replace JavaScript variable names and object properties
        $content = preg_replace(
            '/window\\.wpBoilerplateAdmin/',
            'window.' . lcfirst($pluginData['namespace']) . 'Admin',
            $content
        );

        // Replace Vue component names and imports
        $content = preg_replace(
            '/WpBoilerplateApp/',
            $pluginData['namespace'] . 'App',
            $content
        );

        // Replace CSS class selectors in Vue templates
        $content = preg_replace(
            '/wp-boilerplate-admin-page/',
            $pluginData['pluginSlug'] . '-admin-page',
            $content
        );

        // Replace element IDs
        $content = preg_replace(
            '/wp_boilerplate_app/',
            $pluginData['textDomain'] . '_app',
            $content
        );

        // Replace AJAX action names
        $content = preg_replace(
            '/wp_boilerplate_admin_ajax/',
            $pluginData['textDomain'] . '_admin_ajax',
            $content
        );

        // Replace menu item classes
        $content = preg_replace(
            '/wp_boilerplate_menu_item/',
            $pluginData['textDomain'] . '_menu_item',
            $content
        );


    }

    // CSS/SCSS-specific replacements
    if ($fileType === 'css' || $fileType === 'scss') {
        // Replace CSS class selectors
        $content = preg_replace(
            '/\\.wp-boilerplate-admin-page/',
            '.' . $pluginData['pluginSlug'] . '-admin-page',
            $content
        );

        // Replace CSS ID selectors
        $content = preg_replace(
            '/#wp_boilerplate_app/',
            '#' . $pluginData['textDomain'] . '_app',
            $content
        );

        // Replace body class selectors
        $content = preg_replace(
            '/body\\.wp-admin\\.toplevel_page_wp-boilerplate/',
            'body.wp-admin.toplevel_page_' . $pluginData['pluginSlug'],
            $content
        );
    }

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
    global $dryRun;
    $files = scandir($dir);

    foreach ($files as $file) {
        if ($file === '.' || $file === '..' || $file === '.git' || $file === 'vendor' || $file === 'node_modules' || $file === 'dist') {
            continue;
        }

        $path = $dir . '/' . $file;

        if (is_dir($path)) {
            processDirectory($path, $pluginData);
        } else {
            // Get file extension
            $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

            // Skip the rename script itself
            if ($file === basename(__FILE__)) {
                continue;
            }

            // Skip compiled files
            if (strpos($path, '/dist/') !== false) {
                continue;
            }

            // Process files based on extension
            $shouldProcess = false;
            $fileType = '';

            // Always process AdminMenu.php
            if (basename($path) === 'AdminMenu.php') {
                echo "  Processing AdminMenu.php to update menu titles...\n";
                $shouldProcess = true;
                $fileType = 'php';

                // Process AdminMenu.php directly here
                $content = file_get_contents($path);

                // Replace the menu title in add_menu_page function
                $oldContent = $content;
                $content = preg_replace(
                    "/'WP Plugin Matrix BoilerPlate',\s*'WP Plugin Matrix BoilerPlate',/",
                    "'" . $pluginData['pluginName'] . "',\n            '" . $pluginData['pluginName'] . "',",
                    $content
                );

                if ($content !== $oldContent) {
                    echo "  Updated menu title in add_menu_page function\n";
                } else {
                    // Try a more generic pattern
                    $content = preg_replace(
                        "/('[^']*',\s*'[^']*',\s*'manage_options',\s*'[^']*',)/",
                        "'" . $pluginData['pluginName'] . "',\n            '" . $pluginData['pluginName'] . "',\n            'manage_options',\n            '" . $pluginData['pluginSlug'] . "',",
                        $content
                    );
                }

                // Replace the title in the admin page header
                $oldContent = $content;
                $content = preg_replace(
                    '/<div class="font-bold text-xl mr-4">WP Plugin Matrix BoilerPlate<\/div>/',
                    '<div class="font-bold text-xl mr-4">' . $pluginData['pluginName'] . '</div>',
                    $content
                );

                if ($content !== $oldContent) {
                    echo "  Updated title in admin page header\n";
                } else {
                    // Try a more generic pattern
                    $content = preg_replace(
                        '/<div class="font-bold text-xl mr-4">[^<]*<\/div>/',
                        '<div class="font-bold text-xl mr-4">' . $pluginData['pluginName'] . '</div>',
                        $content
                    );
                }

                if ($dryRun) {
                    echo "  Would update plugin menu titles in AdminMenu.php\n";
                } else {
                    file_put_contents($path, $content);
                    echo "  Updated plugin menu titles in AdminMenu.php\n";
                }
            }

            switch ($extension) {
                case 'php':
                    $shouldProcess = true;
                    $fileType = 'php';
                    break;

                case 'js':
                    $shouldProcess = true;
                    $fileType = 'js';
                    break;

                case 'vue':
                    $shouldProcess = true;
                    $fileType = 'vue';
                    break;

                case 'scss':
                case 'css':
                    $shouldProcess = true;
                    $fileType = 'scss';
                    break;

                case 'json':
                    // Only process package.json and composer.json
                    if ($file === 'package.json' || $file === 'composer.json') {
                        $shouldProcess = true;
                        $fileType = 'json';
                    }
                    break;

                case 'md':
                case 'txt':
                    $shouldProcess = true;
                    $fileType = 'text';
                    break;
            }

            if ($shouldProcess) {
                $content = file_get_contents($path);
                $newContent = replacePluginData($content, $pluginData, $fileType, $path);

                if ($content !== $newContent) {
                    $replacements = countReplacements($content, $newContent);
                    if ($dryRun) {
                        echo "  Would update: {$path} ({$replacements} replacements)\n";
                        // Show a sample of the changes for the first few files
                        static $samplesShown = 0;
                        if ($samplesShown < 3) {
                            $oldLines = explode("\n", $content);
                            $newLines = explode("\n", $newContent);
                            $sampleCount = 0;

                            for ($i = 0; $i < min(count($oldLines), count($newLines)) && $sampleCount < 3; $i++) {
                                if ($oldLines[$i] !== $newLines[$i]) {
                                    echo "    Old: " . trim($oldLines[$i]) . "\n";
                                    echo "    New: " . trim($newLines[$i]) . "\n";
                                    echo "    ---\n";
                                    $sampleCount++;
                                }
                            }
                            $samplesShown++;
                        }
                    } else {
                        file_put_contents($path, $newContent);
                        echo "Updated: {$path} ({$replacements} replacements)\n";
                    }
                }
            }
        }
    }
}
