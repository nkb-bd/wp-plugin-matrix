<template>
  <div class="flex space-x-2">
    <router-link to="/" class="px-3 py-2 rounded-md">
      Dashboard
    </router-link>
    <router-link to="/contact" class="px-3 py-2 rounded-md">
      Contact
    </router-link>
    <router-link to="/settings" class="px-3 py-2 rounded-md">
      Settings
    </router-link>
    <router-link to="/demo" class="px-3 py-2 rounded-md bg-blue-100">
      Components Demo
    </router-link>
  </div>
</template>

<script>
export default {
  name: 'Navigation'
}
</script>
