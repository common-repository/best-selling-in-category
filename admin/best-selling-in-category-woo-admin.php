<?php
/**
 * @package   Best selling in category
 * @author    Vladislav Musílek
 * @license   GPL-2.0+
 * @link      https://musilda.com
 * @copyright 2022 Musilda.com
 */
class Best_Selling_In_Category_Woo_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0
	 *
	 * @var      object
	 */
    protected static $instance = null;
	
	
	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0
	 */
	private function __construct() {

		$this->plugin_slug = 'czech-services-for-woocommerce-woo';

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
    
    	// Add an action link pointing to the options page.
        add_filter( 'plugin_row_meta' , array( $this, 'add_action_links' ), 10, 2 );

        // Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

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
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {
	
		if ( !empty( $_GET['page'] ) && 'best-selling-in-category' == $_GET['page'] ) {
    		wp_enqueue_style( $this->plugin_slug .'-admin-styles', BSICURL . 'assets/css/admin.css', array(), Best_Selling_In_Category_Woo::VERSION );
		}
		
	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( !empty( $_GET['page'] ) && 'best-selling-in-category' === $_GET['page'] ) {
			
			wp_enqueue_script( $this->plugin_slug . '-admin-script', BSICURL . 'assets/js/admin.js', array( 'jquery' ),Best_Selling_In_Category_Woo::VERSION, true );
		}
		
	}

	/**
     * Add settings action link to the plugins page.
     *
     * @since    1.0
     */
    public function add_action_links( $meta, $file ) {

        if( $file == 'best-selling-in-category-woo/best-selling-in-category-woo.php' ){
            $meta[] = '<a href="' . admin_url( 'admin.php?page=best-selling-in-category' ) . '">' . esc_html__( 'Settings', $this->plugin_slug ) . '</a>';            
        }

        return $meta;

    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu() {

        add_submenu_page(
            'options-general.php',
            esc_html__( 'Best selling in category', $this->plugin_slug ),
            esc_html__( 'Best selling in category', $this->plugin_slug ),
            'manage_woocommerce',
            'best-selling-in-category',
            array( $this, 'display_admin_page' )
        );

    }

    /**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0
	 */
	public function display_admin_page() {
		include_once( 'view.php' );
	}

	/**
	 * Render checkbox line
	 *
	 * @since 1.0.1  
	 */        
	public function checkbox_line( $label, $name, $option = null ){
		?>
		<div class="admin-box-item type-checkbox">
			<label><?php echo esc_html__( $label ); ?></label>
			<input type="checkbox" name="<?php echo esc_html__( $name ); ?>" value="on" <?php if ( !empty( $option[$name] ) ) { checked( $option[$name], 'on' ); } ?> />
			<?php if ( !empty( $option[$name] ) && 'on' === $option[$name] ) { ?>
				<div class="item-check selected" data-name="<?php echo esc_html__( $name ); ?>"></div>
			<?php } else { ?>
				<div class="item-check" data-name="<?php echo esc_html__( $name ); ?>"></div>
			<?php } ?>
		</div>
		<?php
	}

}