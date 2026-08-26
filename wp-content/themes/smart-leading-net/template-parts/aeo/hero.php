<?php
/**
 * AEO page — hero.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hero          = sln_aeo_get_hero();
$proof         = sln_aeo_get_hero_proof();
$dm_url        = sln_aeo_get_digital_marketing_url();
$primary_url   = sln_aeo_resolve_cta_url( $hero['primary_cta_url'] ?? '', sln_aeo_get_contact_url() );
$secondary_url = sln_aeo_resolve_cta_url( $hero['secondary_cta_url'] ?? '', '#how-it-works' );
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
				<p class="sln-aeo-eyebrow"><?php echo esc_html( $hero['eyebrow'] ); ?></p>
				<h1 id="sln-aeo-hero-heading" class="sln-aeo-hero__title">
					<?php echo esc_html( $hero['heading'] ); ?>
					<span class="sln-aeo-hl"><?php echo esc_html( $hero['accent'] ); ?></span>
					<?php echo esc_html( $hero['heading_suffix'] ); ?>
				</h1>
				<p class="sln-aeo-lead sln-aeo-hero__lead">
					<?php echo esc_html( sln_aeo_plain_text( $hero['description'] ) ); ?>
				</p>
				<div class="sln-aeo-hero__ctas">
					<?php
					sln_render_aeo_page_button(
						array(
							'text'    => $hero['primary_cta_text'],
							'url'     => $primary_url,
							'variant' => 'primary',
							'arrow'   => true,
							'class'   => 'sln-aeo-cta--hero',
						)
					);
					sln_render_aeo_page_button(
						array(
							'text'    => $hero['secondary_cta_text'],
							'url'     => $secondary_url,
							'variant' => 'outline',
							'arrow'   => false,
							'class'   => 'sln-aeo-cta--hero',
						)
					);
					?>
				</div>
				<?php if ( ! empty( $proof ) ) : ?>
					<div class="sln-aeo-hero__proof">
						<?php foreach ( $proof as $item ) : ?>
							<span class="sln-aeo-hero__proof-item"><?php echo esc_html( $item ); ?></span>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
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
