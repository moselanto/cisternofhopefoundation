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
			<h2><?php esc_html_e( 'About these terms', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'These terms apply to everyone who visits cisternofhopefoundation.org. By using the website you agree to them. If you do not agree, please do not use the website.', 'cohf-child' ); ?></p>
			<h2><?php esc_html_e( 'Our content', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'The text, photographs, logo and other material on this website belong to Cistern of Hope Foundation or are used with permission. You may share links to our pages and quote short passages with credit to the Foundation.', 'cohf-child' ); ?></p>
			<p><?php esc_html_e( 'You may not copy, alter or reuse our photographs, logo or stories for other purposes, including fundraising or commercial use, without our written permission. Many images show children and vulnerable people and are published only with their consent for our own work.', 'cohf-child' ); ?></p>
			<h2><?php esc_html_e( 'Using the website responsibly', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'When you use this website, you agree not to:', 'cohf-child' ); ?></p>
			<ul>
				<li><?php esc_html_e( 'send spam, false information or abusive messages through our forms;', 'cohf-child' ); ?></li>
				<li><?php esc_html_e( 'attempt to gain unauthorised access to the website, its systems or its data;', 'cohf-child' ); ?></li>
				<li><?php esc_html_e( 'upload or send anything that contains viruses or harmful code;', 'cohf-child' ); ?></li>
				<li><?php esc_html_e( 'use automated tools to copy the website or overload it.', 'cohf-child' ); ?></li>
			</ul>
			<p><?php esc_html_e( 'We may block access for anyone who misuses the website.', 'cohf-child' ); ?></p>
			<h2><?php esc_html_e( 'Accuracy of information', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'We work hard to keep the information on this website accurate and up to date, including the figures we report about our work. Figures are updated as new information is confirmed and may change. If you notice something that is wrong, please tell us.', 'cohf-child' ); ?></p>
			<h2><?php esc_html_e( 'Donations', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'Online gifts are processed securely by our payment provider, Paystack. Our Donation and Refund Policy explains how gifts are used, receipted and, where appropriate, refunded.', 'cohf-child' ); ?></p>
			<h2><?php esc_html_e( 'Links to other websites', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'Where we link to other websites, we do so for your convenience. We are not responsible for their content or their privacy practices.', 'cohf-child' ); ?></p>
			<h2><?php esc_html_e( 'Limitation of liability', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'The website is provided as it is. We do not guarantee that it will always be available or free of errors, and to the extent the law allows, we are not responsible for any loss arising from its use.', 'cohf-child' ); ?></p>
			<h2><?php esc_html_e( 'Privacy', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'Our Privacy Policy explains how we collect and protect personal information.', 'cohf-child' ); ?></p>
			<h2><?php esc_html_e( 'Governing law', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'These terms are governed by the laws of Kenya.', 'cohf-child' ); ?></p>
			<h2><?php esc_html_e( 'Changes to these terms', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'We may update these terms from time to time. The date at the top of this page shows the latest version.', 'cohf-child' ); ?></p>
			<p><?php echo esc_html( sprintf( /* translators: 1: email, 2: phone. */ __( 'Questions: email %1$s or call %2$s.', 'cohf-child' ), $email, $phone ) ); ?></p>
