<template>
   <form class="form-width mx-auto border border-gray-300 rounded-lg p-3 mt-5" @submit.prevent="login">
      <div class="mb-2">
          <label for="email" class="form-label">Email</label>
          <Input type="email" id="email" autocomplete="email" class="form-control" placeholder="Enter email" v-model="email" required/>
      </div>
      <div class="mb-2">
          <label for="password" class="form-label">Password</label>
          <input type="password" id="password" class="form-control" autocomplete="current-password" placeholder="Enter password" v-model="password" required>
      </div>
      <span v-if="isCredentialInvalid" class="text-danger mb-1">{{ isCredentialInvalid }}</span>
      <div class="form-check mb-1">
          <Input id="remember" type="checkbox" v-model="remembered" class="form-check-input"/>
          <label for="remember" class="form-check-label ms-1">Remember me</label>
      </div>
      <div class="d-flex justify-content-end align-items-center my-2">
          Dont have an account? <router-link class="text-decoration-none ms-2 text-primary" :to="{ name: 'register' }">Register</router-link>
      </div>
      <div class="my-2 text-center">
          <a :href="googleRedirect" class="text-decoration-none me-2">Google</a> |
          <a href="#" class="text-decoration-none">Facebook</a>
      </div>
      <div id="btn" class="d-flex justify-content-end mt-2">
        <button type="submit" class="btn btn-primary form-control">Login</button>
      </div>
    
  </form>
</template>
  
<script>
  import axios from 'axios';
  import Input from '@js/components/Form/Input.vue'
  import { useAuthStore } from '../../stores/authStore.js';
  
  export default {
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
    components:{
      Input
    },
    created(){
    },  
    methods: {
      async login() {
        try {
          let cookie = await axios.get("sanctum/csrf-cookie");
          
          const response = await this.authStore.login(this.email, this.password, this.remembered);
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

<style scoped>

</style>