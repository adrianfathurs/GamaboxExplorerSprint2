<template>
    <div id="profile-page">
        <!-- PROFILE HEADER -->
        <div class="profile-header">
            <div class="bg-component">
                <div class="row">
                    <div class="col-md-7 col-sm-12">
                        <div
                            class="vx-logo cursor-pointer mx-auto flex items-center mb-lg-8 mb-md-4 mb-sm-0"
                        >
                            <div class="vx-row">
                                <div class="vx-col">
                                    <img
                                        src="/images/Asset 2.png"
                                        style="height: 90%; margin-right: 6px; "
                                        alt=""
                                        class="imgBgSize"
                                    />
                                    <span
                                        class="vx-logo-text text-primary text-logo align-middle fontSize"
                                        style="font-size:3.5vw; margin-top:-10px"
                                        >GamaBox Explorer</span
                                    >
                                </div>
                            </div>
                        </div>
                        <h1 class="color_content wellcome">Welcome to Data Portal</h1>
                        <p class="color_content largetext">
                            Find mine data easily
                        </p>
                        <!-- {{ this.$store.state.AppActiveUser }} -->
                        <div class="form-group">
                            <input
                                id="e_keyword"
                                class="form-control"
                                type="text"
                                v-model="search_value"
                                placeholder="input search keywords..."
                                name="q"
                            />
                        </div>
                        <vs-button
                            class="add_css mt-3"
                            size="medium"
                            color="rgb(0, 123, 255)"
                            type="filled"
                            icon-pack="feather"
                            icon="icon-search"
                            v-on:click="search_dataset"
                            >Search</vs-button
                        >
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="vx-row">
                <!-- Card Migas -->
                <div class="vx-col w-full md:w-3/3 lg:w-1/3 xl:w-1/3 mb-base">
                    <!-- Card Title -->
                    <div
                        id="card_stat_judul"
                        class="migas numClick"
                        v-on:click="select_category('migas', '')"
                    >
                        MIGAS
                    </div>
                    <!-- End Card Title -->

                    <!-- Dataset -->
                    <div id="text_file" style="color:#3662c1 !important;">
                        <!-- Jumlah  -->
                        <div class="justify-center items-center">
                            <div
                                class="w-full numClick"
                                v-on:click="select_category('migas', '')"
                            >
                                {{ totalStat.migas }}
                            </div>
                            <div class="w-full">
                                <h6
                                    class="text_dataset"
                                    style="color:#3662c1 !important;"
                                >
                                    Datasets
                                </h6>
                            </div>
                        </div>
                        <!-- End Jumlah Datasest -->

                        <!-- Collapse -->
                        <div>
                            <div v-b-toggle.collapse1 class="d-flex flex-row-reverse mr-2" >
                                <span class="when-opened h5" style="color:#3662c1 !important;">
                                    <b-icon-chevron-up></b-icon-chevron-up>
                                </span>
                                <span class="when-closed h5" style="color:#3662c1 !important;">
                                    <b-icon-chevron-down></b-icon-chevron-down>
                                </span>
                            </div>
                        <b-collapse visible id="collapse1" class="vs-collapse"
                            :class="visible ? null : 'collapsed'"
                            @click="visible = !visible"
                        >
                            <div >
                                <div class="d-flex justify-content-center">
                                    <div id="isi_text_file" class="dividerSize"></div>
                                </div>
                                <div class="mx-4">
                                    <h6 class="text_file2 mt-3" style="color:#3662c1 !important;" v-for="{subcategory,subcat_count} in dataStat.migas" :key="subcategory">
                                        <div class="flex">
                                            <div class="vx-col w-1/2 numClick" v-on:click=" select_category('migas',subcategory)">
                                                {{ subcategory }}
                                            </div>
                                            <div class="vx-col w-1/2">
                                                <h6 class="text_file3 numClick" v-on:click="select_category('migas',subcategory)" style="color:#3662c1 !important;">
                                                    {{ subcat_count }} datasets
                                                </h6>
                                            </div>
                                        </div>
                                    </h6>
                                </div>
                            </div>
                        </b-collapse>
                        </div>
                        <!-- End Collapse -->
                    </div>
                    <!-- End Dataset -->
                </div>
                <!-- End Card Migas -->

                <!-- Card Batu Bara Asli -->


                <div class="vx-col w-full md:w-3/3 lg:w-1/3 xl:w-1/3 mb-base">
                    <!-- Title Card -->
                    <div
                        id="card_stat_judul"
                        class="batubara numClick"
                        v-on:click="select_category('batubara', '')"
                    >
                        BATU BARA
                    </div>
                    <!-- End Title Card -->

                    <div id="text_file" style="color:#fc7000 !important;">
                        <div class="justify-center items-center">
                            <div
                                class="w-full numClick"
                                v-on:click="select_category('batubara', '')"
                            >
                                {{ totalStat.batubara }}
                            </div>
                            <div class="w-full">
                                <h6
                                    class="text_dataset"
                                    style="color:#fc7000 !important;"
                                >
                                    datasets
                                </h6>
                            </div>
                        </div>
                         <div>
                            <div v-b-toggle.collapse2 class="d-flex flex-row-reverse mr-2" >
                                <span class="when-opened h5" style="color:#fc7000 !important;">
                                    <b-icon-chevron-up></b-icon-chevron-up>
                                </span>
                                <span class="when-closed h5" style="color:#fc7000 !important;">
                                    <b-icon-chevron-down></b-icon-chevron-down>
                                </span>
                            </div>
                        <b-collapse visible id="collapse2" class="vs-collapse"
                            :class="visible ? null : 'collapsed'"
                            @click="visible = !visible"
                        >
                            <div >
                                <div class="d-flex justify-content-center">
                                    <div id="isi_text_file" class="dividerSize"></div>
                                </div>
                                <div class="mx-4">
                                    <h6 class="text_file2 mt-3" style="color:#fc7000 !important;" v-for="{subcategory,subcat_count} in dataStat.batubara" :key="subcategory">
                                        <div class="flex">
                                            <div class="vx-col w-1/2 numClick" v-on:click=" select_category('batubara',subcategory)">
                                                {{ subcategory }}
                                            </div>
                                            <div class="vx-col w-1/2">
                                                <h6 class="text_file3 numClick" v-on:click="select_category('batubara',subcategory)" style="color:#fc7000 !important;">
                                                    {{ subcat_count }} datasets
                                                </h6>
                                            </div>
                                        </div>
                                    </h6>
                                </div>
                            </div>
                        </b-collapse>
                        </div>

<!-- Card Lama Batubara -->
                        <!-- <div>
                            <vs-collapse>
                                <vs-collapse-item
                                    :open="show"
                                    class="text_file2 mt-3"
                                    style="color:#fc7000 !important;"
                                    v-for="{
                                        subcategory,
                                        subcat_count
                                    } in dataStat.batubara"
                                    :key="subcategory"
                                >
                                    <div
                                        class="flex"
                                        id="isi_text_file"
                                        v-on:click="
                                            select_category(
                                                'batubara',
                                                subcategory
                                            )
                                        "
                                    >
                                        <div class="vx-col w-1/2 numClick mt-3">
                                            {{ subcategory }}
                                        </div>
                                        <div class="vx-col w-1/2 mt-3">
                                            <h6
                                                class="text_file3 col numClick"
                                                v-on:click="
                                                    select_category(
                                                        'batubara',
                                                        subcategory
                                                    )
                                                "
                                                style="color:#fc7000 !important;"
                                            >
                                                {{ subcat_count }}
                                                datasets
                                            </h6>
                                        </div>
                                    </div>
                                </vs-collapse-item>
                            </vs-collapse>
                        </div> -->


                    </div>
                </div>
                <!-- End Card Batu Bara -->
                <!-- Card Panas Bumi -->
                <div class="vx-col w-full md:w-3/3 lg:w-1/3 xl:w-1/3 mb-base">
                    <div
                        id="card_stat_judul"
                        class="panasbumi numClick"
                        v-on:click="select_category('panasbumi', '')"
                    >
                        PANAS BUMI
                    </div>
                    <div id="text_file" style="color:#6d89cf !important;">
                        <div class="justify-center items-center">
                            <div
                                class="w-full numClick"
                                v-on:click="select_category('panasbumi', '')"
                            >
                                {{ totalStat.panasbumi }}
                            </div>
                            <div class="w-full">
                                <h6
                                    class="text_dataset"
                                    style="color:#6d89cf !important;"
                                >
                                    datasets
                                </h6>
                            </div>
                        </div>
                        <div>
                            <div v-b-toggle.collapse3 class="d-flex flex-row-reverse mr-2" >
                                <span class="when-opened h5" style="color:#3662c1 !important;">
                                    <b-icon-chevron-up></b-icon-chevron-up>
                                </span>
                                <span class="when-closed h5" style="color:#3662c1 !important;">
                                    <b-icon-chevron-down></b-icon-chevron-down>
                                </span>
                            </div>
                        <b-collapse visible id="collapse3" class="vs-collapse"
                            :class="visible ? null : 'collapsed'"
                            @click="visible = !visible"
                        >
                            <div >
                                <div class="d-flex justify-content-center">
                                    <div id="isi_text_file" class="dividerSize"></div>
                                </div>
                                <div class="mx-4">
                                    <h6 class="text_file2 mt-3" style="color:#3662c1 !important;" v-for="{subcategory,subcat_count} in dataStat.panasbumi" :key="subcategory">
                                        <div class="flex">
                                            <div class="vx-col w-1/2 numClick" v-on:click=" select_category('panasbumi',subcategory)">
                                                {{ subcategory }}
                                            </div>
                                            <div class="vx-col w-1/2">
                                                <h6 class="text_file3 numClick" v-on:click="select_category('panasbumi',subcategory)" style="color:#3662c1 !important;">
                                                    {{ subcat_count }} datasets
                                                </h6>
                                            </div>
                                        </div>
                                    </h6>
                                </div>
                            </div>
                        </b-collapse>
                        </div>
                        <!-- <div>
                            <vs-collapse>
                                <vs-collapse-item>
                                    <div class="detailed_css mr-3" slot="header">Detailed</div>
                                    <div id="isi_text_file">
                                        <h6
                                            class="text_file2 mt-3"
                                            style="color:#6d89cf !important;"
                                            v-for="{
                                                subcategory,
                                                subcat_count
                                            } in dataStat.panasbumi"
                                            :key="subcategory"
                                        >
                                            <div class="flex">
                                                <div
                                                    class="vx-col w-1/2 numClick"
                                                    v-on:click="
                                                        select_category(
                                                            'panasbumi',
                                                            subcategory
                                                        )
                                                    "
                                                >
                                                    {{ subcategory }}
                                                </div>
                                                <div class="vx-col w-1/2">
                                                    <h6
                                                        class="text_file3 col numClick"
                                                        v-on:click="
                                                            select_category(
                                                                'panasbumi',
                                                                subcategory
                                                            )
                                                        "
                                                        style="color:#6d89cf !important;"
                                                    >
                                                        {{ subcat_count }}
                                                        datasets
                                                    </h6>
                                                </div>
                                            </div>
                                        </h6>
                                    </div>
                                </vs-collapse-item>
                            </vs-collapse>
                        </div> -->
                    </div>
                </div>
                <!-- End Card Panas Bumi -->
            </div>
        </div>
    </div>
</template>

<script>
const axios = require("axios");

export default {
    data() {
        return {
            // note: changing this line won't causes changes
            // with hot-reload because the reloaded component
            // preserves its current state and we are modifying
            // its initial state.
            migas_file: 0,
            batubara_file: 0,
            panasbumi_file: 0,
            lengthStat: 0,
            search_value: "",
            dataset_info: {},
            dataStat: {},
            totalStat: {
                migas: 0,
                batubara: 0,
                panasbumi: 0
            },
            value: false,
            show: true,
            visible: true
        };
    },
    components: {},
    methods: {
        getStats() {
            axios.get("/api/stats").then(response => {
                this.dataStat = response.data;
                if (this.dataStat.cat.length > 0) {
                    for (let i of this.dataStat.cat) {
                        // console.log(i)
                        this.totalStat[i["category"]] = i.cat_count;
                    }
                }
                this.value = true;
            });
        },
        search_dataset() {
            localStorage.removeItem("search_dataset");
            // console.log(data)
            localStorage.setItem("search_dataset", this.search_value);
            this.$router.push("/dataset").catch(() => {});
        },
        select_category(category, subcategory) {
            // localStorage.removeItem("select_category");
            if (subcategory == "") {
                // console.log(data)
                let filter_category = category;
                localStorage.setItem("select_category", filter_category);
                this.$router.push("/dataset").catch(() => {});
            } else {
                // console.log(data)
                let filter_category = category + ";" + subcategory;
                localStorage.setItem("select_category", filter_category);
                this.$router.push("/dataset").catch(() => {});
            }
        },
        getDataset() {
            axios.post("/api/perdataset", this.dataset_info).then(response => {
                this.infoDataset = response.data;

                for (let data of this.infoDataset) {
                    this.migas_file = data.migas_file;
                    this.batubara_file = data.batubara_file;
                    this.panasbumi_file = data.panasbumi_file;
                }
            });
        }
    },
    mounted() {
        this.dataset_info = JSON.parse(localStorage.getItem("dataset_info"));
        this.getDataset();
        this.getStats();
    }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h1 {
    color: #42b983;
}
.bg-component {
    padding-top: 70px;
    padding-left: 50px;
    width: -webkit-fill-available;
    height: 440px;
    /* background: url("../assets/cover2.jpeg") 0 0 no-repeat; */
    background: url("../assets/coba.jpg")     0 0 no-repeat;
    margin-bottom: 20px;
    background-size: cover !important;
    background-position: 60% 40% !important;
    background-repeat: no-repeat;
    /* background-size: cover; */
    position: relative;
}

.vx-card .vx-card__header {
    display: flex;
    justify-content: center;
    padding-top: 150px;
}

.vx-card__header {
    justify-content: center;
    padding-top: 150px;
}

[dir] .vx-card .vx-card__header {
    padding: 1.5rem 1.5rem 0;
    justify-content: center;
}

.add_css {
    border-radius: 5px 5px 5px 5px;
}
.color_content {
    color: #ffffff;
}
#text_file {
    font-size: 80px;
    font-weight: bold;
    color: #3662c1;
    text-align: center;
    border-style: hidden solid solid solid;
    /* padding-top: 15px; */
    /* padding-bottom: 15px; */
}
#isi_text_file {
    border-style: solid hidden hidden hidden;
}
.text_file2 {
    text-align: left;
    font-weight: bold;
}
.text_file3 {
    text-align: end;
    font-weight: bold;
}
.file_judul {
    color: white;
    text-align: center;
}
.text_dataset {
    font-weight: bold;
    text-align: center;
}
.detailed_css {
    font-size: 13px;
    text-align: right;
}
.numClick {
    cursor: pointer;
    opacity: 100%;
}
.numClick:hover {
    opacity: 80%;
}
.migas {
    background: #3662c1 !important;
}
.batubara {
    background: #fc7000 !important;
}
.panasbumi {
    background: #6d89cf !important;
}
#card_stat_judul {
    cursor: pointer;
    font-size: 20px;
    text-align: center;
    background: #3662c1;
    padding-top: 10px;
    padding-bottom: 10px;
    font-weight: bold;
    border-radius: 25px 25px 0px 0px;
    color: white;
    text-align: center;
}
.vs-collapse {
    -webkit-transition: all 0.3s ease;
    transition: all 0.3s ease;
    cursor: pointer;
    border-radius: 8px;
    padding: 0px !important;
}

.form-control {
    height: 40px;
}

.text-logo {
    color: rgb(255, 111, 0) !important;
    font-size: 20px;
}

/* dividerSize */
.dividerSize {
    width: 95%;
}

/* Collapse */
.collapsed > .when-opened,
    :not(.collapsed) > .when-closed {
        display: none;
    }

@media screen and (max-width: 1800px) {

    .form-control {
        width: 36vw !important;
    }
}

@media screen and (max-width: 1500px) {
    .imgBgSize {
        /* max-height: 75%; */
        width: 25% !important;
    }
    .fontSize {

    }
}

@media screen and (max-width: 1200px) {

    .bg-component {
    padding-top: 88px;
    padding-left: 25px;
    }

    .imgBgSize {
         /* max-height: 50%; */
        width: 20% !important;
    }
    .fontSize {
        /* font-size: 52px; */
    }
    .form-control {
        width: 70vw !important;
    }
}

@media screen and (max-width: 580px) {
    .bg-component {
    padding-top: 88px;
    padding-left: 25px;
    }

    .wellcome {
        margin-top: 20px;
        font-size: 20px !important;
    }

    .imgBgSize {
        min-width: 22% !important;
    }
    .form-control {
        width: 80vw !important;
    }
}
</style>
