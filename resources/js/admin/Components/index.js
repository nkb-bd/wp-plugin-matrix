// UI Components
import DataTable from './DataTable.vue';
import Modal from './Modal.vue';
import FileUploader from './FileUploader.vue';
import RichTextEditor from './RichTextEditor.vue';
import InterfaceBuilder from './InterfaceBuilder.vue';

// Export all components
export {
  DataTable,
  Modal,
  FileUploader,
  RichTextEditor,
  InterfaceBuilder
};

// Export default as a plugin
export default {
  install(app) {
    // Register all components globally
    app.component('DataTable', DataTable);
    app.component('Modal', Modal);
    app.component('FileUploader', FileUploader);
    app.component('RichTextEditor', RichTextEditor);
    app.component('InterfaceBuilder', InterfaceBuilder);
  }
};
