import Vue from 'vue';
import Vuex from 'vuex';
import axios from 'axios';

Vue.use(Vuex);

export default new Vuex.Store({
	state: {
		title: 'Title of Vue Component',
		count: 0
	},
	mutations: {
		increment(state) {
			state.count++;
		}
	},
	getters: {
		getTitle(state) {
			return state.title;
		},
		getCount(state) {
			return state.count;
		}
	}
});
