<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://gabrielcastillo.net/
 * @since      1.0.0
 *
 * @package    Wp_Page_Summary
 * @subpackage Wp_Page_Summary/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Page_Summary
 * @subpackage Wp_Page_Summary/admin
 * @author     Gabriel Castillo <gabriel@gabrielcastillo.net>
 */
class Wp_Page_Summary_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-page-summary-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

        $asset_version = 'v2';

        // Check if is dev server, load dev script
        if ( strpos($_SERVER['HTTP_HOST'], '.test') > 0 ) {
	        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-page-summary-admin.js', array( 'jquery' ), $this->version, false );
        } else {
	        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . "js/${asset_version}/all.min.js", array( 'jquery' ), $this->version, false );
        }

        wp_localize_script($this->plugin_name, 'wpps_ajax_request', array(
            'wpps_ajax_url' => admin_url( 'admin-ajax.php' ),
            'wpps_nonce' => wp_create_nonce('wpps_page_summary_nonce'),
        ));
	}

	/**
     * wpps_register_page_summary_post_type
	 * @return void
	 */
	final public function wpps_register_page_summary_post_type()
	{
		$labels = [
			'name' => _x("Page Summary's", 'wp-page-summary'),
			'singular_name' => _x('Page Summary', 'wp-page-summary'),
			'menu_name' => _x('Page Summary', 'wp-page-summary'),
			'name_admin_bar' => _x("Page Summary's", 'wp-page-summary'),
			'add_new' => _x('Add New', 'wp-page-summary'),
			'add_new_item' => _x('Add New Summary', 'wp-page-summary'),
			'new_item' => _x('New Summary', 'wp-page-summary'),
			'edit_item' => _x('Edit Summary', 'wp-page-summary'),
			'view_item' => _x('View Summary', 'wp-page-summary'),
			'all_items' => _x("All Summary's", 'wp-page-summary'),
			'search_items' => _x("Search Summary's", 'wp-page-summary'),
			'parent_item_colon' => _x('Parent Summary', 'wp-page-summary'),
			'not_found' => _x("No Summary's Found", 'wp-page-summary'),
			'not_found_in_trash' => _x("No Summary's found in trash", 'wp-page-summary'),
			'insert_into_item' => _x("Insert into summary", 'wp-page-summary'),
			'upload_to_this_item' => _x('Upload to this summary', 'wp-page-summary'),
			'filter_items_list' => _x("Filter Summary List", 'wp-page-summary'),
			'items_list_navigation' => _x('Summary list navigation', 'wp-page-summary'),
			'items_list' => _x("Summary's list", 'wp-page-summary'),
		];
		$args = [
			'labels' => $labels,
			'public' => false,
			'publicly_queryable' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'page-summary'),
			'capability_type' => 'post',
			'has_archive' => false,
			'hierarchical' => false,
			'menu_position' => false,
			'supports' => false,
		];

		register_post_type('page_summary', $args);
	}

	/**
     * wpps_init_page_summary_post_type_meta_boxes
	 * @return void
	 */
	final public function wpps_init_page_summary_post_type_meta_boxes()
	{
		add_meta_box('wpps-meta-box-page-select', __('Published Pages', 'wp-page-summary'), array($this, 'wpps_page_summary_meta_boxes'), 'page_summary', 'normal', 'low');
	}

	/**
     * wpps_page_summary_meta_boxes
     *
	 * @param object $post
	 * @param array $meta_box
	 *
	 * @return void
	 */
	final public function wpps_page_summary_meta_boxes( object $post, array $meta_box )
	{
		$pages = get_pages();
        $target_page = get_post_meta($post->ID, 'page_summary_target_page', true);

		?>
		<label>
            <select name="page_summary_target_page">
                <option value=""><?php echo esc_attr(__('Select Page', 'wp-page-summary')); ?></option>
                <?php
                    foreach( $pages as $page ) {
                        $option = '<option value="' . $page->ID . '" ';
                        if ( intval($target_page) === $page->ID) {
                            $option .= 'selected="selected"';
                        }
                        $option .= '>';
                        $option .= $page->post_title;
                        $option .= '</option>';
                        echo $option;
                    }
                ?>
            </select>
        </label>
        <br />
        <br />
        <?php $post_title = (isset($post->post_title)) ? $post->post_title : ''; ?>
        <input type="hidden" name="post_title" value="<?php echo $post_title; ?>" />
		<?php
        $content = get_post_meta($post->ID, 'page_summary_target_summary', true);

        wp_editor($content, 'page_summary_target_summary', array());
	}

	/**
     * wpps_page_summary_content_save_post
     * Save post data from custom post type meta
	 * @param int $post_id
	 *
	 * @return void
	 */
	final public function wpps_page_summary_content_save_post( int $post_id )
	{

        if ( ! is_admin() && current_user_can('administrator') ) {
            return;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        if ( $parent_id = wp_is_post_revision( $post_id ) ) {
            $post_id = $parent_id;
        }

        $fields = [
            'page_summary_target_page',
            'page_summary_target_summary',
        ];

        foreach ( $fields as $field ) {
	        if ( isset( $_POST ) ) {
	            if ( array_key_exists( $field, $_POST) ) {
		            if ( $_POST['page_summary_target_page'] === '' ) {
			            return;
		            }
                    update_post_meta( $post_id, $field, $_POST[$field] );
	            }
            }
        }
	}

	/**
     * wpps_page_summary_register_shortcode_init
     * Central location for all shortcode registers.
	 * @return void
	 */
    final public function wpps_page_summary_register_shortcode_init()
    {
        add_shortcode( 'wpps_page_summary', array($this, 'wpps_page_summary_shortcode_callback') );
    }

	/**
	 * wpps_page_summary_shortcode_callback
	 *
	 * @param array|string $atts
	 * @param string|null $content
	 *
	 * @return mixed|string
	 */
    final public function wpps_page_summary_shortcode_callback( array|string $atts = [], string $content = null ): mixed {
        $pages = $this->wpps_get_page_summary_post_type_pages();
        // Check if posts exists

        if ( ! empty($pages) ) {
	        $content = get_post_meta($pages[0]->ID, 'page_summary_target_summary', true);
            if ( ! empty($content) ) {
	            return nl2br($content);
            }
        }

        return '';
    }

	/**
     * get_page_summary_post_type_pages
	 * @return array
	 */
    private function wpps_get_page_summary_post_type_pages(): array {
	    global $post;
	    $args = array(
		    'post_type' => 'page_summary',
		    'post_status'  => 'publish',
		    'posts_per_page' => 1,
		    'meta_query' => array(
			    array(
				    'key' => 'page_summary_target_page',
				    'value' => $post->ID,
				    'compare' => '=',
			    ),
		    ),
	    );
	    // Get custom post type (page_summary)
	    return get_posts($args);
    }

	/**
     * Check if page has page summary assigned.
     *
	 * @return array
	 */
    public function wpps_get_page_summary_post_type_pages_by_id(): array {
        if ( !wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'wpps_page_summary_nonce') ) {
            wp_send_json_error(['message' => 'failed request']);
        }

        $args = array(
            'post_type' => 'page_summary',
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key'  => 'page_summary_target_page',
                    'value' => sanitize_text_field($_POST['p']),
                    'compare' => '=',
                ),
            ),
        );

        $posts = get_posts($args);

        wp_send_json_success($posts);
    }
}
