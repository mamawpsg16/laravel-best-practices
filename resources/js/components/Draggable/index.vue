<template>
     <draggable class="list-group mt-3" :list="data" :item-key="Itemkey" :group="group" ghost-class="ghost" :animation="200">
        <template #item="{ element, index }">
          <div class="list-group-item bg-gray-100 p-2 my-1 rounded" :data-id="element.id">
            <div class="d-flex justify-content-between">
              <p>{{ element.name }}</p>
              <template v-if="withButtons">
                <div>
                  <span class="btn btn-sm me-1"  @click="$emit('editItem', element, index, Itemkey)" title="Edit Task"><i class="bi bi-pencil-square"></i></span>
                  <span class="btn btn-sm" @click="deleteTask(element.id)"><i class="bi bi-trash" title="Delete Task"></i></span>
                  <span class="btn btn-sm" v-if="Itemkey == 'ongoing'" @click="markAsCompleted(element.id)"  title="Mark as completed"><i class="bi bi-check2-square"></i></span>
                </div>
              </template>
            </div>
            <div class="d-flex justify-content-between">
              <span>Description : {{ element.description }}</span>
              <span>Due Date : {{ element.due_date }}</span>
            </div>
          </div>
        </template>
      </draggable>
</template>

<script>
import axios from "axios";
import draggable from "vuedraggable";
import Swal from 'sweetalert2/dist/sweetalert2.js'
import { sweetAlertConfirmation, sweetAlertNotification } from "@js/helpers/sweetAlert.js";
    export default {
        props:{
            list:{
                type:[Object, Array],
                required: true
            },
            Itemkey:String,
            group:String,
            withButtons:{
              type:Boolean,
              default:false
            },
            item_index:{
              type:Number,
            }
        },
        emits:['editItem','updateCompletedTasks'],
        data(){
          return{
            drag:false,
            data:[]
          }
        },
        methods:{
          async deleteTask(id){
            if(this.Itemkey == 'ongoing'){
              const result = await sweetAlertConfirmation({
                allowOutsideClick:false,
                allowEscapeKey:false,
                allowEnterKey:false
              })
             
              if (result.isConfirmed) {
                this.delete(id)
                sweetAlertNotification("Task Successfully Deleted", '', 'success', { timer:2000, toast:true, position: "bottom-end", timerProgressBar: true,})
              } else if (result.isDenied) {
                sweetAlertNotification("Changes are not saved", '', 'info')
              }
            }else{
              this.delete(id);    
              sweetAlertNotification("Task Successfully Deleted", '', 'success', { timer:2000, toast:true, position: "bottom-end", timerProgressBar: true,})
            }
            
          },
          async markAsCompleted(id){
            const result = await sweetAlertConfirmation({
              allowOutsideClick:false,
              allowEscapeKey:false,
              allowEnterKey:false
            },"Mark this task as completed?");
            if (result.isConfirmed) {
              this.updateStatus(id)
              sweetAlertNotification("Task Successfully Completed", '', 'success', { timer:2000, toast:true, position: "bottom-end", timerProgressBar: true,})
            } else if (result.isDenied) {
              sweetAlertNotification("Changes are not saved", '', 'info')
            }
          },

          async delete(id){
            await axios.delete(`/api/tasks/${id}`).then((response) => {
                const index = this.data.findIndex(item => item.id === id);
                if (index !== -1) {
                    this.data.splice(index, 1);
                }
              }).catch((error) =>{
              })
          },

          async updateStatus(id){
            await axios.post('/api/tasks/update-status',{id:id, status:2}).then((response) =>{
              const index = this.data.findIndex(item => item.id === id);
              if (index !== -1) {
                const task = this.data.splice(index, 1);
                this.$emit('updateCompletedTasks', response.data.data)
              }
            }).catch((err) =>{
              console.log(err);
            });
          },

    

          async edit(id){
            await axios.put(`/api/tasks/${id}`).then((response) => {
               
              }).catch((error) =>{
              })
          }
        },
        components: {
            draggable,
        },
        watch: {
          list: {
            handler(tasks, oldValue) {
              this.data = tasks;
            },
          },
          
        }
    }
</script>

<style lang="scss" scoped>

</style>