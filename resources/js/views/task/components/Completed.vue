<template>
    <Modal id="completed-tasks-modal" title="Completed Tasks" modal_size="modal-lg">
        <template #content>
            <!-- <div class="d-flex justify-content-end mb-2">
                <div class="col-md-6">
                    <label for=""></label>
                    <input type="text" class="form-control">
                </div>
            </div> -->
            <div class="d-flex justify-content-end align-items-center mt-2">
                <span>Count : {{ completedCount }}</span>
            </div>
            <div class="table-responsive mt-2" style="max-height: calc(80vh - 180px); overflow-y: auto;">
                <table class="table table-bordered" >
                    <thead>
                        <tr>
                        <th scope="col">Task</th>
                        <th scope="col">Description</th>
                        <th scope="col">Start Date</th>
                        <th scope="col">Completion Date</th>
                        <th scope="col">Due Date</th>
                        <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="task in data" v-if="data.length">
                            <td>{{ task.name }}</td>
                            <td>{{ task.description }}</td>
                            <td style="width:auto">{{ task.start_date_text }}</td>
                            <td>{{ task.completion_date_text }}</td>
                            <td>{{ task.due_date_text }}</td>
                            <td><button  class="btn btn-md btn-secondary" title="restore task to ongoing" @click="restore(task.id)"><i class="bi bi-arrow-repeat"></i></button></td>
                        </tr>
                        <tr v-else>
                            <td colspan="6" class="text-center">No Data</td>
                        </tr>
                      
                    </tbody>
                </table>
            </div>
        </template>
    </Modal>
</template>

<script>
import Modal from '@js/components/Modal.vue';
import axios from 'axios';
    export default {
        name:'Completed Tasks',
        props:{
            tasks:[Array, Object]
        },
        emits:['restoredTask'],
        data(){
            return{
                data:[],
              
            }
        },
        computed:{
            completedCount(){
                return this.data.length;
            }
        },
        methods:{
            restore(taskId){
                axios.put('/api/tasks/restore/'+taskId).then((response) => {
                    const index = this.data.findIndex(item => item.id ==  taskId);
                    if (index !== -1) {
                        const task = this.data.splice(index, 1);
                        this.$emit('restoredTask',task, true)
                    }
                }).catch((error) => {
                    console.log(error,'error');
                });
            }
          
        },
        components: {
            Modal,
        },
        watch: {
            tasks: {
                handler(data) {
                    this.data = data;
                },
                deep: true
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>