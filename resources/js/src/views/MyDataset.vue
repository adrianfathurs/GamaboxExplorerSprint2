<template>
  <div>
    <div class="vx-row">
      <div class="vx-col w-full md:w-3/3 lg:w-1/3 xl:w-1/4 mb-base">
          <div id="sidebar_menu" class="sidebar vs-con-loading__container">
              <sidebar-menu :menu="menu" :hideToggle="true " @item-click="filter"/>
          </div>      
      </div>
      <div class="vx-col w-full md:w-3/3 lg:w-2/3 xl:w-3/4 mb-base">        
          <vx-card class="mb-1">
            <div class="flex flex-wrap clearfix">
              <div class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5 clearfix">
                <h2 class="ml-2 w-full mr-auto">My Dataset</h2>
                <p class="ml-2 w-full mr-auto">Manage your dataset</p>
              </div>
              <div class="w-full sm:w-1/2 md:w-2/3 lg:w-3/4 xl:w-4/5 clearfix">

                <vs-button class="add_css mt-2 float-right" text-color="rrgb(255, 111, 0)" :color="colorx_btn" @click="popupActive=true" type="filled">New Upload</vs-button>
                
                <vs-popup v-if="emailVerified" background-color="rgba(255,255,255,.6)" :background-color-popup="colorx" class=""  title="Upload Data" :active.sync="popupActive">
                  <uploadPage></uploadPage>
                </vs-popup>
              </div>
            </div>
          </vx-card>
                
          <vx-card
            card-background="#ffffff"
            content-color="#000000">
            <div id="div-with-loading" class="vs-con-loading__container">
            <template>
              <div>
                <b-container fluid>
                  <b-table
                    sticky-header
                    class="container-data"
                    :items="dataList"
                    :fields="fields"
                    >
                    <template #head(imgs)="data">
                      <p class="head-table" style="color:transparent">{{ data.label }}</p>
                    </template>
                    <template #head(data)="data">
                      <p class="head-table">{{ data.label }}</p>
                    </template>
                    <template #head(created_date)="data">
                      <p class="head-table">{{ data.label }}</p>
                    </template>
                    <template #head(actions)="data">
                      <p class="head-table" style="text-align:center !important;">{{ data.label }}</p>
                    </template>
                    <template #head(actionsOpt)="data">
                      <p class="head-table" style="text-align:center !important;">{{ data.label }}</p>
                    </template>                    
                    <template v-if="startTable" #cell(imgs)="row">
                      <i v-if="row.item.type.toLowerCase() === 'xls' || row.item.type.toLowerCase() === 'xlsx'" class="fa fa-file-excel fa-4x opacityIcon" aria-hidden="true"></i>
                      <i v-else-if="row.item.type.toLowerCase() === 'jpg' || row.item.type.toLowerCase() === 'jpeg' || row.item.type.toLowerCase() === 'png' || row.item.type.toLowerCase() === 'bmp'" class="fa fa-file-image fa-4x opacityIcon" aria-hidden="true"></i>
                      <i v-else-if="row.item.type.toLowerCase() === 'shp' || row.item.type.toLowerCase() === 'dbf' || row.item.type.toLowerCase() === 'shx'" class="fas fa-map-marked-alt fa-4x opacityIcon" aria-hidden="true"></i>
                      <i v-else-if="row.item.type.toLowerCase() === 'doc'" class="fa fa-file-word fa-4x opacityIcon" aria-hidden="true"></i>
                      <i v-else-if="row.item.type.toLowerCase() === 'csv'" class="fa fa-file-csv fa-4x opacityIcon" aria-hidden="true"></i>
                      <i v-else-if="row.item.type.toLowerCase() === 'pdf'" class="fa fa-file-pdf fa-4x opacityIcon" aria-hidden="true"></i>
                      <i v-else-if="row.item.type.toLowerCase() === 'zip' || row.item.type.toLowerCase() === 'rar'" class="fa fa-file-archive fa-4x opacityIcon" aria-hidden="true"></i>
                      <i v-else class="fa fa-file fa-4x opacityIcon" aria-hidden="true"></i>        
                    </template>
                    <template v-if="startTable" #cell(data)="row">
                      <h6><strong>{{row.item.dataset_name}}</strong></h6>
                      <p>{{row.item.description}}</p>        
                    </template>                  
                    <template v-if="startTable" #cell(actions)="row">
                      <div v-if="row.item.approval_ind === 'waiting'" class="flex flex-wrap justify-center items-center">
                        <vs-button disabled class="waiting_css mb-3 mt-3" size="small" type="filled" color="#878787">Waiting for approval</vs-button>
                      </div>
                      <div v-else-if="row.item.approval_ind === 'approved'">
                        <div v-if="selected_view[row.index].value === 'Private'">
                          <v-select
                          class="change_level_cssr change_level_css_addr change_level_css_addr2"
                          name="selected_level" 
                          v-model="selected_view[row.index]"
                          :options="change_level" 
                          :dir="$vs.rtl ? 'rtl' : 'ltr'" 
                          @input="showButton"
                          />
                        </div>
                        <div v-else>
                          <v-select
                          class="change_level_cssrp change_level_css_addr change_level_css_addr2"
                          name="selected_level" 
                          v-model="selected_view[row.index]"
                          :options="change_level" 
                          :dir="$vs.rtl ? 'rtl' : 'ltr'" 
                          @input="showButton"
                          />
                        </div>
                      </div> 
                      <div v-if="row.item.approval_ind === 'rejected'" class="flex flex-wrap justify-center items-center">
                        <vs-button disabled class="rejected_css mb-3 mt-3" size="small" type="filled" color="rgb(192, 0, 0)">Rejected</vs-button>
                      </div>
                    </template>
                    <template v-if="startTable" #cell(actionsOpt)="row">
                      <div class="flex flex-wrap justify-center items-center">
                        <vs-button  v-if="row.item.approval_ind !== 'waiting' && row.item.approval_ind !== 'rejected'" class="button_css mb-3 mt-3" size="small" color="rgb(255, 111, 0)" type="filled" @click="detailed(row.item)">Details</vs-button>
                        <vs-button class="button_css mb-3 mt-3" size="small" color="rgb(192, 0, 0)" type="filled" @click="deleteConfirm(row.item)">Remove</vs-button>
                      </div>
                    </template>                      
                  </b-table>
                </b-container>
              </div>
            </template>

            </div> 
          </vx-card>
          <div v-if="buttonEmmit === true">
            <vs-button class="button_save mb-3 ml-3 mt-3 float-right" size="small" color="rgb(192, 0, 0)" type="filled" @click="cancelSave">Cancel</vs-button>  
            <vs-button class="button_save float-right mb-3 mt-3" size="small" type="filled" @click="changePublicValue" color="rgb(80, 182, 80)">Save change</vs-button> 
          </div>
      </div>
    </div>
  </div>
</template>

<script>
// bootstrap
import {
  BContainer,
  BTable
} from 'bootstrap-vue'

import { SidebarMenu } from 'vue-sidebar-menu'
import uploadPage from './pages/Upload.vue'
import vSelect from 'vue-select'

const axios = require('axios');

export default {
  data() {
    return {
      fields: [
        { key: 'imgs', label: ' ' },
        { key: 'data', label: 'Dataset Name'},
        { key: 'created_date', label: 'Uploaded Date'},
        { key: 'actions', label: 'State Option' },
        { key: 'actionsOpt', label: 'Option' }        
      ],         
      colorx:"#f4f0ec",
      colorx_btn:"rgb(0,123,255)",
      emailVerified: false,
      popupActive: false, 
      buttonEmmit: false,    
      startTable: false, 
      dataList: [],
      dataInfo: [],
      menu: [],
      month: [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July ',
        'August',
        'September',
        'October',
        'November',
        'December'
      ],
      table_selected: {},
      activeConfirm: false,
      change_level: [
        {label: 'Private', value: 'private'}, 
        {label: 'Public', value: 'public'} 
      ],
      selected_level: '',
      selected_view: [],      
      public_value: []
    }
  },
  components: {
    BContainer,
    BTable,
    'v-select': vSelect,
    uploadPage,
    SidebarMenu,
  },
  computed: {

  },
  watch: {
    popupActive() {
        if(!this.emailVerified){
            this.$router.push('/pages/unverified')
        }
    },
  },
  methods:{ 
    showButton(event) {
      this.buttonEmmit = true;
    },
    cancelSave () {
      this.buttonEmmit = false;
      location.reload();
    }, 
    changePublicValue () {
      this.buttonEmmit = false;
      this.$vs.loading({
        container: '#div-with-loading',
        scale: 0.6
      })

      let dict = {
        'data_public':[]
      }
      for(let i in this.public_value){
        let data = {}
        if(this.selected_view[i]['value'] != this.public_value[i]['value']){
          data['dataset_id'] = this.dataList[i]['dataset_id']
          data['public_ind'] = this.selected_view[i]['value'].toLowerCase();
          dict['data_public'].push(data)
        }
      }
      axios.post('/api/update',dict).then(response => {
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
    filter (event, item, node) {
      let nodeData = node.item;
      let nodeAttr = ''
      if(nodeData.hasOwnProperty('attributes')){
          nodeAttr = nodeData.attributes.filter_name
      }
      // console.log(nodeAttr)
      if(node.level == 2){
          this.getDataset(node.item.attributes)
      }else if(node.level == 1 && nodeAttr == 'extensions'){
          this.getDataset(nodeData.attributes)
      }
    },
    detailed (data) {
      // This is just for demo Purpose. If user clicks on logout -> redirect
      localStorage.removeItem("dataset_info");
      localStorage.setItem("dataset_info", JSON.stringify(data));
      this.$router.push('/detailedpage').catch(() => {})
    },
    deleteConfirm(data) {
      this.dataInfo = data
      // console.log(
      //   this.dataInfo
      // )
      this.$vs.dialog({
        type: 'confirm',
        color: 'danger',
        title: `Confirm`,
        text: 'Are you sure you want to delete this dataset?',
        accept: this.deletedata
      })
    },
    getDataset (filter_value) {
      this.$vs.loading({
        container: '#div-with-loading',
        scale: 0.6
      })

      let postData = {}
      let filterData = {
        'username': this.$store.state.AppActiveUser.displayName
      }
      postData = Object.assign({}, filter_value, filterData);

      axios.post('/api/mydataset',postData).then(response => {
        this.dataList = response.data;
        if(this.dataList.length>0){
          this.startTable = true;
        }
        for(let i of this.dataList){
          let publicValue = i.public_ind[0].toUpperCase() +  i.public_ind.slice(1)
          let dict = {
            'label': publicValue,
            'value': publicValue
          }
          this.selected_view.push(dict)
        }
        this.public_value = this.selected_view.map((x) => x);
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
    deletedata() { 
      this.$vs.loading({
        container: '#div-with-loading',
        scale: 0.6
      })   
      axios.post('/api/deletedataset',this.dataInfo).then(response => {
        this.$vs.loading.close('#div-with-loading > .con-vs-loading')
        this.getDataset({})
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
    filterData () {
        this.$vs.loading({
            container: '#sidebar_menu',
            scale: 0.6
        })
        axios.post('/api/filter',{'username':this.$store.state.AppActiveUser.displayName})
            .then(response => {
                let dataFilter = response.data;
                this.$vs.loading.close('#sidebar_menu > .con-vs-loading')
                let filterDate = {
                    header: true,
                    title: 'Years',
                    hiddenOnCollapse: true
                }
                let filterExtensions = {
                    header: true,
                    title: 'Extensions',
                    hiddenOnCollapse: true
                }
                let titleCategory = {
                    header: true,
                    title: 'Category',
                    hiddenOnCollapse: true
                }
                let oil = {
                    title: 'Migas',
                    icon: 'fas fa-gas-pump',
                    child: []
                }
                let geothermal = {
                    title: 'Panas Bumi',
                    icon: 'fas fa-globe-asia',
                    child: []
                }
                let coal = {
                    title: 'Batu Bara',
                    icon: 'fas fa-industry',
                    child: []
                }
                this.menu.push(titleCategory)

                // console.log(dataFilter['category']);   

                //sub
                for(let i of dataFilter['category']){
                    let dictionary = {}
                    if(i['category']==='batubara'){
                        dictionary['title']=i['subcategory']
                        dictionary['attributes'] = {
                            'filter_name': 'category',
                            'category': 'batubara',
                            'subcategory': i['subcategory'] 
                        }
                        coal['child'].push(dictionary)
                    }else if(i['category']==='migas'){
                        dictionary['title']=i['subcategory']
                        dictionary['attributes'] = {
                            'filter_name': 'category',
                            'category': 'migas',
                            'subcategory': i['subcategory'] 
                        }
                        oil['child'].push(dictionary)                            
                    }else if(i['category']==='panasbumi'){
                        dictionary['title']=i['subcategory'] 
                        dictionary['attributes'] = {
                            'filter_name': 'category',
                            'category': 'panasbumi',
                            'subcategory': i['subcategory'] 
                        }
                        geothermal['child'].push(dictionary)                           
                    }
                }


                this.menu.push(coal)
                this.menu.push(oil)
                this.menu.push(geothermal)

                let yearList = []
                let date_filter = {}
                let count = 0
                this.menu.push(filterDate)
                for(let i of dataFilter['date']){
                    count += 1
                    let dictionary = {}
                    let test = yearList.includes(i['year_date'])
                    if (!test && yearList.length===0){
                        yearList.push(i['year_date'])
                        date_filter['title'] = i['year_date']
                        date_filter['icon'] = 'far fa-calendar'
                        date_filter['child'] = []
                    }else if(!test){
                        yearList.push(i['year_date'])
                        this.menu.push(date_filter)
                        date_filter = {}
                        date_filter['title'] = i['year_date']
                        date_filter['icon'] = 'far fa-calendar'
                        date_filter['child'] = []
                    }

                    let month_int = parseInt(i['month_date'])
                    dictionary['title'] = this.month[month_int-1]
                    dictionary['attributes'] = {
                        'filter_name': 'date',
                        'year': i['year_date'],
                        'month': i['month_date']
                    }
                    date_filter['child'].push(dictionary)
                    if(count === dataFilter['date'].length){
                        this.menu.push(date_filter)                                
                    }

                }
                
                this.menu.push(filterExtensions)
                for(let i of dataFilter['extension']){
                    let dictionary = {}
                    dictionary['title'] = i['type']
                    dictionary['attributes'] = {
                        'filter_name': 'extensions',
                        'extension': i['type'].toLowerCase()
                    }
                    let typeFile = i['type'].toLowerCase()
                    if(typeFile === 'jpg' || typeFile === 'jpeg' || typeFile === 'png' || typeFile === 'bmp'){
                        dictionary['icon'] = 'fa fa-file-image'
                    }else if(typeFile === 'xls' || typeFile === 'xlsx'){ 
                        dictionary['icon'] = "fa fa-file-excel"
                    }else if(typeFile === 'shp' || typeFile === 'dbf' || typeFile === 'shx'){ 
                        dictionary['icon'] = "fas fa-map-marked-alt"
                    }else if(typeFile === 'doc'){ 
                        dictionary['icon'] = "fa fa-file-word"
                    }else if(typeFile === 'csv'){ 
                        dictionary['icon'] = "fa fa-file-csv"
                    }else if(typeFile === 'pdf'){ 
                        dictionary['icon'] = "fa fa-file-pdf"
                    }else if(typeFile === 'zip' || typeFile === 'rar'){ 
                        dictionary['icon'] = "fa fa-file-archive" 
                    }else{
                        dictionary['icon'] = "fa fa-file" 
                    }
                    this.menu.push(dictionary)
                }
            })
            .catch(err => {
                if (err.response) {
                    // client received an error response (5xx, 4xx)
                    this.$vs.loading.close('#sidebar_menu > .con-vs-loading')
                } else if (err.request) {
                    // client never received a response, or request never left
                    this.$vs.loading.close('#sidebar_menu > .con-vs-loading')
                } else {
                    // anything else
                    this.$vs.loading.close('#sidebar_menu > .con-vs-loading')
                }
            })
    },
  },
  created() {    
    var userInfo= JSON.parse(localStorage.getItem('userInfo'));
      if(!userInfo){
        var userInfo =this.$store.state.AppActiveUser;
      }
      if(userInfo.emailVerified){
          this.emailVerified = true;
      }  
  },
  mounted() {
      this.getDataset({})
      this.filterData()
  }

}
</script>

<style lang="scss">
@import "vue-sidebar-menu/src/scss/vue-sidebar-menu.scss";

// Sidebar
.sidebar {
    height: 72vh;
    min-height: 510px;
    background-color: white;
}
.v-sidebar-menu {
    position: inherit;
    height: -webkit-fill-available;
    border-radius: 10px;
    background-color:rgb(0 123 255 / 70%);
    width: -webkit-fill-available;
    max-width: -webkit-fill-available !important;
}

.v-sidebar-menu .vsm--icon{
    background-color:transparent !important;
}

[dir] .v-sidebar-menu.vsm_expanded .vsm--item_open .vsm--link_level-1 {
    background-color: #4286f5;
}

[dir] .v-sidebar-menu.vsm_expanded .vsm--item_open .vsm--link_level-1 .vsm--icon {
    background-color:transparent !important;
}

.v-sidebar-menu .vsm--item {
    padding: 0px !important;
}

.v-sidebar-menu .vsm--link {
    font-size: 13px !important;
}

.vsm--link {
    font-size: 13px !important;
}

.v-sidebar-menu .vsm--dropdown .vsm--list {
    background-color: #4286f5;
    padding-left: 30px;
}

.container-data {
    height: calc(72vh - 165px)!important;
    min-height: calc(72vh - 170px)!important;
    overflow: auto;
}

.v-sidebar-menu .vsm--link {
    font-size: 15px;
}

.vs-button {
    border-radius: 10px;    
}
.v-sidebar-menu .vsm--item {
    padding-left: 15px;
}

// end line

[dir] .table.b-table > thead > tr > .table-b-table-default, [dir] .table.b-table > tbody > tr > .table-b-table-default, [dir] .table.b-table > tfoot > tr > .table-b-table-default {
    background-color: #dedede !important;
}

.table td, .table th {
    padding: .75rem;
    vertical-align: middle;
    border-top: 1px solid #dee2e6;
}

#info_status {
  font-size: 13px;
  text-align: center;
  background: #3662c1;
  padding-top: 7px;
  padding-bottom: 7px;
  padding-right: 3px;
  padding-left: 3px;
  border-radius: 7px 7px 7px 7px;
  color: white;
  text-align: center;
}

.opacityIcon{    
    font-size: 3em;
    color: #4ca2ff;
}

.button_css {
  width: 100px;
  height: 34.19px;
  padding: 3px 10px;
  font-size: 12px;
  text-align: center;
  border-radius: 5px;
  cursor: pointer;
  margin-left: auto;
  margin-right: auto;  
}

.rejected_css {
  width: 150px;
  padding: 3px 10px;
  font-size: 12px;
  text-align: center;
  border-radius: 5px;
  cursor: pointer;
}

.waiting_css {
  width: 150px;
  padding: 3px 10px;
  font-size: 10px;
  text-align: center;
  border-radius: 5px;
  cursor: pointer;
}

.button_save {
  padding: 3px 10px;
  font-size: 12px;
  text-align: center;
  border-radius: 5px;
  cursor: pointer;
}

.button_css {
  padding: 3px 10px;
  font-size: 12px;
  text-align: center;
  border-radius: 5px;
  cursor: pointer;
  margin-left: auto;
  margin-right: auto;  
}

.add_css {
  border-radius: 5px;
}

.change_level_cssr {
  width: 150px;
  font-size: 12px;
  text-align: center;
  border-radius: 5px;  
  margin-left: auto;
  margin-right: auto;
  color: white;
  background-color: blue;
}

.change_level_cssrp {
  width: 150px;
  font-size: 12px;
  text-align: center;
  border-radius: 5px;  
  margin-left: auto;
  margin-right: auto;
  color: white;
  background-color: rgba(var(--vs-success), 1);
}

.change_level_css_addr .vs__selected {
  color: white !important;
}

.change_level_css_addr2 .vs__clear {
  color: #ffffff !important;
}


.list_file {
  height: 100%;
  overflow: auto;
  // padding: 0.5rem;
}

@mixin clearfix() {
  &::after {
    display: block;
    content: "";
    clear: both;
  }
}

.button-remove {
  border-radius: 5px; 
  background-color: rgb(255, 0, 0);
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
  background-color: rgb(204, 0, 0) !important; 
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

.head-table {
  margin-bottom: 0px;
}
</style>