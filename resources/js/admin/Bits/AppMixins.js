export default class AppMixins {
    constructor() {
        this.ajaxUrl = window.wpPluginMatrixBoilerPlateAdmin.ajaxurl;
    }

    extendVueConstructor(app) {
        app.mixin({
            methods: {
                $get: this.$get.bind(this),
                $post: this.$post.bind(this),
                $del: this.$del.bind(this),
                $put: this.$put.bind(this),
                $patch: this.$patch.bind(this),
            }
        });
    }

    request(method, route, data = {}) {
        const url = `${this.ajaxUrl}`;
        const headers = {};

        data.action = 'wp_plugin_matrix_boiler_plate_admin_ajax';
        data.route = route;

        // Add nonce if available
        if (window.wpPluginMatrixBoilerPlateAdmin && window.wpPluginMatrixBoilerPlateAdmin.nonce) {
            data._wpnonce = window.wpPluginMatrixBoilerPlateAdmin.nonce;
        }

        if (['PUT', 'PATCH', 'DELETE'].includes(method.toUpperCase())) {
            headers['X-HTTP-Method-Override'] = method;
            method = 'POST';
        }

        // Wrap jQuery AJAX in a Promise to ensure it has finally() method
        return new Promise((resolve, reject) => {
            window.jQuery.ajax({
                url,
                type: method,
                data,
                headers,
                success: (response) => resolve(response),
                error: (xhr, status, error) => reject({ xhr, status, error })
            });
        });
    }

    $get(route, data = {}) {
        return this.request('GET', route, data);
    }

    $post(route, data = {}) {
        return this.request('POST', route, data);
    }

    $del(route, data = {}) {
        return this.request('DELETE', route, data);
    }

    $put(route, data = {}) {
        return this.request('PUT', route, data);
    }

    $patch(route, data = {}) {
        return this.request('PATCH', route, data);
    }
}

// Update nonce after successful AJAX requests
jQuery(document).ajaxSuccess((event, xhr) => {
    const nonce = xhr.getResponseHeader('X-WP-Nonce');
    if (nonce && window.wpPluginMatrixBoilerPlateAdmin) {
        window.wpPluginMatrixBoilerPlateAdmin.nonce = nonce;
    }
});
