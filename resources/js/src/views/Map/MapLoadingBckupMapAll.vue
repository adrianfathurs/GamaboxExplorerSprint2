<template>
  <div>
    <div class="vx-row">
      <div class="centerx example-loading">
          <div :class="{'activeLoading':activeLoading}"  v-for="type in types" :key="type" :id="[`loading-${type}`]" class="vs-con-loading__container loading-example">
          </div>      
      </div>
    </div>
    <div class="vx-row" >
      <div class="vx-col w-full  lg:w-5/5 xl:w-1/5 mb-base">
          <!-- <div>
            <div :class="{'activeLoading':activeLoading}"  v-for="type in types" :key="type" :id="[`loading-${type}`]" class="vs-con-loading__container loading-example">
            </div>
          </div> -->
        <vx-card
          title="Options"
          title-color="primary"
          :collapse-action="true"
          subtitle="Select and filter your map.">
            <div :class="activeOption">
              <div class="vx-row">
                <div class="vx-col w-full my-1 mb-2">
                  <v-select-tree :data='treeData' @node-select='nodechecked' v-model='initSelected' :radio="true"/>
                  <!-- <v-select-tree :data='treeData' v-model='initSelected' id='vue-tree' :radio="true"/> -->
                </div>
                  <div class="vx-col w-full lg:w-3/3 xl:w-3/3 my-1">
                    <div v-if="status">
                    <b-form-group
                      label="Filter:"
                      label-cols-sm="4"
                      label-cols-md="3"
                      label-cols-lg="2"
                      label-align-sm="left"
                      label-size="sm"
                      label-for="geoDataFilter"
                      class="mb-2"
                    >
                      <b-input-group size="sm">
                        <b-form-select v-model="filterSelected" id="geoDataFilter" :options="optionsFilter">
                          <template v-slot:first>
                            <option value="">--Please Select This Filter--</option>
                          </template>
                        </b-form-select>
                      </b-input-group>
                    </b-form-group>               
                    </div>
                </div>
                <div class="vx-col w-full lg:w-3/3 xl:w-3/3 my-1">
                  <b-form-group
                    label="Color:"
                    label-cols-sm="4"
                    label-cols-md="3"
                    label-cols-lg="4"
                    label-align-sm="left"
                    label-size="sm"
                    label-for="colorPicker"
                    class="mb-1"
                  >
                    <b-input-group size="sm">
                      <b-form-input id="colorPicker" v-model="fillColor" type="color" class="w-50"></b-form-input>
                      <b-form-input id="colorPicker" v-model="outerColor" type="color" class="w-50"></b-form-input>
                    </b-input-group>
                  </b-form-group>
                </div>
                
                    
              <!-- <div v-if="geojson" class="vx-col w-full my-1">
                  <div id="submit" style="margin-top: 10px;">
                    <b-button v-if="contentSelected" id="button-sb" type="submit" variant="primary" v-on:click="changeColor()">Change Color</b-button> 
                  </div>
                </div> -->
                <div class="vx-col w-full my-1">
             <!--  <div :class="alertArea">
                  <b-alert 
                  :show="alertShow"
                  variant="warning">
                    <h4 class="alert-heading">Warning</h4>
                    <p>
                      Please select the filter in the left side
                    </p>
                  </b-alert>
                  <b-alert 
                  :show="!alertShow"
                  variant="warning">
                    <h4 class="alert-heading">Attention</h4>
                    <p>
                      Please check map point what you needed in below, before push preview's button
                    </p>
                  </b-alert>
                </div> -->
                <div v-show="btnGroup">
                  <vs-divider position="center">
                   {{contentSelected.length}} box has been selected.
                  </vs-divider>
                </div>
                <div class="selected-content">
                  <b-form-group>
                    <b-form-checkbox-group
                      v-model="contentSelected"
                      :options="filterContent"
                      plain
                      stacked
                    ></b-form-checkbox-group>
                  </b-form-group> 
                <!-- sini -->
                </div>
                <div v-show="btnGroup" class="vx-col w-full mt-2 selected-button">
                      <div class="vx-row ">
                        <div class="vx-col lg:w-2/3 xl:w-2/3">
                        <b-button  id="button-sb" type="submit" variant="primary" @click.prevent="detailedpage" style="float:left; background-color:rgb(255, 111, 0) !important; border-color:rgb(255, 111, 0) !important;">Detailed Page</b-button>
                        </div>
                        <div class="vx-col lg:w-1/3 xl:w-1/3">
                        <b-button   id="button-sb" type="submit" variant="primary" style="float:right" v-on:click="submit">Preview</b-button>
                        </div>
                      </div>
                </div>
              </div>  
              </div>        
            </div>
        </vx-card>   
        <!-- <vx-card 
            title="Check for filter"
            title-color="primary"
            :collapse-action="true"
            :subtitle="contentSelected.length+' box has been selected.'">
            
        </vx-card> -->
      </div>
      <div class="vx-col w-full  lg:w-5/5 xl:w-4/5 mb-base">
        <l-map id="mapid" :zoom="zoom" :center="center" :options="{zoomControl: false}">
            <l-tile-layer
              v-for="tileProvider in tileProviders"
              :key="tileProvider.name"
              :name="tileProvider.name"
              :visible="tileProvider.visible"
              :url="tileProvider.url"
              :attribution="tileProvider.attribution"
              layer-type="base"/>
            <l-control-layers position="topright"  ></l-control-layers>
            <l-geo-json 
              :geojson="geojson"
              :options="options"
              :options-style="styleFunction" />
            <l-control-zoom position="bottomright"></l-control-zoom>        
            <l-control position="topleft">
              <div id="parentx">
              </div>
            </l-control>
        </l-map>
      </div>
    </div>      
      <div class="vx-col w-full mb-base">
      </div>
  </div>    
</template>

<script>

import {
  BAlert,
  BButton,
  BFormCheckboxGroup,
  BFormGroup,
  BFormInput,
  BFormSelect,
  BInputGroup
} from 'bootstrap-vue'

import {LMap, LTileLayer, LControlLayers, LGeoJson, LControlZoom, LControl} from 'vue2-leaflet';
import { VTree, VSelectTree}  from 'vue-tree-halower';
import vSelect from 'vue-select';

const axios = require('axios');
// const { JSDOM } = require('jsdom');
// const { window } = new JSDOM();

export default {
  components: {
    BAlert,
    BButton,
    BFormCheckboxGroup,
    BFormGroup,
    BFormInput,
    BFormSelect,
    BInputGroup,
    LMap,
    LControl,
    LGeoJson,
    LTileLayer,
    LControlZoom,
    LControlLayers,
    VTree,
    VSelectTree,
    'v-select': vSelect,
  },
  data () {
    return {
      /* tambahan */
       types:[
      'default',
     
    ],
    activeLoading:true,
      // 
      btnGroup:false,
      url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
      start: 0,
      end: 0,
      executionTime: 0,
      zoom: 4.5,
      center: [0.7893, 113.9213],
      fillColor: "#85c0ff",
      outerColor: "#037bfc",
      fixouterColor:null,
      fixfillColor:null,
      activeOption:"option",
      alertArea:"alertArea",
      geojson:[],
      saveGeoJson: [],
      active: true,
      notExpand: false,
      alertShow: true,
      reduce: true,
      enableTooltip: true,
      goToFile: false,
      btnSubmitStatus:false,
      geodata: [],
      map_info: {},
      status:false,
      statusLoading:false,
      node_data: {},
      optionsFilter: [],
      filterSelected: '',
      filterContent: [],
      contentSelected: [],
      tileProviders: [
        {
          name: 'OpenStreetMap',
          visible: true,
          attribution:
            '&copy; <a target="_blank" href="http://osm.org/copyright">OpenStreetMap</a> contributors',
          url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        },
        {
          name: 'OpenTopoMap',
          visible: false,
          url: 'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png',
          attribution:
            'Map data: &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>, <a href="http://viewfinderpanoramas.org">SRTM</a> | Map style: &copy; <a href="https://opentopomap.org">OpenTopoMap</a> (<a href="https://creativecommons.org/licenses/by-sa/3.0/">CC-BY-SA</a>)',
        },
        {
          name: 'Stadia_AlidadeSmooth',
          visible: false,
          url: 'https://tiles.stadiamaps.com/tiles/alidade_smooth/{z}/{x}/{y}{r}.png',
          attribution:
            '&copy; <a href="https://stadiamaps.com/">Stadia Maps</a>, &copy; <a href="https://openmaptiles.org/">OpenMapTiles</a> &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors',
        },
        {
          name: 'Stadia_AlidadeSmoothDark',
          visible: false,
          url: 'https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.png',
          attribution:
            '&copy; <a href="https://stadiamaps.com/">Stadia Maps</a>, &copy; <a href="https://openmaptiles.org/">OpenMapTiles</a> &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors',
        },
      ],
      initSelected: [],
      treeData: []  
    };
  },
  computed: {    
   
    activeUserInfo () {
      // console.log(this.$store.state.AppActiveUser)
      return this.$store.state.AppActiveUser
    },
    options() {
      return {
        onEachFeature: this.onEachFeatureFunction
      };
    },
    styleFunction() {
      // important! need touch outerColor in computed for re-calculate when change outerColor
      if(this.fixouterColor || this.fixfillColor){

          return ({
          weight: 2,
          color: this.fixouterColor,
          opacity: 1,
          fillColor: this.fixfillColor,
          fillOpacity: 1
          }) 

          } else {
            return ({
              weight: 2,
              color: this.outerColor,
              opacity: 1,
              fillColor: this.fillColor,
              fillOpacity: 1
              })      
        }
        
      
    },
    onEachFeatureFunction() {
      if (!this.enableTooltip) {
        return () => {};
      }
      return (feature, layer) => {
        let text = ''
        for(const [key, value] of Object.entries(feature.properties)){
          text += "<div>"+key+": "+value+"</div>";
        }
        layer.bindTooltip(
          text,
          { permanent: false, sticky: true }
        );
      };
    }
  },
  watch: {
    filterSelected() {
      /* this.contentSelected = []; */
      console.log("ini nyampe filter Selected");
      console.log(this.filterSelected.length);
      if(this.filterSelected.length != 0){
        this.alertShow = false;
        this.btnGroup=true,
        this.filterContent = this.geodata[this.filterSelected];
        this.filterContent.sort();
        this.activeOption="options";
        this.alertArea="alertAreas";
      }
      else{
        this.btnGroup=false,
        this.activeOption="option";
        this.alertArea="alertArea";
        this.alertShow = true;
        this.filterContent =[];
      
      }
    },
    outerColor(){
      this.fixouterColor=this.outerColor;
    },
    fillColor(){
      this.fixfillColor=this.fillColor;
    }

  },
  methods: {
    /* ta,bahan */
    openLoading(type){
      if(this.activeLoading){
        this.$vs.loading({
            type:type,
            text:"please wait a second. Your data is being processed..."
          })
          this.activeLoading=false;
        }else{
          this.$vs.loading.close();
          this.activeLoading=true;
          
        }
    },
    loadingButton(){
      if(this.activeLoading){
        this.openLoading();
        this.activeLoading=false;   
      }else{
        this.activeLoading=true;
      }
    },
    /* tambahan */
      /* coba(){
      if(this.activeLoading){
        this.load();
        this.activeLoading=false;
        console.log("masuk")
      }else{
        this.activeLoading=true;
      }
    },
    load(){
      this.types.forEach((type)=>{
      console.log(type)
      this.$vs.loading({
        container: `#loading-${type}`,
        type,
        
      })
    })
    }, */
    //
    async nodechecked (node, ctx, parent) {
      this.node_data = {}
      this.goToFile = false
      if(node.selected == true){
        this.goToFile = true
        
        this.node_data = node;
        console.log(node);
        this.map_info['path'] = node['path']
        this.map_info['extension'] = node['extension']
        this.map_info['id']=node['id']
        console.log("id bos",node['id']);
        this.alertShow = true;
        this.geojson = [];
        this.geodata = [];
        this.contentSelected = [];
        this.optionsFilter = [];
        this.filterSelected = '';
        this.filterContent = [];
        this.status=false;
        this.btnSubmitStatus=false;
        
        /*jika nama file nya sama dengan nama file yang ada di aaray vuex    
            maka datanya akan diambil dari vuex
        */
        localStorage.removeItem('lastMap');
        this.openLoading();
        await this.getGeoData();
       /*  await this.getGeoJson(); */  
        
      }
    },
    clickHandler () {
      window.alert('and mischievous')
    },
    detailedpage () {
      let check = Object.keys(this.node_data).length;
      
      if(check){
        // console.log(data)
        localStorage.setItem("dataset_info", JSON.stringify(this.node_data));
        this.$router.push('/detailedpage').catch(() => {})
      }else{
        var datainfo=localStorage.getItem("dataset_info");
        this.node_data=JSON.parse(datainfo);
        this.$router.push('/detailedpage')
      }
    },
    //percobaan
       async getGeoData(){
       try { 
        console.log(this.map_info['id']);
        var response = await axios.get('api/datalist/metadata',{
          params: {
            id : this.map_info['id'],
            }
        });
    //masih dlm bentuk text diambil dari db
        this.geodata=response.data.result.exportData;
        console.log(this.geodata);
    //convert ke json agar dapat dipakai di Object Keys.
        this.geodata = JSON.parse(this.geodata);
        this.optionsFilter=Object.keys(this.geodata)
        if(response){
            this.status=true;
            this.activeLoading=true;
            this.$vs.loading.close();
          }else{
            this.activeLoading=false;    
            this.status=false;
          }
      } catch (error) {
        console.log(error);
      } 
    }, 
    //ini sudah benar
    /*  async getGeoData() {
      try {
        const response = await axios.post('/api/geodata',this.map_info);
        this.geodata = response.data;
        console.log("json",this.geodata);
        this.optionsFilter = Object.keys(this.geodata);
         if(this.geodata){
            this.status=true;
            this.activeLoading=true;
            var filename = this.map_info['path'].replace(/^.*[\\\/]/, '')
            var obj={
              filenamed:filename,
              cordinate:response.data,
            }
            await this.$store.dispatch("map/saveDataMap",obj); 
          }else{
            this.activeLoading=false;
            this.status=false;
          }
        console.log("ini this geodata di fungsi get Geo Data")
        console.log(this.geodata);
      } catch (error) {
        console.error(error);
      }
    },    */
    //////////////////////////
    /* getGeoData () {
      axios.post('/api/geodata',this.map_info).then(response => {
        this.geodata = response.data;
          console.log(this.geodata);
          this.optionsFilter = Object.keys(this.geodata);
      });
    }, */

    getGeoJson () {
      axios.post('/api/mapshapefile',this.map_info).then(response => {
        console.log("iki kabeh diload");
        console.log(response.data);
      });
    },

    //default
    /* postGeoJson () {
      let postData = {}      
      postData['key'] = this.filterSelected
      postData['path'] = this.map_info['path']
      postData['extension'] = this.map_info['extension']
      postData[this.filterSelected] = this.contentSelected
      axios.post('/api/shapefile',postData).then(response => {
          this.geojson = response.data;
          
          this.saveGeoJson = response.data;
          console.log("Ini ksampai Post Geo Json");
          console.log(this.geojson)
          this.end = performance.now();
          this.executionTime = (this.end - this.start)/1000;
      });
    }, */

    async postGeoJson() {
      try {
        let postData = {}       
        postData['key'] = this.filterSelected 
        /* console.log(this.contentSelected);
        console.log("---------------")
        console.log(this.filterSelected)
        console.log(this.postData) */
        
        postData['path'] = this.map_info['path']
        postData['extension'] = this.map_info['extension']
        postData['id'] = this.map_info['id']
        // postData[this.filterSelected] = this.contentSelected
        postData['contentSelected'] = this.contentSelected
        /* console.log("ini post data");
        console.log(postData); */
          /* const response = await axios.post('/api/shapefile',postData); */
          const response = await axios.post('/api/shapefile',postData);
          if(response){  
            this.activeLoading=true;
            this.$vs.loading.close();
            this.status=true;
          }else{
            this.activeLoading=false;
            this.status=false;
          }
          /* mau mengambil data menggunakan localStorage
          1.this.geojson,this.status,this.map_info,this.contentSelected,this.filterSelected
          */
         console.log("ini response");
         console.log(response.data);

          this.geojson= response.data;
          this.saveGeoJson=response.data;
            var mapLast={
            geojson:this.geojson,
            geodata:this.geodata,
            status:this.status,
            path:this.map_info['path'],
            extension:this.map_info['extension'],
            id:this.map_info['id'],
            contentSelected:this.contentSelected,
            filterSelected:this.filterSelected
          } 
          try{
            localStorage.setItem('lastMap',JSON.stringify(mapLast));
          }
          catch(error){
            localStorage.removeItem('lastMap');
            alert("Data Terlalu besar session Data Peta tidak tersimpan");
            this.activeLoading=true;
            this.$vs.loading.close();
            this.status=true;
            this.geojson= response.data;
          }  
      } catch (error) {
        console.log(error);
        /* alert("processing still running, but because data is big, so its not save o localstorage !"); */
      }
    },
    
    changeColor() {
      this.fixfillColor = this.fillColor; // important! need touch fillColor in computed for re-calculate when change fillColor
      this.fixouterColor = this.outerColor; // important! need touch outerColor in computed for re-calculate when change outerColor
      console.log(this.fixouterColor,this.fixfillColor)
    },

    async submit () {
      this.start = performance.now();
      if(this.filterSelected.length != 0 && this.contentSelected.length != 0){
        this.openLoading();
        console.log(this.filterSelected.length,this.contentSelected.length)
        await this.postGeoJson();
        await this.changeColor();
        
      }else{
        if(this.activeLoading){
          this.activeLoading=true;
        }else{
          this.activeLoading=true;
        }
        this.geojson = this.saveGeoJson;
      }
    }
  },

  async created() {
  try {
    const response = await axios.post('/api/shp',{'created_by':this.activeUserInfo.displayName}); 
    /* const response = await axios.get('/api/tampilData'); */
    console.log(response.data);
    console.log(response.data);
    this.treeData = response.data;
    return this.treeData; 
  } catch (error) {
    console.error(error);
  }
},
  
  /* created () {
    axios.post('/api/shp',{'created_by':this.activeUserInfo.displayName}).then(response => {
      this.treeData = response.data;
        return this.treeData; 
    });
  }, */
  
  mounted () {
    /* localStorage.removeItem("dataset_info"); */
      var lastMap=JSON.parse(localStorage.getItem('lastMap'));
    
      if (lastMap){
          this.geojson=lastMap.geojson;
          this.status=lastMap.status;
          this.contentSelected=lastMap.contentSelected;
          console.log(this.contentSelected);
          this.filterSelected=lastMap.filterSelected;
          this.geodata = lastMap.geodata;
          this.optionsFilter=Object.keys(this.geodata);
          console.log(this.filterContent);
          this.fixfillColor=this.fillColor;
          this.fixouterColor=this.outerColor;
          this.map_info['path']=lastMap.path;
          this.map_info['extension']=lastMap.extension;
          this.map_info['id']=lastMap.id;
    }    
     if(!this.geojson){
      this.geojson=[];
      return this.geojson;
    } 
    // this.map_info = JSON.parse(localStorage.getItem("detailedmap_info"));
    // this.getGeoJson()
    // this.getGeoData()
  }
}
</script>

<style lang="scss">
@import "@sass/vuexy/extraComponents/tree.scss";
.tree-container {
  width: 100% !important;
  z-index: 9999;
}

.tree-box{
  background: #fff;
  position: relative;
  z-index: 9999;

  .search-input {
    margin-top: 10px;
    width: 98%;
    display: block;
  }
}

.rmNode {
  background-color: rgba(var(--vs-danger),0.15);
  color: rgba(var(--vs-danger),1);
  line-height: 13px;
}
.alertArea{
  height: 10px;
}
.alertAreas{
  height: 100px;
  
}
@media(max-width: 719px){
  .alertAreas{
    height: 100px;
    margin-bottom:8px;
  }
}
@media(max-width: 413px){
  .alertAreas{
    height: 100px;
    margin-bottom:30px;
  }
}
@media(max-width: 413px){
  .alertAreas{
    height: 100px;
    margin-bottom:50px;
  }
}
.selected-content {
  height: 150px;
  overflow-y: auto;
}
.selected-button{
  height: 40px;
  padding-top:15px;
}
.options {
  height: 400px;
}

@media(max-width: 769px){
.options {
  height: 390px;
}
}
@media(max-width: 575px){
.options {
  height: 430px;
}
}

.option{
  height: 200px;
}

#button-sb {
  float: right
}
// Map
#mapid { 
    height: 70vh; 
}

#parentx {
  overflow: hidden;
  height: 67vh;
  width: 200px;
  position: relative;
}

.vs-sidebar.vs-sidebar-reduce {
    max-width: 50px;
    border-radius: 10px 10px 10px 10px;
}

.header-sidebar {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  width: 100%;
  h4 {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    > button {
      margin-left: 10px;
    }
  }
}

.footer-sidebar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
  > button {
    border: 0px solid rgba(0, 0, 0, 0) !important;
    border-left: 1px solid rgba(0, 0, 0, 0.07) !important;
    border-radius: 0px !important;
  }
}
</style>
<style lang="stylus">
.fill-row-loading
  display flex
  align-items center
  justify-content center
  flex-wrap wrap
  .loading-example
    width 120px;
    float left
    height 120px;
    box-shadow 0px 5px 20px 0px rgba(242,239,239,.05)
    border-radius 10px;
    margin 8px
    transition all .3s ease
    cursor pointer
    &:hover
      box-shadow 0px 0px 0px 0px rgba(0,0,0,.05)
      transform translate(0,4px)
    h4
      z-index 40000
      position relative
      text-align center
      padding 10px

    &.activeLoading
      opacity 0 !important
      transform scale(.5)
</style>


 