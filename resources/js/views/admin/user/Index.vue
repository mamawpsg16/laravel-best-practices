<template>
  <div>
    <VueGoodTable :columns="columns" :rows="rows" ref="vueGoodTable" :searchOptions="{ enabled: true }" :selectOptions="{ enabled: true, disableSelectInfo:true, selectOnCheckboxOnly:true }" @selected-rows-change="onSelectedRowsChange">
        <template #content="{ row, column, index, formattedRow }">
        <span v-if="column.field == 'action'">
            <button class="btn btn-sm"><i class="bi bi-trash"></i></button>
            <button class="btn btn-sm"><i class="bi bi-pencil-square"></i></button>
            <button class="btn btn-sm"><i class="bi bi-person-add"></i></button>
        </span>
        <span v-else>
            {{row[column.field]}}
        </span>
        </template>
    </VueGoodTable>
  </div>
</template>

<script>
import VueGoodTable from '@js/components/VueGoodTable/index.vue';
    export default {
        name:'Users Index',
        components: {
            VueGoodTable
        },
        data() {
            return {
                columns: [
                    {
                    label: 'Action',
                    field: 'action'
                    },
                    {
                    label: 'Name',
                    field: 'name',
                    },
                    {
                    label: 'Age',
                    field: 'age',
                    type: 'number',
                    },
                    {
                    label: 'Created On',
                    field: 'createdAt',
                    },
                    {
                    label: 'Percent',
                    field: 'score',
                    type: 'decimal' 
                    },
                ],
                rows: [
                    { id:1, name:"John", age: 20, createdAt: '',score: 0.03343 },
                    { id:2, name:"Jane", age: 24, createdAt: '2011-10-31', score: 0.03343 },
                    { id:3, name:"Susan", age: 16, createdAt: '2011-10-30', score: 0.03343 },
                    { id:4, name:"Chris", age: 55, createdAt: '2011-10-11', score: 0.03343 },
                    { id:5, name:"Dan", age: 40, createdAt: '2011-10-21', score: 0.03343 },
                    { id:6, name:"John", age: 20, createdAt: '2011-10-31', score: 5 },
                ],
            }
        },
        methods:{
            onSelectedRowsChange({ selectedRows }){
                console.log('MAIN PAGE Selected Rows:', selectedRows);
            },
            onRowClicked(row) {
                console.log('MAIN PAGE  Row clicked:', row);
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>