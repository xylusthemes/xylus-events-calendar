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
		// Universal datepicker initialization
		function init_all_pickers() {
			jQuery('.xt_datepicker, .eec_datepicker, .xt_datetimepicker, .xt_datetimepicker_new').each(function() {
				if (!jQuery(this).hasClass('hasDatepicker')) {
					jQuery(this).datepicker({
						changeMonth: true,
						changeYear: true,
						dateFormat: 'yy-mm-dd'
					});
				}
			});
		}

		// Run initially
		init_all_pickers();

		// Re-initialize when Gutenberg or other scripts might have re-rendered the meta box
		jQuery(document).on('click focus', 'input.xt_datepicker, input.eec_datepicker, input.xt_datetimepicker, input.xt_datetimepicker_new', function() {
			init_all_pickers();
		});

		// Recurrence UI Toggles - use delegation for Gutenberg compatibility
		jQuery(document).on('change', '#event_recurrence_type', function() {
			var type = jQuery(this).val();
			var $fields = jQuery('.eec_recurrence_fields');
			var $list = jQuery('.eec_custom_recurrence_list');

			if (type !== 'none' && type !== '' && type !== 'custom') {
				$fields.show();
				jQuery('.interval_label').text(get_interval_label(type));
				if (type === 'weekly') {
					jQuery('.weekly_days_row').show();
				} else {
					jQuery('.weekly_days_row').hide();
				}
			} else {
				$fields.hide();
			}

			if (type === 'custom') {
				$list.show();
				
				// Fetch fresh occurrences from the database in case they were updated via Gutenberg auto-save or standard save
				var postId = jQuery('#eec_post_id').val();
				var nonce = jQuery('#eec_ajax_nonce').val();
				
				jQuery('.eec_custom_recurrence_list tbody .eec-instance-row').css('opacity', '0.5'); // visual feedback
				
				jQuery.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'eec_get_custom_occurrences',
						event_id: postId,
						nonce: nonce
					},
					success: function(response) {
						if (response.success) {
							// Remove old rows but keep the template
							jQuery('.eec_custom_recurrence_list tbody .eec-instance-row').remove();
							// Prepend fresh rows
							jQuery('.eec_custom_recurrence_list tbody').prepend(response.data.html);
							init_all_pickers();
						}
					}
				});

				init_all_pickers();
			} else {
				$list.hide();
			}
		});

		// Day Picker Interaction
		jQuery(document).on('click', '.eec-day-button', function(e) {
			e.preventDefault();
			var $btn = jQuery(this);
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

		// Handle Instance Removal (Existing)
		jQuery(document).on('click', '.eec-remove-instance', function(e) {
			e.preventDefault();
			e.stopPropagation();
			var $btn = jQuery(this);
			var $row = $btn.closest('tr');
			var instanceId = $row.data('instance-id');
			var postId = jQuery('#eec_post_id').val();
			var nonce = jQuery('#eec_ajax_nonce').val();
			
			if (confirm('Are you sure you want to delete this occurrence instantly from the database? This cannot be undone.')) {
				$row.css('opacity', '0.5').css('background-color', '#fee');
				
				jQuery.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'eec_delete_occurrence',
						instance_id: instanceId,
						event_id: postId,
						nonce: nonce
					},
					success: function(response) {
						if (response.success) {
							$row.fadeOut(400, function() {
								jQuery(this).remove();
							});
						} else {
							alert(response.data.message || 'Error deleting occurrence.');
							$row.css('opacity', '1').css('background-color', '');
						}
					},
					error: function() {
						alert('Server error occurred while deleting.');
						$row.css('opacity', '1').css('background-color', '');
					}
				});
			}
		});

		// Handle Instance Removal (New)
		jQuery(document).on('click', '.eec-remove-new-instance', function(e) {
			e.preventDefault();
			e.stopPropagation();
			jQuery(this).closest('tr').fadeOut(300, function() {
				jQuery(this).remove();
			});
		});

		// Add New Occurrence
		jQuery(document).on('click', '#eec_add_new_occurrence', function(e) {
			e.preventDefault();
			e.stopPropagation();
			var $template = jQuery('.eec-instance-row-template');
			var $newRow = $template.clone().removeClass('eec-instance-row-template').addClass('eec-new-instance-row').show();
			
			jQuery('.eec_custom_recurrence_list tbody').append($newRow);
			init_all_pickers();
		});

		// Handle Instance Addition (Instant Save)
		jQuery(document).on('click', '.eec-save-new-instance', function(e) {
			e.preventDefault();
			e.stopPropagation();
			var $btn = jQuery(this);
			var $row = $btn.closest('tr');
			var $startInput = $row.find('input[name="eec_new_instance_start[]"]');
			var $endInput = $row.find('input[name="eec_new_instance_end[]"]');
			var postId = jQuery('#eec_post_id').val();
			var nonce = jQuery('#eec_ajax_nonce').val();
			
			if (!$startInput.val()) {
				alert('Please select a start date and time.');
				return;
			}
			
			$btn.prop('disabled', true).text('Saving...');
			
			jQuery.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'eec_add_occurrence',
					event_id: postId,
					start_date: $startInput.val(),
					end_date: $endInput.val(),
					nonce: nonce
				},
				success: function(response) {
					if (response.success) {
						var instanceId = response.data.instance_id;
						$row.attr('data-instance-id', instanceId);
						$row.removeClass('eec-new-instance-row').addClass('eec-instance-row');
						
						// Update inputs to match permanent row format
						$startInput.attr('name', 'eec_instance_start[' + instanceId + ']');
						$endInput.attr('name', 'eec_instance_end[' + instanceId + ']');
						
						// Replace buttons with a single delete button (matching existing row)
						var deleteHtml = '<button type="button" class="components-button editor-post-trash is-next-40px-default-size is-secondary is-destructive eec-remove-instance" title="Delete">Delete</button>';
						deleteHtml += '<input type="hidden" name="eec_instance_delete[' + instanceId + ']" class="eec-delete-flag" value="0" />';
						
						$row.find('td:last').html(deleteHtml);
						$row.css('background-color', '#f0fff0'); // Light green for success
						setTimeout(function() { $row.css('background-color', ''); }, 1000);
					} else {
						alert(response.data.message || 'Error saving occurrence.');
						$btn.prop('disabled', false).text('Save');
					}
				},
				error: function() {
					alert('Server error occurred while saving.');
					$btn.prop('disabled', false).text('Save');
				}
			});
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
