export class AxiosConfig {
	constructor() {
		this.method = 'POST';
		this.url = `/graphql`;
		this.data = {};
	}
}

export function getErrorMsg(error) {
	if (error.response) {
		// Request made and server responded
		console.log(error.response.data);
		console.log(error.response.status);
		console.log(error.response.headers);

		return 'There was an issue with received data. Please try again later.';
	} else if (error.request) {
		// The request was made but no response was received
		console.log(error.request);

		return 'There was an issue connecting to the server. Please try again later.';
	} else {
		// Something happened in setting up the request that triggered an Error
		console.log(error);
		return error.message;
	}
}
