const $ = jQuery.noConflict();

'use strict';
const CF7 = {
	$domForm: $('#wpcf7-admin-form-element'),
	$domPanel: $('#wpcf7-form'),
	$domSelect: $('#wpcf7-html-temp'),
	$domTempTab: $('#html-template-tab > a'),
	$domTempPreview: $('.js-html-temp-preview'),
	classDisabled: 'is-disabled',
	localized: window.starter_s_localized,

	init: function() {
		if (this.$domForm.length > 0) {
			this.addMessage();
			this.loadFormContent();
			this.bindEvents();
		}
	},

	bindEvents: function() {
		this.$domSelect.on('change', () => {
			this.loadFormContent();
		});
	},

	addMessage: function() {
		const beforeHTML = `
			<p class="cf7-html-temp-msg">Editing Form template is disabled from the dashboard. Please choose one of the avalible templates from <a class="js-html-temp" href="javascript:;">HTML Template</a> tab.</p>
		`;

		this.$domPanel.before(beforeHTML);

		$('.js-html-temp').on('click', (e) => {
			e.preventDefault;
			this.$domTempTab.trigger('click');
		});
	},

	loadFormContent: function() {
		const _this = this;

		$.ajax({
			method: 'GET',
			url: `${_this.localized.themeRoot}/src/forms/${_this.$domSelect.val()}`,
			success: function(data) {
				_this.$domPanel.addClass(_this.classDisabled);
				_this.$domPanel.val(data);
				_this.$domTempPreview.val(data);
			}
		})
	}
};

export default CF7;
