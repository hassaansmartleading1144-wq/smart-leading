<?php
/**
 * Homepage lead tracking & sales management — three connected steps,
 * built on the workflow card design.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$home_lead_steps = array(
	array(
		'icon'  => 'pin',
		'title' => 'Know where every lead comes from',
		'text'  => 'Connect your Google Ads, Meta Ads, SEO and website to accurate call, form and sales tracking.',
	),
	array(
		'icon'  => 'reply',
		'title' => 'Follow up while customers are interested',
		'text'  => 'Use automated emails, texts, calendars and AI agents to reply and book appointments faster.',
	),
	array(
		'icon'  => 'revenue',
		'title' => 'See what brings in revenue',
		'text'  => 'Manage pipelines, quotes and customer records, and connect closed sales to the campaigns that generated them.',
	),
);
?>

<section class="home-leads" aria-labelledby="home-leads-heading">
	<div class="sls-container">
		<header class="home-section-head" data-home-reveal>
			<p class="home-eyebrow"><?php esc_html_e( 'Lead tracking & sales management', 'smart-leading-net' ); ?></p>
			<h2 id="home-leads-heading" class="home-section-head__title">
				<?php esc_html_e( 'Never let a good lead', 'smart-leading-net' ); ?>
				<span class="home-accent"><?php esc_html_e( 'go to waste.', 'smart-leading-net' ); ?></span>
			</h2>
			<p class="home-section-head__text"><?php esc_html_e( 'We connect your website, ads, phone calls and GoHighLevel CRM so your team can track every inquiry, follow up and manage sales in one place.', 'smart-leading-net' ); ?></p>
		</header>

		<ol class="home-leads__steps">
			<?php foreach ( $home_lead_steps as $i => $step ) : ?>
				<li class="home-leads__step" data-home-reveal style="--reveal-delay: <?php echo esc_attr( (string) ( $i * 140 ) ); ?>ms">
					<span class="home-leads__badge" aria-hidden="true"><?php echo sln_home_icon( $step['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<div class="home-leads__card">
						<h3 class="home-leads__title"><?php echo esc_html( $step['title'] ); ?></h3>
						<p class="home-leads__text"><?php echo esc_html( $step['text'] ); ?></p>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>

		<div class="home-leads__action">
			<?php
			sln_render_cta_button(
				array(
					'text'    => __( 'Improve my sales process', 'smart-leading-net' ),
					'url'     => sln_home_contact_url(),
					'variant' => 'primary',
				)
			);
			?>
		</div>
	</div>
</section>
