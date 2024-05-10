<template>
    <ChangePassword></ChangePassword>
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
                    <li v-if="!authStore.isUserAuthenticated" class="nav-item">
                        <router-link :to="{ name: 'login' }" class="nav-link">Login</router-link>
                    </li>
                    <li v-if="!authStore.isUserAuthenticated" class="nav-item">
                        <router-link :to="{ name: 'register' }" class="nav-link">Register</router-link>
                    </li>
                    <li v-if="authStore.isUserAuthenticated" disabled class="nav-item">
                        <button class="nav-link" type="button">Hello, <span class="text-danger">{{ authStore.userName }}</span></button>
                    </li>
                    <li class="nav-item dropdown" v-if="authStore.isUserAuthenticated">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-gear"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li v-if="authStore.isUserAuthenticated && authStore.isUserVerified" class="nav-item">
                                <router-link :to="{ name: 'tasks' }" class="dropdown-item">Tasks</router-link>
                            </li>
                            <li><a class="dropdown-item" href="#" @click="changePassword" v-if="!authStore.isSocialAuthenticated">Change Password</a></li>
                            <li><a class="dropdown-item" href="#" @click.prevent="logout">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
import  { useAuthStore }  from '@js/stores/authStore.js';
import ChangePassword from '@js/views/authentication/ChangePassword.vue';
    export default {
        data(){
            return{
                authStore: useAuthStore()
            }
        },
        components:{
            ChangePassword
        },
        methods:{
            logout(){
                useAuthStore().logout();
            },

            changePassword(){
                const modal_id = document.getElementById("change-password-modal");
                const modal = bootstrap.Modal.getOrCreateInstance(modal_id);
                modal.show();
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>