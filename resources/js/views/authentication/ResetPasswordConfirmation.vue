<template>
    <form class="form-width mx-auto border border-gray-300 rounded-lg p-4 mt-5" @submit.prevent="reset">
        <input type="text" style="display:none;" id="username" name="username" autocomplete="username">
        <h4 class="text-center">Reset Password</h4>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <Input type="password" id="password" class="form-control" autocomplete="current-password" placeholder="Enter password" v-model="password" :class="{ 'border border-danger': checkInputValidity(null, 'password', ['required']) }" required/>
            <div v-if="errors?.password" class="text-danger">
                {{ errors?.password[0] }}
            </div>
            <div class="text-danger">
                <span v-if="v$.password.required.$invalid">
                    Password is required.
                </span>
                <span v-if="v$.password.minLength.$invalid" :class="{'d-block': v$.password.required}">
                    Password must be at least 8 characters
                </span>
            </div>
        </div>
        <div class="mb-3">
            <label for="confirm-password" class="form-label">Password Confirmation</label>
            <Input type="password" id="confirm-password" class="form-control" autocomplete="new-password-confirm" placeholder="Enter password confirmation" v-model="password_confirmation" :class="{ 'border border-danger': checkInputValidity(null, 'password_confirmation', ['required', 'sameAsPassword']) }" required/>
            <div v-if="errors?.password_confirmation" class="text-danger">
                {{ errors?.password_confirmation[0] }}
            </div>
            <div class="text-danger">
                <span v-if="v$.password_confirmation.required.$invalid">
                    Password Confirmation is required.
                </span>
                <span v-if="v$.password_confirmation.sameAsPassword.$invalid" :class="{'d-block': v$.password_confirmation.required}">
                    Password Confirmation doesnt match.
                </span>
            </div>
        </div>
        <span v-if="isCredentialInvalid" class="text-danger mb-2">{{ isCredentialInvalid }}</span>
        <div id="btn" class="d-flex justify-content-end mt-2">
            <button type="submit" class="btn btn-primary form-control" :disabled="isProcessing">{{ isProcessing ? 'Processing' : 'Reset' }}</button>
        </div>
  </form>

</template>
  
  <script>
  import Input from '@js/components/Form/Input.vue'
  import { useAuthStore } from '../../stores/authStore.js';
  import { useVuelidate } from '@vuelidate/core'
  import { required, email, sameAs, minLength } from '@vuelidate/validators'
  import { checkValidity  } from '@js/helpers/Vuelidate.js';
  import Swal from 'sweetalert2/dist/sweetalert2.js'

  export default {
    setup () {
      return { v$: useVuelidate({ $autoDirty : true, $lazy: true}) }
    },
    data() {
      return {
        name: '',
        email: '',
        password: '',
        password_confirmation:'',
        isCredentialInvalid:null,
        authStore:useAuthStore(),
        isProcessing:false,
        errors:[{
            password:false,
            password_confirmation:false,
        }],
      }
    },
    validations () {
      return {
        password: { required,  minLength:minLength(8) },
        password_confirmation: { required, sameAsPassword: sameAs(this.password) }, // Matches this.password
      }
    },
    components:{
      Input
    },  

    methods: {
      checkInputValidity(parentProperty = null, dataProperty, validations = []) {
          return checkValidity(this.v$, parentProperty, dataProperty, validations);
      },

      resetForm(){
        this.password = null;
        this.password_confirmation = null; 
        this.errors = [{
            password:false,
            password_confirmation:false,
        }];
        this.v$.$reset()
      },

      async reset() {
        try {
            if(!await this.v$.$validate()) return;         
            this.isProcessing = true;
            const response = await this.authStore.resetPasswordConfirmation(this.$route.query.email, this.password, this.password_confirmation, this.$route.params.token);
            console.log(response,'response');
            if(response.status == 200){
                // localStorage.setItem('authenticated', true);
                this.isProcessing = false;
                this.resetForm();
                Swal.fire({
                    title: response.data.message,
                    icon: "success",
                    timer:2000,
                    showConfirmButton: false,
                    toast:true,
                    position: "bottom-end",
                    timerProgressBar: true,
                });
            }
            if (response?.response?.status == 422) {
                this.errors =  response.response.data.errors;
                this.isProcessing = false;
            }
        } catch (error) {
            console.log(error,'error');
            if (error?.response?.status == 422) {
                this.errors =  error?.response?.data?.errors;
            }
            this.isProcessing = false;
        }
      }
    },

    mounted(){
    }
  }
  </script>