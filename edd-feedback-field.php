<?php
/**
 * Plugin Name: EDD Feedback Field
 * Description:
 */

/**
 * The function to dispaly the extra checkout field
 *
 * @since 1.0
 */
function pippin_edd_custom_checkout_fields() {
	?>
	<p id="edd-feedback-wrap">
		<label class="edd-label" for="edd-feedback"><?php _e('Feedback', 'edd_feedback_field'); ?></label>
		<span class="edd-description"><?php _e( 'What did you think of your purchasing experience on this site?', 'edd_feedback_field' ); ?></span>
		<textarea rows="4" cols="100" class="edd-input"  name="edd_feedback" id="edd-feedback" placeholder="<?php _e('Feedback', 'edd_feedback_field'); ?>" value=""></textarea>
	</p>

<?php
}
add_action('edd_purchase_form_user_info', 'pippin_edd_custom_checkout_fields');

/**
 * Store custom purchase fields
 *
 * @param $payment_meta EDD Payment Meta
 *
 * @return mixed
 */
function pippin_edd_store_custom_fields($payment_meta) {
	$payment_meta['feedback'] = isset( $_POST['edd_feedback'] ) ? sanitize_text_field( $_POST['edd_feedback'] ) : '';
	return $payment_meta;
}
add_filter('edd_payment_meta', 'pippin_edd_store_custom_fields');
// show the custom fields in the "View Order Details" popup
function pippin_edd_purchase_details($payment_meta, $user_info) {
	$phone = isset( $payment_meta['feedback'] ) ? $payment_meta['feedback'] : 'none';
	?>
	<li><?php echo __('User Feedback:', 'edd_feedback_field') . ' ' . $phone; ?></li>

<?php
}
add_action('edd_payment_personal_details_list', 'pippin_edd_purchase_details', 10, 2);

/**
 * Send the email after complete purchase
 *
 * @since 1.0
 */
function edd_send_email_feedback() {
	$feedback =  __('A user has submitted some feedback regarding the purchasing experience on your site', 'edd_feedback_field') . "\n\n" . sanitize_text_field( $_POST['edd_feedback'] );
	$admin_email = get_option( 'admin_email' );

	wp_mail( $admin_email, 'Store Purchasing Feedback', $feedback );




}
add_action('edd_complete_purchase', 'edd_send_email_feedback');