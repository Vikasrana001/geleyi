<?php

/**
 * Signup form for applying as a vendor
 *
 * @author  Matt Gates <http://mgates.me>
 * @package ProductVendor
 */


class PV_Vendor_Signup
{


	/**
	 * __construct()
	 */
	function __construct()
	{
		if ( !Product_Vendor::$pv_options->get_option( 'show_vendor_registration' ) ) return;

		$this->terms_page = Product_Vendor::$pv_options->get_option( 'terms_to_apply_page' );

		add_action( 'register_form', array( $this, 'vendor_option' ) );
		add_action( 'woocommerce_created_customer', array( $this, 'save_pending' ), 10, 2 );
		add_action( 'register_post', array( $this, 'apply_form' ), 10 );
		add_action( 'init', array( $this, 'apply_form' ), 10 );
	}


	/**
	 *
	 */
	public function vendor_option()
	{
		?>
		<div class="clear"></div>

		<p class="form-row">
			<input class="input-checkbox"
				   id="apply_for_vendor" <?php checked( isset( $_POST[ 'apply_for_vendor' ] ), true ) ?> type="checkbox"
				   name="apply_for_vendor" value="1"/>
			<label for="apply_for_vendor"
				   class="checkbox"><?php _e( 'Apply to become a vendor?', 'wc_product_vendor' ); ?></label>
		</p>

		<?php if ( $this->terms_page ) { ?>
		<p class="form-row agree-to-terms-container" style="display:none">
			<input class="input-checkbox"
				   id="agree_to_terms" <?php checked( isset( $_POST[ 'agree_to_terms' ] ), true ) ?> type="checkbox"
				   name="agree_to_terms" value="1"/>
			<label for="agree_to_terms"
				   class="checkbox"><?php printf( __( 'I have read and accepted the <a href="%s">terms and conditions</a>', 'wc_product_vendor' ), get_permalink( $this->terms_page ) ); ?></label>
		</p>

		<script type="text/javascript">
			jQuery(function () {
				if (jQuery('#apply_for_vendor').is(':checked')) {
					jQuery('.agree-to-terms-container').show();
				}

				jQuery('#apply_for_vendor').on('click', function () {
					jQuery('.agree-to-terms-container').slideToggle();
				});
			})
		</script>
	<?php } ?>

		<div class="clear"></div>
	<?php
	}


	/**
	 *
	 *
	 * @param unknown $user_id
	 */
	public function save_pending( $user_id )
	{
		if ( isset( $_POST[ 'apply_for_vendor' ] ) ) {
			global $woocommerce;

			if ( function_exists( 'wc_clear_messages' ) ) wc_clear_messages(); else {
				$woocommerce->clear_messages();
			}

			if ( user_can( $user_id, 'manage_options' ) ) {
				if ( function_exists( 'wc_add_error' ) ) wc_add_error( __( 'Application denied. You are an administrator.', 'wc_product_vendor' ) ); else $woocommerce->add_error( __( 'Application denied. You are an administrator.', 'wc_product_vendor' ) );
			} else {
				if ( function_exists( 'wc_add_message' ) ) wc_add_message( __( 'Your application has been submitted.', 'wc_product_vendor' ) ); else $woocommerce->add_message( __( 'Your application has been submitted.', 'wc_product_vendor' ) );

				$manual = Product_Vendor::$pv_options->get_option( 'manual_vendor_registration' );
				$role   = apply_filters( 'wc_product_vendor_pending_role', ( $manual ? 'pending_vendor' : 'vendor' ) );

				$wp_user_object = new WP_User( $user_id );
				$wp_user_object->set_role( $role );

				do_action( 'wc_product_vendor_application_submited', $user_id );

				add_filter( 'woocommerce_registration_redirect', array( 'PV_Vendor_Signup', 'redirect_to_vendor_dash' ) );
			}
		}
	}

	public function redirect_to_vendor_dash( $redirect )
	{
		$vendor_dashboard_page = Product_Vendor::$pv_options->get_option( 'vendor_dashboard_page' );

		return apply_filters( 'wc_product_vendor_signup_redirect', get_permalink( $vendor_dashboard_page ) );
	}


	/**
	 *
	 *
	 * @return unknown
	 */
	public function apply_form()
	{
		global $woocommerce;

		if ( !isset( $_POST[ 'apply_for_vendor' ] ) ) return false;

		if ( $this->terms_page && !isset( $_POST[ 'agree_to_terms' ] ) ) {
			if ( function_exists( 'wc_clear_messages' ) ) wc_clear_messages(); else {
				$woocommerce->clear_messages();
			}
			if ( function_exists( 'wc_add_error' ) ) wc_add_error( __( 'You must accept the terms and conditions to become a vendor.', 'wc_product_vendor' ) ); else $woocommerce->add_error( __( 'You must accept the terms and conditions to become a vendor.', 'wc_product_vendor' ) );
		} else if ( isset( $_POST[ 'apply_for_vendor_submit' ] ) ) {
			self::save_pending( get_current_user_id() );
		}

	}


}
