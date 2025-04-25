# Asset Management System

This document explains how the asset management system works in the WP Boilerplate plugin.

## Overview

The asset management system provides a clean and efficient way to:

1. Register scripts and styles
2. Load assets only on specific admin pages
3. Conditionally load assets based on custom logic
4. Localize scripts with data
5. Separate admin and frontend assets

## Key Components

### 1. Asset Class (`app/Core/Asset.php`)

This is the core implementation that handles:
- Registering scripts and styles
- Enqueuing assets at the right time
- Managing admin page-specific loading
- Conditional loading based on callbacks

### 2. Asset Facade (`app/Facades/Asset.php`)

Provides a static interface to the Asset class methods:
- `Asset::registerScript()` - Register a JavaScript file
- `Asset::registerStyle()` - Register a CSS file
- `Asset::localizeScript()` - Add data to a JavaScript file
- `Asset::setPluginSlug()` - Set the plugin slug for admin page detection
- `Asset::url()` - Get the URL for an asset

### 3. AssetHandler (`app/Hooks/Handlers/AssetHandler.php`)

Coordinates the registration of all plugin assets:
- Registers all scripts and styles
- Sets up asset loading for the plugin's admin pages
- Sets up the necessary WordPress hooks

## How It Works

1. During plugin initialization, the `AssetHandler::register()` method is called
2. This registers all scripts and styles (doesn't enqueue them yet)
3. When a page loads:
   - For admin pages: WordPress calls `admin_enqueue_scripts`
   - For frontend: WordPress calls `wp_enqueue_scripts`
4. The Asset class checks if the current page is our plugin's page by checking the URL parameter (`page=wp-boilerplate`)
5. If yes, it enqueues the appropriate scripts and styles

## Usage Examples

### Registering a Script

```php
// In AssetHandler::registerAssets()
Asset::registerScript(
    'my-script-handle',     // Handle for the script
    'js/my-script.js',      // Path relative to dist directory
    ['jquery'],             // Dependencies
    '1.0.0',                // Version
    true,                   // Load in footer
    'admin',                // Context: 'admin', 'frontend', or 'both'
    function($hook) {       // Optional condition callback
        // Additional conditions beyond the main page check
        return isset($_GET['section']) && $_GET['section'] === 'advanced';
    }
);
```

### Registering a Style

```php
Asset::registerStyle(
    'my-style-handle',      // Handle for the style
    'css/my-style.css',     // Path relative to dist directory
    [],                     // Dependencies
    '1.0.0',                // Version
    'all',                  // Media
    'both'                  // Context: 'admin', 'frontend', or 'both'
);
```

### Localizing a Script with Data

```php
Asset::localizeScript(
    'my-script-handle',     // Script handle to localize
    'myScriptData',         // JavaScript object name
    [                       // Data to pass to JavaScript
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('my_nonce'),
        'settings' => [
            'option1' => get_option('my_option1'),
            'option2' => get_option('my_option2')
        ]
    ]
);
```

### Setting the Plugin Slug

```php
// In AssetHandler::registerAssets()
// Set the plugin slug to match your admin page URL parameter
Asset::setPluginSlug('my-plugin');

// Now assets will only load when the URL contains ?page=my-plugin
```

## Asset Compilation

Assets are compiled using Laravel Mix (webpack) as defined in `webpack.mix.js`. The compiled assets are stored in the `dist` directory.

To compile assets:

```bash
# Development build
npm run dev

# Production build
npm run prod

# Watch for changes
npm run watch
```

## Best Practices

1. Only load assets where they're needed
2. Use conditional loading for frontend assets
3. Keep admin and frontend assets separate
4. Use version numbers for cache busting
5. Minimize dependencies
6. Use the filter hooks to allow customization
