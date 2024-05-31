<template>
    <div v-if="!useAuthStore.isUserVerified" class="d-flex flex-column justify-content-center align-items-center">
      <p>Please verify your Email Address to continue.</p>
      <p href="#" type="button" @click.prevent="resendVerificationLink" class="btn btn-sm btn-primary">Click here to resend verification link </p>
    </div>
  </template>
  
  <script>
  import { useAuthStore } from '@js/stores/authStore';
  import { sweetAlertNotification } from '@js/helpers/sweetAlert.js';
  import apiClient from '@js/helpers/apiClient.js';

  export default {
    data() {
      return {
        useAuthStore
      };
    },
    created() {
    },
    methods: {
      async resendVerificationLink(){
        try {
          const response = await apiClient.post('/api/email/resend-verification')
          if(response.status == 200){
            const { message } = response.data;
            sweetAlertNotification(message,'Kindly check your mail','success')
          }
        } catch (error) {
          
        }
      }
    },
  };
  </script>