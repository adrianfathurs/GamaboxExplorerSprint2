<template>
  <div>
    <div class="vx-row">
      <vx-card>
        <div class="vx-col flex w-full flex-wrap clearfix">
          <h2 class="ml-2 w-full mr-auto">Files Approval</h2>
          <p class="ml-2 w-full mr-auto">Manage users' uploaded files and approve them to make their files available for other users</p>
        </div>
        <div>
          <div class="vx-row flex">
            <div class="vx-col sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6 mb-4">
              <p class="mt-2 mr-3 float-right">Search by</p>
            </div>
            <div class="vx-col sm:w-1/2 md:w-1/3 lg:w-2/4 xl:w-1/6 mb-4">
              <b-form-select v-model="selected_searchby" style="width:100%; height: 90%;" :options="searchby" size="sm" class="mt-1"></b-form-select>
            </div>
            <div class="flex mb-3 w-1/2">
              <vs-input icon-pack="feather" icon="icon-search" placeholder="Search" v-model="value1" class="w-full is-label-placeholder mt-1" />                          
              <vs-button class="ml-3 mr-2 mb-2 mt-1 button-addon" size="small" color="rgb(255, 111, 0)" type="filled" @click="detailed(data[indextr])">Search</vs-button>
            </div>             
          </div>  
          <div class="vx-row flex">
            <div class="vx-col sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6 mb-4">
              <p class="mt-2 mr-3 float-right">Sort by</p>
            </div>
            <div class="vx-col sm:w-1/2 md:w-1/3 lg:w-2/4 xl:w-1/6 mb-4">
              <b-form-select v-model="selected_sortby" style="width:100%; height: 84%;" :options="sortby" size="sm" class=""></b-form-select>
            </div>            
          </div>                 
        </div>
      </vx-card>
    </div>         
        <vx-card
            card-background="#dfe3e5"
            content-color="#000000">
            <div id="div-with-loading" class="vs-con-loading__container">
              <vs-table 
                v-model="table_selected" 
                :data="dataList"
                class="list_file" 
                striped>
                  <template slot="thead">
                  </template>

                  <template slot-scope="{data}">
                      <vs-tr class="mb-3" :data="tr" :key="indextr" v-for="(tr, indextr) in data">

                          <vs-td :data="data[indextr]['type']">                                        
                              <i v-if="data[indextr]['type'] === 'xls' || data[indextr]['type'] === 'xlsx'" class="fa fa-file-excel fa-4x" aria-hidden="true"></i>
                              <i v-else-if="data[indextr]['type'] === 'jpg' || data[indextr]['type'] === 'jpeg' || data[indextr]['type'] === 'png' || data[indextr]['type'] === 'bmp'" class="fa fa-file-image fa-4x" aria-hidden="true"></i>
                              <i v-else-if="data[indextr]['type'] === 'shp' || data[indextr]['type'] === 'dbf' || data[indextr]['type'] === 'shx'" class="fas fa-map-marked-alt fa-4x" aria-hidden="true"></i>
                              <i v-else-if="data[indextr]['type'] === 'doc'" class="fa fa-file-word fa-4x" aria-hidden="true"></i>
                              <i v-else-if="data[indextr]['type'] === 'csv'" class="fa fa-file-csv fa-4x" aria-hidden="true"></i>
                              <i v-else-if="data[indextr]['type'] === 'pdf'" class="fa fa-file-pdf fa-4x" aria-hidden="true"></i>
                              <i v-else-if="data[indextr]['type'] === 'zip' || data[indextr]['type'] === 'rar'" class="fa fa-file-archive fa-4x" aria-hidden="true"></i>
                          </vs-td>

                          <vs-td :data="data[indextr]">
                              <h6><strong>{{data[indextr]['dataset_name']}}</strong></h6>
                              <p>{{data[indextr]['description']}}</p>
                          </vs-td>

                          <vs-td :data="data[indextr]">
                              <p class="mt-2">{{data[indextr]['created_date']}}</p>
                          </vs-td>

                          <vs-td :data="data[indextr]">
                              <vs-button class="button_nanta2 mb-2" size="small" color="rgb(255, 111, 0)" type="filled" @click="detailed(data[indextr])">Details</vs-button>
                              <vs-button class="button_nanta2 mb-3" size="small" color="rgb(255, 111, 0)" type="filled" @click="deletedata(data[indextr])">Remove</vs-button>
                              <!-- <a :href="'api/deletedata/'+data[indextr]['id']+'/'+data[indextr]['created_by']+'/'+data[indextr]['dataset_name']" style="cursor:pointer;" class="button _nanta2 mb-3">Remove</a> -->
                          </vs-td>
                          
                      </vs-tr>
                  </template>
              </vs-table> 
            </div>   
          </vx-card>
  </div>
</template>

<script>
import {
  BFormSelect
} from 'bootstrap-vue'

import vSelect from 'vue-select'

const axios = require('axios');

export default {
  data() {
    return {
      datasetName: '',
      tagging: [],
      colorx:"#f4f0ec",
      colorx_btn:"rgb(0,123,255)",
      popupActive: false,      
      dataList: [],
      table_selected: {},
      selected_searchby: '',
      searchby: [
        {
          value: "dataset_name",
          text: "Dataset Name",
        },
        {
          value: "username",
          text: "Username",
        }
      ]
    }
  },
  components: {
    BFormSelect,
    'v-select': vSelect,
  },
  computed: {

  },
  methods:{
    detailed (data) {
      // This is just for demo Purpose. If user clicks on logout -> redirect
      localStorage.removeItem("dataset_info");
      localStorage.setItem("dataset_info", JSON.stringify(data));
      this.$router.push('/detailedpage').catch(() => {})
    },
    getDataset () {
        this.$vs.loading({
            container: '#div-with-loading',
            scale: 0.6
        })
        axios.post('/api/mydataset',{'username':this.$store.state.AppActiveUser.displayName}).then(response => {
            this.dataList = response.data;
            this.$vs.loading.close('#div-with-loading > .con-vs-loading')
        })
        .catch(err => {
            if (err.response) {
                // client received an error response (5xx, 4xx)
                this.$vs.loading.close('#div-with-loading > .con-vs-loading')
            } else if (err.request) {
                // client never received a response, or request never left
                this.$vs.loading.close('#div-with-loading > .con-vs-loading')
            } else {
                // anything else
                this.$vs.loading.close('#div-with-loading > .con-vs-loading')
            }
        })
    },
    deletedata(data) {
      alert('/api/deletedata/'+data['id']+'/'+data['created_by']+'/'+data['dataset_name'])
      axios.get('/api/deletedata/'+data['id']+'/'+data['created_by']+'/'+data['dataset_name']).then(response => {
          this.$vs.loading.close('#div-with-loading > .con-vs-loading')
      })
    },
  },
  created() {

  },
  mounted() {
      this.getDataset()
  }

}
</script>

<style>

[dir=ltr] .vs-input--input.hasIcon {
    padding: 0.3rem 1rem 0.7rem 3rem !important;
}

.button_nanta2 {
  width: 100px;
  padding: 3px 10px;
  font-size: 12px;
  text-align: center;
  border-radius: 5px;
  cursor: pointer;
}

.tambahan {
  border-radius: 5px;
}

.list_file {
  height: 300px;
  overflow: auto;
}

@mixin clearfix() {
  &::after {
    display: block;
    content: "";
    clear: both;
  }
}

.button-addon {
  border-radius: 10px;  
  font-size: 12px !important;
  text-align: center;
  cursor: pointer;
}

.button-remove {
  border-radius: 5px; 
  background-color: rgb(255, 111, 0);
  border: none;
  color: white;
  padding: 5px 25px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 12px;
  margin: 4px 2px;
  transition-duration: 0.4s !important;
  cursor: pointer;
}

.button1-remove {
  background-color: rgb(255, 111, 0) !important; 
  color: white !important; 
  border: 2px solid #0000 !important;
}

.button1-remove:hover {
  background-color: rgb(255, 111, 0) !important;
  color: white !important;
}

.element {
  @include clearfix;
}

</style>
