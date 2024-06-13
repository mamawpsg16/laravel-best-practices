<template>
    <Modal id="edit-task-modal" :title="task.name">
        <template #content>
            <form  @submit.prevent="update">
                <div id="task_due_date" class="mb-2">
                    <label for="">Due Date</label>
                    <flat-pickr v-model="task.due_date" :config="config"  :class="{'border border-danger': checkInputValidity('task', 'due_date', ['required'])}" class="form-control" placeholder="Select Due Date"/>
                    <div class="text-danger">
                        <span v-if="v$.task.due_date.required.$invalid">
                            Due Date field is required.
                        </span>
                    </div>
                </div>
                <div id="task_name" class="mb-2">
                    <label for="">Name <code>*</code></label>
                    <Input type="text" id="task-name" class="input" placeholder="Enter task" v-model="task.name" :class="{'border border-danger': checkInputValidity('task', 'name', ['required'])}"  />
                    <div class="text-danger">
                        <span v-if="v$.task.name.required.$invalid">
                            Task Name field is required.
                        </span>
                    </div>
                </div>
                <div id="task_description" class="mb-2">
                    <label for="">Description</label>
                    <Input type="text" id="edit-description" class="input" placeholder="Enter description" v-model="task.description" />
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success form-control mt-2" :disabled="isUpdating">Update</button>
                </div>
            </form>
        </template>
    </Modal>
</template>

<script>
import Modal from '@js/components/Modal.vue';
import Input from '@js/components/Form/Input.vue'
import flatPickr from 'vue-flatpickr-component';
import axios from 'axios';
import { useVuelidate } from '@vuelidate/core'
import { required } from '@vuelidate/validators'
import { checkValidity } from '@js/helpers/Vuelidate.js';
import Multiselect from 'vue-multiselect'
import apiClient from '@js/helpers/apiClient.js';
import { sweetAlertNotification } from '@js/helpers/sweetAlert.js';
    export default {
        name:'Task Edit',
        props:{
            task:{
                type:[Array, Object],
                default() {
                    return {
                        name: null,
                        description: null,
                        due_date: null,
                    };
                },
            }
        },
        setup () {
            return { v$: useVuelidate({ $autoDirty: true,  $lazy: true }) }
        },
        emits: ['updateTask'],
        data(){
            return{
                isUpdating:false,
                config:{
                    altFormat: 'F j, Y',
                    altInput: true,
                    dateFormat: 'Y-m-d',
                },
            }
        },
        components: {
            Input,
            flatPickr,
            Modal,
            Multiselect 
        },
        validations () {
            return {
                task: {
                    name: { required }, // Matches this.contact.email
                    due_date: { required }, // Matches this.contact.email
                }
            }
        },
        methods:{
            checkInputValidity(parentProperty = null, dataProperty, validations = []){
                return checkValidity(this.v$, parentProperty, dataProperty, validations)
            },

            async update(){
                try {
                    if(!await this.v$.$validate()) return;
                    this.isUpdating = true;
                    const response = await apiClient.put(`/api/tasks/${this.task.id}`,{...this.task})
                    if(response.status == 200){
                        const { data } = response.data;
                        sweetAlertNotification("Task Updated", "Updated Successfully", "success");
                        this.isUpdating = false;
                        this.$emit('updateTask', data)
                    }
                } catch (error) {
                    
                }
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>