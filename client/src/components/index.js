import Vue from "vue"
import Loading from "./Loading"
import VButton from "./VButton"
import { HasError, AlertError, AlertSuccess } from "vform"

// Components that are registered globally.
;[Loading, VButton, HasError, AlertError, AlertSuccess].forEach(Component => {
  Vue.component(Component.name, Component)
})
