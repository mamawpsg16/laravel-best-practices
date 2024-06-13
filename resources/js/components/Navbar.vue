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
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 d-flex justify-content-end align-items-center">
                    <li v-if="isGuest" class="nav-item">
                        <router-link :to="{ name: 'login' }" class="nav-link" title="Logged In account">Login</router-link>
                    </li>
                    <li v-if="isGuest" class="nav-item">
                        <router-link :to="{ name: 'register' }" class="nav-link" title="Create a new account">Register</router-link>
                    </li>
                    <li v-if="isLoggedIn" disabled class="nav-item">
                        <button class="nav-link" type="button">Hello, <span class="text-danger">{{ authStore.userName }}</span></button>
                    </li>
                    <li class="nav-item dropdown" v-if="isLoggedIn">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-gear"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li v-if="isVerifiedUser" class="nav-item">
                                <router-link :to="{ name: 'report-create' }" class="dropdown-item" title="Submit a ticket">Create Report</router-link>
                            </li>
                            <li v-if="isVerifiedUser" class="nav-item">
                                <router-link :to="{ name: 'report' }" class="dropdown-item" title="View Reports">Reports</router-link>
                            </li>
                            <li v-if="isVerifiedUser" class="nav-item">
                                <router-link :to="{ name: 'admin-user' }" class="dropdown-item" title="View Users">Users</router-link>
                            </li>
                            <li v-if="isVerifiedUser" class="nav-item">
                                <router-link :to="{ name: 'task-index' }" class="dropdown-item" title="View Tasks">Tasks</router-link>
                            </li>
                            <li><a class="dropdown-item" href="#" @click="changePassword" v-if="isGuest" title="Change Account Credentials">Change Password</a></li>
                            <li><a class="dropdown-item" href="#" @click.prevent="logout" title="Logout Account">Logout</a></li>
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

        computed:{
            isVerifiedUser(){
                return (this.authStore.isUserAuthenticated && this.authStore.isUserVerified);
            },
            isGuest(){
                return (!this.authStore.isUserAuthenticated);
            },
            isLoggedIn(){
                return (this.authStore.isUserAuthenticated);
            }
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