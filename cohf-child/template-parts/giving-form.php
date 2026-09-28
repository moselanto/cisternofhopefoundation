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

	<?php
	if ( $live === false ) :
		/*
		 * Offline does not mean unavailable. The Foundation accepts gifts
		 * today; what is missing is the card and M-Pesa step, not the
		 * willingness to receive. The earlier panel apologised and offered
		 * two bare links, which reads as a dead end on the one page whose
		 * entire purpose is to accept a donation. This states plainly how
		 * a gift is made right now and what the donor can expect back.
		 */
		$wa        = isset( $org['whatsapp'] ) ? preg_replace( '/[^0-9]/', '', (string) $org['whatsapp'] ) : '';
		$tel       = preg_replace( '/[^0-9+]/', '', (string) $org['phone'] );
		$wa_text   = rawurlencode( __( 'Hello, I would like to make a donation to Cistern of Hope Foundation.', 'cohf-child' ) );
		$mail_subj = rawurlencode( __( 'Donation to Cistern of Hope Foundation', 'cohf-child' ) );
		?>
		<div class="give-card__offline">
			<div class="sec-label">
				<span class="sec-label__rule"></span>
				<span class="sec-label__text"><?php esc_html_e( 'Giving directly', 'cohf-child' ); ?></span>
			</div>

			<h3><?php esc_html_e( 'You can give today.', 'cohf-child' ); ?></h3>

			<p><?php esc_html_e( 'Card and M-Pesa giving through this page is being connected. Until it is live, gifts are arranged directly with the Foundation, and they reach the same programmes in the same way.', 'cohf-child' ); ?></p>

			<ol class="give-offline__steps">
				<li><?php esc_html_e( 'Tell us the amount and the area of work you want to strengthen.', 'cohf-child' ); ?></li>
				<li><?php esc_html_e( 'We confirm the payment details and complete the gift with you.', 'cohf-child' ); ?></li>
				<li><?php esc_html_e( 'You receive a receipt from the Foundation.', 'cohf-child' ); ?></li>
			</ol>

			<div class="give-offline__actions">
				<?php if ( '' !== $wa ) : ?>
					<a class="btn cta" href="https://wa.me/<?php echo esc_attr( $wa ); ?>?text=<?php echo esc_attr( $wa_text ); ?>" target="_blank" rel="noopener noreferrer">
						<?php esc_html_e( 'Give by WhatsApp', 'cohf-child' ); ?>
					</a>
				<?php endif; ?>
				<a class="btn dark" href="mailto:<?php echo esc_attr( $org['email'] ); ?>?subject=<?php echo esc_attr( $mail_subj ); ?>">
					<?php esc_html_e( 'Email the Foundation', 'cohf-child' ); ?>
				</a>
				<a class="btn outline" href="tel:<?php echo esc_attr( $tel ); ?>">
					<?php echo esc_html( $org['phone'] ); ?>
				</a>
			</div>

			<p class="give__trust"><?php esc_html_e( 'Donations are separate from Hope Market purchases and are receipted separately.', 'cohf-child' ); ?></p>
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
