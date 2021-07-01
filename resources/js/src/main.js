/*=========================================================================================
  File Name: main.js
  Description: main vue(js) file
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/


import Vue from 'vue'
import App from './App.vue'
import '@/firebase/firebaseConfig'
//ckEditor
const CKEditor = require('../../../node_modules/ckeditor4-vue/dist/ckeditor.js');
Vue.use(CKEditor);
// Vuesax Component Framework
import Vuesax from 'vuesax'
Vue.use(Vuesax)

// Bootstrap Vue
import { BootstrapVue, BootstrapVueIcons} from "bootstrap-vue";
Vue.use(BootstrapVue)
Vue.use(BootstrapVueIcons)

//scroll stop
const VueScrollStop = require("vue-scroll-stop/dist/vue-scroll-stop")
Vue.use(VueScrollStop)

// axios
import axios from './axios.js'
Vue.prototype.$http = axios
axios.defaults.baseURL = process.env.APP_URL + ':' + process.env.APP_PORT;

// Theme Configurations
import '../themeConfig.js'


// Globally Registered Components
import './globalComponents.js'

// Vue Router
import router from './router'

// Vuex Store
import store from './store/store'

// Leaflet
import 'leaflet/dist/leaflet.css';

// Vuejs - Vue wrapper for hammerjs
import {
    VueHammer
} from 'vue2-hammer'
Vue.use(VueHammer)

// PrismJS
import 'prismjs'
import 'prismjs/themes/prism-tomorrow.css'

import 'nprogress/nprogress.css'
/* bootraps modal */
import {
    ModalPlugin
} from 'bootstrap-vue'
Vue.use(ModalPlugin)

Vue.config.productionTip = true

Vue.mixin({
    data: function () {
        return {
            get URINode() {
                return "http://localhost:3000/";
            }
        }
    }
})

new Vue({

    router,
    store,
    render: h => h(App)
}).$mount('#app')
