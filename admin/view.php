<?php
/**
 * @package   Best selling in category
 * @author    Vladislav Musílek
 * @license   GPL-2.0+
 * @link      https://musilda.com
 * @copyright 2022 Musilda.com
 */
?>

<?php 

if ( !empty( $_POST['update_setting'] ) ) {

	$defaults = array(
		'enable',
		'custom_sorting'
	);

	$option = array();
	
	foreach( $defaults as $item ) {
		if ( !empty( $_POST[$item] ) ) {
			$option[$item] = sanitize_text_field( $_POST[$item] );
			
		}
	}

	update_option( 'best_selling_in_category', $option );

	wp_redirect( admin_url().'admin.php?page=best-selling-in-category' );

}


$option = get_option( 'best_selling_in_category' );

?>
<div class="wrap ctm-wrap">
	<div class="ctm-header">
		<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	</div> 
	<div class="ctm-body"> 
		<div class="ctm-body-content">
			<form id="ctm-setting-form" action="" method="post">			
				<div class="admin-box-container" >
					<div class="admin-box-inner admin-box-full">
						<h3 class="admin-box-title"><span><?php esc_html_e( 'Plugin settings', 'best-selling-in-category-woo' ); ?></span></h3>
						
						<?php
							echo '<div class="admin-box-line">';
								$label = esc_html__( 'Enable/Disable best selling', 'best-selling-in-category-woo' );
								$this->checkbox_line( $label, 'enable', $option );
							echo '</div>';
							
							echo '<div class="admin-box-line">';
								$label = esc_html__( 'Active custom sorting', 'best-selling-in-category-woo' );
								$this->checkbox_line( $label, 'custom_sorting', $option );
							echo '</div>';

							
						?>

						<div class="clear"></div>
						<div class="admin-box-item type-submit-form">
							<input type="hidden" name="update_setting" value="ok">
							<input type="submit" name="emc-setting" id="emc-setting" class="button-primary" value="<?php esc_html_e( 'Save setting', 'best-selling-in-category-woo' ); ?>">
						</div>		

					</div>
				</div>

			</form>
		</div>
	</div>
</div>
