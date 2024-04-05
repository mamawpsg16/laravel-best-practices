<template>
    <div>
        ABOUT
    </div>
</template>

<script>
import axios from 'axios';
    export default {
        data() {
            return {
             
            }
        },
        mounted(){
            this.getPosts();
        },
        methods:{
            async getPosts() {
                // this.authStore.setAuthenticated(true)
                try {
                const response = await axios.get('/api/posts');
                } catch (error) {
                    console.error(error,'ERROR');
                }
            },
        }
    }
</script>

<style lang="scss" scoped>

</style>