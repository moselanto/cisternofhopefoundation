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
