<template>
    <form class="mx-auto border border-gray-300 rounded-lg p-3 mt-5" id="report-form" @submit.prevent="sendReport" enctype="multipart/form-data">
        <h4 class="text-center">Report</h4>
        <div class="mb-2">
          <label for="type" class="form-label">Type <code>*</code></label>
          <multiselect v-model="form.report_type" id="ajax" label="label" track-by="label" required placeholder="Choose type" :class="{ 'border border-danger': checkInputValidity('form', 'report_type', ['required']) || errors?.report_type }" :options="reportTypes" v-bind="multiselectProps" @open="getReportTypes">
              <template #noResult>
                  <span>Oops! No elements found. Consider changing the search query.</span>
              </template>
          </multiselect>
          <div v-if="errors?.report_type" class="text-danger">
                {{ errors?.report_type[0] }}
          </div>
          <div class="text-danger">
              <span v-if="v$.form.report_type.required.$invalid">
                Type is required.
              </span>
          </div>
        </div>
        <div class="mb-2" v-if="isAccountType">
            <label for="email" class="form-label" >Email <code>*</code></label>
            <Input type="email" id="email" autocomplete="email" :class="{ 'border border-danger': checkInputValidity('form', 'email', ['required', 'email'])  || errors?.email }" class="form-control py-2" placeholder="Enter email" v-model="form.email"/>
            <div v-if="errors?.email" class="text-danger">
                {{ errors?.email[0] }}
            </div>
            <div class="text-danger">
                <span v-if="v$.form.email.required.$invalid">
                  Email is required.
                </span>
                <span v-if="v$.form.email.$invalid" :class="{'d-block' : v$.form.email.required.$invalid}">
                  Email must be valid.
                </span>
            </div>
        </div>
        <div class="mb-2">
            <label for="title" class="form-label" >Title <code>*</code></label>
            <Input type="title" id="title" autocomplete="title" :class="{ 'border border-danger': checkInputValidity('form', 'title', ['required', 'maxLength']) || errors?.title }" class="form-control py-2" placeholder="Enter title" v-model="form.title"/>
            <div v-if="errors?.title" class="text-danger">
                {{ errors?.title[0] }}
            </div>
            <div class="text-danger">
                <span v-if="v$.form.title.required.$invalid">
                  Title is required.
                </span>
                <span v-if="v$.form.title.maxLength.$invalid" :class="{'d-block' : v$.form.title.required.$invalid}">
                    Title must only contain 100 characters.
                </span>
            </div>
        </div>
        <div class="mb-2">
          <label for="password" class="form-label">Description <code>*</code></label>
          <TextArea v-model="form.description"  :class="{ 'border border-danger': checkInputValidity('form', 'description', ['required', 'maxLength']) || errors?.description }" required></TextArea>
          <div v-if="errors?.description" class="text-danger">
                {{ errors?.description[0] }}
            </div>
            <div class="text-danger">
                <span v-if="v$.form.description.required.$invalid">
                  Description is required.
                </span>
                <span v-if="v$.form.description.maxLength.$invalid" :class="{'d-block' : v$.form.description.required.$invalid}">
                  Description must only contain 1000 characters.
                </span>
            </div>
        </div>
        <div class="mb-2">
          <label for="" class="me-2">Upload Proof:</label>
          <div class="d-flex align-items-center">
            <button type="button" v-if="images.length" class="btn btn-sm" @click="show" title="Preview Images"><i class="bi bi-zoom-in" style="font-size:25px"></i></button>
            <input type="file" id="report-attachments" @change="handleAttachment" multiple  accept=".png, .jpg, .jpeg" class="d-block"/>
            <viewer :images="images" @inited="inited" class="viewer" ref="viewer" :options="viewerOptions">
              <template #default="scope">
                <img v-for="src in scope.images" :src="src" :key="src" style="display:none !important">
                <!-- {{scope.options}} -->
              </template>
            </viewer>
          </div>
        </div>
        <div id="btn" class="d-flex justify-content-end mt-1">
          <button type="submit" class="btn btn-primary form-control" :disabled="isSending">{{  isSending ? 'Sending' : 'Send' }}</button>
        </div>
    </form>
  </template>
    
  <script>
    import Input from '@js/components/Form/Input.vue'
    import TextArea from '@js/components/Form/TextArea.vue'
    import apiClient from '@js/helpers/apiClient.js';
    import Multiselect from 'vue-multiselect'
    import { useVuelidate } from '@vuelidate/core'
    import { required, maxLength, email } from '@vuelidate/validators';
    import { checkValidity  } from '@js/helpers/Vuelidate.js';
    import { sweetAlertNotification } from '@js/helpers/sweetAlert.js';
    import { component as Viewer } from "v-viewer"
  
    export default {
      setup () {
        return { v$: useVuelidate({ $autoDirty : true, $lazy: true}) }
      },
      components:{
        Input,
        TextArea,
        Multiselect,
        Viewer
      },
      data() {
        return {
          form:{
            title: '',
            description: '',
            email: '',
            report_type:null
          },
          files:null,
          isSending:false,
          attachments:null,
          errors:[{
            title:false,
            description:false,
            email:false,
          }],
          images:[],
          reportTypes:[],
          multiselectProps: {
              openDirection: 'bottom',
              searchable: true,
              internalSearch:false,
          },
          viewerOptions: {
            // inline: true, // Display the viewer inline instead of a modal
            button: false, // Hide the fullscreen button
            navbar: 3, // Show a maximum of 3 buttons in the navbar
            tooltip: false, // Disable the tooltip
            movable: false, // Disable image movement
            zoomable: true, // Enable zooming
            rotatable: true, // Enable rotation
            scalable: true, // Enable scaling
            transition: true, // Enable transition effects
            fullscreen: true, // Enable fullscreen mode
            keyboard: true, // Enable keyboard shortcuts
          },
        }
      },

      computed: {
        isAccountType() {
          return this.form.report_type && this.form.report_type.account;
        },
        validationRules() {
          const rules = {
            title: { required, maxLength: maxLength(100) },
            description: { required, maxLength: maxLength(1000) },
            report_type: { required }
          };

          if (this.isAccountType) {
            rules.email = { required, email };
          }

          return rules;
        }
      },
      validations() {
        return {
          form: this.validationRules
        }
      },
      created(){
        this.getReportTypes();
      },
      methods: {
        inited (viewer) {
          this.$viewer = viewer
        },
        show () {
          this.$viewer.show()
        },
        checkInputValidity(parentProperty = null, dataProperty, validations = []) {
            return checkValidity(this.v$, parentProperty, dataProperty, validations);
        },

        handleAttachment(e){
          const files = e.target.files;
          this.images = [];
          this.files = files;
          Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = () => {
              this.images.push(reader.result) 
            }
            reader.readAsDataURL(file);
          });
        },


        resetForm(){
          document.getElementById('report-form').reset();
          this.form = {
            title: '',
            email: '',
            description: '',
            report_type:''
          }
          this.files = null;
          this.images = [];
          this.errors = [{
            title:false,
            description:false,
            email:false,
          }];
          this.v$.$reset();
        },
        
        structureData(){
          const formData = new FormData();
          if (this.files && this.files.length > 0) {
            for (let i = 0; i < this.files.length; i++) {
              formData.append('attachments[]', this.files[i]);
            }
          }
          if(this.form.report_type.account){
            formData.append('email',this.form.email)
          }
          formData.append('title',this.form.title)
          formData.append('description',this.form.description)
          formData.append('report_type',this.form.report_type.value)
          return formData;
        },

        async sendReport() {
          try {
            if (!(await this.v$.$validate())) return; // Return if validation fails
            this.isSending = true;

            const response = await apiClient.post('/api/reports',this.structureData());

            if(response.status == 200){
              this.isSending = false;
              sweetAlertNotification(response.data.message, "", "success")
              this.resetForm();
            }

            if (response.status == 422) {
                this.errors =  response.data.errors;
                this.isSending = false;
            }
          } catch (error) {
            if (error.response.status == 422) {
                this.errors =  error.response.data.errors;
            }
            this.isSending = false;
          }
        },

        async getReportTypes(){
          const response = await apiClient.get('/api/reports/types');
          if(response.status == 200) {
            this.reportTypes = response.data.options;
          }
        }
      }
    }
    </script>
  
  <style scoped>
  
  </style>