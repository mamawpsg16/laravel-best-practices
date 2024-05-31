<template>
    <Modal id="create-task-modal">
        <template #content>
            <form  @submit.prevent="addTask">
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
                    <Input type="text" id="name" class="input" placeholder="Enter task" v-model="task.name" :class="{'border border-danger': checkInputValidity('task', 'name', ['required'])}"  />
                    <div class="text-danger">
                        <span v-if="v$.task.name.required.$invalid">
                            Task Name field is required.
                        </span>
                    </div>
                    <div v-if="errors?.name" class="text-danger">
                        {{ errors?.name[0] }}
                    </div>
                </div>
                <div id="task_description" class="mb-2">
                    <label for="">Description</label>
                    <Input type="text" id="description" class="input" placeholder="Enter description" v-model="task.description" />
                </div>

                <div id="task_status" class="mb-2">
                    <label for="">Status</label>
                    <multiselect v-model="task.status" deselect-label="Can't remove this value" track-by="label" label="label" placeholder="Select status" :options="options" :allow-empty="false"></multiselect>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success form-control mt-2" :disabled="isSaving">Save</button>
                </div>
            </form>
        </template>
    </Modal>
</template>

<script>
import Modal from '@js/components/Modal.vue';
import Input from '@js/components/Form/Input.vue'
import flatPickr from 'vue-flatpickr-component';
import { useVuelidate } from '@vuelidate/core'
import { required } from '@vuelidate/validators'
import { checkValidity } from '@js/helpers/Vuelidate.js';
// import Swal from 'sweetalert2/dist/sweetalert2.js'
import Multiselect from 'vue-multiselect'
import apiClient from '@js/helpers/apiClient.js';
import { sweetAlertNotification } from  '@js/helpers/sweetAlert.js';
    export default {
        name:'Task Create',
        setup () {
            return { v$: useVuelidate({ $autoDirty: true,  $lazy: true }) }
        },
        emits: ['updateList'],
        data(){
            return{
                isSaving:false,
                config:{
                    altFormat: 'F j, Y',
                    altInput: true,
                    dateFormat: 'Y-m-d',
                },
                task:{
                    name:null,
                    description:null,
                    due_date:new Date().toISOString().substring(0, 10),
                    status:{ label: 'Pending', value: 0 }
                },
                options: [
                    { label: 'Pending', value: 0 },
                    { label: 'Ongoing', value: 1 },
                ],
                errors:[{
                    name:false
                }]
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

            resetForm(){
                for (const key in this.task) {
                    if(key !== 'status' && key !== 'due_date'){
                        this.task[key] = null;
                    }else if (key == 'status'){
                        this.task[key] = { label: 'Pending', value: 0 };
                    }else if (key == 'due_date'){
                        this.task[key] = new Date().toISOString().substring(0, 10);
                    }
                }
                this.v$.$reset()
            },
            async addTask(){
                if (!(await this.v$.$validate())) return;

                this.isSaving = true;

                try {
                    const response = await this.postTask(this.task);
                    if(response.status == 200){
                        this.resetForm();
                        sweetAlertNotification("Task Added", "Added Successfully", "success");
                        this.$emit('updateList', response.data.data);
                    }
                } catch (error) {
                    this.errors = error.errors;
                } finally {
                    this.isSaving = false;
                }
            },

            async postTask(task){
                const formattedTask = {
                    ...task,
                    status: task.status.value,
                };
                const response = await apiClient.post('/api/tasks', formattedTask);
                return response;
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>