import Vue from 'vue';
import store from './store';
import Main from './components/Main.vue';

new Vue({
	el: '#app',
	render: (createEl) => createEl(Main),
	store
});
