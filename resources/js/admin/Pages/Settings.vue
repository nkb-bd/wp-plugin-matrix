<template>
  <div class="content-area">
    <h1 class="page-title">Settings</h1>

    <div class="section">
      <el-tabs v-model="activeTab">
        <el-tab-pane label="General" name="general">
          <h2 class="section-title">General Settings</h2>

          <el-card>
            <div class="p-4">
              <el-form :model="settings.general" label-position="top" ref="generalForm">
                <el-form-item label="Plugin Title">
                  <el-input v-model="settings.general.title" placeholder="Plugin Title"></el-input>
                  <div class="text-gray-500 text-sm mt-1">The title displayed in the admin menu</div>
                </el-form-item>

                <el-form-item label="Enable Debug Mode">
                  <el-switch v-model="settings.general.debug"></el-switch>
                  <div class="text-gray-500 text-sm mt-1">Enable debug mode for development</div>
                </el-form-item>

                <el-form-item label="Cache Duration">
                  <el-input-number v-model="settings.general.cacheDuration" :min="0" :max="24"></el-input-number>
                  <span class="ml-2">hours</span>
                  <div class="text-gray-500 text-sm mt-1">Duration to cache data (0 to disable)</div>
                </el-form-item>

                <el-form-item>
                  <el-button type="primary" @click="saveSettings('general')">Save General Settings</el-button>
                </el-form-item>
              </el-form>
            </div>
          </el-card>
        </el-tab-pane>

        <el-tab-pane label="Appearance" name="appearance">
          <h2 class="section-title">Appearance Settings</h2>

          <el-card>
            <div class="p-4">
              <el-form :model="settings.appearance" label-position="top" ref="appearanceForm">
                <el-form-item label="Theme">
                  <el-select v-model="settings.appearance.theme" placeholder="Select theme">
                    <el-option label="Light" value="light"></el-option>
                    <el-option label="Dark" value="dark"></el-option>
                    <el-option label="Custom" value="custom"></el-option>
                  </el-select>
                </el-form-item>

                <el-form-item label="Primary Color" v-if="settings.appearance.theme === 'custom'">
                  <el-color-picker v-model="settings.appearance.primaryColor"></el-color-picker>
                </el-form-item>

                <el-form-item label="Font Size">
                  <el-select v-model="settings.appearance.fontSize" placeholder="Select font size">
                    <el-option label="Small" value="small"></el-option>
                    <el-option label="Medium" value="medium"></el-option>
                    <el-option label="Large" value="large"></el-option>
                  </el-select>
                </el-form-item>

                <el-form-item>
                  <el-button type="primary" @click="saveSettings('appearance')">Save Appearance Settings</el-button>
                </el-form-item>
              </el-form>
            </div>
          </el-card>
        </el-tab-pane>


      </el-tabs>
    </div>
  </div>
</template>

<script>
export default {
  name: "Settings",
  data() {
    return {
      activeTab: 'general',
      settings: {
        general: {
          title: 'WP Plugin Matrix Starter',
          debug: false,
          cacheDuration: 1
        },
        appearance: {
          theme: 'light',
          primaryColor: '#2271b1',
          fontSize: 'medium'
        }
      },
      loading: false
    };
  },
  methods: {
    saveSettings(section) {
      this.loading = true;

      // In a real application, you would send the settings to the server
      this.$post('settings/save', { settings: this.settings[section] })
        .then(response => {
          this.$message.success('Settings saved successfully!');
          console.log('Settings saved:', response);
        })
        .catch(error => {
          this.$message.error('Failed to save settings');
          console.error('Error saving settings:', error);
        })
        .finally(() => {
          this.loading = false;
        });
    },
    loadSettings() {
      this.loading = true;

      // In a real application, you would fetch the settings from the server
      this.$get('settings/get')
        .then(response => {
          if (response.success && response.settings) {
            // Merge the settings with the default settings
            this.settings = {
              ...this.settings,
              ...response.settings
            };
          }
        })
        .catch(error => {
          this.$message.error('Failed to load settings');
          console.error('Error loading settings:', error);
        })
        .finally(() => {
          this.loading = false;
        });
    }
  },
  mounted() {
    this.loadSettings();
  }
};
</script>
