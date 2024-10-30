<?php
/**
 * @package   Best selling in category
 * @author    Vladislav Musílek
 * @license   GPL-2.0+
 * @link      https://musilda.com
 * @copyright 2022 Musilda.com
 *
 * Plugin Name:       Best selling in category
 * Plugin URI:        
 * Description:       Plugin shows 10 best selling products in category
 * Version:           1.3
 * Author:            Musilda
 * Author URI:        musilda.com
 * Text Domain:       best-selling-in-category-woo
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * Requires at least: 5.8
 * Requires PHP: 7.2
 * WC tested up to: 6.8.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'BSICDIR', plugin_dir_path( __FILE__ ) );
define( 'BSICURL', plugin_dir_url( __FILE__ ) );

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

register_activation_hook(   __FILE__, array( 'Best_Selling_In_Category_Woo', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Best_Selling_In_Category_Woo', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Best_Selling_In_Category_Woo', 'get_instance' ) );

require_once( plugin_dir_path( __FILE__ ) . 'class-best-selling-in-category-woo.php' );

if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

    require_once( plugin_dir_path( __FILE__ ) . 'admin/best-selling-in-category-woo-admin.php' );
    add_action( 'plugins_loaded', array( 'Best_Selling_In_Category_Woo_Admin', 'get_instance' ) );

}

/**
 * Shortcode callback
 * @since 1.0
 */
add_shortcode( 'bestselling-category', 'best_selling_callback_bestselling_category' );
function best_selling_callback_bestselling_category( $ags, $content ){

    $plugin = Best_Selling_In_Category_Woo::get_instance();
    return $plugin->get_products_by_category();

}

/**
 * 
 * 
 */
add_action( 'woocommerce_before_shop_loop', 'bestselling_in_category_woo', 5 );
function bestselling_in_category_woo(){

	$option = get_option( 'best_selling_in_category' );
	if ( !empty( $option['enable'] ) && 'on' == $option['enable'] ) {

    	$plugin = Best_Selling_In_Category_Woo::get_instance();
    	echo $plugin->get_products_by_category();

	}

}

/**
 * 
 * 
 */
add_action( 'woocommerce_before_shop_loop', 'best_selling_custom_product_sorting', 40 );
function best_selling_custom_product_sorting(){

	$option = get_option( 'best_selling_in_category' );
	if ( !empty( $option['custom_sorting'] ) && 'on' == $option['custom_sorting'] ) {

		$domain 		= esc_html( $_SERVER['HTTP_HOST'] );
		$path 			= sanitize_text_field( $_SERVER["REQUEST_URI"] );
		$queryString 	= sanitize_text_field( $_SERVER['QUERY_STRING'] );
		
		if( $queryString ){
			$url = "https://" . $domain . $path . "?" . $queryString;
		}else{
			$url = "https://" . $domain . $path;
		}

		$popularity = add_query_arg( array( 'orderby' => 'popularity' ), $url );
		$price      = add_query_arg( array( 'orderby' => 'price' ), $url );
		$pricedesc  = add_query_arg( array( 'orderby' => 'price-desc' ), $url );

		$popularity_class = '';
		$price_class = '';
		$pricedesc_class = '';

		if( !empty( $_GET['orderby'] ) ){
			$top_class = '';

			$order_by = sanitize_text_field( $_GET['orderby'] );

			if( $order_by == 'popularity' ){
				$popularity_class = 'active';
			}elseif( $order_by == 'price' ){
				$price_class = 'active';
			}elseif( $order_by == 'price-desc' ){
				$pricedesc_class = 'active';
			}

		}else{
			$top_class = 'active';
		}

		$baseurl = explode( '?', $url );

		echo '<div class="custom-product-sorting">';
			echo '<a href="' . esc_html__( $baseurl[0] ) . '" class="custom-product-sorting-item ' . esc_html__( $top_class ) . '">' . esc_html__( 'Top', 'best-selling-in-category-woo' ) . '</a>';
			echo '<a href="' . esc_html__( $price ) . '" class="custom-product-sorting-item ' . esc_html__( $price_class ) . '">' . esc_html__( 'Highest price', 'best-selling-in-category-woo' ) . '</a>';
			echo '<a href="' . esc_html__( $pricedesc ) . '" class="custom-product-sorting-item ' . esc_html__( $pricedesc_class ) . '">' . esc_html__( 'Lowest price', 'best-selling-in-category-woo' ) . '</a>';
			echo '<a href="' . esc_html__( $popularity ) . '" class="custom-product-sorting-item ' . esc_html__( $popularity_class ) . '">' . esc_html__( 'By popularity', 'best-selling-in-category-woo' ) . '</a>';
		echo '</div>';

	}

}


/**
 * Remove default sorting
 * 
 */
add_action( 'after_setup_theme', 'best_selling_custom_remove_sorting' );
function best_selling_custom_remove_sorting(){
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
}


