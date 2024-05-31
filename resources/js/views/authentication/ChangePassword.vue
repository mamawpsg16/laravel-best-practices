<template>
    <Modal id="change-password-modal" title="Change Password">
        <template #content>
            <form class="mx-auto rounded-lg p-3" @submit.prevent="update">
                <input type="text" style="display:none;" id="username" name="username" autocomplete="username">
                <div class="mb-2">
                    <label for="password" class="form-label">Current Password <code>*</code></label>
                    <div class="input-group">
                        <Input :type="passwordInputType" id="current-password" class="form-control" tabindex="1" autocomplete="current-password" :class="{ 'border border-danger': checkInputValidity(null, 'password', ['required']) }" placeholder="Enter password" v-model="password" required />
                        <span class="input-group-text" id="basic-addon1">
                            <button type="button" class="btn btn-sm" @click="togglePasswordVisibility()" ><i class="bi bi-eye-slash" id="passwordVisibilityToggle"></i></button>
                        </span>
                    </div>
                    <div v-if="errors?.password" class="text-danger">
                        {{ errors?.password[0] }}
                    </div>
                    <div class="text-danger">
                        <span v-if="v$.password.required.$invalid">
                            Password is required.
                        </span>
                    </div>
                </div>
                <div class="mb-2">
                    <label for="password" class="form-label">New Password <code>*</code></label>
                    <div class="input-group">
                        <Input :type="newPasswordInputType" id="new-password" tabindex="2" class="form-control" autocomplete="new-password" :class="{ 'border border-danger': checkInputValidity(null, 'new_password', ['required', 'notSameAsPassword', 'minLength']) }" placeholder="Enter new password" v-model="new_password" required />
                        <span class="input-group-text" id="basic-addon1">
                            <button type="button" class="btn btn-sm" @click="toggleNewPasswordVisibility()" ><i class="bi bi-eye-slash" id="newPasswordVisibilityToggle"></i></button>
                        </span>
                    </div>
                    <div v-if="errors?.new_password" class="text-danger">
                        {{ errors?.new_password[0] }}
                    </div>
                    <div class="text-danger">
                        <span v-if="v$.new_password.required.$invalid">
                            New Password is required.
                        </span>
                        <span v-if="v$.new_password.notSameAsPassword.$invalid" :class="{'d-block': v$.new_password.required}">
                            Old password cannot be reused. Please select a new one
                        </span>
                        <span v-if="v$.new_password.minLength.$invalid" :class="{'d-block': v$.new_password.notSameAsPassword}">
                            New Password must be at least 8 characters
                        </span>
                    </div>
                </div>
                <div class="mb-2">
                    <label for="password" class="form-label">Confirm New Password <code>*</code></label>
                    <div class="input-group">
                        <Input :type="newPasswordConfirmationInputType" tabindex="3" id="new-password-confirmation" class="form-control" autocomplete="new-password-confirmation" :class="{ 'border border-danger': checkInputValidity(null, 'new_password_confirmation', ['required', 'sameAsPassword']) }" placeholder="Enter new password confirmation" v-model="new_password_confirmation" required />
                        <span class="input-group-text" id="basic-addon1">
                            <button type="button" class="btn btn-sm" @click="toggleNewPasswordConfirmationVisibility()" ><i class="bi bi-eye-slash" id="newPasswordConfirmationVisibilityToggle"></i></button>
                        </span>
                    </div>
                    <div v-if="errors?.new_password_confirmation" class="text-danger">
                        {{ errors?.new_password_confirmation[0] }}
                    </div>
                    <div class="text-danger">
                        <span v-if="v$.new_password_confirmation.required.$invalid">
                            New Password Confirmation is required.
                        </span>
                        <span v-if="v$.new_password_confirmation.sameAsPassword.$invalid" :class="{'d-block': v$.new_password_confirmation.required}">
                            New Password Confirmation doesnt match.
                        </span>
                    </div>
                </div>
                <span v-if="isCredentialInvalid" class="text-danger mb-1">{{ isCredentialInvalid }}</span>
                <div id="btn" class="d-flex justify-content-end mt-2">
                    <button type="submit" class="btn btn-primary form-control" tabindex="4" :disabled="isUpdating">{{  isUpdating ? 'Updating' : 'Update' }}</button>
                </div>
            </form>
        </template>
    </Modal>
</template>

<script>
import Modal from '@js/components/Modal.vue';
import Input from '@js/components/Form/Input.vue'
import { useVuelidate } from '@vuelidate/core'
import { required, email, sameAs, helpers, minLength  } from '@vuelidate/validators'
import axios from 'axios';
import { checkValidity  } from '@js/helpers/Vuelidate.js';
import { useAuthStore } from '@js/stores/authStore.js';
import Swal from 'sweetalert2/dist/sweetalert2.js'
import { sweetAlertNotification } from '@js/helpers/sweetAlert.js';

    const notSameAsPassword = (currentPassword) => {
        return helpers.withParams( { type: 'notSameAsPassword', value: currentPassword },
            (value) => value != currentPassword
        );
    };

    export default {
        setup () {
            return { v$: useVuelidate({ $autoDirty : true, $lazy: true}) }
        },
        data() {
            return {
                password: '',
                authStore:useAuthStore(),
                new_password: '',
                new_password_confirmation: '',
                isCredentialInvalid:null,
                isUpdating:false,
                passwordInputType: 'password',
                showPassword:false,
                newPasswordInputType: 'password',
                showNewPassword:false,
                newPasswordConfirmationInputType: 'password',
                showNewConfirmationPassword:false,
                errors:[{
                    password:false,
                    new_password:false,
                    new_password_confirmation:false,
                }],
            }
        },
        validations () {
            return {
                password: { required },
                new_password: { required, notSameAsPassword: notSameAsPassword(this.password), minLength:minLength(8) },
                new_password_confirmation: { required, sameAsPassword: sameAs(this.new_password) }
            }
        },
        methods:{
            resetForm() {
                this.password = null;
                this.new_password = null;
                this.new_password_confirmation = null;
                this.errors = [{
                    password:false,
                    new_password:false,
                    new_password_confirmation:false,
                }],
                this.v$.$reset();
                this.isUpdating = false;
            },
            checkInputValidity(parentProperty = null, dataProperty, validations = []) {
                return checkValidity(this.v$, parentProperty, dataProperty, validations);
            },

            togglePasswordVisibility() {
                const passwordVisibilityToggle = document.getElementById('passwordVisibilityToggle');
                this.showPassword = !this.showPassword;
                if (this.showPassword) {
                    this.passwordInputType =  'text';
                    passwordVisibilityToggle.classList.remove('bi-eye-slash');
                    passwordVisibilityToggle.classList.add('bi-eye');
                } else {
                    this.passwordInputType = 'password';
                    passwordVisibilityToggle.classList.remove('bi-eye');
                    passwordVisibilityToggle.classList.add('bi-eye-slash');
                }
            },

            toggleNewPasswordVisibility() {
                const passwordVisibilityToggle = document.getElementById('newPasswordVisibilityToggle');
                this.showNewPassword = !this.showNewPassword;
                if (this.showNewPassword) {
                this.newPasswordInputType =  'text';
                passwordVisibilityToggle.classList.remove('bi-eye-slash');
                passwordVisibilityToggle.classList.add('bi-eye');
                } else {
                this.newPasswordInputType = 'password';
                passwordVisibilityToggle.classList.remove('bi-eye');
                passwordVisibilityToggle.classList.add('bi-eye-slash');
                }
            },

            toggleNewPasswordConfirmationVisibility() {
                    const passwordVisibilityToggle = document.getElementById('newPasswordConfirmationVisibilityToggle');
                    this.showNewConfirmationPassword = !this.showNewConfirmationPassword;
                if (this.showNewConfirmationPassword) {
                    this.newPasswordConfirmationInputType =  'text';
                    passwordVisibilityToggle.classList.remove('bi-eye-slash');
                    passwordVisibilityToggle.classList.add('bi-eye');
                } else {
                    this.newPasswordConfirmationInputType = 'password';
                    passwordVisibilityToggle.classList.remove('bi-eye');
                    passwordVisibilityToggle.classList.add('bi-eye-slash');
                }
            },
            
            update(){
                this.authStore.changePassword
            },
            async update() {
                try {
                    if(!await this.v$.$validate()) return;
                    this.isUpdating = true;
                    const response = await this.authStore.changePassword(this.password, this.new_password, this.new_password_confirmation);
                    if(response?.status == 200){
                        this.resetForm()
                        sweetAlertNotification(response.data.message,'','success')
                    }
                    if (response.response.status == 422) {
                        this.errors =  response.response.data.errors;
                        this.isUpdating = false;
                    }
                    
                } catch (error) {
                 
                }
            }
        },
        components: {
            Modal,
            Input
        },
    }
</script>

<style lang="scss" scoped>

</style>