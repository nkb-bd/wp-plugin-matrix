import { createApp } from 'vue';
import routes from './routes';
import { createWebHashHistory, createRouter } from 'vue-router';
import WpBoilerplate from './Bits/AppMixins';
import ElementPlus from './Bits/elements';
import App from './App.vue';

// Create router instance
const router = createRouter({
    history: createWebHashHistory(),
    routes
});

// Initialize WpBoilerplate
const framework = new WpBoilerplate();

// Create the Vue 3 app instance with our App component
const app = createApp(App);

// Add the mixins
framework.extendVueConstructor(app);

// No need to register Navigation as a global component anymore

// Set Element Plus theme color
import { useCssVar } from 'element-plus/es';

// Define theme colors
const primaryColor = '#34b916'; // Green primary color
const secondaryColor = '#135e96';

// Set CSS variables programmatically
document.documentElement.style.setProperty('--el-color-primary', primaryColor);
document.documentElement.style.setProperty('--el-color-primary-light-3', primaryColor + '50');
document.documentElement.style.setProperty('--el-color-primary-light-5', primaryColor + '30');
document.documentElement.style.setProperty('--el-color-primary-light-7', primaryColor + '10');
document.documentElement.style.setProperty('--el-color-primary-light-8', primaryColor + '08');
document.documentElement.style.setProperty('--el-color-primary-light-9', primaryColor + '05');
document.documentElement.style.setProperty('--el-color-primary-dark-2', secondaryColor);

// Use plugins
app.use(ElementPlus);
app.use(router);

// Add global properties
app.config.globalProperties.appVars = window.wpBoilerplateAdmin;

// Global error handler
app.config.errorHandler = (err, vm, info) => {
    console.error('Vue Error:', err);
    console.error('Info:', info);
};

// Update document title based on route
router.beforeEach((to, from, next) => {
    // Show loading indicator for page transitions
    app.config.globalProperties.loading = true;

    // Update document title
    if (to.meta.title) {
        document.title = `${to.meta.title} - WP Boilerplate`;
    } else {
        document.title = 'WP Boilerplate';
    }

    next();
});

// Handle after route change
router.afterEach((to) => {
    // Hide loading indicator
    app.config.globalProperties.loading = false;

    // Update active menu item
    const active = to.meta.active;
    jQuery('.wp_boilerplate_menu_item').removeClass('active');

    if (active) {
        jQuery(`.wp_boilerplate_main-menu-items li[data-key=${active}]`).addClass('active');
    }
});

// Mount the app
window.WpBoilerplateApp = app.mount('#wp_boilerplate_app');
