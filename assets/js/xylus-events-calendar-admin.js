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

	jQuery(document).ready(function($){
		$(document).on("click", ".xylusec-btn-copy-shortcode", function() { 
			var trigger = $(this);
			$(".xylusec-btn-copy-shortcode").removeClass("text-success");
			var $tempElement = $("<input>");
			$("body").append($tempElement);
			var copyType = $(this).data("value");
			$tempElement.val(copyType).select();
			document.execCommand("Copy");
			$tempElement.remove();
			$(trigger).addClass("text-success");
			var $this = $(this),
			oldText = $this.text();
			$this.attr("disabled", "disabled");
			$this.text("Copied!");
			setTimeout(function(){
				$this.text("Copy");
				$this.removeAttr("disabled");
			}, 800);
	  
		});

	});

	

})( jQuery );


