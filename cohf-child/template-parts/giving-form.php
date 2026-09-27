<?php
/**
 * Giving form.
 *
 * A real, accessible form: grouped radios in fieldsets with legends, labelled
 * inputs, and a live region for errors. The prototype used unlabelled buttons,
 * which a screen reader cannot report as a choice.
 *
 * Falls back to the Foundation's real contact details when no Paystack key
 * has been configured, rather than showing a payment form that cannot pay.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

$cfg   = cohf_giving_config();
$live  = cohf_giving_is_live();
$recur = cohf_giving_has_recurring();
$areas = cohf_giving_areas();
$org   = cohf_org();
?>
<div class="give-card">

	<?php if ( $live === false ) : ?>
		<div class="give-card__offline">
			<h3><?php esc_html_e( 'Giving channels are being connected.', 'cohf-child' ); ?></h3>
			<p><?php esc_html_e( 'Online giving will appear here as soon as the Foundation\'s payment account is live. In the meantime please contact us directly and we will arrange your gift personally.', 'cohf-child' ); ?></p>
			<div class="buttons">
				<a class="btn dark" href="mailto:<?php echo esc_attr( $org['email'] ); ?>"><?php esc_html_e( 'Email the Foundation', 'cohf-child' ); ?></a>
				<a class="btn outline" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $org['phone'] ) ); ?>"><?php echo esc_html( $org['phone'] ); ?></a>
			</div>
		</div>
	<?php else : ?>

		<form class="give" id="cohf-give"
			data-key="<?php echo esc_attr( $cfg['public_key'] ); ?>"
			data-currency="<?php echo esc_attr( $cfg['currency'] ); ?>"
			data-plan="<?php echo esc_attr( $cfg['plan_code'] ); ?>"
			novalidate>

			<?php if ( $recur ) : ?>
				<fieldset class="give__freq">
					<legend class="screen-reader-text"><?php esc_html_e( 'How often would you like to give?', 'cohf-child' ); ?></legend>
					<label class="give__seg">
						<input type="radio" name="cohf_freq" value="once" checked>
						<span><?php esc_html_e( 'Give once', 'cohf-child' ); ?></span>
					</label>
					<label class="give__seg">
						<input type="radio" name="cohf_freq" value="monthly">
						<span><?php esc_html_e( 'Monthly', 'cohf-child' ); ?></span>
					</label>
				</fieldset>
			<?php else : ?>
				<input type="hidden" name="cohf_freq" value="once">
			<?php endif; ?>

			<fieldset class="give__amounts">
				<legend class="give__legend"><?php esc_html_e( 'Choose an amount', 'cohf-child' ); ?></legend>
				<div class="give__amount-grid">
					<?php foreach ( $cfg['amounts'] as $amount ) : ?>
						<label class="give__amount">
							<input type="radio" name="cohf_amount" value="<?php echo esc_attr( (string) $amount ); ?>"
								<?php checked( $amount, $cfg['default'] ); ?>>
							<span><?php echo esc_html( number_format_i18n( $amount ) . ' ' . $cfg['currency'] ); ?></span>
						</label>
					<?php endforeach; ?>
				</div>
			</fieldset>

			<div class="give__field">
				<label for="cohf-give-custom"><?php esc_html_e( 'Or enter another amount', 'cohf-child' ); ?></label>
				<input type="number" id="cohf-give-custom" name="cohf_custom" min="50" step="50"
					inputmode="numeric"
					placeholder="<?php esc_attr_e( 'Amount in KES', 'cohf-child' ); ?>">
			</div>

			<div class="give__field">
				<label for="cohf-give-area"><?php esc_html_e( 'Support area', 'cohf-child' ); ?></label>
				<select id="cohf-give-area" name="cohf_area">
					<?php foreach ( $areas as $key => $label ) : ?>
						<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="give__row">
				<div class="give__field">
					<label for="cohf-give-name"><?php esc_html_e( 'Your name', 'cohf-child' ); ?></label>
					<input type="text" id="cohf-give-name" name="cohf_name" autocomplete="name" required>
				</div>
				<div class="give__field">
					<label for="cohf-give-email"><?php esc_html_e( 'Email for your receipt', 'cohf-child' ); ?></label>
					<input type="email" id="cohf-give-email" name="cohf_email" autocomplete="email" required>
				</div>
			</div>

			<p class="give__note">
				<?php esc_html_e( 'Paystack will offer M-Pesa and card on the secure payment step. Your details are sent to Paystack, never stored on this website.', 'cohf-child' ); ?>
			</p>

			<p class="give__error" role="alert" aria-live="polite" hidden></p>

			<button type="submit" class="btn cta give__submit">
				<?php esc_html_e( 'Continue to secure giving', 'cohf-child' ); ?>
			</button>

			<p class="give__trust">
				<?php esc_html_e( 'Donations are separate from Hope Market purchases and are receipted separately.', 'cohf-child' ); ?>
			</p>
		</form>

	<?php endif; ?>
</div>
