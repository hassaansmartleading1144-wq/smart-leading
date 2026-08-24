<?php
/**
 * AEO page — hero.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$contact_url = sln_aeo_get_contact_url();
$dm_url      = sln_aeo_get_digital_marketing_url();
?>

<section class="sln-aeo-hero sln-aeo-section" aria-labelledby="sln-aeo-hero-heading">
	<div class="sls-container sln-aeo-hero__inner">
		<nav class="sln-aeo-breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'smart-leading-net' ); ?>">
			<ol class="sln-aeo-breadcrumb__list">
				<li>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'smart-leading-net' ); ?></a>
				</li>
				<li>
					<span class="sln-aeo-breadcrumb__sep" aria-hidden="true">&raquo;</span>
					<a href="<?php echo esc_url( $dm_url ); ?>"><?php esc_html_e( 'Digital Marketing Services', 'smart-leading-net' ); ?></a>
				</li>
				<li aria-current="page">
					<span class="sln-aeo-breadcrumb__sep" aria-hidden="true">&raquo;</span>
					<span><?php esc_html_e( 'Answer Engine Optimization (AEO)', 'smart-leading-net' ); ?></span>
				</li>
			</ol>
		</nav>

		<div class="sln-aeo-hero__grid">
			<div class="sln-aeo-hero__copy sln-aeo-reveal">
				<p class="sln-aeo-eyebrow"><?php esc_html_e( 'SEO Services / AEO', 'smart-leading-net' ); ?></p>
				<h1 id="sln-aeo-hero-heading" class="sln-aeo-hero__title">
					<?php esc_html_e( 'Answer Engine Optimization', 'smart-leading-net' ); ?>
					<span class="sln-aeo-hl"><?php esc_html_e( '(AEO)', 'smart-leading-net' ); ?></span>
					<?php esc_html_e( 'Services', 'smart-leading-net' ); ?>
				</h1>
				<p class="sln-aeo-lead sln-aeo-hero__lead">
					<?php esc_html_e( 'Search doesn\'t end in a list of blue links anymore. AI Overviews, ChatGPT, and Perplexity now hand people a finished answer — and only a few brands get quoted in it. AEO is how we get yours in that answer.', 'smart-leading-net' ); ?>
				</p>
				<div class="sln-aeo-hero__ctas">
					<?php
					sln_render_aeo_page_button(
						array(
							'text'    => __( 'Get a Free AEO Audit', 'smart-leading-net' ),
							'url'     => $contact_url,
							'variant' => 'primary',
							'arrow'   => true,
							'class'   => 'sln-aeo-cta--hero',
						)
					);
					sln_render_aeo_page_button(
						array(
							'text'    => __( 'See How It Works', 'smart-leading-net' ),
							'url'     => '#how-it-works',
							'variant' => 'outline',
							'arrow'   => false,
							'class'   => 'sln-aeo-cta--hero',
						)
					);
					?>
				</div>
				<div class="sln-aeo-hero__proof">
					<span class="sln-aeo-hero__proof-item"><?php esc_html_e( 'Google Partner agency', 'smart-leading-net' ); ?></span>
					<span class="sln-aeo-hero__proof-item"><?php esc_html_e( 'Works alongside your existing SEO', 'smart-leading-net' ); ?></span>
					<span class="sln-aeo-hero__proof-item"><?php esc_html_e( 'No fabricated guarantees, just process', 'smart-leading-net' ); ?></span>
				</div>
			</div>

			<div class="sln-aeo-mock sln-aeo-reveal" aria-hidden="true">
				<span class="sln-aeo-mock__badge"><?php esc_html_e( 'AI Overview — preview', 'smart-leading-net' ); ?></span>
				<div class="sln-aeo-mock__bar"><span></span><span></span><span></span></div>
				<div class="sln-aeo-mock__search">
					<span class="sln-aeo-mock__search-ic"></span>
					<span class="sln-aeo-mock__query"><?php esc_html_e( 'best project management software for small teams', 'smart-leading-net' ); ?></span>
				</div>
				<div class="sln-aeo-mock__answer">
					<span class="sln-aeo-mock__tag">
						<?php echo sln_aeo_icon( 'star' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php esc_html_e( 'AI-generated answer', 'smart-leading-net' ); ?>
					</span>
					<p><?php esc_html_e( 'For teams under 20 people, tools that combine lightweight task boards with built-in time tracking tend to work best — favoring simple setup over deep customization.', 'smart-leading-net' ); ?></p>
					<span class="sln-aeo-mock__cite">
						<span class="sln-aeo-mock__avatar"></span>
						<?php esc_html_e( 'Cited: yourbrand.com', 'smart-leading-net' ); ?>
					</span>
				</div>
				<div class="sln-aeo-mock__links">
					<div class="sln-aeo-mock__row"><small>01</small><span class="sln-aeo-mock__bar-line sln-aeo-mock__bar-line--wide"></span></div>
					<div class="sln-aeo-mock__row"><small>02</small><span class="sln-aeo-mock__bar-line sln-aeo-mock__bar-line--narrow"></span></div>
				</div>
			</div>
		</div>
	</div>
</section>
