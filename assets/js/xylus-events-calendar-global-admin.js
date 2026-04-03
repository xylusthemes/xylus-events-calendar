(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	jQuery(document).ready(function(){
		jQuery('.xt_datepicker').datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd'
		});
		jQuery(document).on("click", ".eec_datepicker", function(){
		    jQuery(this).datepicker({
				changeMonth: true,
				changeYear: true,
				dateFormat: 'yy-mm-dd',
				showOn:'focus'
			}).focus();
		});

		jQuery(document).on("click", ".vc_ui-panel .eec_datepicker input[type='text']", function(){
		    jQuery(this).datepicker({
				changeMonth: true,
				changeYear: true,
				dateFormat: 'yy-mm-dd',
				showOn:'focus'
			}).focus();
		});

		// Recurrence UI Toggles
		jQuery(document).on('change', '#event_recurrence_type', function() {
			var type = jQuery(this).val();
			if (type !== 'none' && type !== '') {
				jQuery('.eec_recurrence_fields').slideDown();
				jQuery('.interval_label').text(get_interval_label(type));
				if (type === 'weekly') {
					jQuery('.weekly_days_row').fadeIn();
				} else {
					jQuery('.weekly_days_row').fadeOut();
				}
			} else {
				jQuery('.eec_recurrence_fields').slideUp();
			}
		});

		// Day Picker Interaction
		jQuery(document).on('click', '.eec-day-button', function() {
			var $btn = jQuery(this);
			var day = $btn.data('day');
			var $checkbox = $btn.next('input[type="checkbox"]');
			
			$btn.toggleClass('active');
			$checkbox.prop('checked', $btn.hasClass('active')).trigger('change');
		});

		// End Type Toggles
		jQuery(document).on('change', 'input[name="event_recurrence_end_type"]', function() {
			var endType = jQuery(this).val();
			jQuery('.date-input-wrap').toggle(endType === 'date');
			jQuery('.count-input-wrap').toggle(endType === 'count');
		});

		function get_interval_label(type) {
			switch(type) {
				case 'daily': return 'day(s)';
				case 'weekly': return 'week(s)';
				case 'monthly': return 'month(s)';
				case 'yearly': return 'year(s)';
				default: return '';
			}
		}
	});

})( jQuery );


