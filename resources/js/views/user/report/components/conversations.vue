<template>
    <Modal id="report-conversation-modal" title="Conversation" modal_size="modal-lg">
        <template #content>
            <div class="comment">
                <h5>{{ comment.author }}</h5>
                <p>{{ comment.text }}</p>
                <small>{{ formatDate(comment.date) }}</small>
            </div>
        </template>
    </Modal>
  </template>
  
  <script>
import Modal from '@js/components/Modal.vue';
  export default {
    name: 'CommentsList',
    props: {
        comment: {
        type: Object,
        required: true
        }
    },
    
    data() {
      return {
        
      };
    },
    components:{
        Modal
    },
    methods: {
        formatDate(date) {
        return new Date(date).toLocaleDateString();
        }
    },
    computed: {
      commentsText() {
        return this.comments.map(comment => {
          const side = comment.author === 'Alice' ? 'Right' : 'Left';
          return `${side} (${comment.author}): ${comment.text}`;
        }).join('\n');
      }
    }
  };
  </script>
  
  
<style scoped>
.comment {
  padding: 10px;
  margin-bottom: 10px;
  border-radius: 5px;
}
.comment h3 {
  margin: 0;
}
.comment small {
  color: #888;
}
</style>