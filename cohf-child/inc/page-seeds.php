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
 * A checklist, two columns.
 *
 * @param string[] $items List items.
 * @return string
 */
function cohf_seed_checklist( $items ) {
	$out = '';

	foreach ( $items as $item ) {
		$out .= "<!-- wp:list-item -->\n<li>" . esc_html( $item ) . "</li>\n<!-- /wp:list-item -->\n";
	}

	return "<!-- wp:list {\"className\":\"list-check list-check--2col\"} -->\n"
		. "<ul class=\"wp-block-list list-check list-check--2col\">\n{$out}</ul>\n"
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
		'page-templates/page-about.php'      => 'cohf_seed_about',
		'page-templates/page-programmes.php' => 'cohf_seed_programmes',
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
