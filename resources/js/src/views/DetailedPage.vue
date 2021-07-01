<template>
    <div>
        <div class="vx-row">
            <div id="scrollPosition" class="vx-col w-full md:w-3/3 lg:w-3/5 xl:w-3/5 mb-base">
                <vx-card
                    title="Dataset Information">
                        <div class="vx-row">
                            <div class="vx-col ml-3 mt-4 w-2/4 mr-3 mb-base">
                                <i class="fa fa-file-archive-o fa-4x" aria-hidden="true"></i>
                            </div>

                            <div class="vx-col mt-2 w-4/4 mb-base" v-if="infoDataset.length > 0">
                                <p><b>Title</b>            : {{infoDataset[0].dataset_name}}</p>
                                <p><b>Uploader </b>        : {{infoDataset[0].created_by}}, {{infoDataset[0].job_title}} di {{infoDataset[0].company}} </p>
                                <p><b>Uploaded Date </b>   : {{infoDataset[0].created_date}}</p>
                                <p><b>Category   </b>      : {{infoDataset[0].category}}</p>
                                
                                <p><b>Subcategory</b>      : {{infoDataset[0].subcategory}}</p>
                                <p v-if="infoDataset[0].tag"><b>Tagging  </b>        : {{infoDataset[0].tag.replace(";", ",")}}</p>
                            </div>
                            <div class="vx-col w-full md:w-3/3 mb-2">
                                <p>{{infoDataset.description}}</p>
                                <hr>
                            </div>
                            <vx-card
                                title="Detail"
                                card-background="#ffffff"
                                :collapse-action="true"
                                style="box-shadow: 0 0 black;"
                                >
                                <div class="vx-row ml-2">
                                    <div class="vx-col w-full">
                                        <div class="vx-row">
                                            <div class="vx-col w-3/5">
                                                <p>Total Size</p>
                                                <p>Total Files</p>
                                                <p>Total Views</p>
                                                <p>Total Downloads</p>
                                            </div>
                                            <div class="vx-col w-2/5">
                                                <p>{{sizeFile}}Mb</p>
                                                <p>{{totalFile}}</p>
                                                <p>{{view_counter}} Views</p>
                                                <p>{{download_counter}} Downloads</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </vx-card>
                        </div>
                </vx-card>
            </div>

            <div class="vx-col w-full md:w-3/3 lg:w-2/5 xl:w-2/5 mb-base">
                <vx-card
                    title="Available File"
                    class="mb-base"
                    :collapse-action="true"
                    >
                        <b-card-group deck>
                            <b-card
                                header-tag="header"
                            >
                                <template v-slot:header>
                                <h6 class="mb-0 header-title">
                                    <i class="fas fa-file"></i>
                                    {{totalRows}} File
                                </h6>
                                </template>
                                <!-- Main table element -->
                                <b-container fluid>
                                    <b-table
                                        show-empty
                                        small
                                        sticky-header
                                        class="table_files"
                                        :items="available_file"
                                    >
                                    <template #cell(title)="data">
                                      <!-- `data.value` is the value after formatted by the Formatter -->
                                      <a :href="'api/downloadfile/'+data.value.filepath" style="cursor:pointer;">{{ data.value.filename }}</a>
                                    </template>
                                    </b-table>
                                </b-container>
                            </b-card>
                        </b-card-group>
                        <div class="vx-row">
                            <div class="vx-col w-full mt-3">
                                <!-- <div class="vx-col mt-2 w-4/4 mb-base" v-if="infoDataset.length > 0">
                                    <p>Title            : {{infoDataset[0].dataset_name}}</p>
                                    <p>Uploader         : {{infoDataset[0].created_by}}</p>
                                    <p>Uploaded Date    : {{infoDataset[0].created_date}}</p>
                                    <p>Category         : {{infoDataset[0].category}}</p>
                                    <p>Subcategory      : {{infoDataset[0].subcategory}}</p>
                                    <p>Tagging          : {{infoDataset[0].tag.replace(";", ",")}}</p>
                                </div> -->
                                <div class="vx-col mt-2 w-4/4 mb-base" v-if="infoDataset.length > 0">
                                    <p>Title            : {{infoDataset[0].dataset_name}}</p>
                                    <p>Uploader         : {{infoDataset[0].created_by}}</p>
                                    <p>Uploaded Date    : {{infoDataset[0].created_date}}</p>
                                    <p>Category         : {{infoDataset[0].category}}</p>
                                    <p>Subcategory      : {{infoDataset[0].subcategory}}</p>
                                    <p v-if="infoDataset[0].tag"> Tagging          : {{infoDataset[0].tag.replace(";", ",")}}</p>
                                </div>

                            <!-- Jika  tag button adalah las maka hide custom file preview disable -->
                              <b-button v-if="fileDisplay === false && typeFile != 'las' " class="float-left" style="background-color:#007bff;" type="submit" v-on:click="display">Show Custom File Preview</b-button>
                              <b-button v-else-if="fileDisplay === true && typeFile == 'las' " class="float-left" style="background-color:#007bff;" type="submit" v-on:click="display">Hide Custom File Preview</b-button>
                              <b-button class="float-right" style="background-color:rgb(255, 111, 0);" type="submit" @click="downloadThisZip">Bulk Download</b-button>
                            </div>
                        </div>
                </vx-card>
            </div>
            <div v-if="fileDisplay === true" class="vx-col w-full mb-base">
                <div class="vx-row">
                    <div class="vx-col w-full md:w-3/3 lg:w-2/5 xl:w-2/5 mb-base">

                    <vx-card
                        title="Filter Chart"
                        card-background="#ffffff"
                        class="mb-3"
                        :collapse-action="true"
                        >
                        <div class="vx-row">
                            <div class="vx-col w-full my-1">
                                <v-select-tree :data='treeData' v-model='initSelected' :radio="true"/>
                            </div>
                            <div v-if="chartOption" class="vx-col w-full lg:w-1/2 xl:w-1/2 my-1">
                                <b-form-group
                                label="X:"
                                label-cols-sm="4"
                                label-cols-md="3"
                                label-cols-lg="1"
                                label-align-sm="right"
                                label-size="sm"
                                label-for="xLine"
                                class="mb-0"
                                >
                                    <b-input-group size="sm">
                                        <b-form-select
                                        v-model="XLine"
                                        id="xLine"
                                        size="sm"
                                        class="w-65"
                                        :options="keyTable"
                                        ></b-form-select>
                                        <b-form-select v-model="XType" size="sm" :disabled="!XLine" class="w-35">
                                            <template v-slot:first>
                                                <option value="">-- type --</option>
                                            </template>
                                            <option value="number">number</option>
                                            <option value="date">date</option>
                                        </b-form-select>
                                    </b-input-group>
                                </b-form-group>
                            </div>
                            <div v-if="chartOption" class="vx-col w-full lg:w-1/2 xl:w-1/2 my-1">
                                <b-form-group
                                label="Y:"
                                label-cols-sm="4"
                                label-cols-md="3"
                                label-cols-lg="1"
                                label-align-sm="right"
                                label-size="sm"
                                label-for="yLine"
                                class="mb-0"
                                >
                                <b-input-group size="sm">
                                    <b-form-select
                                    v-model="YLine"
                                    id="yLine"
                                    size="sm"
                                    class="w-65"
                                    :options="keyTable"
                                    ></b-form-select>
                                    <b-form-select v-model="YType" size="sm" :disabled="!YLine" class="w-35">
                                    <template v-slot:first>
                                        <option value="">-- type --</option>
                                    </template>
                                    <option value="number">number</option>
                                    <option value="alphabet">alphabet</option>
                                    </b-form-select>
                                </b-input-group>
                                </b-form-group>
                            </div>
                        </div>
                        <div v-if="XType=='date'" class="vx-col w-full my-1">
                            <b-form-group
                            label="Date Format:"
                            label-cols-sm="6"
                            label-cols-md="4"
                            label-cols-lg="3"
                            label-align-sm="right"
                            label-size="sm"
                            label-for="dateFormat"
                            class="mb-0"
                            >
                            <b-form-select
                                v-model="dateFormat"
                                id="dateFormat"
                                size="sm"
                                :options="dateListFormat"
                            ></b-form-select>
                            </b-form-group>
                        </div>
                        <div class="vx-col w-full my-1">
                            <div id="submit">
                            <b-button type="submit" variant="primary" v-on:click="submit">Choose File</b-button>
                            </div>
                        </div>
                    </vx-card>


                    <b-card-group deck>
                        <b-card
                        v-if="chartOption"
                        header-tag="header"
                        footer-tag="footer"
                        >
                        <template v-slot:header>
                            <h6 class="mb-0 header-title">
                            <i class="fa fa-table" aria-hidden="true"></i>
                            Table Preview <small>(show 50)</small>
                            </h6>
                        </template>
                        <template v-slot:footer>
                            <h6 class="mb-0 header-title">
                            <b-pagination
                                v-model="currentPage"
                                :total-rows="totalRows"
                                :per-page="perPage"
                                align="fill"
                                size="sm"
                                class="my-0"
                            ></b-pagination>
                            </h6>
                        </template>
                        <!-- Main table element -->
                        <b-container style="overflow:auto; height:400px;" fluid>
                            <b-alert
                            :show="alertTable"
                            variant="success">
                            <h4 class="alert-heading">Warning</h4>
                            <p>
                                Please adjust the filter above for previewing the chart
                            </p>
                            <hr>
                            <p class="mb-0">
                                Thanks.
                            </p>
                            </b-alert>

                            <b-table
                            show-empty
                            small
                            class="table_xls"
                            stacked="md"
                            sticky-header
                            :items="dataChart"
                            :fields="fieldsTable"
                            :current-page="currentPage"
                            :per-page="perPage"
                            @filtered="onFiltered"
                            >
                            </b-table>

                            <!-- Info modal -->
                            <b-modal :id="infoModal.id" :title="infoModal.title" ok-only @hide="resetInfoModal">
                            <pre>{{ infoModal.content }}</pre>
                            </b-modal>
                        </b-container>
                        </b-card>
                    </b-card-group>
                    </div>
                    <div class="vx-col w-full md:w-3/3 lg:w-3/5 xl:w-3/5 mb-base">
                    <b-card-group deck>
                        <b-card
                        header-tag="header"
                        footer-tag="footer"
                        >
                        <template v-slot:header>
                            <h6 v-if="MapDisabled===false" class="mb-0 header-title">
                            <i class="fas fa-map-marked-alt"></i>
                            Map Preview
                            <b-button  class="float-right" v-if="MapDetail" type="submit" variant="primary" v-on:click="previewmap">Full Map Preview</b-button>
                            </h6>
                            <h6 v-else-if="ChartDisabled===false" class="mb-0 header-title">
                            <i class="fas fa-chart-line"></i>
                            Chart Preview
                            </h6>
                        </template>
                        <template v-slot:footer>
                            <h6 class="mb-0 header-title" v-if="tabIndex == 0">
                            {{mapTitle}}
                            </h6>
                            <h6 class="mb-0 header-title" v-else-if="tabIndex == 1">
                            {{chartTitle}}
                            </h6>
                        </template>
                        <b-tabs v-model="tabIndex"  pills card vertical end lazy>
                            <b-tab active :disabled="MapDisabled">
                            <template v-slot:title>
                                <i class="fa fa-map" aria-hidden="true"></i>
                                Map
                            </template>
                            <div>
                                <l-map ref="mapControl" id="mapid" :zoom="zoom" :center="center" :options="{zoomControl: false}">
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
                                </l-control>
                                </l-map>
                            </div>
                            </b-tab>

                            <b-tab :disabled="ChartDisabled">
                            <template v-slot:title>
                                <i class="fa fa-line-chart"></i>
                                Chart
                            </template>
                            <div class="hello">
                                <b-alert
                                :show="alertShow"
                                variant="success">
                                <h4 class="alert-heading">Warning</h4>
                                <p>
                                    Please adjust the filter above for previewing the chart
                                </p>
                                <hr>
                                <p class="mb-0">
                                    Thanks.
                                </p>
                                </b-alert>
                                <div class="hello" ref="chartdiv"/>
                            </div>
                            </b-tab>
                        </b-tabs>
                        </b-card>
                    </b-card-group>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {
  BAlert,
  BButton,
  BCard,
  BCardGroup,
  BContainer,
  BFormGroup,
  BFormSelect,
  BInputGroup,
  BModal,
  BPagination,
  BTabs,
  BTab,
  BTable
} from 'bootstrap-vue'

import * as am4core from "@amcharts/amcharts4/core";
import * as am4charts from "@amcharts/amcharts4/charts";
import am4themes_animated from "@amcharts/amcharts4/themes/animated";

am4core.useTheme(am4themes_animated);

// Map
import {latLngBounds, latLng, featureGroup} from "leaflet";
import {LMap, LTileLayer, LMarker , LControlLayers, LGeoJson, LControlZoom, LControl} from 'vue2-leaflet';

import { VTree, VSelectTree}  from 'vue-tree-halower'

const axios = require('axios');
export default {
  data() {
    return {
        // Map ----------
        typeFile:'',
        url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        zoom: 5,
        center: [0.7893, 113.9213],
        // center: [0,0],
        fillColor: "#85c0ff",
        geojson: [],
        active: true,
        notExpand: false,
        reduce: true,
        enableTooltip: true,
        fileDisplay: false,
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
        // --------------
        chartOption: false,
        MapDetail: false,
        MapDisabled: false,
        ChartDisabled: true,
        alertShow: true,
        alertTable: true,
        sizeFile: '',
        path: '',
        view_counter: 0,
        download_counter: 0,
        totalFile: 0,
        tabIndex: 0,
        totalRows: 1,
        currentPage: 1,
        perPage: 5,
        infoModal: {
            id: 'info-modal',
            title: '',
            content: ''
        },
        detailedmap: {},
        dataset_info: {},
        infoDataset: [],
        fieldsTable: [],
        initSelected: [],
        treeData: [],
        dataChart: [],
        keyTable: [],
        available_file: [],
        dateListFormat: ['YYYY',
            'yyyy/MM/dd HH:mm:ss.SSS XXX',
            'yyyy/MM/dd HH:mm:ss',
            'yyyy/MM/dd HH:mm:ss XXX',
            'yyyyMMddHHmmss',
            'yyy/MM/dd',
            'yyyy-MM-dd',
            'yyyy-MM-dd HH:mm:ss',
            'yyyy-MM-dd HH:mm:ss XXX',
            'yyyyMMdd',
            'MM/dd/yyyy',
            'MM/dd/yyyy HH:mm:ss',
            'MM-dd-yyyy',
            'MM-dd-yyyy HH:mm:ss',
            'MM/dd/yy',
            'MM-dd-yy',
            'dd/MM/yyyy',
            'dd-MM-yyyy',
            "yyyy-MM-dd'T'HH:mm:ss.SSSXXX",
            'yyyy-MM-dd HH:mm:ss.SSS'],
        dateFormat: '',
        XLine: '',
        YLine: '',
        YType: '',
        XType: '',
        mapTitle: ' ',
        chartTitle: ' ',
    }
  },
  components: {
    BAlert,
    BButton,
    BCard,
    BCardGroup,
    BContainer,
    BFormGroup,
    BFormSelect,
    BInputGroup,
    BModal,
    BPagination,
    BTabs,
    BTab,
    BTable,
    LMap,
    LMarker,
    LControl,
    LGeoJson,
    LTileLayer,
    LControlZoom,
    LControlLayers,
    VTree,
    VSelectTree
  },
  watch: {
    initSelected() {
      this.dateFormat = ''
      this.YType = ''
      this.XType = ''
      this.YLine = ''
      this.XLine = ''
      this.dataChart = []
      this.fieldsTable = []
      this.totalRows = 1
      this.chartOption = false
      this.MapDetail = false
      this.alertShow = true
      this.alertTable = true
      if(this.initSelected.length != 0){
        let extension = this.initSelected[0].split('.').pop()
        // console.log(file_info)
        if(extension === 'shp') {
          this.MapDisabled = false
          this.ChartDisabled = true
          this.MapDetail = true
          this.tabIndex = 0
          this.geojson = []

          let file_info = {}
          file_info['extension'] = extension
          file_info['path'] = this.path+'/'+this.initSelected[0]

          this.detailedmap = file_info

          this.mapTitle = this.initSelected[0]
          axios.post('/api/mapshapefile', file_info).then(response => {
              return this.geojson  = response.data;
          });
        } else {
          if(extension === 'xlsx' || extension === 'xls' || extension === 'csv') {
            this.alertTable = false
            this.MapDisabled = true
            this.ChartDisabled = false
            this.chartOption = true
            this.tabIndex = 1
            this.chartTitle = this.initSelected[0]
            let file_info = {}
            file_info['extension'] = extension
            file_info['path'] = this.path+'/'+this.initSelected[0]
            // console.log(file_info)
            this.getData(file_info)
          }
        }
      }
    }
  },
  computed: {
     
    activeUserInfo () {
      return this.$store.state.AppActiveUser
    },    
  
    options() {
      return {
        onEachFeature: this.onEachFeatureFunction
      };
    },
    styleFunction() {
      const fillColor = this.fillColor; // important! need touch fillColor in computed for re-calculate when change fillColor
      return () => {
        return {
          weight: 2,
          color: "#037bfc",
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
        this.$nextTick().then(() => {
            var group = new featureGroup();

            this.$refs.mapControl.mapObject.eachLayer(function(layer) {
                if (layer.feature != undefined)
                    group.addLayer(layer);
            });

            this.$refs.mapControl.mapObject.fitBounds(group.getBounds(), { padding: [20, 20] });
        });
      };
    }
  },
  methods: {
    display() {
      if(this.fileDisplay === true){
        this.fileDisplay = false

      }else{
        this.fileDisplay = true
        // Rawan Error di Server
        let scroll_position = document.getElementById("scrollPosition");
        let height = scroll_position.offsetHeight+30;
        setTimeout(function(){
          window.scrollTo(0, height);
        }, 300);
      }
    },
    previewmap() {
      localStorage.removeItem("detailedmap_info");
      localStorage.setItem("detailedmap_info", JSON.stringify(this.detailedmap));
      this.$router.push('/mapdetail').catch(() => {})
    },
    submit() {
      if(this.XType != '' && this.XLine != '' && this.YType != '' && this.YLine != ''){
        this.alertShow = false
        this.tabIndex = 1
        if(this.XType == 'date' && this.dateFormat != ''){
          this.chartLine()
        }else if(this.XType == 'number'){
          this.dataChart.sort(this.compare(this.XLine));
          this.tabIndex = 1
          this.dateFormat = ''
          this.chartLine()
        }
      }
    },
    downloadThisZip () {
        location.href = '/api/downloadzip/'+this.dataset_info.created_by+'/'+this.dataset_info.dataset_name
    },
    info(item, index, button) {
      this.infoModal.title = `Row index: ${index}`
      this.infoModal.content = JSON.stringify(item, null, 2)
      this.$root.$emit('bv::show::modal', this.infoModal.id, button)
    },
    resetInfoModal() {
      this.infoModal.title = ''
      this.infoModal.content = ''
    },
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length
      this.currentPage = 1
    },
    async getData(file) {
      // console.log(file)
      this.chart.dispose();
      await axios.post('/api/getdata',file).then(response => {
              let dataTable = response.data
              let checkLength = dataTable.length
              if(checkLength>50){
                return this.dataChart  = dataTable.slice(0, 50);
              }else{
                return this.dataChart  = dataTable;
              }
          });
      if(this.dataChart.length==0){
        this.keyTable = []
      }else{
        this.keyTable = Object.keys(this.dataChart[0])
        this.fieldsTable = []
        for(let i of this.keyTable) {
            const dict = {}
            dict['key'] = i
            dict['label'] = i.charAt(0).toUpperCase() + i.slice(1)
            dict['sortable'] = true
            this.fieldsTable.push(dict)
        }
        // Set the initial number of items
        this.totalRows = this.dataChart.length
        // console.log(this.fieldsTable)
      }
    },
    compare(key, order = 'asc') {
      return function innerSort(a, b) {
        if (!a.hasOwnProperty(key) || !b.hasOwnProperty(key)) {
          // property doesn't exist on either object
          return 0;
        }

        const varA = (typeof a[key] === 'string')
          ? a[key].toUpperCase() : a[key];
        const varB = (typeof b[key] === 'string')
          ? b[key].toUpperCase() : b[key];

        let comparison = 0;
        if (varA > varB) {
          comparison = 1;
        } else if (varA < varB) {
          comparison = -1;
        }
        return (
          (order === 'desc') ? (comparison * -1) : comparison
        );
      };
    },
    chartLine() {
      // // Themes begin
      // am4core.useTheme(am4themes_animated);
      // // Themes end

      // Create chart instance
      var chart = am4core.create(this.$refs.chartdiv, am4charts.XYChart);

      // Add data
      chart.data = this.dataChart;

      if(this.dataChart.length != 0){
        // Set input format for the dates
        // chart.dateFormatter.inputDateFormat = "yyyy-MM-ddThh:mm:ssZ";
        chart.dateFormatter.inputDateFormat = this.dateFormat;

        // Create axes
        if(this.XType == 'number'){
          var dateAxis = chart.xAxes.push(new am4charts.ValueAxis());
          dateAxis.dataFields.category = this.XLine;
        }else if(this.XType == 'date'){
          var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
          // dateAxis.renderer.labels.template.fill = am4core.color("#ffffff");
          // // Set date label formatting
          // dateAxis.dateFormats.setKey("second", "dt MMM, HH:mm:ss");
          // dateAxis.periodChangeDateFormats.setKey("second", "dt MMM, HH:mm:ss");
          dateAxis.dateFormats.setKey("second", "yyyy");
          dateAxis.periodChangeDateFormats.setKey("second", "yyyy");
          dateAxis.baseInterval = {
            "timeUnit": "second",
            "count": 1
          };
          // dateAxis.tooltipDateFormat = "HH:mm, dd MMM";
          dateAxis.tooltipDateFormat = "yyyy";
        }

        if(this.YType == 'number'){
          var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        }else if(this.YType == 'alphabet'){
          var valueAxis = chart.yAxes.push(new am4charts.CategoryAxis());
        }
        // valueAxis.renderer.labels.template.fill = am4core.color("#ffffff");
        valueAxis.dataFields.category = this.YLine;

        let label = valueAxis.renderer.labels.template;
        label.truncate = true;
        label.maxWidth = 120;

        // Create series
        var series = chart.series.push(new am4charts.LineSeries());
        if(this.YType == 'number'){
          series.dataFields.valueY = this.YLine;
        }else if(this.YType == 'alphabet'){
          series.dataFields.categoryY = this.YLine;
        }

        if(this.XType == 'number'){
          series.dataFields.valueX = this.XLine;
        }else if(this.XType == 'date'){
          series.dataFields.dateX = this.XLine;
        }
        series.tooltipText = "{"+this.YLine+"}";
        series.strokeWidth = 2;
        series.minBulletDistance = 15;

        // Drop-shaped tooltips
        series.tooltip.background.cornerRadius = 20;
        series.tooltip.background.strokeOpacity = 0;
        series.tooltip.pointerOrientation = "vertical";
        series.tooltip.label.minWidth = 40;
        series.tooltip.label.minHeight = 40;
        series.tooltip.label.textAlign = "middle";
        series.tooltip.label.textValign = "middle";

        // Make bullets grow on hover
        var bullet = series.bullets.push(new am4charts.CircleBullet());
        bullet.circle.strokeWidth = 2;
        bullet.circle.radius = 4;
        bullet.circle.fill = am4core.color("#fff");

        var bullethover = bullet.states.create("hover");
        bullethover.properties.scale = 1.3;

        // Make a panning cursor
        chart.cursor = new am4charts.XYCursor();
        chart.cursor.behavior = "zoomX";

        // dateAxis.start = 0.79;
        dateAxis.keepSelection = true;

        // Add simple vertical scrollbar
        chart.scrollbarY = new am4core.Scrollbar();
      }

      this.chart = chart;
    },
    async getDataset() {

      try{

        var response=await axios.post('/api/perdataset',this.dataset_info)

          this.infoDataset = response.data;
          console.log(this.infoDataset,"info geger")
          this.totalFile = 0;
          let fileSize = 0;
          this.path = this.infoDataset[0]['created_by']+'/'+this.infoDataset[0]['dataset_name']
          let list_file = {
            'title':this.infoDataset[0]['dataset_name'],
            'selDisabled': true,
            'chkDisabled': true,
            'expanded': false,
            'children':[],
          }

          for(let data of this.infoDataset){
            let dictionary = {}
            let detail_data = {}
            detail_data['filepath'] = data.created_by+'/'+data.dataset_name+'/'+data.file_name
            detail_data['filename'] = data.file_name
            dictionary['title'] = detail_data
            dictionary['size'] = (parseInt(data.size)/1024000).toFixed(2).toString()+' Mb'
            this.available_file.push(dictionary)
            fileSize += (parseInt(data.size)/1024000)
            this.totalFile += 1

            if(data.download_counter == null){
              this.download_counter += 0
            }else{
              this.download_counter += data.download_counter
            }

            if(data.view_counter == null){
              this.view_counter += 0
            }else{
              this.view_counter += data.download_counter
            }

            let children = {}
            children['title'] = data.file_name
            list_file['children'].push(children)
          }
          this.treeData.push(list_file)
          this.totalRows = this.available_file.length;
          this.sizeFile = fileSize.toFixed(2)
        }catch(e){
          console.log(e)
        }
     
    }
  },
  async created () {
    // await axios.get('/api/opendata/').then(response => {
    //           return this.treeData  = response.data;
    //       });
    this.chartLine();
  },

  async mounted() {
      this.dataset_info = JSON.parse(localStorage.getItem("dataset_info"));

      var datasetInfo = JSON.parse(localStorage.getItem("dataset_info"));
      this.typeFile=datasetInfo.type;
      console.log(datasetInfo.type,"iki dataset info")
      await this.getDataset()
  },
  beforeDestroy() {
    if (this.chart) {
      this.chart.dispose();
    }
  }
}
</script>

<style lang="scss">
@import "@sass/vuexy/extraComponents/tree.scss";
html{
  scroll-behavior: smooth;
}

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

.rmNode{
  background-color: rgba(var(--vs-danger),0.15);
  color: rgba(var(--vs-danger),1);
  line-height: 13px;
}

.hello {
  // width: 100%;
  height: 600px;
}

.header-title {
  color: black !important;
}

.nav-link.disabled {
  color: #afb4b9 !important;
}

#button-sb {
  float: right
}

// Map
#mapid {
    height: 600px;
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

.prefext {
  color: rgb(255, 111, 0) !important;
}
.btnX {
  font-size: 15px;
  text-align: center;
  border-radius: 5px;
  cursor: pointer;
}
.table_files {
    overflow-y: auto;
    max-height: 150px;
}
</style>
