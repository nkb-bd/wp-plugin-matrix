<template>
  <div class="content-area">
    <div class="text-center py-10">
      <h1 class="text-6xl font-bold text-gray-300 mb-4">404</h1>
      <h2 class="text-2xl font-semibold mb-4">Page Not Found</h2>
      <p class="text-gray-600 mb-6">The page you are looking for does not exist or has been moved.</p>
      <el-button type="primary" @click="goHome">Go to Dashboard</el-button>
    </div>
  </div>
</template>

<script>
export default {
  name: "NotFound",
  methods: {
    goHome() {
      this.$router.push('/');
    }
  }
};
</script>
