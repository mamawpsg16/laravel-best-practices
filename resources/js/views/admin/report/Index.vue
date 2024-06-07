<template>
  <div class="mt-5">
    <VueGoodTable :columns="columns" :tableActionHeader="true" :rows="data" ref="vueGoodTable" >
        <template #table-action-content>
            <div class="d-flex justify-content-center align-items-center border border-dark-subtle rounded" title="Actions">
                <button class="btn btn-sm" type="button" @click="getData(true)"><i class="bi bi-arrow-clockwise"  title="Reset Filter" style="font-size:18px;"></i></button>
                <button class="btn btn-sm" type="button" @click="showFilter"><i class="bi bi-funnel-fill" title="Filter" style="font-size:18px;"></i></button>
                <button class="btn btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-cloud-download-fill" title="Export" style="font-size:18px;"></i></button>
                <ul class="dropdown-menu">
                    <li><button class="dropdown-item" type="button" @click="handleExport('sheet')">CSV</button></li>
                    <li><button class="dropdown-item" type="button" @click="handleExport('pdf')">PDF</button></li>
                </ul>
               
            </div>
        </template>
        <template #content="{ row, column, index, formattedRow }">
        <span v-if="column.field == 'action'" class="d-flex justify-content-center">
          <button class="btn btn-sm"><i class="bi bi-eye action"></i></button>
        </span>
        <span v-else>
            {{row[column.field]}}
        </span>
        </template>
    </VueGoodTable>
  </div>
  <filterModal @load-filtered-users="loadFilteredUsers" :isResetClicked="filterReset"/>
</template>

<script>
import VueGoodTable from '@js/components/VueGoodTable/index.vue';
import formatter  from '@js/helpers/formatter.js';
import apiClient from '@js/helpers/apiClient.js';
import { sweetAlertNotification, sweetAlertConfirmation, SwalDefault } from '@js/helpers/sweetAlert.js';
import filterModal from './components/filter.vue';
import exporter from '@js/helpers/export.js';
import { customDeepClone } from '@js/helpers/clone.js';
    export default {
        name:'Users Index',
        components: {
            VueGoodTable,
            filterModal
        },
        data() {
            return {
                columns: [
                    {
                        label: 'Action',
                        field: 'action',
                        width: '130px',
                    },
                    {
                        label: 'Email Address',
                        field: 'email',
                        width: '200px',
                    },
                    {
                        label: 'Type',
                        field: 'report_type',
                        width: '200px',
                    },
                    {
                        label: 'Subject',
                        field: 'title',
                        width: '200px',
                    },
                    {
                        label: 'Description',
                        field: 'description',
                        width: '200px',
                    },
                    {
                        label: 'Created At',
                        field: 'created_at',
                        width: '230px',
                    },
                    {
                        label: 'Last Updated',
                        field: 'updated_at',
                        width: '230px',
                    },
                ],
                data: [],
                filterReset:false
            }
        },
        created(){
            this.getData();  
        },
        methods:{
            calculateBanDetails(bannedAt, banDuration) {
                // Convert bannedAt to a Date object
                const start = new Date(bannedAt);

                // create a new date based in start variable
                const end = new Date(start);

                // set end date to start date + banDuration which is casts as integer 
                end.setDate(start.getDate() + parseInt(banDuration));
                // Get today's date
                const today = new Date();
               
                // Calculate the remaining days until the ban ends
                const remainingDays = Math.ceil((end - today) / (1000 * 60 * 60 * 24));

                return {
                    banEndDate: end.toISOString().split('T')[0],
                    banEndDateToSql: end,
                    remainingDays: remainingDays > 0 ? remainingDays : 0,
                };
            },

            formatData(data){
                return {
                    ...data,
                    title: formatter.capitalizeFirstLetter(data.title), 
                    report_type: formatter.capitalizeFirstLetter(data.report_type), 
                    created_at: formatter.formatReadableDateTime(data.created_at), 
                    updated_at: formatter.formatReadableDateTime(data.updated_at), 
                }
            },  

            async getData(reset = false) {
                try {
                    const response = await apiClient.get('/api/admin/reports');
                    if(response.status == 200){
                        if(reset){
                            this.filterReset = true;
                        }
                        const { data } = response?.data;
                        this.data = data.map(details => this.formatData(details));
                    }
                } catch (error) {
                    console.error(error);
                }
            },

            async banDetails(row, index){
               if(!row.banned_at){
                    this.banUser(row, index);
                    return
               }
               this.banConfirmation(row, index)
            },


            showFilter(){
                const element = document.querySelector('#user-filter-modal');
                const modal = bootstrap.Modal.getOrCreateInstance(element);
                modal.show();
            },

            loadFilteredUsers(data){
                this.filterReset = false;
                this.data = data.map(details => this.formatData(details));
            },

            exportSheet(data, headers){
                const fileName = `USERS_${formatter.formatNumericDateTime(new Date())}`;
                exporter.toCsv(data, headers, 'Users', fileName)
            },

            exportPdf(data, headers){
                exporter.toPdf(data, headers)
            },

            handleExport(type){
                if(!this.data.length){
                    sweetAlertNotification("No available data to export", "", "info");
                    return;
                }
                const columns = this.columns.filter(column => column.label !== 'Action');
                const clonedColumns = customDeepClone(columns);
                const headers = clonedColumns.map(column => column.label);
                const fields  = clonedColumns.map(column => column.field);
                const clonedData = customDeepClone(this.data);  
                const data = formatter.extractFieldsFromObjects(fields, clonedData)
                if(type == 'sheet'){
                    this.exportSheet(data, headers)
                }else{
                    this.exportPdf(data, headers);
                }
            }
        },
    }
</script>

<style lang="scss" scoped>

</style>