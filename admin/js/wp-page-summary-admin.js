(function( $ ) {
	'use strict';

	const wpps = {
		init() {
			this.cache();
			this.eventListeners();
		},

		cache() {
			this.$admin = $('body.wp-admin');
			this.wpps_target_page = this.$admin.find('select[name="page_summary_target_page"]');
			this.wpps_page_title = this.$admin.find('input[name="post_title"]');
		},

		eventListeners() {
			this.wpps_target_page.on('change', this.updateWppsTargetPageTitle.bind(this));
		},

		checkTargetPage: function( page_id ) {
			$.post(wpps_ajax_request.wpps_ajax_url, {
				nonce: wpps_ajax_request.wpps_nonce,
				action: "check_target_page",
				p: page_id,
			}, function(response){
				if ( response.data.length > 0 ) {
					alert('This page has page summary already assigned. Please choose another page.')
					$('input[name="post_title"]').val('');
					$('select[name="page_summary_target_page"]').prop('selectedIndex', 0);
				}
			});
		},

		// User selected page and post title(hidden input)
		updateWppsTargetPageTitle: function(event) {
			console.clear();
			if (event.target.options.selectedIndex) {
				$(this.wpps_page_title).val(event.target.options[event.target.options.selectedIndex].innerText);
				this.checkTargetPage(event.target.options[event.target.options.selectedIndex].value);
			}
		}
	};

	$(document).ready(function(){
		wpps.init();
	});

})( jQuery, document );
