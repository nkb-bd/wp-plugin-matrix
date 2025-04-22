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
  </div>
</template>

<script>
export default {
  name: 'Navigation'
}
</script>
