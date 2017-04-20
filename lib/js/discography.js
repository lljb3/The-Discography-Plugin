// Shows Plugin Functions
jQuery(document).ready(function($) {
	function musictope() {
		// Isotope
		function resizeBoxes() {
			var h = 0;
			$('div.view').each(function(){
				var b = $(this);
				if (h < b.height()) h = b.height();
			});     
			$('div.view').height(h);
		}   
		function initIsotope() {
			jQuery('.isotope').isotope({
				// Options
				itemSelector: '.isotope-item',
				layoutMode: 'masonry',
			});
		}
		$(window).resize(function() {
			resizeBoxes();  
			initIsotope();
		});
		jQuery(window).load(function() {
			resizeBoxes();
			initIsotope();
			jQuery('#listing').animate({opacity: 1.0}, 200);
		});
		// Filter Items on Button Click
		jQuery('.button-group').on( 'click', 'button', function() {
			var filterValue = jQuery(this).attr('data-filter');
			jQuery('.isotope').isotope({ filter: filterValue });
		});
	}
	musictope();
});