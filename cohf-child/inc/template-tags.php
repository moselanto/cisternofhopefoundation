<?php
/**
 * Template tags — reusable data helpers used by templates and template parts.
 *
 * Every string returned here traces back to a supplied Foundation document.
 * Nothing is invented.
 *
 * @package COHF_Child
 */

defined( 'ABSPATH' ) || exit;

/**
 * The seven-step poverty-to-self-reliance pathway.
 *
 * Source: Strategic Framework 2026–2030, section 4.1.
 *
 * @return array<int,array<string,string>>
 */
function cohf_approach_steps() {
	return array(
		array( 'num' => '01', 'title' => __( 'Respond', 'cohf-child' ),             'text' => __( 'We respond to genuine and identified needs with compassion and accountability.', 'cohf-child' ) ),
		array( 'num' => '02', 'title' => __( 'Restore dignity', 'cohf-child' ),     'text' => __( 'We address barriers affecting food, education, hygiene, wellbeing and basic stability.', 'cohf-child' ) ),
		array( 'num' => '03', 'title' => __( 'Empower', 'cohf-child' ),             'text' => __( 'We build skills, knowledge, confidence, life skills and enterprise capacity.', 'cohf-child' ) ),
		array( 'num' => '04', 'title' => __( 'Create opportunity', 'cohf-child' ),  'text' => __( 'We connect people to education, markets, employment, enterprise, mentors and services.', 'cohf-child' ) ),
		array( 'num' => '05', 'title' => __( 'Build resilience', 'cohf-child' ),    'text' => __( 'We strengthen the ability of households and individuals to withstand shocks.', 'cohf-child' ) ),
		array( 'num' => '06', 'title' => __( 'Promote self-reliance', 'cohf-child' ),'text' => __( 'We support sustainable income, learning, community ownership and responsible independence.', 'cohf-child' ) ),
		array( 'num' => '07', 'title' => __( 'Create lasting change', 'cohf-child' ),'text' => __( 'We work toward communities in which people increasingly have the means and agency to improve their own lives.', 'cohf-child' ) ),
	);
}

/**
 * The twelve programme areas and their strategic purpose.
 *
 * Source: Strategic Framework 2026–2030, section 7.
 * Used to seed the Programmes content type and as a fallback if none exist yet.
 *
 * @return array<int,array<string,string>>
 */
function cohf_programme_seed() {
	return array(
		array( 'num' => '01', 'title' => __( 'Education, Scholarship & Child Development', 'cohf-child' ), 'purpose' => __( 'To reduce barriers to education and support vulnerable children to learn, develop and pursue a better future.', 'cohf-child' ) ),
		array( 'num' => '02', 'title' => __( 'Youth Skills, Enterprise & Employability', 'cohf-child' ), 'purpose' => __( 'To equip young people with skills, mentorship, entrepreneurship and pathways to employment or self-employment.', 'cohf-child' ) ),
		array( 'num' => '03', 'title' => __( 'Women\'s Enterprise & Economic Empowerment', 'cohf-child' ), 'purpose' => __( 'To strengthen women\'s income opportunities, enterprise capacity, financial resilience and dignity.', 'cohf-child' ) ),
		array( 'num' => '04', 'title' => __( 'Humanitarian Assistance & Household Resilience', 'cohf-child' ), 'purpose' => __( 'To respond to urgent vulnerability while helping households move toward recovery and resilience.', 'cohf-child' ) ),
		array( 'num' => '05', 'title' => __( 'Counselling, Mentorship & Life Skills', 'cohf-child' ), 'purpose' => __( 'To strengthen emotional wellbeing, confidence, decision-making, life skills and positive development.', 'cohf-child' ) ),
		array( 'num' => '06', 'title' => __( 'Menstrual Health, Hygiene & Dignity', 'cohf-child' ), 'purpose' => __( 'To promote menstrual dignity, hygiene knowledge and continued participation in school and community life.', 'cohf-child' ) ),
		array( 'num' => '07', 'title' => __( 'Health, Nutrition & Community Wellbeing', 'cohf-child' ), 'purpose' => __( 'To promote health awareness, nutrition, preventive practices, wellbeing and appropriate referral.', 'cohf-child' ) ),
		array( 'num' => '08', 'title' => __( 'Sustainable Livelihoods, Agriculture & Food Security', 'cohf-child' ), 'purpose' => __( 'To strengthen household food security, income, productive capacity and resilience.', 'cohf-child' ) ),
		array( 'num' => '09', 'title' => __( 'Environment, Climate & Conservation', 'cohf-child' ), 'purpose' => __( 'To promote environmental stewardship, climate resilience and green livelihood opportunities.', 'cohf-child' ) ),
		array( 'num' => '10', 'title' => __( 'Water, Sanitation & Hygiene', 'cohf-child' ), 'purpose' => __( 'To improve hygiene, sanitation, safe-water awareness and community WASH practices.', 'cohf-child' ) ),
		array( 'num' => '11', 'title' => __( 'Digital Inclusion & Innovation', 'cohf-child' ), 'purpose' => __( 'To expand digital skills, information access, digital safety and digital economic opportunity.', 'cohf-child' ) ),
		array( 'num' => '12', 'title' => __( 'Community Development & Partnerships', 'cohf-child' ), 'purpose' => __( 'To strengthen community ownership, local networks, volunteers, referrals and strategic partnerships.', 'cohf-child' ) ),
	);
}

/**
 * Reported programme figures.
 *
 * Source: Strategic Framework 2026–2030, Annex B ("Our Current Reported Reach").
 * These are the Foundation's reported current position, not lifetime totals.
 *
 * @return array<int,array<string,mixed>>
 */
function cohf_impact_figures() {
	return cohf_apply_figure_mods( 'impact', cohf_impact_figures_default() );
}

/**
 * Apply figures edited in Appearance > Customize > Impact figures.
 *
 * An empty field keeps the default, so a figure can never be blanked by
 * accident.
 *
 * @param string $group    'impact' or 'outreach'.
 * @param array  $defaults Default figures.
 * @return array
 */
function cohf_apply_figure_mods( $group, $defaults ) {
	foreach ( $defaults as $i => $figure ) {
		foreach ( array( 'value', 'prefix', 'text', 'label', 'note' ) as $field ) {
			$mod = get_theme_mod( 'cohf_fig_' . $group . '_' . $i . '_' . $field, '' );
			if ( '' === trim( (string) $mod ) ) {
				continue;
			}
			$defaults[ $i ][ $field ] = ( 'value' === $field ) ? (float) $mod : (string) $mod;
		}
	}
	return $defaults;
}

/**
 * Default reported programme figures.
 *
 * @return array
 */
function cohf_impact_figures_default() {
	return array(
		array(
			'value'  => 8,
			'label'  => __( 'women supported to establish small businesses', 'cohf-child' ),
			'note'   => __( 'Businesses currently running.', 'cohf-child' ),
		),
		array(
			'value'  => 6,
			'label'  => __( 'young people supported to establish businesses', 'cohf-child' ),
			'note'   => __( 'Businesses currently running.', 'cohf-child' ),
		),
		array(
			'value'  => 200,
			'prefix' => '~',
			'label'  => __( 'children receiving sanitary pads each month', 'cohf-child' ),
			'note'   => __( 'Ongoing monthly distribution.', 'cohf-child' ),
		),
		array(
			'value'  => 6,
			'label'  => __( 'vulnerable children supported with school fees', 'cohf-child' ),
			'note'   => __( 'Alongside books, materials and other school needs.', 'cohf-child' ),
		),
	);
}

/**
 * Figures recorded for the documented community programme.
 *
 * Source: Strategic Framework 2026–2030, section 6 and Annex B.
 *
 * @return array<int,array<string,mixed>>
 */
function cohf_outreach_figures() {
	return cohf_apply_figure_mods( 'outreach', cohf_outreach_figures_default() );
}

/**
 * Default outreach figures.
 *
 * @return array
 */
function cohf_outreach_figures_default() {
	return array(
		array( 'value' => 82, 'label' => __( 'children reached', 'cohf-child' ), 'note' => __( 'Documented 19 August 2026 community programme.', 'cohf-child' ) ),
		array( 'value' => 26, 'label' => __( 'teenagers among those participants', 'cohf-child' ), 'note' => __( 'Same documented programme.', 'cohf-child' ) ),
		array( 'value' => 56, 'label' => __( 'children below teenage years', 'cohf-child' ), 'note' => __( 'Same documented programme.', 'cohf-child' ) ),
		array( 'value' => 0,  'text' => __( 'Quarterly', 'cohf-child' ), 'label' => __( 'youth counselling forums', 'cohf-child' ), 'note' => __( 'Held alongside mentorship and personal-development activities.', 'cohf-child' ) ),
	);
}

/**
 * The 2026–2030 strategic journey.
 *
 * Source: Strategic Framework 2026–2030, section 19.
 *
 * @return array<int,array<string,string>>
 */
function cohf_strategy_years() {
	return array(
		array( 'year' => '2026', 'theme' => __( 'Establish', 'cohf-child' ),      'text' => __( 'We strengthen our institutional foundation, programme systems, safeguarding, documentation, monitoring and donor readiness while consolidating the work already underway.', 'cohf-child' ) ),
		array( 'year' => '2027', 'theme' => __( 'Consolidate', 'cohf-child' ),    'text' => __( 'We strengthen priority programmes, beneficiary follow-up, partnerships, reporting and resource diversification.', 'cohf-child' ) ),
		array( 'year' => '2028', 'theme' => __( 'Scale & review', 'cohf-child' ), 'text' => __( 'We review our progress, strengthen evidence and technical capacity, and expand programmes that demonstrate strong results.', 'cohf-child' ) ),
		array( 'year' => '2029', 'theme' => __( 'Deepen', 'cohf-child' ),         'text' => __( 'We deepen market linkages, corporate partnerships, outcome measurement, institutional learning and sustainability.', 'cohf-child' ) ),
		array( 'year' => '2030', 'theme' => __( 'Sustain', 'cohf-child' ),        'text' => __( 'We evaluate the five-year journey, document results and lessons, strengthen sustainable models and prepare our next strategic direction.', 'cohf-child' ) ),
	);
}

/**
 * Theory of change.
 *
 * Source: Strategic Framework 2026–2030, section 20.
 *
 * @return array<int,array<string,string>>
 */
function cohf_theory_of_change() {
	return array(
		array( 'level' => __( 'Needs', 'cohf-child' ),               'text' => __( 'Poverty and vulnerability limit income, opportunity, education, health, dignity and resilience.', 'cohf-child' ) ),
		array( 'level' => __( 'Inputs', 'cohf-child' ),              'text' => __( 'Our people, volunteers, funding, partnerships, knowledge, community relationships and systems.', 'cohf-child' ) ),
		array( 'level' => __( 'Activities', 'cohf-child' ),          'text' => __( 'Education support, enterprise support, skills, counselling, health and nutrition activities, livelihoods, WASH, environmental action and community development.', 'cohf-child' ) ),
		array( 'level' => __( 'Outputs', 'cohf-child' ),             'text' => __( 'People supported and trained; children reached; enterprises supported; forums held; outreach conducted; referrals made; community structures engaged.', 'cohf-child' ) ),
		array( 'level' => __( 'Short-term change', 'cohf-child' ),   'text' => __( 'Improved access, knowledge, skills, confidence, dignity and opportunity.', 'cohf-child' ) ),
		array( 'level' => __( 'Medium-term change', 'cohf-child' ),  'text' => __( 'Stronger livelihoods, improved school participation, better practices, stronger wellbeing and increased resilience.', 'cohf-child' ) ),
		array( 'level' => __( 'Long-term change', 'cohf-child' ),    'text' => __( 'More self-reliant individuals, stronger households and resilient communities contributing to poverty reduction.', 'cohf-child' ) ),
	);
}

/**
 * Organisational values.
 *
 * Source: Strategic Framework 2026–2030, section 3.
 *
 * @return array<int,array<string,string>>
 */
function cohf_values() {
	return array(
		array( 'name' => __( 'Empathy', 'cohf-child' ),        'text' => __( 'We seek to understand people\'s circumstances and respond with compassion, dignity and humanity.', 'cohf-child' ) ),
		array( 'name' => __( 'Integrity', 'cohf-child' ),      'text' => __( 'We are committed to honesty, transparency, responsible use of resources and accountability.', 'cohf-child' ) ),
		array( 'name' => __( 'Sustainability', 'cohf-child' ), 'text' => __( 'We favour solutions that build capacity, self-reliance and long-term community development.', 'cohf-child' ) ),
		array( 'name' => __( 'Collaboration', 'cohf-child' ),  'text' => __( 'We work with communities, government, donors, organisations and other partners to increase impact.', 'cohf-child' ) ),
		array( 'name' => __( 'Respect', 'cohf-child' ),        'text' => __( 'We uphold the dignity, rights, inclusion and equal worth of every person.', 'cohf-child' ) ),
		array( 'name' => __( 'Innovation', 'cohf-child' ),     'text' => __( 'We remain open to practical and creative ways of addressing changing community challenges.', 'cohf-child' ) ),
	);
}

/**
 * Accountability and safeguarding pillars.
 *
 * Source: Strategic Framework 2026–2030, sections 23, 24 and 25.
 *
 * @return array<int,array<string,string>>
 */
function cohf_accountability_pillars() {
	return array(
		array( 'id' => 'financial',       'title' => __( 'Financial accountability', 'cohf-child' ),        'text' => __( 'Our Constitution provides for annual budgeting, financial oversight, annual audit by a certified auditor and financial reporting to donors, stakeholders and the public. We work from approved budgets, maintain supporting documentation and monitor expenditure against approved programme budgets.', 'cohf-child' ) ),
		array( 'id' => 'governance',      'title' => __( 'Governance', 'cohf-child' ),                      'text' => __( 'An Executive Board provides oversight and strategic direction while the Executive Director manages day-to-day operations. Our Constitution, adopted on 21 June 2024, provides for quarterly Board meetings, annual general meetings and monthly staff meetings.', 'cohf-child' ) ),
		array( 'id' => 'safeguarding',    'title' => __( 'Safeguarding and child protection', 'cohf-child' ),'text' => __( 'Because we work with children, women, youth and vulnerable communities, safeguarding is central to who we are. We maintain reporting and referral mechanisms, train staff and volunteers on safeguarding expectations, and respond appropriately to concerns and allegations.', 'cohf-child' ) ),
		array( 'id' => 'data-protection', 'title' => __( 'Data protection and privacy', 'cohf-child' ),     'text' => __( 'We protect confidential beneficiary information and promote responsible use of photographs, stories and personal information. We protect the dignity and confidentiality of beneficiaries when documenting and communicating our work.', 'cohf-child' ) ),
		array( 'id' => 'conduct',         'title' => __( 'Code of conduct', 'cohf-child' ),                 'text' => __( 'A Code of Conduct sets the standards expected of everyone working on behalf of the Foundation, including staff and volunteers, with clear roles, appropriate supervision and ethical standards.', 'cohf-child' ) ),
		array( 'id' => 'anti-fraud',      'title' => __( 'Anti-fraud and anti-corruption', 'cohf-child' ),  'text' => __( 'We maintain an Anti-Fraud and Anti-Corruption Policy and separate authorisation and accountability responsibilities as our systems develop.', 'cohf-child' ) ),
		array( 'id' => 'conflict',        'title' => __( 'Conflict of interest', 'cohf-child' ),            'text' => __( 'A Conflict of Interest Policy governs how decisions are made where a personal or organisational interest could affect judgement.', 'cohf-child' ) ),
		array( 'id' => 'whistleblowing',  'title' => __( 'Whistleblowing', 'cohf-child' ),                  'text' => __( 'A Whistleblowing Policy allows concerns to be raised responsibly and without fear, supported by appropriate reporting and referral mechanisms.', 'cohf-child' ) ),
		array( 'id' => 'complaints',      'title' => __( 'Complaints and feedback', 'cohf-child' ),         'text' => __( 'A Complaints and Feedback Mechanism allows communities, beneficiaries, partners and the public to raise concerns and give feedback on our work.', 'cohf-child' ) ),
		array( 'id' => 'mel',             'title' => __( 'Monitoring, evaluation and learning', 'cohf-child' ), 'text' => __( 'Our approach moves beyond counting activities to understanding change. We measure reach, activities, outputs, outcomes, quality and sustainability, using attendance records, beneficiary files, follow-up forms, financial records and photographs held with appropriate consent.', 'cohf-child' ) ),
	);
}

/**
 * The Foundation's institutional policies.
 *
 * Source: Strategic Framework 2026–2030, section 24.2.
 *
 * @return string[]
 */
function cohf_policies() {
	return array(
		__( 'Child Safeguarding and Protection Policy', 'cohf-child' ),
		__( 'PSEA / Safeguarding Policy', 'cohf-child' ),
		__( 'Code of Conduct', 'cohf-child' ),
		__( 'Financial Management Policy', 'cohf-child' ),
		__( 'Procurement Policy', 'cohf-child' ),
		__( 'Anti-Fraud and Anti-Corruption Policy', 'cohf-child' ),
		__( 'Conflict of Interest Policy', 'cohf-child' ),
		__( 'Whistleblowing Policy', 'cohf-child' ),
		__( 'Data Protection and Privacy Policy', 'cohf-child' ),
		__( 'Volunteer Management Policy', 'cohf-child' ),
		__( 'Gender Equality and Social Inclusion Policy', 'cohf-child' ),
		__( 'Health and Safety Policy', 'cohf-child' ),
		__( 'Complaints and Feedback Mechanism', 'cohf-child' ),
		__( 'Monitoring, Evaluation and Learning Framework', 'cohf-child' ),
	);
}

/**
 * Partnership contributions the Foundation invites.
 *
 * Source: Strategic Framework 2026–2030, section 26.2.
 *
 * @return string[]
 */
function cohf_partnership_offers() {
	return array(
		__( 'Project and programme grants', 'cohf-child' ),
		__( 'Multi-year strategic partnerships', 'cohf-child' ),
		__( 'Technical assistance and specialist expertise', 'cohf-child' ),
		__( 'Training and mentorship', 'cohf-child' ),
		__( 'Equipment and in-kind support', 'cohf-child' ),
		__( 'Market and employment linkages', 'cohf-child' ),
		__( 'Research, evaluation and learning partnerships', 'cohf-child' ),
		__( 'Co-funding and consortium opportunities', 'cohf-child' ),
		__( 'Organisational-strengthening support', 'cohf-child' ),
	);
}

/**
 * Confirmed partner organisations, for display on the front end.
 *
 * Reads the cohf_partner content type and returns only entries whose
 * "Partnership confirmed in writing" box is ticked. An empty array is a valid
 * and expected result: the Foundation publishes no placeholder or aspirational
 * partners, so the Partners page shows an honest empty state instead.
 *
 * @return array<int,array<string,string>> Each: name, text, url, type.
 */
function cohf_confirmed_partners() {
	if ( ! post_type_exists( 'cohf_partner' ) ) {
		return array();
	}

	$posts = get_posts( array(
		'post_type'              => 'cohf_partner',
		'post_status'            => 'publish',
		'posts_per_page'         => 60,
		'orderby'                => array(
			'menu_order' => 'ASC',
			'title'      => 'ASC',
		),
		'no_found_rows'          => true,
		'update_post_term_cache' => false,
		// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		'meta_query'             => array(
			array(
				'key'   => '_cohf_confirmed',
				'value' => '1',
			),
		),
	) );

	$partners = array();

	foreach ( $posts as $cohf_partner_post ) {
		$partners[] = array(
			'name' => get_the_title( $cohf_partner_post ),
			'text' => wp_trim_words( wp_strip_all_tags( (string) $cohf_partner_post->post_content ), 28, '&hellip;' ),
			'url'  => cohf_field( 'website', $cohf_partner_post->ID ),
			'type' => cohf_field( 'partner_type', $cohf_partner_post->ID ),
		);
	}

	return $partners;
}

/**
 * Types of partner the Foundation welcomes.
 *
 * Source: Strategic Framework 2026–2030, section 22.1.
 *
 * @return string[]
 */
function cohf_partner_types() {
	return array(
		__( 'Government institutions', 'cohf-child' ),
		__( 'Foundations', 'cohf-child' ),
		__( 'International organisations', 'cohf-child' ),
		__( 'NGOs', 'cohf-child' ),
		__( 'Private companies', 'cohf-child' ),
		__( 'Health institutions', 'cohf-child' ),
		__( 'Education institutions', 'cohf-child' ),
		__( 'Universities', 'cohf-child' ),
		__( 'Community organisations', 'cohf-child' ),
		__( 'Faith-based organisations', 'cohf-child' ),
		__( 'Individuals', 'cohf-child' ),
	);
}

/**
 * Leadership team.
 *
 * Source: Leadership & Governance document.
 * Used to seed the Leadership content type and as a fallback.
 *
 * @return array<int,array<string,string>>
 */
function cohf_leadership_seed() {
	return array(
		array(
			'name'  => 'Mr. Justus Kubai',
			'photo' => 'leader-justus-kubai',
			'role'  => __( 'Founder & Executive Director', 'cohf-child' ),
			'group' => 'executive',
			'bio'   => __( 'Provides overall leadership and strategic direction for the Foundation, coordinates organisational development, oversees programme implementation, develops partnerships and ensures that the Foundation remains focused on its mission and community impact.', 'cohf-child' ),
		),
		array(
			'name'  => 'Claire Auma',
			'photo' => 'leader-claire-auma',
			'role'  => __( 'Co-Founder', 'cohf-child' ),
			'group' => 'executive',
			'bio'   => '',
		),
		array(
			'name'  => 'Henry Onzere',
			'photo' => 'leader-henry-onzere',
			'role'  => __( 'Chairperson', 'cohf-child' ),
			'group' => 'board',
			'bio'   => __( 'Provides strategic governance and oversight to the Foundation, supporting accountability, responsible decision-making and alignment with the Foundation\'s mission, values and long-term direction.', 'cohf-child' ),
		),
		array(
			'name'  => 'Victor Luhambo',
			'photo' => 'leader-victor-luhambo',
			'role'  => __( 'Operations Manager', 'cohf-child' ),
			'group' => 'management',
			'bio'   => __( 'Oversees day-to-day operational coordination, logistics and organisational support, helping ensure that programmes and activities are implemented efficiently and effectively.', 'cohf-child' ),
		),
		array(
			'name'  => 'Daria Lumati',
			'photo' => 'leader-daria-lumati',
			'role'  => __( 'Finance & Administration Manager', 'cohf-child' ),
			'group' => 'management',
			'bio'   => __( 'Supports the Foundation\'s financial and administrative functions, including budgeting, financial record-keeping, administrative coordination and strengthening accountability in the use of organisational resources.', 'cohf-child' ),
		),
		array(
			'name'  => 'Kevin Bosire',
			'photo' => 'leader-kevin-bosire',
			'role'  => __( 'Communications, Media & Digital Engagement Manager', 'cohf-child' ),
			'group' => 'management',
			'bio'   => __( 'Leads communications, storytelling, media engagement and digital visibility, helping document the Foundation\'s work and communicate its impact to communities, partners, donors and the wider public.', 'cohf-child' ),
		),
		array(
			'name'  => 'Christabel Sagali',
			'photo' => 'leader-christabel-sagali',
			'role'  => __( 'Community Engagement & Partnerships Manager', 'cohf-child' ),
			'group' => 'management',
			'bio'   => __( 'Leads community engagement and supports the development and maintenance of relationships with community members, local stakeholders, partners and other organisations that share the Foundation\'s vision.', 'cohf-child' ),
		),
	);
}

/**
 * Ways to get involved.
 *
 * @return array<int,array<string,string>>
 */
function cohf_pathways() {
	return array(
		array( 'id' => 'support',   'title' => __( 'Support our work', 'cohf-child' ),        'text' => __( 'Contribute to the cost of school fees, learning materials, sanitary pads, feeding activities or enterprise start-up support.', 'cohf-child' ), 'tpl' => 'page-templates/page-support.php' ),
		array( 'id' => 'partner',   'title' => __( 'Partner with us', 'cohf-child' ),         'text' => __( 'Work with us on programme grants, multi-year partnerships, co-funding or consortium opportunities.', 'cohf-child' ), 'tpl' => 'page-templates/page-partners.php' ),
		array( 'id' => 'volunteer', 'title' => __( 'Volunteer', 'cohf-child' ),               'text' => __( 'Give time and skills to community outreach, mentorship, documentation and programme delivery, within clear roles and safeguarding expectations.', 'cohf-child' ), 'tpl' => '' ),
		array( 'id' => 'mentor',    'title' => __( 'Mentor', 'cohf-child' ),                  'text' => __( 'Support young people through career guidance, life skills, character development and enterprise mentorship.', 'cohf-child' ), 'tpl' => '' ),
		array( 'id' => 'sponsor',   'title' => __( 'Sponsor a programme', 'cohf-child' ),     'text' => __( 'Fund a defined programme area for a defined period, with agreed indicators and reporting.', 'cohf-child' ), 'tpl' => '' ),
		array( 'id' => 'technical', 'title' => __( 'Provide technical support', 'cohf-child' ),'text' => __( 'Offer specialist expertise in health, agriculture, WASH, digital skills, monitoring and evaluation, safeguarding or organisational development.', 'cohf-child' ), 'tpl' => '' ),
		array( 'id' => 'in-kind',   'title' => __( 'Provide in-kind support', 'cohf-child' ), 'text' => __( 'Contribute equipment, learning materials, sanitary products, food support or other practical resources.', 'cohf-child' ), 'tpl' => '' ),
	);
}
