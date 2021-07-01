// NOTE
// Please use your own firebase details below. For more details visit: https://pixinvent.com/demo/vuexy-vuejs-admin-dashboard-template/documentation/development/firebaseIntegration.html


import firebase from 'firebase/app'

// Initialize Firebase
// const config = {
//   apiKey: 'AIzaSyDdRZ8BVsFHxBOwz2Hsw7XsKSmRr0D3AdA',
//   authDomain: 'open-data-f8093.firebaseapp.com',
//   databaseURL: 'https://open-data.firebaseio.com',
//   projectId: 'open-data-f8093',
//   storageBucket: 'open-data.appspot.com',
//   messagingSenderId: '177998399689'
// }

// Initialize Firebase
const config = {
  apiKey: 'AIzaSyDP4TqQ7yOlsyHrpfPAdZHr6LuVxGh2dYs',
  authDomain: 'gamabox-energy.firebaseapp.com',
  databaseURL: 'https://gamabox-energy.firebaseio.com',
  projectId: 'gamabox-energy',
  storageBucket: 'gamabox-energy.appspot.com',
  messagingSenderId: '177998399689'
}

firebase.initializeApp(config)
