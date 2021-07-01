<!-- =========================================================================================
  File Name: TheNavbar.vue
  Description: Navbar component
  Component Name: TheNavbar
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: Pixinvent
  Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
<div class="relative">
  <div class="vx-navbar-wrapper navbar-full p-0">
    <vs-navbar class="navbar-custom navbar-skelton" :class="navbarClasses"  :style="navbarStyle" :color="navbarColor">

      <!-- <bookmarks :navbarColor="navbarColor" v-if="windowWidth >= 992" /> -->

      <router-link tag="div" to="/" class="vx-logo cursor-pointer mx-auto flex items-center" style="margin-left:0px !important;">

        <!-- <img src="/images/Asset 2.png" style="height:83px; margin-right: 6px;" alt="">
        <div class="vx-row">
          <div class="vx-col w-full">
            <span class="vx-logo-text text-primary text-logo" style="font-size:45px; margin-top:-10px">GamaBox Explorer</span>
          </div>      -->

            <!-- <div class="vx-col w-full" style="height: 20px;">
            <span class="vx-logo-text text-primary text-logo" style="font-size:20px">Explorer</span>
          </div>       -->
        <!-- </div> -->
        <!-- <logo class="w-10 mr-4 fill-current text-primary" /> -->
      </router-link>

      <!-- <search-bar /> -->

      <!-- <notification-drop-down /> -->

      <profile-drop-down />

      <!-- <profile-drop-down-firebase /> -->

      <login-drop-down />

    </vs-navbar>
  </div>
</div>
</template>

<script>
// import Bookmarks            from './components/Bookmarks.vue'
import SearchBar            from './components/SearchBar.vue'
import NotificationDropDown from './components/NotificationDropDown.vue'
import ProfileDropDown      from './components/ProfileDropDown.vue'
// import ProfileDropDownFirebase      from './components/ProfileDropDownFirebase.vue'
import LoginDropDown      from './components/LoginDropDown.vue'
import Logo                 from '../Logo.vue'

export default {
  name: 'the-navbar-horizontal',
  props: {
    logo: { type: String                                                                                                          },
    navbarType: {
      type: String,
      required: true
    }
  },
  components: {
    Logo,
    // Bookmarks,
    SearchBar,
    // NotificationDropDown,
    // ProfileDropDownFirebase,
    ProfileDropDown,
    LoginDropDown,
  },
  computed: {
    navbarColor () {
      let color = '#fff'
      if (this.navbarType === 'sticky') color = '#f7f7f7'
      else if (this.navbarType === 'static') {
        if (this.scrollY < 50) {
          color = '#f7f7f7'
        }
      }

      if (this.isThemedark === 'dark') {
        if (color === '#fff') {
          color = '#10163a'
        } else {
          color = '#262c49'
        }
      }

      return color
    },
    isThemedark ()          { return this.$store.state.theme                                                                       },
    navbarStyle ()          { return this.navbarType === 'static' ? {transition: 'all .25s ease-in-out'} : {}                      },
    navbarClasses ()        { return this.scrollY > 5 && this.navbarType === 'static' ? null : 'd-theme-dark-light-bg shadow-none' },
    scrollY ()              { return this.$store.state.scrollY                                                                     },
    verticalNavMenuWidth () { return this.$store.state.verticalNavMenuWidth                                                        },
    windowWidth ()          { return this.$store.state.windowWidth                                                                 }
  }
}

</script>

<style lang="scss" scoped>
.text-logo {
  color: rgb(255, 111, 0) !important;
  font-size: 40px;
}
</style>
