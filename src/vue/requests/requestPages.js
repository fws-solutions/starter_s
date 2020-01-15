import axios from 'axios';
import {queryPages} from '../graphql/queryPages';
import {AxiosConfig} from '../helpers/helpers';

export function requestPages(vuexContext) {
	const pagesResConfig = new AxiosConfig();
	pagesResConfig.data.query = queryPages();

	axios(pagesResConfig)
		.then((response) => {
			const pagesResponse = response.data.data.pages.nodes;
			vuexContext.commit('setPages', pagesResponse);
		}).catch(e => {
		throw e;
	});
}
