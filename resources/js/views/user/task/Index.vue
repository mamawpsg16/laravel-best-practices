<template>
  <Create @update-list="taskAdded"></Create>
  <Edit @update-task="taskUpdated" :data="data"></Edit>
  <CompletedTasks :tasks="completed" @restored-tasks="taskAdded" ></CompletedTasks>
  <div class="row mt-3">
    <div class="d-flex justify-content-end mt-2">
      <button type="button" class="btn btn-primary text-white me-2" @click="createTask" title="Add New Task">
       <i class="bi bi-plus-circle"></i>
      </button>
      <button type="button" class="btn btn-primary text-white me-2" @click="showCompletedTasks" title="View Completed Tasks">
       <i class="bi bi-list-check"></i>
      </button>
      <button type="button" class="btn btn-info text-white me-2" v-if="showSaveOrderBtn && (pendingCount || ongoingCount)" @click="updateTasksOrder"  title="Save order changes">
       <i class="bi bi-floppy"></i>
      </button>
    </div>
    <div class="col-md-6">
      <div class="d-flex justify-content-between align-items-center">
        <h3 class="text-lg font-semibold">Pending</h3>
        <span class="text-right">Count : {{ pendingCount }}</span>
      </div>
      <div class="col-md-12 mb-2" style="max-height: calc(100vh - 180px); overflow-y: auto;">
        <Draggable :list="pending" Itemkey="pending" group="task" :withButtons="true" @add="updateStatus(0, $event)" @change="orderChanged = true"  @edit-item="editItem">
        </Draggable>
      </div>
    </div>
    <div class="col-md-6">
      <div class="d-flex justify-content-between align-items-center">
        <h3 class="text-lg font-semibold">Ongoing</h3>
        <span class="text-right">Count : {{ ongoingCount }}</span>
      </div>
      <div class="col-md-12 mb-2" style="max-height: calc(100vh - 180px); overflow-y: auto;">
        <Draggable :list="ongoing" Itemkey="ongoing" group="task" :withButtons="true" @add="updateStatus(1,$event)" @change="orderChanged = true" @edit-item="editItem" @updateCompletedTasks="updateCompletedTasks"></Draggable>
      </div>
    </div>
  </div>
</template>
<script>
import Create from './Create.vue';
import Edit from './Edit.vue';
import { sweetAlertNotification } from '@js/helpers/sweetAlert.js'
import formatter  from '@js/helpers/formatter.js';
import CompletedTasks from './Components/Completed.vue';
import Draggable from '@js/components/Draggable/Index.vue';
import apiClient from '@js/helpers/apiClient.js';
export default {
  name: "Task Index",
  components: {
    Create,
    Draggable,
    CompletedTasks,
    Edit
  },
  data() {
    return {
      showModal: false,
      pending: [],
      ongoing: [],
      completed: [],
      data:[],
      statuses:{0:'Pending', 1: 'Ongoing'},
      item_index:null,
      item_key:null,
      orderChanged:false
    };
  },
  async created(){  
    await this.getData();
  },
  computed:{
    pendingCount(){
      return this.pending.length;
    },
    ongoingCount(){
      return this.ongoing.length;
    },
    showSaveOrderBtn(){
      return this.orderChanged;
    }
  },

  methods: {
    formatData(data){
  
      return {
          ...data,
          status:{label: this.statuses[data.status], value:data.status}
        } 
    },

    async updateStatus(status, event) {
      try {
        const index = event.newIndex;
        const tasks = this[this.statuses[status].toLowerCase()];
        const id = tasks[index].id;
        const response = await apiClient.post('/api/tasks/update-status', {id:id, status:status});
      } catch (error) {
        console.log('Update Status error: ' + error);
      }
    },

    async updateTasksOrder() {
     try {
      const ongoing = this.formatTasksOrder(this.ongoing);
      const pending = this.formatTasksOrder(this.pending);
      const data = [...ongoing, ...pending];
      
      const response = await apiClient.post('/api/tasks/update-order',{tasks:data})
      if(response.status == 200){
        sweetAlertNotification("Order Updated", "Tasks Orders Updated Succesfully", "success", true, { width:380 });
        this.orderChanged = false;
      }
     } catch (error) {
      console.log('updateTasksOrder error: ' + error);
     }
    },

    formatTasksOrder(tasks){
      if(tasks.length){
        const data = tasks.map((task, index) =>({
          id:task.id,
          order: index + 1,
        }))
        return data;
      }
      return [];
    },

    taskAdded(data, restore = false){
      if(!restore){
        this[this.statuses[data.status].toLowerCase()].unshift(data);
      }else{
        const mergeData = [...this.ongoing, ...data]
        this.ongoing = mergeData;
      }
    },

    taskUpdated(task){
      this[this.item_key][this.item_index] = task;
    },

    updateCompletedTasks(task){
      const formattedTask = {
        ...task,
        due_date_text: formatter.formatReadableDateTime(task.due_date),
        start_date_text: formatter.formatReadableDateTime(task.start_timestamp),
        completion_date_text: formatter.formatReadableDateTime(task.end_timestamp),
      }
      this.completed.unshift(formattedTask);
    },

    editItem(task, index, key){
      const data = this.formatData(task);
      this.data = data;
      this.item_index = index;
      this.item_key = key;
      const modal_id = document.getElementById("edit-task-modal");
      const modal = bootstrap.Modal.getOrCreateInstance(modal_id);
      modal.show();
      this.getData();
    },

    createTask(){
      const modal_id = document.getElementById("create-task-modal");
      const modal = bootstrap.Modal.getOrCreateInstance(modal_id);
      modal.show();
    },

    showCompletedTasks(){
      const modal_id = document.getElementById("completed-tasks-modal");
      const modal = bootstrap.Modal.getOrCreateInstance(modal_id);
      modal.show();
    },

    handleCancel() {
      this.showModal = false
    },

    groupedData(data) {
      if(!data) return;
      // Initialize an empty map to hold the grouped data
      const groupDetails = new Map(Array.from({ length: 3 }, (_, i) => [i, []]));
      // Iterate over each item in the data
      for (const item of data) {
        const status = item.status;
        // Push the current item to the array corresponding to its status in the map
        if (groupDetails.has(status)) {
          groupDetails.get(status).push(item);
        }
      }
      return Object.fromEntries(groupDetails);
    },

    formatTaskData(task){
      return {
        ...task,
        due_date_text: formatter.formatReadableDateTime(task.due_date),
        start_date_text: formatter.formatReadableDateTime(task.start_timestamp),
        completion_date_text: formatter.formatReadableDateTime(task.end_timestamp),
      }
    },

    formatCompletedTasks(completedTasks){
      return completedTasks?.map(task => this.formatTaskData(task));
    },

    async getData() {
      try {
        const response = await apiClient.get('/api/tasks');
        const { data } = response?.data;
        const { '0': pending, '1': ongoing, '2': completed } = this.groupedData(data);
        this.pending = pending;
        this.ongoing = ongoing;
        this.completed = this.formatCompletedTasks(completed);
      } catch (error) {
        console.error(error);
      }
    },
  },
};
</script>
<style scoped>
</style>