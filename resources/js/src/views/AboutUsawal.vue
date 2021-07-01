<template>  
  <div>
    <h1>About Us <a v-if="activeUserInfo.role == 'superuser' || activeUserInfo.role == 'admin'" class="editText" v-on:click="editText">edit post <i class="fas fa-edit" aria-hidden="true"></i></a></h1>
    <br>
    <div class="vx-row">
            <div class="vx-col w-full md:w-2/3 lg:w-2/3 xl:w-2/3 mb-base">
              <div id="text-with-loading" class="vs-con-loading__container">
                <b-form-textarea
                  id="textarea-state"
                  v-model="text"
                  placeholder=""
                  :plaintext="nonEditText"
                  max-rows="10000"
                  :class="borderStyle"
                ></b-form-textarea>
              </div>
            </div>
            <div class="vx-col w-full md:w-1/3 lg:w-1/3 xl:w-1/3 mb-base">

                <div v-if="!nonEditText" id="img-with-loading" class="vs-con-loading__container" style="margin-bottom: 5px;">                  
                  <h3 style="margin-bottom: 15px;">
                    Select to add
                  </h3>
                  <b-form-file
                  v-model="file"
                  :state="Boolean(file)"
                  placeholder="Choose a file or drop it here..."
                  drop-placeholder="Drop file here..."
                  name="file"
                  style="margin-bottom:15px;"
                  multiple 
                  ></b-form-file> 
                  <b-button v-if="!nonEditText" class="saveButton" type="submit" variant="primary" v-on:click="saveConfirm">Save</b-button>
                  <b-button v-if="!nonEditText" class="saveButton" type="submit" variant="danger" style="margin-right: 5px;" v-on:click="cancelConfirm">Cancel</b-button>
                </div> 

                <div v-if="nonEditText">
                  <div v-for="(i,index) in images" :key="index" ref='imgSize' class="imgContainer" :style="'height:'+imgHeight+'px'">
                    <img style="border-radius:5px;" :src="'api/imgaboutus/'+i['img']" class="imgResize" :alt="i['img']">
                  </div>
                </div>

                <div v-if="!nonEditText" id="delete-with-loading" class="vs-con-loading__container">
                  <vs-divider/>
                  <vs-table
                    multiple
                    v-model="selected"
                    :data="images">
                    <template slot="header">
                      <h3>
                        Select to remove
                      </h3>
                    </template>
                    
                    <template slot="thead">
                      <vs-th>
                        <div style="text-align:center; width:100%;">
                          Image
                        </div>
                      </vs-th>
                    </template>

                    <template slot-scope="{data}">
                      <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data" >
                        <vs-td :data="data[indextr].path">
                          <div ref='imgSize' class="imgTest" :style="'height:'+imgHeight+'px'">
                            <img :src="'api/imgaboutus/'+data[indextr].img" class="imgResize" alt="">
                          </div>
                        </vs-td>
                      </vs-tr>
                    </template>
                  </vs-table>
                </div>
                
                <div ref='imgSize' class="imgTest">
                </div>
            </div>
    </div>
  </div>
</template>

<script>
import {
  BImg,
  BButton,
  BFormFile,
  BFormTextarea
} from 'bootstrap-vue'

const axios = require('axios');
export default {
  data() {
    return {
      text: '',
      file: [],
      images: [],
      selected: [],
      nonEditText: true,
      borderStyle: 'borderText',
      webPath: '',
      imgHeight: 0,
      countPush: 0,
      countEnd: 0,
      mainProps: { 
          center: true,
          fluidGrow: true,
          blankColor: '#bbb',
          width: 600,
          height: 400,
          class: 'my-5',
      }
    }
  },
  components: {
    BImg,
    BButton,
    BFormFile,
    BFormTextarea
  },
  watch: {
    nonEditText(){
      if(this.nonEditText == true){
        this.borderStyle = 'borderText'
      }else{
        this.borderStyle = ''
      }
    },
    countPush(){
      if(this.countPush == this.countEnd){
        this.nonEditText = true  
        this.$router.go()
      }
    }
  },
  computed: {
    activeUserInfo () {
        // console.log(this.$store.state.AppActiveUser);
        return this.$store.state.AppActiveUser
    }
  },
  methods:{ 
    handleResize() {
        this.imgHeight = Math.round(this.$refs.imgSize.clientWidth/2);
    },
    editText() {
      this.nonEditText = false
    },
    getText() {
      axios.get('/api/gettext').then(response => {
        this.text = response.data
      })
    },
    getImg() {
      axios.get('/api/listimg').then(response => {
        this.images = response.data
      })
    },
    cancelConfirm() {
      this.nonEditText = true      
    },
    saveConfirm() {
      this.$vs.dialog({
        type: 'confirm',
        color: 'warning',
        title: `Confirm`,
        text: 'Are you sure you want to save?',
        accept: this.saveAll,
      })
    },
    saveAll() {
      this.countPush = 0;
      if(this.$store.state.AppActiveUser.role == 'superuser' || this.$store.state.AppActiveUser.role == 'admin'){
        if(this.file.length == 0 && this.selected.length == 0){
          this.countEnd = 1;
          this.saveText()
        }else if(this.selected.length == 0){
          this.countEnd = 2;
          this.saveText()   
          this.saveImg()       
        }else if(this.file.length == 0){
          this.countEnd = 2;
          this.saveText()   
          this.deleteImg()       
        }else{
          this.countEnd = 2;
          this.saveText()   
          this.saveImg()   
          this.deleteImg()      
        }
      }
    },
    saveImg() {
      this.$vs.loading({
          container: '#img-with-loading',
          scale: 0.6
      })

      const config = {
          headers: { 'content-type': 'multipart/form-data' }
      }

      const formData = new FormData();
      // console.log(this.file);

      // formData.append('file', this.file);
      for (let fileData of this.file) {
          formData.append("file[]", fileData);
      }
      
      axios.post('/api/uploadimg',formData).then(response => {
          // console.log(response);
          this.$vs.loading.close('#img-with-loading > .con-vs-loading')
          this.$vs.notify({
              time:4000,
              title: 'Succes',
              text: 'image has been uploaded.',
              color: 'success',
              iconPack: 'feather',
              icon:'icon-check'
          })    
          this.countPush += 1; 
      })
      .catch(err => {
          this.countPush += 1;
          if (err.response) {
              // client received an error response (5xx, 4xx)
              this.$vs.loading.close('#img-with-loading > .con-vs-loading')
          } else if (err.request) {
              // client never received a response, or request never left
              this.$vs.loading.close('#img-with-loading > .con-vs-loading')
          } else {
              // anything else
              this.$vs.loading.close('#img-with-loading > .con-vs-loading')
          }
          this.$vs.notify({
              time:4000,
              title: 'Warning',
              text: 'image failed to upload.',
              color: 'warning',
              iconPack: 'feather',
              icon:'icon-alert-circle'
          })   
      })
    },
    saveText() {
      this.$vs.loading({
          container: '#text-with-loading',
          scale: 0.6
      })
      axios.post('/api/savetext',{'text':this.text}).then(response => {
        this.$vs.loading.close('#text-with-loading > .con-vs-loading')
        this.countPush += 1;
      })
      .catch(err => {
          this.countPush += 1;
          if (err.response) {
              // client received an error response (5xx, 4xx)
              this.$vs.loading.close('#text-with-loading > .con-vs-loading')
          } else if (err.request) {
              // client never received a response, or request never left
              this.$vs.loading.close('#text-with-loading > .con-vs-loading')
          } else {
              // anything else
              this.$vs.loading.close('#text-with-loading > .con-vs-loading')
          }
      }) 
    },
    deleteImg() {
      this.$vs.loading({
          container: '#delete-with-loading',
          scale: 0.6
      })
      let list = []
      for(let i of this.selected){
        list.push(i['path'])
      }
      axios.post('/api/deleteimage',{'image':list}).then(response => {
        this.$vs.loading.close('#delete-with-loading > .con-vs-loading')
        this.countPush += 1;
      })
      .catch(err => {
          this.countPush += 1;
          if (err.response) {
              // client received an error response (5xx, 4xx)
              this.$vs.loading.close('#delete-with-loading > .con-vs-loading')
          } else if (err.request) {
              // client never received a response, or request never left
              this.$vs.loading.close('#delete-with-loading > .con-vs-loading')
          } else {
              // anything else
              this.$vs.loading.close('#delete-with-loading > .con-vs-loading')
          }
      }) 
    },
    discard() {
      this.nonEditText = true   
      this.$router.push(this.webPath.path).catch(() => {})
    }
  },
  beforeRouteLeave (to, from, next) {
    this.webPath = to
    if(this.nonEditText == false){
      this.$vs.dialog({
        type: 'confirm',
        color: 'warning',
        title: `Confirm`,
        text: 'Are you sure you want to leave this page without saving?',
        accept: this.discard,
      })
    }else{
      next()
    }
  },
  created() {
  },
  mounted() {
    window.addEventListener('resize', this.handleResize);
    this.handleResize();
    this.getText();
    this.getImg();
  }
}
</script>

<style lang="scss" scoped>
.editText {
  opacity: 100%;
  cursor: pointer;
  float: right;
  font-size: 15px;
  text-decoration:none;
  // margin-left: 20px;
  // top: 50%;
  // left: 50%;
  // transform: translate(-50%, -50%);
}

.editText:hover {
  opacity: 70%;
}

.borderText{
  // border: solid 1px #00000047;
  // border-radius: 5px;
  max-height: 10000px !important;;
  padding: 5px 0px;
  font-size: 15px;
}

.saveButton{
  float: right;
  padding: 5px 20px;
}

.imgResize{
  height: 100%;
  width: 100%;
}

.imgContainer{
  background-color:grey; 
  width: 100%;
  margin-bottom: 15px;
  border-radius:5px;
}

.imgTest{
  background-color:transparent; 
  width: 100%;
}
</style>
