import { ElNotification, ElMessage, ElMessageBox } from 'element-plus';

/**
 * Simple notification utility
 */
const notification = {
  // Default notification position
  defaultPosition: 'bottom-right', // Options: 'top-right', 'top-left', 'bottom-right', 'bottom-left'

  // Notification methods
  success(message, title = 'Success', options = {}) {
    return ElNotification({
      title,
      message,
      type: 'success',
      position: options.position || this.defaultPosition,
      duration: options.duration || 4500,
      ...options
    });
  },

  error(message, title = 'Error', options = {}) {
    return ElNotification({
      title,
      message,
      type: 'error',
      position: options.position || this.defaultPosition,
      duration: options.duration || 4500,
      ...options
    });
  },

  warning(message, title = 'Warning', options = {}) {
    return ElNotification({
      title,
      message,
      type: 'warning',
      position: options.position || this.defaultPosition,
      duration: options.duration || 4500,
      ...options
    });
  },

  info(message, title = 'Info', options = {}) {
    return ElNotification({
      title,
      message,
      type: 'info',
      position: options.position || this.defaultPosition,
      duration: options.duration || 4500,
      ...options
    });
  },

  // Toast message
  toast(message, type = 'info') {
    return ElMessage({
      message,
      type
    });
  },

  // Dialogs
  confirm(message, title = 'Confirm') {
    return ElMessageBox.confirm(message, title, {
      confirmButtonText: 'Confirm',
      cancelButtonText: 'Cancel',
      type: 'warning'
    });
  },

  alert(message, title = 'Alert') {
    return ElMessageBox.alert(message, title, {
      confirmButtonText: 'OK'
    });
  }
};

export default notification;
