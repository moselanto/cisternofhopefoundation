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
			<p class="field__hint"><?php echo esc_html( sprintf( /* translators: %s: date. */ __( 'Last updated: %s', 'cohf-child' ), $updated ) ); ?></p>

			<h2><?php esc_html_e( 'Who we are', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'Cistern of Hope Foundation (COHF) is a registered Kenyan organisation working to eradicate poverty through community empowerment. We are responsible for the personal information collected through this website.', 'cohf-child' ); ?></p>
			<p><?php echo esc_html( sprintf( /* translators: 1: address, 2: email, 3: phone. */ __( 'Contact: %1$s. Email: %2$s. Telephone: %3$s.', 'cohf-child' ), $address, $email, $phone ) ); ?></p>

			<h2><?php esc_html_e( 'Information we collect', 'cohf-child' ); ?></h2>
			<ul>
				<li><strong><?php esc_html_e( 'When you contact us:', 'cohf-child' ); ?></strong> <?php esc_html_e( 'your name, email address, and, if you choose to give them, your phone number, organisation and message. The form sends this to our email inbox; it is not stored in the website database.', 'cohf-child' ); ?></li>
				<li><strong><?php esc_html_e( 'When you give online:', 'cohf-child' ); ?></strong> <?php esc_html_e( 'your name, email address, phone number, gift amount, the programme you chose to support, any message you add and the transaction reference. Card and M-Pesa details are entered with our payment provider, Paystack, and never reach or are stored by this website.', 'cohf-child' ); ?></li>
				<li><strong><?php esc_html_e( 'Technical information:', 'cohf-child' ); ?></strong> <?php esc_html_e( 'like every website, our server briefly processes your IP address and browser details to deliver pages securely. We use a one-way scrambled form of your IP address only to block spam and repeated attempts, and it expires within hours.', 'cohf-child' ); ?></li>
			</ul>

			<h2><?php esc_html_e( 'How we use it', 'cohf-child' ); ?></h2>
			<ul>
				<li><?php esc_html_e( 'To reply to your enquiry, partnership proposal, volunteer offer, complaint or feedback.', 'cohf-child' ); ?></li>
				<li><?php esc_html_e( 'To process your donation, send you a receipt and keep the financial records the law and our donors require.', 'cohf-child' ); ?></li>
				<li><?php esc_html_e( 'To keep this website secure and free of spam and abuse.', 'cohf-child' ); ?></li>
			</ul>
			<p><?php esc_html_e( 'We rely on your consent (for example, when you tick the box on our contact form), on the need to complete a donation you have asked us to process, on our legal obligations, and on our legitimate interest in running a safe website. We never sell, rent or trade your personal information.', 'cohf-child' ); ?></p>

			<h2><?php esc_html_e( 'Anonymous giving', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'If you choose to give anonymously, we do not publish your name or share it with anyone outside the Foundation. We still keep a private record so that your gift can be receipted and accounted for.', 'cohf-child' ); ?></p>

			<h2><?php esc_html_e( 'Who we share it with', 'cohf-child' ); ?></h2>
			<ul>
				<li><strong><?php esc_html_e( 'Paystack', 'cohf-child' ); ?></strong> <?php esc_html_e( 'processes online payments on our behalf under its own security and privacy standards.', 'cohf-child' ); ?></li>
				<li><strong><?php esc_html_e( 'Our website host and email provider', 'cohf-child' ); ?></strong> <?php esc_html_e( 'store and deliver the site and our emails.', 'cohf-child' ); ?></li>
				<li><?php esc_html_e( 'Auditors, regulators or law enforcement, only where the law requires it.', 'cohf-child' ); ?></li>
			</ul>

			<h2><?php esc_html_e( 'Photographs and stories', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'The photographs and stories on this website are published with the consent of the people involved. We protect the dignity and privacy of children and vulnerable people: where appropriate we change names, withhold identifying details or photograph people from behind. If you appear on this website and would like an image or story removed, contact us and we will act promptly.', 'cohf-child' ); ?></p>

			<h2><?php esc_html_e( 'Cookies', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'This website uses only the cookies needed for it to work and stay secure, for example to protect our forms. We do not use advertising cookies. If you give online, Paystack may set its own cookies to complete the payment securely.', 'cohf-child' ); ?></p>

			<h2><?php esc_html_e( 'How long we keep it', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'Enquiries are kept only as long as needed to respond and follow up. Donation records are kept for as long as Kenyan law and audit requirements demand. Spam-protection data expires automatically within hours.', 'cohf-child' ); ?></p>

			<h2><?php esc_html_e( 'How we protect it', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'The website is served over an encrypted connection. Our forms are protected against spam and automated abuse, payments are handled entirely by Paystack, and access to donation records is limited to authorised Foundation staff.', 'cohf-child' ); ?></p>

			<h2><?php esc_html_e( 'Your rights', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'Under the Data Protection Act, 2019, you have the right to be told how your information is used, to see the information we hold about you, to ask us to correct or delete it, to object to its use, and to withdraw consent at any time. To exercise any of these rights, email us and we will respond within a reasonable time.', 'cohf-child' ); ?></p>
			<p><?php esc_html_e( 'If you are not satisfied with our response, you may complain to the Office of the Data Protection Commissioner (ODPC), Kenya.', 'cohf-child' ); ?></p>

			<h2><?php esc_html_e( 'Changes to this policy', 'cohf-child' ); ?></h2>
			<p><?php esc_html_e( 'We may update this policy as our work and the law change. The date at the top of this page shows when it was last revised.', 'cohf-child' ); ?></p>

			<p><a class="btn dark" href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>"><?php esc_html_e( 'Email us about your data', 'cohf-child' ); ?></a></p>
