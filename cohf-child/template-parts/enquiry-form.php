<?php
/**
 * Enquiry form. Prototype markup: form > .field / .field.full, submit as .btn.dark
 *
 * Replaceable at any time by a WPForms or Fluent Forms shortcode; this native
 * version means the site works on day one without a form plugin. Field names,
 * nonce and honeypot are unchanged from earlier versions, so inc/forms.php
 * continues to handle submissions without modification.
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;

$default_type = isset( $args['default_type'] ) ? $args['default_type'] : 'general';
$types        = cohf_enquiry_types();

// After an error, keep what the visitor typed so nothing has to be retyped.
$cohf_result = cohf_set_form_result();
$cohf_keep   = static function ( $key ) use ( $cohf_result ) {
	if ( ! $cohf_result || 'error' !== $cohf_result['status'] || ! isset( $_POST[ $key ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		return '';
	}
	return sanitize_textarea_field( wp_unslash( $_POST[ $key ] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
};

/**
 * Initial visibility for a conditional block.
 *
 * The conditional blocks below used to render visible and were hidden by
 * assets/js/main.js on load. That meant all three message hints appeared at
 * once before the script ran, and stayed stacked on top of each other for
 * anyone without JavaScript - reading as one garbled paragraph telling the
 * visitor to describe their skills, their organisation and their complaint
 * simultaneously. Rendering the correct state on the server removes the flash
 * and gives a coherent no-JavaScript page; main.js then only handles changes.
 *
 * @param string $for Space-separated enquiry types this block belongs to.
 * @return string Empty string, or ' hidden'.
 */
$cohf_conditional = static function ( $for ) use ( $default_type ) {
	return in_array( $default_type, preg_split( '/\s+/', $for ), true ) ? '' : ' hidden';
};
?>
<div class="cohf-form-card">
<div class="cohf-form-card__head">
	<h3><?php esc_html_e( 'Send us a message', 'cohf-child' ); ?></h3>
	<p><?php esc_html_e( 'Fields marked * are required. We use your details only to reply to you.', 'cohf-child' ); ?></p>
</div>
<?php cohf_form_result_notice(); ?>

<form class="cohf-form" method="post" action="<?php echo esc_url( get_permalink() ); ?>#enquire" novalidate data-cohf-form data-token-url="<?php echo esc_url( rest_url( 'cohf/v1/form-token' ) ); ?>">
	<?php wp_nonce_field( 'cohf_enquiry', 'cohf_enquiry_nonce' ); ?>
	<?php cohf_form_ts_field(); ?>
	<input type="hidden" name="cohf_js" value="">

	<div class="field">
		<label for="cohf-name"><?php esc_html_e( 'Name', 'cohf-child' ); ?> <span class="req" aria-hidden="true">*</span></label>
		<input type="text" id="cohf-name" name="cohf_name" value="<?php echo esc_attr( $cohf_keep( 'cohf_name' ) ); ?>" required maxlength="100" autocomplete="name" placeholder="<?php esc_attr_e( 'Your name', 'cohf-child' ); ?>">
	</div>

	<div class="field">
		<label for="cohf-email"><?php esc_html_e( 'Email', 'cohf-child' ); ?> <span class="req" aria-hidden="true">*</span></label>
		<input type="email" id="cohf-email" name="cohf_email" value="<?php echo esc_attr( $cohf_keep( 'cohf_email' ) ); ?>" required maxlength="150" autocomplete="email" inputmode="email" placeholder="<?php esc_attr_e( 'name@email.com', 'cohf-child' ); ?>">
	</div>

	<div class="field">
		<label for="cohf-enquiry-type"><?php esc_html_e( 'Reason', 'cohf-child' ); ?></label>
		<select id="cohf-enquiry-type" name="cohf_type">
			<?php foreach ( $types as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $default_type, $value ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
	</div>

	<div class="field">
		<label for="cohf-phone"><?php esc_html_e( 'Phone', 'cohf-child' ); ?></label>
		<input type="tel" id="cohf-phone" name="cohf_phone" value="<?php echo esc_attr( $cohf_keep( 'cohf_phone' ) ); ?>" maxlength="30" autocomplete="tel" inputmode="tel" placeholder="+254 7XX XXX XXX">
	</div>

	<div class="field full" data-enquiry-for="partnership media support"<?php echo $cohf_conditional( 'partnership media support' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- returns a fixed literal. ?>>
		<label for="cohf-organisation"><?php esc_html_e( 'Organisation', 'cohf-child' ); ?></label>
		<input type="text" id="cohf-organisation" name="cohf_organisation" value="<?php echo esc_attr( $cohf_keep( 'cohf_organisation' ) ); ?>" maxlength="150" autocomplete="organization">
	</div>

	<div class="field full">
		<label for="cohf-message"><?php esc_html_e( 'Message', 'cohf-child' ); ?> <span class="req" aria-hidden="true">*</span></label>
		<textarea id="cohf-message" name="cohf_message" required maxlength="5000" rows="6" placeholder="<?php esc_attr_e( 'How can we help?', 'cohf-child' ); ?>"><?php echo esc_textarea( $cohf_keep( 'cohf_message' ) ); ?></textarea>
		<span class="field__count" aria-hidden="true"><span data-count>0</span> / 5000</span>
		<span class="field__hint" data-enquiry-for="volunteer"<?php echo $cohf_conditional( 'volunteer' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- returns a fixed literal. ?>><?php esc_html_e( 'Please tell us about your skills, availability and the kind of role you are interested in.', 'cohf-child' ); ?></span>
		<span class="field__hint" data-enquiry-for="partnership"<?php echo $cohf_conditional( 'partnership' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- returns a fixed literal. ?>><?php esc_html_e( 'Please tell us about your organisation and the kind of partnership you are considering.', 'cohf-child' ); ?></span>
		<span class="field__hint" data-enquiry-for="complaint"<?php echo $cohf_conditional( 'complaint' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- returns a fixed literal. ?>><?php esc_html_e( 'Complaints and feedback are treated seriously and confidentially. You may also raise a concern anonymously by telephone.', 'cohf-child' ); ?></span>
	</div>

	<div class="field full field--check">
		<label class="cohf-check" for="cohf-consent">
			<input type="checkbox" id="cohf-consent" name="cohf_consent" value="1" required>
			<span class="cohf-check__box" aria-hidden="true"></span>
			<?php esc_html_e( 'I am happy for Cistern of Hope Foundation to use these details to respond to my message.', 'cohf-child' ); ?>
		</label>
		<span class="field__hint"><?php esc_html_e( 'We use your details only to reply to you. We do not share them with third parties.', 'cohf-child' ); ?></span>
	</div>

	<p class="honeypot" aria-hidden="true">
		<label for="cohf-website"><?php esc_html_e( 'Leave this field empty', 'cohf-child' ); ?></label>
		<input type="text" id="cohf-website" name="cohf_website" tabindex="-1" autocomplete="off">
	</p>

	<div class="field full">
		<button class="cohf-submit" type="submit" name="cohf_enquiry_submit" value="1">
			<span><?php esc_html_e( 'Send message', 'cohf-child' ); ?></span>
			<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M4 12h15M13 6l6 6-6 6"/></svg>
		</button>
		<p class="cohf-form__secure"><svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg><?php esc_html_e( 'Your details are sent securely and never shared.', 'cohf-child' ); ?></p>
	</div>
</form>
</div>
