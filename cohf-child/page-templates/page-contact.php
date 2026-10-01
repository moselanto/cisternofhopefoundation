<?php
/**
 * Template Name: Contact
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;
get_header();
$org = cohf_org();
?>
<main id="main-content" tabindex="-1">

	<?php
	get_template_part( 'template-parts/page-hero', null, array(
		'image'   => 'story-community',
		'eyebrow' => __( 'Start a conversation', 'cohf-child' ),
		'title'   => __( 'Contact Cistern of Hope Foundation', 'cohf-child' ),
		'text'    => __( 'Whether you want to partner, support a programme, volunteer or learn more, we would be glad to hear from you.', 'cohf-child' ),
	) );
	?>

	<?php if ( cohf_page_body_is_blocks() ) : cohf_the_page_body(); else : ?>

	<section id="enquire">
		<div class="container story">
			<div>
				<div class="kicker"><?php esc_html_e( 'Contact details', 'cohf-child' ); ?></div>
				<h2><?php esc_html_e( 'Let\'s build lasting change together.', 'cohf-child' ); ?></h2>

				<p class="contact-lede"><?php esc_html_e( 'Reach us whichever way suits you. For anything urgent, a call or WhatsApp is quickest.', 'cohf-child' ); ?></p>
				<?php
				$cohf_wa_src = ( isset( $org['whatsapp'] ) && '' !== $org['whatsapp'] ) ? $org['whatsapp'] : $org['phone'];
				$cohf_wa     = preg_replace( '/[^0-9]/', '', (string) $cohf_wa_src );
				$cohf_cards  = array(
					array( 'tel:' . preg_replace( '/[^0-9+]/', '', $org['phone'] ), __( 'Call us', 'cohf-child' ), $org['phone'], '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2Z"/>', '' ),
					array( $cohf_wa ? 'https://wa.me/' . $cohf_wa : '', __( 'WhatsApp', 'cohf-child' ), __( 'Chat with us', 'cohf-child' ), '<path d="M21 12a9 9 0 0 1-13.4 7.8L3 21l1.2-4.4A9 9 0 1 1 21 12Z"/>', 'wa' ),
					array( 'mailto:' . $org['email'], __( 'Email', 'cohf-child' ), $org['email'], '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/>', '' ),
					array( '', __( 'Postal address', 'cohf-child' ), $org['address'], '<path d="M4 7h16v12H4z"/><path d="M4 7l8 6 8-6"/>', '' ),
					array( '', __( 'Where we work', 'cohf-child' ), __( 'Uthiru, Nairobi, and communities across Kenya.', 'cohf-child' ), '<path d="M12 21s-7-6.2-7-11.5A7 7 0 0 1 19 9.5C19 14.8 12 21 12 21Z"/><circle cx="12" cy="9.5" r="2.5"/>', '' ),
				);
				?>
				<ul class="contact-cards">
					<?php foreach ( $cohf_cards as $cohf_card ) : ?>
						<?php $cohf_tag = '' !== $cohf_card[0] ? 'a' : 'div'; ?>
						<li>
							<<?php echo esc_html( $cohf_tag ); ?> class="contact-card<?php echo 'wa' === $cohf_card[4] ? ' contact-card--wa' : ''; ?>"<?php if ( 'a' === $cohf_tag ) : ?> href="<?php echo esc_url( $cohf_card[0] ); ?>"<?php echo 'wa' === $cohf_card[4] ? ' target="_blank" rel="noopener"' : ''; ?><?php endif; ?>>
								<span class="contact-card__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><?php echo $cohf_card[3]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG paths. ?></svg></span>
								<span class="contact-card__body"><small><?php echo esc_html( $cohf_card[1] ); ?></small><strong><?php echo esc_html( $cohf_card[2] ); ?></strong></span>
							</<?php echo esc_html( $cohf_tag ); ?>>
						</li>
					<?php endforeach; ?>
				</ul>

				<p class="callout stack-md contact-concern">
					<b><?php esc_html_e( 'Raising a concern', 'cohf-child' ); ?></b><br>
					<?php esc_html_e( 'To raise a safeguarding concern or make a complaint, select "Complaint or feedback" in the form. Concerns are treated seriously and confidentially.', 'cohf-child' ); ?>
				</p>
			</div>

			<?php get_template_part( 'template-parts/enquiry-form', null, array( 'default_type' => 'general' ) ); ?>
		</div>
	</section>

	<?php if ( trim( get_the_content() ) ) : ?>
		<section class="cream"><div class="container prose"><?php while ( have_posts() ) { the_post(); the_content(); } ?></div></section>
	<?php endif; ?>

	<?php endif; ?>
</main>
<?php get_footer();
