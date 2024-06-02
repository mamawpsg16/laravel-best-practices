<template>
    <Modal id="completed-tasks-modal" title="Completed Tasks" modal_size="modal-lg" :enableBackdrop="true" :disableEscape="false">
        <template #content>
            <div class="my-2">
                <div class="d-flex justify-content-end mb-2">
                    <button v-if="hasRestoreButton" class="btn btn-md" @click="restoreConfirmation"><i class="bi bi-arrow-repeat"></i> Restore All</button>
                </div>
                <VueGoodTable :columns="columns" :rows="data" ref="vueGoodTable" :searchOptions="{ enabled: true }" :selectOptions="{ enabled: true, disableSelectInfo:true, selectOnCheckboxOnly:true }" @selected-rows-change="onSelectedRowsChange">
                    <template #content="{ row, column, index, formattedRow }">
                    <span v-if="column.field == 'action'">
                        <button  class="btn btn-md btn-secondary" title="restore task to ongoing" @click="restore(row.id)"><i class="bi bi-arrow-repeat"></i></button>
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
                    label: 'Task',
                    field: 'name'
                    },
                    {
                    label: 'Description',
                    field: 'description',
                    },
                    {
                    label: 'Start Date',
                    field: 'start_date',
                    },
                    {
                        
                    label: 'Completion Date',
                    field: 'completion_date_text',
                    },
                    {
                    label: 'Due Date',
                    field: 'due_date_text',
                    },
                    {
                    label: 'Action',
                    field: 'action',
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
                const result = await sweetAlertConfirmation();
                if(result.isConfirmed){
                    console.log('CONFIRMED');
                    await this.updateRestoredTaskStatus();
                }
            },

            async updateRestoredTaskStatus() {
                console.log('PASOK');
                const taskIds = this.selectedRows.map(row => row.id);
                const formData = new FormData();
                formData.append('taskIds', JSON.stringify(taskIds));
                
                try {
                    console.log('try PASOK');
                    const response = await axios.post('/api/test-test', {id:1}, {
                        headers: {
                            'Accept': 'application/json' // Expecting JSON response
                        }
                    });
                    console.log('API response:', response);

                    if (response.status === 200) {
                        taskIds.forEach(taskId => {
                            const index = this.data.findIndex(item => item.id === taskId);
                            if (index !== -1) {
                                this.data.splice(index, 1);
                            }
                        });
                        this.$emit('restoredTasks', this.selectedRows, true);
                        sweetAlertNotification("Tasks Successfully Restored");
                    }
                } catch (error) {
                    console.error('Error updating restored task status:', error);
                    sweetAlertNotification("Error restoring tasks. Please try again.");
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