const $ = jQuery.noConflict();

'use strict';
const AcfSvgField = {
	$domWpFooter: $('#wpfooter'),
	$domSvgField: $('.fws-svg-icon'),
	slIconsWrap: '.js-admin-icons-wrap',
	slIconsClose: '.js-admin-icons-close',
	classActive: 'is-active',
	ajaxAction: 'fws_get_svg_icons',
	localized: window.starter_s_localized,

	init: function() {
		if (this.$domSvgField.length > 0) {
			this.bindEvents();
			this.getIcons();
		}
	},

	bindEvents: function() {
		const _this = this;

		this.$domSvgField.find('.acf-input-prepend').on('click', function() {
			$(_this.slIconsWrap).addClass(_this.classActive);
		});
	},

	closeIcons: function () {
		$(this.slIconsWrap).removeClass(this.classActive);
	},

	getIcons: function() {
		const _this = this;

		$.ajax({
			method: 'GET',
			url: _this.localized.ajaxurl,
			data: {
				action: _this.ajaxAction
			},
			success: function(data) {
				_this.$domWpFooter.append(data);

				$(_this.slIconsClose).on('click', _this.closeIcons.bind(_this));
			}
		})
	}
};

export default AcfSvgField;
