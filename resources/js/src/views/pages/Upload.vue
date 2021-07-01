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
                                v-model="namaDataset"
                                required
                                />
                            <span class="text-color-error"><b>{{ errors[0] }}</b></span>
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
                                :options="category" :dir="$vs.rtl ? 'rtl' : 'ltr'"
                                required
                                />
                            <span  class="text-color-error"><b>{{ errors[0] }}</b></span>
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
                                    :options="subcategory[category_selected]" :dir="$vs.rtl ? 'rtl' : 'ltr'"
                                    required
                                    />
                                <span  class="text-color-error"><b>{{ errors[0] }}</b></span>
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
                                required
                            ></b-form-tags>
                            <span  class="text-color-error"><b>{{ errors[0] }}</b></span>
                        </ValidationProvider>
                    </div>
                </div>
                <div class="vx-row mb-1">
                    <div class="vx-col w-full">
                        <h6 class="mr-2 mt-3">Description</h6>
                    </div>
                    <div class="vx-col w-full">
                        <ValidationProvider name="Description" rules="required" v-slot="{ errors }">
                            <vs-textarea
                                class="bg-white mt-1"
                                v-model="textarea" required />
                            <span  class="text-color-error"><b>{{ errors[0] }}</b></span>
                        </ValidationProvider>
                    </div>
                </div>
                <div class="vx-row mb-1">
                    <div class="vx-col w-full">
                        <h6 class="mr-2 mt-3">Upload File</h6>
                    </div>
                    <div class="vx-col w-full">
                        <ValidationProvider name="File" rules="required|array" v-slot="{ errors }">
                            <b-form-file
                            v-model="file"
                            :state="Boolean(file)"
                            placeholder="Choose a file or drop it here..."
                            drop-placeholder="Drop file here..."
                            name="file"
                            multiple
                            @change="onFileChange"
                            ></b-form-file>
                            <span  class="text-color-error"><b>{{ errors[0] }}</b></span>
                        </ValidationProvider>
                        <!-- <div class="mt-3">Selected file: {{ file ? file.name : '' }}</div> -->
                    </div>
                    <div class="vx-col w-full">
                        <span class="text-size"><b>Untuk data Peta,</b> upload sekaligus 3 file dengan ekstensi (.shp, .shx, .dbf)</span>
                        <br>
                        <span class="text-size"><b>Selain data Peta,</b> upload hanya 1 file (.csv, .las, .segy, dll)</span>
                    </div>
                </div>
                <div class="vx-row mb-1">
                    <div class="vx-col w-full">
                        <h6 class="mr-2 mt-3">Upload Image Thumbnail</h6>
                    </div>

                    <div class="vx-col w-full">                                
                        <!-- <ValidationProvider rules="dimensions:976,1024" name="Image file" v-slot="{ errors}"> -->

                            <b-form-file
                            v-model="imageFile"
                            :state="Boolean(imageFile)"
                            accept="image/*"
                            placeholder="Choose a file or drop it here..."
                            drop-placeholder="Drop file here..."
                            name="imageFile"
                            type="file"
                            multiple
                            @change="handleOnChange"

                            ></b-form-file>    
                        <!--     <div v-if="errors[0]">
                                <span  class="text-color-error"><b>[{{ errors[0] }}] Please check extension and image size should be 1000 px X 1000 px !</b></span>
                            </div>
                            
                            
                        </ValidationProvider> -->

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
import { ValidationProvider, ValidationObserver, extend, Validator} from 'vee-validate';
import {dimensions } from "vee-validate/dist/rules";

extend('dimensions', dimensions)



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
            namaDataset: '',
            file: [],
            imageFile:[],
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
        async submitFile(){

            
            const config = {
                headers: { 'content-type': 'multipart/form-data' }
            }

            let formData = new FormData();
            var status=true;
            var nama='';
            this.$vs.loading({
                            container: '#div-upload-loading',
                            text:"please wait a second. Your data is being processed...",
                            scale: 0.6
                            })
             if (this.file.length>1) {
                 for(var k=0;k<this.file.length;k++){
                     console.log(k)
                     console.log(this.file[k].name.search(".dbf"),"cek dbf")
                     console.log(this.file[k].name.search(".las"),"cek las")
                     console.log(this.file[k].name.search(".shp")>-1,"cek shp")
                     console.log(this.file[k].name.search(".shx"),"cek shx")
                     if(this.file[k].name.search(".dbf")>-1 || this.file[k].name.search(".shp")>-1 || this.file[k].name.search(".shx")>-1 ){
                         nama=this.file[k].name;
                         
                        console.log(nama);
                            console.log(status);
                        if(status){
                                for (let j = 0; j < this.file.length; j++) {
                                    if (k != j) {

                                            if(this.file[j].name != nama && status){
                                                status=true;
                                        }else{
                                            status=false;
                                        }
                                    }
                            }
                        }else{
                            status=false;
                            break;
                        }

                     }else{
                        status=false;
                        break;
                     }
                 }

                 if(status){
                    console.log(this.file,"ini true")
                    console.log(this.imageFile[0])
                    status=true;
                        for (let fileData of this.file) {
                            formData.append("file[]", fileData);
                            }
                        formData.append('username', this.$store.state.AppActiveUser.displayName);
                        formData.append('category', this.select_category['value']);
                        formData.append('subcategory', this.select_subcategory['label']);
                        formData.append('file_name', this.namaDataset);
                        formData.append('description', this.textarea);
                         if(this.imageFile.length>0){
                             for (let fileDataImage of this.imageFile) {
     
                                     formData.append('imageFile[]',fileDataImage);
                                 }
                                 formData.append('imageFileName',this.imageFile[0].name);
                                 console.log("ada")
                                 console.log(this.imageFile)
                        }
                       
                        formData.append('tagging', this.tagging.join(';'));
                        
                        try {
                                var response=await axios.post('/api/upload',formData)
                                if(response.status == 201){
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
                                        location.reload();
                                    }, 800);
                                }/* else if(response.data == "Dataset already exist"){
                                    this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
                                    this.$vs.notify({
                                        time:4000,
                                        title: 'Failed',
                                        text: 'Dataset already exist',
                                        color: 'danger',
                                        iconPack: 'feather',
                                        icon:'icon-check'
                                    })
                                } */else{
                                    this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
                                    this.$vs.notify({
                                        time:4000,
                                        title: 'Failed',
                                        text: response.data,
                                        color: 'danger',
                                        iconPack: 'feather',
                                        icon:'icon-check'
                                    })
                                 }
                            } catch (error) {
                                
                                if (error.response) {
                                    // client received an error response (5xx, 4xx)
                                    this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
                                } else if (error.request) {
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
                        
                            }
                        //ini sudah benar
                       /*  axios.post('/api/upload',formData).then(response => {
                             console.log(response);
                             
                            if(response=="Dataset has been saved"){
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
                                    location.reload();
                                }, 800);

                            }else if(response=="Dataset already exist"){
                                    this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
                                    this.$vs.notify({
                                    time:4000,
                                    title: 'Failed',
                                    text: 'Dataset already exist',
                                    color: 'danger',
                                    iconPack: 'feather',
                                    icon:'icon-check'
                                })
                            }else{
                                    this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
                                    this.$vs.notify({
                                    time:4000,
                                    title: 'Failed',
                                    text: 'Failed upload file',
                                    color: 'danger',
                                    iconPack: 'feather',
                                    icon:'icon-check'
                                })
                            }

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
                        }) */

                 }
                 else{
                    console.log(this.file,"Multi Upload hanya berlaku untuk file shp,dbf dan shx")
                    this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
                    this.$vs.notify({
                        title:'Color',
                        text:'Upload Gagal, Multi Upload hanya dilakukan untuk tiga file(.shp,.shx,.dbf)',
                        color:"#FF0000"
                    })
                    status=true
                 }
             }else{
                  if(this.file[0].name.search(".las")>-1 || this.file[0].name.search(".segy")>-1 || this.file[0].name.search(".xlsx")>-1 || this.file[0].name.search(".pdf")>-1 || this.file[0].name.search(".csv")>-1  ){
                      for (let fileDataTunggal of this.file) {
                          formData.append("file[]", fileDataTunggal);
                            }
                            console.log(formData,"ini fil formData cuy")
                        formData.append('username', this.$store.state.AppActiveUser.displayName);
                        formData.append('category', this.select_category['value']);
                        formData.append('subcategory', this.select_subcategory['label']);
                        formData.append('file_name', this.namaDataset);
                        formData.append('description', this.textarea);
                        formData.append('tagging', this.tagging.join(';'));

                             if(this.imageFile.length>0){
                                 for (let fileDataTunggalImage of this.imageFile) {
                                     formData.append('imageFile[]',fileDataTunggalImage);
                                    }
                                 formData.append('imageFileName',this.imageFile[0].name);
                                 console.log("ada")
                                 console.log(this.imageFile)
                             }
                             
                        console.log(formData,"ini form data ya guyss")
                       /*  this.$vs.loading({
                           container: '#div-upload-loading',
                            text:"please wait a second. Your data is being processed...",
                            scale: 0.6
                        }) */
                            console.log("ini form data",formData)
                            try {
                                var response=await axios.post('/api/upload',formData)
                                if(response.status == 201){
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
                                        location.reload();
                                    }, 800);
                                }/* else if(response.data == "Dataset already exist"){
                                    this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
                                    this.$vs.notify({
                                        time:4000,
                                        title: 'Failed',
                                        text: 'Dataset already exist',
                                        color: 'danger',
                                        iconPack: 'feather',
                                        icon:'icon-check'
                                    })
                                } */else{
                                    this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
                                    this.$vs.notify({
                                        time:4000,
                                        title: 'Failed',
                                        text: 'Failed upload file',
                                        color: 'danger',
                                        iconPack: 'feather',
                                        icon:'icon-check'
                                    })
                                 }
                            } catch (error) {
                                
                                if (error.response) {
                                    // client received an error response (5xx, 4xx)
                                    this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
                                } else if (error.request) {
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
                        
                            }
                            //Ini sudah benar
                            /* axios.post('/api/upload',formData).then(response => {
                                console.log(response)
                                if(response == "Dataset has been saved"){
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
                                    location.reload();
                                }, 800);

                            }else if(response == "Dataset already exist"){
                                    this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
                                    this.$vs.notify({
                                    time:4000,
                                    title: 'Failed',
                                    text: 'Dataset already exist',
                                    color: 'danger',
                                    iconPack: 'feather',
                                    icon:'icon-check'
                                })
                            }else{
                                    this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
                                    this.$vs.notify({
                                    time:4000,
                                    title: 'Failed',
                                    text: 'Failed upload file',
                                    color: 'danger',
                                    iconPack: 'feather',
                                    icon:'icon-check'
                                })
                            }
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
                        }) */
                }else if (this.file[0].name.search(".shp")>-1) {
                    this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
                            this.$vs.notify({
                            title:'Color',
                            text:"Uploading file .shp have to upload together with file .dbf and .shx ",
                            color:"#FF0000"
                        })

                        }else{
                            this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
                            this.$vs.notify({
                            title:'Color',
                            text:'You havent upload file',
                            color:"#FF0000"
                        })
                        }
                }



        },
        onFileChange(e){
            const reader = new FileReader();
            this.file = e.target.files[0];

            console.log(e.target.files,"target file")
        },
        handleOnChange(e){
            const reader = new FileReader();
            
            this.imageFile = e.target.files[0];
            console.log(e.target.files,"target image file")

            

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
<style>
.vs-con-textarea{
    margin-bottom: 2px !important;
}
.text-color-error{
    color:darkred;
}
.text-size{
    font-size: 12px;
}
</style>
