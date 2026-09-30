<?php
/**
 * Site footer.
 *
 * Markup mirrors the approved prototype exactly: .container.foot with four
 * columns, then .container.copyright.
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;

$org  = cohf_org();
$acct = cohf_page_url( 'page-templates/page-accountability.php' );
?>
<footer>
	<div class="container foot">
		<div>
			<div class="brand">
				<?php cohf_logo_mark( 44 ); ?>
				<span><?php echo esc_html( strtoupper( $org['name'] ) ); ?><small><?php echo esc_html( strtoupper( rtrim( $org['motto'], '.' ) ) ); ?></small></span>
			</div>
			<p><?php echo esc_html( $org['descriptor'] ); ?></p>
		</div>

		<div>
			<h3><?php esc_html_e( 'Explore', 'cohf-child' ); ?></h3>
			<a href="<?php echo esc_url( cohf_page_url( 'page-templates/page-about.php' ) ); ?>"><?php esc_html_e( 'About', 'cohf-child' ); ?></a>
			<a href="<?php echo esc_url( cohf_page_url( 'page-templates/page-programmes.php' ) ); ?>"><?php esc_html_e( 'Programmes', 'cohf-child' ); ?></a>
			<a href="<?php echo esc_url( cohf_page_url( 'page-templates/page-impact.php' ) ); ?>"><?php esc_html_e( 'Impact', 'cohf-child' ); ?></a>
			<?php $cohf_footer_stories = get_post_type_archive_link( 'cohf_story' ); ?>
			<?php if ( $cohf_footer_stories ) : ?>
				<a href="<?php echo esc_url( $cohf_footer_stories ); ?>"><?php esc_html_e( 'Impact Stories', 'cohf-child' ); ?></a>
			<?php endif; ?>
			<a href="<?php echo esc_url( cohf_page_url( 'page-templates/page-approach.php' ) ); ?>"><?php esc_html_e( 'Our Approach', 'cohf-child' ); ?></a>
		</div>

		<div>
			<h3><?php esc_html_e( 'Get involved', 'cohf-child' ); ?></h3>
			<a href="<?php echo esc_url( cohf_page_url( 'page-templates/page-support.php' ) ); ?>"><?php esc_html_e( 'Support Our Work', 'cohf-child' ); ?></a>
			<a href="<?php echo esc_url( cohf_page_url( 'page-templates/page-partners.php' ) ); ?>"><?php esc_html_e( 'Partner With Us', 'cohf-child' ); ?></a>
			<a href="<?php echo esc_url( cohf_page_url( 'page-templates/page-get-involved.php' ) ); ?>"><?php esc_html_e( 'Volunteer', 'cohf-child' ); ?></a>
			<a href="<?php echo esc_url( cohf_page_url( 'page-templates/page-resources.php' ) ); ?>"><?php esc_html_e( 'Resources', 'cohf-child' ); ?></a>
		</div>

		<div>
			<h3><?php esc_html_e( 'Contact', 'cohf-child' ); ?></h3>
			<a href="<?php echo esc_url( cohf_page_url( 'page-templates/page-contact.php' ) ); ?>"><?php echo esc_html( $org['address'] ); ?></a>
			<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $org['phone'] ) ); ?>"><?php echo esc_html( $org['phone'] ); ?></a>
			<a href="mailto:<?php echo esc_attr( $org['email'] ); ?>"><?php echo esc_html( $org['email'] ); ?></a>
			<?php
			// Social links appear only once a URL is saved under
			// Foundation > Organisation details > Social media.
			$cohf_social = function_exists( 'cohf_social_links' ) ? cohf_social_links() : array();
			if ( $cohf_social ) :
				$cohf_icons = array(
					'facebook' => '<path d="M14 8h3V4h-3a4 4 0 0 0-4 4v2H8v4h2v6h4v-6h3l1-4h-4V8Z"/>',
					'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1"/>',
					'x' => '<path d="M4 4l16 16M20 4 4 20"/>',
					'linkedin' => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M8 10v7M8 7v.01M12 17v-4a2 2 0 0 1 4 0v4M12 10v7"/>',
					'youtube' => '<rect x="2" y="5" width="20" height="14" rx="4"/><path d="m10 9 5 3-5 3V9Z"/>',
					'tiktok' => '<path d="M14 3v11a3 3 0 1 1-3-3M14 3c0 3 2 5 5 5"/>',
				);
				?>
				<ul class="foot-social" aria-label="<?php esc_attr_e( 'Follow the Foundation', 'cohf-child' ); ?>">
					<?php foreach ( $cohf_social as $cohf_key => $cohf_link ) : ?>
						<li>
							<a href="<?php echo esc_url( $cohf_link['url'] ); ?>" target="_blank" rel="noopener noreferrer me">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><?php echo isset( $cohf_icons[ $cohf_key ] ) ? $cohf_icons[ $cohf_key ] : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG paths defined above. ?></svg>
								<span class="screen-reader-text"><?php echo esc_html( $cohf_link['label'] ); ?></span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>

	<div class="container copyright">
		<?php
		printf(
			/* translators: 1: year, 2: organisation name. */
			esc_html__( '%1$s %2$s. Together for a lasting change.', 'cohf-child' ),
			esc_html( html_entity_decode( '&copy;', ENT_QUOTES, 'UTF-8' ) . ' ' . gmdate( 'Y' ) ),
			esc_html( $org['name'] )
		);
		?>
		<span aria-hidden="true"> &middot; </span>
		<a href="<?php echo esc_url( function_exists( 'cohf_privacy_url' ) ? cohf_privacy_url() : $acct . '#data-protection' ); ?>"><?php esc_html_e( 'Privacy Policy', 'cohf-child' ); ?></a>
		<span aria-hidden="true"> &middot; </span>
		<?php foreach ( array( 'terms-of-use' => __( 'Terms of Use', 'cohf-child' ), 'donation-policy' => __( 'Donation Policy', 'cohf-child' ) ) as $cohf_slug => $cohf_label ) : ?>
			<?php $cohf_url = function_exists( 'cohf_legal_url' ) ? cohf_legal_url( $cohf_slug ) : ''; ?>
			<?php if ( $cohf_url ) : ?>
				<a href="<?php echo esc_url( $cohf_url ); ?>"><?php echo esc_html( $cohf_label ); ?></a>
				<span aria-hidden="true"> &middot; </span>
			<?php endif; ?>
		<?php endforeach; ?>
		<a href="<?php echo esc_url( $acct ); ?>#safeguarding"><?php esc_html_e( 'Safeguarding', 'cohf-child' ); ?></a>
		<span aria-hidden="true"> &middot; </span>
		<a href="<?php echo esc_url( $acct ); ?>#complaints"><?php esc_html_e( 'Complaints &amp; Feedback', 'cohf-child' ); ?></a>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
