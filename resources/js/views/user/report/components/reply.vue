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
import formatter from '@js/helpers/formatter.js';
import { useVuelidate } from '@vuelidate/core'
import { required, maxLength, email } from '@vuelidate/validators';
    export default {
        props:{
            id:{
                type:Number,
                default:null
            },
            inConversation:{
                type:Boolean,
                default:false
            }
        },
        emits:['fetch-conversations'],
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

            formatConversation(data){
                return {
                    ...data,
                    created_at: formatter.formatReadableDateTime(data.created_at)
                }
            },

            async getConversations(){
                try {
                    const response = await apiClient.get(`/api/reports/replies`, {params:{ reportId: this.id} })
                    if(response.status == 200){
                        const { replies } = response.data;
                        const formattedReplies = replies.map(reply => this.formatConversation(reply));
                        return formattedReplies;
                    }
                } catch (error) {
                    
                }
            },

            async handleSendMessage(){
                if (!(await this.v$.$validate())) return; // Return if validation fails
                
                this.isSending = true;
                
                try {
                    const response = await apiClient.post('/api/reports/send-message',{id:this.id, message:this.message})
                    if(response.status == 200){
                        this.resetForm();
                        if(this.inConversation){
                            const replies = await this.getConversations();
                            this.$emit('fetch-conversations', replies);
                        }
                        sweetAlertNotification(response.data.message, "", "success");
                    }
                } catch (error) {
                    this.errors = error.errors;
                } finally {
                    this.isSending = false;
                }
            }
        },
        
    }
</script>

<style lang="scss" scoped>

</style>