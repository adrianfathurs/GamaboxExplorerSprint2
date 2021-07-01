<template>
  <div>
    <vs-row>
      <vs-col>
        <div>
          <div class="vx-row flex">
            <div class="vx-col sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6 mb-3">
              <p class="mt-2 mr-3 float-right">Search user</p>
            </div>
            <div class="vx-col sm:w-1/2 md:w-1/3 lg:w-2/4 xl:w-1/8 mb-3">
              <vs-input icon-pack="feather" icon="icon-search" v-model="search_value" class="w-full mt-1" />
            </div>
            <div class="flex mb-3 w-1/2">                 
              <vs-button class="ml-3 mr-2 mb-2 mt-1 button-addon" size="small" color="rgb(255, 111, 0)" type="filled" @click="searchUser">Search</vs-button>
            </div>             
          </div>  
          <div class="vx-row flex">
            <div class="vx-col sm:w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6 mb-4">
              <p class="mt-2 mr-3 float-right">Sort by</p>
            </div>
            <div class="vx-col sm:w-1/2 md:w-1/3 lg:w-2/4 xl:w-1/8 mb-4">
              <b-form-select v-model="selected_sortby" style="width:100%; height: 85%;" :options="sortby" size="sm" class=""></b-form-select>
            </div> 
            <div class="vx-col sm:w-1/2 md:w-1/3 lg:w-2/4 xl:w-1/2 mb-4">
              <div v-if="buttonEmmit === true">
                <vs-button class="button_save mb-3 ml-3 float-right" size="small" color="rgb(192, 0, 0)" type="filled" @click="cancelSave">Cancel</vs-button>                
                <vs-button class="button_save mb-3 float-right" size="small" color="#28a745" type="filled" @click="sendEmail">Save Changes</vs-button>
              </div>
              <!-- {{privilege_value}} -->
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
                      <template #cell(privilege)="row">
                        <div v-if="row.item.role === 'admin'">
                          <vs-button disabled class="css_admin mb-3 mt-3" size="small" color="rgb(192, 0, 0)" type="filled">{{row.item.role[0].toUpperCase() +  row.item.role.slice(1)}}</vs-button>
                        </div>                        
                        <div v-else-if="row.item.role === 'superuser'">
                          <vs-button disabled class="css_admin mb-3 mt-3" size="small" color="rgb(192, 0, 0)" type="filled">{{row.item.role[0].toUpperCase() +  row.item.role.slice(1)}}</vs-button>
                        </div>
                        <div v-else>
                          <v-select
                          color="white"
                          class="change_select change_select_addr change_select_addr2"
                          name="selected_level" 
                          v-model="selected_view[row.index]"
                          :options="change_privilege" 
                          :dir="$vs.rtl ? 'rtl' : 'ltr'" 
                          @input="showButton"
                          />                                
                        </div>                        
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

import vSelect from 'vue-select'

const axios = require('axios');

export default {
  data() {
    return {
      fields: [
        { key: 'displayname', label: 'Username' },
        { key: 'email', label: 'Email' },
        { key: 'privilege', label: 'Privilege'}
      ],
      dataList: [],
      dataInfo: [],
      table_selected: {},
      sortby: [
        {text: 'Username', value: 'displayname'}, 
        {text: 'Privilege', value: 'role'}, 
      ],
      change_privilege: [
        {label: 'Admin', value: 'admin'}, 
        {label: 'User', value: 'user'}, 
      ],
      selected_level: '',
      selected_sortby: '',
      privilege_value: [], 
      selected_view: [],   
      buttonEmmit: false, 
      search_value: ''
    }
  },
  components: {
    BFormSelect,
    BContainer,
    BTable,
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
    selected_view() {
      // console.log(this.selected_view)
      // console.log(this.privilege_value)
    }    
  },
  methods:{
    showButton(event) {
      this.buttonEmmit = true;
    },
    cancelSave () {
      this.buttonEmmit = false;
      location.reload();
    },
    filterData () {
      let data = {
        'filter_name': ''
      }
      this.getDataset(data)
    },
    searchUser () {
      if(!this.search_value == '' ) {
        let searchData = {
            'filter_name': 'search',
            'keyword': this.search_value
        }
        this.getDataset(searchData)
      }
      else {
        this.filterData ()
      }      
    },    
    sendEmail () {      
      this.$vs.loading({
          container: '#div-with-loading',
          scale: 0.6
      })

      let dict = {
        'data_admin':[]
      }
      let email = {
        'emails':[]
      }
      for(let i in this.privilege_value){
          let data = {}
          if(this.selected_view[i]['value'] != this.privilege_value[i]['value']){
              email['emails'].push(this.dataList[i]['email'])
              data['uid'] = this.dataList[i]['uid']
              data['role'] = this.selected_view[i]['value'].toLowerCase();
              dict['data_admin'].push(data)
          }
      }
      axios.post('/api/sendemail',email).then(response => {
          this.changePrivilegeValue(dict)
          // this.filterData()
      })
      .catch(err => {
          this.$vs.notify({
                time:8000,
                title: 'Warning',
                text: 'Changed failed, please try again.',
                color: 'warning'
          })
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
    changePrivilegeValue (dict) {
        this.buttonEmmit = false;
        // this.$vs.loading({
        //     container: '#div-with-loading',
        //     scale: 0.6
        // })

        // let dict = {
        //     'data_admin':[]
        // }
        // for(let i in this.privilege_value){
        //     let data = {}
        //     if(this.selected_view[i]['value'] != this.privilege_value[i]['value']){
        //         data['uid'] = this.dataList[i]['uid']
        //         data['role'] = this.selected_view[i]['value'].toLowerCase();
        //         dict['data_admin'].push(data)
        //     }
        // }
        axios.post('/api/updateAdmin',dict).then(response => {
            this.$router.go()
            this.$vs.loading.close('#div-with-loading > .con-vs-loading')
            // this.filterData()
        })
        .catch(err => {
            this.$vs.notify({
                  time:8000,
                  title: 'Warning',
                  text: 'Changed failed, please try again.',
                  color: 'warning'
            })
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
        text: 'Are you sure you want to delete this user?',
        accept: this.deletedata,
      })
    },
    getDataset (filter_value) {
      this.$vs.loading({
          container: '#div-with-loading',
          scale: 0.6
      })
      this.sort_by = filter_value;
      let postData = {}
      this.selected_view = []
      this.privilege_value = []   
      if(this.selected_sortby != ''){
        let sortData = {
          'sort': this.selected_sortby
        }
        postData = Object.assign({}, this.sort_by, sortData);
      }else{
        postData = this.sort_by
      }
      // console.log(filter_value)
      axios.post('/api/userlist',postData)
        .then(response => {
          this.dataList = response.data;
          for(let i of this.dataList){
              let privilegeValue = i.role[0].toUpperCase() +  i.role.slice(1)
              let dict = {
                  'label': privilegeValue,
                  'value': i.role
              }
              this.selected_view.push(dict)
              this.privilege_value.push(dict)          
          }          
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
      axios.post('/api/removeuser',this.dataInfo).then(response => {
        this.$vs.loading.close('#div-with-loading > .con-vs-loading')
        this.filterData ()
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

<style lang="scss">

.change_select {
  width: 150px;
  font-size: 12px;
  text-align: left;
  border-radius: 5px;  
  color: white;
  background-color: rgba(var(--vs-success), 1);
}

.change_select_addr .vs__selected {
  color: white !important;
}

.change_select_addr2 .vs__clear {
  color: #ffffff !important;
}

// ------------------------------------ //

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

.css_username {
  padding-left: 35px;
}

.css_admin {
  width: 150px;
  height: 34.19px;
  padding: 3px 10px;
  font-size: 12px!important;
  text-align: center;
  border-radius: 5px;
  cursor: pointer;
}

.button_change_css {
  width: 160px;
  height: 34.19px;
  padding: 3px 10px;
  font-size: 12px!important;
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

.container-data {
  height: 43vh;
  min-height: 300px!important;
  overflow: auto;
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