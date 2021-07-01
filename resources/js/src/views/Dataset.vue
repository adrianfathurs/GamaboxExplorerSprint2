<template>
    <div class="vx-row">
        <!-- Sidebar Category -->
        <div class="vx-col w-full md:w-3/3 lg:w-1/3 xl:w-1/4 mb-base">
            <vs-card>
                <div class="sidebar pl-2 mt-5 ">
                    <h5><b>CATEGORY</b></h5>
                    <vs-collapse>
                        <vs-collapse-item icon-arrow="arrow_drop_down" >
                            <div slot="header">
                                <vs-checkbox
                                    v-model="checkBox"
                                    @click="batuBara()"
                                    vs-value="batubara"
                                    >Batu Bara [
                                    {{ checkBox1.length }} ]</vs-checkbox
                                >
                            </div>
                            <div class="pl-2">
                                <ul class="centerx">
                                    <li class="my-2">
                                        <vs-checkbox
                                            v-model="checkBox1"
                                            vs-value="Data Lokasi"
                                            >Data Lokasi
                                        </vs-checkbox>
                                    </li>
                                    <li class="my-2">
                                        <vs-checkbox
                                            v-model="checkBox1"
                                            vs-value="Formasi Pembawa Batubara"
                                            >Formasi Pembawa Batu
                                            Bara</vs-checkbox
                                        >
                                    </li>
                                    <li class="my-2">
                                        <vs-checkbox
                                            v-model="checkBox1"
                                            vs-value="Lapisan Batubara"
                                            >Lapisan Batubara</vs-checkbox
                                        >
                                    </li>
                                    <li class="my-2">
                                        <vs-checkbox
                                            v-model="checkBox1"
                                            vs-value="Data Karakteristik"
                                            >Data Karakteristik</vs-checkbox
                                        >
                                    </li>
                                    <li class="my-2">
                                        <vs-checkbox
                                            v-model="checkBox1"
                                            vs-value="Sumberdaya dan Cadangan"
                                            >Sumberdaya dan
                                            Cadangan</vs-checkbox
                                        >
                                    </li>
                                    <li class="my-2">
                                        <vs-checkbox
                                            v-model="checkBox1"
                                            vs-value="Produksi"
                                            >Produksi</vs-checkbox
                                        >
                                    </li>
                                </ul>
                            </div>
                        </vs-collapse-item>
                        <vs-collapse-item icon-arrow="arrow_drop_down">
                            <div slot="header">
                                <vs-checkbox
                                    v-model="checkBox"
                                    @click="Migas()"
                                    vs-value="migas"
                                    >Migas [
                                    {{ checkBox2.length }} ]</vs-checkbox
                                >
                            </div>
                            <div class="pl-3">
                                <ul class="centerx">
                                    <li class="my-2">
                                        <vs-checkbox
                                            v-model="checkBox2"
                                            vs-value="Sejarah Expolarasi dan Data Umum"
                                            >Sejarah Explorasi dan Data Umum
                                        </vs-checkbox>
                                    </li>
                                    <li class="my-2">
                                        <vs-checkbox
                                            v-model="checkBox2"
                                            vs-value="Batuan Induk"
                                            >Batuan Induk</vs-checkbox
                                        >
                                    </li>
                                    <li class="my-2">
                                        <vs-checkbox
                                            v-model="checkBox2"
                                            vs-value="Batuan Penyekat"
                                            >Batuan Penyekat</vs-checkbox
                                        >
                                    </li>
                                    <li class="my-2">
                                        <vs-checkbox
                                            v-model="checkBox2"
                                            vs-value="Batuan Reservoar"
                                            >Batuan Reservoar</vs-checkbox
                                        >
                                    </li>
                                    <li class="my-2">
                                        <vs-checkbox
                                            v-model="checkBox2"
                                            vs-value="Jebakan Migas"
                                            >Jebakan Migas</vs-checkbox
                                        >
                                    </li>
                                    <li class="my-2">
                                        <vs-checkbox
                                            v-model="checkBox2"
                                            vs-value="Migrasi"
                                            >Migrasi</vs-checkbox
                                        >
                                    </li>
                                    <li class="my-2">
                                        <vs-checkbox
                                            v-model="checkBox2"
                                            vs-value="Sumber Daya"
                                            >Sumber Daya</vs-checkbox
                                        >
                                    </li>
                                    <li class="my-2">
                                        <vs-checkbox
                                            v-model="checkBox2"
                                            vs-value="Cadangan"
                                            >Cadangan</vs-checkbox
                                        >
                                    </li>
                                    <li class="my-2">
                                        <vs-checkbox
                                            v-model="checkBox2"
                                            vs-value="Produksi"
                                            >Produksi</vs-checkbox
                                        >
                                    </li>
                                    <li class="my-2">
                                        <vs-checkbox
                                            v-model="checkBox2"
                                            vs-value="Cadangan Tersisa"
                                            >Cadangan Tersisa</vs-checkbox
                                        >
                                    </li>
                                </ul>
                            </div>
                        </vs-collapse-item>
                        <vs-collapse-item icon-arrow="arrow_drop_down">
                            <div slot="header">
                                <vs-checkbox
                                    v-model="checkBox"
                                    @click="panasBumi()"
                                    vs-value="panasbumi"
                                    >Panas Bumi [
                                    {{ checkBox3.length }} ]</vs-checkbox
                                >
                            </div>
                            <div class="">
                                <ul class="centerx">
                                    <li class="my-2 ">
                                        <vs-checkbox
                                            v-model="checkBox3"
                                            vs-value="Data Lokasi"
                                            >Data Lokasi</vs-checkbox
                                        >
                                    </li>
                                    <li class="my-2">
                                        <vs-checkbox
                                            v-model="checkBox3"
                                            vs-value="Formasi Pembawa Panas Bumi"
                                            >Formasi Pembawa Panas
                                            Bumi</vs-checkbox
                                        >
                                    </li>
                                    <li class="my-2">
                                        <vs-checkbox
                                            v-model="checkBox3"
                                            vs-value="Lapisan Panas Bumi"
                                            >Lapisan Panas Bumi</vs-checkbox
                                        >
                                    </li>
                                    <li class="my-2">
                                        <vs-checkbox
                                            v-model="checkBox3"
                                            vs-value="Data Karakteristik"
                                            >Data Karakteristik</vs-checkbox
                                        >
                                    </li>
                                    <li class="my-2">
                                        <vs-checkbox
                                            v-model="checkBox3"
                                            vs-value="Sumberdaya dan Cadangan"
                                            >Sumberdaya dan
                                            Cadangan</vs-checkbox
                                        >
                                    </li>
                                    <li class="my-2">
                                        <vs-checkbox
                                            v-model="checkBox3"
                                            vs-value="Produksi"
                                            >Produksi</vs-checkbox
                                        >
                                    </li>
                                </ul>
                            </div>
                        </vs-collapse-item>
                    </vs-collapse>

                    <div class="mt-10 mb-5 ">
                        <h5>
                            <b>YEARS{{ year }}</b>
                        </h5>

                        <div class="mb-5">
                            <div class="ml-4">
                                <vs-slider
                                    class="sliderYear justify-center"
                                    :color="colorx_btn"
                                    size="small"
                                    icon="star"
                                    :max="max"
                                    :min="min"
                                    v-model="year"
                                />
                            </div>
                            <vs-button
                                class="mr-5"
                                :color="colorx_btn"
                                size="small"
                                type="filled"
                                @click="rangeValue(year)"
                                style="float:right;font-size:14px"
                            >
                                Set Year
                            </vs-button>
                            <div style="clear:both"></div>
                        </div>
                    </div>
                    <div class="mt-10">
                        <h5>
                            <b>EXTENSION [{{ checkBox4.length }}]</b>
                        </h5>

                        <!-- Checkbox -->
                        <div class="checkBoxFont mt-5">
                            <ul class="row centerx d-flex">
                                <div class="col-6">
                                    <li>
                                        <vs-checkbox
                                            class="my-2"
                                            v-model="checkBox4"
                                            vs-value="shp"
                                            >shp</vs-checkbox
                                        >
                                    </li>
                                    <li>
                                        <vs-checkbox
                                            class="my-2"
                                            v-model="checkBox4"
                                            vs-value="dbf"
                                            >dbf</vs-checkbox
                                        >
                                    </li>
                                    <li>
                                        <vs-checkbox
                                            class="my-2"
                                            v-model="checkBox4"
                                            vs-value="dbSHP"
                                            >dbSHP</vs-checkbox
                                        >
                                    </li>
                                    <li>
                                        <vs-checkbox
                                            class="my-2"
                                            v-model="checkBox4"
                                            vs-value="las"
                                            >las</vs-checkbox
                                        >
                                    </li>
                                    <li>
                                        <vs-checkbox
                                            class="my-2"
                                            v-model="checkBox4"
                                            vs-value="mp4"
                                            >mp4</vs-checkbox
                                        >
                                    </li>
                                    <li>
                                        <vs-checkbox
                                            class="my-2"
                                            v-model="checkBox4"
                                            vs-value="prj"
                                            >prj</vs-checkbox
                                        >
                                    </li>
                                </div>
                                <div class="col-6">
                                    <li>
                                        <vs-checkbox
                                            class="my-2"
                                            v-model="checkBox4"
                                            vs-value="sbn"
                                            >sbn</vs-checkbox
                                        >
                                    </li>
                                    <li>
                                        <vs-checkbox
                                            class="my-2"
                                            v-model="checkBox4"
                                            vs-value="sbx"
                                            >sbx</vs-checkbox
                                        >
                                    </li>
                                    <li>
                                        <vs-checkbox
                                            class="my-2"
                                            v-model="checkBox4"
                                            vs-value="shSHP"
                                            >shSHP</vs-checkbox
                                        >
                                    </li>
                                    <li>
                                        <vs-checkbox
                                            class="my-2"
                                            v-model="checkBox4"
                                            vs-value="shx"
                                            >shx</vs-checkbox
                                        >
                                    </li>
                                    <li>
                                        <vs-checkbox
                                            class="my-2"
                                            v-model="checkBox4"
                                            vs-value="xlsx"
                                            >xlsx</vs-checkbox
                                        >
                                    </li>
                                    <li>
                                        <vs-checkbox
                                            class="my-2"
                                            v-model="checkBox4"
                                            vs-value="xml"
                                            >xml</vs-checkbox
                                        >
                                    </li>
                                    <li>
                                        <vs-checkbox
                                            class="my-2"
                                            v-model="checkBox4"
                                            vs-value="segy"
                                            >segy</vs-checkbox
                                        >
                                    </li>
                                </div>
                            </ul>
                        </div>

                        <!-- End Check Box -->
                    </div>
                </div>
            </vs-card>
        </div>
        <!-- End Sidebar Category -->
        <!-- Content -->
        <div class="vx-col w-full md:w-3/3 lg:w-2/3 xl:w-3/4 mb-base">
            <!-- Content Header -->
            <vx-card class="mb-1">
                    <div class="vx-row">
                        <div class="vx-col w-full md:w-3/3 lg:w-1/2 xl:w-1/2">
                            <h2 class="header-container">Dataset List</h2>
                        </div>
                        <div
                            v-if="activeUserInfo.email"
                            class="vx-col w-full md:w-3/3 lg:w-1/2 xl:w-1/2"
                        >
                            <vs-button
                                size="small"
                                class="button-upload float-right header-container"
                                :color="colorx_btn"
                                icon-pack="feather"
                                icon="icon-upload"
                                @click="popupActive = true"
                                type="filled"
                                style="font-size:14px"
                                >New Upload</vs-button
                            >
                        </div>
                    </div>
                </vx-card>
                <!-- End Content Header -->

            <div class="card">
                <!-- Header Card -->
                <!-- <div class="card-header">
                    <div class="vx-row">
                        <div class="vx-col w-full md:w-3/3 lg:w-1/2 xl:w-1/2">
                            <h2 class="header-container">Dataset List</h2>
                        </div>
                        <div
                            v-if="activeUserInfo.email"
                            class="vx-col w-full md:w-3/3 lg:w-1/2 xl:w-1/2"
                        >
                            <vs-button
                                size="small"
                                class="button-upload float-right header-container"
                                :color="colorx_btn"
                                @click="popupActive = true"
                                type="filled"
                                style="font-size:14px"
                                >New Upload</vs-button
                            >
                        </div>
                    </div>
                </div> -->
                <!-- Search Input-->
                <div class="card-body rounded-t-sm">
                    <!-- Search & Refresh -->
                    <div class="flex mb-5 text-center">
                        <input
                            id="e_keyword"
                            class="w-3/4 ml-2 form-control mt-3"
                            type="text"
                            v-model="search_keyword"
                            placeholder="Search dataset name"
                            name="q"
                        />
                        <vs-button
                            :disabled="statusSearchButton"
                            class=" button-search ml-3 mt-2"
                            size="small"
                            color="rgb(255, 111, 0)"
                            type="filled"
                            icon-pack="feather"
                            icon="icon-search"
                            @click="searchData()"
                            style="font-size:14px"
                            >Search</vs-button
                        >
                        <!--  <vs-button class="button-Asearch ml-3 mt-2" size="small" color="#708afa" type="filled" @click="popupActiveAdSearch=true">Advanced Search</vs-button> -->
                        <vs-button
                            class="button-refresh ml-3 mt-2"
                            size="small"
                            type="filled"
                            icon-pack="feather"
                            icon="icon-refresh-cw"
                            :color="colorx_btn"
                            @click="refresh()"
                            >Refresh</vs-button
                        >
                    </div>
                    <!-- End Search & Refresh -->




                    <!-- Filter Sort By -->
                    <!-- <div class="w-100 ml-3">
                        <div class="flex">
                            <div class="col-2">
                                <p class="m-0 p-0">Sort Dataset by : </p>
                            </div>
                            <div class="w-4/12 flex">
                                <p class="m-0 p-0 w-2/5">Sort Dataset by : </p>
                                <div class="w-50">
                                <b-form-select
                                    v-model="selected"
                                    :options="select"
                                    size="sm"
                                ></b-form-select>
                                </div>
                            </div>
                            <div class="ml-5 d-flex align-items-center">
                                <b-icon-table class="mr-2"></b-icon-table>
                                <b-icon-list></b-icon-list>
                            </div>
                        </div> -->
                        <div class="flex mb-4 w-full">
                        <div class="w-full md:w-3/3 lg:w-1/4 xl:w-1/4">
                            <p class="ml-4 mt-4 flex">Sort by:</p>
                        </div>
                        <div class="w-full md:w-3/3 lg:w-2/4 xl:w-2/4">
                            <b-form-select
                                v-model="selected"
                                style="width:100%;"
                                :options="select"
                                size="sm"
                                class="mt-3"
                            ></b-form-select>
                        </div>
                        <div class="w-full md:w-3/3 lg:w-3/4 xl:w-3/4 pt-4">
                        <div class="flex flex-wrap items-center justify-center">

                        <vs-switch v-model="active3">
                                <template #off>
                                    Grid
                                </template>
                                <template #on>
                                    Table
                                </template>
                            </vs-switch>
                        </div>

                        </div>
                        <div class="w-full md:w-3/3 lg:w-4/4 xl:w-4/4 mb-base">
                            <vs-popup
                                v-if="emailVerified"
                                background-color="rgba(255,255,255,.6)"
                                title="Upload Data"
                                :active.sync="popupActive"

                            >
                               <!--  <uploadPage></uploadPage> -->
                               <MapLoading></MapLoading>
                            </vs-popup>
                        </div>
                    </div>
                    <!-- End Filter Sort By -->
                </div>
                <div class="card-header" v-show="active3==true">
                    <div class="table-responsive ">
                        <!-- <div class="tableFixHead" style="min-height: 510px"> -->
                        <div class="tableFixHead" >
                            <table class="table">
                                <thead class="bg-blue-700">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Icon</th>
                                        <th scope="col">Dataset Name</th>
                                        <th scope="col">Category</th>
                                        <th scope="col">SubCategory</th>

                                        <th scope="col">Uploaded date</th>
                                        <th scope="col">Option</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr
                                        v-for="(item, index) in project"
                                        :key="index"
                                    >
                                        <th scope="row">{{ index + 1 }}</th>
                                        <td>
                                            <i
                                                v-if="
                                                    item.type.toLowerCase() ===
                                                        'xls' ||
                                                        item.type.toLowerCase() ===
                                                            'xlsx'
                                                "
                                                class="fa fa-file-excel fa-4x opacityIcon"
                                                aria-hidden="true"
                                            ></i>
                                            <i
                                                v-else-if="
                                                    item.type.toLowerCase() ===
                                                        'jpg' ||
                                                        item.type.toLowerCase() ===
                                                            'jpeg' ||
                                                        item.type.toLowerCase() ===
                                                            'png' ||
                                                        item.type.toLowerCase() ===
                                                            'bmp'
                                                "
                                                class="fa fa-file-image fa-4x opacityIcon"
                                                aria-hidden="true"
                                            ></i>
                                            <i
                                                v-else-if="
                                                    item.type.toLowerCase() ===
                                                        'shp' ||
                                                        item.type.toLowerCase() ===
                                                            'dbf' ||
                                                        item.type.toLowerCase() ===
                                                            'shx'
                                                "
                                                class="fas fa-map-marked-alt fa-4x opacityIcon"
                                                aria-hidden="true"
                                            ></i>
                                            <i
                                                v-else-if="
                                                    item.type.toLowerCase() ===
                                                        'doc'
                                                "
                                                class="fa fa-file-word fa-4x opacityIcon"
                                                aria-hidden="true"
                                            ></i>
                                            <i
                                                v-else-if="
                                                    item.type.toLowerCase() ===
                                                        'csv'
                                                "
                                                class="fa fa-file-csv fa-4x opacityIcon"
                                                aria-hidden="true"
                                            ></i>
                                            <i
                                                v-else-if="
                                                    item.type.toLowerCase() ===
                                                        'pdf'
                                                "
                                                class="fa fa-file-pdf fa-4x opacityIcon"
                                                aria-hidden="true"
                                            ></i>
                                            <i
                                                v-else-if="
                                                    item.type.toLowerCase() ===
                                                        'zip' ||
                                                        item.type.toLowerCase() ===
                                                            'rar'
                                                "
                                                class="fa fa-file-archive fa-4x opacityIcon"
                                                aria-hidden="true"
                                            ></i>
                                            <i
                                                v-else
                                                class="fa fa-file fa-4x opacityIcon"
                                                aria-hidden="true"
                                            ></i>
                                        </td>
                                        <td>
                                            <p>
                                                <b>{{ item.dataset_name }}</b>
                                            </p>
                                            <p>
                                                {{ item.description }} [.{{
                                                    item.type
                                                }}]
                                            </p>
                                        </td>
                                        <td>{{ item.category }}</td>
                                        <td>{{ item.subcategory }}</td>
                                        <td>{{ item.created_date }}</td>
                                        <td>
                                            <vs-button
                                                size="small"
                                                color="#FF8C00"
                                                type="border"
                                                @click="detailed(item)"
                                                >Detailed</vs-button
                                            >
                                            <a
                                                :href="
                                                    'api/downloadzip/' +
                                                        item.created_by +
                                                        '/' +
                                                        item.dataset_name
                                                "
                                                style="cursor:pointer;"
                                                class="button-download button1-download"
                                                >Download</a
                                            >
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div v-show="project.length == 0">
                                <center><p>Data tidak ditemukan</p></center>
                            </div>
                        </div>
                    </div>
                </div>
                <b-container v-show="active3==false" class="bv-example-row">
                    <vs-divider class="mb-6" position="center">
                        <h2>Dataset</h2>
                    </vs-divider>
                    <div class="sidebar mt-6">
                        <b-row >
                            <b-col sm="12" md="6" lg="4" class="flex flex-wrap justify-center mb-2 " v-for="(item,index) in project" :key="index">
                                <div v-if="item.image">
                                <b-card
                                   
                                    tag="article"
                                    
                                    class="mb-2"
                                >

                                     <img class="pl-2 pt-2" :src="item.image" width="250px" height="250px"  alt="image">
                                    <b-card-title>{{item.dataset_name}}</b-card-title>
                                    <b-card-text>
                                    {{item.description}} [.{{item.type}}]
                                    </b-card-text>
                                    <vs-button
                                                size="small"
                                                color="#FF8C00"
                                                type="border"
                                                @click="detailed(item)"
                                                >Detailed
                                    </vs-button>
                                    <a
                                        :href="'api/downloadzip/' +
                                                item.created_by +
                                                '/' +item.dataset_name"
                                        style="cursor:pointer;"
                                        class="button-download button1-download"
                                        >Download
                                    </a>
                                </b-card>
                                </div>
                                <div v-else-if="item.image == null && item.type=='shp' || item.image == null && item.type=='shx' || item.image == null && item.type=='dbf' ">
                                <b-card
                                    
                                    
                                    tag="article"
                                    
                                    class="mb-2"
                                >

                                    <img class="pl-2 pt-2" src="/images/dataset/shp.png" width="250px" height="250px"  alt="image">
                                    <b-card-title>{{item.dataset_name}}</b-card-title>    
                                    <b-card-text>
                                    {{item.description}} [.{{item.type}}]
                                    </b-card-text>
                                    <vs-button
                                                size="small"
                                                color="#FF8C00"
                                                type="border"
                                                @click="detailed(item)"
                                                >Detailed
                                    </vs-button>
                                    <a
                                        :href="'api/downloadzip/' +
                                                item.created_by +
                                                '/' +item.dataset_name"
                                        style="cursor:pointer;"
                                        class="button-download button1-download"
                                        >Download
                                    </a>
                                </b-card>
                                </div>
                                <div v-else-if="item.image == null && item.type=='las'">
                                <b-card
                                   
                                    img-top
                                    tag="article"
                                   
                                    class="mb-2"
                                >

                                    
                                    <img class="pl-2 pt-2" src="/images/dataset/las.png" width="250px" height="250px"  alt="image">
                                    <b-card-title>{{item.dataset_name}}</b-card-title>    
                                    <b-card-text>
                                    {{item.description}} [.{{item.type}}]
                                    </b-card-text>
                                    <vs-button
                                                size="small"
                                                color="#FF8C00"
                                                type="border"
                                                @click="detailed(item)"
                                                >Detailed
                                    </vs-button>
                                    <a
                                        :href="'api/downloadzip/' +
                                                item.created_by +
                                                '/' +item.dataset_name"
                                        style="cursor:pointer;"
                                        class="button-download button1-download"
                                        >Download
                                    </a>
                                </b-card>
                                </div>
                                <div v-else-if="item.image == null && item.type=='excel'">
                                <b-card
                                    
                                    tag="article"
                                    
                                    class="mb-2"
                                >
                                    <img class="pl-2 pt-2" src="/images/dataset/excel.png" width="250px" height="250px"  alt="image">
                                    <b-card-title>{{item.dataset_name}}</b-card-title>  

                                    <b-card-text>
                                    {{item.description}} [.{{item.type}}]
                                    </b-card-text>
                                    <vs-button
                                                size="small"
                                                color="#FF8C00"
                                                type="border"
                                                @click="detailed(item)"
                                                >Detailed
                                    </vs-button>
                                    <a
                                        :href="'api/downloadzip/' +
                                                item.created_by +
                                                '/' +item.dataset_name"
                                        style="cursor:pointer;"
                                        class="button-download button1-download"
                                        >Download
                                    </a>
                                </b-card>
                                </div>
                                <div v-else-if="item.image == null && item.type=='csv'">
                                <b-card
                                    
                                    
                                    tag="article"
                                    
                                    class="mb-2"
                                >
                                    
                                    <img class="pl-2 pt-2" src="/images/dataset/csv.png" width="250px" height="250px"  alt="image">
                                    <b-card-title>{{item.dataset_name}}</b-card-title>    

                                    <b-card-text>
                                    {{item.description}} [.{{item.type}}]
                                    </b-card-text>
                                    <vs-button
                                                size="small"
                                                color="#FF8C00"
                                                type="border"
                                                @click="detailed(item)"
                                                >Detailed
                                    </vs-button>
                                    <a
                                        :href="'api/downloadzip/' +
                                                item.created_by +
                                                '/' +item.dataset_name"
                                        style="cursor:pointer;"
                                        class="button-download button1-download"
                                        >Download
                                    </a>
                                </b-card>
                                </div>
                                <div v-else-if="item.image == null && item.type=='segy'">
                                <b-card
                                   
                                    tag="article"
                                   
                                    class="mb-2"
                                >

                                
                                    <img class="pl-2 pt-2" src="/images/dataset/segy.png" width="250px" height="250px"  alt="image">
                                    <b-card-title>{{item.dataset_name}}</b-card-title>    
                                    <b-card-text>
                                    {{item.description}} [.{{item.type}}]
                                    </b-card-text>
                                    <vs-button
                                                size="small"
                                                color="#FF8C00"
                                                type="border"
                                                @click="detailed(item)"
                                                >Detailed
                                    </vs-button>
                                    <a
                                        :href="'api/downloadzip/' +
                                                item.created_by +
                                                '/' +item.dataset_name"
                                        style="cursor:pointer;"
                                        class="button-download button1-download"
                                        >Download
                                    </a>
                                </b-card>
                                </div>
                                <div v-else>
                                <b-card
                                    
                                    tag="article"
                                    
                                    class="mb-2"
                                >
                                    
                                    <img class="pl-2 pt-2" src="/images/dataset/las.png" width="250px" height="250px"  alt="image">
                                    <b-card-title>{{item.dataset_name}}</b-card-title>    

                                    <b-card-text>
                                    {{item.description}} [.{{item.type}}]
                                    </b-card-text>
                                    <vs-button
                                                size="small"
                                                color="#FF8C00"
                                                type="border"
                                                @click="detailed(item)"
                                                >Detailed
                                    </vs-button>
                                    <a
                                        :href="'api/downloadzip/' +
                                                item.created_by +
                                                '/' +item.dataset_name"
                                        style="cursor:pointer;"
                                        class="button-download button1-download"
                                        >Download
                                    </a>
                                </b-card>
                                </div>
                            </b-col>
                        </b-row>
                    </div>
                </b-container>
            </div>
        </div>
        <!-- End Content -->
    </div>
</template>
<script>
import {
    BCard,
    BFormSelect,
    BFormTags,
    BContainer,
    BTable
} from "bootstrap-vue";
const VueRangeSlider = require("vue-range-component");
import "vue-range-component/dist/vue-range-slider.css";
/* import uploadPage from "./pages/Upload.vue"; */
import MapLoading from "./Map/MapLoading.vue";
const axios = require("axios");
export default {
    data() {
        return {
            active3: false,
            sliderValue: 50,
            isScrollStop: true,
            value: [2010, 2021],
            year: [2010, 2021],
            statusSearchButton: false,
            checkBox: [],
            checkBox1: [],
            checkBox2: [],
            checkBox3: [],
            checkBox4: [],
            popupActive: false,
            popupActiveAdSearch: false,
            mergeSubCategory2: "",
            coba: "hello",
            project: [],
            emailVerified: false,
            colorx_btn: "rgb(0,123,255)",
            colorx_btn2: "#FFA500",
            datasetName: "",
            email: "",
            password: "",
            selected: "",
            enableCross: "",
            confirm_password: "",
            search_keyword: "",
            keyword: "",
            select_sortby: "",
            tagging: [],
            select: [
                {
                    text: "None",
                    value: ""
                },

                {
                    text: "Subcategory",
                    value: "subcategory"
                },
                {
                    text: "Category",
                    value: "category"
                },
                {
                    text: "Dataset",
                    value: "dataset_name"
                }
            ],
            sortby: [
                { label: "Dataset Name", value: "dataset_name" },
                { label: "Date", value: "created_date" }
            ],
            fields: [
                { key: "imgs", label: "Icon" },
                { key: "dataset_name", label: "Dataset Name" },
                { key: "description", label: "Description" },
                { key: "created_date", label: "Uploaded Date" },
                { key: "actions", label: "Option" }
            ]
        };
    },
    components: {
        VueRangeSlider,
        BCard,
        BFormSelect,
        BFormTags,
        BContainer,
        BTable,
        /* uploadPage, */
        MapLoading
    },
    created() {
        this.min = 2010;
        this.max = 2021;
        this.enableCross = false;
        if (this.$store.state.AppActiveUser.emailVerified) {
            this.emailVerified = true;
        }
    },
    async mounted() {
        this.selected = "";
        this.search_keyword = "";
        var date = new Date();

        var tahunSaatIni = date.getFullYear();
        var body = {
            extensions: [],
            category: [],
            subcategory: [],
            dataset_name: "",
            sort_by: "created_date",
            startyear: this.min,
            endyear: tahunSaatIni
        };
        try {
            var response = await axios.post("api/dataset/filter", body);
            this.project = response.data;
            console.log(response.data);
        } catch (error) {
            alert(error);
        }
    },
    computed: {
        activeUserInfo() {
            return this.$store.state.AppActiveUser;
        }
    },
    watch: {
        popupActive() {
            if (!this.emailVerified) {
                this.$router.push("/pages/unverified");
            }
        },
        selected() {
            if (!this.selected == "") {
                this.getDataset(this.selected);
            }
        },
        async checkBox() {
            var mergeSubCategory = this.checkBox1.concat(this.checkBox2);
            this.mergeSubCategory2 = mergeSubCategory.concat(this.checkBox3);
            var body = {
                extensions: this.checkBox4,
                category: this.checkBox,
                subcategory: this.mergeSubCategory2,
                dataset_name: "",
                sort_by: "created_date",
                startyear: this.value[0],
                endyear: this.value[1]
            };
            var response = await axios.post("api/dataset/filter", body);
            this.project = response.data;
            console.log(response.data);
        },
        async checkBox2() {
            var mergeSubCategory = this.checkBox1.concat(this.checkBox2);
            this.mergeSubCategory2 = mergeSubCategory.concat(this.checkBox3);
            var body = {
                extensions: this.checkBox4,
                category: this.checkBox,
                subcategory: this.mergeSubCategory2,
                dataset_name: "",
                sort_by: "created_date",
                startyear: this.value[0],
                endyear: this.value[1]
            };
            var response = await axios.post("api/dataset/filter", body);
            this.project = response.data;
            console.log(response.data);
        },
        async checkBox1() {
            var mergeSubCategory = this.checkBox1.concat(this.checkBox2);
            this.mergeSubCategory2 = mergeSubCategory.concat(this.checkBox3);
            var body = {
                extensions: this.checkBox4,
                category: this.checkBox,
                subcategory: this.mergeSubCategory2,
                dataset_name: "",
                sort_by: "created_date",
                startyear: this.value[0],
                endyear: this.value[1]
            };
            var response = await axios.post("api/dataset/filter", body);
            this.project = response.data;
            console.log(response.data);
        },
        async checkBox4() {
            var mergeSubCategory = this.checkBox1.concat(this.checkBox2);
            this.mergeSubCategory2 = mergeSubCategory.concat(this.checkBox3);
            var body = {
                extensions: this.checkBox4,
                category: this.checkBox,
                subcategory: this.mergeSubCategory2,
                dataset_name: "",
                sort_by: "created_date",
                startyear: this.value[0],
                endyear: this.value[1]
            };
            var response = await axios.post("api/dataset/filter", body);
            this.project = response.data;
            console.log(response.data);
        },
        async checkBox3() {
            var mergeSubCategory = this.checkBox1.concat(this.checkBox2);
            this.mergeSubCategory2 = mergeSubCategory.concat(this.checkBox3);
            var body = {
                extensions: this.checkBox4,
                category: this.checkBox,
                subcategory: this.mergeSubCategory2,
                dataset_name: "",
                sort_by: "created_date",
                startyear: this.value[0],
                endyear: this.value[1]
            };
            var response = await axios.post("api/dataset/filter", body);
            this.project = response.data;
            console.log(response.data);
        },
        async value() {
            var mergeSubCategory = this.checkBox1.concat(this.checkBox2);
            this.mergeSubCategory2 = mergeSubCategory.concat(this.checkBox3);
            var body = {
                extensions: this.checkBox4,
                category: this.checkBox,
                subcategory: this.mergeSubCategory2,
                dataset_name: "",
                sort_by: "created_date",
                startyear: this.value[0],
                endyear: this.value[1]
            };
            try {
                var response = await axios.post("api/dataset/filter", body);
                this.project = response.data;
                console.log(response.data);
            } catch (error) {
                alert("Silahkan Tekan Tombol Refresh");
            }
        }
    },
    methods: {

        async rangeValue(year) {
            this.value = year;
            var mergeSubCategory = this.checkBox1.concat(this.checkBox2);
            this.mergeSubCategory2 = mergeSubCategory.concat(this.checkBox3);
            var body = {
                extensions: this.checkBox4,
                category: this.checkBox,
                subcategory: this.mergeSubCategory2,
                dataset_name: "",
                sort_by: "created_date",
                startyear: this.value[0],
                endyear: this.value[1]
            };
            try {
                var response = await axios.post("api/dataset/filter", body);
                this.project = response.data;
                console.log(response.data);
            } catch (error) {
                alert("Silahkan Tekan Tombol Refresh");
            }
        },
        refresh() {
            location.reload();
        },
        /* getDataset (filter_value) {

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
                    this.project = response.data;

                })
                .catch(err => {
                    if (err.response) {
                        // client received an error response (5xx, 4xx)

                    } else if (err.request) {
                        // client never received a response, or request never left

                    } else {
                        // anything else

                    }
                })
        }, */
        async getDataset(filterValue) {
            if (this.selected != "") {
                console.log(filterValue);
                var mergeSubCategory = this.checkBox1.concat(this.checkBox2);
                this.mergeSubCategory2 = mergeSubCategory.concat(
                    this.checkBox3
                );
                var body = {
                    extensions: this.checkBox4,
                    category: this.checkBox,
                    subcategory: this.mergeSubCategory2,
                    dataset_name: this.search_keyword,
                    sort_by: filterValue,
                    startyear: this.value[0],
                    endyear: this.value[1]
                };
                try {
                    var response = await axios.post("api/dataset/filter", body);
                    this.project = response.data;
                    console.log(response.data);
                } catch (error) {
                    alert(error);
                }
            } else {
                var mergeSubCategory = this.checkBox1.concat(this.checkBox2);
                this.mergeSubCategory2 = mergeSubCategory.concat(
                    this.checkBox3
                );
                var body = {
                    extensions: this.checkBox4,
                    category: this.checkBox,
                    subcategory: this.mergeSubCategory2,
                    dataset_name: this.search_keyword,
                    sort_by: "created_date",
                    startyear: this.value[0],
                    endyear: this.value[1]
                };
                try {
                    var response = await axios.post("api/dataset/filter", body);
                    this.project = response.data;
                    console.log(response.data);
                } catch (error) {
                    alert(error);
                }
            }
        },
        async searchData() {
            if (!this.search_keyword == "" && this.selected == "") {
                var mergeSubCategory = this.checkBox1.concat(this.checkBox2);
                this.mergeSubCategory2 = mergeSubCategory.concat(
                    this.checkBox3
                );
                var body = {
                    extensions: this.checkBox4,
                    category: this.checkBox,
                    subcategory: this.mergeSubCategory2,
                    dataset_name: this.search_keyword,
                    sort_by: "created_date",
                    startyear: this.value[0],
                    endyear: this.value[1]
                };
                console.log("iki this keyword", this.search_keyword);
                try {
                    var response = await axios.post("api/dataset/filter", body);
                    this.project = response.data;
                    console.log(response.data);
                } catch (error) {
                    console.log(error);
                }
            } else if (!this.search_keyword == "" && this.selected != "") {
                var mergeSubCategory = this.checkBox1.concat(this.checkBox2);
                this.mergeSubCategory2 = mergeSubCategory.concat(
                    this.checkBox3
                );
                var body = {
                    extensions: this.checkBox4,
                    category: this.checkBox,
                    subcategory: this.mergeSubCategory2,
                    dataset_name: this.search_keyword,
                    sort_by: this.selected,
                    startyear: this.value[0],
                    endyear: this.value[1]
                };
                try {
                    var response = await axios.post("api/dataset/filter", body);
                    this.project = response.data;
                    console.log(response.data);
                } catch (error) {
                    console.log(error);
                    console.log("iki error e neng kene1");
                }
            } else if (this.search_keyword == "" && this.selected != "") {
                var mergeSubCategory = this.checkBox1.concat(this.checkBox2);
                this.mergeSubCategory2 = mergeSubCategory.concat(
                    this.checkBox3
                );
                var body = {
                    extensions: this.checkBox4,
                    category: this.checkBox,
                    subcategory: this.mergeSubCategory2,
                    dataset_name: "",
                    sort_by: this.selected,
                    startyear: this.value[0],
                    endyear: this.value[1]
                };
                try {
                    var response = await axios.post("api/dataset/filter", body);
                    this.project = response.data;
                    console.log(response.data);
                    console.log("aku neng kene error");
                } catch (error) {
                    console.log("iki error e neng kene2");
                }
            } else {
                var mergeSubCategory = this.checkBox1.concat(this.checkBox2);
                this.mergeSubCategory2 = mergeSubCategory.concat(
                    this.checkBox3
                );
                var body = {
                    extensions: this.checkBox4,
                    category: this.checkBox,
                    subcategory: this.mergeSubCategory2,
                    dataset_name: "",
                    sort_by: "created_date",
                    startyear: this.value[0],
                    endyear: this.value[1]
                };
                try {
                    var response = await axios.post("api/dataset/filter", body);
                    this.project = response.data;
                    console.log(response.data);
                } catch (error) {
                    console.log(error);
                    console.log("iki error e neng kene3");
                }
            }
            /* if(!this.search_keyword == '' ) {
            let search = {
                'filter_name': 'search',
                'file_name': this.search_keyword,
            }
            this.selected = ''
            this.getDataset(search)
            console.log(search);
            console.log(this.search_keyword);
          }
          else {
            let search = {
              'filter_name': '',
              'file_name': this.search_keyword,
            }
              console.log(this.search_keyword);
              console.log(search);
            this.selected = ''
            this.getDataset(search)
          } */
        },
        detailed(data) {
            localStorage.removeItem("dataset_info");
            // console.log(data)
            localStorage.setItem("dataset_info", JSON.stringify(data));
            this.$router.push("/detailedpage").catch(() => {});
        },
        downloadfile(data) {
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
                container: "#div-upload-loading",
                text:"please wait a second. Your data is being processed...",
                scale: 0.6
            });

            axios
                .post("/api/downloadzip", JSON.stringify(data))
                .then(response => {
                    // console.log(response);
                    this.$vs.loading.close(
                        "#div-upload-loading > .con-vs-loading"
                    );
                    this.$vs.notify({
                        title: "Succes",
                        text: "file has been downloaded.",
                        color: "success"
                    });
                });
        },

        batuBara() {
            this.coba = "Aku neng check box";
            if (this.checkBox1.length > 0) {
                this.checkBox.push("batubara");
            } else if (this.checkBox1.length < 0) {
                for (var i = 0; i < this.checkBox.length; i++) {
                    if (this.checkBox[i] == "batubara") {
                        this.checkBox.splice(i, 1);
                        i--;
                    }
                }
            }
        },
        Migas() {
            this.coba = "Aku neng check box";
            if (this.checkBox2.length > 0) {
                this.checkBox.push("migas");
            } else if (this.checkBox2.length < 0) {
                for (var i = 0; i < this.checkBox.length; i++) {
                    if (this.checkBox[i] == "migas") {
                        this.checkBox.splice(i, 1);
                        i--;
                    }
                }
            }
        },
        panasBumi() {
            this.coba = "Aku neng check box";
            if (this.checkBox3.length > 0) {
                this.checkBox.push("panasbumi");
            } else if (this.checkBox3.length < 0) {
                for (var i = 0; i < this.checkBox.length; i++) {
                    if (this.checkBox[i] == "panasbumi") {
                        this.checkBox.splice(i, 1);
                        i--;
                    }
                }
            }
        },
        async setYear() {
            var mergeSubCategory = this.checkBox1.concat(this.checkBox2);
            var body = {
                extensions: ["shp"],
                category: this.checkBox,
                subcategory: mergeSubCategory,
                dataset_name: "",
                sort_by: "created_date",
                startyear: this.value[0],
                endyear: this.value[1]
            };
            try {
                var response = await axios.post("api/dataset/filter", body);
                this.project = response.data;
                console.log(this.value);
                console.log(response.data);
            } catch (e) {
                console.log(e);
                console.log("erorr e parah tenan");
            }
        }
    }
};
</script>

<style>
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
    border: solid 2px rgb(255, 111, 0);
    padding: 6px 16px;
}

.button1-download:hover {
    background-color: rgb(255, 111, 0);
    color: white;
}

template .colorHeadTable {
    background-color: darkslategrey;
}

.sidebar {
    top: 0;
    right: 0;
    /* height: 700px; */
    min-height: 70vh;
    max-height: 71vh;
    background: #fff;
    color: black;
    overflow-y: scroll;
    z-index: 50;
}

.checkBoxFont {
    font-size: 14px;
}
.tableFixHead {
    overflow-y: auto;
    overflow-x: hidden;
    height: 46vh;
}
.tableFixHead thead th {
    position: sticky;
    top: 0;
    z-index: 1;
    font-size: 16px;
}
table {
    border-collapse: collapse;
    width: 100%;
}
th,
td {
    padding: 8px 16px;
}
th {
    background: #fff;
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
.button-refresh {
    font-size: 12px !important;
    text-align: center;
    cursor: pointer;
}

.sliderYear {
    max-width: 95% !important;
    justify-content: center;
    min-width: 50% !important;
}

@media screen and (min-width: 1500px) {
    .sidebar {
    max-height: 69vh;
    }
    .tableFixHead {
    overflow-x: hidden;
    overflow-y: auto;
    height: 45vh;
}
}

@media screen and (min-width: 1200px){
    .sidebar {
        max-height: 69vh;
}
}

@media screen and (max-width: 1024px) {
    .sidebar {
    max-height: 55vh;
    }
    .tableFixHead {
    height: 40vh;
}
}
</style>
