<template>
    <div id="div-upload-loading" class="vs-con-loading__container">
        
             <form @submit.prevent="submitFile" enctype="multipart/form-data">
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
            
                
                <div class="center">
                    <b-form-checkbox v-model="optionThumbnail" >
                    Upload Image Thumbnail
                    </b-form-checkbox>
                </div>
                <div v-show="optionThumbnail" class="vx-row mb-1">
                    <div class="vx-col w-full">
                        <h6 class="mr-2 mt-3">Upload Image Thumbnail</h6>
                    </div>

                    <div class="vx-col w-full">                                
                        <!-- <ValidationProvider rules="required" name="Image file" v-slot="{ errors}"> -->

                            <b-form-file
                            v-model="imageFile"
                            accept="image/*"
                            placeholder="Choose a image"
                            drop-placeholder="Drop file here..."
                            :required="requireThumbnail"
                            name="imageFile"
                            type="file"
                           
                            @change="setImage"
                            ></b-form-file> 
                            <div
                            v-if="this.imageSrc"
                            class="my-3 ml-2"                            
                            >
                           
                                <b-container  class="bv-example-row">
                                    <b-row>
                                        <b-col lg="6" md="12" sm="12">
                                            <label>Cropper Tools:</label>
                                        </b-col>
                                        <b-col lg="6" md="12" sm="12">
                                            <label>Result Crop :</label>
                                        </b-col>
                                    </b-row>
                                    <b-row>
                                        <b-col lg="6"  md="12" sm="12">
                                                <vue-cropper
                                                    :center="true"
                                                    :cropBoxResizable="false"
                                                    :aspectRatio="ratio"
                                                    :initialAspectRatio="ratio"
                                                    class="mr-2 w-50"
                                                    ref="cropper"
                                                    :guides="true"
                                                    :src="imageSrc"
                                                ></vue-cropper>  
                                                    <b-button class="mt-3" variant="primary" v-if="this.imageSrc"   @click="cropImage">Crop</b-button>
                                        </b-col>
                                        <b-col lg="6" md="12" sm="12">
                                            <img style="borderStyle: solid;"  alt ="Waiting cropping" class="ml-2 w-50 bg-light " :src="croppedImageSrc" />
                                        </b-col>
                                    </b-row>
                                </b-container>
                            </div>   
                        <!--     <div v-if="errors[0]">
                                <span  class="text-color-error"><b>[{{ errors[0] }}] Please check extension and image size should be 1000 px X 1000 px !</b></span>
                            </div>
                            
                            
                        </ValidationProvider> -->

                        <!-- <div class="mt-3">Selected file: {{ file ? file.name : '' }}</div> -->
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
                            required
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
                      
                <div class="vx-row">
                    <div class="vx-col w-full mt-3">
                        <button class="float-right button-submit" type="submit">Upload</button>
                    </div>
                </div>
            </form>
        
    </div>
</template>

<script>
import VueCropper from "vue-cropperjs"
  import "cropperjs/dist/cropper.css"
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
            requireThumbnail:false,
            optionThumbnail:false,
            imageSrc: "",
            croppedImageSrc: "",
            ratio:1/1,
            status:true,
            data:null,
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
        VueCropper
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
        },
        optionThumbnail(){
            if(this.optionThumbnail){
                console.log("ada")
                this.requireThumbnail=this.optionThumbnail;
            }else{
                console.log("tidak ada")
                this.imageSrc= ""
                this.imageFile=[]
                this.croppedImageSrc= ""
                this.ratio=1/1
                this.status=true
                this.data=null
                this.requireThumbnail=this.optionThumbnail
                console.log( this.imageSrc,
                    this.imageFile,
                    this.croppedImageSrc,
                    this.ratio,
                    this.status,
                    this.data,
                    this.requireThumbnail)
                }
        }
        
    },
    methods: {
        async submitFile(){
           
            if(this.optionThumbnail && this.imageSrc != '' && this.croppedImageSrc != ''){
                    this.cropImage()
            }  
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
                    
                    status=true;
                        for (let fileData of this.file) {
                            formData.append("file[]", fileData);
                            }
                        formData.append('username', this.$store.state.AppActiveUser.displayName);
                        formData.append('category', this.select_category['value']);
                        formData.append('subcategory', this.select_subcategory['label']);
                        formData.append('file_name', this.namaDataset);
                        formData.append('description', this.textarea);
                         //ini sebelumnya sudah berhasil
                         /* if(this.imageFile.length>0){
                             for (let fileDataImage of this.imageFile) {
                                 
                                 formData.append('imageFile[]',fileDataImage);
                                 }
                                 formData.append('imageFileName',this.imageFile[0].name);
                                 console.log("ada")
                                 console.log(this.imageFile)
                        } */
                        if(this.croppedImageSrc){
                                 formData.append('imageFile',this.data);
                                formData.append('type',this.data.type);
                                /* formData.append('imageFileName',this.imageFile[0].name); */
                                formData.append('imageFileName',this.imageFile.name);
                                console.log("ada")
                                console.log(this.imageFile,"ini menandakan masuk")
                                
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
                            //ini sebelumnya sudah berhasil
                             /* if(this.imageFile.length>0){
                                 for (let fileDataTunggalImage of this.imageFile) {
                                     formData.append('imageFile[]',fileDataTunggalImage);
                                    }
                                 formData.append('imageFileName',this.imageFile[0].name);
                                 console.log("ada")
                                 console.log(this.imageFile)
                             }
                              */
                             if(this.croppedImageSrc){
                                 formData.append('imageFile',this.data);
                                formData.append('type',this.data.type);
                                /* formData.append('imageFileName',this.imageFile[0].name); */
                                formData.append('imageFileName',this.imageFile.name);
                                console.log("ada")
                                console.log(this.imageFile,"ini menandakan masuk")
                                
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
        },
        setImage: function (e) {
            const file = e.target.files[0]
            this.imageFile=file;
            console.log(this.imageFile,"ini dari this.imageFile")
            if (!file.type.includes("image/")) {
                alert("Please select an image file")
                this.status=true;
            }else{
                this.imageFile=file;
                this.status=false;
            }
            if (typeof FileReader === "function") {
            const reader = new FileReader()
            reader.onload = event => {
                this.imageSrc = event.target.result

                // Rebuild cropperjs with the updated source
                /* this.$refs.cropper.replace(event.target.result) */
            }
            reader.readAsDataURL(file)
            } else {
            alert("Sorry, FileReader API not supported")
            }
        },
        async cropImage() {
            // Get image data for post processing, e.g. upload or setting image src
            

                this.croppedImageSrc = this.$refs.cropper.getCroppedCanvas().toDataURL()
                var self = this;
                    await this.$refs.cropper.getCroppedCanvas().toBlob(function (blob) {
                        self.data = blob;
                                console.log(blob,"ini blob");
                                console.log(self.data,"ini self.data");
                    });
                    
                this.data=self['data']
                
                console.log(this.data,"ini data crop")
                    
            
        },
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
