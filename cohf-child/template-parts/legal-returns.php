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
			<h2><?php esc_html_e( 'Our promise', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'Every item in Hope Market is handmade in Kenya by people in our enterprise programmes. We want you to love what you buy. If something is not right, we will work with you to put it right.', 'cohf-child' ); ?></p>
			<h2><?php esc_html_e( 'Handmade variation', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'Because each piece is made by hand, colours, bead patterns, sizes and finishes may differ slightly from the photographs and from one piece to the next. This is part of the character of handmade work and is not a fault.', 'cohf-child' ); ?></p>
			<h2><?php esc_html_e( 'Damaged, faulty or wrong items', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'Please check your order when it arrives. If an item is damaged, faulty or not what you ordered, contact us within 7 days of delivery with your order number and clear photos. We will replace the item or refund you in full, including the delivery fee, and arrange collection where needed at no cost to you.', 'cohf-child' ); ?></p>
			<h2><?php esc_html_e( 'Changed your mind', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'You may return an item within 7 days of delivery if it is unused, undamaged and in its original condition and packaging. Contact us first so we can arrange the return. Return delivery costs are paid by you, and the original delivery fee is not refunded.', 'cohf-child' ); ?></p>
			<h2><?php esc_html_e( 'Items we cannot take back', 'cohf-child' ); ?></h2>
			<ul>
				<li><?php esc_html_e( 'Items made to order or customised for you, such as a name, colours or size you requested.', 'cohf-child' ); ?></li>
				<li><?php esc_html_e( 'Items that have been worn, used, washed or altered.', 'cohf-child' ); ?></li>
				<li><?php esc_html_e( 'Earrings, for hygiene reasons, unless they arrive damaged or faulty.', 'cohf-child' ); ?></li>
				<li><?php esc_html_e( 'Gift items bought at a special or clearance price, unless faulty.', 'cohf-child' ); ?></li>
			</ul>
			<h2><?php esc_html_e( 'How refunds are paid', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'Once we receive and check a returned item, we will confirm your refund within 3 working days. Card payments made through Paystack are refunded to the same card. M-Pesa and cash-on-delivery payments are refunded by M-Pesa to the number you paid from. Your bank or mobile money provider may take a few extra days to show the refund.', 'cohf-child' ); ?></p>
			<h2><?php esc_html_e( 'Exchanges', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'If you would prefer a different size, colour or item, tell us when you contact us. We will arrange an exchange where the item you want is available, and let you know of any price difference.', 'cohf-child' ); ?></p>
			<h2><?php esc_html_e( 'Purchases are not donations', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'Shop purchases are receipted as purchases and kept separate from donations. Refunds of shop purchases follow this policy; refunds of donations follow our Donation and Fundraising Policy.', 'cohf-child' ); ?></p>
			<p><?php echo esc_html( sprintf( /* translators: 1: phone, 2: email. */ __( 'Questions: call or WhatsApp %1$s, or email %2$s.', 'cohf-child' ), $phone, $email ) ); ?></p>
