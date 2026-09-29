<?php
/**
 * Impact story seeds.
 *
 * The four stories below were supplied by the Foundation with dates and with
 * consent confirmed for the photographs. They are seeded as real cohf_story
 * records so the Stories archive is never shown empty, and so each story is
 * editable in the admin afterwards like any other post.
 *
 * Editorial rules applied to the supplied text:
 *
 *   - Relative dates ("yesterday", "recently", "today") are removed. A web
 *     page keeps its text long after the day it was written, so the date is
 *     carried by the post date and the story_date field instead.
 *   - "Inbox us" is removed. It is a social-media instruction that means
 *     nothing on a website; the call to action below each story handles it.
 *   - No figure, name, place or outcome has been added that the supplied
 *     text did not already state.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * The stories supplied by the Foundation, oldest first.
 *
 * @return array<int,array<string,mixed>>
 */
function cohf_story_seed() {
	return array(

		array(
			'title'   => __( 'When women rise, communities rise with them', 'cohf-child' ),
			'slug'    => 'women-empowerment-seminar',
			'date'    => '2025-08-25',
			'image'   => 'story-01-women-seminar',
			'excerpt' => __( 'A women empowerment seminar equipping women with knowledge, confidence and practical tools to bring transformation in their families and communities.', 'cohf-child' ),
			'body'    => array(
				__( 'The Foundation held a women empowerment seminar, and it was a great joy to witness women being equipped, discovering their true identity, and embracing their potential to bring transformation within their families and communities.', 'cohf-child' ),
				__( 'This seminar is part of our ongoing commitment to empower women with knowledge, confidence and practical tools to positively influence society. We believe that when women rise, entire communities are uplifted.', 'cohf-child' ),
				__( 'We intend to hold more of these seminars, and we are seeking strategic partners who share the vision of empowering women and fostering lasting change. Together, we can multiply the impact and create a brighter future for many.', 'cohf-child' ),
			),
			'challenge'    => __( 'Women in the community had few opportunities to be equipped with the knowledge, confidence and practical tools needed to influence their own families and communities.', 'cohf-child' ),
			'intervention' => __( 'The Foundation convened a women empowerment seminar focused on identity, confidence and the practical tools of transformation.', 'cohf-child' ),
			'change'       => __( 'Women left the seminar equipped, having discovered their identity and embraced their potential to bring transformation within their families and communities.', 'cohf-child' ),
		),

		array(
			'title'   => __( 'Fellowship and dignity with orphans in our community', 'cohf-child' ),
			'slug'    => 'fellowship-with-orphans',
			'date'    => '2025-12-16',
			'image'   => 'story-02-dignity-packs',
			'excerpt' => __( 'Sanitary pads, encouragement and shared joy with orphaned children, and a commitment to return every month with the essentials they need.', 'cohf-child' ),
			'body'    => array(
				__( 'The Cistern of Hope Foundation is committed to uplifting the less fortunate in our communities, restoring dignity, hope and a sense of belonging to those who need it most.', 'cohf-child' ),
				__( 'We had the privilege of fellowshipping with orphans in our community, where we distributed sanitary pads, offered encouragement, and shared moments of joy and laughter together. It was a truly fulfilling experience that reminded us of the power of love and compassion.', 'cohf-child' ),
				__( 'Our vision is to engage with these children every month, providing essential support such as food supplies, blankets, mattresses, sanitary pads and other basic necessities that promote dignity and well-being.', 'cohf-child' ),
				__( 'We warmly invite individuals, organisations and well-wishers to partner with us in this noble cause. Your support can make a lasting impact in the lives of vulnerable children and families.', 'cohf-child' ),
			),
			'challenge'    => __( 'Orphaned children in the community go without the basic necessities that protect their dignity and well-being, from sanitary pads to food supplies, blankets and bedding.', 'cohf-child' ),
			'intervention' => __( 'The Foundation spent time with the children, distributed sanitary pads, and offered encouragement and companionship rather than only goods.', 'cohf-child' ),
			'change'       => __( 'The children received the essentials they needed that day, and the Foundation set out to return every month with food supplies, blankets, mattresses and sanitary pads.', 'cohf-child' ),
		),

		array(
			'title'   => __( 'Door to door, restoring dignity to girls in our community', 'cohf-child' ),
			'slug'    => 'door-to-door-distribution',
			'date'    => '2025-12-20',
			'image'   => 'story-03-door-to-door',
			'excerpt' => __( 'A door-to-door sanitary pad distribution reaching less fortunate girls, with an honest account of the need that still remains.', 'cohf-child' ),
			'body'    => array(
				__( 'We carried out another door-to-door distribution of sanitary pads, reaching and blessing less fortunate girls in our community.', 'cohf-child' ),
				__( 'This simple act has helped to continue restoring dignity and bringing hope. The need, however, is still great. Many of these girls still lack essential items such as clothing, beddings and basic sleeping necessities.', 'cohf-child' ),
				__( 'We are humbly appealing for more partners and well-wishers to join us in supporting and restoring dignity to these vulnerable girls.', 'cohf-child' ),
				__( 'Thank you to all our partners for enabling us to show love and compassion to the community.', 'cohf-child' ),
			),
			'challenge'    => __( 'Girls in the community go without sanitary pads, and many also lack clothing, beddings and basic sleeping necessities.', 'cohf-child' ),
			'intervention' => __( 'Foundation staff went door to door, reaching girls in their own homes with packs of sanitary pads.', 'cohf-child' ),
			'change'       => __( 'The girls reached received the pads they needed. The Foundation is clear that clothing, beddings and sleeping necessities remain outstanding needs.', 'cohf-child' ),
		),

		array(
			'title'   => __( 'Three boys off the streets and into school', 'cohf-child' ),
			'slug'    => 'three-boys-enrolled-in-school',
			'date'    => '2026-06-22',
			'image'   => 'story-04-back-to-school',
			'excerpt' => __( 'In June 2026 the Foundation took three boys from the streets and enrolled them in school, and continues to follow their progress.', 'cohf-child' ),
			'body'    => array(
				__( 'True love and genuine transformation in society are expressed through acts of compassion and commitment. At Cistern of Hope Foundation, we remain dedicated to our mission of fighting poverty and creating opportunities that will empower the current generation and many generations to come.', 'cohf-child' ),
				__( 'In the month of June 2026, through the support of a few individuals who believe in our vision and stand with us on this journey, we were blessed with the opportunity to take three boys from the streets and enrol them in school. It was a moment filled with great joy, hope and deep satisfaction to witness this life-changing step.', 'cohf-child' ),
				__( 'Our journey continues, and we are committed to following up on their progress in school while working to provide the support they need to continue their education successfully.', 'cohf-child' ),
				__( 'If you are inspired by what we are doing and would like to partner with us in transforming lives, we would be glad to hear from you. Together, we can bring hope, restore dignity, and create a brighter future.', 'cohf-child' ),
			),
			'challenge'    => __( 'Three boys were living on the streets and out of school.', 'cohf-child' ),
			'intervention' => __( 'With the support of a few individuals who believe in the Foundation\'s vision, the boys were taken from the streets and enrolled in school.', 'cohf-child' ),
			'change'       => __( 'The three boys are enrolled and in uniform, and the Foundation is following up on their progress and on the support they need to stay in education.', 'cohf-child' ),
		),
	);
}

/**
 * Create the seeded stories, skipping any that already exist.
 *
 * Existing records are left completely alone: once the Foundation has edited a
 * story in the admin, a later theme release must not overwrite that work.
 *
 * @return int Number of stories created.
 */
function cohf_seed_stories() {
	$created = 0;

	foreach ( cohf_story_seed() as $story ) {

		if ( function_exists( 'cohf_find_by_title' ) && cohf_find_by_title( $story['title'], 'cohf_story' ) ) {
			continue;
		}

		$existing = get_page_by_path( $story['slug'], OBJECT, 'cohf_story' );
		if ( $existing ) {
			continue;
		}

		$content = '';
		foreach ( $story['body'] as $paragraph ) {
			$content .= "<!-- wp:paragraph -->\n<p>" . wp_kses_post( $paragraph ) . "</p>\n<!-- /wp:paragraph -->\n\n";
		}

		// Deliberately inserted as a draft. cohf_guard_story_consent() forces
		// any story to draft when consent is not on record at the moment of
		// writing, and a fresh insert has no post ID yet, so the consent meta
		// written below cannot exist in time. The story is promoted to publish
		// after that meta is in place, which satisfies the guard on its own
		// terms rather than working around it.
		$story_id = wp_insert_post( array(
			'post_type'    => 'cohf_story',
			'post_status'  => 'draft',
			'post_title'   => $story['title'],
			'post_name'    => $story['slug'],
			'post_excerpt' => $story['excerpt'],
			'post_content' => trim( $content ),
			'post_date'    => $story['date'] . ' 09:00:00',
		) );

		if ( ! $story_id || is_wp_error( $story_id ) ) {
			continue;
		}

		// Consent is confirmed for every seeded story; the Foundation holds
		// permission from the people photographed. Without this flag the
		// safeguarding checkbox would read as unconfirmed in the admin.
		update_post_meta( $story_id, '_cohf_consent', '1' );
		update_post_meta( $story_id, '_cohf_story_date', $story['date'] );
		update_post_meta( $story_id, '_cohf_challenge', $story['challenge'] );
		update_post_meta( $story_id, '_cohf_intervention', $story['intervention'] );
		update_post_meta( $story_id, '_cohf_change', $story['change'] );

		$attachment_id = cohf_import_image( $story['image'] );
		if ( $attachment_id ) {
			set_post_thumbnail( $story_id, $attachment_id );
		}

		// Consent is now recorded against a real post ID, so the guard reads
		// it and allows the story through.
		wp_update_post( array(
			'ID'          => $story_id,
			'post_status' => 'publish',
		) );

		++$created;
	}

	return $created;
}

/**
 * How many impact stories exist, in any status.
 *
 * @return int
 */
function cohf_stories_count() {
	$ids = get_posts( array(
		'post_type'        => 'cohf_story',
		'post_status'      => array( 'publish', 'draft', 'pending', 'private' ),
		'numberposts'      => -1,
		'fields'           => 'ids',
		'suppress_filters' => false,
	) );

	return count( $ids );
}

/**
 * How many impact stories are actually published.
 *
 * The distinction matters: four stories sat as drafts on the live site for
 * several releases while every check that only counted rows reported
 * success. Published is the only count a visitor can see.
 *
 * @return int
 */
function cohf_stories_published_count() {
	$ids = get_posts( array(
		'post_type'        => 'cohf_story',
		'post_status'      => 'publish',
		'numberposts'      => -1,
		'fields'           => 'ids',
		'suppress_filters' => false,
	) );

	return count( $ids );
}

/**
 * Publish seeded stories left sitting as drafts with consent on record.
 *
 * Repairs sites seeded by 9.46.0 to 9.49.0, where the story was inserted
 * asking for publish, the consent guard correctly demoted it to draft, and
 * nothing ever promoted it back.
 *
 * Only a draft is touched, and only one carrying recorded consent. A story
 * an editor has deliberately made private or pending is left exactly as it
 * is, and a story without consent is never published by this.
 *
 * @return int Number promoted.
 */
function cohf_stories_publish_seeded() {
	$published = 0;

	foreach ( cohf_story_seed() as $story ) {
		$existing = get_page_by_path( $story['slug'], OBJECT, 'cohf_story' );

		if ( empty( $existing ) ) {
			continue;
		}

		if ( 'draft' \!== $existing->post_status ) {
			continue;
		}

		if ( '1' \!== (string) get_post_meta( $existing->ID, '_cohf_consent', true ) ) {
			continue;
		}

		wp_update_post( array(
			'ID'          => $existing->ID,
			'post_status' => 'publish',
		) );

		++$published;
	}

	return $published;
}

/**
 * Seed and repair the stories once per theme version.
 *
 * Runs on admin_init, so it fires on a wp-admin request and not a front-end
 * one. Deploying and then loading the stories page directly will not trigger
 * it; opening any admin page will. The notice below makes that visible
 * rather than leaving it to be guessed at.
 *
 * Retries are bounded. Writing the version marker only on success would
 * retry a permanent failure on every admin request; writing it before the
 * attempt, as 9.48.0 did, gave up after one transient failure. Three
 * attempts, then stop and let the notice offer a manual run.
 */
function cohf_stories_maybe_seed() {
	if ( is_admin() === false ) {
		return;
	}

	if ( function_exists( 'cohf_seed_stories' ) === false ) {
		return;
	}

	$done = get_option( 'cohf_stories_seeded' );

	if ( $done && version_compare( (string) $done, COHF_CHILD_VERSION, '>=' ) ) {
		return;
	}

	$attempts = (int) get_option( 'cohf_stories_seed_attempts', 0 );

	if ( $attempts >= 3 ) {
		return;
	}

	update_option( 'cohf_stories_seed_attempts', $attempts + 1 );

	cohf_seed_stories();
	cohf_stories_publish_seeded();

	// Success is measured by what a visitor can see, not by rows existing.
	if ( cohf_stories_published_count() > 0 ) {
		update_option( 'cohf_stories_seeded', COHF_CHILD_VERSION );
		delete_option( 'cohf_stories_seed_attempts' );
	}
}
add_action( 'admin_init', 'cohf_stories_maybe_seed' );

/**
 * Manual run, for when the automatic attempts have been exhausted.
 */
function cohf_seed_stories_handler() {
	check_admin_referer( 'cohf_seed_stories' );

	if ( current_user_can( 'manage_options' ) === false ) {
		wp_die( esc_html__( 'You do not have permission to do this.', 'cohf-child' ) );
	}

	delete_option( 'cohf_stories_seed_attempts' );

	$created   = cohf_seed_stories();
	$published = cohf_stories_publish_seeded();

	if ( cohf_stories_published_count() > 0 ) {
		update_option( 'cohf_stories_seeded', COHF_CHILD_VERSION );
	}

	wp_safe_redirect( add_query_arg(
		array(
			'cohf_stories'   => 'seeded',
			'cohf_created'   => (int) $created,
			'cohf_published' => (int) $published,
		),
		admin_url( 'edit.php?post_type=cohf_story' )
	) );
	exit;
}
add_action( 'admin_post_cohf_seed_stories', 'cohf_seed_stories_handler' );

/**
 * Say plainly whether the stories are live, and offer to fix it.
 */
function cohf_stories_notice() {
	if ( current_user_can( 'manage_options' ) === false ) {
		return;
	}

	$screen = get_current_screen();

	if ( empty( $screen ) ) {
		return;
	}

	$screens = array( 'dashboard', 'toplevel_page_cohf-home', 'edit-cohf_story' );

	if ( in_array( $screen->id, $screens, true ) === false ) {
		return;
	}

	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only notice.
	if ( isset( $_GET['cohf_stories'] ) && 'seeded' === $_GET['cohf_stories'] ) {
		$created   = isset( $_GET['cohf_created'] ) ? absint( $_GET['cohf_created'] ) : 0;
		$published = isset( $_GET['cohf_published'] ) ? absint( $_GET['cohf_published'] ) : 0;

		printf(
			'<div class="notice notice-success is-dismissible"><p>%s</p></div>',
			esc_html( sprintf(
				/* translators: 1: stories created, 2: drafts published. */
				__( 'Impact stories: %1$d created, %2$d published from draft.', 'cohf-child' ),
				$created,
				$published
			) )
		);
	}
	// phpcs:enable WordPress.Security.NonceVerification.Recommended

	if ( cohf_stories_published_count() > 0 ) {
		return;
	}

	$total = cohf_stories_count();

	if ( $total > 0 ) {
		$headline = __( 'The impact stories exist but none of them are published.', 'cohf-child' );
		$detail   = __( 'A story stays a draft until consent is recorded against it. These have consent recorded, so they can be published safely. Until they are, the Stories page in the Impact menu shows an empty state to visitors.', 'cohf-child' );
		$button   = __( 'Publish the impact stories', 'cohf-child' );
	} else {
		$headline = __( 'The four impact stories have not been created yet.', 'cohf-child' );
		$detail   = __( 'The Stories page is in the Impact menu, so visitors can reach it, but it will show an empty state until the stories exist. Nothing already in the admin is changed or overwritten by this.', 'cohf-child' );
		$button   = __( 'Create the impact stories now', 'cohf-child' );
	}

	$url = wp_nonce_url(
		admin_url( 'admin-post.php?action=cohf_seed_stories' ),
		'cohf_seed_stories'
	);

	printf(
		'<div class="notice notice-warning"><p><strong>%1$s</strong> %2$s</p><p><a class="button button-primary" href="%3$s">%4$s</a></p></div>',
		esc_html( $headline ),
		esc_html( $detail ),
		esc_url( $url ),
		esc_html( $button )
	);
}
add_action( 'admin_notices', 'cohf_stories_notice' );
