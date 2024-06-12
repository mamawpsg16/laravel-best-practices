<template>
   <div class="mx-auto border border-gray-300 rounded-lg p-3 mt-5" >
        <div class="d-flex justify-content-between">
            <button class="btn btn-sm text-center mb-2 px-2" title="Conversations" @click="showConversation()"><i class="bi bi-envelope action" style="font-size:20px;"></i></button>
            <h4>Report</h4>
            <span></span>
        </div>
        <div class="mb-2">
            <label for="type" class="form-label">Type </label>
            <Input type="text" readonly v-model="details.report_type"></Input>
        </div>
        <div class="mb-2" v-if="isAccountType">
            <label for="email" class="form-label">Email </label>
            <Input type="text" readonly v-model="details.email"></Input>
        </div>
        <div class="mb-2">
            <label for="title" class="form-label">Title </label>
            <Input type="text" readonly v-model="details.title"></Input>
        </div>
        <div class="mb-2">
          <label for="password" class="form-label">Description </label>
          <TextArea v-model="details.description" readonly></TextArea>
        </div>
        <div class="mb-2">  
            <label for="" class="me-2">Uploaded Images</label>
            <button type="button" v-if="images.length" class="btn btn-sm" @click="show" title="Preview Images"><i class="bi bi-zoom-in" style="font-size:25px"></i></button>
            <span v-else class="fw-bold">No Uploaded Images</span>
            <viewer :images="images" @inited="inited" class="viewer" ref="viewer" :options="viewerOptions">
              <template #default="scope">
                <img v-for="src in scope.images" :src="src" :key="src" style="display:none !important"/>
              </template>
            </viewer>
        </div>
        <reply :id="details.id"> </reply>
        <conversations   :replies="replies" :id="details.id" v-if="details"/>
    </div>
</template>

<script>
import formatter  from '@js/helpers/formatter.js';
import Input from '@js/components/Form/Input.vue'
import apiClient from '@js/helpers/apiClient.js';
import TextArea from '@js/components/Form/TextArea.vue'
import CKEditor from '@ckeditor/ckeditor5-vue';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import { component as Viewer } from "v-viewer"
import { viewerOptions } from './viewerConfig.js';
import conversations from './components/conversations.vue';
import { checkValidity  } from '@js/helpers/Vuelidate.js';
import { useVuelidate } from '@vuelidate/core'
import reply from './components/reply.vue';
    export default {
        setup () {
            return { v$: useVuelidate({ $autoDirty : true, $lazy: true}) }
        },
        components:{
            Input,
            TextArea,
            ckeditor: CKEditor.component,
            Viewer,
            conversations,
            reply
        },
        data(){
            return{
                viewerOptions:viewerOptions,
                comments: [
                    { id: 1, author: 'Alice', text: 'This is a great post!', date: '2023-06-11' },
                    { id: 2, author: 'Bob', text: 'I learned a lot from this article.', date: '2023-06-10' },
                    { id: 3, author: 'Alice', text: 'Thanks for the feedback!', date: '2023-06-12' },
                    { id: 4, author: 'Bob', text: 'You\'re welcome!', date: '2023-06-13' },
                ],
                images:[],
                editor: ClassicEditor,
                editorData: "",
                editorConfig: {
                    toolbar: ['heading', '|', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote'],
                    heading: {
                        options: [
                            { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                            { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                            { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' }
                        ]
                    },
                    language: 'en',
                    placeholder: 'Start typing here...',
                },
                details:{},
                replies:[]
            }
        },
        computed:{
            isAccountType() {
                return this.details.type && this.details.type.account;
            },
        },
        async created(){
            await this.getData();
        },
        

        mounted() {
        },
        methods:{
            inited (viewer) {
                this.$viewer = viewer
            },

            show () {
                this.$viewer.show()
            },
            
            formatData(data){
                return {
                    ...data,
                    read_at: formatter.formatReadableDateTime(data.read_at),
                    resolved_at: formatter.formatReadableDateTime(data.resolved_at),
                    report_type: data.type.name,
                }
            },

            getAttachments(attachments){
                const images = attachments.map((attachment) => attachment.url);
                this.images = images
            },

            checkInputValidity(parentProperty = null, dataProperty, validations = []) {
                return checkValidity(this.v$, parentProperty, dataProperty, validations);
            },

            async getData(){
                const response = await apiClient.get(`/api/reports/${this.$route.params.uuid}`)
                if(response.status == 200){
                    const { data } = response.data;
                    const formattedData = this.formatData(data);
                    if(formattedData.attachments.length > 0){
                        this.getAttachments(formattedData.attachments);
                    }
                    this.details = formattedData;
                }
            },

            async getConversations(){
                try {
                    const response = await apiClient.get(`/api/reports/replies`, {params:{ reportId: this.details.id} })
                    if(response.status == 200){
                        const { replies } = response.data;
                        this.replies = replies;
s                    }
                } catch (error) {
                    
                }
            },

            async showConversation(){
                await this.getConversations();
                const modal_id = document.getElementById("report-conversation-modal");
                console.log(modal_id,'modal_id');
                const modal = bootstrap.Modal.getOrCreateInstance(modal_id);
                modal.show();
            },
        }
    }
</script>

<style lang="scss" scoped>

</style>