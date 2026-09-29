<?php
/**
 * Giving form.
 *
 * A real, accessible form: grouped radios in fieldsets with legends, labelled
 * inputs, and a live region for errors. The prototype used unlabelled buttons,
 * which a screen reader cannot report as a choice.
 *
 * Three states, in order of precedence:
 *
 *   1. Thank you  - the donor has just come back from Paystack. Showing them
 *                   the donation form again would be absurd, so the form is
 *                   replaced by the verified outcome of their gift.
 *   2. Offline    - no Paystack key configured. Falls back to the
 *                   Foundation's real contact details rather than showing a
 *                   payment form that cannot take a payment.
 *   3. Live       - the form itself.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

$cfg   = cohf_giving_config();
$live  = cohf_giving_is_live();
$recur = cohf_giving_has_recurring();
$areas = cohf_giving_areas();
$org   = cohf_org();

/*
 * A returning donor arrives with ?giving=thank-you&ref=... on the URL.
 * That reference is a claim, not proof: anyone can type one. It is checked
 * against Paystack server-side before a single word of thanks is shown.
 *
 * phpcs:disable WordPress.Security.NonceVerification.Recommended -- a return
 * from an external payment provider carries no nonce, and the reference is
 * verified with Paystack rather than trusted.
 */
$cohf_ref    = isset( $_GET['ref'] ) ? sanitize_text_field( wp_unslash( $_GET['ref'] ) ) : '';
$cohf_is_ty  = isset( $_GET['giving'] ) && 'thank-you' === sanitize_text_field( wp_unslash( $_GET['giving'] ) );
// phpcs:enable WordPress.Security.NonceVerification.Recommended

$cohf_show_ty = ( $cohf_is_ty && '' !== $cohf_ref && function_exists( 'cohf_giving_verify' ) );
$cohf_result  = $cohf_show_ty ? cohf_giving_verify( $cohf_ref ) : array();
?>
<div class="give-card">

	<?php if ( $cohf_show_ty ) : ?>

		<div class="give-thanks<?php echo empty( $cohf_result['ok'] ) ? ' give-thanks--pending' : ''; ?>">
			<div class="sec-label">
				<span class="sec-label__rule"></span>
				<span class="sec-label__text">
					<?php
					echo esc_html(
						empty( $cohf_result['ok'] )
							? __( 'Your gift', 'cohf-child' )
							: __( 'Gift received', 'cohf-child' )
					);
					?>
				</span>
			</div>

			<?php if ( empty( $cohf_result['ok'] ) === false ) : ?>

				<h3>
					<?php
					$cohf_donor = isset( $cohf_result['name'] ) ? (string) $cohf_result['name'] : '';
					echo esc_html(
						'' !== $cohf_donor
							/* translators: %s: donor first name. */
							? sprintf( __( 'Thank you, %s.', 'cohf-child' ), $cohf_donor )
							: __( 'Thank you.', 'cohf-child' )
					);
					?>
				</h3>

				<p class="give-thanks__amount">
					<?php
					echo esc_html(
						(string) $cohf_result['currency'] . ' ' . number_format_i18n( (float) $cohf_result['amount'], 0 )
					);
					?>
				</p>

				<p><?php esc_html_e( 'Your gift has been received and confirmed. A receipt is on its way to the email address you gave, and it carries your payment reference.', 'cohf-child' ); ?></p>

				<p class="give-thanks__ref">
					<?php
					/* translators: %s: Paystack transaction reference. */
					printf( esc_html__( 'Reference: %s', 'cohf-child' ), '<strong>' . esc_html( $cohf_ref ) . '</strong>' );
					?>
				</p>

				<p><?php esc_html_e( 'This goes directly into the work of restoring dignity, promoting hope and strengthening communities in Kenya. Thank you for standing with us.', 'cohf-child' ); ?></p>

			<?php else : ?>

				<h3><?php esc_html_e( 'We are confirming your gift.', 'cohf-child' ); ?></h3>

				<p>
					<?php
					echo esc_html(
						isset( $cohf_result['message'] ) && '' !== $cohf_result['message']
							? (string) $cohf_result['message']
							: __( 'This payment could not be confirmed automatically.', 'cohf-child' )
					);
					?>
				</p>

				<p class="give-thanks__ref">
					<?php
					/* translators: %s: Paystack transaction reference. */
					printf( esc_html__( 'Reference: %s', 'cohf-child' ), '<strong>' . esc_html( $cohf_ref ) . '</strong>' );
					?>
				</p>

				<p><?php esc_html_e( 'If money has left your account, it is safe. Quote the reference above and the Foundation will confirm it with you directly.', 'cohf-child' ); ?></p>

			<?php endif; ?>

			<div class="give-thanks__actions">
				<a class="btn dark" href="<?php echo esc_url( cohf_page_url( 'page-templates/page-impact.php' ) ); ?>"><?php esc_html_e( 'See our impact', 'cohf-child' ); ?></a>
				<a class="btn outline" href="<?php echo esc_url( cohf_page_url( 'page-templates/page-contact.php' ) ); ?>"><?php esc_html_e( 'Contact the Foundation', 'cohf-child' ); ?></a>
			</div>
		</div>

	<?php elseif ( $live === false ) : ?>

		<?php
		/*
		 * Offline does not mean unavailable. The Foundation accepts gifts
		 * today; what is missing is the card and M-Pesa step, not the
		 * willingness to receive.
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
			data-thanks="<?php echo esc_url( add_query_arg( 'giving', 'thank-you', cohf_page_url( 'page-templates/page-support.php' ) ) ); ?>"
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
							<span><?php echo esc_html( $cfg['currency'] . ' ' . number_format_i18n( $amount ) ); ?></span>
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

			<div class="give__field">
				<label for="cohf-give-phone">
					<?php esc_html_e( 'Phone number', 'cohf-child' ); ?>
					<span class="give__optional"><?php esc_html_e( 'for M-Pesa', 'cohf-child' ); ?></span>
				</label>
				<input type="tel" id="cohf-give-phone" name="cohf_phone" autocomplete="tel"
					inputmode="tel"
					placeholder="<?php esc_attr_e( '07xx xxx xxx', 'cohf-child' ); ?>">
			</div>

			<p class="give__note">
				<?php esc_html_e( 'Paystack will offer M-Pesa and card on the secure payment step. Card and M-Pesa details are handled entirely by Paystack and never reach this website; your name, email and phone are recorded here so the Foundation can receipt your gift.', 'cohf-child' ); ?>
			</p>

			<p class="give__error" role="alert" aria-live="polite" hidden></p>

			<button type="submit" class="btn cta give__submit">
				<?php esc_html_e( 'Continue to secure giving', 'cohf-child' ); ?>
			</button>

			<p class="give__trust">
				<?php esc_html_e( 'Secure payment powered by Paystack. Donations are separate from Hope Market purchases and are receipted separately.', 'cohf-child' ); ?>
			</p>
		</form>

	<?php endif; ?>
</div>
