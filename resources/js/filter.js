import Vue from 'vue';
//import moment js
import moment from 'moment';
Vue.filter('dateFormat',(argument)=>{
    return moment(argument).format("MMM Do YYYY");
})
Vue.filter('textShorting',(text,length,suffix)=>{
    return text.substring(0,length)+suffix;
})
