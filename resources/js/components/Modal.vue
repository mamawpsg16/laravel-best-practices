<template>
  <Teleport to="#modals">
    <div class="modal fade" :class="modal_size" :id="id" tabindex="-1":aria-labelledby="`${id}Label`" aria-hidden="true" :data-bs-backdrop="enableBackdrop ? 'static' : null" :data-bs-keyboard="disableEscape">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" >{{  title }}</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" style="margin: 0px; padding-top: 0px;" >
            <slot name="content"></slot>
          </div>
          <div class="modal-footer" v-if="footer">
            <slot name="footer"></slot>
          </div>
        </div>
      </div>
    </div>
  </Teleport>
</template>
<script>
export default {
  props: {
    id:{
        type: String,
        required: true,
        default:'modal'
    },
    title: {
      type: String,
      default: 'Create'
    },
    footer:{
      type:Boolean,
      default:false
    },
    modal_size:{
      type:String,
      default:'modal-md'
    },
    enableBackdrop:{
      type:Boolean,
      default:false
    },
    disableEscape:{
      type:Boolean,
      default:true
    }
  },
}
</script>
<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.25s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.modal-enter-to,
.modal-leave-from {
  opacity: 1;
}
</style>