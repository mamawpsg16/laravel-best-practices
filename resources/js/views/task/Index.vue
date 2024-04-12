<template>
    <div class="component-container">
        <form class="border border-gray-300 rounded-lg p-6 mt-10" @submit.prevent="register">
          <div class="mb-5">
            <label for="email">Name</label>
            <Input type="name" id="name" autocomplete="name" class="input" placeholder="Enter name" v-model="name" required/>
          </div>
          <div class="mb-5">
            <label for="email">Email</label>
            <Input type="email" id="email" autocomplete="email" class="input" placeholder="Enter email" v-model="email" required/>
          </div>
          <div class="mb-5">
            <label for="password">Password</label>
            <Input type="password" id="password" class="input" autocomplete="current-password" placeholder="Enter password" v-model="password" required/>
          </div>
          <div class="mb-5">
            <label for="password">Password Confirmation</label>
            <Input type="password" id="confirm-password" class="input" autocomplete="new-password-confirm" placeholder="Enter password confirmation" v-model="password_confirmation" required/>
          </div>
          <div class="flex justify-end items-center my-4">
            Already have an account ? &nbsp;  <router-link class="rounded text-blue-500  hover:underline hover:cursor-pointer text-md" :to="{ name: 'login' }">Login</router-link>
          </div>
          <span v-if="isCredentialInvalid" class="text-red-500 mb-2 block">{{ isCredentialInvalid }}</span>
          <!-- <div class="flex justify-end mb-2">
                <a href="#" class="text-end text-white bg-teal-700 hover:bg-teal-900 px-4 py-2 rounded">Google</a>
          </div> -->
          <button type="submit" class="auth-btn">Register</button>
        </form>
    </div>
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