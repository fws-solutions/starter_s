import Vue from 'vue';
import Vuex from 'vuex';
import {requestPages} from '../requests/requestPages';

Vue.use(Vuex);

export default new Vuex.Store({
	state: {
		title: 'Title of Vue Component',
		count: 0,
		loadedPages: null
	},
	mutations: {
		increment(state) {
			state.count++;
		},
		setPages(state, pages) {
			state.loadedPages = pages;
		}
	},
	actions: {
		setPages(vuexContext) {
			if (vuexContext.getters.getPages === null) requestPages(vuexContext);
		}
	},
	getters: {
		getTitle(state) {
			return state.title;
		},
		getCount(state) {
			return state.count;
		},
		getPages(state) {
			return state.loadedPages;
		}
	}
});
