<template>
    <ckeditor  :editor="editor" v-model="editorData" :config="editorConfig"></ckeditor>
</template>

<script>
import CKEditor from '@ckeditor/ckeditor5-vue';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import {config} from './config.js';
    export default {
        props:{
            config:{
                type:[Object],
                default:{}
            },
        },
        components:{
            ckeditor: CKEditor.component
        },
        data(){
            return{
                editor: ClassicEditor,
                editorData: "",
                editorConfig: config
            }
        },

        watch:{
        

            config(config){
                const count = Object.keys(config).length;
                if(count){
                    this.editorConfig = config
                }
            },
        }
    }
</script>

<style lang="scss" scoped>

</style>