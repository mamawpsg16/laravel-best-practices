<template>
      <Modal id="user-filter-modal" title="FILTER USERS" modal_size="modal-lg">
        <template #content>
            <div class="d-flex justify-content-end align-items-center mt-3" v-if="isFetching">
                <div class="spinner-border text-secondary" style="width: 1.5rem; height: 1.5rem;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="email" class="form-label">Creation Date</label>
                    <flat-pickr v-model="filters.date" class="form-control" :config="flatpickrConfig" placeholder="Select Creation Date"/>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Name/Email</label>
                    <multiselect v-model="filters.selectedUsers" id="ajax" label="label" track-by="label" placeholder="Type to search" :options="filters.options.users" v-bind="multiselectProps" @search-change="handleSearchUserChange">
                        <template #noResult>
                            <span>Oops! No elements found. Consider changing the search query.</span>
                        </template>
                    </multiselect>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label for="email" class="form-label">Status</label>
                    <multiselect v-model="filters.status" deselect-label="Can't remove this value" track-by="label" label="label" placeholder="Select status" style="margin-bottom:10px;" :options="filters.options.status" :searchable="false"></multiselect>
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
import callOptimization from '@js/helpers/callOptimization.js';
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
        emits: ['loadFilteredUsers'],
        data(){
            return{
                filters:{
                    date:null,
                    status:null,
                    selectedUsers:[],
                    options:{
                        status: [
                            {label: 'Banned', value: 1},
                            {label: 'Unbanned', value: 0},
                        ],
                        users: [],
                    }
                },
                flatpickrConfig:{
                    altInput: true,
                    altFormat: "F j, Y",
                    dateFormat: "Y-m-d",
                    mode: "range",
                },
                multiselectProps: {
                    openDirection: 'bottom',
                    multiple: true,
                    searchable: true,
                    clearOnSelect: false,
                    closeOnSelect: false,
                    optionsLimit: 50,
                    internalSearch:false,
                    showNoResults: false,
                    hideSelected:true
                },
                isFetching:false
            }
        },
        methods:{
            async handleFilter(){
                this.isFetching = true;
                const bannedStatus = this.filters.status ? this.filters.status.value : this.status;
                try {
                    const response = await apiClient.get('/api/admin/users',{ params:{ bannedStatus:bannedStatus, date:this.filters.date, userIds: this.filters.selectedUsers?.map(user => user.value)  } } )
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
            
            
            handleSearchUserChange: callOptimization.throttle(async function (query) {
                if(!query){
                    this.filters.options.users = [];
                    return false
                }

                // your async operations, e.g., API calls
                const response = await this.findUser(query);
                if(response.status == 200){
                    this.filters.options.users = response.data.data;
                }
            }, 500),

            async findUser(query){
                try {
                    return await apiClient.get('/api/admin/users',{ params:{ search: query  } } )
                } catch (error) {
                    
                }
            },

            resetFilter(){
                this.filters = {
                    date:null,
                    status:null,
                    selectedUsers:[],
                    options:{
                        status: [
                            {label: 'Banned', value: 1},
                            {label: 'Unbanned', value: 0},
                        ],
                        users: [],
                    }
                }
            }
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