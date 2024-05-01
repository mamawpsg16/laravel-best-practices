<template>
     <draggable
        class="list-group mt-3"
        :list="list"
        :itemKey="Itemkey"
      >
        <template #item="{ element, index }">
          <div class="list-group-item bg-gray-100 p-2 my-1 rounded">
            {{ element.name }} {{ index }}
          </div>
        </template>
      </draggable>
</template>

<script>
import draggable from "vuedraggable";
    export default {
        props:{
            list:{
                type:[Object, Array],
                required: true
            },
            Itemkey:{
                String
            }
        },
        components: {
            draggable,
        },
    }
</script>

<style lang="scss" scoped>

</style>