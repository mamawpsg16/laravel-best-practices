<template>
      <Modal id="user-filter-modal" title="FILTER REPORT" modal_size="modal-lg">
        <template #content>
            <div class="d-flex justify-content-end align-items-center mt-3" v-if="isFetching">
                <div class="spinner-border text-secondary" style="width: 1.5rem; height: 1.5rem;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-6">
                    <label for="email" class="form-label">Creation Date</label>
                    <flat-pickr v-model="filters.date" class="form-control" :config="flatpickrConfig" placeholder="Select Creation Date"/>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Type</label>
                    <multiselect v-model="filters.selectedReportType" id="ajax" label="label" track-by="label" placeholder="Choose report type" :options="filters.options.reportTypes">
                        <template #noResult>
                            <span>Oops! No elements found. Consider changing the search query.</span>
                        </template>
                    </multiselect>
                </div>
            </div>
            
            <div class="row mb-2">
                <div class="col-md-6">
                    <label for="email" class="form-label">View Status</label>
                    <multiselect v-model="filters.viewStatus" deselect-label="Can't remove this value" track-by="label" label="label" placeholder="Select status" style="margin-bottom:10px;" :options="filters.options.viewStatus" :searchable="false"></multiselect>
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label">Resolve Status</label>
                    <multiselect v-model="filters.resolveStatus" deselect-label="Can't remove this value" track-by="label" label="label" placeholder="Select status" style="margin-bottom:10px;" :options="filters.options.resolveStatus" :searchable="false"></multiselect>
                </div>
            </div>
            
            <div class="d-flex justify-content-end align-items-center">
                <div id="right-section">
                    <button  type="button" class="btn btn-sm  bg-success-subtle px-3 ms-3" @click="handleFilter">Load</button>
                </div>
            </div>
        </template>
    </Modal>
</template>

<script>
import Modal from '@js/components/Modal.vue';
import Input from '@js/components/Form/Input.vue'
import { useVuelidate } from '@vuelidate/core'
import apiClient from '@js/helpers/apiClient.js';
import flatPickr from 'vue-flatpickr-component';
import Multiselect from 'vue-multiselect'
    export default {
        props:{
            isResetClicked:{
                type: Boolean,
                default: false
            }
        },
        setup () {
            return { v$: useVuelidate({ $autoDirty : true, $lazy: true}) }
        },
        components: {
            Modal,
            Input,
            flatPickr,
            Multiselect
        },
        async created(){
            await this.getReportTypes()
        },
        emits: ['loadFilteredUsers'],
        data(){
            return{
                filters:{
                    date:null,
                    status:null,
                    selectedReportType:[],
                    viewStatus:null,
                    resolveStatus:null,
                    options:{
                        reportTypes: [],
                        viewStatus: [
                            {label: 'Unread', value: 0},
                            {label: 'Reviewed', value: 1},
                        ],
                        resolveStatus: [
                            {label: 'Pending', value: 0},
                            {label: 'Under Investigation', value: 1},
                            {label: 'Resolved', value: 2},
                        ],
                    }
                },
                flatpickrConfig:{
                    altInput: true,
                    altFormat: "F j, Y",
                    dateFormat: "Y-m-d",
                    mode: "range",
                },
                isFetching:false
            }
        },
        methods:{
            async handleFilter(){
                this.isFetching = true;
                const reportType = this.filters.selectedReportType ? this.filters.selectedReportType.value : this.filters.selectedReportType;
                const viewStatus = this.filters.viewStatus ? this.filters.viewStatus.value : this.filters.viewStatus;
                const resolveStatus = this.filters.resolveStatus ? this.filters.resolveStatus.value : this.filters.resolveStatus;
                try {
                    const response = await apiClient.get('/api/reports',{ params:{ reportType:reportType, date:this.filters.date, viewStatus:viewStatus, resolveStatus: resolveStatus } } )
                    if(response.status == 200){
                        this.$emit('loadFilteredUsers', response.data.data)
                        this.isFetching = false;
                        const element = document.getElementById("user-filter-modal");
                        const modal = bootstrap.Modal.getOrCreateInstance(element);
                        modal.hide();
                    }
                } catch (error) {
                    
                }
            },

            async getReportTypes(){
                const response = await apiClient.get('/api/reports/types');
                if(response.status == 200) {
                    this.filters.options.reportTypes = response.data.options;
                }
            },

            resetFilter(){
                this.filters =  {
                    date:null,
                    status:null,
                    selectedReportType:[],
                    options:{
                        reportTypes: [],
                    }
                }
            },
        },

        watch: {
            isResetClicked(isReset) {
                if (isReset) {
                    this.resetFilter();
                }
            }
        },
    }
</script>

<style lang="scss" scoped>
</style>