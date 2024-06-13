<template>
  <Modal id="report-conversation-modal" title="Conversation" modal_size="modal-lg">
    <template #content>
      <div class="d-flex justify-content-end align-items-center" title="Actions">
        <button class="btn btn-sm" type="button" @click="getConversations"><i class="bi bi-arrow-clockwise"  title="Refresh conversation" style="font-size:18px;"></i></button>
        <button class="btn btn-sm" type="button" @click="reviewNewestReply"><i class="bi bi-chevron-down"  title="Go to newest message" style="font-size:18px;"></i></button>
      </div>  
      <div class="comment my-2 comment-container  border rounded p-3" ref="commentContainerRef">
        <!-- <img :src="reply.avatar" class="avatar" alt="User Avatar" /> -->
        <div class="comment-info" v-for="reply in data" :key="reply.id">
          <span class="fw-bold">{{ reply.name }}</span>
          <p>{{ reply.description }}</p>
          <p class="text-secondary text-end mt-3">{{ reply.created_at }}</p>
        </div>
      </div>
      <reply :id="id" inConversation  @fetch-conversations="handleUpdateCoversation"> </reply>
    </template>
  </Modal>
</template>
  
  <script>
import Modal from '@js/components/Modal.vue';
import reply from './reply.vue';
import formatter from '@js/helpers/formatter.js';
import apiClient from '@js/helpers/apiClient.js';

  export default {
    name: 'CommentsList',
    props: {
        replies: {
          type: Array,
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
        reportId:null,
        data:null
      };
    },
 
    methods: {
      handleUpdateCoversation(replies){
        this.data = replies;
        this.reviewNewestReply();
      },

      reviewNewestReply(){
        this.$nextTick(() =>{
          const commentContainer = this.$refs.commentContainerRef;
          if (this.data && this.data.length > 0) {
            commentContainer.scrollTop = commentContainer.scrollHeight;
          }
        })
      },
      
      formatConversation(data){
          return {
              ...data,
              created_at: formatter.formatReadableDateTime(data.created_at)
          }
      },

      async getConversations(){
          try {
              const response = await apiClient.get(`/api/reports/replies`, {params:{ reportId: this.id} })
              if(response.status == 200){
                  const { replies } = response.data;
                  this.data = replies.map(reply => this.formatConversation(reply));
              }
          } catch (error) {
              
          }
      },
    },

    watch:{
      replies: {
        deep: true,
        handler(updatedReplies) {
          this.data = updatedReplies;
        }
      }
    }
  };
  </script>
  
  
  <style scoped>
  .comment-container {
    max-height: 400px; /* Adjust this value as needed */
    overflow-y: auto; /* Add scrollbar when content exceeds max-height */
  }
  </style>