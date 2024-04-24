<!-- resources/js/components/App.vue -->
<template>

  <div class="container mx-auto">
      <Navbar></Navbar>
      <router-view />
  </div>
</template>

<script>
import Navbar from '@js/components/Navbar.vue';
export default {
  props:["authuser"],
  // Vue component logic
  components:{
    Navbar
  },

  mounted(){
    console.log(this.authuser,'this.authuser');
    if(this.authuser){
      localStorage.setItem('authenticated',true);
    }
  }
};
</script>