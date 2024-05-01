<template>
    <Modal id="create-task-modal">
        <template #content>
            <form  @submit.prevent="addTask">
                <div id="task_due_date" class="mb-2">
                    <label for="">Due Date</label>
                    <flat-pickr v-model="task.due_date" :config="config" class="form-control" placeholder="Select Due Date"/>
                </div>
                <div id="task_name" class="mb-2">
                    <label for="">Name <code>*</code></label>
                    <Input type="text" id="name" class="input" placeholder="Enter task" v-model="task.name" :class="{'border border-danger': checkInputValidity('task', 'name', ['required'])}"  />
                    <div class="text-danger">
                        <span v-if="v$.task.name.required.$invalid">
                            Task Name field is required.
                        </span>
                    </div>
                </div>
                <div id="task_description" class="mb-2"     >
                    <label for="">Description</label>
                    <Input type="text" id="description" class="input" placeholder="Enter description" v-model="task.description" />
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success form-control mt-2">Save</button>
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
import Swal from 'sweetalert2/dist/sweetalert2.js'

    export default {
        name:'Task Create',
        setup () {
            return { v$: useVuelidate({ $autoDirty: true,  $lazy: true }) }
        },
        emits: ['updateList'],
        data(){
            return{
                config:{
                    altFormat: 'F j, Y',
                    altInput: true,
                    dateFormat: 'Y-m-d',
                },
                task:{
                    name:null,
                    description:null,
                    due_date:null
                }
            }
        },
        validations () {
            return {
                task: {
                    name: { required }, // Matches this.contact.email
                    description: { required } // Matches this.contact.email
                }
            }
        },
        methods:{
            checkInputValidity(parentProperty = null, dataProperty, validations = []){
                return checkValidity(this.v$, parentProperty, dataProperty, validations)
            },

            resetForm(){
                console.log('F');
                for (const key in this.task) {
                    this.task[key] = null;
                }
                this.v$.$reset()
            },
            async addTask(){
                if(!await this.v$.$validate()) return;
                console.log('SHEESH');
                axios.post('/api/tasks',{...this.task}).then((response)=>{
                    if(response.status == 200){
                        const { data } = response.data;
                        this.resetForm()
                        Swal.fire({
                            title: "Task Added!",
                            text: "Added Succesfully",
                            icon: "success",
                            timer:1500,
                            showConfirmButton: false,
                        });

                        this.$emit('updateList', data)
                    }
                }).catch((error) =>{

                })
            },
        },
        components: {
            Input,
            flatPickr,
            Modal
            // rawDisplayer
        },
    }
</script>

<style lang="scss" scoped>

</style>