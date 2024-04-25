<template>
  <!-- <Modal id="testModal" title="testModal"/>
  <div class="d-flex justify-content-end mt-2">
    <button type="button" class="btn btn-primary" @click="openModal">
      <i class="fa-solid fa-plus"></i>
    </button>
  </div> -->
 <div class="row mt-3">
  <div class="col-md-4 mb-2">
    <h3 class="text-lg font-semibold mb-2">Create Task</h3>
    <Create/>
  </div>
  <div class="col-md-8">
    <div class="row">
      <div class="col-md-6">
        <h3 class="text-lg font-semibold mb-2">Pending/Ongoing</h3>
        <draggable
          class="list-group mt-4"
          :list="list1"
          group="task"
          @change="log"
          itemKey="name"
        >
          <template #item="{ element, index }">
            <div class="list-group-item bg-gray-100 p-2 mb-1 rounded">
              {{ element.name }} {{ index }}
            </div>
          </template>
        </draggable>
      </div>
      <div class="col-md-6 ml-4">
        <h3 class="text-lg font-semibold mb-2">Completed</h3>
        <draggable
          class="list-group mt-4"
          :list="list2"
          group="task"
          @change="log"
          itemKey="name"
        >
          <template #item="{ element, index }">
            <div class="list-group-item bg-gray-100 p-2 mb-1 rounded">
              {{ element.name }} {{ index }}
            </div>
          </template>
        </draggable>
      </div>
    </div>
  </div>
</div>
</template>
<script>
import draggable from "vuedraggable";
import Create from './Create.vue';
import Modal from '@js/components/Modal.vue';
import Swal from 'sweetalert2/dist/sweetalert2.js'

export default {
  name: "Task Index",
  display: "Two Lists",
  order: 1,
  components: {
    draggable,
    Create,
    Modal
    // rawDisplayer
  },
  data() {
    return {
      showModal: false,
      list1: [
        { name: "John", id: 1 },
        { name: "Joao", id: 2 },
        { name: "Jean", id: 3 },
        { name: "Gerard", id: 4 }
      ],
      list2: [
        { name: "Juan", id: 5 },
        { name: "Edgard", id: 6 },
        { name: "Johnson", id: 7 }
      ]
    };
  },
  mounted(){
    Swal.fire({
      title: 'Error!',
      text: 'Do you want to continue',
      icon: 'error',
      confirmButtonText: 'Cool'
    })
  },
  methods: {
    openModal(){
      const modal_id = document.getElementById("testModal");
      const modal = bootstrap.Modal.getOrCreateInstance(modal_id);
      modal.show();
    },

    handleCancel() {
      this.showModal = false
    },

   
    log: function(evt) {
      window.console.log(evt);
    }
  }
};
</script>