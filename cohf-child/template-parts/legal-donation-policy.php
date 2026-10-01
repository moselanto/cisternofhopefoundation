<?php
/**
 * Default wording for this legal page. Used when the page has no content of
 * its own, and copied into the page once so it can be edited in WordPress.
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;

$email   = cohf_org_get( 'email' );
$phone   = cohf_org_get( 'phone' );
$address = cohf_org_get( 'address' );
$updated = '30 September 2026';
?>
			<p class="field__hint"><?php esc_html_e( 'Last updated: 30 September 2026', 'cohf-child' ); ?></p>
			<h2><?php esc_html_e( 'Our commitment to donors', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'Every gift to Cistern of Hope Foundation is received with gratitude and used responsibly to fight poverty and restore dignity in the communities we serve. We are accountable for every shilling entrusted to us.', 'cohf-child' ); ?></p>
			<h2><?php esc_html_e( 'How online giving works', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'Online gifts are processed by Paystack, a licensed payment provider. You can give by card or mobile money, including M-Pesa. Your payment details are entered with Paystack and are never seen or stored by our website.', 'cohf-child' ); ?></p>
			<p><?php esc_html_e( 'Each gift receives a unique reference beginning with COH, which appears on your confirmation and helps us trace your gift if you contact us.', 'cohf-child' ); ?></p>
			<h2><?php esc_html_e( 'How your gift is used', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'Where you choose a programme area, we direct your gift to that work. If a programme is fully funded or can no longer proceed, we will use your gift where the need is greatest, in line with our mission, unless you ask us not to.', 'cohf-child' ); ?></p>
			<p><?php esc_html_e( 'Unrestricted gifts are used where they are needed most across our programmes and the costs of running them responsibly.', 'cohf-child' ); ?></p>
			<h2><?php esc_html_e( 'Receipts', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'You will receive a payment confirmation from Paystack by email. If you need an official receipt from the Foundation, email us with your gift reference.', 'cohf-child' ); ?></p>
			<h2><?php esc_html_e( 'Refunds', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'We understand that mistakes happen. If you gave the wrong amount, gave twice by accident, or did not authorise a payment, contact us within 30 days of the gift with your reference and we will review your request and, where appropriate, refund it to the original payment method.', 'cohf-child' ); ?></p>
			<p><?php esc_html_e( 'Because gifts are put to work quickly, we cannot usually refund a gift after 30 days, or once it has been spent on programme activity, except where the law requires it.', 'cohf-child' ); ?></p>
			<p><?php esc_html_e( 'Approved refunds are processed through Paystack. The time it takes to reach you depends on your bank or mobile money provider.', 'cohf-child' ); ?></p>
			<h2><?php esc_html_e( 'Fundraising on our behalf', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'We welcome individuals, schools, churches and companies who want to raise money for our work. Please contact us before you start, so we can agree how the money will be collected, which programme it will support, and how you may use our name and logo.', 'cohf-child' ); ?></p>
			<p><?php esc_html_e( 'Funds raised for us should be paid directly to the Foundation, through our website or our official payment details confirmed by our team. Never collect money in our name in cash or through personal accounts without our written agreement.', 'cohf-child' ); ?></p>
			<h2><?php esc_html_e( 'Our fundraising standards', 'cohf-child' ); ?></h2>
			<ul>
				<li><?php esc_html_e( 'We tell donors honestly what their gift will do and report back on our work.', 'cohf-child' ); ?></li>
				<li><?php esc_html_e( 'We never pressure anyone to give, and we respect a request to stop contacting you.', 'cohf-child' ); ?></li>
				<li><?php esc_html_e( 'We do not sell or share donor details.', 'cohf-child' ); ?></li>
				<li><?php esc_html_e( 'We use photographs and stories only with consent, and protect the dignity of the people in them.', 'cohf-child' ); ?></li>
				<li><?php esc_html_e( 'Our accounts are audited each year, as our Constitution requires.', 'cohf-child' ); ?></li>
			</ul>
			<h2><?php esc_html_e( 'Anonymous gifts', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'If you choose to give anonymously, we do not publish your name. We still keep a private record so that your gift can be accounted for and, if needed, refunded.', 'cohf-child' ); ?></p>
			<h2><?php esc_html_e( 'Fraud and security', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'We never ask for your card PIN, M-Pesa PIN or passwords. If someone contacts you claiming to collect money for Cistern of Hope Foundation and you are unsure, please contact us directly before giving.', 'cohf-child' ); ?></p>
			<p><?php echo esc_html( sprintf( /* translators: 1: email, 2: phone. */ __( 'Questions: email %1$s or call %2$s.', 'cohf-child' ), $email, $phone ) ); ?></p>
