<template>    
    <div  class="div-upload-loading vs-con-loading__container" v-if="activeUserInfo.role == 'superuser' || activeUserInfo.role == 'admin'">
        <div v-if="edit.length<=0" >
            <b-card class="mt-4" title="Form Article" >
                <b-card-text>
                    <b-form-group
                        id="input-group-1"
                        label="Title Article :"
                        label-for="input-1"
                    >
                        <b-form-input
                        id="input-1"
                        v-model="articleTitle"
                        type="text"
                        placeholder="Title"
                        required
                        ></b-form-input>
                    </b-form-group>
                    <b-form-group
                        id="input-group-1"
                        label="Upload Thumbnail :"
                        label-for="input-1"
                        class="mt-3"
                    >
                    
                        <form  enctype="multipart/form-data">
                            <b-form-file type="file" v-model="imageFile" name="file" accept="image/*" @change="setImage" />
                            <!-- Image previewer -->
                            <!-- <img :src="imageSrc" width="100px" /> -->

                            <!-- Cropper container -->
                            <div
                            v-if="this.imageSrc"
                            class="my-3 ml-2"
                            
                            >
                                <b-container class="bv-example-row">
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
                                                    <b-button class="mt-3" variant="primary" v-if="this.imageSrc" @click="cropImage">Crop</b-button>
                                        </b-col>
                                        <b-col lg="6" md="12" sm="12">
                                            <img style="borderStyle: solid;"  alt ="Waiting cropping" class="ml-2 w-50 bg-light " :src="croppedImageSrc" />
                                        </b-col>
                                    </b-row>
                                </b-container>
                            </div>
                        </form>
                    <!-- Cropped image previewer -->       
                    </b-form-group>
                    <label>Description :</label>
                    <ckeditor v-model="articleDescription" :config="editorConfig"></ckeditor>
                    <div class="float-right mt-4">
                        <b-button @click="submitForm()" variant="primary" type="submit">Save Article</b-button>
                        <a @click="cancelAddInsight()"><b-button  variant="danger">Cancel Add Article</b-button></a>
                        <div class="clc"></div>
                    </div>
                </b-card-text>
            </b-card>
        </div>
        <div v-else>
        <b-card class="mt-4" title="Update Article" >
                <b-card-text>
                    <b-form-group
                        id="input-group-1"
                        label="Title Article :"
                        label-for="input-1"
                    >
                        <b-form-input
                        id="input-1"
                        v-model="edit.title"
                        type="text"
                        placeholder="Title"
                        required
                        ></b-form-input>
                    </b-form-group>
                    <b-form-group
                        id="input-group-1"
                        label="Upload Thumbnail :"
                        label-for="input-1"
                        class="mt-3"
                    >
                    
                        <form  enctype="multipart/form-data">
                            <b-form-file type="file" v-model="imageFile" name="file" accept="image/*" @change="setImage" />
                            <!-- Image previewer -->
                            <!-- <img :src="imageSrc" width="100px" /> -->

                            <!-- Cropper container -->
                            <div
                            v-if="this.imageSrc"
                            class="my-3 ml-2"                            
                            >
                                <b-container class="bv-example-row">
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
                                                    <b-button class="mt-3" variant="primary" v-if="this.imageSrc" @click="cropImage">Crop</b-button>
                                        </b-col>
                                        <b-col lg="6" md="12" sm="12">
                                            <img style="borderStyle: solid;"  alt ="Waiting cropping" class="ml-2 w-50 bg-light " :src="croppedImageSrc" />
                                        </b-col>
                                    </b-row>
                                </b-container>
                            </div>
                        </form>
                    <!-- Cropped image previewer -->       
                    </b-form-group>
                    <label>Description :</label>
                    <ckeditor v-model="edit.description" :config="editorConfig"></ckeditor>
                    <div class="float-right mt-4">
                        <b-button @click="submitEditForm()" variant="primary" type="submit">Update Article</b-button>
                        <a @click="cancelAddInsight()"><b-button  variant="danger">Cancel Add Article</b-button></a>
                        <div class="clc"></div>
                    </div>
                </b-card-text>
            </b-card>
        </div>


    </div>
    <div v-else>
        Bukan Super User
    </div>
</template>

<script>
import VueCropper from "vue-cropperjs"
  import "cropperjs/dist/cropper.css"
import {
  BFormFile,
  BFormTags,BForm 
} from 'bootstrap-vue'
import { ValidationProvider, ValidationObserver, extend, Validator} from 'vee-validate';
import { required, email, dimensions } from 'vee-validate/dist/rules';
const axios = require('axios');
extend('dimensions', dimensions)

extend('required', {
      ...required,
      message:"This field is empty"
});
extend('min', {
  validate(value, args) {
    return value.length >= args.length;
  },
  params: ['length']
});
extend('minmax', {
  validate(value, { min, max }) {
    return value.length >= min && value.length <= max;
  },
  params: ['min', 'max']
});
extend('password', {
  params: ['target'],
  validate(value, { target }) {
    return value === target;
  },
  message: 'Password confirmation does not match'
});


    export default {
        name: 'app',
        components:{
            BFormFile,BFormTags,BForm,ValidationProvider,
            ValidationObserver,VueCropper
            
        },props:['edit'],
        data() {
            
        return {
            imageSrc: "",
            croppedImageSrc: "",
            ratio:1/1,
            status:true,
            data:null,
            editorConfig: {

                  toolbar:['bold']  
                },
                articleTitle:"",
                articleDescription:"",
                imageFile:[],
                itemArticle:this.edit,
                
            };
        },
        
        computed: {
          activeUserInfo () {
              // console.log(this.$store.state.AppActiveUser);
              return this.$store.state.AppActiveUser
          },
        
        },
        methods:{
            setImage: function (e) {
        const file = e.target.files[0]
        this.imageFile=file;
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
                console.log(this.data,"ini data di cropImage")
                
            this.data=self['data']
      },
      uploadImage() {
        this.$refs.cropper.getCroppedCanvas().toBlob(function (blob) {
          let formData = new FormData()

          // Add name for our image
          formData.append("name", "image-name-" + new Date().getTime())

          // Append image file
          formData.append("image", blob)
          console.log(blob)

        })
      },
     
            async getDataArticle(){
                var response= await axios.get('api/articles')
                this.dataArticle=response.data.result
            },
            handleOnChange(e){
                this.imageFile=e.target.files[0]
            },
            async submitForm(){
                try {
                 await this.cropImage();
                console.log(this.data,"ini data di submit")
                const config = {
                headers: { 'content-type': 'multipart/form-data' }
                }
                /* kalo butuh file name bisa pake ini */
                /* console.log(this.imageFile.name) */
                this.$vs.loading({
                            container: '.div-upload-loading',
                            text:"please wait a second. Your data is being processed...",
                            scale: 0.6
                }) 
                const formData=new FormData();
                    formData.set('title',this.articleTitle)
                    formData.set('user_id',this.activeUserInfo.id)
                    formData.set('description',this.articleDescription);
                    formData.set('image',this.data);
                    formData.set('type',this.data.type);
                var response=await axios.post('api/articles/add',formData);
                    if(response.data.message=="Success add article"){
                        this.$vs.loading.close('.div-upload-loading > .con-vs-loading')
                        this.$vs.notify({
                            color:'success',
                            title:'Successfully',
                            text:"Success add article"
                            })
                            location.reload() 
                    }else{
                        this.$vs.loading.close('.div-upload-loading > .con-vs-loading')
                        this.$vs.notify({
                            color:'danger',
                            title:'Failed',
                            text:"Failed when adding article"
                            })
                    }
            } catch (error) {
                console.log(error);
               
                this.$vs.notify({
                    color:'danger',
                    title:'Failed',
                    text:"Failed when adding article"
                    }) 
            }
               
            
            },
            cancelAddInsight(){
                    this.$vs.dialog({
                    type:'confirm',
                    color: 'danger',
                    title: `Confirm`,
                    text: 'if you press the accept button, the data changes will not be saved',
                    accept:this.acceptAlert
                })
            },
            async acceptAlert(){
                var buttonAddInsight=false;
                await this.$emit('clicked',buttonAddInsight)
                location.reload()
                

            },
            async submitEditForm(){
                try {
                
                const config = {
                headers: { 'content-type': 'multipart/form-data' }
                }
                 this.$vs.loading({
                            container: '.div-upload-loading',
                            text:"please wait a second. Your data is being processed...",
                            scale: 0.6
                        }) 
                const formData=new FormData();
                if(this.data){

                    formData.set('id',this.edit.id)
                    formData.set('title',this.edit.title)
                    formData.set('description',this.edit.description);
                    formData.set('image',this.data);
                }else{
                    
                    formData.set('id',this.edit.id)
                    formData.set('title',this.edit.title)
                    formData.set('description',this.edit.description);
                }
                var response=await axios.post('api/articles/edit',formData);
                    if(response.data.message=="Success update article"){
                        await setTimeout(() => {
                            this.$vs.loading.close('.div-upload-loading > .con-vs-loading')
                        }, 4000)
                        this.$vs.notify({
                            color:'success',
                            title:'Successfully',
                            text:"Success updating article"
                            })
                            location.reload() 
                    }else{
                        this.$vs.loading.close('.div-upload-loading > .con-vs-loading')
                        this.$vs.notify({
                            color:'danger',
                            title:'Failed',
                            text:"Failed when updating article"
                            })
                    }
            } catch (error) {
                console.log(error);
                this.$vs.loading.close('.div-upload-loading > .con-vs-loading')
                this.$vs.notify({
                    color:'danger',
                    title:'Failed',
                    text:"Failed when updating article"
                    }) 
            }
            }
        }
    }
</script>

