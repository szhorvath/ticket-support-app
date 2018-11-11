import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import wait from './plugins/vuewait'

import './plugins'
import './components'

Vue.config.productionTip = false

new Vue({
  router,
  store,
  wait,
  render: h => h(App)
}).$mount('#app')