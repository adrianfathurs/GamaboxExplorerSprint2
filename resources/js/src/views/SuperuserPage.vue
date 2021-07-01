<template>
  <div>
    <div class="vx-row">
      <vx-card>
        <div class="vx-col flex w-full flex-wrap clearfix">
          <h2 class="ml-2 w-full mr-auto">Superuser Settings</h2>
          <p class="ml-2 w-full mr-auto">Manage users, datasets, and approval.</p>
        </div>
      </vx-card>
    </div>     
    <vx-card
      card-background="#dfe3e5"
      content-color="#000000">
      <div class="mb-3">
        <vs-tabs>
          <vs-tab label="User">
            <div>    
              <userPages></userPages>
            </div>
          </vs-tab>
          <vs-tab label="Dataset">
            <div>
              <datasetPages></datasetPages>
            </div>
          </vs-tab>
          <vs-tab label="Approval">
            <div>    
              <approvalPages></approvalPages>
            </div>
          </vs-tab>
        </vs-tabs>  
      </div>
    </vx-card>  
  </div>
</template>

<script>
import datasetPages from './pages/datasetSettings.vue'
import userPages from './pages/userSuperuser.vue'
import approvalPages from './pages/approvalSettings.vue'
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
      search_value: '',
      selected_searchby: '',
      selected_sortby: '',
      searchby: [
        { value: "dataset_name", text: "Dataset Name" },
        { value: "username", text: "Username" }
      ],
      sortby: [
        { value: "dataset_name", text: "Dataset Name" },
        { value: "username", text: "Username" },
        { value: "date", text: "Date" }        
      ],
    }
  },
  components: {
    'v-select': vSelect,
    datasetPages,
    userPages,
    approvalPages,
  },
  computed: {
    activeUserInfo () {
      // console.log(this.$store.state.AppActiveUser);
      return this.$store.state.AppActiveUser
    }
  },
  methods:{
    search_dataset() {      
      localStorage.removeItem("search_dataset");
      // console.log(data)
      localStorage.setItem("search_dataset", this.search_value);
      this.$router.push('/adminpage').catch(() => {})
    },
  }

}
</script>

<style>

[dir=ltr] .vs-input--input.hasIcon {
    padding: 0.5rem 1rem 0.7rem 3rem !important;
}

@mixin clearfix() {
  &::after {
    display: block;
    content: "";
    clear: both;
  }
}

.element {
  @include clearfix;
}

</style>