
<template>
    <textarea :rows="rows" :cols="cols" v-bind="$attrs" class="form-control" @input="$emit('update:modelValue', $event.target.value)"   @click="moveCursorToStart">
        {{ modelValue }}
    </textarea>
</template>
<script>
export default {
    props: {
        modelValue:[String, Number, Boolean],
        rows:{
            type:Number,
            default:5
        },
        cols:{
            type:Number,
            default:5
        },
    },
  emits: ['update:modelValue'],
  methods: {
    moveCursorToStart(event) {
      event.target.setSelectionRange(0, 0);
    },
  },
  mounted(){
  } 
}
</script>

