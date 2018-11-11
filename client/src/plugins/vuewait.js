import Vue from 'vue'
import VueWait from 'vue-wait'

Vue.use(VueWait)

const wait = new VueWait({
  useVuex: true
})

export default wait
