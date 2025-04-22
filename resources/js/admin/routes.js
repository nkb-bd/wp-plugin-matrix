// Import components
import Dashboard from './Pages/Dashboard.vue';
import Contact from './Pages/Contact.vue';
import Settings from './Pages/Settings.vue';
import NotFound from './Pages/NotFound.vue';

// Define routes
const routes = [
    {
        path: '/',
        name: 'dashboard',
        component: Dashboard,
        meta: {
            title: 'Dashboard',
            active: 'dashboard'
        },
    },
    {
        path: '/contact',
        name: 'contact',
        component: Contact,
        meta: {
            title: 'Contact',
            active: 'contact'
        },
    },
    {
        path: '/settings',
        name: 'settings',
        component: Settings,
        meta: {
            title: 'Settings',
            active: 'settings'
        },
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'not-found',
        component: NotFound,
        meta: {
            title: 'Page Not Found'
        },
    }
];

export default routes;
