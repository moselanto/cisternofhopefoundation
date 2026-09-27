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
?>
<?php cohf_form_result_notice(); ?>

<form method="post" action="<?php echo esc_url( get_permalink() ); ?>#enquire" novalidate>
	<?php wp_nonce_field( 'cohf_enquiry', 'cohf_enquiry_nonce' ); ?>

	<div class="field">
		<label for="cohf-name"><?php esc_html_e( 'Name', 'cohf-child' ); ?> <span class="req" aria-hidden="true">*</span></label>
		<input type="text" id="cohf-name" name="cohf_name" required autocomplete="name" placeholder="<?php esc_attr_e( 'Your name', 'cohf-child' ); ?>">
	</div>

	<div class="field">
		<label for="cohf-email"><?php esc_html_e( 'Email', 'cohf-child' ); ?> <span class="req" aria-hidden="true">*</span></label>
		<input type="email" id="cohf-email" name="cohf_email" required autocomplete="email" placeholder="<?php esc_attr_e( 'you@example.com', 'cohf-child' ); ?>">
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
		<input type="tel" id="cohf-phone" name="cohf_phone" autocomplete="tel" placeholder="+254">
	</div>

	<div class="field full" data-enquiry-for="partnership media support">
		<label for="cohf-organisation"><?php esc_html_e( 'Organisation', 'cohf-child' ); ?></label>
		<input type="text" id="cohf-organisation" name="cohf_organisation" autocomplete="organization">
	</div>

	<div class="field full">
		<label for="cohf-message"><?php esc_html_e( 'Message', 'cohf-child' ); ?> <span class="req" aria-hidden="true">*</span></label>
		<textarea id="cohf-message" name="cohf_message" required placeholder="<?php esc_attr_e( 'Tell us how we can work together...', 'cohf-child' ); ?>"></textarea>
		<span class="field__hint" data-enquiry-for="volunteer"><?php esc_html_e( 'Please tell us about your skills, availability and the kind of role you are interested in.', 'cohf-child' ); ?></span>
		<span class="field__hint" data-enquiry-for="partnership"><?php esc_html_e( 'Please tell us about your organisation and the kind of partnership you are considering.', 'cohf-child' ); ?></span>
		<span class="field__hint" data-enquiry-for="complaint"><?php esc_html_e( 'Complaints and feedback are treated seriously and confidentially. You may also raise a concern anonymously by telephone.', 'cohf-child' ); ?></span>
	</div>

	<div class="field full">
		<label for="cohf-consent">
			<input type="checkbox" id="cohf-consent" name="cohf_consent" value="1" required>
			<?php esc_html_e( 'I am happy for Cistern of Hope Foundation to use these details to respond to my message.', 'cohf-child' ); ?>
		</label>
		<span class="field__hint"><?php esc_html_e( 'We use your details only to reply to you. We do not share them with third parties.', 'cohf-child' ); ?></span>
	</div>

	<p class="honeypot" aria-hidden="true">
		<label for="cohf-website"><?php esc_html_e( 'Leave this field empty', 'cohf-child' ); ?></label>
		<input type="text" id="cohf-website" name="cohf_website" tabindex="-1" autocomplete="off">
	</p>

	<div class="field full">
		<button class="btn dark" type="submit" name="cohf_enquiry_submit" value="1"><?php esc_html_e( 'Send Enquiry', 'cohf-child' ); ?></button>
	</div>
</form>
