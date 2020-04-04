import Vue from 'vue';
import store from './store';
import Main from './components/Main.vue';

if (document.getElementById('app')) {
	new Vue({ // eslint-disable-line no-new
		el: '#app',
		render: (createEl) => createEl(Main),
		store
	});
}
