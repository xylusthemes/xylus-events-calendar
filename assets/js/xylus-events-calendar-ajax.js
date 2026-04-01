/**
 * AJAX Filtering for Easy Events Calendar.
 * 
 * Handles form submission and pagination asynchronously.
 * 
 * @package Xylus_Events_Calendar
 * @since 1.2.0
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        const $form = $('#eec-filter-ajax-form');
        const $gridContainer = $('#eec-events-grid');
        const $loader = $('.eec-ajax-loader');
        const $resultsInfo = $('.eec-results-info');

        if (!$form.length || !$gridContainer.length) {
            return;
        }

        /**
         * Perform AJAX Request
         */
        function eec_perform_ajax(paged = 1) {
            const formData = $form.serializeArray();
            const data = {
                action: 'eec_ajax_filter',
                nonce: eec_ajax_obj.nonce,
                paged: paged,
                taxonomy: $gridContainer.data('taxonomy') || '',
                term: $gridContainer.data('term') || ''
            };

            // Add form fields to data object
            formData.forEach(field => {
                data[field.name] = field.value;
            });

            // Show loader
            $loader.css('display', 'flex').fadeIn(200);
            $gridContainer.css('opacity', '0.5');

            console.log('EEC AJAX Request:', data); // Debug logging

            $.ajax({
                url: eec_ajax_obj.ajax_url,
                type: 'POST',
                data: data,
                success: function(response) {
                    if (response.success) {
                        $gridContainer.html(response.data.html).css('opacity', '1');
                        
                        // Update results info
                        const found = parseInt(response.data.found_posts);
                        const text = found === 1 ? '1 event found' : found + ' events found';
                        $resultsInfo.text(text);

                        // Scroll to top of grid on mobile if needed
                        if ($(window).width() < 768) {
                            $('html, body').animate({
                                scrollTop: $gridContainer.offset().top - 100
                            }, 500);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('EEC AJAX Error:', error);
                },
                complete: function() {
                    $loader.fadeOut(200);
                }
            });
        }

        // Handle form submission (Search button)
        $form.on('submit', function(e) {
            e.preventDefault();
            eec_perform_ajax(1);
        });

        // Trigger on select change (Real-time filtering)
        $form.on('change', 'select', function() {
            eec_perform_ajax(1);
        });

        // Debounced search input
        let searchTimer;
        $form.on('keyup', 'input[name="eec_search"]', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function() {
                eec_perform_ajax(1);
            }, 500);
        });

        // Handle Pagination Click
        $(document).on('click', '.eec-archive-pagination a', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            const paged = url.match(/paged=(\d+)/) ? url.match(/paged=(\d+)/)[1] : 1;
            
            eec_perform_ajax(paged);
        });

        // Handle Reset Link
        $(document).on('click', '.eec-filter-reset', function(e) {
            if ($form.length) {
                e.preventDefault();
                $form[0].reset();
                
                // Reset layout to grid on full reset
                $('#eec-layout-input').val('grid');
                $('.eec-layout-btn').removeClass('active');
                $('.eec-layout-btn[data-layout="grid"]').addClass('active');

                eec_perform_ajax(1);
                
                window.history.pushState({}, '', window.location.pathname);
            }
        });

        // Handle Layout Toggle
        $(document).on('click', '.eec-layout-btn', function(e) {
            e.preventDefault();
            const $btn = $(this);
            const layout = $btn.data('layout');

            if ($btn.hasClass('active')) {
                return;
            }

            // Update UI
            $('.eec-layout-btn').removeClass('active');
            $btn.addClass('active');

            // Update hidden input
            $('#eec-layout-input').val(layout);

            // Re-run AJAX
            eec_perform_ajax(1);
        });

        /**
         * Custom Dropdown Implementation
         */
        function eec_init_custom_dropdowns() {
            $('.eec-filter-select-wrap').each(function() {
                const $wrap = $(this);
                const $select = $wrap.find('select');
                
                if ($wrap.find('.eec-custom-select-trigger').length) return;

                $wrap.addClass('eec-custom-select-wrap');
                
                // Create Trigger
                const selectedText = $select.find('option:selected').text();
                const $trigger = $('<div class="eec-custom-select-trigger">' + 
                    '<span>' + selectedText + '</span>' +
                    '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="18" height="18">' +
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />' +
                    '</svg></div>');
                
                // Create Options List
                const $optionsList = $('<div class="eec-custom-options"></div>');
                $select.find('option').each(function() {
                    const $opt = $(this);
                    const isSelected = $opt.is(':selected');
                    const $customOpt = $('<div class="eec-custom-option' + (isSelected ? ' selected' : '') + '" data-value="' + $opt.val() + '">' + $opt.text() + '</div>');
                    $optionsList.append($customOpt);
                });

                $wrap.append($trigger).append($optionsList);
            });
        }

        // Run on load
        eec_init_custom_dropdowns();

        // Handle Trigger Click
        $(document).on('click', '.eec-custom-select-trigger', function(e) {
            e.stopPropagation();
            const $wrap = $(this).closest('.eec-custom-select-wrap');
            
            // Close others
            $('.eec-custom-select-wrap').not($wrap).removeClass('open');
            $('.eec-custom-select-trigger').not($(this)).removeClass('active');
            
            $wrap.toggleClass('open');
            $(this).toggleClass('active');
        });

        // Handle Option Click
        $(document).on('click', '.eec-custom-option', function(e) {
            const $opt = $(this);
            const value = $opt.data('value');
            const text = $opt.text();
            const $wrap = $opt.closest('.eec-custom-select-wrap');
            const $select = $wrap.find('select');
            const $trigger = $wrap.find('.eec-custom-select-trigger');

            // Update Native Select
            $select.val(value).trigger('change');

            // Update UI
            $trigger.find('span').text(text);
            $opt.addClass('selected').siblings().removeClass('selected');
            $wrap.removeClass('open');
            $trigger.removeClass('active');
        });

        // Close on outside click
        $(document).on('click', function() {
            $('.eec-custom-select-wrap').removeClass('open');
            $('.eec-custom-select-trigger').removeClass('active');
        });

        // Ensure triggers are updated when form is reset
        $(document).on('click', '.eec-filter-reset', function() {
            setTimeout(function() {
                $('.eec-custom-select-wrap').each(function() {
                    const $wrap = $(this);
                    const $select = $wrap.find('select');
                    const selectedText = $select.find('option:selected').text();
                    $wrap.find('.eec-custom-select-trigger span').text(selectedText);
                    $wrap.find('.eec-custom-option').removeClass('selected');
                    $wrap.find('.eec-custom-option[data-value=""]').addClass('selected');
                });
            }, 10);
        });

    });

})(jQuery);
