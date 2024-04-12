<template>
    <div class="not-found-container">
       <h1>403</h1>
       <p>Forbidden</p>
   </div>
</template>

<script>
   export default {
       
   }
</script>

<style scoped>
.not-found-container {
 display: flex;
 flex-direction: column;
 align-items: center;
 justify-content: center;
 height: 100vh;
 font-family: 'Arial', sans-serif;
}

h1 {
 font-size: 5em;
 margin-bottom: 0.2em;
 color: #333;
}

p {
 font-size: 1.5em;
 color: #666;
 margin-bottom: 1em;
}
</style>