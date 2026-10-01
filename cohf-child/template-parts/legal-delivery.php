<?php
/**
 * Default wording for this legal page. Used when the page has no content of
 * its own, and copied into the page once so it can be edited in WordPress.
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;

$email = cohf_org_get( 'email' );
$phone = cohf_org_get( 'phone' );
?>
			<p class="field__hint"><?php esc_html_e( 'Last updated: 1 October 2026', 'cohf-child' ); ?></p>
			<h2><?php esc_html_e( 'Where we deliver', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'We currently deliver to addresses within Kenya. If you are outside Kenya and would like to buy, contact us and we will see what we can arrange.', 'cohf-child' ); ?></p>
			<h2><?php esc_html_e( 'Delivery areas and fees', 'cohf-child' ); ?></h2>
			<ul>
				<li><?php esc_html_e( 'Nairobi and nearby areas, including Kabete, Westlands, Kikuyu and Kiambu: delivery from KSh 300, depending on the exact location.', 'cohf-child' ); ?></li>
				<li><?php esc_html_e( 'Other towns in Kenya: sent by a reliable courier or parcel service. We confirm the fee with you before dispatch, based on the destination and the size of the parcel.', 'cohf-child' ); ?></li>
				<li><?php esc_html_e( 'Large or heavy items, such as wall clocks, vases and clay murals: we confirm the fee and the safest way to send them before dispatch.', 'cohf-child' ); ?></li>
			</ul>
			<p><?php esc_html_e( 'The delivery fee is shown or confirmed before you pay. If you order on WhatsApp, we confirm the delivery fee in the chat before you pay.', 'cohf-child' ); ?></p>
			<h2><?php esc_html_e( 'How long it takes', 'cohf-child' ); ?></h2>
			<ul>
				<li><?php esc_html_e( 'Orders are prepared and packed within 1 to 2 working days of payment or confirmation.', 'cohf-child' ); ?></li>
				<li><?php esc_html_e( 'Nairobi and nearby areas: usually 1 to 3 working days after dispatch.', 'cohf-child' ); ?></li>
				<li><?php esc_html_e( 'Other towns: usually 2 to 5 working days after dispatch.', 'cohf-child' ); ?></li>
				<li><?php esc_html_e( 'Made-to-order or customised items take longer. We will tell you the expected date when you order.', 'cohf-child' ); ?></li>
			</ul>
			<p><?php esc_html_e( 'Delivery times are estimates. Public holidays, weather and courier delays can occasionally affect them. We will keep you informed if your order is delayed.', 'cohf-child' ); ?></p>
			<h2><?php esc_html_e( 'Collecting your order', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'You may be able to collect your order from our office in Kabete, behind N Market, by arrangement. Please contact us first so we can have it ready for you.', 'cohf-child' ); ?></p>
			<h2><?php esc_html_e( 'When your order arrives', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'Please check your parcel on delivery. If anything is damaged or missing, keep the packaging and contact us within 7 days with photos, as explained in our Refund and Returns Policy.', 'cohf-child' ); ?></p>
			<h2><?php esc_html_e( 'Paying for your order', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'You can pay securely online by card through Paystack, or arrange to pay by M-Pesa or cash on delivery when you order on WhatsApp. We never ask for your card PIN, M-Pesa PIN or passwords.', 'cohf-child' ); ?></p>
			<p><?php echo esc_html( sprintf( /* translators: 1: phone, 2: email. */ __( 'Questions: call or WhatsApp %1$s, or email %2$s.', 'cohf-child' ), $phone, $email ) ); ?></p>
