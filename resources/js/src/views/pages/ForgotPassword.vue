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
      <h1 class="text-center text_sign" >Create New Password</h1>
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
                      <form @submit.prevent="handleSubmit(resetConfirm)">
                        <h6 class="mr-2 mt-4">Email address</h6>
                        <ValidationProvider name="E-mail" rules="required|min:3" v-slot="{ errors }"> 
                            <vs-input
                                id="email_field"
                                type="email"
                                icon-no-border
                                icon="icon icon-user"
                                icon-pack="feather"
                                placeholder="Email"
                                v-model="email"
                                class="w-full"/>
                            <span>{{ errors[0] }}</span>
                        </ValidationProvider> 

                        <div class="vx-row no-gutter justify-center items-center">
                          <button class="w-full mt-6 button-submit" type="submit">Reset</button>
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
  </div>
</template>

<script>
import firebase from 'firebase/app';
import ProfileDropDownFirebase from '@/layouts/components/navbar/components/ProfileDropDownFirebase.vue';
const axios = require('axios');
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
extend('password', {
  params: ['target'],
  validate(value, { target }) {
    return value === target;
  },
  message: 'Password confirmation does not match'
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
      confirm_password: '',
      checkbox_remember_me: false
    }
  },
  computed: {
    validateForm () {
      return !this.errors.any() && this.email !== '' && this.password !== ''
    }
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
    resetConfirm () {
      this.$vs.dialog({
        color: 'success',
        title: `Notification`,
        text: 'Changed password. Please check your email',
        accept: this.reset
      })
    },
    reset () {
		    const payload = {
          email: this.email
        };
        console.log(payload);
         axios.post('/api/send_forgot_password',payload).then(response => {
           console.log(response);
             if(response.data.code==200){
               this.$vs.notify({
                time:4000,
                title: 'Succes',
                text: response.data.message,
                color: 'success',
                iconPack: 'feather',
                icon:'icon-check'
            })   
             }else{
               this.$vs.notify({
                time:4000,
                title: 'Failed',
                text: response.data.message,
                color: 'danger',
                iconPack: 'feather',
                icon:'icon-x'
            })   
             }
		});
    },

    login_fire () {
      var userEmail = document.getElementById("email_field").value;
      var userPass = document.getElementById("password_field").value;

      // window.alert(userEmail + " " + userPass);

      firebase.auth().onAuthStateChanged(function(user) {
        if (user) {

            document.getElementById("user_div").style.display = "block";
            document.getElementById("login_div").style.display = "none";

            var user = firebase.auth().currentUser;

            if (user != null) {
              var email_id = user.email;
              this.email_id_tampilan = email_id;
              document.getElementById("user_para").innerHTML = "Welcome : " + email_id;

            }

        } else {

            document.getElementById("user_div").style.display = "none";
            document.getElementById("login_div").style.display = "block";

      }

      });

      firebase.auth().signInWithEmailAndPassword(userEmail, userPass).catch(function(error) {
        // Handle Errors here.
        var errorCode = error.code;
        var errorMessage = error.message;

        window.alert("Error: " + errorMessage);

        // ...
      });     
    },

    // Google login
    loginWithGoogle () {
      this.$store.dispatch('auth/loginWithGoogle', { notify: this.$vs.notify })
    },
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