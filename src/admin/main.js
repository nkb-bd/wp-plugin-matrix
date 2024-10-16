import { createApp } from 'vue'; // Import createApp for Vue 3
import routes from './routes'; 
import { createWebHashHistory, createRouter } from 'vue-router';
import PluginClassName from './Bits/AppMixins';
import ElementPlus from './Bits/elements';

const router = createRouter({
    history: createWebHashHistory(),
    routes
});

// Initialize PluginClassName
const framework = new PluginClassName();

// Create the Vue 3 app instance
const app = createApp(framework.app);

//add the mixing
framework.extendVueConstructor(app); 

app.use(ElementPlus); 

app.config.globalProperties.appVars = window.PluginClassNameAdmin;

// Mount the app and apply the router
window.PluginClassNameApp = app.use(router).mount('#pluginlowercase_app');

// Handle active menu item after each route change
router.afterEach((to) => {
    const active = to.meta.active;
    jQuery('.pluginlowercase_menu_item').removeClass('active');
    
    if (active) {
        jQuery(`.pluginlowercase_main-menu-items li[data-key=${active}]`).addClass('active');
    }
});
