<template>
  <div class="demo-page">
    <el-card class="demo-header">
      <template #header>
        <div class="card-header">
          <h1>Components & Commands Demo</h1>
          <p>This page demonstrates the UI components and commands available in the WP Boilerplate plugin.</p>
        </div>
      </template>

      <el-tabs v-model="activeTab" type="border-card">
        <el-tab-pane label="Data Table" name="data-table">
          <h2>Data Table Component</h2>
          <p>A powerful data table with sorting, filtering, and pagination.</p>

          <DataTable
            :data="tableData"
            :loading="tableLoading"
            :filters="tableFilters"
            @sort-change="handleSortChange"
          >
            <el-table-column prop="id" label="ID" sortable width="80" />
            <el-table-column prop="name" label="Name" sortable />
            <el-table-column prop="email" label="Email" />
            <el-table-column prop="status" label="Status">
              <template #default="scope">
                <el-tag :type="scope.row.status === 'active' ? 'success' : 'danger'">
                  {{ scope.row.status }}
                </el-tag>
              </template>
            </el-table-column>
            <el-table-column label="Actions" width="150">
              <template #default="scope">
                <el-button size="small" @click="showModal(scope.row)">View</el-button>
                <el-button size="small" type="danger" @click="deleteItem(scope.row)">Delete</el-button>
              </template>
            </el-table-column>
          </DataTable>
        </el-tab-pane>

        <el-tab-pane label="Modal Dialog" name="modal">
          <h2>Modal Dialog Component</h2>
          <p>A flexible modal dialog component.</p>

          <div class="demo-buttons">
            <el-button type="primary" @click="showBasicModal">Basic Modal</el-button>
            <el-button type="success" @click="showConfirmModal">Confirmation Modal</el-button>
            <el-button type="warning" @click="showCustomModal">Custom Modal</el-button>
          </div>

          <!-- Basic Modal -->
          <Modal
            :visible.sync="basicModalVisible"
            title="Basic Modal"
            @confirm="handleBasicConfirm"
            @cancel="handleBasicCancel"
          >
            <p>This is a basic modal dialog with default buttons.</p>
          </Modal>

          <!-- Confirmation Modal -->
          <Modal
            :visible.sync="confirmModalVisible"
            title="Confirm Action"
            confirm-text="Yes, Delete"
            cancel-text="Cancel"
            @confirm="handleConfirmAction"
            @cancel="handleCancelAction"
          >
            <div class="confirm-content">
              <i class="el-icon-warning" style="font-size: 48px; color: #E6A23C;"></i>
              <p>Are you sure you want to delete this item? This action cannot be undone.</p>
            </div>
          </Modal>

          <!-- Custom Modal -->
          <Modal
            :visible.sync="customModalVisible"
            title="Custom Modal"
            width="70%"
          >
            <template #default>
              <div class="custom-modal-content">
                <h3>Custom Content</h3>
                <p>This modal has custom content and footer buttons.</p>
                <el-form :model="customForm" label-position="top">
                  <el-form-item label="Name">
                    <el-input v-model="customForm.name"></el-input>
                  </el-form-item>
                  <el-form-item label="Email">
                    <el-input v-model="customForm.email"></el-input>
                  </el-form-item>
                </el-form>
              </div>
            </template>

            <template #footer>
              <div class="custom-footer">
                <el-button @click="customModalVisible = false">Cancel</el-button>
                <el-button type="primary" @click="handleCustomSubmit">Submit</el-button>
                <el-button type="success" @click="handleCustomSave">Save Draft</el-button>
              </div>
            </template>
          </Modal>
        </el-tab-pane>

        <el-tab-pane label="Notifications" name="notifications">
          <h2>Notifications System</h2>
          <p>A comprehensive notification utility for displaying messages and confirmations.</p>

          <div class="demo-buttons">
            <el-button type="success" @click="showSuccessNotification">Success</el-button>
            <el-button type="warning" @click="showWarningNotification">Warning</el-button>
            <el-button type="danger" @click="showErrorNotification">Error</el-button>
            <el-button type="info" @click="showInfoNotification">Info</el-button>
            <el-button @click="showConfirmDialog">Confirm Dialog</el-button>
            <el-button @click="showPromptDialog">Prompt Dialog</el-button>
          </div>
        </el-tab-pane>

        <el-tab-pane label="File Uploader" name="file-uploader">
          <h2>File Uploader Component</h2>
          <p>A file uploader component with preview functionality.</p>

          <FileUploader
            v-model="uploadedFiles"
            action="/wp-admin/admin-ajax.php?action=wp_boilerplate_admin_ajax&route=upload"
            :headers="{ 'X-WP-Nonce': wpBoilerplateAdmin.nonce }"
            :multiple="true"
            :limit="5"
            :custom-upload="true"
            @custom-upload="handleCustomUpload"
          >
            <template #drag-text>
              Drop files here or <em>click to upload</em>
            </template>

            <template #tip>
              Accepted file types: .jpg, .jpeg, .png, .pdf, .doc, .docx (Max 5 files)
            </template>
          </FileUploader>

          <div class="uploaded-files" v-if="uploadedFiles.length > 0">
            <h3>Uploaded Files</h3>
            <ul>
              <li v-for="(file, index) in uploadedFiles" :key="index">
                {{ file.name }} ({{ formatFileSize(file.size) }})
              </li>
            </ul>
          </div>
        </el-tab-pane>

        <el-tab-pane label="Rich Text Editor" name="rich-text-editor">
          <h2>Rich Text Editor Component</h2>
          <p>A rich text editor component based on Quill.</p>

          <RichTextEditor
            v-model="editorContent"
            placeholder="Start writing..."
            :height="300"
            :show-word-count="true"
          />

          <div class="editor-preview" v-if="editorContent">
            <h3>Preview</h3>
            <div v-html="editorContent"></div>
          </div>
        </el-tab-pane>



        <el-tab-pane label="Commands" name="commands">
          <h2>Available Commands</h2>
          <p>This tab demonstrates the various commands available in the WP Boilerplate plugin.</p>

          <el-collapse accordion>
            <el-collapse-item title="WP-CLI Commands" name="wp-cli">
              <div class="command-section">
                <h3>Database Migration Commands</h3>
                <div class="command-item">
                  <code>wp wp-boilerplate migrate</code>
                  <p>Run all pending migrations</p>
                </div>
                <div class="command-item">
                  <code>wp wp-boilerplate migrate:rollback</code>
                  <p>Rollback the last batch of migrations</p>
                </div>
                <div class="command-item">
                  <code>wp wp-boilerplate migrate:rollback --steps=3</code>
                  <p>Rollback multiple batches</p>
                </div>
                <div class="command-item">
                  <code>wp wp-boilerplate migrate:reset</code>
                  <p>Reset all migrations</p>
                </div>
                <div class="command-item">
                  <code>wp wp-boilerplate migrate:make create_custom_table --table=custom_table</code>
                  <p>Create a new migration</p>
                </div>
                <div class="command-item">
                  <code>wp wp-boilerplate migrate:status</code>
                  <p>Show migration status</p>
                </div>
              </div>
            </el-collapse-item>

            <el-collapse-item title="NPM Commands" name="npm">
              <div class="command-section">
                <h3>Asset Compilation Commands</h3>
                <div class="command-item">
                  <code>npm install</code>
                  <p>Install dependencies</p>
                </div>
                <div class="command-item">
                  <code>npm run dev</code>
                  <p>Development build with source maps</p>
                </div>
                <div class="command-item">
                  <code>npm run watch</code>
                  <p>Watch for changes and rebuild automatically</p>
                </div>
                <div class="command-item">
                  <code>npm run prod</code>
                  <p>Production build with optimizations</p>
                </div>
                <div class="command-item">
                  <code>npm run hot</code>
                  <p>Hot module replacement for development</p>
                </div>
              </div>
            </el-collapse-item>

            <el-collapse-item title="Composer Commands" name="composer">
              <div class="command-section">
                <h3>PHP Dependency Management Commands</h3>
                <div class="command-item">
                  <code>composer install</code>
                  <p>Install dependencies</p>
                </div>
                <div class="command-item">
                  <code>composer update</code>
                  <p>Update dependencies</p>
                </div>
                <div class="command-item">
                  <code>composer install --dev</code>
                  <p>Install development dependencies</p>
                </div>
                <div class="command-item">
                  <code>composer install --no-dev --optimize-autoloader</code>
                  <p>Optimize autoloader for production</p>
                </div>
              </div>
            </el-collapse-item>

            <el-collapse-item title="Plugin Setup Commands" name="setup">
              <div class="command-section">
                <h3>Plugin Setup Commands</h3>
                <div class="command-item">
                  <code>php installer.php YourPluginName</code>
                  <p>Set up a new plugin based on the boilerplate</p>
                </div>
                <div class="command-item">
                  <code>composer install</code>
                  <p>Install PHP dependencies</p>
                </div>
                <div class="command-item">
                  <code>npm install</code>
                  <p>Install JavaScript dependencies</p>
                </div>
                <div class="command-item">
                  <code>npm run dev</code>
                  <p>Build assets</p>
                </div>
              </div>
            </el-collapse-item>
          </el-collapse>
        </el-tab-pane>
      </el-tabs>
    </el-card>
  </div>
</template>

<script>
import { DataTable, Modal, FileUploader, RichTextEditor } from '../Components';
import notification from '../Utils/notification';

export default {
  name: 'DemoPage',

  components: {
    DataTable,
    Modal,
    FileUploader,
    RichTextEditor
  },

  data() {
    return {
      activeTab: 'data-table',

      // Data Table
      tableData: [],
      tableLoading: true,
      tableFilters: [
        {
          name: 'Status',
          options: [
            { label: 'Active', value: 'status:active' },
            { label: 'Inactive', value: 'status:inactive' }
          ]
        }
      ],

      // Modal
      basicModalVisible: false,
      confirmModalVisible: false,
      customModalVisible: false,
      customForm: {
        name: '',
        email: ''
      },

      // File Uploader
      uploadedFiles: [],

      // Rich Text Editor
      editorContent: '<h2>Welcome to the Rich Text Editor</h2><p>This is a sample content to demonstrate the editor functionality.</p><ul><li>Feature 1</li><li>Feature 2</li><li>Feature 3</li></ul>',


    };
  },

  mounted() {
    this.loadTableData();
  },

  methods: {
    // Data Table Methods
    loadTableData() {
      this.tableLoading = true;

      // Simulate API call
      setTimeout(() => {
        this.tableData = [
          { id: 1, name: 'John Doe', email: 'john@example.com', status: 'active' },
          { id: 2, name: 'Jane Smith', email: 'jane@example.com', status: 'active' },
          { id: 3, name: 'Bob Johnson', email: 'bob@example.com', status: 'inactive' },
          { id: 4, name: 'Alice Brown', email: 'alice@example.com', status: 'active' },
          { id: 5, name: 'Charlie Wilson', email: 'charlie@example.com', status: 'inactive' }
        ];

        this.tableLoading = false;
      }, 1000);
    },

    handleSortChange(column) {
      console.log('Sort changed:', column);
    },

    // Modal Methods
    showModal(row) {
      this.selectedRow = row;
      this.basicModalVisible = true;
    },

    deleteItem(row) {
      this.selectedRow = row;
      this.confirmModalVisible = true;
    },

    showBasicModal() {
      this.basicModalVisible = true;
    },

    showConfirmModal() {
      this.confirmModalVisible = true;
    },

    showCustomModal() {
      this.customModalVisible = true;
    },

    handleBasicConfirm() {
      notification.success('Basic modal confirmed');
      this.basicModalVisible = false;
    },

    handleBasicCancel() {
      notification.info('Basic modal canceled');
      this.basicModalVisible = false;
    },

    handleConfirmAction() {
      notification.success('Item deleted successfully');
      this.confirmModalVisible = false;
    },

    handleCancelAction() {
      notification.info('Delete action canceled');
      this.confirmModalVisible = false;
    },

    handleCustomSubmit() {
      notification.success(`Form submitted: ${this.customForm.name} (${this.customForm.email})`);
      this.customModalVisible = false;
    },

    handleCustomSave() {
      notification.success('Draft saved');
      this.customModalVisible = false;
    },

    // Notification Methods
    showSuccessNotification() {
      notification.success('Operation completed successfully!');
    },

    showWarningNotification() {
      notification.warning('This action might have consequences.');
    },

    showErrorNotification() {
      notification.error('An error occurred while processing your request.');
    },

    showInfoNotification() {
      notification.info('Here is some information you might find useful.');
    },

    showConfirmDialog() {
      notification.confirm('Are you sure you want to proceed?', 'Confirm Action')
        .then(() => {
          notification.success('Action confirmed');
        })
        .catch(() => {
          notification.info('Action canceled');
        });
    },

    showPromptDialog() {
      notification.prompt('Please enter your name:', 'User Input')
        .then(({ value }) => {
          notification.success(`Hello, ${value}!`);
        })
        .catch(() => {
          notification.info('Input canceled');
        });
    },

    // File Uploader Methods
    handleCustomUpload({ file, onProgress, onSuccess, onError }) {
      // Simulate file upload
      const progress = { percent: 0 };
      const interval = setInterval(() => {
        progress.percent += 10;
        onProgress(progress);

        if (progress.percent >= 100) {
          clearInterval(interval);
          onSuccess({ url: URL.createObjectURL(file) });
        }
      }, 300);
    },

    formatFileSize(size) {
      if (size < 1024) {
        return size + ' B';
      } else if (size < 1024 * 1024) {
        return (size / 1024).toFixed(2) + ' KB';
      } else {
        return (size / (1024 * 1024)).toFixed(2) + ' MB';
      }
    },


  }
};
</script>

<style scoped>
.demo-page {
  padding: 20px;
}

.demo-header {
  margin-bottom: 20px;
}

.card-header {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 20px;
}

.card-header h1 {
  margin-bottom: 10px;
  color: #409EFF;
}

.demo-buttons {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-bottom: 20px;
}

.confirm-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
}

.custom-modal-content {
  padding: 20px;
}

.custom-footer {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}

.uploaded-files {
  margin-top: 20px;
  padding: 15px;
  background-color: #f5f7fa;
  border-radius: 4px;
}

.editor-preview {
  margin-top: 20px;
  padding: 15px;
  background-color: #f5f7fa;
  border-radius: 4px;
}

.shortcode-preview {
  margin-top: 20px;
  padding: 15px;
  background-color: #f5f7fa;
  border-radius: 4px;
}

.shortcode-preview pre {
  background-color: #fff;
  padding: 10px;
  border-radius: 4px;
  border: 1px solid #dcdfe6;
  font-family: monospace;
  white-space: pre-wrap;
  word-break: break-all;
}

.command-section {
  margin-bottom: 20px;
}

.command-item {
  margin-bottom: 10px;
  padding: 10px;
  background-color: #f5f7fa;
  border-radius: 4px;
}

.command-item code {
  display: block;
  padding: 10px;
  background-color: #fff;
  border: 1px solid #dcdfe6;
  border-radius: 4px;
  font-family: monospace;
  margin-bottom: 5px;
}

.command-item p {
  margin: 5px 0 0;
  color: #606266;
}
</style>
