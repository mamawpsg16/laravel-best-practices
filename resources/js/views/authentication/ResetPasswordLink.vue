<template>
  <Modal id="reset-password-modal" title="Reset Password">
    <template #content>
      <form class="mx-auto rounded-lg p-3" @submit.prevent="resendVerificationLink">
          <div class="mb-2">
              <label for="email" class="form-label">Email</label>
              <Input type="email" id="reset-password-email" autocomplete="email" :class="{ 'border border-danger': checkInputValidity(null, 'email', ['required', 'email']) }" class="form-control py-2" placeholder="Enter email" v-model="email" required/>
              <div v-if="errors?.email" class="text-danger">
                  {{ errors?.email[0] }}
              </div>
              <span v-if="hasError" class="text-danger mb-1">{{ hasError }}</span>
              <div class="text-danger">
                  <span v-if="v$.email.required.$invalid">
                      Email is required.
                  </span>
                  <span v-if="v$.email.email.$invalid">
                      Email must be valid.
                  </span>
              </div>
          </div>
          <div id="btn" class="d-flex justify-content-end mt-2">
            <button class="btn btn-primary form-control" :disabled="isResetLinkSent">{{  isResetLinkSent ? 'Sending...' : 'Send password reset link' }}</button>
          </div>
      </form>
    </template>
  </Modal>
</template>
  
  <script>
  import axios from 'axios';
  import { useAuthStore } from '@js/stores/authStore';
  import { useVuelidate } from '@vuelidate/core'
  import { required, email } from '@vuelidate/validators';
  import { checkValidity  } from '@js/helpers/Vuelidate.js';
  import Swal from 'sweetalert2/dist/sweetalert2.js'
  import Input from '@js/components/Form/Input.vue'
  import Modal from '@js/components/Modal.vue';

  export default {
    setup () {
      return { v$: useVuelidate({ $autoDirty : true, $lazy: true}) }
    },
    data() {
      return {
        authStore:useAuthStore(),
        isResetLinkSent:false,
        email:null,
        hasError:null,
        errors:[{
          email:false,
        }],
      };
    },
    validations () {
      return {
        email: { required, email },
      }
    },
    components:{
      Input,
      Modal
    },
    created() {
    },
    methods: {
      checkInputValidity(parentProperty = null, dataProperty, validations = []) {
          return checkValidity(this.v$, parentProperty, dataProperty, validations);
      },

      resetForm(){
        this.email = null;
        this.v$.$reset();
      },
      async resendVerificationLink() {
        try {
          if(this.v$.email.$errors.length > 0) return;
          this.isResetLinkSent = true;
          const response = await this.authStore.resetPassword(this.email);
          if(response.status == 200){
            this.isResetLinkSent = false;
            Swal.fire({
              title: response.data.message,
              icon: "success",
              timer:2000,
              showConfirmButton: false,
              toast:true,
              position: "bottom-end",
              timerProgressBar: true,
             });

             this.resetForm();
             this.hasError = null;
          }
        } catch (error) {
          this.isResetLinkSent = false;
          if(error.response.status == 422){
            this.hasError = error?.response?.data.message
          }
        }
      }
    },
  };
  </script>