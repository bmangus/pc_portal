/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */


require('./bootstrap');

import Vue from 'vue';
import VueHtml2Canvas from 'vue-html2canvas';

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

import VueTailwind from 'vue-tailwind';
import { library } from '@fortawesome/fontawesome-svg-core';
import {faFilePdf, faSave, faPaperPlane, faShare, faSpinner, faTimesCircle, faSort, faSortUp, faSortDown} from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library.add(faFilePdf);
library.add(faSave);
library.add(faPaperPlane);
library.add(faShare);
library.add(faSpinner);
library.add(faTimesCircle);
library.add(faSort);
library.add(faSortUp);
library.add(faSortDown);



Vue.use(VueTailwind);
Vue.use(VueHtml2Canvas);

Vue.component('font-awesome-icon', FontAwesomeIcon);
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('navbar', require('./components/Navbar.vue').default);
Vue.component('workflow-table', require('./components/WorkflowTable.vue').default);
Vue.component('workflow-requisition-modal', require('./components/WorflowRequisitionModal').default);
Vue.component('workflow-comment', require('./components/WorkflowComment').default);
Vue.component('workflow-forward-modal', require('./components/WorkflowForwardModal').default);
Vue.component('workflow-reassign-modal', require('./components/WorkflowReassignModal').default);
Vue.component('workflow-rejection-modal', require('./components/WorkflowRejectionModal').default);
Vue.component('workflow-table-2', require('./components/WorkflowTable2').default);
Vue.component('at-workflow-table', require('./components/ATWorkflowTable').default);
Vue.component('at-workflow-requisition-modal', require('./components/ATWorkflowRequisitionModal').default);
Vue.component('at-workflow-forward-modal', require('./components/ATWorkflowForwardModal').default);
Vue.component('at-workflow-rejection-modal', require('./components/ATWorkflowRejectionModal').default);





/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
