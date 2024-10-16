// src/element-plus.js
import { ElButton, ElMessage } from 'element-plus';
import 'element-plus/dist/index.css';

export default {
    install(app) {
        // Register the components
        app.component(ElButton.name, ElButton);

        // Add ElMessage to global properties
        app.config.globalProperties.$message = ElMessage;
    }
};
