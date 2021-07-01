<template>
  <div class="the-navbar__user-meta flex items-center">

    <vs-dropdown vs-custom-content vs-trigger-click class="cursor-pointer">

      <div class="con-img ml-3 mt-3">
        <feather-icon icon="UserIcon" class="cursor-pointer mt-1 sm:mr-6 mr-2" />
      </div>

      <vs-dropdown-menu class="vx-navbar-dropdown">
        <ul style="min-width: 9rem">
          <li v-if="!activeUserInfo.email" class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white" @click="login">
            <feather-icon icon="LogInIcon" svgClasses="w-4 h-4" />
            <span class="ml-2">Login</span>
          </li>
          <li v-if="!activeUserInfo.email" class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white" @click="register">
            <feather-icon icon="UserPlusIcon" svgClasses="w-4 h-4" />
            <span class="ml-2">Sign Up</span>
          </li>
          <li v-if="activeUserInfo.email" class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white" @click="profile_settings">
            <feather-icon icon="SettingsIcon" svgClasses="w-4 h-4" />
            <span class="ml-2">Profile Settings</span>
          </li>
          <li v-if="activeUserInfo.role === 'admin'" class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white" @click="settings_admin">
            <feather-icon icon="UserIcon" svgClasses="w-4 h-4" />
            <span class="ml-2">Admin Settings</span>
          </li>
          <li v-if="activeUserInfo.role === 'superuser'" class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white" @click="settings_supersu">
            <feather-icon icon="UserIcon" svgClasses="w-4 h-4" />
            <span class="ml-2">Superuser Settings</span>
          </li>

          <li v-if="activeUserInfo.email" class="flex py-2 px-4 cursor-pointer hover:bg-primary hover:text-white" @click="logout">
            <feather-icon icon="LogOutIcon" svgClasses="w-4 h-4" />
            <span class="ml-2">Logout</span>
          </li>
        </ul>
      </vs-dropdown-menu>
    </vs-dropdown>
  </div>
</template>

<script>
import firebase from 'firebase/app'
import 'firebase/auth'

export default {
  data () {
    return {

    }
  },
  computed: {
    activeUserInfo () {
       var userInfo= JSON.parse(localStorage.getItem('userInfo'));
      if(!userInfo){
        var userInfo =this.$store.state.AppActiveUser;
      }
      return userInfo;
    }
  },
  methods: {
    login () {
      // This is just for demo Purpose. If user clicks on logout -> redirect
      this.$router.push('/pages/login').catch(() => {})
      // this.$router.reload()
    },
    register () {
      // This is just for demo Purpose. If user clicks on logout -> redirect
      this.$router.push('/pages/register').catch(() => {})
    },
    logout () {

      // this.$vs.loading()

      // If JWT login
      if (localStorage.getItem('accessToken')) {
        localStorage.removeItem('accessToken')
        localStorage.removeItem('activeUserInfo')
        // localStorage.clear();
        this.$router.push('/pages/login').catch(() => {})
      }

      // Change role on logout. Same value as initialRole of acj.js
      // this.$acl.change('admin')
      localStorage.removeItem('userInfo')
      localStorage.removeItem('activeUserInfo')
      // localStorage.clear();

      // This is just for demo Purpose. If user clicks on logout -> redirect
      // this.$router.push('/pages/login').catch(() => {})
      // location.reload();
      // this.$router.push('/pages/login')
      // this.$router.push('/pages/login').catch(() => {})
      this.$router.push('/pages/login', () => this.$router.go(0))
    },
    settings_admin () {
      this.$router.push('/adminpage').catch(() => {})
    },
    settings_supersu () {
      this.$router.push('/superuserpage').catch(() => {})
    },
    profile_settings () {
      this.$router.push('/profile').catch(() => {})
    }
  }
}
</script>
