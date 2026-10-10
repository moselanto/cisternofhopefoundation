<?php
/**
 * Partners: visibility rules and the partners supplied by the Foundation.
 *
 * Added in 14.9.0 for the "Our partners" showcase on the Partners page. The
 * Foundation asked for a space to highlight current and past partners and
 * what has been accomplished together with each of them.
 *
 * - An unconfirmed partner is never public: its single page returns 404 for
 *   visitors and it is left out of the WordPress sitemap. Editors can still
 *   preview it.
 * - The partners below were supplied by the Foundation for publication. They
 *   are seeded once as real cohf_partner records so staff can edit them like
 *   any other post. A seed never overwrites an existing partner, and a seed
 *   that has run once is never recreated after staff delete it.
 * - Editorial rules match inc/story-seeds.php: no figure, name, date or
 *   outcome has been added that the supplied text did not state, and relative
 *   dates ("for the past year") are removed because the page outlives them.
 *
 * To add a later partner without code, use Partners > Add New in wp-admin
 * and tick "Partnership confirmed in writing".
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Is this partner confirmed for publication?
 *
 * @param int $post_id Partner post ID.
 * @return bool
 */
function cohf_partner_is_confirmed( $post_id ) {
	return '1' === (string) get_post_meta( (int) $post_id, '_cohf_confirmed', true );
}

/**
 * Send visitors a 404 for any partner page that is not confirmed.
 */
function cohf_partner_guard_single() {
	if ( ! is_singular( 'cohf_partner' ) ) {
		return;
	}
	$post_id = (int) get_queried_object_id();
	if ( cohf_partner_is_confirmed( $post_id ) || current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	global $wp_query;
	$wp_query->set_404();
	status_header( 404 );
	nocache_headers();
}
add_action( 'template_redirect', 'cohf_partner_guard_single', 1 );

/**
 * Keep unconfirmed partners out of the core XML sitemap.
 *
 * @param array  $args      WP_Query args.
 * @param string $post_type Post type.
 * @return array
 */
function cohf_partner_sitemap_args( $args, $post_type ) {
	if ( 'cohf_partner' === $post_type ) {
		// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		$args['meta_query'] = array(
			array(
				'key'   => '_cohf_confirmed',
				'value' => '1',
			),
		);
	}
	return $args;
}
add_filter( 'wp_sitemaps_posts_query_args', 'cohf_partner_sitemap_args', 10, 2 );

/**
 * Partners supplied by the Foundation, in the order they were sent.
 *
 * @return array<int,array<string,mixed>>
 */
function cohf_partner_seed() {
	return array(

		array(
			'slug'    => 'deliverance-church-kabete-n',
			'title'   => 'Deliverance Church Kabete N',
			'order'   => 1,
			'excerpt' => __( 'The first to believe in our programmes. What began as Sunday-morning porridge for children grew into a quarterly outreach of meals, counselling, activities and games, and the church continues to open its doors to our work.', 'cohf-child' ),
			'body'    => array(
				__( 'Every meaningful journey begins with someone who believes. For Cistern of Hope Foundation, Deliverance Church Kabete N was the first to believe in our programmes and support our vision of improving the lives of vulnerable children in the community.', 'cohf-child' ),
				__( 'The church began by supporting the preparation and serving of porridge to children on Sunday mornings. This simple but meaningful act of kindness attracted more children from the village and laid the foundation for the growth of our community feeding initiative.', 'cohf-child' ),
				__( 'As the programme developed, it evolved into a quarterly outreach bringing children together for meals, counselling sessions, fun activities, and games. These gatherings provide opportunities not only to address children\'s nutritional needs but also to nurture their emotional well-being, social connection, and sense of belonging.', 'cohf-child' ),
				__( 'Beyond the feeding programme, Deliverance Church Kabete N has continued to support our mission by generously providing free meeting venues for many of our activities, helping us reach and engage the community while reducing programme costs.', 'cohf-child' ),
				__( 'Through this valued relationship, Cistern of Hope Foundation has been able to reach 297 children through its community efforts.', 'cohf-child' ),
				__( 'We remain deeply grateful to Deliverance Church Kabete N for being the first to believe in our vision, opening its doors to our work, and continuing to walk alongside us in serving children and strengthening the community.', 'cohf-child' ),
				__( 'Thank you for helping us turn a simple cup of porridge into a growing programme of nourishment, care, connection, and hope. Your early belief helped lay the foundation for a lasting difference in children\'s lives.', 'cohf-child' ),
			),
			'meta'    => array(
				'status'       => 'current',
				'tagline'      => __( 'Our First Believer in Hope', 'cohf-child' ),
				'partner_type' => __( 'Faith-based organisation', 'cohf-child' ),
				'period'       => '',
				'achievements' => implode( "\n", array(
					__( 'Sunday-morning porridge for children, the start of our community feeding initiative', 'cohf-child' ),
					__( 'A quarterly children\'s outreach with meals, counselling sessions, fun activities and games', 'cohf-child' ),
					__( 'Free meeting venues for many of our activities, reducing programme costs', 'cohf-child' ),
				) ),
				'figure'       => '297',
				'figure_label' => __( 'children reached through our community efforts together', 'cohf-child' ),
			),
		),

		array(
			'slug'    => 'nellique-sylvia-kat-australia',
			'title'   => 'Nellique, Sylvia & Kat (Australia)',
			'order'   => 2,
			'excerpt' => __( 'Supporters from Australia walking alongside us since 2024: monthly feeding of widows, business start-up support for women and young people, and donations of hygiene kits.', 'cohf-child' ),
			'body'    => array(
				__( 'In 2024, Nellique, Sylvia and Kat from Australia believed in the vision of Cistern of Hope Foundation and chose to support our mission of transforming lives and strengthening vulnerable communities.', 'cohf-child' ),
				__( 'Through their continued generosity and support for our feeding programme, business start-up initiatives and donations of hygiene kits, they have helped us make a meaningful difference in the lives of vulnerable widows, women and young people.', 'cohf-child' ),
				__( 'Their support has contributed to the monthly feeding of widows while helping women and young people take steps towards starting their own businesses, building livelihoods and pursuing greater financial independence.', 'cohf-child' ),
				__( 'Beyond their contributions, Nellique, Sylvia and Kat have demonstrated the power of compassion, trust and solidarity across borders. Their continued support reminds us that meaningful change is possible when people believe in the potential of others.', 'cohf-child' ),
				__( 'From all of us at Cistern of Hope Foundation, thank you, Nellique, Sylvia and Kat, for believing in our mission and walking alongside us since 2024. Your support continues to bring hope, dignity and opportunity to the lives of those we serve.', 'cohf-child' ),
			),
			'meta'    => array(
				'status'       => 'current',
				'tagline'      => __( 'Celebrating Our Valued Supporters', 'cohf-child' ),
				'partner_type' => __( 'Individual supporters, Australia', 'cohf-child' ),
				'period'       => __( 'Since 2024', 'cohf-child' ),
				'achievements' => implode( "\n", array(
					__( 'Monthly feeding of vulnerable widows', 'cohf-child' ),
					__( 'Business start-up support for women and young people', 'cohf-child' ),
					__( 'Donations of hygiene kits', 'cohf-child' ),
				) ),
				'figure'       => '',
				'figure_label' => '',
			),
		),
		array(
			'slug'    => 'suivera-community',
			'title'   => 'Suivera Community',
			'order'   => 3,
			'excerpt' => __( 'Partners in girls\' dignity and education: supporting our Girls\' Dignity Kits initiative and Partial Education Support Programme, with encouraging progress in school retention.', 'cohf-child' ),
			'body'    => array(
				__( 'Suivera Community has stood alongside Cistern of Hope Foundation in supporting our Girls\' Dignity Kits initiative and Partial Education Support Programme.', 'cohf-child' ),
				__( 'Through their support, we have continued working to address barriers that prevent vulnerable children, particularly girls, from accessing and remaining in school.', 'cohf-child' ),
				__( 'The provision of dignity kits helps girls manage their menstrual health with dignity and confidence, while partial education support helps ease some of the financial challenges that can interrupt a child\'s education.', 'cohf-child' ),
				__( 'This partnership has contributed to encouraging progress in school retention, with positive changes witnessed among girls and boys who continue to pursue their education.', 'cohf-child' ),
				__( 'We are deeply grateful to Suivera Community for believing in our mission and helping us create an environment where children can learn, grow, and pursue a brighter future.', 'cohf-child' ),
			),
			'meta'    => array(
				'status'       => 'current',
				'tagline'      => __( 'Partners in Girls\' Dignity and Education', 'cohf-child' ),
				'partner_type' => '',
				'period'       => '',
				'achievements' => implode( "\n", array(
					__( 'Girls\' Dignity Kits, helping girls manage their menstrual health with dignity and confidence', 'cohf-child' ),
					__( 'Partial education support, easing the financial challenges that interrupt a child\'s education', 'cohf-child' ),
					__( 'Encouraging progress in school retention among girls and boys', 'cohf-child' ),
				) ),
				'figure'       => '',
				'figure_label' => '',
			),
		),
	);
}

/**
 * Create any supplied partner that has not been seeded before.
 *
 * Runs in wp-admin only, for users who can publish. Each slug is recorded once
 * seeded, so deleting a partner in wp-admin is respected.
 */
function cohf_partners_maybe_seed() {
	if ( ! is_admin() || wp_doing_ajax() || ! current_user_can( 'publish_posts' ) || ! post_type_exists( 'cohf_partner' ) ) {
		return;
	}

	$seeded = get_option( 'cohf_partners_seeded', array() );
	$seeded = is_array( $seeded ) ? $seeded : array();
	$added  = false;

	foreach ( cohf_partner_seed() as $partner ) {
		if ( in_array( $partner['slug'], $seeded, true ) ) {
			continue;
		}

		$existing = get_posts( array(
			'post_type'      => 'cohf_partner',
			'name'           => $partner['slug'],
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
		) );

		if ( empty( $existing ) ) {
			$content = '';
			foreach ( $partner['body'] as $paragraph ) {
				$content .= "<!-- wp:paragraph -->\n<p>" . esc_html( $paragraph ) . "</p>\n<!-- /wp:paragraph -->\n\n";
			}

			$meta = array( '_cohf_confirmed' => '1' );
			foreach ( $partner['meta'] as $key => $value ) {
				if ( '' !== $value ) {
					$meta[ '_cohf_' . $key ] = $value;
				}
			}

			$post_id = wp_insert_post( array(
				'post_type'    => 'cohf_partner',
				'post_status'  => 'publish',
				'post_title'   => $partner['title'],
				'post_name'    => $partner['slug'],
				'post_excerpt' => $partner['excerpt'],
				'post_content' => trim( $content ),
				'menu_order'   => (int) $partner['order'],
				'meta_input'   => $meta,
			), true );

			if ( is_wp_error( $post_id ) ) {
				continue; // Try again on the next admin load.
			}
		}

		$seeded[] = $partner['slug'];
		$added    = true;
	}

	if ( $added ) {
		update_option( 'cohf_partners_seeded', $seeded, false );
	}
}
add_action( 'admin_init', 'cohf_partners_maybe_seed', 30 );
