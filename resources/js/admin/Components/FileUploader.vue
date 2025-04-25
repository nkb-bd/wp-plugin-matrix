<template>
  <div class="file-uploader">
    <el-upload
      :action="action"
      :headers="headers"
      :multiple="multiple"
      :limit="limit"
      :file-list="fileList"
      :list-type="listType"
      :auto-upload="autoUpload"
      :show-file-list="showFileList"
      :drag="drag"
      :accept="accept"
      :on-preview="handlePreview"
      :on-remove="handleRemove"
      :on-success="handleSuccess"
      :on-error="handleError"
      :on-progress="handleProgress"
      :on-exceed="handleExceed"
      :on-change="handleChange"
      :before-upload="beforeUpload"
      :before-remove="beforeRemove"
      :http-request="customUpload ? handleCustomUpload : undefined"
      :disabled="disabled"
      :data="uploadData"
    >
      <template v-if="drag">
        <i class="el-icon-upload"></i>
        <div class="el-upload__text">
          <slot name="drag-text">
            Drop file here or <em>click to upload</em>
          </slot>
        </div>
      </template>
      
      <el-button v-else size="small" type="primary">
        <slot name="button-text">Click to upload</slot>
      </el-button>
      
      <template #tip>
        <div class="el-upload__tip">
          <slot name="tip">
            <span v-if="accept">Accepted file types: {{ formatAccept(accept) }}</span>
            <span v-if="limit"> (Max {{ limit }} files)</span>
          </slot>
        </div>
      </template>
    </el-upload>
    
    <!-- Preview Dialog -->
    <el-dialog
      v-if="previewVisible"
      :visible.sync="previewVisible"
      :append-to-body="true"
      title="Preview"
      width="50%"
    >
      <div class="preview-container">
        <img v-if="isImage(previewFile)" :src="previewFile.url" class="preview-image" />
        <div v-else class="preview-file">
          <i class="el-icon-document"></i>
          <p>{{ previewFile.name }}</p>
          <el-button type="primary" size="small" @click="downloadFile(previewFile)">
            Download
          </el-button>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
export default {
  name: 'FileUploader',
  
  props: {
    action: {
      type: String,
      default: ''
    },
    headers: {
      type: Object,
      default: () => ({})
    },
    multiple: {
      type: Boolean,
      default: false
    },
    limit: {
      type: Number,
      default: 5
    },
    value: {
      type: Array,
      default: () => []
    },
    listType: {
      type: String,
      default: 'text',
      validator: value => ['text', 'picture', 'picture-card'].includes(value)
    },
    autoUpload: {
      type: Boolean,
      default: true
    },
    showFileList: {
      type: Boolean,
      default: true
    },
    drag: {
      type: Boolean,
      default: false
    },
    accept: {
      type: String,
      default: ''
    },
    disabled: {
      type: Boolean,
      default: false
    },
    customUpload: {
      type: Boolean,
      default: false
    },
    uploadData: {
      type: Object,
      default: () => ({})
    },
    maxSize: {
      type: Number,
      default: 10 // 10MB
    }
  },
  
  data() {
    return {
      fileList: [],
      previewVisible: false,
      previewFile: null,
      uploadingFiles: 0
    };
  },
  
  watch: {
    value: {
      immediate: true,
      handler(val) {
        if (val && Array.isArray(val)) {
          this.fileList = val.map(file => {
            if (typeof file === 'string') {
              return {
                name: this.getFileNameFromUrl(file),
                url: file
              };
            }
            return file;
          });
        }
      }
    }
  },
  
  methods: {
    getFileNameFromUrl(url) {
      return url.split('/').pop();
    },
    
    formatAccept(accept) {
      return accept.split(',').map(type => type.trim()).join(', ');
    },
    
    isImage(file) {
      return file && file.url && /\.(jpeg|jpg|png|gif|webp)$/i.test(file.url);
    },
    
    downloadFile(file) {
      const link = document.createElement('a');
      link.href = file.url;
      link.download = file.name;
      link.target = '_blank';
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    },
    
    handlePreview(file) {
      this.previewFile = file;
      this.previewVisible = true;
    },
    
    handleRemove(file, fileList) {
      this.fileList = fileList;
      this.$emit('input', fileList);
      this.$emit('remove', file, fileList);
    },
    
    handleSuccess(response, file, fileList) {
      this.uploadingFiles--;
      this.fileList = fileList;
      this.$emit('input', fileList);
      this.$emit('success', response, file, fileList);
      
      if (this.uploadingFiles === 0) {
        this.$emit('complete', fileList);
      }
    },
    
    handleError(error, file, fileList) {
      this.uploadingFiles--;
      this.$emit('error', error, file, fileList);
    },
    
    handleProgress(event, file, fileList) {
      this.$emit('progress', event, file, fileList);
    },
    
    handleExceed(files, fileList) {
      this.$message.warning(`You can only upload a maximum of ${this.limit} files.`);
      this.$emit('exceed', files, fileList);
    },
    
    handleChange(file, fileList) {
      this.$emit('change', file, fileList);
    },
    
    beforeUpload(file) {
      // Check file size
      const isLessThanMaxSize = file.size / 1024 / 1024 < this.maxSize;
      
      if (!isLessThanMaxSize) {
        this.$message.error(`File size cannot exceed ${this.maxSize}MB!`);
        return false;
      }
      
      this.uploadingFiles++;
      this.$emit('before-upload', file);
      return true;
    },
    
    beforeRemove(file, fileList) {
      return this.$emit('before-remove', file, fileList);
    },
    
    handleCustomUpload({ file, onProgress, onSuccess, onError }) {
      this.$emit('custom-upload', {
        file,
        onProgress: event => {
          onProgress(event);
        },
        onSuccess: response => {
          onSuccess(response);
        },
        onError: error => {
          onError(error);
        }
      });
    },
    
    clearFiles() {
      this.$refs.upload.clearFiles();
      this.fileList = [];
      this.$emit('input', []);
    },
    
    submit() {
      this.$refs.upload.submit();
    }
  }
};
</script>

<style scoped>
.file-uploader {
  width: 100%;
}

.preview-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 200px;
}

.preview-image {
  max-width: 100%;
  max-height: 500px;
}

.preview-file {
  text-align: center;
}

.preview-file i {
  font-size: 48px;
  color: #909399;
}

.preview-file p {
  margin: 10px 0;
}
</style>
