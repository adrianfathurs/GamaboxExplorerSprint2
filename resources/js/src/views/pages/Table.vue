<template>
  <b-container fluid>
    <!-- User Interface controls -->
    <b-row>
      <b-col lg="6" class="my-1">
        <b-form-group
          label="Files"
          label-cols-sm="6"
          label-cols-md="4"
          label-cols-lg="3"
          label-align-sm="right"
          label-size="sm"
          label-for="treeData"
          class="mb-0"
        >
          <v-select-tree :data='treeData' v-model='initSelected' :radio="true"/>
        </b-form-group>
      </b-col>

      <b-col lg="6" class="my-1">
        <b-form-group
          label="Per page"
          label-cols-sm="6"
          label-cols-md="4"
          label-cols-lg="3"
          label-align-sm="right"
          label-size="sm"
          label-for="perPageSelect"
          class="mb-0"
        >
          <b-form-select
            v-model="perPage"
            id="perPageSelect"
            size="sm"
            :options="pageOptions"
          ></b-form-select>
        </b-form-group>
      </b-col>

      <b-col lg="6" class="my-1">
        <b-form-group
          label="Sort"
          label-cols-sm="3"
          label-align-sm="right"
          label-size="sm"
          label-for="sortBySelect"
          class="mb-0"
        >
          <b-input-group size="sm">
            <b-form-select v-model="sortBy" id="sortBySelect" :options="sortOptions" class="w-75">
              <template v-slot:first>
                <option value="">-- none --</option>
              </template>
            </b-form-select>
            <b-form-select v-model="sortDesc" size="sm" :disabled="!sortBy" class="w-25">
              <option :value="false">Asc</option>
              <option :value="true">Desc</option>
            </b-form-select>
          </b-input-group>
        </b-form-group>
      </b-col>

      <b-col lg="6" class="my-1">
        <b-form-group
          label="Initial sort"
          label-cols-sm="3"
          label-align-sm="right"
          label-size="sm"
          label-for="initialSortSelect"
          class="mb-0"
        >
          <b-form-select
            v-model="sortDirection"
            id="initialSortSelect"
            size="sm"
            :options="['asc', 'desc', 'last']"
          ></b-form-select>
        </b-form-group>
      </b-col>

      <b-col lg="6" class="my-1">
        <b-form-group
          label="Filter"
          label-cols-sm="3"
          label-align-sm="right"
          label-size="sm"
          label-for="filterInput"
          class="mb-0"
        >
          <b-input-group size="sm">
            <b-form-input
              v-model="filter"
              type="search"
              id="filterInput"
              placeholder="Type to Search"
            ></b-form-input>
            <b-input-group-append>
              <b-button :disabled="!filter" @click="filter = ''">Clear</b-button>
            </b-input-group-append>
          </b-input-group>
        </b-form-group>
      </b-col>

      <b-col lg="6" class="my-1">
        <b-form-group
          label="Filter On"
          label-cols-sm="3"
          label-align-sm="right"
          label-size="sm"
          description="Leave all unselect to filter on all data"
          class="mb-0">
          <b-form-checkbox-group v-model="filterOn" class="mt-1">
            <v-select multiple :closeOnSelect="false" v-model="filterOn" :options="keyTable" :dir="$vs.rtl ? 'rtl' : 'ltr'" /><br>
            <!-- <b-form-checkbox v-for="(key,i) in keyTable" :key="i"  :value="key">{{key.charAt(0).toUpperCase() + key.slice(1)}}</b-form-checkbox> -->
          </b-form-checkbox-group>
        </b-form-group>
      </b-col>

      <b-col sm="7" md="6" class="my-1">
        <b-pagination
          v-model="currentPage"
          :total-rows="totalRows"
          :per-page="perPage"
          align="fill"
          size="sm"
          class="my-0"
        ></b-pagination>
      </b-col>
    </b-row>

    <!-- Main table element -->
    <b-table
      show-empty
      small
      class="table_xls"
      stacked="md"
      :items="items"
      :fields="fieldsTable"
      :current-page="currentPage"
      :per-page="perPage"
      :filter="filter"
      :filter-included-fields="filterOn"
      :sort-by.sync="sortBy"
      :sort-desc.sync="sortDesc"
      :sort-direction="sortDirection"
      @filtered="onFiltered"
    >
    </b-table>

    <!-- Info modal -->
    <b-modal :id="infoModal.id" :title="infoModal.title" ok-only @hide="resetInfoModal">
      <pre>{{ infoModal.content }}</pre>
    </b-modal>
  </b-container>
</template>

<script>
import {
  BButton,
  BCol,
  BContainer,
  BFormCheckboxGroup,
  BFormGroup,
  BFormInput,
  BFormSelect,
  BFormTextarea,
  BInputGroup,
  BModal,
  BPagination,
  BRow,
  BTable
} from 'bootstrap-vue'

import { VTree, VSelectTree}  from 'vue-tree-halower'
import vSelect from 'vue-select'
const axios = require('axios');

export default {
  data() {
    return {
      initSelected: [],
      treeData: [],
      items: [],
      fieldsTable: [],
      keyTable: [],
      totalRows: 1,
      currentPage: 1,
      perPage: 5,
      pageOptions: [5, 10, 15],
      sortBy: '',
      sortDesc: false,
      sortDirection: 'asc',
      filter: null,
      filterOn: [],
      infoModal: {
        id: 'info-modal',
        title: '',
        content: ''
      }
    }
  },
  components: {
    BButton,
    BCol,
    BContainer,
    BFormCheckboxGroup,
    BFormGroup,
    BFormInput,
    BFormSelect,
    BFormTextarea,
    BInputGroup,
    BModal,
    BPagination,
    BRow,
    BTable,
    VTree,
    VSelectTree,
    'v-select': vSelect,
  },
  watch: {
    initSelected() {
      if(this.initSelected.length != 0){
        let extension = this.initSelected[0].split('.').pop()
        this.getData(extension,this.initSelected[0])
      }
    }
  },
  computed: {
    sortOptions() {
      // Create an options list from our fields
      return this.fieldsTable
        .filter(f => f.sortable)
        .map(f => {
          return { text: f.label, value: f.key }
        })
    }
  },
  async created () {
    await axios.get('/api/opendata/').then(response => {
              return this.treeData  = response.data;
          });
  },
  methods: {
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
    async getData(ext,file) {
      await axios.get('/api/getdata/'+file).then(response => {
              return this.items  = response.data;
          });
      if(this.items.length==0){
        this.keyTable = []
        this.fieldsTable = []
        this.totalRows = this.items.length
      }else{
        this.keyTable = Object.keys(this.items[0])
        this.fieldsTable = []
        for(let i of this.keyTable) {
            const dict = {}
            dict['key'] = i
            dict['label'] = i.charAt(0).toUpperCase() + i.slice(1)
            dict['sortable'] = true
            this.fieldsTable.push(dict)
        }
        // Set the initial number of items
        this.totalRows = this.items.length
        // console.log(this.fieldsTable)
      }        
    }
  },
  mounted() {
    // this.getData()
  }
}
</script>

<style lang="scss">
@import "@sass/vuexy/extraComponents/tree.scss";
.tree-container {
  width: 100% !important;
}

.tree-box{
  background: #fff;
  position: relative;
  z-index: 9;

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

.table_xls{
  background-color: white;
}
</style>