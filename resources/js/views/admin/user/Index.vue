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
            <button class="btn btn-sm" @click="banDetails(row, index)" :data='`ban-${index}`'><i :class="`bi ${!row.banned_at ? 'bi-ban' : 'bi-escape'}`" :title="`${!row.banned_at ? 'Ban user' : 'Unbanned user'}`"></i></button>
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
                        label: 'Name',
                        field: 'name',
                        width: '200px',
                    },
                    {
                        label: 'Email Address',
                        field: 'email',
                    },
                    {
                        label: 'Email Verified At',
                        field: 'email_verified_at',
                        width: '230px',
                    },
                    {
                        label: 'Provider',
                        field: 'provider',
                        width: '200px',
                    },
                    {
                        label: 'Provider ID',
                        field: 'provider_id',
                        width: '150px',
                    },
                    {
                        label: 'Banned At',
                        field: 'banned_at',
                        width: '230px',
                    },
                    {
                        label: 'Banned End At',
                        field: 'banned_date',
                        width: '230px',
                    },
                    {
                        label: 'Remaining Banned Days',
                        field: 'banned_remaining_days',
                        width: '230px',
                    },
                    {
                        label: 'Banned Description',
                        field: 'banned_description',
                        width: '230px',
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
                    provider: formatter.capitalizeFirstLetter(data.provider), 
                    created_at: formatter.formatReadableDateTime(data.created_at), 
                    banned_at: formatter.formatReadableDateTime(data.banned_at), 
                    updated_at: formatter.formatReadableDateTime(data.updated_at), 
                    email_verified_at: formatter.formatReadableDateTime(data.email_verified_at), 
                    banned_date: formatter.formatReadableDateTime(data.banned_end_at), 
                    banned_remaining_days: data.banned_days
                }
            },  

            async getData(reset = false) {
                try {
                    const response = await apiClient.get('/api/users');
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

            async banUser(row, index){
                await SwalDefault.fire({
                    title: 'Banning System',
                    html: `
                        <input id="ban-days" type="number" class="swal2-input" placeholder="Enter ban duration">
                        <input id="ban-description" type="text" class="swal2-input" placeholder="Enter ban description">
                    `,
                    showCancelButton: true,
                    confirmButtonText: "Confirm",
                    focusConfirm: false,
                    preConfirm: async () => {
                        const bannedDays = document.getElementById('ban-days').value;
                        const bannedDescripton = document.getElementById('ban-description').value;
                        if (!bannedDays.trim()) {
                            sweetAlertNotification('Please enter a ban duration', '', 'info', false);
                        }
                        return {bannedDays, bannedDescripton};
                    }
                }).then((result) => {
                    if (result.isConfirmed && result.value.bannedDays.trim()) {
                        const dateToday = formatter.formatNumericDate(new Date());
                        const { banEndDateToSql } = this.calculateBanDetails(dateToday, result.value.bannedDays);
                        result.value.bannedEndDate =formatter.formatToSql(banEndDateToSql)

                        this.banConfirmation(row, index, result.value)
                    }
                })
            },

            async banConfirmation(row, index, details = []){
                let title = `Unbanned ${row.name}`;
                let text = `Ban Description: ${row.banned_description}`;
                if(!row.banned_at){
                    const extension = parseInt(details.bannedDays) > 1 ? 'days' : 'day';
                    title = `Ban ${row.name} for ${details.bannedDays} ${extension}`
                    text = '';
                }
                const result = await sweetAlertConfirmation({},title, text);
                if(result.isConfirmed){
                    await this.ban(row.id, index, details);
                }
            },

            async ban(userId, index, details = []){
                const banIcon = 'bi-escape';
                const unbannedIcon = 'bi-ban';
                try {
                    const response = await apiClient.put(`/api/users/${userId}/ban`, {...details});
                    const { message, data } = response.data;
                    if(response.status == 200){
                        const iconElement  = document.querySelector(`button[data="ban-${index}"] > i`);
                        this.data[index] = this.formatData(data)
                        if(!data.banned_at){
                            iconElement.classList.remove(banIcon)
                            iconElement.classList.add(unbannedIcon)
                            iconElement.setAttribute('title','Ban user')
                        }else{
                            iconElement.classList.remove(unbannedIcon)
                            iconElement.classList.add(banIcon)
                            iconElement.setAttribute('title','Unbanned user')
                        }
                        sweetAlertNotification(message, "", "success");
                    }
                } catch (error) {
                    console.error(error);
                }
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