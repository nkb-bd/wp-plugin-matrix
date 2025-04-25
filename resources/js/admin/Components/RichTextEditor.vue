<template>
  <div class="rich-text-editor" :class="{ 'is-disabled': disabled }">
    <div ref="editor" class="editor-container"></div>

    <div v-if="showWordCount" class="word-count">
      {{ wordCount }} words
    </div>
  </div>
</template>

<script>
export default {
  name: 'RichTextEditor',

  props: {
    value: {
      type: String,
      default: ''
    },
    placeholder: {
      type: String,
      default: 'Start writing...'
    },
    disabled: {
      type: Boolean,
      default: false
    },
    height: {
      type: [String, Number],
      default: '300px'
    },
    toolbar: {
      type: Array,
      default: () => [
        ['bold', 'italic', 'underline', 'strike'],
        ['blockquote', 'code-block'],
        [{ 'header': 1 }, { 'header': 2 }],
        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
        [{ 'script': 'sub' }, { 'script': 'super' }],
        [{ 'indent': '-1' }, { 'indent': '+1' }],
        [{ 'direction': 'rtl' }],
        [{ 'size': ['small', false, 'large', 'huge'] }],
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
        [{ 'color': [] }, { 'background': [] }],
        [{ 'font': [] }],
        [{ 'align': [] }],
        ['clean'],
        ['link', 'image', 'video']
      ]
    },
    showWordCount: {
      type: Boolean,
      default: false
    },
    readOnly: {
      type: Boolean,
      default: false
    },
    formats: {
      type: Array,
      default: null
    }
  },

  data() {
    return {
      editor: null,
      content: '',
      wordCount: 0,
      isInitialized: false
    };
  },

  watch: {
    value(val) {
      if (this.editor && val !== this.content) {
        this.content = val;
        this.editor.root.innerHTML = val || '';
      }
    },

    disabled(val) {
      if (this.editor) {
        this.editor.enable(!val);
      }
    }
  },

  mounted() {
    this.initQuill();
  },

  beforeDestroy() {
    if (this.editor) {
      this.editor = null;
    }
  },

  methods: {
    async initQuill() {
      try {
        // Dynamically import Quill
        const Quill = await import('quill').then(module => module.default || module);

      // Configure Quill
      const options = {
        modules: {
          toolbar: this.toolbar
        },
        placeholder: this.placeholder,
        readOnly: this.readOnly || this.disabled,
        theme: 'snow'
      };

      // Limit formats if specified
      if (this.formats) {
        options.formats = this.formats;
      }

      // Create Quill instance
      this.editor = new Quill(this.$refs.editor, options);

      // Set initial content
      if (this.value) {
        this.editor.root.innerHTML = this.value;
        this.content = this.value;
      }

      // Set editor height
      this.$refs.editor.style.height = typeof this.height === 'number' ? `${this.height}px` : this.height;

      // Add event listeners
      this.editor.on('text-change', this.handleTextChange);

      this.isInitialized = true;
      } catch (error) {
        console.error('Error initializing Quill editor:', error);
        // Fallback to a simple textarea if Quill fails to load
        const textarea = document.createElement('textarea');
        textarea.value = this.value || '';
        textarea.placeholder = this.placeholder;
        textarea.style.width = '100%';
        textarea.style.height = typeof this.height === 'number' ? `${this.height}px` : this.height;
        textarea.disabled = this.disabled || this.readOnly;
        textarea.addEventListener('input', (e) => {
          this.content = e.target.value;
          this.$emit('input', e.target.value);
        });

        // Clear and append textarea
        this.$refs.editor.innerHTML = '';
        this.$refs.editor.appendChild(textarea);
      }
    },

    handleTextChange() {
      const html = this.editor.root.innerHTML;

      // Skip if content hasn't changed
      if (html === this.content) return;

      this.content = html;
      this.$emit('input', html === '<p><br></p>' ? '' : html);

      // Update word count
      if (this.showWordCount) {
        const text = this.editor.getText();
        this.wordCount = text.trim() === '' ? 0 : text.trim().split(/\s+/).length;
      }

      this.$emit('change', html);
    },

    focus() {
      if (this.editor) {
        this.editor.focus();
      }
    },

    blur() {
      if (this.editor) {
        this.editor.blur();
      }
    },

    getEditor() {
      return this.editor;
    },

    getHTML() {
      return this.editor ? this.editor.root.innerHTML : '';
    },

    getText() {
      return this.editor ? this.editor.getText() : '';
    },

    setHTML(html) {
      if (this.editor) {
        this.editor.root.innerHTML = html;
        this.handleTextChange();
      }
    },

    insertText(text) {
      if (this.editor) {
        const selection = this.editor.getSelection();
        if (selection) {
          this.editor.insertText(selection.index, text);
        } else {
          this.editor.insertText(0, text);
        }
      }
    },

    insertImage(url, alt = '') {
      if (this.editor) {
        const selection = this.editor.getSelection();
        if (selection) {
          this.editor.insertEmbed(selection.index, 'image', { src: url, alt });
        } else {
          this.editor.insertEmbed(0, 'image', { src: url, alt });
        }
      }
    }
  }
};
</script>

<style>
/* Import Quill styles in your main.js or App.vue */
/* @import 'quill/dist/quill.snow.css'; */

.rich-text-editor {
  border: 1px solid #dcdfe6;
  border-radius: 4px;
}

.rich-text-editor.is-disabled {
  background-color: #f5f7fa;
  cursor: not-allowed;
}

.editor-container {
  overflow-y: auto;
}

.word-count {
  padding: 5px 10px;
  text-align: right;
  color: #909399;
  font-size: 12px;
  border-top: 1px solid #dcdfe6;
  background-color: #f5f7fa;
}
</style>
