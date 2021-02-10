'use strict';
const Login = {
	init: function() {
		const userLogin = document.getElementById('user_login');
		if (userLogin) {
			userLogin.setAttribute('placeholder', 'Username');
		}

		const userPass = document.getElementById('user_pass');
		if (userPass) {
			userPass.setAttribute('placeholder', 'Password');
		}
	}
};

export default Login;
