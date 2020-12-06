
require('./bootstrap');

window.Vue = require('vue');
import VCalendar from 'v-calendar';
Vue.use(VCalendar, {componentPrefix: 'vc'})

Vue.component('calendar-component', require('./components/CalendarComponent.vue').default);
const app = new Vue({
    el: '#app',
});
