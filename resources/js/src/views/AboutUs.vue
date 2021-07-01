
<template>
        <div class=" " >

        <b-container  id="div-upload-loading"  class="bv-example-row bv-example-row-flex-cols vs-con-loading__container" >
            
            <div v-if="buttonAddInsight==false && activeUserInfo.role=='superuser' || buttonAddInsight==false && activeUserInfo.role=='admin' " id="buttonAdd" class="float-right mb-4">
                <a href="#form" @click="addInsight()"><b-button  variant="primary">Add Article</b-button></a>
            </div>
            
            <div class="clc"></div>
            <b-row class=" mb-6">
                <b-col col lg="4" md="6" sm="12" v-for="(item,index) in dataArticle" :key="index">
                    <b-card-group deck>
                        <b-card class=" mb-6">
                            <img class="pl-2 pt-2" :src="item.image" width="320px" height="300px"  alt="image">
                            <b-card-text>
                                <h4>{{item.title}}</h4>
                                <p class="float-right">Uploaded by : {{item.user.username}}</p>
                                <div class="clc"></div>
                            </b-card-text>
                            <b-card-footer v-if=" activeUserInfo.role=='superuser' ||  activeUserInfo.role=='admin'">
                                <b-row class="text-center">
                                    <b-col class="mb-2" col lg="4" md="12" sm="4">
                                <a href="#form"> <b-button  @click="editArticle(item)"  variant="outline-warning">Edit</b-button></a>

                                    </b-col>
                                    <b-col class="mb-2" col lg="4" md="12" sm="4">

                                <b-button @click="detailedArticle(item)" variant="outline-primary">Detail</b-button>
                                    </b-col>
                                    <b-col class="mb-2" col lg="4" md="12" sm="4">

                                <b-button  @click="deleteArticle(item.id)" variant="danger">Delete</b-button>
                                    </b-col>
                                </b-row>
                                
                            </b-card-footer>
                            <b-card-footer v-else>
                                <b-button class="float-right" @click="detailedArticle(item)" variant="outline-primary">Detail</b-button>
                                <div class="clc"></div>
                            </b-card-footer>
                        </b-card>
                    </b-card-group>
                </b-col>
                
            </b-row>
            <div v-if="dataArticle.length>0" class="mt-3">
                <h6 class="text-center">Choose Page</h6>
                    <b-pagination-nav
                        
                        align="center"
                        v-model="currentPage"
                        :number-of-pages="pages"
                        base-url="#"
                        first-number
                    ></b-pagination-nav>
                
            </div>
            <div v-else class="mt-3">
                <center>

                <b-button  variant="outline-secondary">Tidak Ada Data</b-button>
                </center>
            </div>
            <div id="form">
                <div v-show="buttonAddInsight">
                     <UploadInsight :edit="itemChoose" @clicked="changeStatusBtn"></UploadInsight>
                  <!--   <MapLoading :edit="itemChoose" @clicked="changeStatusBtn"></MapLoading> -->
                </div>
            </div>
        </b-container>
    
    </div>
</template>

<script>

import { BPaginationNav } from 'bootstrap-vue'
import {
    BFormFile,
  BFormTags,BForm 
} from 'bootstrap-vue'
    import MapLoading from './Map/MapLoading.vue'
import UploadInsight from './UploadInsight.vue';
const axios = require('axios');
    export default {
        name: 'app',
        components:{
            BFormFile,BFormTags,BForm,
                UploadInsight,BPaginationNav,MapLoading
        },
        data() {
            return ({

                editorData: '<p>Content of the editor.</p>',
                editorConfig: { 
                },
                pages:1,
                currentPage: 1,
                buttonAddInsight:false,
                dataArticle:"",
                idArticleChoose:null,
                itemChoose:[],
                user:{},
            })
            
        },
        watch:{
            async currentPage(){
                try {
                    console.log("curent pafe",this.currentPage)
                     var resultOffset=(this.currentPage-1)*6
                        var response= await axios.get('api/articles',{
                            params:{
                                user_id:this.dataArticle.user_id,
                                offset:resultOffset,
                                limit:6,
                                status:"publish"
                            }
                        }) 
                    
                    this.dataArticle=response.data.result;
                    console.log(this.dataArticle);
                } catch (error) {
                    console.log(error)  
                }
            }
        },
        computed: {
            activeUserInfo () {
              // console.log(this.$store.state.AppActiveUser);
                return this.$store.state.AppActiveUser
            },
        },
        methods: {
            linkGen(pageNum) {
                console.log("jdf")
                console.log(pageNum)
            },
            async getDataArticle(){
                try {
                    this.$vs.loading({
                            container: '#div-upload-loading',
                            text:"please wait a second. Your data is being processed...",
                            scale: 0.6
                        }) 
                    var res=await axios.get('api/articles');
                    var response= await axios.get('api/articles',{
                                params:{
                                    user_id:this.dataArticle.user_id,
                                    offset:0,
                                    limit:6,
                                    status:"publish"
                                }
                            }) 
                    this.dataArticle=response.data.result;
                    
                    if(res.data.result.length % 6 == 0)
                    {
                        this.pages=res.data.result.length/6;
                        this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
                        
                        console.log("hasil",this.pages)
                    }else{
                        this.pages=Math.floor(res.data.result.length/6)+1;
                      
                            this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
                            
                        
                        
                    }

                    console.log("lenght nya data ",this.dataArticle.length)
                } catch (error) {
                    console.log(error)    
                }
                
            },
            submitForm(){
            this.$vs.notify({
                color:'success',
                title:'Successfully',
                text:'Data has been saved'
                })
            },
            addInsight(){
                this.buttonAddInsight=true;
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
            acceptAlert(){
                this.buttonAddInsight=false;

            },
            changeStatusBtn(value){
                this.buttonAddInsight=value; 
            },
            deleteArticle(id){
                this.idArticleChoose=id
                this.$vs.dialog({
                type:'confirm',
                color: 'danger',
                title: `Confirm`,
                text: 'if you press the accept button, the data changes will not be saved',
                accept:this.acceptDelete
            })
            },
            async acceptDelete(){
                try {
                    this.$vs.loading({
                            container: '#div-upload-loading',
                            text:"please wait a second. Your data is being processed...",
                            scale: 0.6
                        }) 
                    var body={
                        'id':this.idArticleChoose
                    }
                    var response=await axios.post('api/articles/delete',body)
                    if(response.data.message=="Success deleted article"){
                        await this.getDataArticle()
                        this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
                        this.$vs.notify({
                            color:'success',
                            title:'Successfully',
                            text:"Success add article"
                            })
                    }else{
                        this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
                        this.$vs.notify({
                            color:'danger',
                            title:'Failed',
                            text:"Failed to delete article"
                            })
                    }
                    
                } catch (error) {
                    console.log(error)
                    this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
                        this.$vs.notify({
                            color:'danger',
                            title:'Failed',
                            text:"Article is not found"
                            })
                }
            },
            editArticle(item){
                this.buttonAddInsight=true;
                this.itemChoose=item;
            },
            detailedArticle(item){
                localStorage.setItem('idArticle',item.id)
                 this.$router.push({
                    name:'detailedArticle-page',
                    params:{
                        id:item.id
                    }
                });
            }
        },
        async mounted() {
            console.log("mounted")
            try {
                await this.getDataArticle();
            } catch (error) {
                console.log(error)
            }
        },
}
</script>
<style >
.float-right{
    float: right;
    margin-left: 40px;
}
.clc{
    clear: both;
}
</style>

