<template>
  <div>
    <div class="col-12 md:col-6 p-6 text-center md:text-left flex align-items-center ">
      <section>
        <span class="block text-6xl font-bold mb-1">Dashboard Page Vue + Laravel Mix</span>
        <!-- a smalll list of features likes tags styled -->
        <div class="p-4">
          <ul class="flex space-x-4 list-none p-0 text-blue-600">
            <li class="border border-gray-300 rounded px-3 py-1 bg-white">
              Vue
            </li>
            <li class="border border-gray-300 rounded px-3 py-1 bg-white">
              Tailwind
            </li>
            <li class="border border-gray-300 rounded px-3 py-1 bg-white">
              Laravel Mix
            </li>
            <li class="border border-gray-300 rounded px-3 py-1 bg-white">
              Element +
            </li>
          </ul>
        </div>

        <div class="text-2xl text-primary font-bold mb-3">Hello, There</div>
        <p class="mt-0 mb-4 text-700 line-height-3">No need to reload your page 🥳</p>
        <!-- Response from server highlighted -->
        <div v-if="loading" class="p-3 mb-0 text-center" style="background-color: #f8f9fa; border-radius: 4px;">
          Loading data...
        </div>
        <div v-else-if="error" class="p-3 mb-0 text-center" style="background-color: #ffeeee; border-radius: 4px; color: #cc0000;">
          {{ error }}
          <div>
            <button @click="fetchData" class="mt-2 p-2 bg-primary text-white rounded">
              Try Again
            </button>
          </div>
        </div>
        <pre v-else class="p-3 mb-0 text-center" style="text-align:center;background-color: #f8f9fa; border-radius: 4px;">
          {{ data }}
        </pre>
      </section>
    </div>

  </div>
</template>
<script type="module">

export default {
    name: "dashboard-page",
    data() {
      return {
        data: null,
        loading: false,
        error: null
      };
    },
    components: {
    },
    methods: {
      fetchData() {
        this.loading = true;
        this.error = null;

        this.$get('test')
          .then(response => {
            this.data = response.data;
            this.loading = false;
          })
          .catch(error => {
            console.error('Error fetching data:', error);

            // Get error details
            let errorMessage = 'Failed to fetch data from server. Please try again.';

            // Check if it's a security error (nonce failure)
            if (error.status === 403) {
              errorMessage = 'Security check failed. Please refresh the page and try again.';
            } else if (error.responseJSON && error.responseJSON.message) {
              // Use the server's error message if available
              errorMessage = error.responseJSON.message;
            }

            this.error = errorMessage;
            this.loading = false;

            // Log additional debug info
            if (window.wPPluginMatrixStarterAdmin && window.wPPluginMatrixStarterAdmin.is_dev) {
              console.log('Debug info:', {
                ajaxUrl: window.wPPluginMatrixStarterAdmin.ajaxurl,
                nonce: window.wPPluginMatrixStarterAdmin.nonce ? 'Present' : 'Missing',
                error: error
              });
            }
          });
      }
    },
    mounted() {
      console.log("Dashboard Page mounted.");
      this.fetchData();
    }
};
</script>
