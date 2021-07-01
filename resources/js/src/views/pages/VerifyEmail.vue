<template>

  <div class="" id="page-login">
  Plesae wait a second, your account will be review
  </div>
        
</template>

<script>
import firebase from 'firebase/app';
import ProfileDropDownFirebase from '@/layouts/components/navbar/components/ProfileDropDownFirebase.vue';
// vee validate
import { ValidationProvider, ValidationObserver, extend } from 'vee-validate';
const axios = require('axios');

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
    }
    console.log('ssss');
  },
  computed: {
  },
  created() {
    //    let interval = setInterval(
    //                 function(){
    //                 console.log('2: Inside of interval')
    //                 }
    //             , 6000);

                
        const payload = {
          email: this.$route.query.email,
          verification_code: this.$route.query.verification_code,
        };
        console.log(payload);
        axios.post('/api/verify_email',payload).then(response => {
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

             if(response.data.code==200){
              this.$router.push('/pages/login');
            }      
            
        })
  },
  methods: {
  
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