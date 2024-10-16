export default class AppMixins {
    constructor() {
        this.ajaxUrl = window.pluginlowercaseAdmin.ajaxurl;
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

        data.action = 'woo_com_exporter_admin_ajax';
        data.route = route;

        if (['PUT', 'PATCH', 'DELETE'].includes(method.toUpperCase())) {
            headers['X-HTTP-Method-Override'] = method;
            method = 'POST';
        }

        return window.jQuery.ajax({
            url,
            type: method,
            data,
            headers
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
    if (nonce) {
        window.PluginClassName.rest.nonce = nonce;
    }
});
