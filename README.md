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
- Security helpers for CSRF protection, XSS protection, and input sanitization
- Debug panel for development
- Simple plugin renamer script for quick setup and customization (no dependencies required)
- Advanced UI components library:
    - Data tables with sorting, filtering, and pagination
    - Modal dialogs
    - Notifications system

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

# Works with multi-word plugin names
php rename-plugin.php "Your Amazing Plugin"

# Preview changes without modifying files
php rename-plugin.php YourPluginName --dry-run
```

This will:
1. Rename the main plugin file to `your-plugin-name.php`
2. Update all namespaces from `WPPluginMatrixStarter` to `YourPluginName`
3. Update all function prefixes from `wp_plugin_matrix_starter_` to `your_plugin_name_`
4. Update all constants from `WP_PLUGIN_MATRIX_STARTER_` to `YOUR_PLUGIN_NAME_` & plugin menu titles
5. Update the custom autoloader to use your namespace, composer.json and package.json

The tool works without requiring Composer, making it easy to set up a new plugin quickly.

### Hook Registration

The plugin provides a centralized way to register WordPress hooks with tracking for debugging:

```php
// Register an action hook
wp_plugin_matrix_starter_add_action('init', 'my_init_function');

// Register a hook with a class method
wp_plugin_matrix_starter_add_action('admin_menu', [$this, 'registerMenu']);


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

The plugin provides a powerful asset management system that handles script and style enqueuing with support for page-specific loading. This makes it easy to load your scripts and styles only where they're needed, improving performance.

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

### Security Helpers

The plugin includes security helpers for CSRF protection, XSS protection, and input sanitization:

```php
// Sanitize input
$sanitized = wp_plugin_matrix_starter_sanitize($_POST['title'], 'text');
$sanitizedEmail = wp_plugin_matrix_starter_sanitize($_POST['email'], 'email');


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

The plugin makes it easy to interact with WordPress REST API endpoints or AJAX actions in Vue 2:

```js
<template>
  <div>
    <button @click="fetchRestApi" :disabled="loading">REST API</button>
    <button @click="fetchAjax" :disabled="loading">AJAX Action</button>
    <div v-if="loading">Loading...</div>
    <div v-if="error" class="error">{{ error }}</div>
    <ul v-if="data.length">
      <li v-for="item in data" :key="item.id">{{ item.title }}</li>
    </ul>
  </div>
</template>

<script>
export default {
  data() {
    return {
      data: [],
      loading: false,
      error: null
    }
  },
  methods: {
    // REST API example
    async fetchRestApi() {
      this.loading = true;
      this.error = null;

      try {
        const nonce = window.wpApiSettings?.nonce || '';
        const response = await fetch('/wp-json/wp-plugin-matrix-starter/v1/items', {
          headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': nonce
          }
        });

        if (!response.ok) throw new Error(`API error: ${response.status}`);
        const result = await response.json();

        if (result.success) {
          this.data = result.data;
        } else {
          throw new Error(result.message || 'Unknown error');
        }
      } catch (err) {
        this.error = err.message;
      } finally {
        this.loading = false;
      }
    },

    // AJAX action example
    async fetchAjax() {
      this.loading = true;
      this.error = null;

      try {
        const formData = new FormData();
        formData.append('action', 'get_items');
        formData.append('nonce', window.my_plugin_vars.nonce);

        const response = await fetch(window.ajaxurl, {
          method: 'POST',
          body: formData
        });

        if (!response.ok) throw new Error(`AJAX error: ${response.status}`);
        const result = await response.json();

        if (result.success) {
          this.data = result.data;
        } else {
          throw new Error(result.message || 'Unknown error');
        }
      } catch (err) {
        this.error = err.message;
      } finally {
        this.loading = false;
      }
    }
  }
};
</script>
```

## Distribution

The plugin includes a `.distignore` file that helps with creating clean distributions for WordPress.org or other platforms.

```bash
# Create a distribution-ready plugin zip
npm run dist-archive
```

This command uses the `wp dist-archive` command to instantly build a clean plugin zip file, excluding development files like `.git`, `node_modules`, source files, and configuration files. The resulting zip is lightweight and ready for distribution.

## Available Commands



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


## License

GPL-2.0-or-later
