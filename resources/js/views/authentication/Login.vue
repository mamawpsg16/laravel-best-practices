<template>
   <form class="form-width mx-auto border border-gray-300 rounded-lg p-3 mt-5" @submit.prevent="login">
      <div class="mb-2">
          <label for="email" class="form-label">Email</label>
          <Input type="email" id="email" autocomplete="email" :class="{ 'border border-danger': checkInputValidity(null, 'email', ['required', 'email']) }" class="form-control" placeholder="Enter email" v-model="email" required/>
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
          <input type="password" id="password" class="form-control" autocomplete="current-password" :class="{ 'border border-danger': checkInputValidity(null, 'password', ['required']) }" placeholder="Enter password" v-model="password" required>
          <div class="text-danger">
              <span v-if="v$.password.required.$invalid">
                  Password is required.
              </span>
          </div>
      </div>
      <span v-if="isCredentialInvalid" class="text-danger mb-1">{{ isCredentialInvalid }}</span>
      <div class="form-check mb-1">
          <Input id="remember" type="checkbox" v-model="remembered" class="form-check-input"/>
          <label for="remember" class="form-check-label ms-1">Remember me</label>
      </div>
      <div class="d-flex justify-content-end align-items-center my-2">
          Dont have an account? <router-link class="text-decoration-none ms-2 text-primary" :to="{ name: 'register' }">Register</router-link>
      </div>
      <div id="btn" class="d-flex justify-content-end mt-2">
        <button type="submit" class="btn btn-primary form-control">Login</button>
      </div>
      <div class="my-2 text-center">
          <a :href="googleRedirect" class="text-decoration-none me-2">Google</a> 
          <!-- |
          <a href="#" class="text-decoration-none">Facebook</a> -->
      </div>
    
  </form>
</template>
  
<script>
  import Input from '@js/components/Form/Input.vue'
  import { useAuthStore } from '../../stores/authStore.js';
  import { useVuelidate } from '@vuelidate/core'
  import { required, email } from '@vuelidate/validators';
  import { checkValidity  } from '@js/helpers/Vuelidate.js';

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
        authStore:useAuthStore()
      }
    },
    validations () {
      return {
        email: { required, email },
        password: { required },
      }
    },
    components:{
      Input
    },
    created(){
    },  
    methods: {
      checkInputValidity(parentProperty = null, dataProperty, validations = []) {
          return checkValidity(this.v$, parentProperty, dataProperty, validations);
      },
      async login() {
        try {
          if(!await this.v$.$validate()) return;
          const response = await this.authStore.login(this.email, this.password, this.remembered);
          if(response.status == 200){
            localStorage.setItem('authenticated', true);
            window.location.href = '/';
          }
        } catch (error) {
          this.isCredentialInvalid = error?.response?.data.error
        }
      }
    }
  }
  </script>

<style scoped>

</style>