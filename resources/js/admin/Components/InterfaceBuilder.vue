<template>
  <div class="interface-builder">
    <div class="builder-container">
      <div class="sidebar">
        <h3>Components</h3>
        <div class="components-list">
          <div
            v-for="component in availableComponents"
            :key="component.type"
            class="component-item"
            draggable="true"
            @dragstart="dragStart($event, component)"
          >
            <i :class="component.icon"></i>
            <span>{{ component.label }}</span>
          </div>
        </div>
      </div>

      <div
        class="canvas"
        @dragover.prevent
        @drop="drop($event)"
      >
        <div v-if="elements.length === 0" class="empty-canvas">
          <p>Drag and drop components here</p>
        </div>

        <div v-else class="canvas-content">
          <div
            v-for="(element, index) in elements"
            :key="index"
            class="element-wrapper"
            :class="{ 'is-selected': selectedElement === index }"
            @click.stop="selectElement(index)"
          >
            <div class="element-actions">
              <button @click.stop="moveElement(index, -1)" :disabled="index === 0">
                <i class="el-icon-arrow-up"></i>
              </button>
              <button @click.stop="moveElement(index, 1)" :disabled="index === elements.length - 1">
                <i class="el-icon-arrow-down"></i>
              </button>
              <button @click.stop="duplicateElement(index)">
                <i class="el-icon-copy-document"></i>
              </button>
              <button @click.stop="removeElement(index)">
                <i class="el-icon-delete"></i>
              </button>
            </div>

            <component
              :is="getComponentType(element.type)"
              v-bind="element.props"
              :style="element.style"
              class="builder-element"
            />
          </div>
        </div>
      </div>

      <div class="properties-panel" v-if="selectedElement !== null">
        <h3>Properties</h3>
        <el-form label-position="top">
          <el-form-item label="Element Type">
            <el-input :value="elements[selectedElement].type" disabled></el-input>
          </el-form-item>

          <template v-for="(prop, key) in getComponentProps(elements[selectedElement].type)" :key="key">
            <el-form-item :label="prop.label">
              <el-input
                v-if="prop.type === 'text'"
                v-model="elements[selectedElement].props[key]"
              ></el-input>

              <el-input-number
                v-else-if="prop.type === 'number'"
                v-model="elements[selectedElement].props[key]"
                :min="prop.min"
                :max="prop.max"
                :step="prop.step || 1"
              ></el-input-number>

              <el-select
                v-else-if="prop.type === 'select'"
                v-model="elements[selectedElement].props[key]"
              >
                <el-option
                  v-for="option in prop.options"
                  :key="option.value"
                  :label="option.label"
                  :value="option.value"
                ></el-option>
              </el-select>

              <el-switch
                v-else-if="prop.type === 'boolean'"
                v-model="elements[selectedElement].props[key]"
              ></el-switch>

              <el-color-picker
                v-else-if="prop.type === 'color'"
                v-model="elements[selectedElement].props[key]"
              ></el-color-picker>
            </el-form-item>
          </template>

          <el-form-item label="Styles">
            <el-collapse>
              <el-collapse-item title="Dimensions">
                <el-form-item label="Width">
                  <el-input v-model="elements[selectedElement].style.width"></el-input>
                </el-form-item>
                <el-form-item label="Height">
                  <el-input v-model="elements[selectedElement].style.height"></el-input>
                </el-form-item>
              </el-collapse-item>

              <el-collapse-item title="Spacing">
                <el-form-item label="Margin">
                  <el-input v-model="elements[selectedElement].style.margin"></el-input>
                </el-form-item>
                <el-form-item label="Padding">
                  <el-input v-model="elements[selectedElement].style.padding"></el-input>
                </el-form-item>
              </el-collapse-item>

              <el-collapse-item title="Typography">
                <el-form-item label="Font Size">
                  <el-input v-model="elements[selectedElement].style.fontSize"></el-input>
                </el-form-item>
                <el-form-item label="Font Weight">
                  <el-select v-model="elements[selectedElement].style.fontWeight">
                    <el-option label="Normal" value="normal"></el-option>
                    <el-option label="Bold" value="bold"></el-option>
                    <el-option label="Light" value="300"></el-option>
                    <el-option label="Medium" value="500"></el-option>
                  </el-select>
                </el-form-item>
                <el-form-item label="Text Align">
                  <el-select v-model="elements[selectedElement].style.textAlign">
                    <el-option label="Left" value="left"></el-option>
                    <el-option label="Center" value="center"></el-option>
                    <el-option label="Right" value="right"></el-option>
                  </el-select>
                </el-form-item>
              </el-collapse-item>

              <el-collapse-item title="Colors">
                <el-form-item label="Text Color">
                  <el-color-picker v-model="elements[selectedElement].style.color"></el-color-picker>
                </el-form-item>
                <el-form-item label="Background Color">
                  <el-color-picker v-model="elements[selectedElement].style.backgroundColor"></el-color-picker>
                </el-form-item>
              </el-collapse-item>
            </el-collapse>
          </el-form-item>
        </el-form>
      </div>
    </div>

    <div class="builder-actions">
      <el-button type="primary" @click="saveInterface">Save</el-button>
      <el-button @click="previewInterface">Preview</el-button>
      <el-button @click="clearInterface">Clear</el-button>
      <el-button @click="exportInterface">Export</el-button>
      <el-button @click="importInterface">Import</el-button>
    </div>

    <!-- Preview Modal -->
    <el-dialog
      title="Preview"
      :visible.sync="previewVisible"
      width="80%"
      :before-close="closePreview"
    >
      <div class="preview-container">
        <div
          v-for="(element, index) in elements"
          :key="index"
          class="preview-element"
        >
          <component
            :is="getComponentType(element.type)"
            v-bind="element.props"
            :style="element.style"
          />
        </div>
      </div>
    </el-dialog>

    <!-- Import Modal -->
    <el-dialog
      title="Import Interface"
      :visible.sync="importVisible"
      width="50%"
    >
      <el-form>
        <el-form-item label="JSON Configuration">
          <el-input
            type="textarea"
            v-model="importJson"
            :rows="10"
            placeholder="Paste JSON configuration here"
          ></el-input>
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="importVisible = false">Cancel</el-button>
        <el-button type="primary" @click="handleImport">Import</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
export default {
  name: 'InterfaceBuilder',

  props: {
    value: {
      type: Array,
      default: () => []
    }
  },

  data() {
    return {
      elements: [],
      selectedElement: null,
      previewVisible: false,
      importVisible: false,
      importJson: '',
      availableComponents: [
        {
          type: 'el-button',
          label: 'Button',
          icon: 'el-icon-thumb',
          defaultProps: {
            type: 'primary',
            size: 'medium',
            text: 'Button'
          }
        },
        {
          type: 'el-input',
          label: 'Input',
          icon: 'el-icon-edit',
          defaultProps: {
            placeholder: 'Enter text',
            type: 'text',
            clearable: true
          }
        },
        {
          type: 'el-select',
          label: 'Select',
          icon: 'el-icon-arrow-down',
          defaultProps: {
            placeholder: 'Select',
            options: [
              { value: 'option1', label: 'Option 1' },
              { value: 'option2', label: 'Option 2' }
            ]
          }
        },
        {
          type: 'el-date-picker',
          label: 'Date Picker',
          icon: 'el-icon-date',
          defaultProps: {
            placeholder: 'Select date',
            type: 'date'
          }
        },
        {
          type: 'el-switch',
          label: 'Switch',
          icon: 'el-icon-open',
          defaultProps: {
            activeText: 'On',
            inactiveText: 'Off'
          }
        },
        {
          type: 'el-slider',
          label: 'Slider',
          icon: 'el-icon-s-operation',
          defaultProps: {
            min: 0,
            max: 100,
            step: 1
          }
        },
        {
          type: 'div',
          label: 'Container',
          icon: 'el-icon-s-grid',
          defaultProps: {
            text: 'Container'
          }
        },
        {
          type: 'h1',
          label: 'Heading',
          icon: 'el-icon-s-fold',
          defaultProps: {
            text: 'Heading'
          }
        },
        {
          type: 'p',
          label: 'Paragraph',
          icon: 'el-icon-document',
          defaultProps: {
            text: 'This is a paragraph of text.'
          }
        },
        {
          type: 'img',
          label: 'Image',
          icon: 'el-icon-picture',
          defaultProps: {
            src: 'https://via.placeholder.com/300x200',
            alt: 'Image'
          }
        }
      ]
    };
  },

  watch: {
    value: {
      immediate: true,
      handler(val) {
        if (val && Array.isArray(val)) {
          this.elements = JSON.parse(JSON.stringify(val));
        }
      }
    },

    elements: {
      deep: true,
      handler(val) {
        this.$emit('input', JSON.parse(JSON.stringify(val)));
      }
    }
  },

  methods: {
    getComponentType(type) {
      return type;
    },

    getComponentProps(type) {
      const component = this.availableComponents.find(c => c.type === type);
      if (!component) return {};

      const props = {};

      Object.keys(component.defaultProps).forEach(key => {
        props[key] = {
          label: key.charAt(0).toUpperCase() + key.slice(1),
          type: this.getPropType(component.defaultProps[key])
        };

        if (props[key].type === 'select' && Array.isArray(component.defaultProps[key])) {
          props[key].options = component.defaultProps[key];
        }
      });

      return props;
    },

    getPropType(value) {
      if (typeof value === 'string') return 'text';
      if (typeof value === 'number') return 'number';
      if (typeof value === 'boolean') return 'boolean';
      if (Array.isArray(value)) return 'select';
      return 'text';
    },

    dragStart(event, component) {
      event.dataTransfer.setData('component', JSON.stringify(component));
    },

    drop(event) {
      const componentData = JSON.parse(event.dataTransfer.getData('component'));

      const newElement = {
        type: componentData.type,
        props: { ...componentData.defaultProps },
        style: {}
      };

      this.elements.push(newElement);
      this.selectedElement = this.elements.length - 1;
    },

    selectElement(index) {
      this.selectedElement = index;
    },

    removeElement(index) {
      this.elements.splice(index, 1);

      if (this.selectedElement === index) {
        this.selectedElement = null;
      } else if (this.selectedElement > index) {
        this.selectedElement--;
      }
    },

    duplicateElement(index) {
      const element = JSON.parse(JSON.stringify(this.elements[index]));
      this.elements.splice(index + 1, 0, element);
      this.selectedElement = index + 1;
    },

    moveElement(index, direction) {
      const newIndex = index + direction;

      if (newIndex < 0 || newIndex >= this.elements.length) {
        return;
      }

      const element = this.elements[index];
      this.elements.splice(index, 1);
      this.elements.splice(newIndex, 0, element);
      this.selectedElement = newIndex;
    },

    saveInterface() {
      this.$emit('save', this.elements);
    },

    previewInterface() {
      this.previewVisible = true;
    },

    closePreview() {
      this.previewVisible = false;
    },

    clearInterface() {
      this.$confirm('Are you sure you want to clear the interface? This cannot be undone.', 'Warning', {
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        type: 'warning'
      }).then(() => {
        this.elements = [];
        this.selectedElement = null;
      }).catch(() => {});
    },

    exportInterface() {
      const json = JSON.stringify(this.elements, null, 2);
      const blob = new Blob([json], { type: 'application/json' });
      const url = URL.createObjectURL(blob);

      const link = document.createElement('a');
      link.href = url;
      link.download = 'interface-config.json';
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    },

    importInterface() {
      this.importVisible = true;
    },

    handleImport() {
      try {
        const importedElements = JSON.parse(this.importJson);

        if (Array.isArray(importedElements)) {
          this.elements = importedElements;
          this.selectedElement = null;
          this.importVisible = false;
          this.importJson = '';
        } else {
          this.$message.error('Invalid JSON format. Expected an array.');
        }
      } catch (error) {
        this.$message.error('Invalid JSON format: ' + error.message);
      }
    }
  }
};
</script>

<style scoped>
.interface-builder {
  display: flex;
  flex-direction: column;
  height: 100%;
}

.builder-container {
  display: flex;
  flex: 1;
  border: 1px solid #dcdfe6;
  border-radius: 4px;
  overflow: hidden;
}

.sidebar {
  width: 200px;
  border-right: 1px solid #dcdfe6;
  background-color: #f5f7fa;
  padding: 15px;
  overflow-y: auto;
}

.components-list {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.component-item {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px;
  border: 1px solid #dcdfe6;
  border-radius: 4px;
  background-color: #fff;
  cursor: move;
  user-select: none;
}

.component-item:hover {
  background-color: #ecf5ff;
  border-color: #c6e2ff;
}

.canvas {
  flex: 1;
  padding: 20px;
  background-color: #fff;
  overflow-y: auto;
  min-height: 500px;
}

.empty-canvas {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
  border: 2px dashed #dcdfe6;
  border-radius: 4px;
  color: #909399;
}

.element-wrapper {
  position: relative;
  margin-bottom: 10px;
  border: 1px solid transparent;
  padding: 5px;
}

.element-wrapper:hover {
  border-color: #dcdfe6;
}

.element-wrapper.is-selected {
  border-color: #409eff;
}

.element-actions {
  display: none;
  position: absolute;
  top: -30px;
  right: 0;
  background-color: #fff;
  border: 1px solid #dcdfe6;
  border-radius: 4px;
  padding: 2px;
  z-index: 10;
}

.element-wrapper:hover .element-actions,
.element-wrapper.is-selected .element-actions {
  display: flex;
}

.element-actions button {
  background: none;
  border: none;
  cursor: pointer;
  padding: 4px;
  color: #606266;
}

.element-actions button:hover {
  color: #409eff;
}

.element-actions button:disabled {
  color: #c0c4cc;
  cursor: not-allowed;
}

.properties-panel {
  width: 300px;
  border-left: 1px solid #dcdfe6;
  background-color: #f5f7fa;
  padding: 15px;
  overflow-y: auto;
}

.builder-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 15px;
}

.preview-container {
  min-height: 300px;
  padding: 20px;
  border: 1px solid #dcdfe6;
  border-radius: 4px;
  background-color: #fff;
}

.preview-element {
  margin-bottom: 10px;
}
</style>
