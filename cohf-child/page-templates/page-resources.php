<?php
/**
 * Template Name: Resources
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;
get_header();

$search = isset( $_GET['rq'] ) ? sanitize_text_field( wp_unslash( $_GET['rq'] ) ) : '';
$types  = get_terms( array( 'taxonomy' => 'cohf_content_type', 'hide_empty' => true ) );

$query_args = array(
	'post_type'      => array( 'cohf_report', 'cohf_resource', 'cohf_news' ),
	'posts_per_page' => 20,
	'paged'          => max( 1, (int) get_query_var( 'paged' ) ),
);
if ( $search ) {
	$query_args['s'] = $search;
}
$library = new WP_Query( $query_args );
?>
<main id="main-content" tabindex="-1">

	<?php
	get_template_part( 'template-parts/page-hero', null, array(
		'image'   => 'hero-accountability',
		'eyebrow' => __( 'Knowledge and accountability', 'cohf-child' ),
		'title'   => __( 'Resources &amp; Updates', 'cohf-child' ),
		'text'    => __( 'A growing library for reports, strategic documents, policies, stories, news and programme updates.', 'cohf-child' ),
	) );
	?>

	<!-- Categories -->
	<section>
		<div class="container">
			<div class="section-head">
				<div>
					<div class="kicker"><?php esc_html_e( 'What you will find here', 'cohf-child' ); ?></div>
					<h2><?php esc_html_e( 'Three kinds of document.', 'cohf-child' ); ?></h2>
				</div>
				<p><?php esc_html_e( 'Documents are published as each is finalised and approved. Partners and institutions can request anything not yet published.', 'cohf-child' ); ?></p>
			</div>

			<div class="grid">
				<article class="card">
					<div class="card-body">
						<div class="kicker"><?php esc_html_e( 'Strategic framework', 'cohf-child' ); ?></div>
						<h3><?php esc_html_e( 'Master Institutional Profile &amp; Strategic Programme Framework 2026-2030', 'cohf-child' ); ?></h3>
						<p><?php esc_html_e( 'The Foundation\'s five-year direction and programme framework.', 'cohf-child' ); ?></p>
					</div>
				</article>
				<article class="card">
					<div class="card-body">
						<div class="kicker"><?php esc_html_e( 'Reports', 'cohf-child' ); ?></div>
						<h3><?php esc_html_e( 'Programme &amp; Impact Reports', 'cohf-child' ); ?></h3>
						<p><?php esc_html_e( 'Published here with dates and downloads as each reporting cycle completes.', 'cohf-child' ); ?></p>
					</div>
				</article>
				<article class="card">
					<div class="card-body">
						<div class="kicker"><?php esc_html_e( 'Policies', 'cohf-child' ); ?></div>
						<h3><?php esc_html_e( 'Safeguarding &amp; Accountability', 'cohf-child' ); ?></h3>
						<p><?php esc_html_e( 'Child safeguarding, data protection, financial management and related policies.', 'cohf-child' ); ?></p>
					</div>
				</article>
			</div>
		</div>
	</section>

	<!-- Library -->
	<section class="cream">
		<div class="container">
			<div class="section-head">
				<div>
					<div class="kicker"><?php esc_html_e( 'Document library', 'cohf-child' ); ?></div>
					<h2><?php esc_html_e( 'Search the library.', 'cohf-child' ); ?></h2>
				</div>
				<form class="search-form" method="get" action="<?php echo esc_url( get_permalink() ); ?>">
					<label class="search-form__label" for="resource-search"><?php esc_html_e( 'Search resources', 'cohf-child' ); ?></label>
					<div class="search-form__row">
						<input class="search-form__input" type="search" id="resource-search" name="rq" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Title or keyword', 'cohf-child' ); ?>">
						<button class="btn dark" type="submit"><?php esc_html_e( 'Search', 'cohf-child' ); ?></button>
					</div>
				</form>
			</div>

			<?php if ( ! empty( $types ) && ! is_wp_error( $types ) ) : ?>
				<div class="filter-bar" data-filter-group data-filter-target="#resource-list" data-filter-status="#resource-filter-status" role="group" aria-label="<?php esc_attr_e( 'Filter resources by type', 'cohf-child' ); ?>">
					<button type="button" data-filter="all" aria-pressed="true"><?php esc_html_e( 'All', 'cohf-child' ); ?></button>
					<?php foreach ( $types as $type ) : ?>
						<button type="button" data-filter="<?php echo esc_attr( $type->slug ); ?>" aria-pressed="false"><?php echo esc_html( $type->name ); ?></button>
					<?php endforeach; ?>
				</div>
				<p id="resource-filter-status" class="screen-reader-text" role="status"></p>
			<?php endif; ?>

			<div id="resource-list">
				<?php if ( $library->have_posts() ) : ?>
					<?php
					while ( $library->have_posts() ) :
						$library->the_post();
						$terms     = get_the_terms( get_the_ID(), 'cohf_content_type' );
						$slugs     = ( $terms && ! is_wp_error( $terms ) ) ? implode( ' ', wp_list_pluck( $terms, 'slug' ) ) : '';
						$file      = cohf_field( 'file_url' );
						$size      = cohf_field( 'file_size' );
						$post_type = get_post_type_object( get_post_type() );
						?>
						<article class="resource-row" data-filter-value="<?php echo cohf_attr( $slugs ); ?>">
							<div>
								<p class="resource-row__type"><?php echo esc_html( $post_type ? $post_type->labels->singular_name : '' ); ?></p>
								<h3 class="resource-row__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<p class="resource-row__meta">
									<?php echo esc_html( get_the_date() ); ?><?php echo $size ? ' - ' . esc_html( $size ) : ''; ?>
								</p>
							</div>
							<div>
								<a class="btn outline" href="<?php echo esc_url( $file ? $file : get_permalink() ); ?>"<?php echo $file ? ' download' : ''; ?>>
									<?php cohf_link_context( $file ? __( 'Download', 'cohf-child' ) : __( 'Read', 'cohf-child' ), get_the_title() ); ?>
								</a>
							</div>
						</article>
						<?php
					endwhile;
					?>
					<div class="pagination">
						<?php
						echo wp_kses_post( paginate_links( array(
							'total'   => (int) $library->max_num_pages,
							'current' => max( 1, (int) get_query_var( 'paged' ) ),
							'type'    => 'list',
						) ) );
						?>
					</div>
					<?php wp_reset_postdata(); ?>
				<?php else : ?>
					<p class="partner-empty">
						<?php esc_html_e( 'No documents have been published yet. Annual reports, programme reports, strategic documents and policies will appear here as they are finalised and approved.', 'cohf-child' ); ?>
					</p>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<?php
	get_template_part( 'template-parts/cta', null, array(
		'title'         => __( 'Need a document we have not published?', 'cohf-child' ),
		'text'          => __( 'Partners and institutions can request our Constitution, strategic framework, policies and programme documentation directly from the Foundation.', 'cohf-child' ),
		'primary_label' => __( 'Contact Us', 'cohf-child' ),
		'primary_url'   => cohf_page_url( 'page-templates/page-contact.php' ),
	) );
	?>

</main>
<?php get_footer();
