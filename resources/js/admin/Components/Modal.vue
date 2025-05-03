<template>
  <el-dialog
    v-model="dialogVisible"
    :title="title"
    :width="width"
    :fullscreen="fullscreen"
    :top="top"
    :modal="modal"
    :append-to-body="appendToBody"
    :close-on-click-modal="closeOnClickModal"
    :close-on-press-escape="closeOnPressEscape"
    :show-close="showClose"
    :before-close="handleBeforeClose"
    :center="center"
    :destroy-on-close="destroyOnClose"
    :custom-class="customClass"
  >
    <div class="modal-body">
      <slot></slot>
    </div>

    <template #footer>
      <slot name="footer">
        <div class="dialog-footer">
          <el-button @click="handleCancel">{{ cancelText }}</el-button>
          <el-button type="primary" @click="handleConfirm" :loading="confirmLoading">
            {{ confirmText }}
          </el-button>
        </div>
      </slot>
    </template>
  </el-dialog>
</template>

<script>
export default {
  name: 'Modal',

  props: {
    visible: {
      type: Boolean,
      default: false
    },
    title: {
      type: String,
      default: 'Dialog'
    },
    width: {
      type: String,
      default: '50%'
    },
    fullscreen: {
      type: Boolean,
      default: false
    },
    top: {
      type: String,
      default: '15vh'
    },
    modal: {
      type: Boolean,
      default: true
    },
    appendToBody: {
      type: Boolean,
      default: false
    },
    closeOnClickModal: {
      type: Boolean,
      default: true
    },
    closeOnPressEscape: {
      type: Boolean,
      default: true
    },
    showClose: {
      type: Boolean,
      default: true
    },
    center: {
      type: Boolean,
      default: false
    },
    destroyOnClose: {
      type: Boolean,
      default: false
    },
    customClass: {
      type: String,
      default: ''
    },
    confirmText: {
      type: String,
      default: 'Confirm'
    },
    cancelText: {
      type: String,
      default: 'Cancel'
    },
    confirmLoading: {
      type: Boolean,
      default: false
    }
  },

  data() {
    return {
      dialogVisible: this.visible
    };
  },

  watch: {
    visible(val) {
      this.dialogVisible = val;
    },

    dialogVisible(val) {
      this.$emit('update:visible', val);
      if (!val) {
        this.$emit('close');
      }
    }
  },

  created() {
    // Initialize dialogVisible with the visible prop
    this.dialogVisible = this.visible;
  },

  methods: {
    handleBeforeClose(done) {
      this.$emit('before-close', done);
    },

    handleConfirm() {
      this.$emit('confirm');
    },

    handleCancel() {
      this.$emit('cancel');
      this.dialogVisible = false;
    },

    open() {
      this.dialogVisible = true;
    },

    close() {
      this.dialogVisible = false;
    }
  }
};
</script>

<style scoped>
.modal-body {
  max-height: 60vh;
  overflow-y: auto;
}
</style>
