'use strict';
const Login = {
	init: function () {
		let userLogin = document.getElementById('user_login');
		if (userLogin) {
			userLogin.setAttribute('placeholder', 'Username')
		}

		let userPass = document.getElementById('user_pass');
		if (userPass) {
			userPass.setAttribute('placeholder', 'Password');
		}
	}
};

export default Login;
