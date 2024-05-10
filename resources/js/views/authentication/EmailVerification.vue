<template>
    <div v-if="!useAuthStore.isUserVerified" class="d-flex flex-column justify-content-center align-items-center">
      <p>Please verify your Email Address to continue.</p>
      <p href="#" type="button" @click.prevent="resendVerificationLink" class="btn btn-sm btn-primary">Click here to resend verification link </p>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import { useAuthStore } from '@js/stores/authStore';
  import Swal from 'sweetalert2/dist/sweetalert2.js'
  export default {
    data() {
      return {
        useAuthStore
      };
    },
    created() {
    },
    methods: {
      resendVerificationLink(){
        axios.post('/api/email/resend-verification').then((response) =>{
          const { message } = response.data;
          Swal.fire({
                    title: message,
                    text: "Kindly check your mail.",
                    icon: "success",
                    timer:2000,
                    showConfirmButton: false,
                    toast:true,
                    position: "bottom-end",
                    timerProgressBar: true,
                });
        }).catch((error) =>{
        });

      }
    },
  };
  </script>