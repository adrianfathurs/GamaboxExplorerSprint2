<template>

  <div class="" id="page-login">
    <div class="vx-card__title py-8">
      <!-- <h1 class="text-center">Energy Logo</h1> -->
      <router-link tag="div" to="/" class="vx-logo cursor-pointer mx-auto flex items-center" style="margin-left:calc(50% - 100px) !important;">
        <img src="/images/Asset 2.png" style="height:50px; margin-right: 6px;" alt="">
        <div class="vx-row">
          <div class="vx-col w-full" style="height: 21px;">
            <span class="vx-logo-text text-primary text-logo" style="">GamaBox</span>
          </div>        
          <div class="vx-col w-full">
            <span class="vx-logo-text text-primary text-logo" style="">Explorer</span>
          </div>
        </div>
      </router-link>
    </div>
    <div class="vx-card__title py-1 ">
      <h1 class="text-center text_sign" >Sign to Data Portal</h1>
    </div>

    <div class="vx-row items-center justify-center py-4">
      <div class="vx-col w-full sm:w-1/2 lg:w-1/3 ">

        <vx-card noShadow cardBorder>
          <div slot="no-body" class="full-page">

            <div class="vx-row no-gutter justify-center items-center">

              <div class="vx-col sm:w-full md:w-full d-theme-dark-bg">
                <div class="p-8 login-tabs-container">

                  <div id="login_div">
                    
                    <ValidationObserver v-slot="{ handleSubmit }">
                        <form @submit.prevent="handleSubmit(login)">
                          <h6 class="mr-2 mt-4">Email address</h6>
                          <ValidationProvider name="E-mail" rules="required|min:3" v-slot="{ errors }"> 
                              <vs-input
                                  id="email_field"
                                  icon-no-border
                                  icon="icon icon-user"
                                  icon-pack="feather"
                                  placeholder="Email"
                                  type="email"
                                  v-model="email"
                                  class="w-full"/>
                              <span>{{ errors[0] }}</span>
                          </ValidationProvider> 

                          <div class="flex flex-wrap justify-between my-1">
                            <h6 class="mr-2 mt-4">Password</h6>
                            <router-link class="mt-4" to="/pages/forgot-password">Forgot Password?</router-link>
                          </div>
                          <ValidationProvider name="Password" rules="required|minmax:6,10" v-slot="{ errors }"> 
                              <vs-input
                                  id="password_field"
                                  type="password"
                                  icon-no-border
                                  icon="icon icon-lock"
                                  icon-pack="feather"
                                  placeholder="Password"
                                  v-model="password"
                                  class="w-full mt-1" />
                              <span>{{ errors[0] }}</span>
                          </ValidationProvider> 

                          <div class="vx-row no-gutter justify-center items-center">
                            <button class="w-full mt-6 button-submit" type="submit">Sign In</button>
                            <!-- <vs-button class="w-full mt-6" color="rgb(255, 111, 0)" type="filled">Sign In</vs-button> -->
                          </div>
                        </form>
                    </ValidationObserver>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </vx-card>

      </div>

    </div>

    <!-- Bawah -->
    <div class="vx-row items-center justify-center">
      <div class="vx-col w-full md:w-1/2 lg:w-1/3 mb-base">

        <vx-card noShadow cardBorder>
          <div slot="no-body" class="full-page">

            <div class="vx-row no-gutter justify-center items-center">

              <div class="vx-col sm:w-full md:w-full d-theme-dark-bg">
                <div class="p-8 login-tabs-container">

                  <!-- <div class="vx-row no-gutter justify-center items-center"> -->
                  <div class="vx-row no-gutter justify-center no-gutter">
                    <h6 class="mr-2 mt-1">New to Data Portal? </h6>
                    <!-- <vs-button background="none" color="rgb(93, 173, 248)" type="flat">Create an account</vs-button> -->
                    <router-link to="/pages/register">Create an account</router-link>
                  </div>
                  
                  <!-- <vs-divider>OR</vs-divider>

                  <div class="ml-4 social-login-buttons flex flex-wrap justify-center items-center mt-4">
                      <div class="bg-google pt-2 pb-2 px-4 rounded-lg cursor-pointer mr-4" @click="loginWithGoogle">
                        <svg aria-hidden="true" focusable="false" data-prefix="fab" data-icon="google" class="text-white h-4 w-4 svg-inline--fa fa-google fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 488 512"><path fill="currentColor" d="M488 261.8C488 403.3 391.1 504 248 504 110.8 504 0 393.2 0 256S110.8 8 248 8c66.8 0 123 24.5 166.3 64.9l-67.5 64.9C258.5 52.6 94.3 116.6 94.3 256c0 86.5 69.1 156.6 153.7 156.6 98.2 0 135-70.4 140.8-106.9H248v-85.3h236.1c2.3 12.7 3.9 24.9 3.9 41.4z"></path></svg>
                      </div>
                  </div> -->


                </div>
              </div>
            </div>
          </div>
        </vx-card>

      </div>
    </div>


  </div>
        
</template>

<script>
import firebase from 'firebase/app';
import ProfileDropDownFirebase from '@/layouts/components/navbar/components/ProfileDropDownFirebase.vue';
// vee validate
import { ValidationProvider, ValidationObserver, extend } from 'vee-validate';
extend('required', {
  validate(value) {
    return {
      required: true,
      valid: ['', null, undefined].indexOf(value) === -1
    };
  },
  computesRequired: true
});
extend('min', {
  validate(value, args) {
    return value.length >= args.length;
  },
  params: ['length']
});
extend('minmax', {
  validate(value, { min, max }) {
    return value.length >= min && value.length <= max;
  },
  params: ['min', 'max']
});

export default {
  name: "page-login",
  components: {
    ValidationObserver,
    ValidationProvider,
    ProfileDropDownFirebase
  },

  data () {
    return {
      email_id_tampilan: '',
      email: '',
      password: '',
      checkbox_remember_me: false
    }
    console.log('sssss');
  },
  computed: {
  },
  methods: {
    checkLogin () {
      // If user is already logged in notify
      if (this.$store.state.auth.isUserLoggedIn()) {

        // Close animation if passed as payload
        // this.$vs.loading.close()

        this.$vs.notify({
          title: 'Login Attempt',
          text: 'You are already logged in!',
          iconPack: 'feather',
          icon: 'icon-alert-circle',
          color: 'warning'
        })

        return false
      }
      return true
    },
    login () {
      // Loading
      this.$vs.loading()
      const payload = {
        checkbox_remember_me: this.checkbox_remember_me,
        userDetails: {
          email: this.email,
          password: this.password
        },
        notify: this.$vs.notify,
        closeAnimation: this.$vs.loading.close
      }
      this.$store.dispatch('auth/loginAttempt', payload)
    },

  //   login_fire () {
  //     var userEmail = document.getElementById("email_field").value;
  //     var userPass = document.getElementById("password_field").value;
  // console.log('sssxxxsss');

  //     window.alert(userEmail + " " + userPass);
  //     firebase.auth().onAuthStateChanged(function(user) {
  //       if (user) {

  //           document.getElementById("user_div").style.display = "block";
  //           document.getElementById("login_div").style.display = "none";

  //           var user = firebase.auth().currentUser;

  //           if (user != null) {
  //             var email_id = user.email;
  //             this.email_id_tampilan = email_id;
  //             document.getElementById("user_para").innerHTML = "Welcome : " + email_id;

  //           }

  //       } else {

  //           document.getElementById("user_div").style.display = "none";
  //           document.getElementById("login_div").style.display = "block";

  //       }

  //     });

  //     firebase.auth().signInWithEmailAndPassword(userEmail, userPass).catch(function(error) {
  //       // Handle Errors here.
  //       var errorCode = error.code;
  //       var errorMessage = error.message;

  //       window.alert("Error: " + errorMessage);

  //       // ...
  //     });     
  //   },

  //   Google login
  //   loginWithGoogle () {
  //     this.$store.dispatch('auth/loginWithGoogle', { notify: this.$vs.notify })
  //   },
  }
}

</script>


<style lang="scss">
#page-login {
  .social-login-buttons {
    .bg-google { background-color: #4285F4 }
  }
}
.text_sign {
  color: rgb(255, 111, 0);
}
.text-logo {
  color: rgb(255, 111, 0) !important;
  font-size: 18px;
}
.button-submit {
  background-color:rgb(255, 111, 0); 
  border-radius:5px; 
  border: none; 
  padding: 5px 0px !important;
  color:white;
}
</style>