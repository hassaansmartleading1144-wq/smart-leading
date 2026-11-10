<?php
/**
 * SEO Services — SEO tracking in Lead Analytics.
 *
 * Shows how Google Search Console, Google Analytics 4 and Google Business Profile
 * data come together in Lead Analytics (https://leads.smartleading.net/).
 * Product facts only — no client performance numbers are displayed.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sln_tracking_img = SLN_THEME_URI . '/assets/images/lead-analytics/';

$sln_tracking_platforms = array(
	array(
		'key'      => 'google-search-console',
		'name'     => 'Google Search Console',
		'question' => 'How do you show up in Google?',
		'metrics'  => array( 'Search clicks', 'Impressions', 'Average position', 'Top queries' ),
	),
	array(
		'key'      => 'google-analytics',
		'name'     => 'Google Analytics 4',
		'question' => 'What do visitors do on your site?',
		'metrics'  => array( 'Users', 'Sessions', 'Engagement', 'Key events' ),
	),
	array(
		'key'      => 'google-business-profile',
		'name'     => 'Google Business Profile',
		'question' => 'How is your local listing performing?',
		'metrics'  => array( 'Local listing performance', 'Viewed next to search and website data' ),
	),
);

$sln_tracking_points = array(
	'Scheduled sync, with historical data kept for reporting',
	'Custom date ranges and client-ready reports',
	'Missing data is labelled “not connected”, never shown as zero',
);
?>

<section class="seo-page__section seo-page__tracking" id="seo-tracking" aria-labelledby="seo-tracking-heading">
	<div class="sls-container">
		<header class="seo-page__section-head seo-page__section-head--center seo-page__reveal">
			<p class="seo-page__eyebrow">SEO TRACKING</p>
			<h2 id="seo-tracking-heading" class="seo-page__section-title">
				Your Search, Traffic and Local Stats.
				<span class="seo-page__tracking-highlight">One Dashboard.</span>
			</h2>
			<p class="seo-page__section-desc">
				Lead Analytics is the marketing dashboard built by Smart Leading. Google Search Console, Google Analytics 4 and Google Business Profile connect once and sit together in your own client workspace, next to the leads your marketing brings in.
			</p>
		</header>

		<ul class="seo-page__tracking-grid" role="list">
			<?php foreach ( $sln_tracking_platforms as $platform ) : ?>
				<li class="seo-page__tracking-card seo-page__reveal">
					<div class="seo-page__tracking-card-top">
						<span class="seo-page__tracking-logo">
							<img src="<?php echo esc_url( $sln_tracking_img . $platform['key'] . '.webp' ); ?>" alt="" width="36" height="36" loading="lazy" decoding="async">
						</span>
						<span class="seo-page__tracking-name"><?php echo esc_html( $platform['name'] ); ?></span>
					</div>
					<h3 class="seo-page__tracking-question"><?php echo esc_html( $platform['question'] ); ?></h3>
					<ul class="seo-page__tracking-metrics" role="list">
						<?php foreach ( $platform['metrics'] as $metric ) : ?>
							<li><?php echo sln_seo_page_check_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( $metric ); ?></span></li>
						<?php endforeach; ?>
					</ul>
				</li>
			<?php endforeach; ?>
		</ul>

		<div class="seo-page__tracking-band seo-page__reveal">
			<div class="seo-page__tracking-band-copy">
				<h3>See your SEO next to the leads it brings in.</h3>
				<p>Search clicks and website sessions are shown as performance context. Calls, website forms and CRM contacts are tracked as unique leads, each person counted once.</p>
				<ul class="seo-page__tracking-points" role="list">
					<?php foreach ( $sln_tracking_points as $point ) : ?>
						<li><?php echo sln_seo_page_check_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( $point ); ?></span></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="seo-page__tracking-band-cta">
				<img class="seo-page__tracking-brand" src="<?php echo esc_url( $sln_tracking_img . 'logo.webp' ); ?>" alt="Lead Analytics" width="180" height="70" loading="lazy" decoding="async">
				<a class="seo-page__tracking-btn" href="https://leads.smartleading.net/" target="_blank" rel="noopener noreferrer">
					Explore Lead Analytics
					<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14m-5-5 5 5-5 5"/></svg>
					<span class="screen-reader-text"> (opens in a new tab)</span>
				</a>
				<a class="seo-page__tracking-login" href="https://leads.smartleading.net/login" target="_blank" rel="noopener noreferrer">Client login<span class="screen-reader-text"> (opens in a new tab)</span></a>
			</div>
		</div>
	</div>
</section>
