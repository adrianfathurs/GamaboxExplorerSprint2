<template>
    <div>
        <div class="vx-row">
            <div class="vx-col w-full md:w-3/3 lg:w-1/3 xl:w-1/4 mb-base">
                <div id="sidebar_menu" class="sidebar vs-con-loading__container">
                    
                     <sidebar-menu :menu="menu" :hideToggle="true " @item-click="filter"/> 
                
                </div>      
            </div>
            <div class="vx-col w-full md:w-3/3 lg:w-2/3 xl:w-3/4 mb-base">
                <b-card
                header-tag="header"
                footer-tag="footer"
                title=""
                class="sidebar"
                >
                    <template #header>
                        <div class="vx-row">
                            <div class="vx-col w-full md:w-3/3 lg:w-1/2 xl:w-1/2">
                                <h2 class="header-container">Dataset List</h2>
                            </div>
                            <div v-if="activeUserInfo.email" class="vx-col w-full md:w-3/3 lg:w-1/2 xl:w-1/2">
                                <vs-button size="small" class="button-upload float-right header-container" :color="colorx_btn" @click="popupActive=true" type="filled">New Upload</vs-button>
                            </div>
                        </div>
                    </template>

                    <div class="flex mb-2">
                        <input id="e_keyword" class="w-3/4 ml-2 form-control mt-3" type="text"
                        v-model="search_keyword"
                        placeholder="input search keywords..." name="q">
                        <vs-button class="button-search ml-3 mt-2" size="small" color="rgb(255, 111, 0)" type="filled" icon-pack="feather" icon="icon-search" v-on:click="searchData">Search</vs-button>
                        <vs-button class="button-Asearch ml-3 mt-2" size="small" color="#708afa" type="filled" @click="popupActiveAdSearch=true">Advanced Search</vs-button>
                    </div>
                    
                    <div class="flex mb-4 w-full">
                        <div class="w-full md:w-3/3 lg:w-1/4 xl:w-1/4">
                            <p class="ml-4 mt-4 flex">Sort by:</p>
                        </div>
                        <div class="w-full md:w-3/3 lg:w-2/4 xl:w-2/4">
                            <b-form-select v-model="selected" style="width:119%;" :options="select" size="sm" class="mt-3"></b-form-select>
                        </div>  
                        <div class="w-full md:w-3/3 lg:w-3/4 xl:w-3/4 mb-base">
                            <vs-popup v-if="emailVerified" background-color="rgba(255,255,255,.6)"  title="Upload Data" :active.sync="popupActive">
                                <uploadPage></uploadPage>
                            </vs-popup>
                        </div>
                    </div>

                    <div class="w-full md:w-3/3 lg:w-3/4 xl:w-3/4 mb-base">
                        <vs-popup background-color="rgba(255,255,255,.6)"  title="Advanced Search" :active.sync="popupActiveAdSearch">

                            <div id="div-upload-loading" class="vs-con-loading__container">
                                <form enctype="multipart/form-data">
                                    <div class="vx-row mb-1">
                                        <div class="vx-col w-full">
                                            <h6 class="mr-2 mt-3">Keyword</h6>
                                        </div>
                                        <div class="vx-col w-full">
                                            <!-- <ValidationProvider name="Keyword" rules="required|min:3" v-slot="{ errors }"> -->
                                                <vs-input class="w-full" v-model="keyword" />        
                                                <!-- <span>{{ errors[0] }}</span>
                                            </ValidationProvider>                  -->
                                        </div>
                                    </div>
                                    <div class="vx-row">
                                        <div class="vx-col w-full">
                                            <h6 class="mr-2 mt-3">Category</h6>
                                        </div>
                                        <div class="vx-col w-full">
                                            <!-- <ValidationProvider name="Select_Category" rules="required" v-slot="{ errors }"> -->
                                                <v-select 
                                                    class="bg-white mt-1 w-full" 
                                                    v-model="select_category" 
                                                    :options="category" 
                                                    :dir="$vs.rtl ? 'rtl' : 'ltr'" 
                                                    /><br>    
                                                <!-- <span>{{ errors[0] }}</span>
                                            </ValidationProvider>    -->
                                        </div>
                                    </div>
                                    <div class="vx-row">
                                        <div class="vx-col w-full">                                           
                                            <div>
                                                <h6 class="mr-2 mb-3">Sub Category</h6>
                                                <!-- <ValidationProvider name="Select_Subcategory" rules="required" v-slot="{ errors }"> -->
                                                    <v-select class="bg-white mt-1 w-full mb-3" 
                                                        taggable 
                                                        push-tags
                                                        v-model="select_subcategory"
                                                        :options="subcategory[category_selected]" 
                                                        :dir="$vs.rtl ? 'rtl' : 'ltr'" 
                                                        />   
                                                    <!-- <span>{{ errors[0] }}</span>
                                                </ValidationProvider>    -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="vx-row">
                                        <div class="vx-col w-full">
                                            <div>
                                                <h6 class="mr-2 mb-3">Tagging</h6>   
                                                <!-- <ValidationProvider name="Select_Tagging" rules="required" v-slot="{ errors }"> -->
                                                    <b-form-tags
                                                        input-id="tags-remove-on-delete tags-basic"
                                                        :input-attrs="{ 'aria-describedby': 'tags-remove-on-delete-help' }"
                                                        v-model="select_tagging"
                                                        separator=" "
                                                        placeholder="Add tag..."
                                                        remove-on-delete
                                                        no-add-on-enter
                                                    ></b-form-tags>  
                                                    <!-- <span>{{ errors[0] }}</span>
                                                </ValidationProvider>                          -->
                                            </div>
                                        </div>

                                    </div>                                    
                                    <div class="vx-row mb-1">
                                        <div class="vx-col w-full">
                                            <h6 class="mr-2 mt-3">Sort by</h6>
                                        </div>
                                        <div class="vx-col w-full">
                                            <v-select 
                                                name="sortby" 
                                                class="bg-white mt-1 w-full" 
                                                v-model="select_sortby"
                                                :options="sortby" :dir="$vs.rtl ? 'rtl' : 'ltr'" />
                                        </div>
                                    </div>
                                    <div class="vx-row">
                                        <div class="vx-col w-full mt-3">
                                            <vs-button class="float-right" style="border-radius: 5px;" color="rgb(255, 111, 0)" type="filled" @click.prevent="search_advanced">Search</vs-button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </vs-popup>
                    </div>

                    <template #footer>
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
                                    <template #head(data)="data">
                                      <p class="head-table">{{ data.label }}</p>
                                    </template>
                                    <template #head(imgs)="data">
                                      <p class="head-table" style="color:transparent">{{ data.label }}</p>
                                    </template>
                                    <template #head(created_date)="data">
                                      <p class="head-table">{{ data.label }}</p>
                                    </template>
                                    <template #head(actions)="data">
                                      <p class="head-table" style="text-align:center !important;">{{ data.label }}</p>
                                    </template>
                                    <template #cell(imgs)="row">
                                        <i v-if="row.item.type.toLowerCase() === 'xls' || row.item.type.toLowerCase() === 'xlsx'" class="fa fa-file-excel fa-4x opacityIcon" aria-hidden="true"></i>
                                        <i v-else-if="row.item.type.toLowerCase() === 'jpg' || row.item.type.toLowerCase() === 'jpeg' || row.item.type.toLowerCase() === 'png' || row.item.type.toLowerCase() === 'bmp'" class="fa fa-file-image fa-4x opacityIcon" aria-hidden="true"></i>
                                        <i v-else-if="row.item.type.toLowerCase() === 'shp' || row.item.type.toLowerCase() === 'dbf' || row.item.type.toLowerCase() === 'shx'" class="fas fa-map-marked-alt fa-4x opacityIcon" aria-hidden="true"></i>
                                        <i v-else-if="row.item.type.toLowerCase() === 'doc'" class="fa fa-file-word fa-4x opacityIcon" aria-hidden="true"></i>
                                        <i v-else-if="row.item.type.toLowerCase() === 'csv'" class="fa fa-file-csv fa-4x opacityIcon" aria-hidden="true"></i>
                                        <i v-else-if="row.item.type.toLowerCase() === 'pdf'" class="fa fa-file-pdf fa-4x opacityIcon" aria-hidden="true"></i>
                                        <i v-else-if="row.item.type.toLowerCase() === 'zip' || row.item.type.toLowerCase() === 'rar'" class="fa fa-file-archive fa-4x opacityIcon" aria-hidden="true"></i>
                                        <i v-else class="fa fa-file fa-4x opacityIcon" aria-hidden="true"></i>    
                                    </template>
                                    <template #cell(data)="row">
                                        <h6><strong>{{row.item.dataset_name}}</strong></h6>
                                        <p>{{row.item.description}}</p>        
                                    </template>                  
                                        <template #cell(actions)="row">
                                            <div class="flex flex-wrap justify-center items-center">
                                                <vs-button class="mr-2 mb-1 button-addon1" size="small" color="#ffffff" type="filled" @click="detailed(row.item)">Details</vs-button>
                                                <a :href="'api/downloadzip/'+row.item.created_by+'/'+row.item.dataset_name" style="cursor:pointer;" class="button-download button1-download">Download</a> 
                                            </div>
                                        </template>

                                    

                                    </b-table>

                                </b-container>
                                </div>
                            </template>

                        </div>   
                    </template>                   
                    
                </b-card>    
            </div>
        </div>  
    </div>
</template>

<script>
// bootstrap
import {
  BCard,
  BFormSelect,
  BFormTags,
  BContainer,
  BTable
} from 'bootstrap-vue'

// vee validate
// import { ValidationProvider, extend } from 'vee-validate';

// extend('required', {
//   validate(value) {
//     return {
//       required: true,
//       valid: ['', null, undefined].indexOf(value) === -1
//     };
//   },
//   computesRequired: true
// });
// extend('min', {
//   validate(value, args) {
//     return value.length >= args.length;
//   },
//   params: ['length']
// });

import { SidebarMenu } from 'vue-sidebar-menu'
import vSelect from 'vue-select'
import uploadPage from './pages/Upload.vue'

const axios = require('axios');

export default {
    data() {
        return {
            fields: [
                { key: 'imgs', label: 'Icon' },
                { key: 'data', label: 'Dataset Name'},
                { key: 'created_date', label: 'Uploaded Date'},
                { key: 'actions', label: 'Option' }
            ],           
            emailVerified: false,      
            datasetName: '',
            email: '',
            password: '',
            confirm_password: '',
            tagging: [],
            colorx_btn:"rgb(0,123,255)",
            popupActive: false,
            popupActiveAdSearch: false,
            textarea: '',
            selected: '',
            search_keyword: '',
            table_selected: {},
            sort_by: {},
            select: [
                {
                text: 'Date', 
                value: 'created_date'
                },
                {
                text: 'Dataset', 
                value: 'dataset_name'
                }
            ],
            dataList: [],
            menu: [],
            sub_category_migas: [],
            sub_category_batu_bara: [],
            sub_category_panas_bumi: [],
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
                'December',
            ],
            filterHeader: '',
            filterSelect: '',
            keyword: '',
            select_sortby: '',
            selected_cat: '',
            sortby: [
                {label: 'Dataset Name', value: 'dataset_name'}, 
                {label: 'Date', value: 'created_date'}
            ],
            category: [
                {label: 'Migas', value: 'migas'}, 
                {label: 'Batu Bara', value: 'batubara'}, 
                {label: 'Panas Bumi', value: 'panasbumi'},
                {label: 'ALL', value: ''},
            ],
            subcategory: {
                empty:[],
                all: [
                    {label: 'ALL', value: ''},
                ],
                subcategory_migas:[
                    {label: 'ALL', value: ''},
                    {label: 'Sejarah Eksplorasi dan Data Umum', value: 'sejaraheksplorasidandataumum'}, 
                    {label: 'Batuan Induk', value: 'batuaninduk'}, 
                    {label: 'Batuan Reservoar', value: 'batuanreservoar'},
                    {label: 'Batuan Penyekat/Seal', value: 'batuanpenyekatseal'}, 
                    {label: 'Jebakan Migas/Traps', value: 'jebakanmigastraps'},
                    {label: 'Migrasi', value: 'migrasi'}, 
                    {label: 'Sumber Daya', value: 'sumberdaya'},
                    {label: 'Cadangan', value: 'cadangan'}, 
                    {label: 'Produksi', value: 'produksimigas'},
                    {label: 'Cadangan Tersisa', value: 'cadangantersisa'}
                ],
                subcategory_batu_bara:[
                    {label: 'ALL', value: ''},
                    {label: 'Data Lokasi', value: 'datalokasibb'},
                    {label: 'Formasi Pembawa Batubara', value: 'formasipembawabatubarabb'}, 
                    {label: 'Lapisan Batubara', value: 'lapisanbatubarabb'},
                    {label: 'Data Karakteristik', value: 'datakarakteristikbb'}, 
                    {label: 'Sumberdaya dan Cadangan', value: 'sumberdayadancadanganbb'},
                    {label: 'Produksi', value: 'produksibb'}
                ],
                subcategory_panas_bumi:[
                    {label: 'ALL', value: ''},
                    {label: 'Data Lokasi', value: 'datalokasipb'}, 
                    {label: 'Formasi Pembawa Panas Bumi', value: 'formasipembawaPanas Bumipb'}, 
                    {label: 'Lapisan Panas Bumi', value: 'lapisanPanas Bumipb'},
                    {label: 'Data Karakteristik', value: 'datakarakteristikpb'}, 
                    {label: 'Sumberdaya dan Cadangan', value: 'sumberdayadancadanganpb'}, 
                    {label: 'Produksi', value: 'produksipb'}
                ]
            },
            select_category: [
                {label: 'ALL', value: ''},
            ],
            select_subcategory: [
                {label: 'ALL', value: ''},
            ],
            select_tagging: [],
            category_selected: 'all',
        }
    },  
    components: {
        BCard,
        BFormSelect,
        BFormTags,        
        BContainer,
        BTable,
        // ValidationProvider,
        'v-select': vSelect,
        uploadPage,
        SidebarMenu,
    },
    watch: {
        popupActive() {
            if(!this.emailVerified){
                this.$router.push('/pages/unverified')
            }
        },
        selected() {
            if(this.selected !== ''){
                this.getDataset(this.sort_by)
            }
        },
        select_category(){
            if(this.select_category == null){
                this.category_selected = 'all'   
                this.select_subcategory = null             
            }else{
                if(this.select_category.value == 'migas'){
                    this.category_selected = 'subcategory_migas'
                }else if(this.select_category.value == 'batubara'){
                    this.category_selected = 'subcategory_batu_bara'
                }else if(this.select_category.value == 'panasbumi'){
                    this.category_selected = 'subcategory_panas_bumi'
                }else{
                    this.category_selected = 'all'
                }
            }
        }        
    },
    computed: {
        activeUserInfo () {
          return this.$store.state.AppActiveUser
        },
    },
    methods: {
        search_advanced() {     
            this.popupActiveAdSearch = false
            this.selected = ''
            let search = {
                'filter_name': 'advsearch',
                'file_name': this.keyword,
                'category': this.select_category.value,
                'subcategory': this.select_subcategory.value,
                'tag': this.select_tagging,
                'sort': this.select_sortby.value,
            }
            // console.log(search)
            if(search['category'] == undefined){
                search['category'] = ''
            }
            if(search['subcategory'] == undefined){
                search['subcategory'] = ''
            }
            // console.log(search)
            this.getDataset(search) 
        },
        detailed (data) {
            localStorage.removeItem("dataset_info");
            // console.log(data)
            localStorage.setItem("dataset_info", JSON.stringify(data));
            this.$router.push('/detailedpage').catch(() => {})
        },
        downloadfile (data) {        
            //const config = {
            //     headers: { 'content-type': 'multipart/form-data' }
            // 
            // disini nanti apinya bisa dipanggil setelah melempar data
            // untuk download zip dia bakal ngezip folder ntar yang di append diganti
            // data this.row. nama dataset dan username
            // untuk yang download file biasa tinggal mlempar data file pathnya sama dataset name buat counter downloadnyanya buat counter

            //const formData = new FormData();
            // console.log(this.file);

            // formData.append('file', this.file);
            //formData.append('path', 'dataset_id');
            //formData.append('username', 'activeUserInfo.displayName');
            // console.log(this.file)
            this.$vs.loading({
                container: '#div-upload-loading',
                scale: 0.6
            })

            axios.post('/api/downloadzip',JSON.stringify(data)).then(response => {
                // console.log(response);
                this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
                this.$vs.notify({
                    title: 'Succes',
                    text: 'file has been downloaded.',
                    color: 'success'
                })
            });
        },
        filterData () {
            this.$vs.loading({
                container: '#sidebar_menu',
                scale: 0.6
            })
            this.selected = ''
            axios.post('/api/filter',{})
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
        getDataset (filter_value) {
            this.$vs.loading({
                container: '#div-with-loading',
                scale: 0.6
            })
            this.sort_by = filter_value;
            let postData = {}
            if(this.selected != ''){
                let sortData = {
                    'sort': this.selected
                }
                postData = Object.assign({}, this.sort_by, sortData);
            }else{
                postData = this.sort_by
            }
            // console.log(filter_value)
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
        searchData () {
          if(!this.search_keyword == '' ) {
            let search = {
                'filter_name': 'search',
                'file_name': this.search_keyword,
            }
            this.selected = ''
            this.getDataset(search)
          }
          else {
            let search = {
                'filter_name': '',
                'file_name': this.search_keyword,
            }
            this.selected = ''
            this.getDataset(search)
          }
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
        }
    },
    created() {    
        // console.log(this.$store.state.AppActiveUser)    
        if(this.$store.state.AppActiveUser.emailVerified){
            this.emailVerified = true;
        }        
    },
    mounted() {
        this.filterData()
        if(localStorage.getItem("search_dataset")){
            this.search_keyword = localStorage.getItem("search_dataset")
            this.searchData()
        }else if(localStorage.getItem("select_category")){
            let select_category = localStorage.getItem("select_category")  
            let split_text = select_category.split(';')
            let filter_category = {}
            if(split_text.length==1){                
                filter_category = {
                    'filter_name': 'onlycategory',
                    'category': split_text[0]
                }
            }else{                
                filter_category = {
                    'filter_name': 'category',
                    'category': split_text[0],
                    'subcategory': split_text[1]
                }
            }
            // console.log(filter_category)
            this.getDataset(filter_category)
        }else{
            this.getDataset({'filter_name':''}) 
        }        
        localStorage.removeItem("search_dataset");
        localStorage.removeItem("select_category");
    }
}
</script>

<style lang="scss">
@import "vue-sidebar-menu/src/scss/vue-sidebar-menu.scss";
// @import 'node_modules/bootstrap/scss/bootstrap.scss';
// @import 'node_modules/bootstrap-vue/src/index.scss';

/* Scrollbar */

/* width */
::-webkit-scrollbar {
    width: 1px;
}

/* Track */
::-webkit-scrollbar-track {
    background: #f1f1f1; 
}

/* Handle */
::-webkit-scrollbar-thumb {
    background: #888; 
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
    background: #555; 
}
/* -------------------------------- */

.opacityIcon{
    font-size: 3em;
    color: #4ca2ff;
}
.sidebar {
    height: 72vh;
    min-height: 510px;
    background-color: white;
}
.v-sidebar-menu {
    position: inherit;
    height: -webkit-fill-available;
    border-radius: 10px;
    background-color:rgb(0 123 255 / 70%);;
    width: -webkit-fill-available;
    max-width: -webkit-fill-available !important;
}

[dir] .v-sidebar-menu .vsm--link_level-1 .vsm--icon {
    background-color:transparent;
}

[dir] .v-sidebar-menu.vsm_expanded .vsm--item_open .vsm--link_level-1 .vsm--icon {
    background-color:transparent;
}

[dir] .v-sidebar-menu.vsm_expanded .vsm--item_open .vsm--link_level-1 {
    background-color: #4286f5;
}

[dir] .v-sidebar-menu .vsm--link_level-2 {
    font-size: 13px !important;
}

[dir=ltr] .v-sidebar-menu .vsm--item {
    padding: 0px !important;
}

.vsm--link_level-2 {
    font-size: 13px !important;
}

[dir] .v-sidebar-menu .vsm--dropdown .vsm--list {
    background-color: #4286f5;
    padding-left: 30px;
}

[dir] .v-sidebar-menu .vsm--link {
    font-size: 15px;
}

.vs-button {
    border-radius: 10px;    
}
.v-sidebar-menu .vsm--item {
    padding-left: 15px;
}
.header-container {
    margin: 0px!important;
    border-radius: 5px;
    /* padding: 0px !important; */
}
.container-data {
    // height: 43vh;
    max-height: 43vh!important;
    min-height: 250px!important;
    overflow: auto;
}
[dir] .border-light {
    border-color: #d9d9d9 !important;
}
.button-addon1 {
  font-size: 12px !important;
  text-align: center;
  cursor: pointer;
  color: rgb(255, 111, 0);
  border: solid 2px rgb(255, 111, 0);
  font-weight: bold;
}
.button-search {
  font-size: 12px !important;
  text-align: center;
  cursor: pointer;
}
.button-Asearch {
  font-size: 10px !important;
  text-align: center;
  cursor: pointer;
}
.button-upload {
  font-size: 12px !important;
  text-align: center;
  cursor: pointer;
}
.button1-download {
  border-radius: 10px; 
  background-color: rgb(255, 111, 0);
  text-align: center;
  text-decoration: none;
  display: inline-block;

  font-size: 12px;
  margin: 4px 2px;
  transition-duration: 0.4s;
  cursor: pointer;
  color: white; 
  font-weight: bold;
  border: solid 2px  rgb(255, 111, 0);
  padding: 6px 16px;
}

.button1-download:hover {
  background-color: rgb(255, 111, 0);
  color: white;
}

.head-table {
  margin-bottom: 0px;
}

.action_css {
  margin-left: auto;
  margin-right: auto;
}

// .card-body {
//   min-height: 150px !important;
// }
</style>