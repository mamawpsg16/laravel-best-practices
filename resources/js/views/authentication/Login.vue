<template>
    <!-- <form @submit.prevent="login">
        <input v-model="email" placeholder="Email" autocomplete="email" />
        <input v-model="password" type="password" placeholder="Password" autocomplete="current-password" />
        <p v-if="isCredentialInvalid">{{ isCredentialInvalid }}</p>
        <button type="submit">Login</button>
    </form>
     -->

    <form class="max-w-sm mx-auto" @submit.prevent="login">
      <div class="mb-5">
        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
        <input type="email" id="email" autocomplete="email" v-model="email" placeholder="Enter email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
      </div>
      <div class="mb-5">
        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
        <input type="password" autocomplete="current-password" placeholder="Enter password" v-model="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required />
      </div>
      <span v-if="isCredentialInvalid" class="text-red-500 mb-2 block">{{ isCredentialInvalid }}</span>
      <div class="flex justify-end mb-2">
            <a href="#" class="text-end text-white bg-teal-700 hover:bg-teal-900 px-4 py-2 rounded">Google</a>
      </div>
      <!-- <div class="flex items-start mb-5">
        <div class="flex items-center h-5">
          <input id="remember" type="checkbox" value="" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800" required />
        </div>
        <label for="remember" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Remember me</label>
      </div> -->
      <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Login</button>
    </form>

  </template>
  
  <script>
  import axios from 'axios';
  import { useAuthStore } from '../../stores/authStore.js';
  
  export default {
    data() {
      return {
        email: '',
        password: '',
        isCredentialInvalid:null,
        authStore:useAuthStore()
      }
    },
    created(){
    },  
    methods: {
      async login() {
        try {
          let cookie = await axios.get("sanctum/csrf-cookie");
          const response = await axios.post('/login', {
            email: this.email,
            password: this.password
          });
          
          localStorage.setItem('authenticated', true);
          
          // Store the session ID in a cookie
          this.$router.push({ name: 'dashboard'})
  
          // Redirect or update the UI as needed
          console.log('Login successful');
        } catch (error) {
            this.isCredentialInvalid = error.response.data.error
          console.error(error,'ERROR');
        }
      }
    }
  }
  </script>