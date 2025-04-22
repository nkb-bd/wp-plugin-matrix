<template>
  <div class="content-area">
    <h1 class="page-title">Contact</h1>
    
    <div class="section">
      <h2 class="section-title">Contact Form</h2>
      
      <el-card>
        <div class="p-4">
          <el-form :model="form" :rules="rules" ref="contactForm" label-position="top">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <el-form-item label="Name" prop="name">
                <el-input v-model="form.name" placeholder="Your name"></el-input>
              </el-form-item>
              
              <el-form-item label="Email" prop="email">
                <el-input v-model="form.email" placeholder="Your email"></el-input>
              </el-form-item>
            </div>
            
            <el-form-item label="Subject" prop="subject">
              <el-input v-model="form.subject" placeholder="Subject"></el-input>
            </el-form-item>
            
            <el-form-item label="Message" prop="message">
              <el-input type="textarea" v-model="form.message" rows="5" placeholder="Your message"></el-input>
            </el-form-item>
            
            <el-form-item>
              <el-button type="primary" @click="submitForm">Send Message</el-button>
              <el-button @click="resetForm">Reset</el-button>
            </el-form-item>
          </el-form>
        </div>
      </el-card>
    </div>
  </div>
</template>

<script>
export default {
  name: "Contact",
  data() {
    return {
      form: {
        name: '',
        email: '',
        subject: '',
        message: ''
      },
      rules: {
        name: [
          { required: true, message: 'Please enter your name', trigger: 'blur' },
          { min: 2, message: 'Name must be at least 2 characters', trigger: 'blur' }
        ],
        email: [
          { required: true, message: 'Please enter your email', trigger: 'blur' },
          { type: 'email', message: 'Please enter a valid email address', trigger: 'blur' }
        ],
        subject: [
          { required: true, message: 'Please enter a subject', trigger: 'blur' }
        ],
        message: [
          { required: true, message: 'Please enter your message', trigger: 'blur' },
          { min: 10, message: 'Message must be at least 10 characters', trigger: 'blur' }
        ]
      }
    };
  },
  methods: {
    submitForm() {
      this.$refs.contactForm.validate((valid) => {
        if (valid) {
          this.$message.success('Form submitted successfully!');
          // In a real application, you would send the form data to the server
          console.log('Form data:', this.form);
        } else {
          this.$message.error('Please correct the errors in the form');
          return false;
        }
      });
    },
    resetForm() {
      this.$refs.contactForm.resetFields();
    }
  }
};
</script>
