<template>
    <div id="div-upload-loading" class="vs-con-loading__container">
        <ValidationObserver v-slot="{ handleSubmit }">
            <form @submit.prevent="handleSubmit(submitFile)" enctype="multipart/form-data">
                <div class="vx-row mb-1">
                    <div class="vx-col w-full">
                        <h6 class="mr-2 mt-3">Dataset Name</h6>
                    </div>
                    <div class="vx-col w-full">                        
                        <ValidationProvider name="Name" rules="required" v-slot="{ errors }"> 
                            <vs-input class="w-full"
                                v-model="name"/>    
                            <span>{{ errors[0] }}</span>
                        </ValidationProvider> 
                    </div>
                </div>
                <div class="vx-row mb-1">
                    <div class="vx-col w-full">
                        <h6 class="mr-2 mt-3">Category</h6>
                    </div>
                    <div class="vx-col w-full">                   
                        <ValidationProvider name="Category" rules="required" v-slot="{ errors }"> 
                            <v-select 
                                class="bg-white mt-1 w-full" 
                                v-model="select_category"
                                :options="category" :dir="$vs.rtl ? 'rtl' : 'ltr'" />  
                            <span>{{ errors[0] }}</span>
                        </ValidationProvider> 
                    </div>
                </div>
                <div class="vx-row mb-1">
                    <div class="vx-col w-full">
                        <div>
                            <h6 class="mr-2 mt-3">Sub Category</h6>           
                            <ValidationProvider name="Subcategory" rules="required" v-slot="{ errors }"> 
                                <v-select class="bg-white mt-1 w-full" 
                                    taggable 
                                    push-tags
                                    v-model="select_subcategory"
                                    :options="subcategory[category_selected]" :dir="$vs.rtl ? 'rtl' : 'ltr'" />    
                                <span>{{ errors[0] }}</span>
                            </ValidationProvider> 
                        </div>
                    </div>                
                </div>
                <div class="vx-row mb-1">
                    <div class="vx-col w-full">
                        <h6 class="mr-2 mt-3">Tagging</h6>
                    </div>
                    <div class="vx-col w-full">                          
                        <ValidationProvider name="Tagging" rules="required" v-slot="{ errors }"> 
                            <b-form-tags
                                input-id="tags-remove-on-delete tags-basic"
                                :input-attrs="{ 'aria-describedby': 'tags-remove-on-delete-help' }"
                                v-model="tagging"
                                separator=" "
                                placeholder="Add tag..."
                                remove-on-delete
                                no-add-on-enter
                            ></b-form-tags>        
                            <span>{{ errors[0] }}</span>
                        </ValidationProvider> 
                    </div>
                </div>
                <div class="vx-row mb-3">
                    <div class="vx-col w-full">
                        <h6 class="mr-2 mt-3">Description</h6>
                    </div>
                    <div class="vx-col w-full">                                    
                        <ValidationProvider name="Description" rules="required" v-slot="{ errors }"> 
                            <vs-textarea 
                                class="bg-white mt-1"
                                v-model="textarea" />       
                            <span>{{ errors[0] }}</span>
                        </ValidationProvider> 
                    </div>
                </div>
                <div class="vx-row mb-1">
                    <div class="vx-col w-full">                                
                        <ValidationProvider name="File" rules="required|array" v-slot="{ errors }"> 
                            <b-form-file
                            v-model="file"
                            :state="Boolean(file)"
                            placeholder="Choose a file or drop it here..."
                            drop-placeholder="Drop file here..."
                            name="file"
                            multiple 
                            ></b-form-file>    
                            <span>{{ errors[0] }}</span>
                        </ValidationProvider> 
                        <!-- <div class="mt-3">Selected file: {{ file ? file.name : '' }}</div> -->
                    </div>
                </div>
                <div class="vx-row">
                    <div class="vx-col w-full mt-3">
                        <button class="float-right button-submit" type="submit">Upload</button>
                    </div>
                </div>
            </form>
        </ValidationObserver>
    </div>
</template>

<script>
import {
  BFormFile,
  BFormTags
} from 'bootstrap-vue'

// vee validate
import { ValidationProvider, ValidationObserver, extend } from 'vee-validate';

extend('required', {
  validate(value) {
    return {
      required: true,
      valid: ['', null, undefined].indexOf(value) === -1
    };
  },
  computesRequired: true
});
extend('min', {
  validate(value, args) {
    return value.length >= args.length;
  },
  params: ['length']
});
extend('array', {
  validate(value) {
    return value.length > 0;
  },
  message: 'File cannot be null or empty.'
});

import { SidebarMenu } from 'vue-sidebar-menu'
import vSelect from 'vue-select'

const axios = require('axios');

export default {
    mounted() {
        // console.log('Component mounted.')
    },
    data() {
        return {
            name: '',
            file: [],
            textarea: '',
            category: [
                {label: 'Migas', value: 'migas'}, 
                {label: 'Batu Bara', value: 'batubara'}, 
                {label: 'Panas Bumi', value: 'panasbumi'}
            ],
            subcategory: {
                    empty:[],
                    subcategory_migas:[
                        {label: 'Sejarah Eksplorasi dan Data Umum', value: 'sejaraheksplorasidandataumum'}, 
                        {label: 'Batuan Induk', value: 'batuaninduk'}, 
                        {label: 'Batuan Reservoar', value: 'batuanreservoar'},
                        {label: 'Batuan Penyekat/Seal', value: 'batuanpenyekatseal'}, 
                        {label: 'Jebakan Migas/Traps', value: 'jebakanmigastraps'},
                        {label: 'Migrasi', value: 'migrasi'}, 
                        {label: 'Sumber Daya', value: 'sumberdaya'},
                        {label: 'Cadangan', value: 'cadangan'}, 
                        {label: 'Produksi', value: 'produksimigas'},
                        {label: 'Cadangan Tersisa', value: 'cadangantersisa'}
                    ],
                    subcategory_batu_bara:[
                        {label: 'Data Lokasi', value: 'datalokasibb'},
                        {label: 'Formasi Pembawa Batubara', value: 'formasipembawabatubarabb'}, 
                        {label: 'Lapisan Batubara', value: 'lapisanbatubarabb'},
                        {label: 'Data Karakteristik', value: 'datakarakteristikbb'}, 
                        {label: 'Sumberdaya dan Cadangan', value: 'sumberdayadancadanganbb'},
                        {label: 'Produksi', value: 'produksibb'}
                    ],
                    subcategory_panas_bumi:[
                        {label: 'Data Lokasi', value: 'datalokasipb'}, 
                        {label: 'Formasi Pembawa Panas Bumi', value: 'formasipembawaPanas Bumipb'}, 
                        {label: 'Lapisan Panas Bumi', value: 'lapisanPanas Bumipb'},
                        {label: 'Data Karakteristik', value: 'datakarakteristikpb'}, 
                        {label: 'Sumberdaya dan Cadangan', value: 'sumberdayadancadanganpb'}, 
                        {label: 'Produksi', value: 'produksipb'}
                    ]
            },
            category_selected: 'empty',
            tagging: [],
            select_category: '',
            select_subcategory: '',
            textarea: '',
        };
    },
    components: {
        BFormFile,
        BFormTags,
        ValidationProvider,
        ValidationObserver,
        'v-select': vSelect,
    },
    watch: {
        select_category(){
            if(this.select_category == null){
                this.category_selected = 'empty'   
                this.select_subcategory = null             
            }else{
                if(this.select_category.value == 'migas'){
                    this.category_selected = 'subcategory_migas'
                }else if(this.select_category.value == 'batubara'){
                    this.category_selected = 'subcategory_batu_bara'
                }else if(this.select_category.value == 'panasbumi'){
                    this.category_selected = 'subcategory_panas_bumi'
                }else{
                    this.category_selected = 'empty'
                }
            }            
        }
    },
    methods: {
        submitFile(){
            const config = {
                headers: { 'content-type': 'multipart/form-data' }
            }

            const formData = new FormData();
            // console.log(this.file);

            // formData.append('file', this.file);
            for (let fileData of this.file) {
                formData.append("file[]", fileData);
            }
            formData.append('username', this.$store.state.AppActiveUser.displayName);
            formData.append('category', this.select_category['value']);
            formData.append('subcategory', this.select_subcategory['label']);
            formData.append('file_name', this.name);
            formData.append('description', this.textarea);
            formData.append('tagging', this.tagging.join(';'));

            // console.log(this.file)
            this.$vs.loading({
                container: '#div-upload-loading',
                scale: 0.6
            })

            axios.post('/api/upload',formData).then(response => {
                // console.log(response);
                this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
                this.$vs.notify({
                    time:4000,
                    title: 'Succes',
                    text: 'file has been uploaded.',
                    color: 'success',
                    iconPack: 'feather',
                    icon:'icon-check'
                })                        
                setTimeout(function(){ 
                    this.$router.go()
                }, 600);
            })
            .catch(err => {
                if (err.response) {
                    // client received an error response (5xx, 4xx)
                    this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
                } else if (err.request) {
                    // client never received a response, or request never left
                    this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
                } else {
                    // anything else
                    this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
                }
                this.$vs.notify({
                    time:4000,
                    title: 'Warning',
                    text: 'file failed to upload.',
                    color: 'warning',
                    iconPack: 'feather',
                    icon:'icon-alert-circle'
                })   
            })
        },
        onFileChange(e){
            const reader = new FileReader();
            this.file = e.target.files[0];
            // console.log(e.target.files)
        }
    },
    created() {
    }
}
</script>
        
<style lang="scss">
.button-submit {
  background-color:rgb(255, 111, 0); 
  border-radius:5px; 
  border: none; 
  padding: 5px 10px !important;
  color:white;
}
</style>