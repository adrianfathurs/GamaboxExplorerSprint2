<template >
  <b-container id="div-upload-loading" class="vs-con-loading__container ">
    <b-card  >
      <b-card-title>
        <h1>{{articleData.title}}</h1>
      </b-card-title>
      <b-card-sub-title>
        <span> created at : {{this.date}} | upload by : {{user.username}}</span>
      </b-card-sub-title>
      <hr>
      <b-card-text>
        <img class="pl-2 pt-2" :src="articleData.image" width="300px" height="300px" style="float:right;" alt="image">
        <p  style="textAlign:justify; text-indent: 0.5in;">{{this.description}} </p>
        
      </b-card-text>
    </b-card>
  </b-container>
</template>
<script>
import { BCard } from 'bootstrap-vue'

const axios = require('axios');
  export default{
    data(){
      return({
        articleData:{},
        user:{},
        description:'',
        date:'',
        

      })
    },
    components:{BCard},
    
    methods: {
      async getArticleById(id_article){
        try {
          var response =await axios.get('/api/articles',{
          params:{
            id:id_article,
          }
        });
          this.articleData=response.data.result;
          this.user=this.articleData.user;
          
          var dateTime=new Date(this.articleData.created_at).getTime();
          var dateDay=new Date(dateTime).getDate()-1;
          var dateMonth=new Date(dateTime).getMonth()+1;
          var dateYear=new Date(this.articleData.created_at).getFullYear();
          /* this.date=dateDay+'/'+dateMonth+'/'+dateYear; */
          if( dateDay<10 && dateMonth<10){
            this.date='0'+dateDay+' - 0'+dateMonth+' - '+dateYear;
          }else if(dateDay<10 && dateMonth>=10){
            this.date='0'+dateDay+' - '+dateMonth+' - '+dateYear;
          }else if(dateDay>=10 && dateMonth<10){
            this.date=dateDay+' - 0'+dateMonth+' - '+dateYear;
          }else{
            this.date=dateDay+' - '+dateMonth+' - '+dateYear;

          }
          let strippedString = this.articleData.description.replace(/(<([^>]+)>)/gi, "");
          this.description=strippedString.replace(/<[^>]*(>|$)|&nbsp;|&zwnj;|&raquo;|&laquo;|&gt;/g, ' ');
        } catch (error) {
          console.log(error)
        }
        
      }
    },
    async mounted(){
      var id_article=localStorage.getItem('idArticle')
      await this.getArticleById(id_article);

    }
  }
</script>
<style >
@media(max-width: 360px ){
  img{
    width: 200px;
    height: 200px;
  }
}
@media(max-width: 415px ){
  img{
    width: 220px;
    height: 200px;
  }
}
</style>