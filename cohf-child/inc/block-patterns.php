<?php
/**
 * Block patterns for the Foundation's section designs.
 *
 * Without these, "build this page with blocks" would mean rebuilding the
 * design by hand in plain blocks, and pages would drift apart visually within
 * a month. Each pattern emits the same class names the stylesheets already
 * target - .container, .feature, .section-head, .kicker, .grid, .card and so
 * on - so a section inserted from the pattern library looks identical to the
 * hardcoded original.
 *
 * Patterns carry the Foundation's real wording rather than placeholder text,
 * so inserting one gives an editor something true to adjust instead of
 * something to invent.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the pattern category and the patterns themselves.
 */
function cohf_register_block_patterns() {
	if ( ! function_exists( 'register_block_pattern' ) ) {
		return;
	}

	if ( function_exists( 'register_block_pattern_category' ) ) {
		register_block_pattern_category(
			'cohf',
			array( 'label' => __( 'Cistern of Hope', 'cohf-child' ) )
		);
	}

	foreach ( cohf_block_pattern_definitions() as $name => $pattern ) {
		register_block_pattern( 'cohf/' . $name, $pattern );
	}
}
add_action( 'init', 'cohf_register_block_patterns', 20 );

/**
 * Pattern definitions.
 *
 * @return array<string,array<string,mixed>>
 */
function cohf_block_pattern_definitions() {
	$cat = array( 'cohf' );

	return array(

		'section-heading' => array(
			'title'      => __( 'Section heading', 'cohf-child' ),
			'categories' => $cat,
			'keywords'   => array( 'kicker', 'title', 'intro' ),
			'content'    => '
<!-- wp:group {"className":"container","layout":{"type":"constrained"}} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"section-head"} -->
<div class="wp-block-group section-head">
<!-- wp:group -->
<div class="wp-block-group">
<!-- wp:paragraph {"className":"kicker"} -->
<p class="kicker">' . esc_html__( 'Section label', 'cohf-child' ) . '</p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2>' . esc_html__( 'A short, plain statement.', 'cohf-child' ) . '</h2>
<!-- /wp:heading -->
</div>
<!-- /wp:group -->
<!-- wp:paragraph -->
<p>' . esc_html__( 'One or two supporting sentences that explain what follows.', 'cohf-child' ) . '</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->',
		),

		'feature-split' => array(
			'title'      => __( 'Text and image, side by side', 'cohf-child' ),
			'categories' => $cat,
			'keywords'   => array( 'feature', 'story', 'image', 'two column' ),
			'content'    => '
<!-- wp:group {"className":"container feature","layout":{"type":"default"}} -->
<div class="wp-block-group container feature">
<!-- wp:group -->
<div class="wp-block-group">
<!-- wp:paragraph {"className":"kicker"} -->
<p class="kicker">' . esc_html__( 'Section label', 'cohf-child' ) . '</p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2>' . esc_html__( 'A statement that carries the section.', 'cohf-child' ) . '</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>' . esc_html__( 'Explain the work in plain language. Say what happens, for whom, and what changes as a result.', 'cohf-child' ) . '</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:image {"sizeSlug":"large"} -->
<figure class="wp-block-image size-large"><img alt="' . esc_attr__( 'Describe what is happening in this photograph.', 'cohf-child' ) . '"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:group -->',
		),

		'checklist' => array(
			'title'      => __( 'Checklist, two columns', 'cohf-child' ),
			'categories' => $cat,
			'keywords'   => array( 'list', 'ticks', 'criteria' ),
			'content'    => '
<!-- wp:group {"className":"container","layout":{"type":"constrained"}} -->
<div class="wp-block-group container">
<!-- wp:list {"className":"list-check list-check--2col"} -->
<ul class="wp-block-list list-check list-check--2col">
<!-- wp:list-item --><li>' . esc_html__( 'First item', 'cohf-child' ) . '</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>' . esc_html__( 'Second item', 'cohf-child' ) . '</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>' . esc_html__( 'Third item', 'cohf-child' ) . '</li><!-- /wp:list-item -->
<!-- wp:list-item --><li>' . esc_html__( 'Fourth item', 'cohf-child' ) . '</li><!-- /wp:list-item -->
</ul>
<!-- /wp:list -->
</div>
<!-- /wp:group -->',
		),

		'card-grid' => array(
			'title'      => __( 'Three cards', 'cohf-child' ),
			'categories' => $cat,
			'keywords'   => array( 'cards', 'grid', 'three' ),
			'content'    => '
<!-- wp:group {"className":"container","layout":{"type":"constrained"}} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"grid","layout":{"type":"default"}} -->
<div class="wp-block-group grid">
<!-- wp:group {"className":"card"} -->
<div class="wp-block-group card">
<!-- wp:group {"className":"card-body"} -->
<div class="wp-block-group card-body">
<!-- wp:heading {"level":3} --><h3>' . esc_html__( 'First card', 'cohf-child' ) . '</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>' . esc_html__( 'A sentence or two describing this area of work.', 'cohf-child' ) . '</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"card"} -->
<div class="wp-block-group card">
<!-- wp:group {"className":"card-body"} -->
<div class="wp-block-group card-body">
<!-- wp:heading {"level":3} --><h3>' . esc_html__( 'Second card', 'cohf-child' ) . '</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>' . esc_html__( 'A sentence or two describing this area of work.', 'cohf-child' ) . '</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"card"} -->
<div class="wp-block-group card">
<!-- wp:group {"className":"card-body"} -->
<div class="wp-block-group card-body">
<!-- wp:heading {"level":3} --><h3>' . esc_html__( 'Third card', 'cohf-child' ) . '</h3><!-- /wp:heading -->
<!-- wp:paragraph --><p>' . esc_html__( 'A sentence or two describing this area of work.', 'cohf-child' ) . '</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->',
		),

		'pull-quote' => array(
			'title'      => __( 'Pull quote', 'cohf-child' ),
			'categories' => $cat,
			'keywords'   => array( 'quote', 'statement' ),
			'content'    => '
<!-- wp:group {"className":"container","layout":{"type":"constrained"}} -->
<div class="wp-block-group container">
<!-- wp:paragraph {"className":"quote"} -->
<p class="quote">' . esc_html__( 'A single sentence that states what the Foundation believes.', 'cohf-child' ) . '</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
		),

		'callout' => array(
			'title'      => __( 'Callout note', 'cohf-child' ),
			'categories' => $cat,
			'keywords'   => array( 'notice', 'note', 'important' ),
			'content'    => '
<!-- wp:group {"className":"container","layout":{"type":"constrained"}} -->
<div class="wp-block-group container">
<!-- wp:paragraph {"className":"callout"} -->
<p class="callout"><strong>' . esc_html__( 'Heading for this note', 'cohf-child' ) . '</strong><br>' . esc_html__( 'Use this for something the reader must not miss, such as how to raise a concern.', 'cohf-child' ) . '</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->',
		),

		'cta-band' => array(
			'title'      => __( 'Closing call to action', 'cohf-child' ),
			'categories' => $cat,
			'keywords'   => array( 'cta', 'partner', 'donate', 'closing' ),
			'content'    => '
<!-- wp:group {"className":"container","layout":{"type":"constrained"}} -->
<div class="wp-block-group container">
<!-- wp:group {"className":"cta-band","layout":{"type":"default"}} -->
<div class="wp-block-group cta-band">
<!-- wp:group -->
<div class="wp-block-group">
<!-- wp:heading --><h2>' . esc_html__( 'Let us build lasting change together.', 'cohf-child' ) . '</h2><!-- /wp:heading -->
<!-- wp:paragraph --><p>' . esc_html__( 'Partner with Cistern of Hope Foundation to strengthen pathways for children, young people, women, families and communities.', 'cohf-child' ) . '</p><!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:buttons {"className":"buttons"} -->
<div class="wp-block-buttons buttons">
<!-- wp:button {"className":"btn gold"} -->
<div class="wp-block-button btn gold"><a class="wp-block-button__link wp-element-button">' . esc_html__( 'Partner With Us', 'cohf-child' ) . '</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->',
		),
	);
}
