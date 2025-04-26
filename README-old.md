# WP Plugin Matrix Starter Plugin

A modern WordPress plugin boilerplate with Vue.js, Tailwind CSS, and Laravel Mix, following PSR-4 autoloading and modern PHP practices.

## Features

- Simple PSR-4 autoloading (no Composer dependency required)
- Modern PHP architecture (MVC pattern)
- Vue.js 3 for admin interface
- Tailwind CSS for styling
- Laravel Mix for asset compilation
- Modular organization with clear separation of concerns
- Transient API wrapper for simplified caching
- Database migration system similar to Laravel
- Security helpers for CSRF protection, XSS protection, and input sanitization
- Debug panel for development
- Simple plugin renamer script for quick setup and customization (no dependencies required)
- Advanced UI components library:
  - Data tables with sorting, filtering, and pagination
  - Modal dialogs
  - Notifications system
  - File uploader with preview
  - Rich text editor
  - Drag and drop interface builder

## Directory Structure

```
app/                  # PHP application code
  Core/               # Core functionality
  Database/           # Database-related code
  Helpers/            # Helper functions and utilities
  Hooks/              # WordPress hooks handlers
    Handlers/         # Hook handler classes
  Http/               # HTTP-related code
    Controllers/      # Controllers for handling requests
    Routes/           # Route definitions
  Model/              # Data models
  Services/           # Service classes
  Views/              # View templates
dist/                 # Compiled assets (generated)
language/             # Translation files
resources/            # Frontend source files
  js/                 # JavaScript files
    admin/            # Admin interface Vue.js code
      Components/     # Vue.js components
      Pages/          # Vue.js pages
  scss/               # SCSS files
  images/             # Image files
vendor/               # Composer dependencies (generated)
wp-plugin-matrix-starter.php    # Main plugin file
composer.json         # Composer configuration
package.json          # NPM configuration
webpack.mix.js        # Laravel Mix configuration
tailwind.config.js    # Tailwind CSS configuration
```

## Installation

1. Clone the repository:
   ```
   git clone https://github.com/yourusername/wp-plugin-matrix-starter
   ```

2. Rename the plugin to your desired name using the dependency-free renamer script:
   ```
   php rename-plugin.php YourPluginName
   ```
   This will rename all files, namespaces, function prefixes, and constants to match your plugin name.

3. Install JavaScript dependencies:
   ```
   npm install
   ```

4. Build assets:
   ```
   npm run dev     # Development build
   npm run watch   # Watch for changes
   npm run prod    # Production build
   ```

## Development

### PHP

The plugin follows a modern PHP architecture with PSR-4 autoloading (no Composer required). All PHP code is organized in the `app` directory with proper namespacing. The custom autoloader in `app/autoloader.php` handles class loading without requiring Composer.

### JavaScript

Vue.js 3 is used for the admin interface. The source files are located in the `resources/js` directory.

### CSS

Tailwind CSS is used for styling. The source files are located in the `resources/scss` directory.

## Advanced Features

### Debug Panel

The plugin includes a comprehensive debug panel that helps you understand the execution flow, registered services, facades, and hooks. It's automatically displayed in the admin footer when `WP_DEBUG` is set to `true`.

You can also manually dump the debug information anywhere in your code:

```php
// Dump debug info to the screen
\WPPluginMatrixStarter\Core\Debug::dump();

// Get debug info as a string
$debugInfo = \WPPluginMatrixStarter\Core\Debug::dump(true);

// Access raw debug information
$allDebugInfo = \WPPluginMatrixStarter\Core\Debug::getDebugInfo();
```

The debug panel shows:
- Execution sequence with timestamps
- All registered services
- All registered facades
- All registered hooks with their callbacks and priorities

You can toggle the debug panel on/off using the button that appears in the bottom-right corner of the screen. Your preference is saved in a cookie so it persists between page loads.

### Plugin Renaming Tool

The plugin includes a powerful renaming tool that makes it easy to customize the boilerplate for your own plugin:

```bash
# Run the renaming tool
php rename-plugin.php YourPluginName
```

This will:
1. Rename the main plugin file to `your-plugin-name.php`
2. Update all namespaces from `WPPluginMatrixStarter` to `YourPluginName`
3. Update all function prefixes from `wp_plugin_matrix_starter_` to `your_plugin_name_`
4. Update all constants from `WP_PLUGIN_MATRIX_STARTER_` to `YOUR_PLUGIN_NAME_`
5. Update the custom autoloader to use your namespace
6. Update composer.json and package.json with your plugin information
7. Update plugin menu titles in the WordPress admin dashboard to match your plugin name
8. Update database table prefixes in migration files
8. Update database table prefixes in migration files

The tool works without requiring Composer, making it easy to set up a new plugin quickly.

### Hook Registration

The plugin provides a centralized way to register WordPress hooks with tracking for debugging:

```php
// Register an action hook
wp_plugin_matrix_starter_add_action('init', 'my_init_function');

// Register a filter hook
wp_plugin_matrix_starter_add_filter('the_content', 'my_content_filter');

// Register a hook with a class method
wp_plugin_matrix_starter_add_action('admin_menu', [$this, 'registerMenu']);

// Register a hook with priority and arguments
wp_plugin_matrix_starter_add_action('save_post', 'my_save_post_function', 20, 3);
```

You can also create custom hook handlers by extending the base handler classes:

```php
<?php

namespace YourPlugin\Hooks\Handlers;

class CustomHandler
{
    /**
     * Register hooks
     *
     * This method is called during the 'plugins_loaded' action.
     *
     * @return void
     */
    public function register()
    {
        // Register your hooks here
        wp_plugin_matrix_starter_add_action('init', [$this, 'initializePlugin']);
        wp_plugin_matrix_starter_add_filter('the_content', [$this, 'filterContent']);
    }

    /**
     * Initialize the plugin
     */
    public function initializePlugin()
    {
        // Initialization code
    }

    /**
     * Filter content
     */
    public function filterContent($content)
    {
        // Modify content
        return $content;
    }
}
```

Then register your handler in the HookManager:

```php
// In app/Hooks/HookManager.php
public function registerHooks()
{
    // Register all hook handlers
    $handlers = [
        new AdminHandler(),
        new AjaxHandler(),
        new ApiHandler(),
        new ShortcodeHandler(),
        new \YourPlugin\Hooks\Handlers\CustomHandler() // Add your custom handler
    ];

    foreach ($handlers as $handler) {
        $handler->register();
    }
}
```

### Service Registration

You can register your own services with the App class:

```php
// Create your service class
namespace YourPlugin\Services;

class CustomService
{
    public function doSomething()
    {
        // Implementation
    }
}

// Register the service
$app = wp_plugin_matrix_starter_app();
$app->registerService('custom', new \YourPlugin\Services\CustomService());

// Use the service
$customService = $app->getService('custom');
$customService->doSomething();
```

Or create a Facade for easier access:

```php
// Create a facade class
namespace YourPlugin\Facades;

use WPPluginMatrixStarter\Core\Facade;

class Custom extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'custom';
    }
}

// Use the facade
use YourPlugin\Facades\Custom;

Custom::doSomething();
```

### Enhanced Asset Management

The plugin provides a powerful asset management system that handles script and style enqueuing with support for page-specific loading, conditional loading, and proper dependency management. This makes it easy to load your scripts and styles only where they're needed, improving performance.

### Facade Pattern

The plugin uses a simplified Facade pattern inspired by Laravel to provide a clean, static interface to various services:

```php
// Using the Cache facade
use WPPluginMatrixStarter\Facades\Cache;

Cache::set('key', 'value', 3600);
$value = Cache::get('key');

// Using the Security facade
use WPPluginMatrixStarter\Facades\Security;

$sanitized = Security::sanitize($_POST['title'], 'text');
$escaped = Security::escape($title);

// Using the View facade
use WPPluginMatrixStarter\Facades\View;

View::render('admin/dashboard', ['data' => $data]);

// Using the Asset facade
use WPPluginMatrixStarter\Facades\Asset;

// Register a script for admin area only
Asset::registerScript(
    'my-admin-script',  // Handle
    'js/admin.js',      // Path relative to dist directory
    ['jquery'],         // Dependencies
    '1.0.0',            // Version
    true,               // Load in footer
    'admin'             // Context: 'admin', 'frontend', or 'both'
);

// Register a script with a condition (only on single posts)
Asset::registerScript(
    'my-frontend-script',
    'js/frontend.js',
    ['jquery'],
    '1.0.0',
    true,
    'frontend',
    function() {
        return is_singular('post');
    }
);

// Localize a script
Asset::localizeScript(
    'my-admin-script',
    'myScriptData',
    [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('my_nonce')
    ]
);

// Register a style
Asset::registerStyle(
    'my-style',
    'css/style.css',
    [],
    '1.0.0',
    'all',
    'both'
);

// Set the plugin slug for admin page detection
Asset::setPluginSlug('my-plugin');

// Get asset URL
$url = Asset::url('images/logo.png');
```

You can also use the procedural helper functions if you prefer:

```php
wp_plugin_matrix_starter_cache_set('key', 'value', 3600);
$value = wp_plugin_matrix_starter_cache_get('key');

$sanitized = wp_plugin_matrix_starter_sanitize($_POST['title'], 'text');
$escaped = wp_plugin_matrix_starter_escape($title);

wp_plugin_matrix_starter_view('admin/dashboard', ['data' => $data]);

$url = wp_plugin_matrix_starter_asset('js/main.js');
```

#### Creating Your Own Facades

You can easily create your own facades:

```php
// 1. Create a class that implements the functionality
namespace YourPlugin\Core;

class EmailService {
    public function send($to, $subject, $message) {
        // Implementation
    }
}

// 2. Create a facade for the class
namespace YourPlugin\Facades;

use WPPluginMatrixStarter\Core\Facade;

class Email extends Facade {
    protected static function getFacadeAccessor() {
        return 'email';
    }
}

// 3. Register the service with the Facade system
Facade::registerService('email', new \YourPlugin\Core\EmailService());

// 4. Use the facade in your code
use YourPlugin\Facades\Email;

Email::send('user@example.com', 'Hello', 'This is a test');
```

### Transient API Wrapper

The plugin includes a wrapper for WordPress Transient API that simplifies caching:

```php
// Get a cached value
$value = wp_plugin_matrix_starter_cache_get('cache_key', 'default_value');

// Set a cached value
$result = wp_plugin_matrix_starter_cache_set('cache_key', 'value', 3600); // 1 hour

// Remember a value (only calculate if not in cache)
$value = wp_plugin_matrix_starter_cache_remember('cache_key', 3600, function() {
    // This will only run if the cache key doesn't exist
    return expensive_calculation();
});

// Delete a cached value
$result = wp_plugin_matrix_starter_cache_delete('cache_key');
```

### Database Migration System

The plugin includes a Laravel-like database migration system:

```php
// Create a migration file
wp wp-plugin-matrix-starter migrate:make create_users_table --table=users

// Run migrations
wp wp-plugin-matrix-starter migrate

// Rollback the last batch of migrations
wp wp-plugin-matrix-starter migrate:rollback

// Reset all migrations
wp wp-plugin-matrix-starter migrate:reset

// Show migration status
wp wp-plugin-matrix-starter migrate:status
```

Migration files are stored in `app/Database/Migrations` and follow this structure:

> **Note:** When using the rename script, database table prefixes in migration files will be automatically updated to match your new plugin name. This ensures that your database tables will use the correct prefix when migrations are run.

```php
class 2023_01_01_000000_create_users_table extends Migration
{
    protected $table = "users";

    public function up()
    {
        $charsetCollate = $this->getCharsetCollate();

        $sql = "CREATE TABLE {$this->table} (
            id int(10) NOT NULL AUTO_INCREMENT,
            name varchar(191) NOT NULL,
            email varchar(191) NOT NULL,
            created_at timestamp NULL DEFAULT NULL,
            updated_at timestamp NULL DEFAULT NULL,
            PRIMARY KEY (id)
        ) $charsetCollate;";

        $this->execute($sql);
    }

    public function down()
    {
        $sql = "DROP TABLE IF EXISTS {$this->table};";
        $this->wpdb->query($sql);
    }
}
```

### Security Helpers

The plugin includes security helpers for CSRF protection, XSS protection, and input sanitization:

```php
// Sanitize input
$sanitized = wp_plugin_matrix_starter_sanitize($_POST['title'], 'text');
$sanitizedEmail = wp_plugin_matrix_starter_sanitize($_POST['email'], 'email');
$sanitizedHtml = wp_plugin_matrix_starter_sanitize($_POST['content'], 'html');

// Escape output
<h1><?php echo wp_plugin_matrix_starter_escape($title); ?></h1>
<a href="<?php echo wp_plugin_matrix_starter_escape($url, 'url'); ?>">Link</a>

// CSRF protection
<?php echo wp_plugin_matrix_starter_nonce_field('my_action'); ?>

// Verify CSRF token
if (!wp_plugin_matrix_starter_verify_nonce('my_action')) {
    wp_die('Security check failed');
}
```

### Plugin Renamer

The plugin includes a simple PHP script that makes it easy to set up a new plugin by renaming all namespaces, prefixes, and constants to match your plugin name. This script works without requiring any external dependencies:

```bash
# Run the renamer script
php rename-plugin.php YourPluginName

# Works with multi-word plugin names (quotes optional)
php rename-plugin.php Your Amazing Plugin

# Preview changes without modifying files (dry run)
php rename-plugin.php YourPluginName --dry-run

# Combine options
php rename-plugin.php Your Amazing Plugin --dry-run --force
```

This will:
1. Rename the main plugin file to `your-plugin-name.php`
2. Update all namespaces from `WPPluginMatrixStarter` to `YourPluginName`
3. Update all function prefixes from `wp_plugin_matrix_starter_` to `your_plugin_name_`
4. Update all constants from `WP_PLUGIN_MATRIX_STARTER_` to `YOUR_PLUGIN_NAME_`
5. Update the custom autoloader to use your namespace
6. Update composer.json and package.json with your plugin information
7. Update JavaScript files, Vue components, and SCSS/CSS files
8. Update plugin menu titles in the WordPress admin dashboard
9. Update database table prefixes in migration files
10. Automatically rebuild assets using npm

The script is designed to work without requiring Composer, making it compatible with the plugin's custom PHP autoloader. It handles all aspects of renaming, including JavaScript and CSS files, ensuring a smooth transition to your custom plugin name.

#### Asset Rebuilding

After renaming, the script will automatically rebuild your assets using npm if it's available on your system. This ensures that all JavaScript and CSS files are properly updated with your new plugin name.

If npm is not available, you'll need to manually rebuild the assets:

```bash
# Install dependencies
npm install

# Build assets
npm run dev
```

This step is crucial because the compiled JavaScript and CSS files contain hardcoded references to the original plugin name that need to be updated.

### UI Components Library

The plugin includes a comprehensive UI components library for building admin interfaces:

#### Data Table

```vue
<DataTable
  :data="items"
  :loading="loading"
  :filters="[
    { name: 'Status', options: [
      { label: 'Active', value: 'status:active' },
      { label: 'Inactive', value: 'status:inactive' }
    ]}
  ]"
>
  <el-table-column prop="id" label="ID" sortable />
  <el-table-column prop="name" label="Name" sortable />
  <el-table-column prop="status" label="Status" />
</DataTable>
```

#### Modal Dialog

```vue
<Modal
  :visible.sync="modalVisible"
  title="Confirm Action"
  @confirm="handleConfirm"
  @cancel="handleCancel"
>
  <p>Are you sure you want to perform this action?</p>
</Modal>
```

#### File Uploader

```vue
<FileUploader
  v-model="files"
  action="/wp-admin/admin-ajax.php?action=upload_file"
  :headers="{ 'X-WP-Nonce': nonce }"
  :multiple="true"
  :limit="5"
  accept=".jpg,.jpeg,.png,.gif"
/>
```

#### Rich Text Editor

```vue
<RichTextEditor
  v-model="content"
  placeholder="Start writing..."
  :height="300"
  :show-word-count="true"
/>
```

#### Interface Builder

```vue
<InterfaceBuilder
  v-model="interfaceElements"
  @save="saveInterface"
/>
```

### Using UI Components in Your Plugin

To use the UI components in your Vue.js application, you can import them individually or register them all at once:

```js
// Import and register all components
import UIComponents from './Components';
app.use(UIComponents);

// Or import individual components
import { DataTable, Modal, RichTextEditor } from './Components';
app.component('DataTable', DataTable);
app.component('Modal', Modal);
app.component('RichTextEditor', RichTextEditor);
```

### Using the Notification System

The plugin includes a simple notification system for displaying messages, alerts, and confirmations:

```js
import notification from './Utils/notification';

export default {
  methods: {
    showSuccess() {
      notification.success('Operation completed successfully!');
    },
    showError() {
      notification.error('An error occurred');
    },
    showWarning() {
      notification.warning('This action might have consequences');
    },
    showInfo() {
      notification.info('Here is some information');
    },
    showToast() {
      notification.toast('Quick message', 'success');
    },
    confirmAction() {
      notification.confirm('Are you sure?', 'Confirm Action')
        .then(() => {
          // User confirmed
          this.performAction();
        })
        .catch(() => {
          // User canceled
        });
    },
    showAlert() {
      notification.alert('Important information', 'Alert')
        .then(() => {
          // User acknowledged
        });
    }
  }
};
```

### Making API Requests from Vue

The plugin makes it easy to interact with WordPress REST API endpoints:

```js
<template>
  <div>
    <button @click="fetchData" :disabled="loading">Fetch Data</button>
    <div v-if="loading">Loading...</div>
    <div v-if="error" class="error">{{ error }}</div>
    <ul v-if="data.length">
      <li v-for="item in data" :key="item.id">{{ item.title }}</li>
    </ul>
  </div>
</template>

<script>
import { ref } from 'vue';
import notification from '../Utils/notification';

export default {
  setup() {
    const data = ref([]);
    const loading = ref(false);
    const error = ref(null);

    const fetchData = async () => {
      loading.value = true;
      error.value = null;

      try {
        // Get the WordPress REST API nonce
        const nonce = window.wpApiSettings?.nonce || '';

        // Make the API request
        const response = await fetch('/wp-json/wp-plugin-matrix-starter/v1/items', {
          headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': nonce
          }
        });

        if (!response.ok) {
          throw new Error(`API error: ${response.status}`);
        }

        const result = await response.json();

        if (result.success) {
          data.value = result.data;
          notification.success('Data loaded successfully');
        } else {
          throw new Error(result.message || 'Unknown error');
        }
      } catch (err) {
        error.value = err.message;
        notification.error(`Failed to load data: ${err.message}`);
      } finally {
        loading.value = false;
      }
    };

    // Example of submitting data to an API endpoint
    const submitData = async (formData) => {
      loading.value = true;
      error.value = null;

      try {
        const nonce = window.wpApiSettings?.nonce || '';

        const response = await fetch('/wp-json/wp-plugin-matrix-starter/v1/items', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': nonce
          },
          body: JSON.stringify(formData)
        });

        if (!response.ok) {
          throw new Error(`API error: ${response.status}`);
        }

        const result = await response.json();

        if (result.success) {
          notification.success('Data saved successfully');
          return result.data;
        } else {
          throw new Error(result.message || 'Unknown error');
        }
      } catch (err) {
        error.value = err.message;
        notification.error(`Failed to save data: ${err.message}`);
        throw err;
      } finally {
        loading.value = false;
      }
    };

    return {
      data,
      loading,
      error,
      fetchData,
      submitData
    };
  }
};
</script>
```

## Distribution

The plugin includes a `.distignore` file that helps with creating clean distributions for WordPress.org or other platforms. This file tells build tools which files and directories to exclude when creating a distribution package.

The `.distignore` file excludes:
- Development files and directories (`.git`, `node_modules`, etc.)
- Build configuration files
- Source files (only including compiled assets)
- Documentation files not needed in the final plugin

This ensures that only the necessary files are included in the distributed plugin, keeping it lightweight and free of development artifacts.

## Available Commands

### Plugin Renaming Command

The plugin includes a simple dependency-free command-line tool for renaming the plugin:

```bash
# Rename the plugin
php rename-plugin.php YourPluginName

# Works with multi-word plugin names (quotes optional)
php rename-plugin.php Your Amazing Plugin

# Preview changes without modifying files
php rename-plugin.php YourPluginName --dry-run
```

The rename script handles all aspects of renaming, including JavaScript and CSS files, plugin menu titles in the WordPress admin dashboard, and automatically rebuilds assets to ensure a smooth transition to your custom plugin name. When you rename your plugin, the script will update both the menu title displayed in the WordPress admin sidebar and the title shown in the plugin's admin page header.

### NPM Commands

The plugin uses Laravel Mix (webpack) for asset compilation. The following npm commands are available:

```bash
# Install dependencies
npm install

# Development build with source maps
npm run dev

# Watch for changes and rebuild automatically
npm run watch

# Production build with optimizations
npm run prod

# Hot module replacement for development
npm run hot
```

### Composer Commands (Optional)

While the plugin uses a custom autoloader and doesn't require Composer for its core functionality, you can optionally use Composer for additional PHP dependencies:

```bash
# Install dependencies
composer install

# Update dependencies
composer update
```

### Plugin Setup Commands

```bash
# Set up a new plugin based on the boilerplate
php rename-plugin.php YourPluginName

# For multi-word plugin names (quotes optional)
php rename-plugin.php Your Amazing Plugin

# Preview changes first (recommended)
php rename-plugin.php YourPluginName --dry-run
```

The rename script will automatically:
1. Update all PHP, JavaScript, and CSS files with your new plugin name
2. Update plugin menu titles in the WordPress admin dashboard
3. Update database table prefixes in migration files
4. Install npm dependencies if needed
5. Rebuild assets to ensure everything works correctly

No manual rebuilding is required unless npm is not available on your system.

## Extending the Plugin

### Adding Custom Shortcodes

To add a custom shortcode, create a new class in the `app/Shortcodes` directory:

```php
<?php

namespace WPPluginMatrixStarter\Shortcodes;

class CustomShortcode
{
    /**
     * Register the shortcode
     */
    public function register()
    {
        add_shortcode('custom', [$this, 'render']);
    }

    /**
     * Render the shortcode
     *
     * @param array $atts Shortcode attributes
     * @param string|null $content Shortcode content
     * @return string
     */
    public function render($atts, $content = null)
    {
        $atts = shortcode_atts([
            'title' => 'Default Title',
            'color' => 'blue'
        ], $atts, 'custom');

        ob_start();
        ?>
        <div class="custom-shortcode" style="color: <?php echo esc_attr($atts['color']); ?>">
            <h3><?php echo esc_html($atts['title']); ?></h3>
            <?php if ($content): ?>
                <div class="content"><?php echo wp_kses_post($content); ?></div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
}
```

Then register your shortcode in the `ShortcodeHandler` class:

```php
// In app/Hooks/Handlers/ShortcodeHandler.php
public function register()
{
    $shortcodes = [
        new \WPPluginMatrixStarter\Shortcodes\CustomShortcode(),
        // Add more shortcodes here
    ];

    foreach ($shortcodes as $shortcode) {
        $shortcode->register();
    }
}
```

### Adding Custom REST API Endpoints

To add a custom REST API endpoint, define it in the `app/Http/Routes/api.php` file:

```php
// GET endpoint
$router->route('GET', 'custom-endpoint', function($request) {
    return [
        'success' => true,
        'data' => [
            'message' => 'This is a custom endpoint',
            'timestamp' => current_time('mysql')
        ]
    ];
});

// POST endpoint with controller
$router->route('POST', 'custom-data', [new \WPPluginMatrixStarter\Http\Controllers\CustomController(), 'store'], [
    'edit_posts' // Required capability
]);
```

Then create your controller:

```php
<?php

namespace WPPluginMatrixStarter\Http\Controllers;

class CustomController
{
    /**
     * Store custom data
     *
     * @param \WP_REST_Request $request
     * @return array
     */
    public function store($request)
    {
        $data = $request->get_params();

        // Validate data
        if (empty($data['title'])) {
            return new \WP_Error('missing_title', 'Title is required', ['status' => 400]);
        }

        // Process data
        // ...

        return [
            'success' => true,
            'message' => 'Data stored successfully',
            'data' => $data
        ];
    }
}
```

### Adding Custom Admin Pages

To add a custom admin page, create a new class in the `app/Admin` directory:

```php
<?php

namespace WPPluginMatrixStarter\Admin;

class CustomPage
{
    /**
     * Register the admin page
     */
    public function register()
    {
        add_action('admin_menu', [$this, 'addAdminPage']);
    }

    /**
     * Add admin page
     */
    public function addAdminPage()
    {
        add_submenu_page(
            'wp-plugin-matrix-starter', // Parent slug
            __('Custom Page', 'wp-plugin-matrix-starter'),
            __('Custom Page', 'wp-plugin-matrix-starter'),
            'manage_options',
            'wp-plugin-matrix-starter-custom',
            [$this, 'renderPage']
        );
    }

    /**
     * Render the admin page
     */
    public function renderPage()
    {
        echo '<div id="wp_plugin_matrix_starter_custom_page"></div>';
    }
}
```

Then register your admin page in the `AdminHandler` class:

```php
// In app/Hooks/Handlers/AdminHandler.php
public function register()
{
    // ... existing code

    // Register custom admin pages
    $customPage = new \WPPluginMatrixStarter\Admin\CustomPage();
    $customPage->register();
}
```

## License

GPL-2.0-or-later
