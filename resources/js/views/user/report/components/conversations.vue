<template>
    <Modal id="report-conversation-modal" title="Conversation" modal_size="modal-lg">
        <template #content>
            <div v-for="reply in replies" :key="reply.id">
              {{ reply.description }}
            </div>
            <reply :id="reportId"> </reply>
        </template>
    </Modal>
  </template>
  
  <script>
import Modal from '@js/components/Modal.vue';
import reply from './reply.vue';

  export default {
    name: 'CommentsList',
    props: {
        replies: {
          type: Object,
          required: true
        },
        id:{
          type:Number,
          default:null
        }
    },
    components:{
        Modal,
        reply
    },
    data() {
      return {
        reportId:null
      };
    },
 
    methods: {
        formatDate(date) {
        return new Date(date).toLocaleDateString();
        }
    },
    watch:{
        id(newId){
            if(newId){
                this.reportId = newId;
            }
        },
    }
  };
  </script>
  
  
<style scoped>
.reply {
  padding: 10px;
  margin-bottom: 10px;
  border-radius: 5px;
}
.reply h3 {
  margin: 0;
}
.reply small {
  color: #888;
}
</style>