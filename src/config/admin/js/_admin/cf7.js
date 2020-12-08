const $ = jQuery.noConflict();

'use strict';
const CF7 = {
	$domForm: $('#wpcf7-admin-form-element'),
	$domPanel: $('#wpcf7-form'),
	classDisabled: 'is-disabled',

	init: function () {
		if (this.$domForm.length > 0) {
			this.$domPanel.val('pas');
			this.$domPanel.addClass(this.classDisabled);
		}
	}
};

export default CF7;
