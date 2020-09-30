import Vue from 'vue';
import store from './store';
import WidgetExample from './widgets/WidgetExample.vue';

const createNewVueInstance = function(id, component, store) {
	if (document.getElementById(id)) {
		new Vue({ // eslint-disable-line no-new
			el: `#${id}`,
			render: (createEl) => createEl(component),
			store
		});
	}
};

// create an example block
createNewVueInstance('vue-example-block', WidgetExample, store);
