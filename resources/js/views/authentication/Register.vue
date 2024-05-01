<template>
    <form class="form-width mx-auto border border-gray-300 rounded-lg p-4 mt-5" @submit.prevent="register">
      <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <Input type="text" id="name" autocomplete="name" class="form-control" placeholder="Enter name" v-model="name" :class="{ 'border border-danger': checkInputValidity(null, 'name', ['required']) }" required/>
          <div class="text-danger">
              <span v-if="v$.name.required.$invalid">
                  Name is required.
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
          <Input type="password" id="password" class="form-control" autocomplete="current-password" placeholder="Enter password" v-model="password" :class="{ 'border border-danger': checkInputValidity(null, 'password', ['required']) }" required/>
          <div class="text-danger">
              <span v-if="v$.password.required.$invalid">
                  Password is required.
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
      <div class="d-flex justify-content-end align-items-center my-3">
          Already have an account? <router-link class="text-decoration-none ms-2 text-primary" :to="{ name: 'login' }">Login</router-link>
      </div>
      <span v-if="isCredentialInvalid" class="text-danger mb-2">{{ isCredentialInvalid }}</span>
      <div id="btn" class="d-flex justify-content-end mt-2">
        <button type="submit" class="btn btn-primary form-control">Register</button>
      </div>
  </form>

</template>
  
  <script>
  import Input from '@js/components/Form/Input.vue'
  import { useAuthStore } from '../../stores/authStore.js';
  import { useVuelidate } from '@vuelidate/core'
  import { required, email, sameAs } from '@vuelidate/validators'
  import { checkValidity  } from '@js/helpers/Vuelidate.js';

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
        authStore:useAuthStore()
      }
    },
    validations () {
      return {
        name: { required }, 
        email: { required, email }, 
        password: { required },
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

      async register() {
        try {
          if(!await this.v$.$validate()) return;         
          const response = await this.authStore.register(this.name, this.email, this.password, this.password_confirmation );
          if(response.status == 200){

            localStorage.setItem('authenticated', true);

            window.location.href = '/';
          }
        } catch (error) {
            this.isCredentialInvalid = error.response.data.error
        }
      }
    }
  }
  </script>