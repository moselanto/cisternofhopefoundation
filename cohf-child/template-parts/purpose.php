<?php
/**
 * Vision / mission / motto. Prototype markup: .purpose > article
 *
 * @package COHF_Child
 */
defined( 'ABSPATH' ) || exit;

$org = cohf_org();
?>
<div class="purpose">
	<article>
		<div class="kicker"><?php esc_html_e( 'Vision', 'cohf-child' ); ?></div>
		<h3><?php esc_html_e( 'Poverty-free communities.', 'cohf-child' ); ?></h3>
		<p><?php esc_html_e( 'Built on sustainable solutions and empowered individuals.', 'cohf-child' ); ?></p>
	</article>
	<article>
		<div class="kicker"><?php esc_html_e( 'Mission', 'cohf-child' ); ?></div>
		<h3><?php esc_html_e( 'Empowering communities.', 'cohf-child' ); ?></h3>
		<p><?php esc_html_e( 'Promoting self-reliance, economic development and social welfare.', 'cohf-child' ); ?></p>
	</article>
	<article>
		<div class="kicker"><?php esc_html_e( 'Motto', 'cohf-child' ); ?></div>
		<h3><?php echo esc_html( $org['motto'] ); ?></h3>
		<p><?php esc_html_e( 'Collaboration is central to how we work.', 'cohf-child' ); ?></p>
	</article>
</div>
