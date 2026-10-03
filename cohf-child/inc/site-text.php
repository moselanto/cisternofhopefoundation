<?php
/**
 * Site Text editor (14.2.0).
 *
 * Every piece of wording written into the theme (headings, paragraphs,
 * buttons, labels) can be changed from WP Admin > Foundation > Site Text,
 * without touching code. Changes are stored in the database and survive
 * theme updates. Leave a box empty to use the original wording.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/** Saved overrides: original => new wording. */
function cohf_st_overrides() {
	static $o = null;
	if ( null === $o ) {
		$o = get_option( 'cohf_text_overrides', array() );
		$o = is_array( $o ) ? $o : array();
	}
	return $o;
}

add_filter( 'gettext', function ( $translation, $text, $domain ) {
	if ( 'cohf-child' !== $domain ) {
		return $translation;
	}
	$o = cohf_st_overrides();
	return ( isset( $o[ $text ] ) && '' !== $o[ $text ] ) ? $o[ $text ] : $translation;
}, 20, 3 );

/** Friendly names for the theme files, so editors know where text appears. */
function cohf_st_area( $file ) {
	$map = array(
		'page-home'        => 'Home page',
		'home-market'      => 'Hope Market bar, home shop and services',
		'page-about'       => 'About page',
		'page-impact'      => 'Impact page',
		'page-support'     => 'Donate page',
		'giving-form'      => 'Donation form',
		'page-get-involved'=> 'Get Involved page',
		'page-partners'    => 'Partners page',
		'page-contact'     => 'Contact page',
		'enquiry-form'     => 'Contact form',
		'page-approach'    => 'Our Approach page',
		'page-leadership'  => 'Leadership page',
		'page-accountability' => 'Accountability page',
		'page-strategy'    => 'Strategic Plan page',
		'page-resources'   => 'Resources page',
		'page-gallery'     => 'Gallery page',
		'page-programmes'  => 'Programmes page',
		'legal-'           => 'Policy pages',
		'header'           => 'Header',
		'footer'           => 'Footer',
		'hero'             => 'Home page slider',
		'cta'              => 'Call-to-action boxes',
		'single-cohf_programme' => 'Programme pages',
		'single-cohf_story'     => 'Story pages',
		'shop'             => 'Shop',
	);
	foreach ( $map as $needle => $label ) {
		if ( false !== strpos( $file, $needle ) ) {
			return $label;
		}
	}
	return 'Other';
}

/** All theme strings, grouped by area (cached for a day, refreshed on update). */
function cohf_st_strings() {
	$key    = 'cohf_st_strings_' . COHF_CHILD_VERSION;
	$cached = get_transient( $key );
	if ( is_array( $cached ) ) {
		return $cached;
	}
	$dir   = get_stylesheet_directory();
	$files = array_merge( glob( $dir . '/*.php' ), glob( $dir . '/page-templates/*.php' ), glob( $dir . '/template-parts/*.php' ), glob( $dir . '/inc/*.php' ) );
	$skip  = array( 'site-text', 'seeds', 'admin-', 'customizer', 'custom-fields', 'custom-post-types', 'content-defaults', 'redirects', 'anti-spam', 'security', 'performance', 'giving-records', 'giving-checkout', 'schema', 'seo', 'rank-math', 'media', 'photos', 'nav-structure', 'page-body' );
	$out   = array();
	foreach ( $files as $f ) {
		$base = basename( $f, '.php' );
		foreach ( $skip as $s ) {
			if ( false !== strpos( $base, $s ) ) {
				continue 2;
			}
		}
		$src = (string) file_get_contents( $f ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		if ( preg_match_all( "/(?:__|_e|esc_html__|esc_html_e|esc_attr__|esc_attr_e)\(\s*'((?:[^'\\\\]|\\\\.){3,})'\s*,\s*'cohf-child'/", $src, $m ) ) {
			$area = cohf_st_area( $base );
			foreach ( $m[1] as $s ) {
				$s = stripslashes( $s );
				$out[ $area ][ $s ] = true;
			}
		}
	}
	ksort( $out );
	$result = array();
	foreach ( $out as $area => $list ) {
		$result[ $area ] = array_keys( $list );
	}
	set_transient( $key, $result, DAY_IN_SECONDS );
	return $result;
}

add_action( 'admin_menu', function () {
	add_submenu_page( 'cohf-home', __( 'Site Text', 'cohf-child' ), __( 'Site Text', 'cohf-child' ), 'manage_options', 'cohf-site-text', 'cohf_st_page' );
}, 30 );

/** Save. */
add_action( 'admin_post_cohf_site_text', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Sorry, you are not allowed to do that.', 'cohf-child' ) );
	}
	check_admin_referer( 'cohf_site_text' );
	$orig = isset( $_POST['orig'] ) ? (array) wp_unslash( $_POST['orig'] ) : array(); // phpcs:ignore WordPress.Security.ValidSanitizeInput.InputNotSanitized
	$new  = isset( $_POST['new'] ) ? (array) wp_unslash( $_POST['new'] ) : array(); // phpcs:ignore WordPress.Security.ValidSanitizeInput.InputNotSanitized
	$save = get_option( 'cohf_text_overrides', array() );
	$save = is_array( $save ) ? $save : array();
	foreach ( $orig as $i => $o ) {
		$o = (string) $o;
		$v = isset( $new[ $i ] ) ? trim( wp_kses_post( (string) $new[ $i ] ) ) : '';
		if ( '' === $v || $v === $o ) {
			unset( $save[ $o ] );
		} else {
			$save[ $o ] = $v;
		}
	}
	update_option( 'cohf_text_overrides', $save, true );
	wp_safe_redirect( add_query_arg( 'updated', '1', admin_url( 'admin.php?page=cohf-site-text' ) ) );
	exit;
} );

/** Admin screen. */
function cohf_st_page() {
	$o = cohf_st_overrides();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Site Text', 'cohf-child' ); ?></h1>
		<p><?php esc_html_e( 'Change any wording on the website. Type your new wording in the box next to the original and click Save. Leave a box empty to keep the original. Keep any %s or %d exactly as they are: they are filled in automatically.', 'cohf-child' ); ?></p>
		<?php if ( isset( $_GET['updated'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
			<div class="notice notice-success"><p><?php esc_html_e( 'Saved. Your changes are live.', 'cohf-child' ); ?></p></div>
		<?php endif; ?>
		<p><input type="search" id="cohf-st-search" class="regular-text" placeholder="<?php esc_attr_e( 'Search for a word or sentence...', 'cohf-child' ); ?>"></p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="cohf_site_text">
			<?php wp_nonce_field( 'cohf_site_text' ); ?>
			<?php
			$i = 0;
			foreach ( cohf_st_strings() as $area => $list ) :
				?>
				<details class="cohf-st-area" open><summary style="font-size:15px;font-weight:600;margin:18px 0 8px;cursor:pointer"><?php echo esc_html( $area ); ?> (<?php echo (int) count( $list ); ?>)</summary>
				<table class="widefat striped"><tbody>
				<?php foreach ( $list as $s ) : ?>
					<tr class="cohf-st-row">
						<td style="width:45%"><?php echo esc_html( $s ); ?><input type="hidden" name="orig[<?php echo (int) $i; ?>]" value="<?php echo esc_attr( $s ); ?>"></td>
						<td><textarea name="new[<?php echo (int) $i; ?>]" rows="<?php echo strlen( $s ) > 90 ? 3 : 1; ?>" style="width:100%" placeholder="<?php esc_attr_e( 'Keep original', 'cohf-child' ); ?>"><?php echo esc_textarea( isset( $o[ $s ] ) ? $o[ $s ] : '' ); ?></textarea></td>
					</tr>
					<?php
					++$i;
				endforeach;
				?>
				</tbody></table></details>
			<?php endforeach; ?>
			<p style="position:sticky;bottom:0;background:#f0f0f1;padding:12px 0"><?php submit_button( __( 'Save changes', 'cohf-child' ), 'primary', 'submit', false ); ?></p>
		</form>
	</div>
	<script>
	document.getElementById('cohf-st-search').addEventListener('input', function () {
		var q = this.value.toLowerCase();
		document.querySelectorAll('.cohf-st-row').forEach(function (r) {
			r.style.display = r.textContent.toLowerCase().indexOf(q) > -1 || r.querySelector('textarea').value.toLowerCase().indexOf(q) > -1 ? '' : 'none';
		});
	});
	</script>
	<?php
}
