<template>
    <form class="form-width mx-auto border border-gray-300 rounded-lg p-4 mt-5" @submit.prevent="register">
      <h4 class="text-center">Register</h4>
      <div class="mb-3">
          <label for="name" class="form-label">Username</label>
          <Input type="text" id="register-name" autocomplete="name" class="form-control" placeholder="Enter name" v-model="name" :class="{ 'border border-danger': checkInputValidity(null, 'name', ['required']) }" required/>
          <div class="text-danger">
              <span v-if="v$.name.required.$invalid">
                Username is required.
              </span>
          </div>
      </div>
      <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <Input type="email" id="email" autocomplete="email" class="form-control" placeholder="Enter email" v-model="email" :class="{ 'border border-danger': checkInputValidity(null, 'email', ['required', 'email']) }" required/>
          <div class="text-danger">
              <span v-if="v$.email.required.$invalid">
                  Email is required.
              </span>
              <span v-if="v$.email.email.$invalid" :class="{'d-block': v$.email.required}">
                  Email must be valid.
              </span>
          </div>
      </div>
      <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <Input type="password" id="password" class="form-control" autocomplete="current-password" placeholder="Enter password" v-model="password" :class="{ 'border border-danger': checkInputValidity(null, 'password', ['required', 'minLength']) }" required/>
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
      <div class="d-flex justify-content-end align-items-center my-3">
        <button type="button" class="btn btn-md text-primary d-inline" @click="login"><span class="text-black"> Already have an account ? </span>Login</button> 
      </div>
      <div id="btn" class="d-flex justify-content-end mt-2">
        <button type="submit" class="btn btn-primary form-control" :disabled="isProcessing">Register</button>
      </div>
  </form>

</template>
  
  <script>
  import Input from '@js/components/Form/Input.vue'
  import { useAuthStore } from '../../stores/authStore.js';
  import { useVuelidate } from '@vuelidate/core'
  import { required, email, sameAs, minLength } from '@vuelidate/validators'
  import { checkValidity  } from '@js/helpers/Vuelidate.js';
  import { sweetAlertNotification } from "@js/helpers/sweetAlert.js";

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
        isProcessing:false
      }
    },
    validations () {
      return {
        name: { required }, 
        email: { required, email }, 
        password: { required, minLength:minLength(8) },
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
      
      login(){
        this.$router.push({name: 'login'});
      },

      async register() {
        try {
          if(!await this.v$.$validate()) return;         
          this.isProcessing = true;
          const response = await this.authStore.register(this.name, this.email, this.password, this.password_confirmation );
          if(response.status == 200){
            localStorage.setItem('authenticated', true);
            this.isProcessing = false;
            this.isCredentialInvalid = null;
            sweetAlertNotification("Registered Succesfully, Kindly Verify Your Email","", "success")
            setTimeout(() => {
              window.location.href = '/';
            }, 1500);
          }
        } catch (error) {
          this.isCredentialInvalid = error.response.data.message
          this.isProcessing = false;
        }
      }
    }
  }
  </script>