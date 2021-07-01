<template>
  <div>
    <vs-row>
      <vs-col>
        <div>
          <div class="vx-row flex">
            <div class="vx-col sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6 mb-3">
              <p class="mt-2 mr-3 float-right">Search by</p>
            </div>
            <div class="vx-col sm:w-1/2 md:w-1/3 lg:w-2/4 xl:w-1/6 mb-3">
              <b-form-select v-model="selected_searchby" style="width:100%; height: 91%;" :options="searchby" size="sm" class="mt-1"></b-form-select>
            </div>
            <div class="flex mb-3 w-1/2">
              <vs-input icon-pack="feather" icon="icon-search" v-model="selected_keyword" class="w-full mt-1" />                          
              <vs-button class="ml-3 mr-2 mb-2 mt-1 button-addon" size="small" color="rgb(255, 111, 0)" type="filled" v-on:click="searchData">Search</vs-button>
            </div>             
          </div>  
          <div class="vx-row flex">
            <div class="vx-col sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6 mb-4">
              <p class="mt-2 mr-3 float-right">Sort by</p>
            </div>
            <div class="vx-col sm:w-1/2 md:w-1/3 lg:w-2/4 xl:w-1/8 mb-4">
              <b-form-select v-model="selected_sortby" style="width:46%; height: 85%;" :options="sortby" size="sm" class=""></b-form-select>
            </div>  
          </div>                 
        </div>          
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
                      <template #cell(actions)="row">
                        <vs-button class="button_css" size="small" color="rgb(255, 111, 0)" type="filled" @click="confirmApproval('approved', row.item.dataset_id)">Approve</vs-button>
                        <vs-button class="button_css" size="small" color="rgb(192, 0, 0)" type="filled" @click="confirmApproval('rejected', row.item.dataset_id)">Dissaprove</vs-button>
                      </template>
                  </b-table>
                </b-container>
              </div>
            </template>

            </div>   
          </vx-card>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
import {
  BFormSelect,
  BContainer,
  BTable
} from 'bootstrap-vue'

// import uploadPage from './pages/Upload.vue'
import vSelect from 'vue-select'

const axios = require('axios');

export default {
  data() {
    return {
      fields: [
        { key: 'created_by', label: 'Uploader' },
        { key: 'dataset_name', label: 'Dataset Name'},
        { key: 'created_date', label: 'Uploaded Date'},
        { key: 'actions', label: 'Options' }
      ],   
      dataList: [],
      dataInfo: [],
      table_selected: {},
      activeConfirm: false,
      searchby: [
        {text: 'User', value: 'user'},
        {text: 'Dataset', value: 'dataset'},
      ],
      sortby: [
        {text: 'User', value: 'created_by'}, 
        {text: 'Dataset', value: 'dataset_name'}, 
        {text: 'Approved Date', value: 'updated_date'},
      ],
      selected_searchby: '',
      selected_sortby: '',
      selected_keyword: '',
      sort_by: {},

    }
  },
  components: {
    BContainer,
    BTable,
    BFormSelect,
    'v-select': vSelect,
  },
  computed: {

  },
  watch: {
    selected_sortby () {
        if(this.selected_sortby !== ''){
            this.getDataset(this.sort_by)
        }
    },
  },    
  methods:{
    confirmApproval (verif, id){   
      axios.get('/api/publish'+'/'+verif+'/'+id).then(response => {
        // console.log(response);
        this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
        if(verif == 'rejected'){
          this.$vs.notify({
              time:8000,
              title: 'Succes',
              text: 'file has been rejected.',
              color: 'success'
          })
        }else if(verif == 'approved'){
          this.$vs.notify({
              time:8000,
              title: 'Succes',
              text: 'file has been approved.',
              color: 'success'
          })
        }
      }) 
      let data = {
        'filter_name': 'approval',
      }
      this.getDataset(data)     
    },
    filterData () {
      let data = {
        'filter_name': 'approval',
      }
      this.getDataset(data)       
    },
    searchData () {
      if(!this.selected_keyword == ''){
        if(this.selected_searchby == 'user') {
          let search = {
            'filter_name': 'searchAppUser',
            'created_by': this.selected_keyword
          }
          this.getDataset(search)
        }
        else if(this.selected_searchby == 'dataset'){
          let search = {
            'filter_name': 'searchApp',
            'file_name': this.selected_keyword,
          }
          this.getDataset(search)
        }
        else{
          let search = {
            'filter_name': 'searchApp',
            'file_name': this.selected_keyword,
          }
          this.getDataset(search)
        }
      }
      else {
        let search = {
            'filter_name': 'approval',
            'file_name': this.selected_keyword,
        }
        this.getDataset(search)
      }
    }, 
    getDataset (filter_value) {
        this.$vs.loading({
            container: '#div-with-loading',
            scale: 0.6
        })
        this.sort_by = filter_value;
        let postData = {}
        if(this.selected_sortby != ''){
            let sortData = {
                'sort': this.selected_sortby
            }
            postData = Object.assign({}, this.sort_by, sortData);
        }else{
            postData = this.sort_by
        }
        axios.post('/api/dataset',postData)
            .then(response => {
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
    deletedata() {
      this.$vs.loading({
          container: '#div-with-loading',
          scale: 0.6
      })   
      axios.post('/api/deletedataset',this.dataInfo).then(response => {
          this.$vs.loading.close('#div-with-loading > .con-vs-loading')
          this.getDataset()
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
  },
  created() {

  },
  mounted() {
      this.filterData()
    }

}
</script>

<style scoped lang="scss">

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

.container-data {
    height: 43vh;
    min-height: 300px!important;
    overflow: auto;
}

.css_username {
  padding-left: 35px;
}


.button_css {
  width: 100px;
  padding: 3px 10px;
  font-size: 12px;
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

.button-href1 {
  color: white;
  font-size: 12px;
  text-align: center;
  background: #28a745;
  padding: 8px 30px 8px 30px;
  border-radius: 5px;
}

.button-href2 {
  color: white;
  font-size: 12px;
  text-align: center;
  background: rgb(192, 0, 0);
  padding: 8px 30px 8px 30px;
  border-radius: 5px;
}

.button_css {
  padding: 3px 10px;
  font-size: 12px;
  text-align: center;
  border-radius: 5px;
  cursor: pointer;
}

.tambahan {
  border-radius: 5px;
}

.change_level_css {
  width: 150px;
  background: #3662c1;
  color: #ffffff;
}

.v-select .vs__selected {
  color: #ffffff !important;
}

@mixin clearfix() {
  &::after {
    display: block;
    content: "";
    clear: both;
  }
}

[dir] .table.b-table > thead > tr > .table-b-table-default, [dir] .table.b-table > tbody > tr > .table-b-table-default, [dir] .table.b-table > tfoot > tr > .table-b-table-default {
    background-color: #dedede;
}

.table td, .table th {
    padding: .75rem;
    vertical-align: middle;
    border-top: 1px solid #dee2e6;
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

</style>
