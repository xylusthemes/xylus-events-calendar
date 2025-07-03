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

	jQuery(document).ready(function($) {
		var calendarEl = document.getElementById('xylusec-calendar');
		const defaultView = xylusec_ajax?.xylusec_options?.xylusec_default_view || 'month';
		const weekStart = parseInt(xylusec_ajax?.xylusec_options?.xylusec_week_start ?? 0);
		const fullCalendarViews = { month: 'dayGridMonth', week: 'timeGridWeek', day: 'timeGridDay', list: 'listMonth', };

		if (calendarEl) {
	
			var calendar = new FullCalendar.Calendar(calendarEl, {
				initialView: fullCalendarViews[defaultView] || 'dayGridMonth',
				firstDay: weekStart,
				eventDisplay: 'auto',
				headerToolbar: {
					left: 'prev,next today',
					center: 'title',
					right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
				},
				height: 'auto',
    			contentHeight : 'auto',
				fixedWeekCount: false,
				views: {
					dayGridMonth: { 
						buttonText: 'Month',
						dayMaxEventRows: true,
					},
					timeGridWeek: { 
						buttonText: 'Week',
						allDaySlot: false
					},
					timeGridDay: { 
						buttonText: 'Day',
						allDaySlot: false
					},
					listMonth: { 
						buttonText: 'List',
						noEventsContent: 'No events to display'
					}
				},
				eventTimeFormat: {
					hour: '2-digit',
					minute: '2-digit',
					meridiem: 'short'
				},
				events: function(fetchInfo, successCallback, failureCallback) {
					const startTimestamp = Math.floor(fetchInfo.start.getTime() / 1000);
					const endTimestamp = Math.floor((fetchInfo.end.getTime() - 1) / 1000);
					$.ajax({
						url: xylusec_ajax.ajaxurl,
						data: {
							action: 'xylusec_get_events',
							start: startTimestamp,
							end: endTimestamp,
							nonce: xylusec_ajax.nonce
						},
						type: 'GET',
						success: function(response) {
							successCallback(response);
						},
						error: function(error) {
							failureCallback(error);
						}
					});
				},
				eventDidMount: function(info) {
					const dotEl = info.el.querySelector('.fc-daygrid-event-dot');
					if (dotEl) {
						dotEl.remove();
					}

					if (info.el) {
						info.el.style.backgroundColor = info.event.backgroundColor || '#f0f0f0';
						info.el.style.color = info.event.textColor || '#000';
						info.el.style.border = 'none';
						info.el.style.borderRadius = '6px';
					}

					const tooltip = document.createElement('div');
					const imageUrl = info.event.extendedProps.image ? info.event.extendedProps.image.replace(/^http:\/\//i, 'https://') : '';
					tooltip.className = 'xylusec-event-tooltip';
					tooltip.style.display = 'none'; // Hide by default
					tooltip.innerHTML = `
						${imageUrl && info.event.url ? `<a href="${info.event.url}" target="_blank"><img src="${imageUrl}" alt="${info.event.title}"></a>` : (imageUrl ? `<img src="${imageUrl}" alt="${info.event.title}">` : '')}
						<div class="xylusec-event-date">${info.event.extendedProps.formattedDate || ''}</div>
						${info.event.url ? `<h4 class="tooltip-title-click"><a href="${info.event.url}" target="_blank">${info.event.title}</a></h4>` : `<h4 class="tooltip-title-click">${info.event.title}</h4>`}
						<p>${(info.event.extendedProps.description || 'No additional details provided.').substring(0, 150)}${info.event.extendedProps.description?.length > 150 ? '…' : ''}</p>
					`;
					document.body.appendChild(tooltip);

					const popupOverlay = document.createElement('div');
					popupOverlay.className = 'xylusec-event-popup-overlay';
					popupOverlay.innerHTML = `
						<div class="xylusec-event-popup">
							<span class="close-popup">&times;</span>
							${info.event.url ? `<h2><a href="${info.event.url}" target="_blank">${info.event.title}</a></h2>` : `<h2>${info.event.title}</h2>`}
							${imageUrl && info.event.url ? `<a href="${info.event.url}" target="_blank"><img src="${imageUrl}" alt=""></a>` : (imageUrl ? `<img src="${imageUrl}" alt="">` : '')}
							<p>${info.event.extendedProps.description || 'No additional details provided.'}</p>
						</div>
					`;

					const calendarContainer = document.getElementById('xylusec-calendar');
					if (calendarContainer && calendarContainer.parentNode) {
						calendarContainer.parentNode.insertBefore(popupOverlay, calendarContainer.nextSibling);
					}

					let isTooltipHovered = false;
					let isEventHovered = false;

					const showTooltip = () => {
						const rect = info.el.getBoundingClientRect();
						tooltip.style.top = `${rect.bottom + window.scrollY + 10}px`;
						tooltip.style.left = `${rect.left + rect.width / 2}px`;
						tooltip.style.display = 'block';
						tooltip.style.opacity = '1';
						tooltip.style.visibility = 'visible';
					};

					const hideTooltip = () => {
						if (!isTooltipHovered && !isEventHovered) {
							tooltip.style.display = 'none';
							tooltip.style.opacity = '0';
							tooltip.style.visibility = 'hidden';
						}
					};

					info.el.addEventListener('mouseenter', () => {
						isEventHovered = true;
						showTooltip();
					});
					info.el.addEventListener('mouseleave', () => {
						isEventHovered = false;
						setTimeout(hideTooltip, 100); // small delay
					});
					tooltip.addEventListener('mouseenter', () => {
						isTooltipHovered = true;
					});
					tooltip.addEventListener('mouseleave', () => {
						isTooltipHovered = false;
						setTimeout(hideTooltip, 100); // small delay
					});
				},
				eventClick: function(info) {
					info.jsEvent.preventDefault();
					if (info.event.url) {
						window.open(info.event.url, '_blank');
					}
				}
			});

			if (!fullCalendarViews.hasOwnProperty(defaultView)) {
				setTimeout(() => {
					const customButton = document.querySelector(`.fc-button-${defaultView}`);
					if (customButton) customButton.click();
				}, 100); // slight delay to ensure buttons are rendered
			}
			
			calendar.render();
			
			document.getElementById('xylusec-search-events').addEventListener('click', function () {
				const searchTerm = document.getElementById('xylusec-search').value.toLowerCase();
				const events = document.querySelectorAll('.fc-event');
				events.forEach(event => {
					const eventTitle = event.querySelector('.fc-xylusec-event-title')?.textContent.toLowerCase() || '';
					event.style.display = eventTitle.includes(searchTerm) ? '' : 'none';
				});
			});

			document.getElementById('xylusec-search').addEventListener('keypress', function (e) {
				if (e.key === 'Enter') {
					e.preventDefault();
					document.getElementById('xylusec-search-events').click();
				}
			});
		}

		const buttonBgColor   = xylusec_ajax?.xylusec_options?.xylusec_button_color || '#2c3e50';
		const buttonTextColor = xylusec_ajax?.xylusec_options?.xylusec_text_color || '#FFFFFF';

		setTimeout(() => {
			// 1. FullCalendar default buttons
			document.querySelectorAll('.fc .fc-button').forEach(btn => {
				btn.style.backgroundColor = buttonBgColor;
				btn.style.color = buttonTextColor;
				btn.style.borderColor = buttonBgColor;

				// Update SVG fill color inside the button
				btn.querySelectorAll('svg').forEach(svg => {
				svg.style.fill = buttonTextColor;
				});
			});

			// 2. Custom "Search" button
			const searchBtn = document.querySelector('#xylusec-search-events');
			if (searchBtn) {
				searchBtn.style.backgroundColor = buttonBgColor;
				searchBtn.style.color = buttonTextColor;
				searchBtn.style.border = `1px solid ${buttonBgColor}`;
				searchBtn.style.borderRadius = '5px';

				// If it contains SVG icons, update fill as well
				searchBtn.querySelectorAll('svg').forEach(svg => {
				svg.style.fill = buttonTextColor;
				});
			}

			// 3. Custom View Buttons in .xylusec-custom-buttons-container
			document.querySelectorAll('.xylusec-custom-buttons-container .fc-button').forEach(btn => {
				btn.style.backgroundColor = buttonBgColor;
				btn.style.color = buttonTextColor;
				btn.style.borderColor = buttonBgColor;
				btn.style.border = 'none';

				// Update SVG fill color inside the button
				btn.querySelectorAll('svg').forEach(svg => {
					svg.style.fill = buttonTextColor;
				});
			});

			// 4. :focus box-shadow for consistent styling
			const styleTag = document.createElement('style');
			styleTag.textContent = `
				#xylusec-calendar-container .fc .fc-button-primary:not(:disabled).fc-button-active:focus,
				#xylusec-calendar-container .fc .fc-button-primary:not(:disabled):active:focus,
				#xylusec-calendar-container .xylusec-custom-buttons-container .fc-button:focus {
				box-shadow: 0 0 0 0.2rem ${buttonBgColor}80;
				outline: none;
				}
			`;
			document.head.appendChild(styleTag);

			document.querySelectorAll('.xylusec-load-spinner').forEach(spinner => {
				spinner.style.setProperty('--_g', `no-repeat radial-gradient(farthest-side, ${buttonBgColor}80 90%, #0000)`);
			});
			document.documentElement.style.setProperty('--spinner-color', `${buttonBgColor}80`);

			const activeBgColor = buttonBgColor.replace(/#(\w\w)(\w\w)(\w\w)/, (_, r, g, b) => 
				`#${Math.round(parseInt(r, 16) * 0.7).toString(16).padStart(2, '0')}${Math.round(parseInt(g, 16) * 0.7).toString(16).padStart(2, '0')}${Math.round(parseInt(b, 16) * 0.7).toString(16).padStart(2, '0')}`
			);

			const style = document.createElement('style');
			style.textContent = `
				.fc-button.fc-active, 
				.fc-button.fc-button-active {
					background-color: ${activeBgColor} !important;
					border-color: ${activeBgColor} !important;
				}
			`;
			document.head.appendChild(style);

		}, 100);
	});


	/** grid view js start */
	jQuery(document).ready(function($) {
		
		const calendarWrapper = $('#xylusec-calendar');
		const gridWrapper = $('#xylusec-grid-view-container');
		const rowWrapper = $('#xylusec-row-view-container');
		const staggeredWrapper = $('#xylusec-grid-staggered-view-container');
		
		let rowPage = 1;
		let rowKeyword = '';
		let isLoading = false;

		function fetchEvents(reset = false) {
			if (isLoading) return;
			
			isLoading = true;
			$('#load-more-events').hide();
			$('.xylusec-load-spinner').show();

			$.ajax({
				url: xylusec_ajax.ajaxurl,
				type: 'POST',
				data: {
					action: 'xylusec_load_more_events',
					paged: reset ? 1 : rowPage,
					keyword: rowKeyword,
					nonce: xylusec_ajax.nonce
				},
				success: function(response) {
					if (reset) {
						$('.xylusec-event-grid-container').html(response);
						rowPage = 2;
					} else {
						$('.xylusec-event-grid-container').append(response);
						rowPage++;
					}

					if (!response.trim()) {
						$('#load-more-events').hide();
					} else {
						$('#load-more-events').show();
					}
				},
				complete: function() {
					isLoading = false;
					$('.xylusec-load-spinner').hide();
					$('#load-more-events').prop('disabled', false).show();
				}
			});
		}

		// Load more row events
		$('#load-more-events').on('click', function() {
			calendarWrapper.hide();
			rowWrapper.hide();
			staggeredWrapper.hide();
			gridWrapper.show();
			fetchEvents(false);
		});

		// Search row events
		$('#xylusec-search-events').on('click', function() {
			rowKeyword = $('#xylusec-search').val().trim();
			fetchEvents(true);
		});

		// Show Row View
		$('.fc-button-grid').on('click', function() {
			calendarWrapper.hide();
			rowWrapper.hide();
			staggeredWrapper.hide();
			gridWrapper.show();
			fetchEvents(true);
		});

		// Back to Month View
		$('.fc-button-month').on('click', function() {
			rowWrapper.hide();
			gridWrapper.hide();
			staggeredWrapper.hide();
			calendarWrapper.show();
		});
	});
	/** grid view js end */


	/** row view js start */
	jQuery(document).ready(function($) {
		const calendarWrapper = $('#xylusec-calendar');
		const gridWrapper = $('#xylusec-grid-view-container');
		const rowWrapper = $('#xylusec-row-view-container');
		const staggeredWrapper = $('#xylusec-grid-staggered-view-container');
		
		let rowPage = 1;
		let rowKeyword = '';
		let isLoading = false;

		function fetchRowEvents(reset = false) {
			if (isLoading) return;
			
			isLoading = true;
			$('#load-more-row-events').hide();
			$('.xylusec-load-spinner').show();

			$.ajax({
				url: xylusec_ajax.ajaxurl,
				type: 'POST',
				data: {
					action: 'xylusec_load_more_row_events',
					paged: reset ? 1 : rowPage,
					keyword: rowKeyword,
					nonce: xylusec_ajax.nonce
				},
				success: function(response) {
					if (reset) {
						$('.xylusec-event-row-container').html(response);
						rowPage = 2; // Set to page 2 after reset
					} else {
						$('.xylusec-event-row-container').append(response);
						rowPage++;
					}

					if (!response.trim()) {
						$('#load-more-row-events').hide();
					} else {
						$('#load-more-row-events').show();
					}
				},
				complete: function() {
					isLoading = false;
					$('.xylusec-load-spinner').hide();
					$('#load-more-row-events').prop('disabled', false).show();
				}
			});
		}

		// Load more row events
		$('#load-more-row-events').on('click', function() {
			fetchRowEvents(false);
		});

		// Search row events
		$('#xylusec-search-events').on('click', function() {
			rowKeyword = $('#xylusec-search').val().trim();
			fetchRowEvents(true);
		});

		// Show Row View
		$('.fc-button-row').on('click', function() {
			calendarWrapper.hide();
			gridWrapper.hide();
			staggeredWrapper.hide();
			rowWrapper.show();
			fetchRowEvents(true); // This will reset to page 1
		});

		// Back to Month View
		$('.fc-button-month').on('click', function() {
			rowWrapper.hide();
			staggeredWrapper.hide();
			gridWrapper.hide();
			calendarWrapper.show();
		});
	});
	/** row view js end */

	/** staggered view start */
	jQuery(document).ready(function($) {
		const staggeredWrapper = $('#xylusec-grid-staggered-view-container');
		const calendarWrapper = $('#xylusec-calendar');
		const gridWrapper = $('#xylusec-grid-view-container');
		const rowWrapper = $('#xylusec-row-view-container');

		let staggeredPage = 1;
		let staggeredKeyword = '';
		let isLoadingStaggered = false;

		function fetchStaggeredEvents(reset = false) {
			if (isLoadingStaggered) return;

			isLoadingStaggered = true;
			$('#load-more-grid-staggered-events').hide();
			$('.xylusec-load-spinner').show();

			$.ajax({
				url: xylusec_ajax.ajaxurl,
				type: 'POST',
				data: {
					action: 'xylusec_load_more_staggered_events',
					paged: reset ? 1 : staggeredPage,
					keyword: staggeredKeyword,
					nonce: xylusec_ajax.nonce
				},
				success: function(response) {
					if (reset) {
						$('.xylusec-event-grid-staggered-container').html(response);
						staggeredPage = 2;
					} else {
						$('.xylusec-event-grid-staggered-container').append(response);
						staggeredPage++;
					}

					if (!response.trim()) {
						$('#load-more-grid-staggered-events').hide();
					} else {
						$('#load-more-grid-staggered-events').show();
					}
				},
				complete: function() {
					isLoadingStaggered = false;
					$('.xylusec-load-spinner').hide();
					$('#load-more-grid-staggered-events').prop('disabled', false).show();
				}
			});
		}

		// Load more
		$('#load-more-grid-staggered-events').on('click', function() {
			fetchStaggeredEvents(false);
		});

		// Search
		$('#xylusec-search-events').on('click', function() {
			staggeredKeyword = $('#xylusec-search').val().trim();
			fetchStaggeredEvents(true);
		});

		// Show Staggered View
		$('.fc-button-staggered').on('click', function() {
			calendarWrapper.hide();
			gridWrapper.hide();
			rowWrapper.hide();
			staggeredWrapper.show();
			fetchStaggeredEvents(true);
		});

		// Month View
		$('.fc-button-month').on('click', function() {
			staggeredWrapper.hide();
			rowWrapper.hide();
			gridWrapper.hide();
			calendarWrapper.show();
		});
	});
	/** staggered view end */

	jQuery(document).ready(function($) {
		$(document).on('click', '.fc-timeGridWeek-button', function () {
			setTimeout(() => {
				$('#xylusec-calendar').css({
					display: 'block',
					minHeight: '500px'
				});
				calendar.updateSize(); // Ensures layout recalculates
			}, 100);
		});
	});

	$('.xylusec-c-button').on('click', function() {
        $('.fc-button').removeClass('fc-active');
        $(this).addClass('fc-active');
    });

})( jQuery );


