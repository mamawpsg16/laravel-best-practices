
<template>
    <textarea  ref="textareaRef"  :rows="rows" :cols="cols" v-bind="$attrs" v-model="content" class="form-control" @input="updateValue">
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
  data() {
      return {
          content: this.modelValue
      };
  },
  emits: ['update:modelValue'],
  methods: {
    updateValue(event) {
      this.$emit('update:modelValue', event.target.value);
    },
  },
  watch: {
      modelValue(newValue) {
          this.content = newValue;
      }
  },
}
</script>

<style scoped>
#text-area {
    text-align: left;
    vertical-align: top;
    padding: 0;
    margin: 0;
}
</style>
