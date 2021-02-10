const $ = jQuery.noConflict();
const _template = require('lodash.template');

'use strict';
const CF7 = {
	$domBody: $('body'),
	$domForm: $('#wpcf7-admin-form-element'),
	$domTempTab: $('#html-template-tab > a'),
	$domInputForm: $('#wpcf7-form'),
	$domInputEmailAdmin: $('#wpcf7-mail-body'),
	$domInputEmailUser: $('#wpcf7-mail-2-body'),
	$domSelectForm: $('#cf7-form-temp'),
	$domSelectEmailAdmin: $('#cf7-email-admin-temp'),
	$domSelectEmailUser: $('#cf7-email-user-temp'),
	$domPreviewForm: $('#cf7-form-temp-preview'),
	$domPreviewEmailAdmin: $('#cf7-email-admin-temp-preview'),
	$domPreviewEmailUser: $('#cf7-email-user-temp-preview'),
	classDisabled: 'is-disabled',
	classInit: 'fws-cf7-init',
	templateDirs: '/dist/cf7/',
	localized: window.fwsLocalized,

	init: function() {
		if (this.$domForm.length > 0 && this.$domBody.hasClass(this.classInit)) {
			this.disableForms();
			this.addMessage();
			this.loadFormContent(this.$domSelectForm, this.$domPreviewForm, this.$domInputForm);
			this.loadFormContent(this.$domSelectEmailAdmin, this.$domPreviewEmailAdmin, this.$domInputEmailAdmin);
			this.loadFormContent(this.$domSelectEmailUser, this.$domPreviewEmailUser, this.$domInputEmailUser);
			this.bindEvents();
		}
	},

	disableForms: function() {
		this.$domInputForm.addClass(this.classDisabled);
		this.$domInputEmailAdmin.addClass(this.classDisabled);
		this.$domInputEmailUser.addClass(this.classDisabled);
	},

	bindEvents: function() {
		this.$domSelectForm.on('change', this.loadFormContent.bind(this, this.$domSelectForm, this.$domPreviewForm, this.$domInputForm));
		this.$domSelectEmailAdmin.on('change', this.loadFormContent.bind(this, this.$domSelectEmailAdmin, this.$domPreviewEmailAdmin, this.$domInputEmailAdmin));
		this.$domSelectEmailUser.on('change', this.loadFormContent.bind(this, this.$domSelectEmailUser, this.$domPreviewEmailUser, this.$domInputEmailUser));
	},

	addMessage: function() {
		const beforeHTML = `
			<p class="cf7-html-temp-msg">Editing Form template is disabled from the dashboard. Please choose one of the avalible templates from <a class="js-html-temp" href="javascript:;">HTML Template</a> tab.</p>
		`;

		this.$domInputForm.before(beforeHTML);
		this.$domInputEmailAdmin.before(beforeHTML);
		this.$domInputEmailUser.before(beforeHTML);

		$('.js-html-temp').on('click', (e) => {
			e.preventDefault;
			this.$domTempTab.trigger('click');
		});
	},

	loadFormContent: function($select, $preview, $dest) {
		const _this = this;

		$.ajax({
			method: 'GET',
			url: _this.localized.themeRoot + _this.templateDirs + $select.val(),
			success: function(data) {
				const compiledFileContentTemp = _template(data);
				const html = compiledFileContentTemp({
					themeRoot: _this.localized.themeRoot,
					siteUrl: _this.localized.siteUrl
				});

				$dest.val(html);
				$preview.val(html);
			}
		});
	}
};

export default CF7;
