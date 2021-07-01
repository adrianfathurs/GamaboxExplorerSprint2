<template>
  <div class="h-screen flex w-full vx-row no-gutter items-center justify-center" id="page-login">
    <div class="vx-col sm:w-1/2 md:w-1/2 lg:w-3/4 xl:w-3/5 sm:m-0 m-4">
      <!-- <vx-card> -->
        <div slot="no-body" class="full-page">

          <div class="vx-row no-gutter justify-center items-center">

            <div class="vx-col sm:w-full md:w-full lg:w-1/2 d-theme-dark-bg">
              <div class="p-8 login-tabs-container">

                <div class="vx-card__title mb-4">
                  <h6 class="mb-4 text-center">Join GamaBox Energy</h6>
                  <h1 class="mb-4 text-center">Create your account</h1>
                </div>

                <div>
                  <ValidationObserver v-slot="{ handleSubmit }">
                    <form @submit.prevent="handleSubmit(registerUser)">
                        <h6 class="mr-2 mt-4">Username</h6>
                        <ValidationProvider name="Display_Name" rules="required|alpha|min:3" v-slot="{ errors }"> 
                            <vs-input
                                icon-no-border
                                icon="icon icon-user"
                                icon-pack="feather"
                                placeholder="Username"
                                v-model="displayName"
                                class="w-full"/>
                            <span>{{ errors[0] }}</span>
                        </ValidationProvider> 

                        <h6 class="mr-2 mt-4">Email address</h6>
                        <ValidationProvider name="E-mail" rules="required" v-slot="{ errors }"> 
                            <vs-input
                                type="email"
                                icon-no-border
                                icon="icon icon-mail"
                                icon-pack="feather"
                                placeholder="Email"
                                v-model="email"
                                class="w-full"/>
                            <span>{{ errors[0] }}</span>
                        </ValidationProvider> 

                        <h6 class="mr-2 mt-4">Password</h6>
                        <ValidationProvider name="Password" rules="required|minmax:6,10" v-slot="{ errors }"> 
                            <vs-input
                                type="password"
                                icon-no-border
                                icon="icon icon-lock"
                                icon-pack="feather"
                                placeholder="Password"
                                v-model="password"
                                ref="password"  
                                class="w-full" />
                            <span>{{ errors[0] }}</span>
                        </ValidationProvider> 
                            
                        <h6 class="mr-2 mt-4">Confirmation Password</h6>   
                        <ValidationProvider name="Confirm_Password" rules="required|password:@Password" v-slot="{ errors }">                          
                            <vs-input
                                type="password"
                                icon-no-border
                                icon="icon icon-lock"
                                icon-pack="feather"
                                placeholder="Confirmation Password"
                                v-model="confirm_password"
                                class="w-full" />
                            <span>{{ errors[0] }}</span>
                        </ValidationProvider> 

                        <div class="flex flex-wrap justify-between my-1"> 
                          <ValidationProvider name="TermsAndCondition" rules="required" v-slot="{ errors }">    
                              <vs-checkbox v-model="isTermsConditionAccepted" class="mt-2 mb-4">I accept the terms & conditions.</vs-checkbox>
                              <span>{{ errors[0] }}</span>
                          </ValidationProvider> 
                        </div>

                        <div class="vx-row no-gutter justify-center items-center">
                          <button class="w-full mt-6 button-submit" type="submit">Register</button>
                        </div>
                    </form>
                  </ValidationObserver>

                  <div class="vx-row no-gutter justify-center items-center">
                    <vs-button class="w-full mt-6" color="rgb(255, 111, 0)" type="filled" to="/pages/login">Sign In</vs-button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
  </div>
</template>

<script>
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
extend('alpha', {
  validate(value) {
    return value.indexOf(' ') <= 0;
  },
  message: 'The element cannot contain white space.'
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
  data () {
    return {
      displayName: '',
      email: '',
      password: '',
      confirm_password: '',
      isTermsConditionAccepted: false
    }
  },
  components: {
    ValidationProvider,
    ValidationObserver
  },
  computed: {
    validateForm () {
      return this.isTermsConditionAccepted === true
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
    registerUser () {
      // If form is not validated or user is already login return
      if (!this.validateForm || !this.checkLogin()) return

      const payload = {
        userDetails: {
          displayName: this.displayName,
          email: this.email,
          password: this.password,
          confirmPassword: this.confirm_password
        },
        notify: this.$vs.notify
      }
      this.$store.dispatch('auth/registerUser', payload)
    }
  }
}
</script>

<style lang="scss">
.button-submit {
  background-color:rgb(255, 111, 0); 
  border-radius:5px; 
  border: none; 
  padding: 5px 0px !important;
  color:white;
}
</style>