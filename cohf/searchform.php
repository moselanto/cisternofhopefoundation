<?php
/**
 * Search form.
 *
 * @package COHF
 */
defined( 'ABSPATH' ) || exit;
?>
<form class="search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="search-form__label" for="cohf-s"><?php esc_html_e( 'Search', 'cohf' ); ?></label>
	<div class="search-form__row">
		<input class="search-form__input" type="search" id="cohf-s" name="s" value="<?php echo esc_attr( get_search_query() ); ?>">
		<button class="btn dark" type="submit"><?php esc_html_e( 'Search', 'cohf' ); ?></button>
	</div>
</form>
