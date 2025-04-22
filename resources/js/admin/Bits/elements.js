// Element Plus components
import {
    ElButton,
    ElMessage,
    ElInput,
    ElForm,
    ElFormItem,
    ElSelect,
    ElOption,
    ElTable,
    ElTableColumn,
    ElPagination,
    ElDialog,
    ElCard,
    ElTabs,
    ElTabPane,
    ElMenu,
    ElMenuItem,
    ElMenuItemGroup,
    ElSubMenu,
    ElDropdown,
    ElDropdownMenu,
    ElDropdownItem,
    ElSwitch,
    ElCheckbox,
    ElRadio,
    ElRadioGroup,
    ElDatePicker,
    ElLoading,
    ElTooltip,
    ElAlert
} from 'element-plus';

// Note: We don't import the default Element Plus CSS since we're using our custom theme
// The custom theme is imported in resources/scss/style.scss

// Create an array of components to register
const components = [
    ElButton,
    ElInput,
    ElForm,
    ElFormItem,
    ElSelect,
    ElOption,
    ElTable,
    ElTableColumn,
    ElPagination,
    ElDialog,
    ElCard,
    ElTabs,
    ElTabPane,
    ElMenu,
    ElMenuItem,
    ElMenuItemGroup,
    ElSubMenu,
    ElDropdown,
    ElDropdownMenu,
    ElDropdownItem,
    ElSwitch,
    ElCheckbox,
    ElRadio,
    ElRadioGroup,
    ElDatePicker,
    ElTooltip,
    ElAlert
];

export default {
    install(app) {
        // Register all components
        components.forEach(component => {
            app.component(component.name, component);
        });

        // Add global properties
        app.config.globalProperties.$message = ElMessage;

        // Add directives
        app.use(ElLoading);
    }
};
