<?php
/**
 * Flat navigation walker.
 *
 * The approved prototype renders primary navigation as bare anchors inside
 * nav.links, with no <ul>/<li> wrapper. This walker reproduces that markup
 * exactly while keeping WordPress menu management, and preserves the
 * aria-current attribute for assistive technology.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Renders a single-level menu as a sequence of anchors.
 */
class COHF_Flat_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * No list wrapper.
	 *
	 * @param string $output Output buffer.
	 * @param int    $depth  Depth.
	 * @param array  $args   Arguments.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {}

	/**
	 * No list wrapper.
	 *
	 * @param string $output Output buffer.
	 * @param int    $depth  Depth.
	 * @param array  $args   Arguments.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {}

	/**
	 * Emit the anchor.
	 *
	 * @param string   $output Output buffer.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Arguments.
	 * @param int      $id     Item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		if ( $depth > 0 ) {
			return;
		}

		$aria = '';
		if ( ! empty( $item->current ) ) {
			$aria = ' aria-current="page"';
		} elseif ( ! empty( $item->current_item_ancestor ) || ! empty( $item->current_item_parent ) ) {
			$aria = ' aria-current="true"';
		}

		$output .= sprintf(
			'<a href="%1$s"%3$s>%2$s</a>',
			esc_url( $item->url ),
			esc_html( $item->title ),
			$aria
		);
	}

	/**
	 * Nothing to close.
	 *
	 * @param string   $output Output buffer.
	 * @param WP_Post  $item   Menu item.
	 * @param int      $depth  Depth.
	 * @param stdClass $args   Arguments.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {}
}

/**
 * Two-level navigation walker.
 *
 * Mirrors the markup produced by template-parts/site-nav.php so a menu managed
 * in Appearance > Menus gets the same dropdowns, keyboard behaviour and ARIA
 * as the theme's own list.
 */
class COHF_Mega_Nav_Walker extends Walker_Nav_Menu {

	/** @var int Panel counter for unique ids. */
	private $group = 0;

	/** @var string Id of the panel currently open. */
	private $panel_id = '';

	public function start_lvl( &$output, $depth = 0, $args = null ) {
		if ( 0 !== $depth ) {
			return;
		}
		$output .= '<div class="navgroup__panel" id="' . esc_attr( $this->panel_id ) . '">';
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
		if ( 0 !== $depth ) {
			return;
		}
		$output .= '</div>';
	}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes     = empty( $item->classes ) ? array() : (array) $item->classes;
		$has_children = in_array( 'menu-item-has-children', $classes, true );

		$aria = '';
		if ( ! empty( $item->current ) ) {
			$aria = ' aria-current="page"';
		}

		if ( 0 === $depth && $has_children ) {
			$this->group++;
			$this->panel_id = 'cohf-navgroup-wp-' . $this->group;
			$current = ( ! empty( $item->current_item_ancestor ) || ! empty( $item->current_item_parent ) ) ? ' data-current="true"' : '';

			$output .= '<div class="navgroup" data-navgroup>';
			$output .= '<button type="button" class="navgroup__btn" aria-expanded="false" aria-controls="' . esc_attr( $this->panel_id ) . '"' . $current . '>';
			$output .= '<span>' . esc_html( $item->title ) . '</span>';
			$output .= '<svg class="navgroup__chev" viewBox="0 0 10 6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M1 1l4 4 4-4"/></svg>';
			$output .= '</button>';
			return;
		}

		$output .= sprintf(
			'<a href="%1$s"%3$s>%2$s</a>',
			esc_url( $item->url ),
			esc_html( $item->title ),
			$aria
		);
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		if ( 0 === $depth && in_array( 'menu-item-has-children', $classes, true ) ) {
			$output .= '</div>';
		}
	}
}
