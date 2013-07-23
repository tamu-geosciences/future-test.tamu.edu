// Fix jQuery fx bug for IE 8
if (jQuery.browser.msie && jQuery.browser.version.substring(0, 2) == "8.")
{
	jQuery('<style>.isotope-item{z-index:99999999}</style>').appendTo('head');

	jQuery.fx.step = {
		opacity: function( fx ) {
			jQuery.style(fx.elem, "opacity", fx.now);
		},

		_default: function( fx ) {
			// FIX ===============
			if (isNaN(fx.now))
				fx.now = 'auto';

			if (fx.unit == Infinity)
				fx.unit = '';
			//=====================

			if ( fx.elem.style && fx.elem.style[ fx.prop ] != null ) {
				fx.elem.style[ fx.prop ] = fx.now + fx.unit;
			} else {
				fx.elem[ fx.prop ] = fx.now;
			}
		}
	};
}
