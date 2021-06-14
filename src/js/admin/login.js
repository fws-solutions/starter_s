const $ = jQuery.noConflict();

'use strict';
const Login = {

	classForgetmenot: '.forgetmenot',
	idRememberme: '#rememberme',
	idWpSubmit: '#wp-submit',

	init: function() {
		const userLogin = document.getElementById('user_login');
		if (userLogin) {
			userLogin.setAttribute('placeholder', 'Username');
		}

		const userPass = document.getElementById('user_pass');
		if (userPass) {
			userPass.setAttribute('placeholder', 'Password');
		}

		$('#nav').insertAfter(this.classForgetmenot);
		$('<span class="checkmark"></span>').insertAfter(this.idRememberme);
		$('<span class="btn-icon-login svg-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M8.578 16.594L13.172 12 8.578 7.406 9.984 6l6 6-6 6z"></path></svg></span>').insertAfter(this.idWpSubmit);
		$('.fws-footer-author').insertBefore($('.fws-login-illustration').find('img'));
		$('.login-action-confirm_admin_email').find('.button-large').append('<span class="btn-icon-login svg-icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M8.578 16.594L13.172 12 8.578 7.406 9.984 6l6 6-6 6z"></path></svg></span>');
	}
};

export default Login;
