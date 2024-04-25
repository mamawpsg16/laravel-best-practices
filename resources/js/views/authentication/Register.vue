<template>
    <form class="form-width mx-auto border border-gray-300 rounded-lg p-4 mt-5" @submit.prevent="register">
      <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <Input type="text" id="name" autocomplete="name" class="form-control" placeholder="Enter name" v-model="name" required/>
      </div>
      <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <Input type="email" id="email" autocomplete="email" class="form-control" placeholder="Enter email" v-model="email" required/>
      </div>
      <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <Input type="password" id="password" class="form-control" autocomplete="current-password" placeholder="Enter password" v-model="password" required/>
      </div>
      <div class="mb-3">
          <label for="confirm-password" class="form-label">Password Confirmation</label>
          <Input type="password" id="confirm-password" class="form-control" autocomplete="new-password-confirm" placeholder="Enter password confirmation" v-model="password_confirmation" required/>
      </div>
      <div class="d-flex justify-content-end align-items-center my-3">
          Already have an account? <router-link class="text-decoration-none ms-2 text-primary" :to="{ name: 'login' }">Login</router-link>
      </div>
      <span v-if="isCredentialInvalid" class="text-danger mb-2">{{ isCredentialInvalid }}</span>
      <!-- <div class="flex justify-end mb-2">
          <a href="#" class="text-end text-white bg-teal-700 hover:bg-teal-900 px-4 py-2 rounded">Google</a>
      </div> -->
      <div id="btn" class="d-flex justify-content-end mt-2">
        <button type="submit" class="btn btn-primary form-control">Register</button>
      </div>
  </form>

</template>
  
  <script>
  import Input from '@js/components/Form/Input.vue'
  import axios from 'axios';
  import { useAuthStore } from '../../stores/authStore.js';
  
  export default {
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
    components:{
      Input
    },  
    methods: {
      async register() {
        try {
          let cookie = await axios.get("sanctum/csrf-cookie");
          
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