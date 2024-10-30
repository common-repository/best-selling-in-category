<?php
/**
 * @package   Best selling in category
 * @author    Vladislav Musílek
 * @license   GPL-2.0+
 * @link      https://musilda.com
 * @copyright 2022 Musilda.com
 */

class Best_Selling_In_Category_Woo {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0';

	/**
	 *
	 * @since    1.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'best-selling-in-category-woo';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Option
	 *
	 * @since    1.0
	 *
	 * @var      array
	 */
	public $option = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	
	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0
	 */
	private static function single_activate() {

    }

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0
	 */
	private static function single_deactivate() {

	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		$load = load_textdomain( $domain, WP_LANG_DIR . '/musilda/' . $domain . '-' . $locale . '.mo' );

		if( $load === false ){
			load_textdomain( $domain, plugin_dir_path( __FILE__ ) . 'languages/' . $domain . '-' . $locale . '.mo' );
		}

	}	

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0
	 */
	public function enqueue_styles() {
		if ( is_product_category() || is_product_tag() ) {
			wp_enqueue_style(  $this->plugin_slug . '-plugin-style', plugins_url( 'assets/css/public.css', __FILE__ ), array(), '1120' );
		}
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0
	 */
	public function enqueue_scripts() {
		if ( is_product_category() || is_product_tag() ) {
			wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), '1114' );
		}
        
	}

	/**
	 * Get bestselling products by category
	 *
	 * @since    1.0
	 */
	public function get_products_by_category() {
    
		if( !empty( $_GET['s'] ) ){
			return;
		}

		global $wp_query;
	    if( empty( $wp_query->queried_object->term_id ) ){
	        return;
    	}else{
        	$term_id = $wp_query->queried_object->term_id;
		}
		
		$html = '';
		
		$loop_args = array(
			'post_type' => 'product',
			'post_status' => 'publish',
			'posts_per_page' => 10,
			'meta_key' => 'total_sales',
			'orderby' => 'meta_value_num',
			'tax_query' => array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'term_id',
					'terms'    => $term_id,
				),
			),
		);

		$loop = new WP_Query( $loop_args );

		if( !empty( $loop->posts ) ){
			$html .= '<h3 class="best-selling-title" >' . esc_html__( 'Best Selling', $this->plugin_slug ) . '</h3>';
			$html .= '<ul class="bestselling-list">';
			$iterator = 1;
			foreach( $loop->posts as $item ){
				$product = wc_get_product( $item->ID );
				$price_html = $product->get_price_html();

				$html .= '<li class="' . esc_html__( $this->get_item_classes( $iterator ) ) . '">';
				$html .= '<div class="bestselling-list-item-inner">';

					$html .= '<div class="bestselling-list-item-position">' . esc_html__( $iterator ) . '.</div>';
					$html .= '<a href=" ' . get_the_permalink( $item->ID ) . '" title="' . get_the_title( $item->ID ) . '">';
						$html .= get_the_post_thumbnail( $item->ID, 'thumbnail' );
					$html .= '</a>';
					$html .= '<div class="bestselling-list-item-content">';
						$html .= '<h3><a href="'.get_the_permalink( $item->ID ).'" title="'.get_the_title( $item->ID ).'">'.get_the_title( $item->ID ).'</a></h3>';
						$html .= '<p>' . wp_trim_words( $item->post_excerpt, 20 ) . '...</p>';
					$html .= '</div>';
					$html .= '<div class="bestselling-list-item-price">' . wp_kses( $price_html, wp_kses_allowed_html() ) . '</div>';

				$html .= '</div>';
				$html .= '</li>';

				$iterator++;
				
			}
			$html .= '</ul>';
			$html .= '<div class="bestselling-list-more"><span id="bestselling-list-more-span">' . esc_html__( 'More Best Selling', $this->plugin_slug ) . '</span></div>';

		}

		return $html;

	}

	/**
	 * Get item classes
	 *
	 * @since    1.0
	 */
	public function get_item_classes( $iterator ) {

		if( $iterator > 3 ){
			$classes = 'bestselling-list-item hidden-item';
		}else{
			$classes = 'bestselling-list-item';
		}

		return $classes;

	}


}//End class
