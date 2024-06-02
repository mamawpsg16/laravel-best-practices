<template>
  <ResetPasswordLink></ResetPasswordLink>
  <form class="form-width mx-auto border border-gray-300 rounded-lg p-3 mt-5" @submit.prevent="login">
      <h4 class="text-center">Login</h4>
      <div class="mb-2">
          <label for="email" class="form-label">Email</label>
          <Input type="email" id="email" autocomplete="email" :class="{ 'border border-danger': checkInputValidity(null, 'email', ['required', 'email']) }" class="form-control py-2" placeholder="Enter email" v-model="email" required/>
          <div v-if="errors?.email" class="text-danger">
              {{ errors?.email[0] }}
          </div>
          <div class="text-danger">
              <span v-if="v$.email.required.$invalid">
                  Email is required.
              </span>
              <span v-if="v$.email.email.$invalid">
                  Email must be valid.
              </span>
          </div>
      </div>
      <div class="mb-2">
        <label for="password" class="form-label">Password</label>
        <div class="input-group">
          <Input :type="passwordInputType" id="password" class="form-control" autocomplete="current-password" :class="{ 'border border-danger': checkInputValidity(null, 'password', ['required']) }" placeholder="Enter password" v-model="password" required />
          <span class="input-group-text" id="basic-addon1">
            <button type="button" class="btn btn-sm" @click="togglePasswordVisibility()" ><i class="bi bi-eye-slash" id="passwordVisibilityToggle"></i></button>
          </span>
        </div>
        <div v-if="errors?.password" class="text-danger">
            {{ errors?.password[0] }}
        </div>
        <div class="text-danger">
          <span v-if="v$.password.required.$invalid"> Password is required. </span>
        </div>
      </div>
      <span v-if="isCredentialInvalid" class="text-danger mb-1">{{ isCredentialInvalid }}</span>
      <div class="form-check mb-1">
          <Input id="remember" type="checkbox" v-model="remembered" class="form-check-input"/>
          <label for="remember" class="form-check-label ms-1">Remember me</label>
      </div>
      <div class="d-flex justify-content-between align-items-center my-2">
        <button type="button" class="btn btn-md text-primary" @click="changePassword">Forgot password</button>
        <button type="button" class="btn btn-md text-primary d-inline" @click="register"><span class="text-black">Dont have an account ?</span> <span>Register</span></button> 
      </div>
      <div id="btn" class="d-flex justify-content-end mt-1">
        <button type="submit" class="btn btn-primary form-control" :disabled="isLoggingIn">{{  isLoggingIn ? 'Logging In' : 'Login' }}</button>
      </div>
      <div class="my-2 text-center">
          <a :href="googleRedirect" class="text-decoration-none me-2"><i class="bi bi-google"></i></a> 
      </div>
  </form>
</template>
  
<script>
  import Input from '@js/components/Form/Input.vue'
  import { useAuthStore } from '../../stores/authStore.js';
  import { useVuelidate } from '@vuelidate/core'
  import { required, email } from '@vuelidate/validators';
  import { checkValidity  } from '@js/helpers/Vuelidate.js';
  import ResetPasswordLink from '@js/views/authentication/ResetPasswordLink.vue';
  import { sweetAlertNotification } from '@js/helpers/sweetAlert.js';
  import { deepClone, nestedDeepClone, customDeepClone } from '@js/helpers/clone.js'
  export default {
    setup () {
      return { v$: useVuelidate({ $autoDirty : true, $lazy: true}) }
    },
    data() {
      return {
        email: '',
        password: '',
        isCredentialInvalid:null,
        googleRedirect:'auth/google/redirect',
        remembered:false,
        authStore:useAuthStore(),
        isLoggingIn:false,
        passwordInputType: 'password',
        showPassword:false,
        errors:[{
            email:false,
            password:false,
        }],
      }
    },
    validations () {
      return {
        email: { required, email },
        password: { required },
      }
    },
    components:{
      Input,
      ResetPasswordLink
    },
    created(){
      const person = {
        name: "John Doe",
        age: 30,
        f: function() { return 'test'; },
        occupation: {
          jobs:['Web Dev', 'Data Analyst']
        },
      };
      const personClone = customDeepClone(person);
      console.log('ABC')
      // console.log(personClone.occupation = 'Web Developer','personClone');
      // console.log(personClone.f(),'person');
      // console.log(person,'person');
      // console.log(null || 'SHEESH');
      // console.log('SHEESH' && 1 && null && 'abc');
    },  
    methods: {

      togglePasswordVisibility() {
        const passwordVisibilityToggle = document.getElementById('passwordVisibilityToggle');
        this.showPassword = !this.showPassword;
        if (this.showPassword) {
          this.passwordInputType =  'text';
          passwordVisibilityToggle.classList.remove('bi-eye-slash');
          passwordVisibilityToggle.classList.add('bi-eye');
        } else {
          this.passwordInputType = 'password';
          passwordVisibilityToggle.classList.remove('bi-eye');
          passwordVisibilityToggle.classList.add('bi-eye-slash');
        }
      },

      checkInputValidity(parentProperty = null, dataProperty, validations = []) {
          return checkValidity(this.v$, parentProperty, dataProperty, validations);
      },
      
      changePassword(){
          const modal_id = document.getElementById("reset-password-modal");
          const modal = bootstrap.Modal.getOrCreateInstance(modal_id);
          modal.show();
      },

      register(){
        this.$router.push({name: 'register'});
      },

      async login() {
        try {
          if(this.v$.email.$errors.length > 0  && this.v$.password.$errors.length > 0 ) return;
          this.isLoggingIn = true;
          this.isCredentialInvalid = null;
          const response = await this.authStore.login(this.email, this.password, this.remembered);
          if(response.status == 200){
            localStorage.setItem('authenticated', true);
            this.isCredentialInvalid = null;
            this.isLoggingIn = false;
            sweetAlertNotification("Logged In Succesfully", "", "success")
            setTimeout(() => {
              window.location.href = '/';
            }, 1000);
          }
          if (response.response.status == 422) {
              this.errors =  response.response.data.errors;
              this.isLoggingIn = false;
          }
        } catch (error) {
          this.isCredentialInvalid = error?.response?.data.error
          this.isLoggingIn = false;
        }
      }
    }
  }
  </script>

<style scoped>

</style>