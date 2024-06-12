<!-- resources/js/components/App.vue -->
<template>

  <div class="container mx-auto">
      <Navbar></Navbar>
      <Breadcrumbs v-if="isVerifiedUser"/>
      <router-view/>
  </div>
</template>

<script>
import Navbar from '@js/components/Navbar.vue';
import { useAuthStore } from '@js/stores/authStore.js';
import Breadcrumbs from '@js/components/Breadcrumbs/Index.vue';
// import * as bootstrap from "bootstrap";
// window.bootstrap = bootstrap;

export default {
  props:{
    authuser:{
      type:[Array,Object],
      default:{}
    },
    authDetails:{
      type:[Array,Object],
      default:{
        error:null,
        status_code: 200
      }
    }
  },
  computed:{
      isVerifiedUser(){
          return (this.authStore.isUserAuthenticated && this.authStore.isUserVerified);
      },
  },
  data() {
    return {
      authStore:useAuthStore()
    }
  },
  // Vue component logic
  components:{
    Navbar,
    Breadcrumbs
  },

  methods:{
   
  },
  
  mounted(){
    if(this.authuser){
      this.authStore.setAuthenticated(true);
    }
    
    if(this.authDetails?.status_code != 200){
      this.authStore.setAuthenticationDetails(this.authDetails);
    }
  }
};
</script>