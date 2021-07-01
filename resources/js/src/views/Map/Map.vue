<template>
  <div>
    <div class="vx-row">
      <div class="vx-col w-full md:w-3/3 lg:w-2/5 xl:w-2/5 mb-base">
        <vx-card
          title="Options"
          title-color="primary"
          :collapse-action="true"
          subtitle="Select and filter your map.">
            <div class="options">
              <div class="vx-row">
                <!-- <div class="vx-col w-full my-1">
                  <v-select-tree :data='treeData' v-model='initSelected' :radio="true"/>
                </div> -->
                <div class="vx-col w-full lg:w-2/3 xl:w-2/3 my-1">
                  <b-form-group
                    label="Filter:"
                    label-cols-sm="4"
                    label-cols-md="3"
                    label-cols-lg="2"
                    label-align-sm="right"
                    label-size="sm"
                    label-for="geoDataFilter"
                    class="mb-0"
                  >
                    <b-input-group size="sm">
                      <b-form-select v-model="filterSelected" id="geoDataFilter" :options="optionsFilter">
                        <template v-slot:first>
                          <option value="">-- none --</option>
                        </template>
                      </b-form-select>
                    </b-input-group>
                  </b-form-group>               
                </div>
                <div class="vx-col w-full lg:w-1/3 xl:w-1/3 my-1">
                  <b-form-group
                    label="Color:"
                    label-cols-sm="4"
                    label-cols-md="3"
                    label-cols-lg="4"
                    label-align-sm="right"
                    label-size="sm"
                    label-for="colorPicker"
                    class="mb-0"
                  >
                    <b-input-group size="sm">
                      <b-form-input id="colorPicker" v-model="fillColor" type="color" class="w-50"></b-form-input>
                      <b-form-input id="colorPicker" v-model="outerColor" type="color" class="w-50"></b-form-input>
                    </b-input-group>
                  </b-form-group>
                </div>
                <div class="vx-col w-full my-1">
                  <div id="submit">
                    <b-button id="button-sb" type="submit" variant="primary" v-on:click="submit">Preview</b-button>
                    <!-- Execution time: {{executionTime}} s -->
                  </div>
                </div>
              </div>        
            </div>
        </vx-card>   
      </div>
      <div class="vx-col w-full md:w-3/3 lg:w-3/5 xl:w-3/5 mb-base">
        <vx-card
            title="Check for filter"
            title-color="primary"
            :collapse-action="true"
            :subtitle="contentSelected.length+' box has been selected.'">
            <div class="selected-content">
              <b-alert 
              :show="alertShow"
              variant="success">
                <h4 class="alert-heading">Warning</h4>
                <p>
                  Please select the filter in the left side
                </p>
              </b-alert>
              <b-form-group>
                <b-form-checkbox-group
                  v-model="contentSelected"
                  :options="filterContent"
                  plain
                  stacked
                ></b-form-checkbox-group>
              </b-form-group> 
            </div>
        </vx-card>
      </div>
      <div class="vx-col w-full mb-base">
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
      url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
      start: 0,
      end: 0,
      executionTime: 0,
      zoom: 5,
      center: [0.7893, 113.9213],
      fillColor: "#85c0ff",
      outerColor: "#037bfc",
      geojson: [],
      saveGeoJson: [],
      active: true,
      notExpand: false,
      alertShow: true,
      reduce: true,
      enableTooltip: false,
      geodata: {},
      map_info: {},
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
    options() {
      return {
        onEachFeature: this.onEachFeatureFunction
      };
    },
    styleFunction() {
      const fillColor = this.fillColor; // important! need touch fillColor in computed for re-calculate when change fillColor
      const outerColor = this.outerColor; // important! need touch outerColor in computed for re-calculate when change outerColor
      return () => {
        return {
          weight: 2,
          color: outerColor,
          opacity: 1,
          fillColor: fillColor,
          fillOpacity: 1
        };
      };
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
    initSelected() {
      this.start = performance.now();
      if(this.initSelected.length != 0){
        let extension = this.initSelected[0].split('.').pop();
        this.alertShow = true;
        this.geojson = [];
        this.geodata = [];
        this.contentSelected = [];
        this.optionsFilter = [];
        this.filterSelected = '';
        this.filterContent = [];
        this.getGeoData();
        this.getGeoJson();
      }
    },
    filterSelected() {
      this.contentSelected = [];
      if(this.filterSelected.length != 0){
        this.alertShow = false;
        this.filterContent = this.geodata[this.filterSelected];
        this.filterContent.sort();
      }
    }
  },
  methods: {
    clickHandler () {
      window.alert('and mischievous')
    },
    getGeoData () {
      axios.post('/api/geodata',this.map_info).then(response => {
          this.geodata = response.data;
          this.optionsFilter = Object.keys(this.geodata);
      });
    },
    getGeoJson () {
      axios.post('/api/mapshapefile',this.map_info).then(response => {
          this.geojson  = response.data;
          this.saveGeoJson = response.data;
          this.end = performance.now();
          this.executionTime = (this.end - this.start)/1000;
      });
    },
    postGeoJson () {
      let postData = {}      
      postData['key'] = this.filterSelected
      postData['path'] = this.map_info['path']
      postData['extension'] = this.map_info['extension']
      postData[this.filterSelected] = this.contentSelected
      axios.post('/api/shapefile',postData).then(response => {
          this.geojson = response.data;
          this.end = performance.now();
          this.executionTime = (this.end - this.start)/1000;
      });
    },
    submit () {
      this.start = performance.now();
      if(this.filterSelected.length != 0 && this.contentSelected.length != 0){
        this.postGeoJson()
      }else{
        this.geojson = this.saveGeoJson;
      }
    }
  },
  async created () {
  },
  mounted () {
    this.map_info = JSON.parse(localStorage.getItem("detailedmap_info"));
    this.getGeoJson()
    this.getGeoData()
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

.selected-content {
  height: 150px;
  overflow-y: auto;
}

.options {
  height: 150px;
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
