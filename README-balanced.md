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
   This will rename all files, namespaces, function prefixes, constants, database table prefixes, and menu titles to match your plugin name.

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

## Plugin Renaming Tool

The plugin includes a powerful renaming tool that makes it easy to customize the boilerplate for your own plugin:

```bash
# Run the renaming tool
php rename-plugin.php YourPluginName

# Works with multi-word plugin names (quotes optional)
php rename-plugin.php Your Amazing Plugin

# Preview changes without modifying files
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

The rename script handles all aspects of renaming, including JavaScript and CSS files, plugin menu titles in the WordPress admin dashboard, and automatically rebuilds assets to ensure a smooth transition to your custom plugin name. When you rename your plugin, the script will update both the menu title displayed in the WordPress admin sidebar and the title shown in the plugin's admin page header.

## Database Migration System

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

When using the rename script, database table prefixes in migration files will be automatically updated to match your new plugin name. This ensures that your database tables will use the correct prefix when migrations are run.

## Hook Registration

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

## Facade Pattern

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
```

## UI Components Library

The plugin includes a comprehensive UI components library for building admin interfaces:

### Data Table

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

### Modal Dialog

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

### File Uploader

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

## License

GPL-2.0-or-later
