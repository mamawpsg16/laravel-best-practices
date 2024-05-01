<template>
  <create @update-list="taskAdded"></create>
  <div class="row mt-3">
    <div class="d-flex justify-content-end mt-2">
     <button type="button" class="btn btn-primary me-2" @click="saveTasks">
       <i class="bi bi-floppy"></i>
     </button>
     <button type="button" class="btn btn-primary text-white" @click="createTask">
      <i class="bi bi-plus-circle"></i>
     </button>
    </div>
    <div class="col-md-4 mb-2">
      <h3 class="text-lg font-semibold">Pending</h3>
      <Draggable :list="pending" Itemkey="pending"></Draggable>
    </div>
    <div class="col-md-4">
      <h3 class="text-lg font-semibold">Ongoing</h3>
      <Draggable :list="ongoing" Itemkey="ongoing"></Draggable>
    </div>
    <div class="col-md-4 ml-4">
      <h3 class="text-lg font-semibold">Completed</h3>
      <Draggable :list="completed" Itemkey="completed"></Draggable>
    </div>
   
  </div>
</template>
<script>
import Create from './Create.vue';
import Draggable from '@js/components/Draggable/Index.vue';
export default {
  name: "Task Index",
  components: {
    Create,
    Draggable
  },
  data() {
    return {
      showModal: false,
      pending: [],
      ongoing: [],
      completed: []
    };
  },
  async created(){  
    await this.getData();
  },
  methods: {
    
    taskAdded(task){
      const status = {0:'pending', 1: 'ongoing', 2: 'completed'};
      this[status[task.status]].push(task);
    },

    createTask(){
      const modal_id = document.getElementById("create-task-modal");
      const modal = bootstrap.Modal.getOrCreateInstance(modal_id);
      modal.show();
    },

    saveTasks(){
    },

    handleCancel() {
      this.showModal = false
    },

    gropedData(data) {
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

    async getData(){
      await axios.get('/api/tasks').then((response)=>{
          const { data } = response.data;
          const { '0': pending, '1': ongoing, '2': completed } = this.gropedData(data);
          this.pending = pending;
          this.ongoing = ongoing;
          this.completed = completed;
       }).catch((error) =>{

      })
    },

   
    log: function(evt) {
      window.console.log(evt);
    }
  }
};
</script>