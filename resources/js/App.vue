<!-- resources/js/components/App.vue -->
<template>

  <div class="container mx-auto">
      <Navbar></Navbar>
      <router-view/>
  </div>
</template>

<script>
import Navbar from '@js/components/Navbar.vue';
import { useAuthStore } from '@js/stores/authStore.js';

// import * as bootstrap from "bootstrap";
// window.bootstrap = bootstrap;

export default {
  props:{
    authuser:{
      type:[Array,Object],
      default:{}
    },
    banned:{
      type:[Array,Object],
      default:{
        error:null,
        status_code: 200
      }
    }
  },
  data() {
    return {
      authStore:useAuthStore()
    }
  },
  // Vue component logic
  components:{
    Navbar,
  },

  methods:{
   
  },
  
  mounted(){
    if(this.authuser){
      this.authStore.setAuthenticated(true);
    }

    if(this.banned?.status_code == 403){
      this.authStore.setBannedConfig(this.banned);
    }
  }
};
</script>