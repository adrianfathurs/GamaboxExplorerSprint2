<template>
  <div class="mt-3 the-navbar__user-meta flex items-center" v-if="activeUserInfo.uid">

    <div class="text-right leading-tight hidden sm:block">
      <p class="font-semibold m-0">Hello, {{ activeUserInfo.displayName }}</p>
      <!-- <p class="font-semibold m-0">{{ activeUserInfo.displayName }}</p> -->
      <!-- {{activeUserInfo}} -->
    </div>
  </div>
</template>

<script>
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
    logout () {
      // If JWT login
      if (localStorage.getItem('accessToken')) {
        localStorage.removeItem('accessToken')
        localStorage.removeItem('activeUserInfo')
        // localStorage.clear();
        this.$router.push('/pages/login').catch(() => {})
      }

      // Change role on logout. Same value as initialRole of acj.js
      this.$acl.change('user')
      localStorage.removeItem('activeUserInfo')
      // localStorage.removeItem('activeUserInfo')
      // localStorage.clear();
      // This is just for demo Purpose. If user clicks on logout -> redirect
      // this.$router.reload()
      this.$router.push('/pages/login').catch(() => {})
    }
  }
}
</script>
