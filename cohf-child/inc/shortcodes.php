<?php
/**
 * Shortcodes for the dynamic parts of a page.
 *
 * Most page copy is prose and converts cleanly to blocks. Some sections are
 * not copy at all - the enquiry form carries a nonce and a honeypot, the
 * partner list is a live query, the programme grid follows the Programmes
 * content type. Converting those to static blocks would silently break them.
 *
 * These shortcodes let a block-built page keep the working section. An editor
 * places it in the editor like any other block and the theme renders the real
 * thing on the front end.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render a template part and return it as a string.
 *
 * @param string $slug Template part slug, relative to the theme.
 * @param array  $args Arguments passed to the part.
 * @return string
 */
function cohf_shortcode_part( $slug, $args = array() ) {
	ob_start();
	get_template_part( $slug, null, $args );
	return (string) ob_get_clean();
}

/**
 * [cohf_enquiry_form type="partnership"]
 *
 * @param array $atts Attributes.
 * @return string
 */
function cohf_sc_enquiry_form( $atts ) {
	$atts = shortcode_atts( array( 'type' => 'general' ), $atts, 'cohf_enquiry_form' );

	$types = function_exists( 'cohf_enquiry_types' ) ? cohf_enquiry_types() : array();
	$type  = sanitize_key( $atts['type'] );

	// An unknown type would render a form with nothing preselected.
	if ( $types && ! isset( $types[ $type ] ) ) {
		$type = 'general';
	}

	return cohf_shortcode_part( 'template-parts/enquiry-form', array( 'default_type' => $type ) );
}
add_shortcode( 'cohf_enquiry_form', 'cohf_sc_enquiry_form' );

/**
 * [cohf_partners] - confirmed partners, or the honest empty state.
 *
 * @return string
 */
function cohf_sc_partners() {
	return cohf_shortcode_part( 'template-parts/partner-section' );
}
add_shortcode( 'cohf_partners', 'cohf_sc_partners' );

/**
 * [cohf_image key="programme-09"] - an image from the theme's own library.
 *
 * @param array $atts Attributes.
 * @return string
 */
function cohf_sc_image( $atts ) {
	$atts = shortcode_atts( array(
		'key'   => '',
		'sizes' => '(max-width: 60em) 100vw, 50vw',
	), $atts, 'cohf_image' );

	if ( ! $atts['key'] || ! function_exists( 'cohf_the_image' ) ) {
		return '';
	}

	ob_start();
	cohf_the_image( sanitize_key( $atts['key'] ), array( 'sizes' => $atts['sizes'] ) );
	return (string) ob_get_clean();
}
add_shortcode( 'cohf_image', 'cohf_sc_image' );

/**
 * Simple template-part shortcodes with no arguments.
 *
 * Registered in a loop because they differ only by which part they render.
 *
 * @return array<string,string> Shortcode tag => template part slug.
 */
function cohf_simple_part_shortcodes() {
	return array(
		'cohf_impact_numbers'   => 'template-parts/numbers',
		'cohf_timeline'         => 'template-parts/timeline',
		'cohf_approach'         => 'template-parts/approach',
		'cohf_purpose'          => 'template-parts/purpose',
		'cohf_theory_of_change' => 'template-parts/theory-of-change',
		'cohf_giving_form'      => 'template-parts/giving-form',
	);
}

foreach ( cohf_simple_part_shortcodes() as $cohf_tag => $cohf_slug ) {
	add_shortcode(
		$cohf_tag,
		static function () use ( $cohf_slug ) {
			return cohf_shortcode_part( $cohf_slug );
		}
	);
}
unset( $cohf_tag, $cohf_slug );

/**
 * [cohf_cta title="" text=""] - the closing call to action.
 *
 * Takes attributes because Programmes overrides the wording. Only non-empty
 * attributes are passed through, so a bare [cohf_cta] keeps the template
 * part's own defaults rather than blanking the heading.
 *
 * @param array $atts Attributes.
 * @return string
 */
function cohf_sc_cta( $atts ) {
	$atts = shortcode_atts( array(
		'title'           => '',
		'text'            => '',
		'primary_label'   => '',
		'primary_page'    => '',
		'primary_key'     => '',
		'secondary_label' => '',
		'secondary_page'  => '',
	), $atts, 'cohf_cta' );

	/*
	 * Targets are given as template files, or as keys into cohf_cta_links(),
	 * rather than as URLs. Both resolve at render time; a pasted URL would
	 * break the moment a page is renamed or the permalinks change.
	 */
	$links = function_exists( 'cohf_cta_links' ) ? cohf_cta_links() : array();

	$resolve = static function ( $page, $key ) use ( $links ) {
		if ( $key && isset( $links[ $key ] ) ) {
			return $links[ $key ];
		}

		if ( $page && function_exists( 'cohf_page_url' ) ) {
			return cohf_page_url( $page );
		}

		return '';
	};

	$primary   = $resolve( $atts['primary_page'], $atts['primary_key'] );
	$secondary = $resolve( $atts['secondary_page'], '' );

	unset( $atts['primary_page'], $atts['primary_key'], $atts['secondary_page'] );

	if ( $primary ) {
		$atts['primary_url'] = $primary;
	}

	if ( $secondary ) {
		$atts['secondary_url'] = $secondary;
	}

	$args = array_filter( $atts, static function ( $value ) {
		return '' !== trim( (string) $value );
	} );

	return cohf_shortcode_part( 'template-parts/cta', $args );
}
add_shortcode( 'cohf_cta', 'cohf_sc_cta' );

/**
 * [cohf_contact_details] - postal address, phone, email and location.
 *
 * Seeded into the Contact page rather than flattened into text on purpose.
 * These values already have one home, Foundation > Organisation details, and
 * copying them into block content would mean a phone number change silently
 * failing to reach the Contact page.
 *
 * @return string
 */
function cohf_sc_contact_details() {
	if ( ! function_exists( 'cohf_org' ) ) {
		return '';
	}

	$org  = cohf_org();
	$tel  = preg_replace( '/[^0-9+]/', '', $org['phone'] );
	$rows = array();

	$rows[] = '<p><b>' . esc_html( strtoupper( $org['name'] ) ) . '</b></p>';

	$rows[] = '<p><b>' . esc_html__( 'Postal address', 'cohf-child' ) . '</b><br>'
		. esc_html( $org['address'] ) . '</p>';

	$rows[] = '<p><b>' . esc_html__( 'Phone', 'cohf-child' ) . '</b><br>'
		. '<a href="tel:' . esc_attr( $tel ) . '">' . esc_html( $org['phone'] ) . '</a></p>';

	$rows[] = '<p><b>' . esc_html__( 'Email', 'cohf-child' ) . '</b><br>'
		. '<a href="mailto:' . esc_attr( $org['email'] ) . '">' . esc_html( $org['email'] ) . '</a></p>';

	$rows[] = '<p><b>' . esc_html__( 'Where we work', 'cohf-child' ) . '</b><br>'
		. esc_html__( 'Uthiru, Nairobi, and communities across Kenya.', 'cohf-child' ) . '</p>';

	return implode( "\n", $rows );
}
add_shortcode( 'cohf_contact_details', 'cohf_sc_contact_details' );

/**
 * [cohf_giving_thanks] - the post-payment confirmation.
 *
 * Paystack returns the donor to this page with ?giving=thank-you and a
 * reference. The template rendered that state above the block insertion
 * point, so a seeded Support page would have dropped the confirmation and
 * left donors with no acknowledgement. Renders nothing otherwise.
 *
 * @return string
 */
function cohf_sc_giving_thanks() {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only return URL from the payment provider.
	$thanks = isset( $_GET['giving'] ) && 'thank-you' === $_GET['giving'];

	if ( ! $thanks ) {
		return '';
	}

	$ref = isset( $_GET['ref'] ) ? sanitize_text_field( wp_unslash( $_GET['ref'] ) ) : '';
	// phpcs:enable WordPress.Security.NonceVerification.Recommended

	// Carries its own section band, so a seeded page shows nothing at all
	// until a donor actually returns from payment.
	ob_start();
	?>
	<section class="sage">
		<div class="container">
			<div class="give-thanks" role="status">
				<h2 class="sec-statement"><?php esc_html_e( 'Thank you. Your gift has been received.', 'cohf-child' ); ?></h2>
				<p class="sec-lede"><?php esc_html_e( 'A receipt is on its way to the email address you gave. If anything looks wrong, contact us and we will put it right.', 'cohf-child' ); ?></p>
				<?php if ( $ref ) : ?>
					<p class="give-thanks__ref">
						<?php esc_html_e( 'Reference:', 'cohf-child' ); ?>
						<code><?php echo esc_html( $ref ); ?></code>
					</p>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}
add_shortcode( 'cohf_giving_thanks', 'cohf_sc_giving_thanks' );

/**
 * [cohf_section_nav items="#journey|Five-year journey;;#objectives|Objectives"]
 *
 * Anchor links for a long page. Pairs are separated by ";;" and each pair is
 * anchor, a pipe, then the label, so labels containing a comma or a single
 * semicolon survive intact.
 *
 * @param array $atts Attributes.
 * @return string
 */
function cohf_sc_section_nav( $atts ) {
	$atts = shortcode_atts( array( 'items' => '' ), $atts, 'cohf_section_nav' );

	$sections = array();

	foreach ( array_filter( explode( ';;', (string) $atts['items'] ) ) as $pair ) {
		if ( false === strpos( $pair, '|' ) ) {
			continue;
		}

		list( $anchor, $label ) = explode( '|', $pair, 2 );

		$anchor = trim( $anchor );
		$label  = trim( $label );

		if ( '' !== $anchor && '' !== $label ) {
			$sections[ $anchor ] = $label;
		}
	}

	if ( ! $sections ) {
		return '';
	}

	return cohf_shortcode_part( 'template-parts/section-nav', array( 'sections' => $sections ) );
}
add_shortcode( 'cohf_section_nav', 'cohf_sc_section_nav' );

/**
 * [cohf_stories count="3"] - recent community stories.
 *
 * Mirrors the home page behaviour: when no stories exist, visitors see
 * nothing at all and only signed-in editors get the note explaining where
 * to add them. An empty section on a live home page helps nobody.
 *
 * @param array $atts Attributes.
 * @return string
 */
function cohf_sc_stories( $atts ) {
	$atts = shortcode_atts( array( 'count' => 3 ), $atts, 'cohf_stories' );

	$stories = new WP_Query( array(
		'post_type'      => 'cohf_story',
		'posts_per_page' => max( 1, (int) $atts['count'] ),
		'no_found_rows'  => true,
	) );

	if ( ! $stories->have_posts() ) {
		wp_reset_postdata();

		if ( ! current_user_can( 'edit_posts' ) ) {
			return '';
		}

		return sprintf(
			'<p class="partner-empty">%s</p>',
			esc_html__( 'Community stories will appear here once the first story is published. Add them under Stories in the WordPress admin. Only signed-in editors can see this message.', 'cohf-child' )
		);
	}

	ob_start();
	echo '<div class="grid">';
	while ( $stories->have_posts() ) {
		$stories->the_post();
		get_template_part( 'template-parts/story-card' );
	}
	echo '</div>';
	wp_reset_postdata();

	return (string) ob_get_clean();
}
add_shortcode( 'cohf_stories', 'cohf_sc_stories' );

/**
 * [cohf_leadership] - the three governance tiers and the profile panel.
 *
 * Leadership is a live query over the Leadership content type, split into
 * Executive, Board and Management tiers. The profile panel markup travels
 * with it because leadership.js expects to find it in the document; seeding
 * the tiers without the panel would leave every profile link inert.
 *
 * @return string
 */
function cohf_sc_leadership() {
	$any = new WP_Query( array(
		'post_type'      => 'cohf_leader',
		'posts_per_page' => 1,
		'fields'         => 'ids',
		'no_found_rows'  => true,
	) );

	$has_leaders = $any->have_posts();
	wp_reset_postdata();

	$tiers = array(
		array(
			'group'     => 'executive',
			'number'    => '01',
			'title'     => __( 'Executive Leadership', 'cohf-child' ),
			'statement' => __( 'Direction and stewardship.', 'cohf-child' ),
			'intro'     => __( 'Overall direction, strategy and day-to-day leadership of the Foundation.', 'cohf-child' ),
			'modifier'  => 'feature',
		),
		array(
			'group'     => 'board',
			'number'    => '02',
			'title'     => __( 'Board of Directors', 'cohf-child' ),
			'statement' => __( 'Governance and oversight.', 'cohf-child' ),
			'intro'     => __( 'Independent governance, oversight and accountability, meeting quarterly.', 'cohf-child' ),
			'modifier'  => 'board',
		),
		array(
			'group'     => 'management',
			'number'    => '03',
			'title'     => __( 'Management and Operations', 'cohf-child' ),
			'statement' => __( 'The people moving the work forward.', 'cohf-child' ),
			'intro'     => __( 'The team delivering programmes and running the Foundation day to day.', 'cohf-child' ),
			'modifier'  => '',
		),
	);

	ob_start();

	if ( $has_leaders ) {
		foreach ( $tiers as $tier ) {
			get_template_part( 'template-parts/leadership-tier', null, $tier );
		}
	} else {
		printf(
			'<p class="partner-empty">%s</p>',
			esc_html__( 'Leadership records are created from the Leadership menu in the WordPress admin. Run the one-time setup to add the current team.', 'cohf-child' )
		);
	}
	?>

	<div class="leader-panel" id="cohf-leader-panel" hidden>
		<div class="leader-panel__scrim" data-leader-close></div>
		<div class="leader-panel__dialog" role="dialog" aria-modal="true" aria-labelledby="cohf-leader-panel-name">
			<button type="button" class="leader-panel__close" data-leader-close>
				<span aria-hidden="true">&times;</span>
				<span class="screen-reader-text"><?php esc_html_e( 'Close profile', 'cohf-child' ); ?></span>
			</button>
			<div class="leader-panel__grid">
				<div class="leader-panel__media">
					<img class="leader-panel__img" src="" alt="" hidden>
					<span class="leader-panel__monogram" aria-hidden="true"></span>
				</div>
				<div class="leader-panel__body">
					<h2 class="leader-panel__name" id="cohf-leader-panel-name"></h2>
					<p class="leader-panel__role"></p>
					<div class="leader-panel__bio"></div>
				</div>
			</div>
		</div>
	</div>
	<?php
	return (string) ob_get_clean();
}
add_shortcode( 'cohf_leadership', 'cohf_sc_leadership' );

/**
 * [cohf_resource_library] - search, filter, list and pagination.
 *
 * The document library is the least block-like section on the site: a search
 * form, a taxonomy filter bar, a paginated query across three content types
 * and a per-row download link. Seeding it as static blocks would turn a
 * working library into a snapshot that never updates, so it stays whole.
 *
 * @param array $atts Attributes.
 * @return string
 */
function cohf_sc_resource_library( $atts ) {
	$atts = shortcode_atts( array( 'per_page' => 20 ), $atts, 'cohf_resource_library' );

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- public read-only search.
	$search = isset( $_GET['rq'] ) ? sanitize_text_field( wp_unslash( $_GET['rq'] ) ) : '';
	$paged  = max( 1, (int) get_query_var( 'paged' ) );

	$query_args = array(
		'post_type'      => array( 'cohf_report', 'cohf_resource', 'cohf_news' ),
		'posts_per_page' => max( 1, (int) $atts['per_page'] ),
		'paged'          => $paged,
	);

	if ( $search ) {
		$query_args['s'] = $search;
	}

	$library = new WP_Query( $query_args );
	$types   = get_terms( array(
		'taxonomy'   => 'cohf_content_type',
		'hide_empty' => true,
	) );

	ob_start();
	?>
	<form class="search-form" method="get" action="<?php echo esc_url( get_permalink() ); ?>">
		<label class="search-form__label" for="resource-search"><?php esc_html_e( 'Search resources', 'cohf-child' ); ?></label>
		<div class="search-form__row">
			<input class="search-form__input" type="search" id="resource-search" name="rq" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php esc_attr_e( 'Title or keyword', 'cohf-child' ); ?>">
			<button class="btn dark" type="submit"><?php esc_html_e( 'Search', 'cohf-child' ); ?></button>
		</div>
	</form>

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
				$file      = function_exists( 'cohf_field' ) ? cohf_field( 'file_url' ) : '';
				$size      = function_exists( 'cohf_field' ) ? cohf_field( 'file_size' ) : '';
				$post_type = get_post_type_object( get_post_type() );
				?>
				<article class="resource-row" data-filter-value="<?php echo function_exists( 'cohf_attr' ) ? cohf_attr( $slugs ) : esc_attr( $slugs ); ?>">
					<div>
						<p class="resource-row__type"><?php echo esc_html( $post_type ? $post_type->labels->singular_name : '' ); ?></p>
						<h3 class="resource-row__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						<p class="resource-row__meta">
							<?php echo esc_html( get_the_date() ); ?><?php echo $size ? ' - ' . esc_html( $size ) : ''; ?>
						</p>
					</div>
					<div>
						<a class="btn outline" href="<?php echo esc_url( $file ? $file : get_permalink() ); ?>"<?php echo $file ? ' download' : ''; ?>>
							<?php
							$label = $file ? __( 'Download', 'cohf-child' ) : __( 'Read', 'cohf-child' );

							if ( function_exists( 'cohf_link_context' ) ) {
								cohf_link_context( $label, get_the_title() );
							} else {
								echo esc_html( $label );
							}
							?>
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
					'current' => $paged,
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
	<?php
	return (string) ob_get_clean();
}
add_shortcode( 'cohf_resource_library', 'cohf_sc_resource_library' );

/**
 * [cohf_programmes count="12"] - the programme card grid.
 *
 * @param array $atts Attributes.
 * @return string
 */
function cohf_sc_programmes( $atts ) {
	$atts = shortcode_atts( array(
		'count'  => 12,
		'filter' => 'no',
	), $atts, 'cohf_programmes' );

	$query = new WP_Query( array(
		'post_type'      => 'cohf_programme',
		'posts_per_page' => max( 1, (int) $atts['count'] ),
		'orderby'        => array(
			'menu_order' => 'ASC',
			'title'      => 'ASC',
		),
		'no_found_rows'  => true,
	) );

	if ( ! $query->have_posts() ) {
		return '';
	}

	// Unique ids so two grids on one page cannot cross-wire their filters.
	static $instance = 0;
	++$instance;

	$list_id   = 'programme-list-' . $instance;
	$status_id = 'programme-filter-status-' . $instance;
	$want_bar  = in_array( strtolower( (string) $atts['filter'] ), array( 'yes', 'true', '1' ), true );

	ob_start();

	if ( $want_bar ) {
		$audiences = get_terms( array(
			'taxonomy'   => 'cohf_audience',
			'hide_empty' => true,
		) );

		if ( ! empty( $audiences ) && ! is_wp_error( $audiences ) ) {
			printf(
				'<div class="filter-bar" data-filter-group data-filter-target="#%1$s" data-filter-status="#%2$s" role="group" aria-label="%3$s">',
				esc_attr( $list_id ),
				esc_attr( $status_id ),
				esc_attr__( 'Filter programmes by audience', 'cohf-child' )
			);

			printf(
				'<button type="button" data-filter="all" aria-pressed="true">%s</button>',
				esc_html__( 'All programmes', 'cohf-child' )
			);

			foreach ( $audiences as $audience ) {
				printf(
					'<button type="button" data-filter="%1$s" aria-pressed="false">%2$s</button>',
					esc_attr( $audience->slug ),
					esc_html( $audience->name )
				);
			}

			echo '</div>';

			printf(
				'<p id="%s" class="screen-reader-text" role="status"></p>',
				esc_attr( $status_id )
			);
		}
	}

	printf( '<div class="grid" id="%s">', esc_attr( $list_id ) );
	while ( $query->have_posts() ) {
		$query->the_post();
		get_template_part( 'template-parts/programme-card' );
	}
	echo '</div>';
	wp_reset_postdata();

	return (string) ob_get_clean();
}
add_shortcode( 'cohf_programmes', 'cohf_sc_programmes' );


/**
 * 14.7.1: contact details for use inside page and post content.
 *
 * [cohf_phone] [cohf_whatsapp] [cohf_email] print the value saved under
 * Foundation > Organisation details, so text typed in the editor follows
 * that screen instead of freezing a number in place. Add link="yes" to
 * print a tap-to-call, WhatsApp or email link.
 *
 * @param array|string $atts Shortcode attributes.
 * @param string       $content Unused.
 * @param string       $tag Shortcode name.
 * @return string
 */
function cohf_sc_org_contact( $atts, $content = '', $tag = '' ) {
	if ( ! function_exists( 'cohf_org' ) ) {
		return '';
	}
	$atts = shortcode_atts( array( 'link' => 'no' ), $atts, $tag );
	$org  = cohf_org();
	$link = in_array( strtolower( (string) $atts['link'] ), array( 'yes', '1', 'true' ), true );

	if ( 'cohf_email' === $tag ) {
		$value = isset( $org['email'] ) ? (string) $org['email'] : '';
		if ( '' === $value ) {
			return '';
		}
		return $link ? '<a href="mailto:' . esc_attr( $value ) . '">' . esc_html( $value ) . '</a>' : esc_html( $value );
	}

	$phone = isset( $org['phone'] ) ? (string) $org['phone'] : '';
	if ( 'cohf_whatsapp' === $tag ) {
		$value = ! empty( $org['whatsapp'] ) ? (string) $org['whatsapp'] : $phone;
		if ( '' === $value ) {
			return '';
		}
		$digits = preg_replace( '/[^0-9]/', '', $value );
		return $link ? '<a href="' . esc_url( 'https://wa.me/' . $digits ) . '" target="_blank" rel="noopener">' . esc_html( $value ) . '</a>' : esc_html( $value );
	}

	if ( '' === $phone ) {
		return '';
	}
	$tel = preg_replace( '/[^0-9+]/', '', $phone );
	return $link ? '<a href="tel:' . esc_attr( $tel ) . '">' . esc_html( $phone ) . '</a>' : esc_html( $phone );
}
add_shortcode( 'cohf_phone', 'cohf_sc_org_contact' );
add_shortcode( 'cohf_whatsapp', 'cohf_sc_org_contact' );
add_shortcode( 'cohf_email', 'cohf_sc_org_contact' );

