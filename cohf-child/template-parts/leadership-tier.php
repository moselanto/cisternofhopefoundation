<?php
/**
 * One governance tier of the leadership page.
 *
 * Carries a numbered label, a statement heading and an honest count badge.
 * The badge reports photographs still outstanding rather than hiding the gap,
 * which reads as candour to the donors and partners who use this page to
 * judge whether the Foundation is properly governed.
 *
 * @package COHF_Child
 *
 * @var array $args group, number, title, statement, intro, modifier.
 */
defined( 'ABSPATH' ) || exit;

$group     = isset( $args['group'] ) ? $args['group'] : '';
$number    = isset( $args['number'] ) ? $args['number'] : '';
$title     = isset( $args['title'] ) ? $args['title'] : '';
$statement = isset( $args['statement'] ) ? $args['statement'] : '';
$intro     = isset( $args['intro'] ) ? $args['intro'] : '';
$modifier  = isset( $args['modifier'] ) ? $args['modifier'] : '';

$query = new WP_Query(
	array(
		'post_type'      => 'cohf_leader',
		'posts_per_page' => 24,
		'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
		'no_found_rows'  => true,
		'meta_query'     => array(
			array(
				'key'   => '_cohf_group',
				'value' => $group,
			),
		),
	)
);

if ( $query->have_posts() === false ) {
	wp_reset_postdata();
	return;
}

$total   = (int) $query->post_count;
$pending = 0;
foreach ( $query->posts as $cohf_leader_post ) {
	if ( has_post_thumbnail( $cohf_leader_post->ID ) === false ) {
		$pending++;
	}
}

$count_label = sprintf(
	/* translators: %d: number of people in this governance tier. */
	_n( '%d leader', '%d leaders', $total, 'cohf-child' ),
	$total
);
if ( $pending > 0 ) {
	$count_label .= ' - ' . sprintf(
		/* translators: %d: number of portraits not yet supplied. */
		_n( '%d photograph pending', '%d photographs pending', $pending, 'cohf-child' ),
		$pending
	);
}
?>
<div class="leader-tier">
	<div class="leader-tier__head">
		<div class="leader-tier__heading">
			<?php if ( $number ) : ?>
				<div class="sec-label">
					<span class="sec-label__num"><?php echo esc_html( $number ); ?></span>
					<span class="sec-label__rule"></span>
					<span class="sec-label__text"><?php echo esc_html( $title ); ?></span>
				</div>
			<?php endif; ?>

			<?php if ( $statement ) : ?>
				<h2 class="leader-tier__title sec-statement sec-statement--wide"><?php echo esc_html( $statement ); ?></h2>
			<?php else : ?>
				<h2 class="leader-tier__title sec-statement sec-statement--wide"><?php echo esc_html( $title ); ?></h2>
			<?php endif; ?>

			<?php if ( $intro ) : ?>
				<p class="leader-tier__intro"><?php echo esc_html( $intro ); ?></p>
			<?php endif; ?>
		</div>

		<span class="sec-count"><?php echo esc_html( $count_label ); ?></span>
	</div>

	<div class="leader-grid<?php echo $modifier ? ' leader-grid--' . esc_attr( $modifier ) : ''; ?>">
		<?php
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part( 'template-parts/leadership-card' );
		}
		?>
	</div>
</div>
<?php
wp_reset_postdata();
