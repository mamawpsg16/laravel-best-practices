<template>
    <Modal id="completed-tasks-modal" title="Completed Tasks" modal_size="modal-xl" :enableBackdrop="true" :disableEscape="false">
        <template #content>
            <div class="my-2 ">
                <VueGoodTable :columns="columns" :rows="data" :tableActionHeader="true" ref="vueGoodTable" :searchOptions="{ enabled: true }" :selectOptions="{ enabled: true, disableSelectInfo:true, selectOnCheckboxOnly:true }" @selected-rows-change="onSelectedRowsChange">
                    <template #table-action-content>
                        <div class="d-flex justify-content-center align-items-center" title="Actions">
                            <button v-if="hasRestoreButton" class="btn btn-sm" @click="restoreConfirmation"><i class="bi bi-arrow-clockwise" title="Restore All" style="font-size: 18px;"></i></button>
                        </div>
                    </template>
                    <template #content="{ row, column, index, formattedRow }">
                    <span v-if="column.field == 'action'" class="d-flex justify-content-center align-items-center" title="Actions">
                        <button  class="btn btn-sm" title="restore task to ongoing" @click="restore(row.id)"><i class="bi bi-arrow-repeat"></i></button>
                    </span>
                    <span v-else>
                        {{row[column.field]}}
                    </span>
                    </template>
                </VueGoodTable>
            </div>
        </template>
    </Modal>
</template>

<script>
import Modal from '@js/components/Modal.vue';
import apiClient from '@js/helpers/apiClient.js';
import VueGoodTable from '@js/components/VueGoodTable/index.vue'
import { sweetAlertNotification, sweetAlertConfirmation } from '@js/helpers/sweetAlert.js';
    export default {
        name:'Completed Tasks',
        components: {
            VueGoodTable,
            Modal
        },
        props:{
            tasks:[Array, Object]
        },
        emits:['restoredTasks'],
        data(){
            return{
                columns: [
                    {
                        label: 'Action',
                        field: 'action',
                    },
                    {
                        label: 'Task',
                        field: 'name',
                        width: '200px'
                    },
                    {
                        label: 'Description',
                        field: 'description',
                        width: '200px'
                    },
                    {
                        label: 'Start Date',
                        field: 'start_date_text',
                        width: '230px'
                    },
                    {
                        label: 'Completion Date',
                        field: 'completion_date_text',
                        width: '230px'
                    },
                    {
                        label: 'Due Date',
                        field: 'due_date_text',
                        width: '230px'
                    },
                ],
                data:[],
                selectedRows:false
              
            }
        },
        computed:{
            hasRestoreButton(){
                return this.selectedRows.length;
            }
        },
        methods:{
            async restore(taskId){
                try {
                    const response = await apiClient.put(`/api/tasks/restore/${taskId}`);
                    if(response.status == 200){
                        const index = this.data.findIndex(item => item.id ==  taskId);
                        if (index !== -1) {
                            const task = this.data.splice(index, 1);
                            this.$emit('restoredTasks',task, true)
                            sweetAlertNotification("Task Restored", "Revert to Ongoing Status", "success");
                        }
                    }
                } catch (error) {
                    
                }
            },
            
            async restoreConfirmation(){
                const result = await sweetAlertConfirmation({}, 'Restore Tasks');
                if(result.isConfirmed){
                    await this.updateRestoredTaskStatus();
                }
            },

            async updateRestoredTaskStatus() {
                const taskIds = this.selectedRows.map(row => row.id);
                try {
                    const response = await axios.post('/api/tasks/update-tasks', {taskIds});

                    if (response.status === 200) {
                        taskIds.forEach(taskId => {
                            const index = this.data.findIndex(item => item.id === taskId);
                            if (index !== -1) {
                                this.data.splice(index, 1);
                            }
                        });
                        this.$emit('restoredTasks', this.selectedRows, true);
                        sweetAlertNotification(response.data.message);
                    }
                } catch (error) {
                    sweetAlertNotification("Error restoring tasks. Please try again.", "", "error");
                }
            },


            onSelectedRowsChange({selectedRows}){
                this.selectedRows = selectedRows;
            }
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