(function( $ ) {
	'use strict';

	const wpps = {
		wpps_target_page: '',
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

		// User selected page and post title(hidden input)
		updateWppsTargetPageTitle: function(event) {
			if (event.target.options.selectedIndex) {
				$(this.wpps_page_title).val(event.target.options[event.target.options.selectedIndex].innerText)
			}
		}
	};

	$(document).ready(function(){
		wpps.init();
	});

})( jQuery, document );
