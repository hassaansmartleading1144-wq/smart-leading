<?php
/**
 * Why Choose Us section — trust points and the 3-month lead-growth offer.
 *
 * Copy source: work/why-choose-us/01-research-and-draft.md (draft v2, 11 Nov 2026).
 * Open items before going live:
 * - Replace the "Google Partner" text chip with Google's official badge snippet (must link to the public Partner profile).
 * - Add the LLC state + public registry link once confirmed.
 * - Team card: swap to named team + portfolios when the owner supplies them.
 * - Guarantee small print: add the agreed lead definition and baseline.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wcu_icons = array(
	'shield'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 4.5 6v5.5c0 4.6 3.2 8.4 7.5 9.5 4.3-1.1 7.5-4.9 7.5-9.5V6L12 3Z"/><path d="m8.8 12 2.2 2.2 4.4-4.4"/></svg>',
	'key'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="15" r="4"/><path d="m11 12 8.5-8.5"/><path d="m16 6 2.5 2.5"/><path d="m14 8 2 2"/></svg>',
	'team'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.2"/><path d="M3 19.5c0-3.3 2.7-5.5 6-5.5s6 2.2 6 5.5"/><circle cx="17" cy="9" r="2.4"/><path d="M16.5 14.1c2.6.3 4.5 2.3 4.5 5.1"/></svg>',
	'chart'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4v16h16"/><path d="m7.5 14.5 3.5-3.5 3 3 5-6"/><path d="M15.5 8H19v3.5"/></svg>',
	'check'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12.5 4.2 4.2L19 7"/></svg>',
);

$wcu_cards = array(
	array(
		'icon'        => 'shield',
		'title'       => __( 'A registered US company', 'smart-leading-net' ),
		'description' => __( 'Smart Leading Solutions LLC is a registered US business and Google Partner, working with business owners since 2018. You get a written agreement, USD invoices and a dedicated point of contact.', 'smart-leading-net' ),
	),
	array(
		'icon'        => 'key',
		'title'       => __( 'You own everything. We just manage it.', 'smart-leading-net' ),
		'description' => __( 'We are added as a Manager, never the owner, and we never ask for your passwords. Missing a Google Business Profile, Analytics or Ads account? We create and claim it in your business name. Remove our access any time.', 'smart-leading-net' ),
	),
	array(
		'icon'        => 'team',
		'title'       => __( 'Real people, real portfolios', 'smart-leading-net' ),
		'description' => __( 'Your SEO, ads and website are handled by a dedicated team of specialists, not freelancers you never meet. See who is behind the work before you start.', 'smart-leading-net' ),
	),
	array(
		'icon'        => 'chart',
		'title'       => __( 'We report leads, not vanity metrics', 'smart-leading-net' ),
		'description' => __( 'Every call, form and quote request is tracked in SLS Leads CRM, so you see what your marketing actually brings in. No fake numbers. If something is not working, we tell you.', 'smart-leading-net' ),
	),
);

$wcu_offer_points = array(
	__( '$0 upfront for months 1–3', 'smart-leading-net' ),
	__( 'Lead report at the end of month 3', 'smart-leading-net' ),
	__( 'Leads grew? Pay for the 3 months', 'smart-leading-net' ),
	__( 'No growth? You pay nothing', 'smart-leading-net' ),
);

$wcu_contact_url = home_url( '/contact-us/' );
?>

<section class="why-choose-us" aria-labelledby="why-choose-us-heading">
	<div class="sls-container why-choose-us__container">

		<header class="why-choose-us__header">
			<p class="why-choose-us__label"><?php esc_html_e( 'Why Business Owners Choose Us', 'smart-leading-net' ); ?></p>
			<h2 id="why-choose-us-heading" class="why-choose-us__title">
				<?php
				echo wp_kses(
					sprintf(
						/* translators: %s: highlighted phrase */
						__( 'More Leads in 3 Months, %s', 'smart-leading-net' ),
						'<span class="why-choose-us__accent">' . esc_html__( 'or You Don’t Pay', 'smart-leading-net' ) . '</span>'
					),
					array( 'span' => array( 'class' => true ) )
				);
				?>
			</h2>
			<p class="why-choose-us__intro">
				<?php esc_html_e( 'Your accounts stay yours, every result is tracked, and you only pay once you have seen the leads.', 'smart-leading-net' ); ?>
			</p>

			<ul class="why-choose-us__trust" aria-label="<?php esc_attr_e( 'Company credentials', 'smart-leading-net' ); ?>">
				<?php /* TODO: replace with Google's official Partner badge HTML snippet (links to public Partner profile). */ ?>
				<li class="why-choose-us__trust-item"><?php esc_html_e( 'Google Partner', 'smart-leading-net' ); ?></li>
				<li class="why-choose-us__trust-item"><?php esc_html_e( 'US-registered LLC', 'smart-leading-net' ); ?></li>
				<li class="why-choose-us__trust-item"><?php esc_html_e( 'Clients in the USA & Australia', 'smart-leading-net' ); ?></li>
				<li class="why-choose-us__trust-item"><?php esc_html_e( 'Since 2018', 'smart-leading-net' ); ?></li>
			</ul>
		</header>

		<div class="why-choose-us__offer">
			<div class="why-choose-us__offer-main">
				<p class="why-choose-us__offer-tag"><?php esc_html_e( '3-month lead growth guarantee', 'smart-leading-net' ); ?></p>
				<h3 class="why-choose-us__offer-title"><?php esc_html_e( 'Pay nothing for 3 months. Then pay only if your leads grew.', 'smart-leading-net' ); ?></h3>
				<p class="why-choose-us__offer-text">
					<?php esc_html_e( 'We work for your first 3 months with no upfront fee. At the end of month 3 we show you the lead report. Our target is 10–15% more leads, tracked in a dashboard you can see too.', 'smart-leading-net' ); ?>
				</p>
				<p class="why-choose-us__offer-terms">
					<?php esc_html_e( 'Applies to the Smart Leading service fee for new clients’ first 3 months. Ad spend paid directly to Google or Meta is not included. Leads are measured against a starting point agreed before work begins. Full terms are in your service agreement.', 'smart-leading-net' ); ?>
				</p>
			</div>

			<ol class="why-choose-us__steps">
				<?php foreach ( $wcu_offer_points as $index => $point ) : ?>
					<li class="why-choose-us__step">
						<span class="why-choose-us__step-num" aria-hidden="true"><?php echo esc_html( (string) ( $index + 1 ) ); ?></span>
						<span class="why-choose-us__step-text"><?php echo esc_html( $point ); ?></span>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>

		<div class="why-choose-us__cards">
			<?php foreach ( $wcu_cards as $card ) : ?>
				<article class="why-choose-us__card">
					<span class="why-choose-us__icon" aria-hidden="true">
						<?php echo $wcu_icons[ $card['icon'] ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?>
					</span>
					<h3 class="why-choose-us__card-title"><?php echo esc_html( $card['title'] ); ?></h3>
					<p class="why-choose-us__card-text"><?php echo esc_html( $card['description'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>

		<div class="why-choose-us__cta">
			<?php
			sln_render_cta_button(
				array(
					'text'    => __( 'Get My Free Proposal', 'smart-leading-net' ),
					'url'     => $wcu_contact_url,
					'variant' => 'primary',
					'class'   => 'why-choose-us__cta-btn',
				)
			);
			?>
			<p class="why-choose-us__cta-note"><?php esc_html_e( '$0 upfront. Pay after 3 months, only if your leads grew.', 'smart-leading-net' ); ?></p>
		</div>

	</div>
</section>
