<template>
     <vue-good-table 
      :columns="columns"
      :rows="rows"
      :select-options="selectOptions"
      :search-options="searchOptions"
      @selected-rows-change="onSelectedRowsChange"
      @row-click="onRowClicked"
      :pagination-options="getPaginationOptions"
      :styleClass="classes"
      >
        <template v-if="tableActionHeader" #table-actions>
            <slot name="table-action-content"></slot>
        </template>
        <template #table-row="{ row, column, index, formattedRow }">
            <slot name="content" :row="row" :column="column" :index="index" :formattedRow="formattedRow"></slot>
        </template>
        <template v-if="tableActionFooter" #table-actions-bottom>
            <button class="btn btn-sm">BOTTOM</button>
        </template>
    </vue-good-table>
</template>

<script>
import { VueGoodTable } from 'vue-good-table-next';
    export default {
        emits:['selected-rows-change', 'row-click'],
        props:{
            rows:{
                type:[Array],
                default:[]
            },
            columns:{
                type:[Array],
                default:[]
            },
            selectOptions:{
                type:[Object],
                default:{}
            },
            searchOptions:{
                type:[Object],
                default:{
                    enabled: true,
                    skipDiacritics: true,
                    placeholder: 'Search...',
                }
            },
            paginationOptions:{
                type:[Object],
                default:{
                    enabled: true,
                    infoFn: (params) => `Showing ${params.firstRecordOnPage} to ${params.lastRecordOnPage} of ${params.totalRecords} entries - Page ${params.currentPage}`,
                }
            },
            classes:{
                type:[String],
                default:'vgt-table bordered striped'
            },
            tableActionHeader:{
                type:Boolean,
                default:false
            },
            tableActionFooter:{
                type:Boolean,
                default:false
            }
        },
        components: {
            VueGoodTable
        },
        computed:{
            getPaginationOptions(){
                return {enabled: true, ...this.paginationOptions}
            }
        },
        methods:{
            onSelectedRowsChange(rows){
                this.$emit('selected-rows-change',rows)
            },
            onRowClicked(row) {
                this.$emit('row-click', row)
                // params.row - row object 
                // params.pageIndex - index of this row on the current page.
                // params.selected - if selection is enabled this argument 
                // indicates selected or not
                // params.event - click event
            }
        }
    }
</script>

<style lang="scss" scoped>
.vgt-left-align{
    text-align:unset !important;
}
</style>