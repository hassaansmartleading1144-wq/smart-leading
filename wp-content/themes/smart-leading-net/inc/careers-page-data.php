<?php
/**
 * Careers page — presentation helpers and default section data.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Careers page button — reuses homepage/SEO sls-btn CTA.
 *
 * @param array $args {
 *     @type string $text    Button label.
 *     @type string $url     Link URL.
 *     @type string $variant primary|secondary|outline|white|ghost.
 *     @type string $class   Extra classes.
 *     @type bool   $arrow   Show arrow circle.
 *     @type string $type    link|button|submit.
 * }
 */
function sln_render_careers_page_button( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'text'    => '',
			'url'     => '#',
			'variant' => 'primary',
			'class'   => '',
			'arrow'   => false,
			'type'    => 'link',
		)
	);

	if ( '' === $args['text'] ) {
		return;
	}

	$variant = $args['variant'];

	if ( 'ghost' === $variant ) {
		$variant = 'outline';
	}

	if ( ! function_exists( 'sln_render_cta_button' ) ) {
		return;
	}

	sln_render_cta_button(
		array(
			'text'       => $args['text'],
			'url'        => $args['url'],
			'type'       => $args['type'],
			'variant'    => $variant,
			'show_arrow' => ! empty( $args['arrow'] ),
			'class'      => trim( 'careers-page__cta ' . $args['class'] ),
		)
	);
}

/**
 * Inline SVG icon for careers cards.
 *
 * @param string $icon Icon key.
 * @return string
 */
function sln_careers_page_icon( $icon ) {
	$icon = sanitize_key( (string) $icon );

	$icons = array(
		'growth'     => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 17l6-6 4 4 8-8"/><path d="M14 7h7v7"/></svg>',
		'ownership'  => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 3l8 4v6c0 5-3.5 8.5-8 9-4.5-.5-8-4-8-9V7l8-4z"/></svg>',
		'learning'   => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>',
		'impact'     => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>',
		'craft'      => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7z"/></svg>',
		'clarity'    => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>',
		'client'     => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M17 21v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>',
		'team'       => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>',
		'health'     => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 00-7.8 0L12 5.6l-1-1a5.5 5.5 0 00-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 000-7.8z"/></svg>',
		'remote'     => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2" y="4" width="20" height="14" rx="2"/><path d="M8 21h8M12 18v3"/></svg>',
		'tools'      => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M14.7 6.3a4 4 0 015 5L11 20l-4 1 1-4 8.7-10.7z"/></svg>',
		'bonus'      => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7H14a3.5 3.5 0 010 7H6"/></svg>',
		'default'    => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9"/></svg>',
	);

	return $icons[ $icon ] ?? $icons['default'];
}

/**
 * Why Join cards.
 *
 * @return array<int, array<string, string>>
 */
function sln_get_careers_why_join() {
	return array(
		array(
			'icon'  => 'growth',
			'title' => __( 'Real Growth Opportunities', 'smart-leading-net' ),
			'text'  => __( 'Clear career paths, mentorship, and stretch projects that help you level up every quarter.', 'smart-leading-net' ),
		),
		array(
			'icon'  => 'ownership',
			'title' => __( 'Ownership & Trust', 'smart-leading-net' ),
			'text'  => __( 'You own outcomes, not just tasks — with autonomy to ship ideas that move client results.', 'smart-leading-net' ),
		),
		array(
			'icon'  => 'learning',
			'title' => __( 'Learning Culture', 'smart-leading-net' ),
			'text'  => __( 'Workshops, certifications, and peer reviews keep your skills sharp and your craft current.', 'smart-leading-net' ),
		),
		array(
			'icon'  => 'impact',
			'title' => __( 'Measurable Impact', 'smart-leading-net' ),
			'text'  => __( 'Every role connects to revenue, leads, or product quality — so your work always matters.', 'smart-leading-net' ),
		),
	);
}

/**
 * Culture blocks.
 *
 * @return array<int, array<string, string>>
 */
function sln_get_careers_culture() {
	return array(
		array(
			'title' => __( 'High Standards, Low Ego', 'smart-leading-net' ),
			'text'  => __( 'We debate ideas hard and support people harder. Feedback is direct, kind, and actionable.', 'smart-leading-net' ),
		),
		array(
			'title' => __( 'Client Outcomes First', 'smart-leading-net' ),
			'text'  => __( 'We celebrate shipping work that grows businesses — not vanity metrics or busywork.', 'smart-leading-net' ),
		),
		array(
			'title' => __( 'Flexible & Focused', 'smart-leading-net' ),
			'text'  => __( 'Hybrid collaboration with deep-work blocks so you can do your best thinking.', 'smart-leading-net' ),
		),
	);
}

/**
 * Optional culture image URL.
 *
 * @return string
 */
function sln_get_careers_culture_image() {
	return '';
}

/**
 * Values cards.
 *
 * @return array<int, array<string, string>>
 */
function sln_get_careers_values() {
	return array(
		array(
			'icon'  => 'craft',
			'title' => __( 'Craft Over Hype', 'smart-leading-net' ),
			'text'  => __( 'We sweat the details because quality compounds — in code, creative, and campaigns.', 'smart-leading-net' ),
		),
		array(
			'icon'  => 'clarity',
			'title' => __( 'Clarity Always', 'smart-leading-net' ),
			'text'  => __( 'Clear goals, clear ownership, and clear communication — no guesswork.', 'smart-leading-net' ),
		),
		array(
			'icon'  => 'client',
			'title' => __( 'Clients Win With Us', 'smart-leading-net' ),
			'text'  => __( 'We succeed when clients grow. Partnership beats transactional delivery.', 'smart-leading-net' ),
		),
		array(
			'icon'  => 'team',
			'title' => __( 'Team Over Solo Heroes', 'smart-leading-net' ),
			'text'  => __( 'We share wins, share load, and build systems that make everyone better.', 'smart-leading-net' ),
		),
	);
}

/**
 * Benefits cards.
 *
 * @return array<int, array<string, string>>
 */
function sln_get_careers_benefits() {
	return array(
		array(
			'icon'  => 'health',
			'title' => __( 'Health & Wellbeing', 'smart-leading-net' ),
			'text'  => __( 'Medical coverage support and paid time off so you can recharge properly.', 'smart-leading-net' ),
		),
		array(
			'icon'  => 'learning',
			'title' => __( 'Learning Budget', 'smart-leading-net' ),
			'text'  => __( 'Annual stipend for courses, books, and certifications relevant to your role.', 'smart-leading-net' ),
		),
		array(
			'icon'  => 'remote',
			'title' => __( 'Hybrid Flexibility', 'smart-leading-net' ),
			'text'  => __( 'Collaborate in-office when it matters and focus remotely when deep work wins.', 'smart-leading-net' ),
		),
		array(
			'icon'  => 'tools',
			'title' => __( 'Best-In-Class Tools', 'smart-leading-net' ),
			'text'  => __( 'Modern stack, design systems, and analytics platforms that remove friction.', 'smart-leading-net' ),
		),
		array(
			'icon'  => 'bonus',
			'title' => __( 'Performance Rewards', 'smart-leading-net' ),
			'text'  => __( 'Bonuses and recognition tied to real outcomes — not politics.', 'smart-leading-net' ),
		),
		array(
			'icon'  => 'growth',
			'title' => __( 'Career Pathing', 'smart-leading-net' ),
			'text'  => __( 'Transparent levels and promotion criteria so you always know what “next” looks like.', 'smart-leading-net' ),
		),
	);
}

/**
 * Life gallery items.
 *
 * @return array<int, array<string, string>>
 */
function sln_get_careers_life_gallery() {
	return array(
		array(
			'size'  => 'large',
			'title' => __( 'Strategy Sessions', 'smart-leading-net' ),
			'text'  => __( 'Cross-functional rooms where growth, creative, and engineering align on outcomes.', 'smart-leading-net' ),
			'image' => '',
		),
		array(
			'size'  => 'tall',
			'title' => __( 'Ship Days', 'smart-leading-net' ),
			'text'  => __( 'Focused sprints that turn ideas into live campaigns and product releases.', 'smart-leading-net' ),
			'image' => '',
		),
		array(
			'size'  => 'wide',
			'title' => __( 'Team Celebrations', 'smart-leading-net' ),
			'text'  => __( 'We mark client wins and personal milestones — because culture is built in the moments between meetings.', 'smart-leading-net' ),
			'image' => '',
		),
		array(
			'size'  => 'large',
			'title' => __( 'Learning Labs', 'smart-leading-net' ),
			'text'  => __( 'Internal workshops on SEO, paid media, UX, and engineering best practices.', 'smart-leading-net' ),
			'image' => '',
		),
	);
}

/**
 * Hiring process steps.
 *
 * @return array<int, array<string, string>>
 */
function sln_get_careers_hiring_process() {
	return array(
		array(
			'number' => '01',
			'title'  => __( 'Apply', 'smart-leading-net' ),
			'text'   => __( 'Submit your application with resume and role preference. We review every submission.', 'smart-leading-net' ),
		),
		array(
			'number' => '02',
			'title'  => __( 'Screen', 'smart-leading-net' ),
			'text'   => __( 'A short conversation to understand your experience, goals, and working style.', 'smart-leading-net' ),
		),
		array(
			'number' => '03',
			'title'  => __( 'Skills Interview', 'smart-leading-net' ),
			'text'   => __( 'Role-specific discussion or practical task that mirrors real work at Smart Leading.', 'smart-leading-net' ),
		),
		array(
			'number' => '04',
			'title'  => __( 'Team Fit', 'smart-leading-net' ),
			'text'   => __( 'Meet the people you will collaborate with and ask anything about life on the team.', 'smart-leading-net' ),
		),
		array(
			'number' => '05',
			'title'  => __( 'Offer & Onboard', 'smart-leading-net' ),
			'text'   => __( 'Clear offer, structured onboarding, and a buddy to help you ramp with confidence.', 'smart-leading-net' ),
		),
	);
}

/**
 * Open positions.
 *
 * @return array<int, array<string, string>>
 */
function sln_get_careers_positions() {
	return array(
		array(
			'department'  => __( 'Growth', 'smart-leading-net' ),
			'type'        => __( 'Full-time', 'smart-leading-net' ),
			'title'       => __( 'SEO Specialist', 'smart-leading-net' ),
			'description' => __( 'Own technical and content SEO programs that grow organic traffic and qualified leads.', 'smart-leading-net' ),
			'location'    => __( 'Hybrid / Austin', 'smart-leading-net' ),
			'experience'  => __( '2–4 years', 'smart-leading-net' ),
			'salary'      => '',
		),
		array(
			'department'  => __( 'Paid Media', 'smart-leading-net' ),
			'type'        => __( 'Full-time', 'smart-leading-net' ),
			'title'       => __( 'PPC Account Manager', 'smart-leading-net' ),
			'description' => __( 'Plan, launch, and optimize paid search/social campaigns with clear ROAS accountability.', 'smart-leading-net' ),
			'location'    => __( 'Hybrid / Remote-friendly', 'smart-leading-net' ),
			'experience'  => __( '3+ years', 'smart-leading-net' ),
			'salary'      => '',
		),
		array(
			'department'  => __( 'Engineering', 'smart-leading-net' ),
			'type'        => __( 'Full-time', 'smart-leading-net' ),
			'title'       => __( 'WordPress Developer', 'smart-leading-net' ),
			'description' => __( 'Build high-performance marketing sites and theme features with clean, maintainable code.', 'smart-leading-net' ),
			'location'    => __( 'Hybrid', 'smart-leading-net' ),
			'experience'  => __( '2–5 years', 'smart-leading-net' ),
			'salary'      => '',
		),
		array(
			'department'  => __( 'Design', 'smart-leading-net' ),
			'type'        => __( 'Full-time', 'smart-leading-net' ),
			'title'       => __( 'UI/UX Designer', 'smart-leading-net' ),
			'description' => __( 'Design conversion-focused websites and landing experiences that feel premium and clear.', 'smart-leading-net' ),
			'location'    => __( 'Hybrid', 'smart-leading-net' ),
			'experience'  => __( '2–4 years', 'smart-leading-net' ),
			'salary'      => '',
		),
	);
}

/**
 * Position options for the apply form select.
 *
 * @return array<int, string>
 */
function sln_get_careers_form_positions() {
	$options = array();

	foreach ( sln_get_careers_positions() as $job ) {
		if ( ! empty( $job['title'] ) ) {
			$options[] = $job['title'];
		}
	}

	$options[] = __( 'General Application', 'smart-leading-net' );

	return $options;
}

/**
 * FAQ items.
 *
 * @return array<int, array<string, string>>
 */
function sln_get_careers_faq() {
	return array(
		array(
			'question' => __( 'Do you offer remote or hybrid roles?', 'smart-leading-net' ),
			'answer'   => __( 'Most roles are hybrid with flexible remote deep-work days. Some positions may be remote-friendly depending on the team.', 'smart-leading-net' ),
		),
		array(
			'question' => __( 'How long does the hiring process take?', 'smart-leading-net' ),
			'answer'   => __( 'Typically 1–3 weeks from application to decision, depending on role complexity and interview scheduling.', 'smart-leading-net' ),
		),
		array(
			'question' => __( 'Can I apply if I do not see my exact role listed?', 'smart-leading-net' ),
			'answer'   => __( 'Yes. Choose “General Application” and tell us where you can add the most value — we keep strong profiles for upcoming openings.', 'smart-leading-net' ),
		),
		array(
			'question' => __( 'What should I include with my application?', 'smart-leading-net' ),
			'answer'   => __( 'A current resume (PDF/DOC), the role you are targeting, and optionally LinkedIn/portfolio links that show your best work.', 'smart-leading-net' ),
		),
		array(
			'question' => __( 'Will I hear back if I am not selected?', 'smart-leading-net' ),
			'answer'   => __( 'We aim to update every candidate who completes an interview. High-volume general applications may receive a shorter response.', 'smart-leading-net' ),
		),
	);
}

/**
 * Employee testimonials.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_get_careers_testimonials() {
	return array(
		array(
			'rating'   => 5,
			'quote'    => __( 'I joined for the craft and stayed for the ownership. The team cares about outcomes and helps each other level up.', 'smart-leading-net' ),
			'name'     => __( 'Ayesha K.', 'smart-leading-net' ),
			'role'     => __( 'SEO Strategist', 'smart-leading-net' ),
			'initials' => 'AK',
		),
		array(
			'rating'   => 5,
			'quote'    => __( 'Clear goals, modern tools, and leaders who actually listen. It is the most growth-focused agency I have worked in.', 'smart-leading-net' ),
			'name'     => __( 'Daniel R.', 'smart-leading-net' ),
			'role'     => __( 'PPC Manager', 'smart-leading-net' ),
			'initials' => 'DR',
		),
		array(
			'rating'   => 5,
			'quote'    => __( 'Engineering here is treated as a product discipline — not ticket farming. That makes a huge difference.', 'smart-leading-net' ),
			'name'     => __( 'Sara M.', 'smart-leading-net' ),
			'role'     => __( 'WordPress Developer', 'smart-leading-net' ),
			'initials' => 'SM',
		),
	);
}
