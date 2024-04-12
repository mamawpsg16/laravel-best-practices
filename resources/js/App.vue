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
 
  // Vue component logic
  components:{
    Navbar
  }
};
</script>