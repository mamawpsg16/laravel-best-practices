<template>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom border-gray-200 dark:bg-gray-900">
    <div class="container">
        <router-link :to="{ name: 'dashboard' }" class="navbar-brand">
            Home
        </router-link>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-default" aria-controls="navbar-default" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar-default">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li v-if="!isAuthenticated" class="nav-item">
                    <router-link :to="{ name: 'login' }" class="nav-link">Login</router-link>
                </li>
                <li v-if="!isAuthenticated" class="nav-item">
                    <router-link :to="{ name: 'register' }" class="nav-link">Register</router-link>
                </li>
                <li v-if="isAuthenticated" class="nav-item">
                    <router-link :to="{ name: 'tasks' }" class="nav-link">Tasks</router-link>
                </li>
                <li v-if="isAuthenticated" class="nav-item">
                    <a href="#" class="nav-link">Hello, <span class="text-danger">{{ userName }}</span></a>
                </li>
                <li v-if="isAuthenticated" class="nav-item">
                    <a href="#" @click.prevent="logout" class="nav-link">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


</template>

<script>
import  { useAuthStore }  from '@js/stores/authStore.js';
    export default {
        data(){
            return{
            users:['Kevin','Rojenson', 'Jep'],
            user:null,
            authStore: useAuthStore()
            }
        },

        computed:{
            isAuthenticated(){
              return this.authStore.isAuthenticated;
            },

            userName() {
              return this.authStore.userName;
            }
        },

        
        methods:{
            async logout(){
                const response = await useAuthStore().logout().then(response =>{
                    window.location.href = '/login';
                });
            },

            navigateUser(){
                const index = Math.trunc(Math.random() * 3);
                this.$router.push({name: 'user', params:{username:this.users[index]}})
            }
        },
        watch: {
          // Note: only simple paths. Expressions are not supported.
          'authStore.isAuthenticated'(newValue) {
            localStorage.setItem('authenticated',newValue);
          }
        }
    }
</script>

<style lang="scss" scoped>

</style>