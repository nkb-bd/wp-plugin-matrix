# WP Boilerplate Plugin

A modern WordPress plugin boilerplate with Vue.js, Tailwind CSS, and Laravel Mix, following PSR-4 autoloading and modern PHP practices.

## Features

- PSR-4 autoloading with Composer
- Modern PHP architecture (MVC pattern)
- Vue.js 3 for admin interface
- Tailwind CSS for styling
- Laravel Mix for asset compilation
- Modular organization with clear separation of concerns

## Directory Structure

```
app/                  # PHP application code
  Helpers/            # Helper functions and utilities
  Hooks/              # WordPress hooks handlers
    Handlers/         # Hook handler classes
  Http/               # HTTP-related code
    Controllers/      # Controllers for handling requests
  Model/              # Data models
  Services/           # Service classes
dist/                 # Compiled assets (generated)
language/             # Translation files
resources/            # Frontend source files
  js/                 # JavaScript files
  scss/               # SCSS files
  images/             # Image files
vendor/               # Composer dependencies (generated)
wp-boilerplate.php    # Main plugin file
composer.json         # Composer configuration
package.json          # NPM configuration
```

## Installation

1. Clone the repository:
   ```
   git clone https://github.com/yourusername/wp-boilerplate
   ```

2. Install PHP dependencies:
   ```
   composer install
   ```

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

The plugin follows a modern PHP architecture with PSR-4 autoloading. All PHP code is organized in the `app` directory with proper namespacing.

### JavaScript

Vue.js 3 is used for the admin interface. The source files are located in the `resources/js` directory.

### CSS

Tailwind CSS is used for styling. The source files are located in the `resources/scss` directory.

## License

GPL-2.0-or-later
