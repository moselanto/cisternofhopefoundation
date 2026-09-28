<?php
/**
 * Starter block content for pages that opt into block editing.
 *
 * Ticking "Build this page with blocks" on an empty page leaves the editor
 * with a blank canvas and the page's real copy still locked inside PHP. The
 * copy is the thing the Foundation actually wants to edit, so the useful move
 * is to hand it over, already laid out, as ordinary blocks.
 *
 * Seeds are built from the same functions the templates read - for example
 * cohf_partner_types() and cohf_partnership_offers() - so the starter content
 * cannot drift away from what the hardcoded page renders today.
 *
 * Sections that are not copy stay dynamic through shortcodes. The enquiry
 * form carries a nonce and a honeypot, and the partner list is a live query;
 * freezing either into static blocks would break it. See inc/shortcodes.php.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/* -------------------------------------------------------------------------
 * Small builders for core block markup.
 *
 * Hand-written block comments must match what the editor expects or the block
 * is flagged as invalid, so class names here mirror current core output:
 * wp-block-heading on headings, wp-block-list on lists, wp-block-group on
 * groups. Paragraphs carry no core class.
 * ---------------------------------------------------------------------- */

/**
 * A paragraph block.
 *
 * @param string $text  Text content.
 * @param string $class Optional class name.
 * @return string
 */
function cohf_seed_p( $text, $class = '' ) {
	$attrs = $class ? ' {"className":"' . $class . '"}' : '';
	$open  = $class ? '<p class="' . esc_attr( $class ) . '">' : '<p>';

	return "<!-- wp:paragraph{$attrs} -->\n{$open}" . wp_kses_post( $text ) . "</p>\n<!-- /wp:paragraph -->\n\n";
}

/**
 * A heading block.
 *
 * @param string $text  Text content.
 * @param int    $level Heading level.
 * @return string
 */
function cohf_seed_h( $text, $level = 2 ) {
	$attrs = ( 2 === $level ) ? '' : ' {"level":' . (int) $level . '}';

	return "<!-- wp:heading{$attrs} -->\n<h{$level} class=\"wp-block-heading\">" . esc_html( $text ) . "</h{$level}>\n<!-- /wp:heading -->\n\n";
}

/**
 * A group block wrapper.
 *
 * @param string $inner   Inner block markup.
 * @param string $classes Class names.
 * @param string $tag     HTML tag.
 * @param string $anchor  Optional id.
 * @return string
 */
function cohf_seed_group( $inner, $classes = '', $tag = 'div', $anchor = '' ) {
	$json = array();

	if ( 'div' !== $tag ) {
		$json['tagName'] = $tag;
	}
	if ( $anchor ) {
		$json['anchor'] = $anchor;
	}
	if ( $classes ) {
		$json['className'] = $classes;
	}
	$json['layout'] = array( 'type' => 'default' );

	$attrs = ' ' . wp_json_encode( $json );
	$class = trim( 'wp-block-group ' . $classes );
	$id    = $anchor ? ' id="' . esc_attr( $anchor ) . '"' : '';

	return "<!-- wp:group{$attrs} -->\n<{$tag} class=\"{$class}\"{$id}>\n{$inner}</{$tag}>\n<!-- /wp:group -->\n\n";
}

/**
 * A checklist. Two columns by default.
 *
 * @param string[] $items   List items.
 * @param string   $classes Class names on the list.
 * @return string
 */
function cohf_seed_checklist( $items, $classes = 'list-check list-check--2col' ) {
	$out = '';

	foreach ( $items as $item ) {
		$out .= "<!-- wp:list-item -->\n<li>" . esc_html( $item ) . "</li>\n<!-- /wp:list-item -->\n";
	}

	return '<!-- wp:list {"className":"' . $classes . "\"} -->\n"
		. '<ul class="wp-block-list ' . esc_attr( $classes ) . "\">\n{$out}</ul>\n"
		. "<!-- /wp:list -->\n\n";
}

/**
 * A shortcode block.
 *
 * @param string $shortcode Shortcode text.
 * @return string
 */
function cohf_seed_shortcode( $shortcode ) {
	return "<!-- wp:shortcode -->\n{$shortcode}\n<!-- /wp:shortcode -->\n\n";
}

/**
 * A grid of simple cards.
 *
 * @param array<int,array{0:string,1:string}> $cards Title and body pairs.
 * @return string
 */
function cohf_seed_cards( $cards ) {
	$inner = '';

	foreach ( $cards as $card ) {
		$body = cohf_seed_group(
			cohf_seed_h( $card[0], 3 ) . cohf_seed_p( $card[1] ),
			'card-body'
		);

		$inner .= cohf_seed_group( $body, 'card', 'article' );
	}

	return cohf_seed_group( $inner, 'grid' );
}

/**
 * A grid of numbered route cards, each ending in an arrow link.
 *
 * @param array<int,array{0:string,1:string,2:string,3:string}> $routes Number, title, text, url.
 * @param string                                                $link_label Arrow link text.
 * @return string
 */
function cohf_seed_route_cards( $routes, $link_label ) {
	$inner = '';

	foreach ( $routes as $route ) {
		$body = cohf_seed_p( $route[0], 'kicker' )
			. cohf_seed_h( $route[1], 3 )
			. cohf_seed_p( $route[2] );

		// Skip the link rather than point it nowhere when the page is missing.
		if ( ! empty( $route[3] ) ) {
			$body .= cohf_seed_p(
				'<a class="arrow" href="' . esc_url( $route[3] ) . '">' . esc_html( $link_label ) . '</a>'
			);
		}

		$inner .= cohf_seed_group( cohf_seed_group( $body, 'card-body' ), 'card', 'article' );
	}

	return cohf_seed_group( $inner, 'grid' );
}

/**
 * A row of headline facts.
 *
 * @param array<int,array{0:string,1:string}> $facts Figure and label pairs.
 * @return string
 */
function cohf_seed_facts( $facts ) {
	$inner = '';

	foreach ( $facts as $fact ) {
		$inner .= cohf_seed_group(
			cohf_seed_p( '<strong>' . esc_html( $fact[0] ) . '</strong><span>' . esc_html( $fact[1] ) . '</span>' ),
			'fact'
		);
	}

	return cohf_seed_group( $inner, 'facts' );
}

/**
 * A link styled as a button.
 *
 * Returns an empty string when the target page does not exist, so a seed
 * never plants a link to nowhere.
 *
 * @param string $label    Link text.
 * @param string $template Template file of the target page.
 * @param string $style    Button style class.
 * @return string
 */
function cohf_seed_btn( $label, $template, $style = 'dark' ) {
	$url = function_exists( 'cohf_page_url' ) ? cohf_page_url( $template ) : '';

	if ( ! $url ) {
		return '';
	}

	return cohf_seed_p(
		'<a class="btn ' . esc_attr( $style ) . '" href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a>'
	);
}

/**
 * A trio of statement cards.
 *
 * @param array<int,array{0:string,1:string}> $items Title and body pairs.
 * @return string
 */
function cohf_seed_purpose_trio( $items ) {
	$inner = '';

	foreach ( $items as $item ) {
		$inner .= cohf_seed_group(
			cohf_seed_h( $item[0], 3 ) . cohf_seed_p( $item[1] ),
			'',
			'article'
		);
	}

	return cohf_seed_group( $inner, 'purpose' );
}

/**
 * A two-column table.
 *
 * Body cells are td rather than th. A row header would be better markup, but
 * the core table block only recognises header cells in thead and flags the
 * rest as invalid; an editor meeting a "this block contains unexpected
 * content" warning on first open is the worse outcome. The first column is
 * styled as a header in CSS.
 *
 * @param string   $head_a First column heading.
 * @param string   $head_b Second column heading.
 * @param string[] $rows   Map of first column => second column.
 * @return string
 */
function cohf_seed_table( $head_a, $head_b, $rows ) {
	$body = '';

	foreach ( $rows as $left => $right ) {
		$body .= '<tr><td>' . esc_html( $left ) . '</td><td>' . esc_html( $right ) . "</td></tr>\n";
	}

	return "<!-- wp:table {\"className\":\"cohf-table\"} -->\n"
		. '<figure class="wp-block-table cohf-table"><table><thead><tr><th>'
		. esc_html( $head_a ) . '</th><th>' . esc_html( $head_b )
		. "</th></tr></thead><tbody>\n{$body}</tbody></table></figure>\n"
		. "<!-- /wp:table -->\n\n";
}

/**
 * A raw HTML block.
 *
 * Reserved for structures core blocks cannot express without changing how
 * they look. The text stays editable; only the wrapper is fixed.
 *
 * @param string $html Markup.
 * @return string
 */
function cohf_seed_html( $html ) {
	return "<!-- wp:html -->\n{$html}\n<!-- /wp:html -->\n\n";
}

/**
 * A section heading pair: kicker plus title.
 *
 * @param string $kicker  Small label.
 * @param string $title   Heading.
 * @param string $lede    Optional supporting paragraph.
 * @param string $classes Extra classes on the section head.
 * @param string $anchor  Optional id.
 * @return string
 */
function cohf_seed_section_head( $kicker, $title, $lede = '', $classes = '', $anchor = '' ) {
	$inner = cohf_seed_group(
		cohf_seed_p( $kicker, 'kicker' ) . cohf_seed_h( $title ),
		''
	);

	if ( $lede ) {
		$inner .= cohf_seed_p( $lede );
	}

	return cohf_seed_group( $inner, trim( 'section-head ' . $classes ), 'div', $anchor );
}

/* -------------------------------------------------------------------------
 * Seeds
 * ---------------------------------------------------------------------- */

/**
 * Which page templates have starter content available.
 *
 * @return array<string,string> Template file => builder function.
 */
function cohf_page_seed_map() {
	return apply_filters( 'cohf_page_seed_map', array(
		'page-templates/page-partners.php'   => 'cohf_seed_partners',
		'page-templates/page-approach.php'   => 'cohf_seed_approach',
		'page-templates/page-contact.php'    => 'cohf_seed_contact',
		'page-templates/page-about.php'        => 'cohf_seed_about',
		'page-templates/page-programmes.php'   => 'cohf_seed_programmes',
		'page-templates/page-get-involved.php' => 'cohf_seed_get_involved',
		'page-templates/page-resources.php'    => 'cohf_seed_resources',
		'page-templates/page-leadership.php'   => 'cohf_seed_leadership',
		'page-templates/page-impact.php'       => 'cohf_seed_impact',
	) );
}

/**
 * Starter block content for a page template.
 *
 * @param string $template Template file, e.g. page-templates/page-partners.php.
 * @return string Block markup, or an empty string when none is defined.
 */
function cohf_page_seed_for( $template ) {
	$map = cohf_page_seed_map();

	if ( empty( $map[ $template ] ) || ! is_callable( $map[ $template ] ) ) {
		return '';
	}

	return (string) call_user_func( $map[ $template ] );
}

/**
 * The Partners page, as blocks.
 *
 * Mirrors page-templates/page-partners.php section for section. The partner
 * list and the enquiry form stay dynamic.
 *
 * @return string
 */
function cohf_seed_partners() {

	// 1. Our message to partners.
	$message = cohf_seed_group(
		cohf_seed_p( __( 'Our message to partners', 'cohf-child' ), 'kicker' )
		. cohf_seed_h( __( 'A young but determined Kenyan organisation.', 'cohf-child' ) )
		. cohf_seed_p( __( 'We are not presenting ourselves as an organisation that has already solved the problems we seek to address. We are presenting ourselves as an organisation that has started, has learned from the communities we serve, has demonstrated the willingness to act, and is now building the systems and partnerships required to increase our impact responsibly.', 'cohf-child' ) )
		. cohf_seed_p( __( 'Our early work with women, youth and children has given us practical experience. Our Constitution gives us an institutional foundation. Our 2026-2030 strategy gives us direction. Our partnerships will give us the opportunity to take solutions further.', 'cohf-child' ) )
		. cohf_seed_p( __( 'We invite partners to walk with us: not simply to fund activities, but to help build lasting pathways.', 'cohf-child' ), 'quote' )
	);

	$section_message = cohf_seed_group(
		cohf_seed_group(
			$message . cohf_seed_shortcode( '[cohf_image key="programme-09"]' ),
			'container feature'
		),
		'',
		'section',
		'message'
	);

	// 2. Who we work with.
	$section_who = cohf_seed_group(
		cohf_seed_group(
			cohf_seed_section_head(
				__( 'Who we work with', 'cohf-child' ),
				__( 'The partners we welcome.', 'cohf-child' )
			)
			. cohf_seed_checklist( function_exists( 'cohf_partner_types' ) ? cohf_partner_types() : array() ),
			'container'
		),
		'cream',
		'section',
		'who-we-partner-with'
	);

	// 3. Opportunities and priority areas.
	$areas = array(
		__( 'Women and girls\' economic empowerment', 'cohf-child' ),
		__( 'Youth skills, employment and entrepreneurship', 'cohf-child' ),
		__( 'Education and child development', 'cohf-child' ),
		__( 'Health, nutrition and community wellbeing', 'cohf-child' ),
		__( 'Agriculture, food security and livelihoods', 'cohf-child' ),
		__( 'Environment, climate and conservation', 'cohf-child' ),
		__( 'Water, sanitation and hygiene', 'cohf-child' ),
		__( 'Digital inclusion and innovation', 'cohf-child' ),
		__( 'Community development and resilience', 'cohf-child' ),
		__( 'Institutional strengthening, safeguarding, M&E and organisational development', 'cohf-child' ),
	);

	$section_opps = cohf_seed_group(
		cohf_seed_group(
			cohf_seed_section_head(
				__( 'Partnership opportunities', 'cohf-child' ),
				__( 'What we invite partners to contribute.', 'cohf-child' )
			)
			. cohf_seed_checklist( function_exists( 'cohf_partnership_offers' ) ? cohf_partnership_offers() : array() )
			. cohf_seed_section_head(
				__( 'Priority areas', 'cohf-child' ),
				__( 'Areas where we seek partnerships.', 'cohf-child' ),
				'',
				'section-head--stacked',
				'priority-areas'
			)
			. cohf_seed_checklist( $areas ),
			'container'
		),
		'',
		'section',
		'opportunities'
	);

	// 4. Partnership snapshot.
	$rows = array(
		__( 'Who are you?', 'cohf-child' )                         => __( 'Cistern of Hope Foundation (COHF), a Kenyan organisation focused on poverty eradication through community empowerment.', 'cohf-child' ),
		__( 'What is your mission?', 'cohf-child' )                => function_exists( 'cohf_org_get' ) ? cohf_org_get( 'mission' ) : '',
		__( 'Who do you serve?', 'cohf-child' )                    => __( 'Vulnerable children, adolescents, youth, women, people with disabilities, households and communities in Kenya.', 'cohf-child' ),
		__( 'What are your key programme areas?', 'cohf-child' )   => __( 'Education, youth empowerment, women\'s economic empowerment, health and wellbeing, livelihoods, agriculture and food security, environment, WASH, digital inclusion and community development.', 'cohf-child' ),
		__( 'What experience do you have?', 'cohf-child' )         => __( 'Women and youth enterprise support, monthly sanitary-pad support, education support, feeding programmes, youth counselling and mentorship, and community outreach.', 'cohf-child' ),
		__( 'What do you seek from partners?', 'cohf-child' )      => __( 'Funding, technical expertise, training, equipment, market linkages, mentorship, research and evaluation, co-funding and institutional strengthening.', 'cohf-child' ),
		__( 'What makes your approach distinctive?', 'cohf-child' ) => __( 'We connect immediate support with empowerment, opportunity, resilience and self-reliance under one poverty-eradication mission.', 'cohf-child' ),
	);

	$body = '';
	foreach ( $rows as $question => $answer ) {
		$body .= '<tr><td>' . esc_html( $question ) . '</td><td>' . esc_html( $answer ) . "</td></tr>\n";
	}

	$table = "<!-- wp:table {\"className\":\"cohf-table\"} -->\n"
		. "<figure class=\"wp-block-table cohf-table\"><table><thead><tr><th>"
		. esc_html__( 'Question', 'cohf-child' ) . '</th><th>' . esc_html__( 'Our answer', 'cohf-child' )
		. "</th></tr></thead><tbody>\n{$body}</tbody></table></figure>\n"
		. "<!-- /wp:table -->\n\n";

	$section_snapshot = cohf_seed_group(
		cohf_seed_group(
			cohf_seed_section_head(
				__( 'Partnership snapshot', 'cohf-child' ),
				__( 'The questions partners ask us.', 'cohf-child' )
			) . $table,
			'container'
		),
		'sage',
		'section',
		'snapshot'
	);

	// 5. Confirmed partners - live query.
	$section_partners = cohf_seed_shortcode( '[cohf_partners]' );

	// 6. Enquiry - live form.
	$section_enquire = cohf_seed_group(
		cohf_seed_group(
			cohf_seed_section_head(
				__( 'Start a conversation', 'cohf-child' ),
				__( 'Partnership enquiry.', 'cohf-child' ),
				__( 'Tell us about your organisation and the kind of partnership you are considering. A member of the team will respond.', 'cohf-child' )
			)
			. cohf_seed_shortcode( '[cohf_enquiry_form type="partnership"]' ),
			'container'
		),
		'',
		'section',
		'enquire'
	);

	return $section_message . $section_who . $section_opps . $section_snapshot . $section_partners . $section_enquire;
}

/**
 * The Our Approach page, as blocks.
 *
 * Mirrors page-templates/page-approach.php. The approach steps, theory of
 * change and closing call to action are shared sections used on other pages
 * too, so they stay as shortcodes rather than being copied in as static text.
 *
 * @return string
 */
function cohf_seed_approach() {

	// 1. The approach steps.
	$section_steps = cohf_seed_group(
		cohf_seed_group( cohf_seed_shortcode( '[cohf_approach]' ), 'container' ),
		'sage',
		'section'
	);

	// 2. Theory of change - renders its own section wrapper.
	$section_toc = cohf_seed_shortcode( '[cohf_theory_of_change]' );

	// 3. How we work.
	$how = cohf_seed_group(
		cohf_seed_p( __( 'How we work', 'cohf-child' ), 'kicker' )
		. cohf_seed_h( __( 'Communities are partners, not recipients.', 'cohf-child' ) )
		. cohf_seed_p( __( 'We work with qualified professionals and appropriate institutions wherever services require clinical expertise, diagnosis, treatment or other regulated practice. Our role includes community mobilisation, awareness, outreach coordination, referral and follow-up.', 'cohf-child' ) )
		. cohf_seed_p( __( 'Sustainable change requires knowledge, mentorship, supportive relationships, access to opportunity and follow-up.', 'cohf-child' ), 'quote' )
	);

	$section_how = cohf_seed_group(
		cohf_seed_group(
			$how . cohf_seed_shortcode( '[cohf_image key="programme-11"]' ),
			'container feature'
		),
		'cream',
		'section'
	);

	// 4. Closing call to action.
	$section_cta = cohf_seed_shortcode( '[cohf_cta]' );

	return $section_steps . $section_toc . $section_how . $section_cta;
}

/**
 * The Contact page, as blocks.
 *
 * Mirrors page-templates/page-contact.php. The address, phone and email are
 * left as [cohf_contact_details] so they keep reading from Foundation >
 * Organisation details; freezing them here would mean a future phone number
 * change never reaching this page.
 *
 * @return string
 */
function cohf_seed_contact() {

	$details = cohf_seed_group(
		cohf_seed_p( __( 'Contact details', 'cohf-child' ), 'kicker' )
		. cohf_seed_h( __( 'Let\'s build lasting change together.', 'cohf-child' ) )
		. cohf_seed_shortcode( '[cohf_contact_details]' )
		. cohf_seed_p(
			'<b>' . esc_html__( 'Raising a concern', 'cohf-child' ) . '</b><br>'
			. esc_html__( 'To raise a safeguarding concern or make a complaint, select "Complaint or feedback" in the form. Concerns are treated seriously and confidentially.', 'cohf-child' ),
			'callout stack-md'
		)
	);

	return cohf_seed_group(
		cohf_seed_group(
			$details . cohf_seed_shortcode( '[cohf_enquiry_form type="general"]' ),
			'container story'
		),
		'',
		'section',
		'enquire'
	);
}

/**
 * The About page, as blocks.
 *
 * Mirrors page-templates/page-about.php. Founded and registered years are
 * read from Organisation details at seed time so the facts row starts out
 * matching the rest of the site.
 *
 * @return string
 */
function cohf_seed_about() {

	$org_get = static function ( $key ) {
		return function_exists( 'cohf_org_get' ) ? cohf_org_get( $key ) : '';
	};

	// 1. Why we exist.
	$why = cohf_seed_group(
		cohf_seed_p( __( 'Why we exist', 'cohf-child' ), 'kicker' )
		. cohf_seed_h( __( 'Poverty should not define a person\'s future.', 'cohf-child' ) )
		. cohf_seed_p( __( 'We work with vulnerable children, young people, women and communities, responding to immediate needs while creating pathways toward sustainable livelihoods, education, wellbeing, resilience and self-reliance.', 'cohf-child' ) )
		. cohf_seed_btn( __( 'See Our Approach', 'cohf-child' ), 'page-templates/page-approach.php', 'dark' ),
		'story-copy'
	);

	$section_why = cohf_seed_group(
		cohf_seed_group(
			cohf_seed_shortcode( '[cohf_image key="programme-02"]' ) . $why,
			'container story'
		),
		'',
		'section'
	);

	// 2. Purpose.
	$section_purpose = cohf_seed_group(
		cohf_seed_group(
			cohf_seed_section_head(
				__( 'Our purpose', 'cohf-child' ),
				__( 'Vision, mission and motto.', 'cohf-child' )
			)
			. cohf_seed_shortcode( '[cohf_purpose]' ),
			'container'
		),
		'sage',
		'section'
	);

	// 3. Values.
	$values = array(
		array( __( 'Empathy', 'cohf-child' ), __( 'Understanding people\'s circumstances and responding with compassion, dignity and humanity.', 'cohf-child' ) ),
		array( __( 'Integrity', 'cohf-child' ), __( 'Honesty, transparency, responsible use of resources and accountability.', 'cohf-child' ) ),
		array( __( 'Sustainability', 'cohf-child' ), __( 'Solutions that build capacity, self-reliance and long-term community development.', 'cohf-child' ) ),
		array( __( 'Collaboration', 'cohf-child' ), __( 'Working with communities, government, donors and partners to increase impact.', 'cohf-child' ) ),
		array( __( 'Respect', 'cohf-child' ), __( 'Upholding dignity, rights, inclusion and the equal worth of every person.', 'cohf-child' ) ),
		array( __( 'Innovation', 'cohf-child' ), __( 'Remaining open to practical and creative ways of addressing changing community challenges.', 'cohf-child' ) ),
	);

	$section_values = cohf_seed_group(
		cohf_seed_group(
			cohf_seed_section_head(
				__( 'Our values', 'cohf-child' ),
				__( 'How we want to work.', 'cohf-child' )
			)
			. cohf_seed_cards( $values ),
			'container'
		),
		'cream',
		'section'
	);

	// 4. Journey.
	$facts = array(
		array( $org_get( 'founded' ), __( 'Founded in Uthiru, Nairobi', 'cohf-child' ) ),
		array( $org_get( 'registered' ), __( 'Registered under the Registrar of Societies', 'cohf-child' ) ),
		array( '12', __( 'Connected programme areas', 'cohf-child' ) ),
	);

	$journey = cohf_seed_group(
		cohf_seed_p( __( 'Our journey', 'cohf-child' ), 'kicker' )
		. cohf_seed_h( __( 'A young organisation with a clear direction.', 'cohf-child' ) )
		. cohf_seed_p( __( 'Our Constitution gives us an institutional foundation, and our 2026-2030 strategy gives us direction. We are building the systems and partnerships required to increase our impact responsibly.', 'cohf-child' ) )
		. cohf_seed_facts( $facts )
		. cohf_seed_btn( __( 'Leadership and Governance', 'cohf-child' ), 'page-templates/page-leadership.php', 'outline' )
	);

	$section_journey = cohf_seed_group(
		cohf_seed_group(
			$journey . cohf_seed_shortcode( '[cohf_image key="programme-12"]' ),
			'container feature'
		),
		'',
		'section'
	);

	return $section_why . $section_purpose . $section_values . $section_journey . cohf_seed_shortcode( '[cohf_cta]' );
}

/**
 * The Programmes page, as blocks.
 *
 * Mirrors page-templates/page-programmes.php. The programme grid is a live
 * query over the Programmes content type and keeps its audience filter bar,
 * so it stays a shortcode rather than twelve frozen cards.
 *
 * @return string
 */
function cohf_seed_programmes() {

	// 1. The programme grid, with its audience filter.
	$section_grid = cohf_seed_group(
		cohf_seed_group(
			cohf_seed_shortcode( '[cohf_programmes count="24" filter="yes"]' ),
			'container'
		),
		'',
		'section'
	);

	// 2. How programmes connect.
	$connect = cohf_seed_group(
		cohf_seed_p( __( 'How our programmes connect', 'cohf-child' ), 'kicker' )
		. cohf_seed_h( __( 'One poverty-eradication mission.', 'cohf-child' ) )
		. cohf_seed_p( __( 'A child who receives school support needs more than fees alone; a young person needs more than a training certificate; a woman starting a business needs more than start-up capital.', 'cohf-child' ) )
		. cohf_seed_p( __( 'We work with qualified professionals and appropriate institutions wherever services require clinical expertise, diagnosis, treatment or other regulated practice.', 'cohf-child' ) )
	);

	$section_connect = cohf_seed_group(
		cohf_seed_group(
			cohf_seed_shortcode( '[cohf_image key="programme-01"]' ) . $connect,
			'container feature'
		),
		'cream',
		'section'
	);

	// 3. Call to action, with wording specific to this page.
	$cta = cohf_seed_shortcode(
		'[cohf_cta title="' . esc_attr__( 'Sponsor a programme area.', 'cohf-child' ) . '"'
		. ' text="' . esc_attr__( 'Fund a defined programme for a defined period, with agreed indicators and reporting. We welcome programme grants, multi-year partnerships, technical assistance and co-funding.', 'cohf-child' ) . '"]'
	);

	return $section_grid . $section_connect . $cta;
}

/**
 * The Get Involved page, as blocks.
 *
 * Mirrors page-templates/page-get-involved.php.
 *
 * Note on wording: the template renders "Volunteer &amp; Mentor" through
 * esc_html(), which double-encodes the entity and shows the raw "&amp;" on
 * the page. The seed spells the word out instead, so the copy an editor
 * inherits is the copy a visitor should have been reading.
 *
 * @return string
 */
function cohf_seed_get_involved() {

	$links   = function_exists( 'cohf_cta_links' ) ? cohf_cta_links() : array();
	$contact = function_exists( 'cohf_page_url' ) ? cohf_page_url( 'page-templates/page-contact.php' ) : '';

	// 1. The three main routes in.
	$routes = array(
		array(
			'01',
			__( 'Partner With Us', 'cohf-child' ),
			__( 'Explore programme, technical, market, research and institutional partnerships.', 'cohf-child' ),
			isset( $links['partner'] ) ? $links['partner'] : '',
		),
		array(
			'02',
			__( 'Support Our Work', 'cohf-child' ),
			__( 'Support programmes and strengthen pathways toward self-reliance.', 'cohf-child' ),
			isset( $links['support'] ) ? $links['support'] : '',
		),
		array(
			'03',
			__( 'Volunteer and Mentor', 'cohf-child' ),
			__( 'Bring your time, skills, relationships or professional expertise.', 'cohf-child' ),
			$contact ? $contact . '#enquire' : '',
		),
	);

	$section_routes = cohf_seed_group(
		cohf_seed_group(
			cohf_seed_route_cards( $routes, __( 'Start a conversation', 'cohf-child' ) ),
			'container'
		),
		'',
		'section'
	);

	// 2. Other ways to help.
	$others = array(
		array( __( 'Sponsor a programme', 'cohf-child' ), __( 'Fund a defined programme area for a defined period, with agreed indicators and reporting.', 'cohf-child' ) ),
		array( __( 'Provide technical support', 'cohf-child' ), __( 'Offer expertise in health, agriculture, WASH, digital skills, monitoring and evaluation or safeguarding.', 'cohf-child' ) ),
		array( __( 'Provide in-kind support', 'cohf-child' ), __( 'Contribute equipment, learning materials, sanitary products, food support or other practical resources.', 'cohf-child' ) ),
		array( __( 'Offer market linkages', 'cohf-child' ), __( 'Connect supported enterprises to buyers, employment opportunities and business networks.', 'cohf-child' ) ),
	);

	$section_others = cohf_seed_group(
		cohf_seed_group(
			cohf_seed_section_head(
				__( 'Other ways to help', 'cohf-child' ),
				__( 'Practical contributions that go further.', 'cohf-child' )
			)
			. cohf_seed_cards( $others ),
			'container'
		),
		'cream',
		'section'
	);

	// 3. Volunteering.
	$volunteering = cohf_seed_group(
		cohf_seed_p( __( 'Volunteering', 'cohf-child' ), 'kicker' )
		. cohf_seed_h( __( 'Volunteers are part of our journey.', 'cohf-child' ) )
		. cohf_seed_p( __( 'We value volunteers not simply as extra hands, but as people who bring skills, relationships, ideas and community knowledge.', 'cohf-child' ) )
		. cohf_seed_p( __( 'We create clear roles, appropriate supervision, ethical standards and safeguarding expectations for everyone working on behalf of the Foundation. Volunteers working with children or vulnerable adults are subject to our Child Safeguarding and Protection Policy and our Code of Conduct.', 'cohf-child' ) )
	);

	$section_volunteering = cohf_seed_group(
		cohf_seed_group(
			cohf_seed_shortcode( '[cohf_image key="programme-04"]' ) . $volunteering,
			'container feature'
		),
		'',
		'section'
	);

	// 4. Enquiry.
	$section_enquire = cohf_seed_group(
		cohf_seed_group(
			cohf_seed_section_head(
				__( 'Start a conversation', 'cohf-child' ),
				__( 'Tell us how you would like to take part.', 'cohf-child' )
			)
			. cohf_seed_shortcode( '[cohf_enquiry_form type="volunteer"]' ),
			'container'
		),
		'sage',
		'section',
		'enquire'
	);

	return $section_routes . $section_others . $section_volunteering . $section_enquire;
}

/**
 * The Resources page, as blocks.
 *
 * Mirrors page-templates/page-resources.php. The document library keeps its
 * search box, type filter, query and pagination through a shortcode.
 *
 * @return string
 */
function cohf_seed_resources() {

	// 1. What you will find here - three category cards, no links.
	$categories = array(
		array(
			__( 'Strategic framework', 'cohf-child' ),
			__( 'Master Institutional Profile and Strategic Programme Framework 2026-2030', 'cohf-child' ),
			__( 'The Foundation\'s five-year direction and programme framework.', 'cohf-child' ),
			'',
		),
		array(
			__( 'Reports', 'cohf-child' ),
			__( 'Programme and Impact Reports', 'cohf-child' ),
			__( 'Published here with dates and downloads as each reporting cycle completes.', 'cohf-child' ),
			'',
		),
		array(
			__( 'Policies', 'cohf-child' ),
			__( 'Safeguarding and Accountability', 'cohf-child' ),
			__( 'Child safeguarding, data protection, financial management and related policies.', 'cohf-child' ),
			'',
		),
	);

	$section_categories = cohf_seed_group(
		cohf_seed_group(
			cohf_seed_section_head(
				__( 'What you will find here', 'cohf-child' ),
				__( 'Three kinds of document.', 'cohf-child' ),
				__( 'Documents are published as each is finalised and approved. Partners and institutions can request anything not yet published.', 'cohf-child' )
			)
			. cohf_seed_route_cards( $categories, '' ),
			'container'
		),
		'',
		'section'
	);

	// 2. The library itself.
	$section_library = cohf_seed_group(
		cohf_seed_group(
			cohf_seed_section_head(
				__( 'Document library', 'cohf-child' ),
				__( 'Search the library.', 'cohf-child' )
			)
			. cohf_seed_shortcode( '[cohf_resource_library]' ),
			'container'
		),
		'cream',
		'section'
	);

	// 3. Call to action, pointing at Contact.
	$cta = cohf_seed_shortcode(
		'[cohf_cta title="' . esc_attr__( 'Need a document we have not published?', 'cohf-child' ) . '"'
		. ' text="' . esc_attr__( 'Partners and institutions can request our Constitution, strategic framework, policies and programme documentation directly from the Foundation.', 'cohf-child' ) . '"'
		. ' primary_label="' . esc_attr__( 'Contact Us', 'cohf-child' ) . '"'
		. ' primary_page="page-templates/page-contact.php"]'
	);

	return $section_categories . $section_library . $cta;
}

/**
 * The Leadership and Governance page, as blocks.
 *
 * Mirrors page-templates/page-leadership.php. The three tiers and the
 * profile panel stay dynamic through [cohf_leadership].
 *
 * @return string
 */
function cohf_seed_leadership() {

	// 1. The tiers themselves.
	$section_tiers = cohf_seed_group(
		cohf_seed_group( cohf_seed_shortcode( '[cohf_leadership]' ), 'container' ),
		'leadership',
		'section'
	);

	// 2. Governance chain.
	$constitution = function_exists( 'cohf_org_get' ) ? cohf_org_get( 'constitution' ) : '';

	$lede = $constitution
		? sprintf(
			/* translators: %s: constitution adoption date. */
			__( 'Our Constitution, adopted on %s, provides for quarterly Board meetings, annual general meetings and monthly staff meetings.', 'cohf-child' ),
			$constitution
		)
		: __( 'Our Constitution provides for quarterly Board meetings, annual general meetings and monthly staff meetings.', 'cohf-child' );

	$chain = array(
		array( '01', __( 'Members', 'cohf-child' ), __( 'Receive reports at the annual general meeting and hold leadership accountable.', 'cohf-child' ) ),
		array( '02', __( 'Board of Directors', 'cohf-child' ), __( 'Provides oversight and strategic direction, meeting quarterly.', 'cohf-child' ) ),
		array( '03', __( 'Executive Director', 'cohf-child' ), __( 'Accountable to the Board for operations and programme delivery.', 'cohf-child' ) ),
		array( '04', __( 'Management and Operations', 'cohf-child' ), __( 'Deliver programmes and meet monthly to review progress.', 'cohf-child' ) ),
	);

	/*
	 * An ordered list whose items each carry a number, a heading and a
	 * paragraph has no core-block equivalent that keeps this appearance, so
	 * the chain is raw HTML. The wording stays editable.
	 */
	$chain_html = '<ol class="gov-chain">';

	foreach ( $chain as $link ) {
		$chain_html .= '<li class="gov-chain__item">'
			. '<span class="gov-chain__num">' . esc_html( $link[0] ) . '</span>'
			. '<h3>' . esc_html( $link[1] ) . '</h3>'
			. '<p>' . esc_html( $link[2] ) . '</p>'
			. '</li>';
	}

	$chain_html .= '</ol>';

	$section_gov = cohf_seed_group(
		cohf_seed_group(
			cohf_seed_section_head(
				__( 'Governance', 'cohf-child' ),
				__( 'How the Foundation is governed.', 'cohf-child' ),
				$lede
			)
			. cohf_seed_html( $chain_html ),
			'container'
		),
		'sage',
		'section'
	);

	// 3. Accountability trio.
	$trio = array(
		array( __( 'Safeguarding', 'cohf-child' ), __( 'Protecting children and vulnerable people and promoting safe programme environments.', 'cohf-child' ) ),
		array( __( 'Financial accountability', 'cohf-child' ), __( 'Approved budgets, appropriate records and responsible reporting.', 'cohf-child' ) ),
		array( __( 'Responsible communication', 'cohf-child' ), __( 'Protecting beneficiary dignity, confidentiality and responsible use of stories.', 'cohf-child' ) ),
	);

	$section_trio = cohf_seed_group(
		cohf_seed_group(
			cohf_seed_purpose_trio( $trio )
			. cohf_seed_btn(
				__( 'Accountability and Safeguarding', 'cohf-child' ),
				'page-templates/page-accountability.php',
				'dark'
			),
			'container'
		),
		'cream',
		'section'
	);

	return $section_tiers . $section_gov . $section_trio . cohf_seed_shortcode( '[cohf_cta]' );
}

/**
 * The Impact page, as blocks.
 *
 * Mirrors page-templates/page-impact.php. The headline figures stay dynamic
 * through [cohf_impact_numbers] so they keep reading from one place.
 *
 * @return string
 */
function cohf_seed_impact() {

	// 1. Current reported position.
	$section_reported = cohf_seed_group(
		cohf_seed_group(
			cohf_seed_section_head(
				__( 'Current reported position', 'cohf-child' ),
				__( 'What we have already started achieving.', 'cohf-child' ),
				__( 'Our strategic framework grows from work we have already started. These are the figures recorded in our own programme records.', 'cohf-child' )
			)
			. cohf_seed_shortcode( '[cohf_impact_numbers]' )
			. cohf_seed_p(
				__( 'These figures represent the Foundation\'s reported programme experience and current starting point. They are not lifetime totals. Individual projects and donor submissions contain the detailed evidence, dates, locations, budgets and beneficiary records relevant to each intervention.', 'cohf-child' ),
				'impact-disclaimer stack-lg'
			),
			'container'
		),
		'impact',
		'section',
		'reported'
	);

	// 2. Education.
	$education = cohf_seed_group(
		cohf_seed_p( __( 'Education', 'cohf-child' ), 'kicker' )
		. cohf_seed_h( __( 'Helping vulnerable children stay in school.', 'cohf-child' ) )
		. cohf_seed_p( __( 'In June 2026, three children who had been living on the streets were supported to return to school, with ongoing responsibility for their educational needs including school fees, books, learning materials and food support.', 'cohf-child' ) )
		. cohf_seed_p( __( 'Reach people. Restore hope. Create opportunity. Build resilience. Sustain change.', 'cohf-child' ), 'quote' )
	);

	$section_education = cohf_seed_group(
		cohf_seed_group(
			$education . cohf_seed_shortcode( '[cohf_image key="programme-01"]' ),
			'container feature'
		),
		'',
		'section',
		'children'
	);

	// 3. Women and livelihoods.
	$women_items = array(
		__( 'Small-business start-up and strengthening support', 'cohf-child' ),
		__( 'Entrepreneurship and business-management training', 'cohf-child' ),
		__( 'Financial literacy and savings linkages', 'cohf-child' ),
		__( 'Market access and business linkages', 'cohf-child' ),
	);

	$women = cohf_seed_group(
		cohf_seed_p( __( 'Women and livelihoods', 'cohf-child' ), 'kicker' )
		. cohf_seed_h( __( 'Empowerment creates pathways beyond short-term relief.', 'cohf-child' ) )
		. cohf_seed_p( __( 'We measure progress not simply by the number of women trained, but by the extent to which supported women are able to sustain and grow viable economic activities.', 'cohf-child' ) )
		. cohf_seed_checklist( $women_items, 'list-check stack-sm' )
	);

	$section_women = cohf_seed_group(
		cohf_seed_group(
			cohf_seed_shortcode( '[cohf_image key="programme-03"]' ) . $women,
			'container feature'
		),
		'cream',
		'section',
		'women'
	);

	// 4. Across our work.
	$across = array(
		array( __( 'Youth empowerment', 'cohf-child' ), __( 'Through mentorship, counselling, career guidance and life-skills activities we help young people make informed choices and identify pathways towards education, employment and entrepreneurship. These programmes run quarterly.', 'cohf-child' ) ),
		array( __( 'Menstrual dignity', 'cohf-child' ), __( 'Every month we provide sanitary pads to more than 200 girls, reducing absenteeism, discomfort and stigma, and supporting continued participation in school and community life.', 'cohf-child' ) ),
		array( __( 'Community outreach', 'cohf-child' ), __( 'Our documented 19 August 2026 programme at Kabete "N" reached 82 children, including 26 teenagers and 56 children below the teenage years, combining feeding with counselling, mentorship and recreation.', 'cohf-child' ) ),
	);

	$section_across = cohf_seed_group(
		cohf_seed_group(
			cohf_seed_section_head(
				__( 'Across our work', 'cohf-child' ),
				__( 'Where else change is taking hold.', 'cohf-child' )
			)
			. cohf_seed_purpose_trio( $across ),
			'container'
		),
		'sage',
		'section',
		'across'
	);

	// 5. How we measure.
	$measures = array(
		__( 'Reach', 'cohf-child' )          => __( 'Women, youth, children, households and communities reached.', 'cohf-child' ),
		__( 'Activities', 'cohf-child' )     => __( 'Trainings, counselling forums, feeding activities, distributions, enterprise support.', 'cohf-child' ),
		__( 'Outputs', 'cohf-child' )        => __( 'People trained, children supported, businesses established, outreach delivered.', 'cohf-child' ),
		__( 'Outcomes', 'cohf-child' )       => __( 'School participation, enterprise continuation, skills gained, referrals completed.', 'cohf-child' ),
		__( 'Quality', 'cohf-child' )        => __( 'Participant feedback, safeguarding performance, complaints resolution.', 'cohf-child' ),
		__( 'Sustainability', 'cohf-child' ) => __( 'Continued operation of supported enterprises and continuation of benefits after funding.', 'cohf-child' ),
	);

	$section_measure = cohf_seed_group(
		cohf_seed_group(
			cohf_seed_section_head(
				__( 'Monitoring, evaluation and learning', 'cohf-child' ),
				__( 'Beyond counting activities.', 'cohf-child' ),
				__( 'Our approach moves beyond counting activities to understanding change.', 'cohf-child' )
			)
			. cohf_seed_table(
				__( 'What we measure', 'cohf-child' ),
				__( 'Examples', 'cohf-child' ),
				$measures
			),
			'container'
		),
		'',
		'section',
		'how-we-measure'
	);

	// 6. Call to action.
	$cta = cohf_seed_shortcode(
		'[cohf_cta title="' . esc_attr__( 'Help us close the gap between need and capacity.', 'cohf-child' ) . '"'
		. ' text="' . esc_attr__( 'The demand for support is greater than the resources currently available to us. Responsible partnerships allow us to reach more children, young people and women.', 'cohf-child' ) . '"]'
	);

	return $section_reported . $section_education . $section_women . $section_across . $section_measure . $cta;
}
