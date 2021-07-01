<template>
    <div>
        <div class="vx-row">
            <div class="vx-col w-full mb-base">
                <div class="vx-row">
                    <div
                        class="vx-col w-full md:w-3/3 lg:w-5/5 xl:w-1/5 mb-base"
                    >
                        <vx-card
                            title="Filter Chart"
                            title-color="primary"
                            card-background="#ffffff"
                            class="mb-3"
                            :collapse-action="true"
                        >
                            <div class="vx-row">
                                <div
                                    class="vx-col w-full lg:w-3/3 xl:w-3/3 my-1"
                                >
                                    <b-form-group
                                        label="Category :"
                                        label-cols-sm="5"
                                        label-cols-md="4"
                                        label-cols-lg="4"
                                        label-align-sm="left"
                                        label-size="sm"
                                        label-for="select_category"
                                        class="mb-0"
                                    >
                                        <b-form-select
                                            v-model="select_category"
                                            id="select_category"
                                            size="sm"
                                            class="w-65"
                                            :options="dataFilter['category']"
                                        ></b-form-select>
                                    </b-form-group>
                                </div>
                                <div
                                    class="vx-col w-full lg:w-3/3 xl:w-3/3 my-1"
                                >
                                    <b-form-group
                                        label="Subcategory :"
                                        label-cols-sm="5"
                                        label-cols-md="4"
                                        label-cols-lg="4"
                                        label-align-sm="left"
                                        label-size="sm"
                                        label-for="select_subcategory"
                                        class="mb-0"
                                    >
                                        <b-form-select
                                            v-model="select_subcategory"
                                            id="select_subcategory"
                                            size="sm"
                                            class="w-65"
                                            :options="
                                                dataFilter[select_category]
                                            "
                                        ></b-form-select>
                                    </b-form-group>
                                </div>
                                <div class="vx-col w-full my-3">
                                    <div id="submit">
                                        <b-button
                                            type="submit"
                                            variant="primary"
                                            v-on:click="fileFilter"
                                            style="float:right;"
                                            >Filter Select File</b-button
                                        >
                                    </div>
                                </div>
                                <vs-divider />
                                <div
                                    id="div-loading"
                                    class="vx-col w-full my-1"
                                >
                                    <v-select-tree
                                        :data="treeData"
                                        @node-select="nodechecked"
                                        v-model="initSelected"
                                        :radio="true"
                                    />
                                </div>
                            </div>
                        </vx-card>
                        <!-- Filter Picker -->
                        <vx-card
                            title="Filter Picker"
                            card-background="#ffffff"
                            class="mb-3"
                            v-if="chartOption"
                            :collapse-action="true"
                        >
                            <div class="vx-row">
                                <div
                                    class="vx-col w-full lg:w-1/2 xl:w-1/2 my-1"
                                >
                                    <b-form-group
                                        label="X :"
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
                                            <b-form-select
                                                v-model="XType"
                                                size="sm"
                                                :disabled="!XLine"
                                                class="w-35"
                                            >
                                                <template v-slot:first>
                                                    <option value=""
                                                        >-- type --</option
                                                    >
                                                </template>
                                                <option value="number"
                                                    >number</option
                                                >
                                                <option value="date"
                                                    >date</option
                                                >
                                            </b-form-select>
                                        </b-input-group>
                                    </b-form-group>
                                </div>
                                <div
                                    class="vx-col w-full lg:w-1/2 xl:w-1/2 my-1"
                                >
                                    <b-form-group
                                        label="Y :"
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
                                            <b-form-select
                                                v-model="YType"
                                                size="sm"
                                                :disabled="!YLine"
                                                class="w-35"
                                            >
                                                <template v-slot:first>
                                                    <option value=""
                                                        >-- type --</option
                                                    >
                                                </template>
                                                <option value="number"
                                                    >number</option
                                                >
                                                <option value="alphabet"
                                                    >alphabet</option
                                                >
                                            </b-form-select>
                                        </b-input-group>
                                    </b-form-group>
                                </div>
                            </div>
                            <div
                                v-if="XType == 'date'"
                                class="vx-col w-full my-1"
                            >
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
                            <div class="vx-col w-full my-3">
                                <div
                                    id="submit"
                                    class="d-flex align-items-center"
                                >
                                    <b-button
                                        class="ml-auto"
                                        type="submit"
                                        variant="primary"
                                        v-on:click="submit"
                                        >Filter</b-button
                                    >
                                </div>
                            </div>
                        </vx-card>
                        <!-- End Filter Picker -->
                    </div>
                    <div
                        class="vx-col w-full md:w-3/3 lg:w-5/5 xl:w-4/5 mb-base"
                    >
                        <b-card-group deck>
                            <b-card header-tag="header" footer-tag="footer">
                                <template v-slot:header>
                                    <h6
                                        v-if="ImageDisabled === false"
                                        class="mb-0 header-title"
                                    >
                                        <i class="fas fa-file-image"></i>
                                        Image Preview
                                    </h6>

                                    <h6
                                        v-else-if="PdfDisabled === false"
                                        class="mb-0 header-title"
                                    >
                                        <i class="fas fa-file-pdf"></i>
                                        Pdf Preview
                                    </h6>

                                    <h6
                                        v-else-if="TxtDisabled === false"
                                        class="mb-0 header-title"
                                    >
                                        <i class="far fa-file-alt"></i>
                                        TXT Preview
                                    </h6>

                                    <h6
                                        v-else-if="ChartDisabled === false"
                                        class="mb-0 header-title"
                                    >
                                        <i class="fa fa-line-chart"></i>
                                        Chart Preview
                                    </h6>

                                    <h6
                                        v-else-if="LasDisabled === false"
                                        class="mb-0 header-title"
                                    >
                                        <i class="fas fa-file"></i>
                                        Las Preview
                                    </h6>
                                </template>
                                <template v-slot:footer>
                                    <h6 class="mb-0 header-title">
                                        {{ FileTitle }}
                                    </h6>
                                </template>
                                <b-tabs
                                    v-model="tabIndex"
                                    pills
                                    card
                                    vertical
                                    end
                                    lazy
                                >
                                    <b-tab :disabled="ImageDisabled">
                                        <template v-slot:title>
                                            <i class="fas fa-file-image"></i>
                                            Image
                                        </template>

                                        <div
                                            v-if="firstOpen == true"
                                            class="hello"
                                        >
                                            <b-alert
                                                :show="alertShow"
                                                variant="success"
                                            >
                                                <h4 class="alert-heading">
                                                    Warning
                                                </h4>
                                                <p>
                                                    Please select any file on
                                                    the left side
                                                </p>
                                                <hr />
                                                <p class="mb-0">
                                                    Thanks.
                                                </p>
                                            </b-alert>
                                        </div>

                                        <div
                                            v-if="filePath !== ''"
                                            class="hello"
                                        >
                                            <img
                                                :src="'api/img/' + filePath"
                                                alt=""
                                                style="object-fit:cover; height:100%; width:100%;"
                                            />
                                        </div>
                                        <div v-else class="hello"></div>
                                    </b-tab>

                                    <b-tab :disabled="PdfDisabled">
                                        <template v-slot:title>
                                            <i class="fas fa-file-pdf"></i>
                                            Pdf
                                        </template>
                                        <div
                                            v-if="filePath !== ''"
                                            id="pdfContent"
                                            class="hello"
                                        >
                                            <iframe
                                                class="pdfIframe"
                                                :src="'api/pdf/' + filePath"
                                                frameborder="0"
                                            ></iframe>
                                        </div>
                                        <div v-else class="hello"></div>
                                    </b-tab>

                                    <b-tab :disabled="TxtDisabled">
                                        <template v-slot:title>
                                            <i class="far fa-file-alt"></i>
                                            Text
                                        </template>
                                        <div class="hello">
                                            <p v-html="textFile"></p>
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
                                                variant="success"
                                            >
                                                <h4 class="alert-heading">
                                                    Warning
                                                </h4>
                                                <p>
                                                    Please adjust the filter
                                                    above for previewing the
                                                    chart
                                                </p>
                                                <hr />
                                                <p class="mb-0">
                                                    Thanks.
                                                </p>
                                            </b-alert>
                                            <div class="hello" ref="chartdiv" />
                                        </div>
                                    </b-tab>

                                    <b-tab :disabled="LasDisabled">
                                        <template v-slot:title>
                                            <i class="fas fa-file"></i>
                                            Las
                                        </template>
                                        <div
                                            id="div-upload-loading"
                                            class="vs-con-loading__container hello"
                                        >
                                            <!-- <iframe :src="'api/las/'+filePath" name="my-iframe" id="las_file_iframe" class="frameLas" frameborder="0">
                                </iframe> -->
                                            <div
                                                class="container"
                                                style="overflow-y:auto; overflow-x:hidden;"
                                            >
                                                <div class="row">
                                                    <div
                                                        class="col-md-4"
                                                        v-for="(key,
                                                        index) in exportData"
                                                        :key="index"
                                                    >
                                                        <div class="card custom-card mb-3">
                                                            <div class="card-body">
                                                                <h5 class="card-title">
                                                                    {{ index }}
                                                                </h5>
                                                                <p class="card-text">{{ key }}</p>
                                                                <a
                                                                    class="card-link"
                                                                    :href="
                                                                        'https://energy.widyaanalytic.com/las/' +
                                                                            fileName +
                                                                            '/' +
                                                                            index
                                                                    "
                                                                    target="_blank"
                                                                    >View
                                                                    Log</a
                                                                >
                                                                <!-- <a class="card-link" href="/<%= fileName %>/<%= item %>" target="_blank">View Log</a> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
    BFormGroup,
    BFormSelect,
    BInputGroup,
    BTabs,
    BTab
} from "bootstrap-vue";

import * as am4core from "@amcharts/amcharts4/core";
import * as am4charts from "@amcharts/amcharts4/charts";
import am4themes_animated from "@amcharts/amcharts4/themes/animated";

am4core.useTheme(am4themes_animated);

import { VTree, VSelectTree } from "vue-tree-halower";

const axios = require("axios");
export default {
    data() {
        return {
            // --------------
            chartOption: false,
            ImageDisabled: false,
            PdfDisabled: true,
            TxtDisabled: true,
            ChartDisabled: true,
            LasDisabled: true,
            alertShow: true,
            alertTable: true,
            firstOpen: true,
            select_tree: false,
            sizeFile: "",
            path: "",
            filePath: "",
            textFile: "",
            node_url: "",
            exportData: "",
            fileName: "",
            select_subcategory: "",
            select_category: "",
            view_counter: 0,
            download_counter: 0,
            totalFile: 0,
            tabIndex: 0,
            currentTab: 0,
            totalRows: 1,
            currentPage: 1,
            perPage: 5,
            infoModal: {
                id: "info-modal",
                title: "",
                content: ""
            },
            detailedmap: {},
            dataFilter: {
                category: [],
                migas: [],
                batubara: [],
                panasbumi: []
            },
            dataset_info: {},
            infoDataset: [],
            fieldsTable: [],
            initSelected: [],
            treeData: [],
            dataChart: [],
            keyTable: [],
            available_file: [],
            dateListFormat: [
                "YYYY",
                "yyyy/MM/dd HH:mm:ss.SSS XXX",
                "yyyy/MM/dd HH:mm:ss",
                "yyyy/MM/dd HH:mm:ss XXX",
                "yyyyMMddHHmmss",
                "yyy/MM/dd",
                "yyyy-MM-dd",
                "yyyy-MM-dd HH:mm:ss",
                "yyyy-MM-dd HH:mm:ss XXX",
                "yyyyMMdd",
                "MM/dd/yyyy",
                "MM/dd/yyyy HH:mm:ss",
                "MM-dd-yyyy",
                "MM-dd-yyyy HH:mm:ss",
                "MM/dd/yy",
                "MM-dd-yy",
                "dd/MM/yyyy",
                "dd-MM-yyyy",
                "yyyy-MM-dd'T'HH:mm:ss.SSSXXX",
                "yyyy-MM-dd HH:mm:ss.SSS"
            ],
            dateFormat: "",
            XLine: "",
            YLine: "",
            YType: "",
            XType: "",
            FileTitle: ""
        };
    },
    components: {
        BAlert,
        BButton,
        BCard,
        BCardGroup,
        BFormGroup,
        BFormSelect,
        BInputGroup,
        BTabs,
        BTab,
        VTree,
        VSelectTree
    },
    watch: {},
    computed: {
        activeUserInfo() {
            // console.log(this.$store.state.AppActiveUser)
            return this.$store.state.AppActiveUser;
        }
    },
    methods: {
        submit() {
            if (
                this.XType != "" &&
                this.XLine != "" &&
                this.YType != "" &&
                this.YLine != ""
            ) {
                this.alertShow = false;
                if (this.XType == "date" && this.dateFormat != "") {
                    this.chartLine();
                } else if (this.XType == "number") {
                    this.dataChart.sort(this.compare(this.XLine));
                    this.dateFormat = "";
                    this.chartLine();
                }
            }
        },
        compare(key, order = "asc") {
            return function innerSort(a, b) {
                if (!a.hasOwnProperty(key) || !b.hasOwnProperty(key)) {
                    // property doesn't exist on either object
                    return 0;
                }

                const varA =
                    typeof a[key] === "string" ? a[key].toUpperCase() : a[key];
                const varB =
                    typeof b[key] === "string" ? b[key].toUpperCase() : b[key];

                let comparison = 0;
                if (varA > varB) {
                    comparison = 1;
                } else if (varA < varB) {
                    comparison = -1;
                }
                return order === "desc" ? comparison * -1 : comparison;
            };
        },
        async getData(file) {
            //   console.log(file)
            // this.chart.dispose();
            await axios.post("/api/getdata", file).then(response => {
                let dataTable = response.data;
                let checkLength = dataTable.length;
                if (checkLength > 100) {
                    return (this.dataChart = dataTable.slice(0, 50));
                } else {
                    return (this.dataChart = dataTable);
                }
            });
            if (this.dataChart.length == 0) {
                this.keyTable = [];
            } else {
                this.keyTable = Object.keys(this.dataChart[0]);
                this.fieldsTable = [];
                for (let i of this.keyTable) {
                    const dict = {};
                    dict["key"] = i;
                    dict["label"] = i.charAt(0).toUpperCase() + i.slice(1);
                    dict["sortable"] = true;
                    this.fieldsTable.push(dict);
                }
                // Set the initial number of items
                this.totalRows = this.dataChart.length;
                // console.log(this.fieldsTable)
            }
        },
        nodechecked(node, ctx, parent) {
            // console.log(node)
            //   if(node.selected == true){
                if (node.selected == true) {
                let extension = node.extension.toLowerCase();
                this.firstOpen = false;
                this.chartOption = false;
                this.LasDisabled = true;
                this.ImageDisabled = true;
                this.PdfDisabled = true;
                this.TxtDisabled = true;
                this.ChartDisabled = true;
                this.pdfUrl = "";
                this.filePath = node.path;
                // console.log(node)
                if (
                    extension == "jpg" ||
                    extension == "jpeg" ||
                    extension == "png"
                ) {
                    this.FileTitle = node.title;
                    this.ImageDisabled = false;
                    this.tabIndex = 0;
                } else if (
                    extension == "csv" ||
                    extension == "xls" ||
                    extension == "xlsx"
                ) {
                    this.FileTitle = node.title;
                    this.tabIndex = 3;
                    this.ChartDisabled = false;
                    this.chartOption = true;
                    let dataset = {};
                    dataset["extension"] = extension;
                    dataset["path"] = node.path;
                    this.getData(dataset);
                } else if (extension == "txt") {
                    this.FileTitle = node.title;
                    this.tabIndex = 2;
                    this.TxtDisabled = false;
                    axios.get("/api/txt/" + this.filePath).then(response => {
                        this.textFile = response.data;
                    });
                } else if (extension == "pdf") {
                    this.FileTitle = node.title;
                    this.tabIndex = 1;
                    this.PdfDisabled = false;
                } else if (extension == "las") {
                    var exportData = JSON.parse(node.exportData);
                    // var exportData = "wkwkwk";
                    // var exportData = JSON.parse("{\"headersDesc\":{\"DEPT\":\"none\",\"SP\":\"Spontaneous Potential\",\"ILD\":\"Induction Laterolog, Deep\",\"SN\":\"Short Normal 16\\\"\",\"GR\":\"Gamma Ray\",\"NPHI\":\"Neutron Porosity\",\"RHOB\":\"Bulk Density\",\"DRHO\":\"Density Correction\",\"CALI\":\"Caliper\"},\"fileName\":\"03a061452bbeaec104ceafe011a127a2-1623214803824\",\"id\":\"60c04ad3a234100966fe2fe7\"}");
                    this.exportData = exportData.headersDesc;
                    this.fileName = exportData.fileName;
                    this.FileTitle = node.title;
                    this.tabIndex = 4;
                    this.LasDisabled = false;
                    // console.log(this.exportData)
                    //   $(function(){
                    //       $('#las_file_iframe').ready(function(){
                    //           //your code (will be called once iframe is done loading)
                    //           this.$vs.loading({
                    //               container: '#div-upload-loading',
                    //               scale: 0.6
                    //           })
                    //       });
                    //   });
                    //   $('#las_file_iframe').on('load', function(){
                    //       //your code (will be called once iframe is done loading)]
                    //       this.$vs.loading.close('#div-upload-loading > .con-vs-loading')
                    //   });
                }
                // this.getGeoData();
                // this.getGeoJson();

            } else {
                this.chartOption = false;
                this.ImageDisabled = true;
                this.LasDisabled = false;
                this.PdfDisabled = true;
                this.TxtDisabled = true;
                this.ChartDisabled = true;
                // var exportData = JSON.parse("{\"headersDesc\":{\"DEPT\":\"none\",\"SP\":\"Spontaneous Potential\",\"ILD\":\"Induction Laterolog, Deep\",\"SN\":\"Short Normal 16\\\"\",\"GR\":\"Gamma Ray\",\"NPHI\":\"Neutron Porosity\",\"RHOB\":\"Bulk Density\",\"DRHO\":\"Density Correction\",\"CALI\":\"Caliper\"},\"fileName\":\"03a061452bbeaec104ceafe011a127a2-1623214803824\",\"id\":\"60c04ad3a234100966fe2fe7\"}");
                // console.log(this.exportData, 'fftftfufyf')
                var exportData = JSON.parse(node.exportData);
                this.exportData = exportData.headersDesc;
                this.fileName = exportData.fileName ;
                this.FileTitle = node.title;
                this.filePath = "";

                // this.fileFilter()
            }
        },
        chartLine() {
            // // Themes begin
            // am4core.useTheme(am4themes_animated);
            // // Themes end

            // Create chart instance
            var chart = am4core.create(this.$refs.chartdiv, am4charts.XYChart);

            // Add data
            chart.data = this.dataChart;

            if (this.dataChart.length != 0) {
                // Set input format for the dates
                // chart.dateFormatter.inputDateFormat = "yyyy-MM-ddThh:mm:ssZ";
                chart.dateFormatter.inputDateFormat = this.dateFormat;

                // Create axes
                if (this.XType == "number") {
                    var dateAxis = chart.xAxes.push(new am4charts.ValueAxis());
                    dateAxis.dataFields.category = this.XLine;
                } else if (this.XType == "date") {
                    var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
                    // dateAxis.renderer.labels.template.fill = am4core.color("#ffffff");
                    // // Set date label formatting
                    // dateAxis.dateFormats.setKey("second", "dt MMM, HH:mm:ss");
                    // dateAxis.periodChangeDateFormats.setKey("second", "dt MMM, HH:mm:ss");
                    dateAxis.dateFormats.setKey("second", "yyyy");
                    dateAxis.periodChangeDateFormats.setKey("second", "yyyy");
                    dateAxis.baseInterval = {
                        timeUnit: "second",
                        count: 1
                    };
                    // dateAxis.tooltipDateFormat = "HH:mm, dd MMM";
                    dateAxis.tooltipDateFormat = "yyyy";
                }

                if (this.YType == "number") {
                    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                } else if (this.YType == "alphabet") {
                    var valueAxis = chart.yAxes.push(
                        new am4charts.CategoryAxis()
                    );
                }
                // valueAxis.renderer.labels.template.fill = am4core.color("#ffffff");
                valueAxis.dataFields.category = this.YLine;

                let label = valueAxis.renderer.labels.template;
                label.truncate = true;
                label.maxWidth = 120;

                // Create series
                var series = chart.series.push(new am4charts.LineSeries());
                if (this.YType == "number") {
                    series.dataFields.valueY = this.YLine;
                } else if (this.YType == "alphabet") {
                    series.dataFields.categoryY = this.YLine;
                }

                if (this.XType == "number") {
                    series.dataFields.valueX = this.XLine;
                } else if (this.XType == "date") {
                    series.dataFields.dateX = this.XLine;
                }
                series.tooltipText = "{" + this.YLine + "}";
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
        treeMenu(dataValue) {
            this.$vs.loading({
                container: "#div-loading",
                scale: 0.6
            });
            axios
                .post("/api/all", dataValue)
                .then(response => {
                    this.treeData = response.data;
                    this.$vs.loading.close("#div-loading > .con-vs-loading");
                })
                .catch(err => {
                    if (err.response) {
                        // client received an error response (5xx, 4xx)
                        this.$vs.loading.close(
                            "#div-loading > .con-vs-loading"
                        );
                    } else if (err.request) {
                        // client never received a response, or request never left
                        this.$vs.loading.close(
                            "#div-loading > .con-vs-loading"
                        );
                    } else {
                        // anything else
                        this.$vs.loading.close(
                            "#div-loading > .con-vs-loading"
                        );
                    }
                });
        },
        fileFilter() {
            if (this.select_category == "all" || this.select_category == "") {
                this.treeMenu({ category: "all" });
            } else if (
                this.select_subcategory == "all" ||
                this.select_subcategory == ""
            ) {
                this.treeMenu({ category: this.select_category });
            } else {
                this.treeMenu({
                    category: this.select_category,
                    subcategory: this.select_subcategory
                });
            }
        },
        filter() {
            axios
                .post("/api/filter", { filter: "getCategory" })
                .then(response => {
                    this.dataFilter = response.data;
                    this.dataFilter["category"].push(data);
                    this.dataFilter["batubara"].push(data);
                    this.dataFilter["migas"].push(data);
                    this.dataFilter["panasbumi"].push(data);
                });
        }
    },
    created() {},
    mounted() {
        this.node_url = this.URINode;
        this.filter();
        this.treeMenu({ created_by: this.activeUserInfo.displayName });
    },
    beforeDestroy() {
        if (this.chart) {
            this.chart.dispose();
        }
    }
};
</script>

<style lang="scss">
@import "@sass/vuexy/extraComponents/tree.scss";
html {
    scroll-behavior: smooth;
}

.pdfIframe {
    width: 100%;
    height: 100%;
}

.frameLas {
    width: 100%;
    height: 100%;
}

.tree-container {
    width: 100% !important;
    z-index: 9999;
}

.tree-box {
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
    background-color: rgba(var(--vs-danger), 0.15);
    color: rgba(var(--vs-danger), 1);
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
    float: right;
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
