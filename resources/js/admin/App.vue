<template>
  <div>
    <div class="main-menu">
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <div class="font-bold text-xl mr-4">WP Plugin Matrix BoilerPlate</div>
          <Navigation />
        </div>
        <div class="text-sm text-gray-500">
          v{{ version }}
        </div>
      </div>
    </div>
    <router-view></router-view>
  </div>
</template>

<script>
import Navigation from './components/Navigation.vue';

export default {
  name: 'App',
  components: {
    Navigation
  },
  data() {
    return {
      version: window.wpPluginMatrixBoilerPlateAdmin?.version || '1.0.0'
    };
  }
};
</script>
