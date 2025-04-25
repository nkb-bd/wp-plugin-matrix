<template>
  <div class="content-area">
    <h1 class="page-title">Dashboard</h1>

    <div class="section">
      <h2 class="section-title">Welcome to WP Boilerplate</h2>

      <el-card class="mb-4">
        <div class="p-4">
          <h3 class="text-xl font-medium mb-2">Modern WordPress Plugin Boilerplate</h3>
          <p class="mb-4">A modern WordPress plugin boilerplate with Vue.js, Tailwind CSS, and Laravel Mix, following PSR-4 autoloading and modern PHP practices.</p>

          <div class="flex flex-wrap gap-2 mb-4">
            <el-tag type="primary">Vue.js 3</el-tag>
            <el-tag type="success">Tailwind CSS</el-tag>
            <el-tag type="warning">Laravel Mix</el-tag>
            <el-tag type="info">Element Plus</el-tag>
            <el-tag type="danger">PSR-4</el-tag>
          </div>

          <el-button type="primary" @click="fetchData">Fetch Data</el-button>
        </div>
      </el-card>

      <el-card v-if="data" class="mb-4">
        <div class="p-4">
          <h3 class="text-lg font-medium mb-2">Response from Server:</h3>
          <pre class="bg-gray-100 p-3 rounded">{{ data }}</pre>
        </div>
      </el-card>
    </div>

    <div class="section">
      <h2 class="section-title">Element Plus Components</h2>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <el-card>
          <div class="p-4">
            <h3 class="text-lg font-medium mb-2">Buttons</h3>
            <div class="flex flex-wrap gap-2">
              <el-button>Default</el-button>
              <el-button type="primary">Primary</el-button>
              <el-button type="success">Success</el-button>
              <el-button type="warning">Warning</el-button>
              <el-button type="danger">Danger</el-button>
              <el-button type="info">Info</el-button>
            </div>
          </div>
        </el-card>

        <el-card>
          <div class="p-4">
            <h3 class="text-lg font-medium mb-2">Form Elements</h3>
            <el-form>
              <el-form-item label="Input">
                <el-input v-model="form.input" placeholder="Type something..."></el-input>
              </el-form-item>
              <el-form-item label="Select">
                <el-select v-model="form.select" placeholder="Select an option">
                  <el-option label="Option 1" value="1"></el-option>
                  <el-option label="Option 2" value="2"></el-option>
                </el-select>
              </el-form-item>
              <el-form-item>
                <el-switch v-model="form.switch"></el-switch> Toggle switch
              </el-form-item>
            </el-form>
          </div>
        </el-card>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Dashboard",
  data() {
    return {
      data: null,
      form: {
        input: '',
        select: '',
        switch: false
      }
    };
  },
  methods: {
    fetchData() {
      this.$get('test')
        .then(response => {
          this.data = response.data;
          // Use our custom notification utility
          this.$notification.success('Data fetched successfully!');
        })
        .catch(error => {
          console.error('Error fetching data:', error);

          // Check if it's a security error (nonce failure)
          if (error.status === 403) {
            this.$notification.error('Security check failed. Please refresh the page and try again.');
          } else {
            this.$notification.error('Failed to fetch data');
          }
        });
    }
  },
  mounted() {
    console.log("Dashboard Page mounted.");
  }
};
</script>
