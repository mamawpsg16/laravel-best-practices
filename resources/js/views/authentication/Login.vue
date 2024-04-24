<template>
    <form class="w-2/6 mx-auto border border-gray-300 rounded-lg  p-6 mt-10" @submit.prevent="login">
      <div class="mb-5">
        <label for="email">Email</label>
        <Input type="email" id="email" autocomplete="email" class="input" placeholder="Enter email" v-model="email" required/>
      </div>
      <div class="mb-2">
        <label for="password">Password</label>
        <Input type="password" id="password" class="input" autocomplete="current-password" placeholder="Enter password" v-model="password" required/>
      </div>
      <span v-if="isCredentialInvalid" class="text-red-500 mb-1 block">{{ isCredentialInvalid }}</span>
      <div class="flex justify-end items-center mt-4 mb-2">
        Don't have an account ? &nbsp;  <router-link class="rounded text-blue-500  hover:underline hover:cursor-pointer text-md" :to="{ name: 'register' }">Register</router-link>
      </div>
      <div class="flex items-center h-5 mb-2">
        <input id="remember" type="checkbox" v-model="remembered" class="input-checkbox" />
        <label for="remember" class="ms-1 m-0">Remember me</label>
      </div>

      <button type="submit" class="auth-btn">Login</button>
      
      <div class="flex justify-around my-2">
        <a :href="googleRedirect">Google</a>
        <router-link class="rounded text-blue-500  hover:underline hover:cursor-pointer text-md" :to="{ name: 'login' }">GMAIL</router-link>
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