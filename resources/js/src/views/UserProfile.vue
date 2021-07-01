<template>
    <div  id="div-upload-loading" class="vs-con-loading__container">
        <div class="vx-row">
            <vx-card>
                <div class="vx-col flex w-full flex-wrap clearfix">
                    <h3 class="ml-2 w-full mr-auto">User Profile Setting</h3>
                <!-- <p class="ml-2 w-full mr-auto">Manage users, datasets, and approval.</p> -->
                </div>
            </vx-card>
        </div>
        <vx-card
            card-background="#dfe3e5"
            content-color="#000000">
            <div class="mb-1">
                <div>
                    <!-- <userSettings></userSettings> -->
                    <vx-card no-shadow>
                        <!-- Img Row -->
                        <!-- <i style="color:red;">*/ this page will be updated</i> -->
                        <div class="vx-row">
                            <div class="vx-col w-full sm:w-3/3 md:w-3/3 lg:w-1/2 xl:w-1/2 sm:my-7">
                            <!-- <div class="vx-col w-full md:w-3/3 lg:w-1/2 xl:w-1/2 sm:my-10"> -->
                                <!-- Info -->
                                <div class="row">
                                    <div class="col-sm-12 text-center">
                                        <div class="">
                                            <h5 class="">Upload Profile Picture</h5>
                                        </div>
                                        <div>
                                            <vs-avatar :src="activeUserInfo.photo" size="170px" class="" />
                                        </div>
                                    </div>
                                    <div class="col-sm-12 text-center">
                                        <div class="">
                                            <vs-button   @click="popupActive = true" class="sm:mb-0 mb-2" >Upload photo</vs-button>
                                            <vs-popup
                                                background-color="rgba(255,255,255,.6)"
                                                title="Upload Profile Picture"
                                                :active.sync="popupActive"
                                            >
                                                <form @submit.prevent="submitUploadImage" enctype="multipart/form-data">
                                                    <ValidationProvider  name="Image file" v-slot="{ errors,validate}">
                                                        <b-form-file
                                                            v-model="imageFile"
                                                            :state="Boolean(imageFile)"
                                                            accept="image/*"
                                                            placeholder="Choose a file or drop it here..."
                                                            drop-placeholder="Drop file here..."
                                                            name="image File"
                                                            type="file"
                                                            @change="handleOnChange|validate"
                                                        ></b-form-file>
                                                        <div v-if="errors[0]">
                                                            <span  class="text-color-error"><b>[{{ errors[0] }}] Please check extension and image size should be 250 px X 250 px !</b></span>
                                                        </div>
                                                    </ValidationProvider>
                                                <b-button class="float-right  mt-2" variant="warning" type="submit">Upload</b-button>
                                                </form>
                                            </vs-popup>
                                            <!-- <div class="mt-3">Selected file: {{ file ? file.name : '' }}</div> -->
                                        </div>
                                        <div class="">
                                            <p class="text-sm mt-2 italic" >*Allowed JPG or PNG and ratio width:900px , height:900px</p>
                                        </div>
                                        <!-- </div> -->
                                        <!-- <vs-button type="border" color="danger">Remove</vs-button> -->
                                    </div>
                                </div>
                                <!-- End Info -->
                            </div>

                            <div class="vx-col w-full md:w-3/3 lg:w-1/2 xl:w-1/2">
                                <div class="vx-row  w-full">
                                    <div class="vx-col md:w-1/2 lg:w-1/2 xl:w-1/2">
                                    <label>Username</label>
                                    <vs-input class="w-full mb-base user-field text-sm" disabled="true" v-model="username"></vs-input>
                                    </div>
                                    <div class="vx-col md:w-1/2 lg:w-1/2 xl:w-1/2">
                                    <label>Email</label>
                                    <vs-input class="w-full mb-base user-field"  disabled="true"  v-model="email"></vs-input>
                                    </div>
                                </div>
                                <!-- <vs-alert icon-pack="feather" icon="icon-info" class="h-full my-4" color="warning">
                                <span>Your account is not verified. <a href="#" class="hover:underline">Resend Confirmation</a></span>
                                </vs-alert> -->
                                <div class="vx-row w-full">
                                    <div class="vx-col md:w-1/2 lg:w-1/2 xl:w-1/2">
                                        <label>Status</label>
                                        <vs-input class="w-full mb-base user-field"  disabled="true" v-model="status"></vs-input>
                                    </div>
                                    <div class="vx-col md:w-1/2 lg:w-1/2 xl:w-1/2 mb-base">
                                        <label>Job Title</label>
                                        <ValidationProvider rules="required" v-slot="{ errors }">
                                            <vs-input class="w-full user-field"  required v-model="job_title"></vs-input>
                                            <span  class="text-color-error"><b>{{ errors[0] }}</b></span>
                                        </ValidationProvider>
                                    </div>
                                </div>
                                <div class=" vx-row w-full">
                                    <div class="vx-col md:w-1/2 lg:w-1/2 xl:w-1/2 mb-base">
                                        <label>Company</label>
                                        <ValidationProvider rules="required" v-slot="{ errors }">
                                            <vs-input class="w-full user-field"  v-model="company"></vs-input>
                                            <span  class="text-color-error"><b>{{ errors[0] }}</b></span>
                                        </ValidationProvider>
                                    </div>
                                    <div class="vx-col md:w-1/2 lg:w-1/2 xl:w-1/2 mb-base">
                                        <label>No. Telp</label>
                                        <ValidationProvider rules="required" v-slot="{ errors }">
                                            <vs-input class="w-full user-field"  required v-model="noTelp"></vs-input>
                                            <span  class="text-color-error"><b>{{ errors[0] }}</b></span>
                                        </ValidationProvider>
                                    </div>
                                </div>
                                <!-- Save & Reset Button -->
                                <div class="flex flex-wrap items-center justify-end mr-8">
                                    <vs-button class="ml-auto mt-2" v-show="activeUserInfo.registered_via != 'gmail'" @click="popupActiveResetPass = true" color="warning" >Reset Password</vs-button>
                                    <vs-button class="ml-1 mt-2" color="#288cfc" @click="submitSaveChange()">Save Changes</vs-button>
                                    <!-- <vs-button class="ml-4 mt-2" type="border" color="warning">Reset</vs-button> -->
                                </div>
                                <vs-popup
                                    background-color="rgba(255,255,255,.6)"
                                    title="Reset Password"
                                    :active.sync="popupActiveResetPass"
                                    class="p-2"
                                >
                                    <div class="p-4">
                                        <div class="" >
                                            <div class=" ">
                                                <div v-if="!oldpasswordHidden">
                                                    <label>
                                                        <span class="strong-label">Old Password</span>
                                                    </label>
                                                    <div class="inputContainer">
                                                        <input type="text" class="password-field form-control d-inline" v-model="oldpasswordText" />
                                                        <span class="display-eye fa fa-eye-slash eyeIcon" @click="oldhidePassword"></span>
                                                    </div>
                                                </div>
                                                <div v-if="oldpasswordHidden">
                                                    <label>
                                                        <span class="strong-label">Old Password</span>
                                                    </label>
                                                    <div class="inputContainer">
                                                        <input type="password" class="password-field form-control d-inline" v-model="oldpasswordText" />
                                                        <span class="display-eye fa fa-eye eyeIcon" @click="oldshowPassword"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <div v-if="!newpasswordHidden">
                                                    <label>
                                                        <span class="strong-label">New Password</span>
                                                    </label>
                                                    <div class="inputContainer">
                                                        <input type="text" class="password-field form-control d-inline" v-model="newpasswordText" />
                                                        <span class="display-eye fa fa-eye-slash eyeIcon" @click="newhidePassword"></span>
                                                    </div>
                                                </div>
                                                <div v-if="newpasswordHidden">
                                                    <label>
                                                        <span class="strong-label">New Password</span>
                                                    </label>
                                                    <div class="inputContainer">
                                                        <input type="password" class="password-field form-control d-inline" v-model="newpasswordText" />
                                                        <span class="display-eye fa fa-eye eyeIcon" @click="newshowPassword"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4 flex justify-content-end">
                                            <vs-button class="" color="#288cfc" @click="changePassword()" >Change Password</vs-button>
                                            <!-- <vs-button class="ml-4 mt-2" type="border" color="warning">Reset</vs-button> -->
                                        </div>
                                    </div>
                                </vs-popup>
                            </div>
                        </div>
                    </vx-card>
                </div>
            </div>
        </vx-card>
    </div>
</template>

<script>

import datasetPages from './pages/datasetSettings.vue'
import userPages from './pages/userAdmin.vue'
import userSettings from './pages/UserSettings.vue'
import vSelect from 'vue-select'
import 'vuesax/dist/vuesax.css'
import {
    BFormFile,
    BFormTags
} from 'bootstrap-vue'

// vee validate
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
  components: {
    ValidationProvider,
    ValidationObserver,
    BFormFile,
    BFormTags
  },
  props: {


        },
  data () {
    return {
      oldpasswordText:"",

      newpasswordText:"",

       oldpasswordHidden: {
                    default: false,
                    type: Boolean
                },
       newpasswordHidden: {
                    default: false,
                    type: Boolean
                },

      popupActiveResetPass: false,
      popupActive: false,
      username: '' ,
      name: '',
      email: '',
      status: '',
      job_title:'',
      company:'',
      password:'',
      noTelp:'',
      imageFile:[],
    }
  },
  computed: {

    activeUserInfo () {
      return this.$store.state.AppActiveUser
    }
  },
  methods: {
    async changePassword(){
      console.log(this.newpasswordText)
      console.log(this.oldpasswordText)
      if(this.newpasswordText && this.oldpasswordText){
        try {
          var body={
            'id':this.activeUserInfo.id,
            'new_password':this.newpasswordText,
            'old_password':this.oldpasswordText
          }
          this.$vs.loading({
                            container: '#div-upload-loading',
                            text:"please wait a second. Your data is being processed...",
                            scale: 0.6
                        })
          var response=await axios.post('api/user/change_password',body)
          console.log(response)
          if(response.data.status == "success"){
            this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
            this.$vs.notify({
              time:4000,
            title: 'Success',
            text: 'Success to update photo',
            color: 'success',
            iconPack: 'feather',
                            })
          }else{
            this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
            this.$vs.notify({
            time:4000,
            title: 'Danger',
            text: 'Wrong Old Password',
            color: 'danger',
            iconPack: 'feather',
                            })
          }
        } catch (error) {
          console.log(error);
        }
      }else{
        this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
            this.$vs.notify({
            time:4000,
            title: 'Warning',
            text: 'Please check, field cannot empty if you want change passwor ',
            color: 'warning',
            iconPack: 'feather',
                            })

      }
    },
    oldhidePassword() {
      this.oldpasswordHidden = true;
      },
    oldshowPassword() {
      this.oldpasswordHidden = false;
      },
    newhidePassword() {
      this.newpasswordHidden = true;
      },
    newshowPassword() {
      this.newpasswordHidden = false;
      },
    handleOnChange(e){
      this.imageFile=e.target.files[0]
    },
    async submitUploadImage(){
      if(this.imageFile){
        const formData= new FormData();
        const config = {
                headers: { 'content-type': 'multipart/form-data' }
            }

          console.log(this.imageFile,"ini image file")
          formData.set('photo',this.imageFile)
          formData.set('id',this.activeUserInfo.id)
          formData.set('company',this.activeUserInfo.company)
          formData.set('phone',this.activeUserInfo.phone)
          formData.set('job_title',this.activeUserInfo.job_title)
         /* var body={
          'id':this.activeUserInfo.id,
          'job_title':this.activeUserInfo.job_title,
          'company':this.activeUserInfo.company,
          'phone':this.activeUserInfo.phone,
          'photo':this.imageFile
        }   */
        console.log(formData,"ini formData")
         this.$vs.loading({
                            container: '#div-upload-loading',
                            text:"please wait a second. Your data is being processed...",
                            scale: 0.6
                        })
        let response=await axios.post('api/user/edit',formData)
        if(response.data.status=="success"){
          this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
          this.$vs.notify({
            time:4000,
            title: 'Success',
            text: 'Success to update photo',
            color: 'success',
            iconPack: 'feather',
                            })
            var payload={
              'job_title':this.activeUserInfo.job_title,
              'company':this.activeUserInfo.company,
              'phone':this.activeUserInfo.phone,
              'photo':response.data.result.photo
            }
            //sekarang ngga perlu logout setelah menggunakan vuex updateUserInfo
            await this.$store.dispatch('updateUserInfo',payload)
        }else{
          this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
          this.$vs.notify({
            time:4000,
            title: 'Danger',
            text: 'Failed to update photo',
            color: 'danger',
            iconPack: 'feather',
                            })
        }
      }
    },
    async submitSaveChange(){
      if(this.company && this.job_title && this.noTelp){
        var body={
          'id':this.activeUserInfo.id  ,
          'job_title':this.job_title,
          'company':this.company,
          'phone':this.noTelp,
        }
         this.$vs.loading({
                            container: '#div-upload-loading',
                            text:"please wait a second. Your data is being processed...",
                            scale: 0.6
                        })
        let response=await axios.post('api/user/edit',body)
        if(response.data.status=="success"){
          this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
          this.$vs.notify({
            time:4000,
            title: 'Success',
            text: 'Success to update',
            color: 'success',
            iconPack: 'feather',
                            })
            var payload={
              'job_title':this.job_title,
              'company':this.company,
              'phone':this.noTelp,
            }
            //sekarang ngga perlu logout setelah menggunakan vuex updateUserInfo
            await this.$store.dispatch('updateUserInfo',payload)
        }else{
          this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
          this.$vs.notify({
            time:4000,
            title: 'Danger',
            text: 'Failed to update',
            color: 'danger',
            iconPack: 'feather',
                            })
        }
      }
      else if(!this.company && !this.job_title && !this.noTelp){
       this.$vs.notify({
            time:4000,
            title: 'Danger',
            text: 'Please Check, Your`s field is empty',
            color: 'danger',
            iconPack: 'feather',
                            })
      }
      else if(!this.company){
        this.$vs.notify({
            time:4000,
            title: 'Warning',
            text: 'Company field is empty',
            color: 'warning',
            iconPack: 'feather',
                            })
      }

      else if(!this.job_title){
        this.$vs.notify({
            time:4000,
            title: 'Warning',
            text: 'Job title field is empty',
            color: 'warning',
            iconPack: 'feather',
                            })

      }else if(!this.noTelp){
        this.$vs.notify({
            time:4000,
            title: 'Warning',
            text: 'No Telp field is empty',
            color: 'warning',
            iconPack: 'feather',
                            })

      }else{
        this.$vs.notify({
            time:4000,
            title: 'Danger',
            text: 'Please, Your field is empty',
            color: 'danger',
            iconPack: 'feather',
                            })
      }
    }
  },

  mounted(){

    this.username = this.activeUserInfo.displayName
    this.name = this.activeUserInfo.displayName
    this.email = this.activeUserInfo.email
    this.company = this.activeUserInfo.company
    this.job_title= this.activeUserInfo.job_title
    this.status = this.activeUserInfo.role
    this.noTelp = this.activeUserInfo.phone

    console.log(this.activeUserInfo.photoURL)
  }
}
</script>


<style>
  [dir=ltr] .vs-input--input.hasIcon {
      padding: 0.5rem 1rem 0.7rem 3rem !important;
  }
  @mixin clearfix() {
    &::after {
      display: block;
      content: "";
      clear: both;
    }
  }
  .user-field{
    border: 2px solid rgba(0,0,0,0.4);
    border-radius: 8px;
  }

  .vs-con-input .vs-inputx{
    border: none !important;
  }
  .element {
    @include clearfix;
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
<style scoped>
    input {
        padding: .8rem;
        /* margin-right: -30px;
        padding-right: 35px; */
    }
    .strong-label {
        /* margin-right: 10px; */
        font-weight: 900;
        /* margin: .5 0 0 0rem; */
    }
    .display-eye {
        cursor:pointer;
    }
    .inputContainer {
        position:relative;

    }
    .eyeIcon {
        position: absolute;
        bottom: 0px;
        right:5px;
        width:24px;
        height:24px;
    }
</style>
