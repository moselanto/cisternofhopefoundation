<?php
/**
 * Primary navigation - two levels.
 *
 * Replaces the flat anchor list. Top-level entries that carry children render
 * as a <button> controlling a dropdown panel, which is what makes the
 * navigation reachable by keyboard and announceable by screen readers. Entries
 * without children stay plain anchors, exactly as before.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

$cohf_nav     = cohf_nav();
$cohf_here    = untrailingslashit( home_url( add_query_arg( array() ) ) );
$cohf_support = cohf_page_url( 'page-templates/page-support.php' );
?>
<nav class="links" id="cohf-primary-nav" aria-label="<?php esc_attr_e( 'Primary', 'cohf-child' ); ?>">
	<?php
	if ( 'wordpress' === $cohf_nav['source'] && has_nav_menu( 'primary' ) ) {
		wp_nav_menu( array(
			'theme_location' => 'primary',
			'container'      => false,
			'items_wrap'     => '%3$s',
			'depth'          => 2,
			'fallback_cb'    => false,
			'walker'         => new COHF_Mega_Nav_Walker(),
		) );
	} else {
		$cohf_group = 0;

		foreach ( $cohf_nav['items'] as $cohf_item ) {
			$cohf_children = isset( $cohf_item['children'] ) ? $cohf_item['children'] : array();

			// Plain top-level link.
			if ( empty( $cohf_children ) ) {
				printf(
					'<a href="%1$s"%3$s>%2$s</a>',
					esc_url( $cohf_item['url'] ),
					esc_html( $cohf_item['label'] ),
					untrailingslashit( $cohf_item['url'] ) === $cohf_here ? ' aria-current="page"' : ''
				);
				continue;
			}

			// Dropdown group.
			$cohf_group++;
			$cohf_panel_id = 'cohf-navgroup-' . $cohf_group;

			$cohf_is_current = false;
			foreach ( $cohf_children as $cohf_child ) {
				if ( untrailingslashit( $cohf_child['url'] ) === $cohf_here ) {
					$cohf_is_current = true;
					break;
				}
			}
			?>
			<div class="navgroup" data-navgroup>
				<button type="button" class="navgroup__btn"
					aria-expanded="false"
					aria-controls="<?php echo esc_attr( $cohf_panel_id ); ?>"
					<?php echo $cohf_is_current ? ' data-current="true"' : ''; ?>>
					<span><?php echo esc_html( $cohf_item['label'] ); ?></span>
					<svg class="navgroup__chev" viewBox="0 0 10 6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M1 1l4 4 4-4"/></svg>
				</button>
				<div class="navgroup__panel" id="<?php echo esc_attr( $cohf_panel_id ); ?>">
					<?php foreach ( $cohf_children as $cohf_child ) : ?>
						<a href="<?php echo esc_url( $cohf_child['url'] ); ?>"<?php echo untrailingslashit( $cohf_child['url'] ) === $cohf_here ? ' aria-current="page"' : ''; ?>>
							<?php echo esc_html( $cohf_child['label'] ); ?>
							<?php if ( ! empty( $cohf_child['desc'] ) ) : ?>
								<span class="navgroup__desc"><?php echo esc_html( $cohf_child['desc'] ); ?></span>
							<?php endif; ?>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
			<?php
		}
	}
	?>
	<a class="cta-mobile" href="<?php echo esc_url( $cohf_support ); ?>"><?php esc_html_e( 'Support Our Work', 'cohf-child' ); ?></a>
</nav>
