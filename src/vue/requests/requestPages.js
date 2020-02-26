import axios from 'axios';

export function requestPages(vuexContext) {
	axios.get('/wp-json/wp/v2/pages')
		.then((response) => {
			vuexContext.commit('setPages', response.data);
		})
		.catch(e => {
			throw e;
		});
}
