<template>
   <form class="mb-2" @submit.prevent="handleSendMessage">
        <label for="" class="form-label">Send A Message</label>
        <TextArea v-model="message"  :class="{ 'border border-danger': checkInputValidity(null, 'message', ['required', 'maxLength']) || errors?.message }" required></TextArea>
        <div v-if="errors?.message" class="text-danger">
                {{ errors?.message[0] }}
            </div>
            <div class="text-danger">
                <span v-if="v$.message.required.$invalid">
                Message is required.
                </span>
                <span v-if="v$.message.maxLength.$invalid" :class="{'d-block' : v$.message.required.$invalid}">
                Message must only contain 1000 characters.
                </span>
            </div>
        <button class="btn btn-sm form-control bg-success-subtle my-2"   :disabled="isSending"> {{  isSending ? 'Sending' : 'Send' }} <i class="bi bi-send"></i></button>
    </form>
</template>

<script>
import apiClient from '@js/helpers/apiClient.js';
import { sweetAlertNotification } from '@js/helpers/sweetAlert.js';
import TextArea from '@js/components/Form/TextArea.vue'
import { checkValidity  } from '@js/helpers/Vuelidate.js';
import { useVuelidate } from '@vuelidate/core'
import { required, maxLength, email } from '@vuelidate/validators';
    export default {
        props:{
            id:{
                type:Number,
                default:null
            }
        },
        setup () {
            return { v$: useVuelidate({ $autoDirty : true, $lazy: true}) }
        },
        components:{
            TextArea,
        },
        data(){
                return{
                    errors:[{
                    message:false,
                }],
                message:null,
                isSending: false,
                reportId:null
            }
        },
        validations() {
            return {
                message: { required, maxLength: maxLength(1000) }
            }
        },
        methods:{
            resetForm(){
                this.message = null;
                this.errors = [{
                    message:false,
                }];
                this.v$.$reset();
            },

            checkInputValidity(parentProperty = null, dataProperty, validations = []) {
                return checkValidity(this.v$, parentProperty, dataProperty, validations);
            },

            async handleSendMessage(){
                if (!(await this.v$.$validate())) return; // Return if validation fails
                
                this.isSending = true;
                
                try {
                    const response = await apiClient.post('/api/reports/send-message',{id:this.reportId, message:this.message})
                    if(response.status == 200){
                        this.resetForm();
                        sweetAlertNotification(response.data.message, "", "success");
                    }
                } catch (error) {
                    this.errors = error.errors;
                } finally {
                    this.isSending = false;
                }
            }
        },

        watch:{
            id(newId){
                if(newId){
                    this.reportId = newId;
                }
            }
        }
        
    }
</script>

<style lang="scss" scoped>

</style>