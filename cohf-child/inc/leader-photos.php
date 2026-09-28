<?php
/**
 * Bundled leadership portraits.
 *
 * The portraits uploaded to the media library are old, low-resolution and
 * heavily sepia-toned, and they arrive in wildly different aspect ratios -
 * anywhere from 0.45 to 1.0 - so the leadership grid never looked even no
 * matter what the CSS did. The Foundation supplied clean colour photographs
 * of all seven people; those have been cropped to a single 4:5 frame at
 * 800x1000 and are shipped with the theme.
 *
 * A theme file takes precedence over the featured image on purpose. The point
 * is to replace the sepia uploads without asking anyone to redo seven media
 * library entries by hand. Two ways to go back:
 *
 * - Delete the file from assets/images/leaders/ and that leader falls back to
 *   the featured image, then to the monogram plate.
 * - Filter 'cohf_leader_photo' and return '' to disable a photo, or a URL to
 *   point somewhere else entirely.
 *
 * Matching is by filename: the leader's post title, minus any honorific,
 * slugged. "Mr. Justus Kubai" resolves to justus-kubai.jpg.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * Slug used to find a bundled portrait for a person.
 *
 * Honorifics are stripped first. They are part of how a name is presented on
 * the page but not part of the person's name, and leaving them in would mean
 * renaming the file every time a title changed.
 *
 * @param string $name Leader's display name.
 * @return string Slug, or '' when the name yields nothing usable.
 */
function cohf_leader_photo_slug( $name ) {
	$name = wp_strip_all_tags( (string) $name );
	$name = preg_replace( '/^\s*(mr|mrs|ms|miss|dr|prof|rev|pr|pastor|eng|hon|sir|madam)\.?\s+/i', '', $name );

	return sanitize_title( $name );
}

/**
 * URL of the bundled portrait for a person, if one ships with the theme.
 *
 * @param string $name Leader's display name.
 * @return string Absolute URL, or '' when no file exists.
 */
function cohf_leader_photo( $name ) {
	$slug = cohf_leader_photo_slug( $name );
	$url  = '';

	if ( $slug ) {
		$relative = '/assets/images/leaders/' . $slug . '.jpg';

		if ( file_exists( COHF_CHILD_DIR . $relative ) ) {
			$url = COHF_CHILD_URI . $relative;
		}
	}

	/**
	 * Filter the resolved portrait URL.
	 *
	 * @param string $url  Resolved URL, or '' when no bundled file exists.
	 * @param string $name Leader's display name.
	 * @param string $slug Slug derived from the name.
	 */
	return apply_filters( 'cohf_leader_photo', $url, $name, $slug );
}
