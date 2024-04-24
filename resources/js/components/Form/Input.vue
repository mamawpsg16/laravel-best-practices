
<template>
    <input :type="type" :value="modelValue" v-bind="$attrs" class="input" @input="$emit('update:modelValue', $event.target.value)"/>
</template>
<script>
export default {
    props: {
        modelValue:[String, Number, Boolean],
        type:{
            type:String,
            default:'text'
        },
        classes: {
            type: Object,
            default: () => ({})
        }
    },
  emits: ['update:modelValue'],

  mounted(){
    // console.log(this.$attrs,'this.$attrs');
  }
}
</script>

