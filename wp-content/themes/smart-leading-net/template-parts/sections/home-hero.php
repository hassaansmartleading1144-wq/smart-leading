<?php
/**
 * Home page hero section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sln_hero_assets = trailingslashit( content_url( '/uploads/2026/05' ) );
?>

<section class="home-hero" aria-label="<?php esc_attr_e( 'Hero', 'smart-leading-net' ); ?>" style="background-image: url('<?php echo esc_url( $sln_hero_assets . rawurlencode( 'banner background.webp' ) ); ?>');">
	<div class="container home-hero__container">
		<div class="home-hero__grid">
			<div class="home-hero__content hero-left" data-animate="fade-left">
				<div class="home-hero__badges">
					<span class="home-hero__badge home-hero__badge--trusted"><?php esc_html_e( 'TRUSTED AGENCY', 'smart-leading-net' ); ?></span>
					<span class="home-hero__badge home-hero__badge--certified">
						<img src="<?php echo esc_url( $sln_hero_assets . rawurlencode( 'certified team.svg' ) ); ?>" alt="" width="19" height="18" loading="eager" decoding="async">
						<?php esc_html_e( 'Certified Team', 'smart-leading-net' ); ?>
					</span>
				</div>

				<h1 class="home-hero__title">
					<span class="home-hero__title-line">
						<?php esc_html_e( 'Growth Systems Built', 'smart-leading-net' ); ?>
					</span>
					<span class="home-hero__title-line home-hero__title-line--drive">
						<?php esc_html_e( 'To Drive', 'smart-leading-net' ); ?> <span class="home-hero__title-accent-wrap"><span class="home-hero__title-accent"><?php esc_html_e( 'Revenue', 'smart-leading-net' ); ?></span><img class="home-hero__underline-img" src="<?php echo esc_url( $sln_hero_assets . rawurlencode( 'line sls.svg' ) ); ?>" alt="" width="223" height="13" loading="eager" decoding="async"></span>
					</span>
				</h1>

				<p class="home-hero__description">
					<?php
					echo wp_kses(
						sprintf(
							/* translators: %1$s: Right Audience, %2$s: Boost Sales, %3$s: Increase Revenue */
							__( 'We build data-driven growth systems that attract the %1$s, %2$s, and %3$s.', 'smart-leading-net' ),
							'<strong>' . esc_html__( 'Right Audience', 'smart-leading-net' ) . '</strong>',
							'<strong>' . esc_html__( 'Boost Sales', 'smart-leading-net' ) . '</strong>',
							'<strong>' . esc_html__( 'Increase Revenue', 'smart-leading-net' ) . '</strong>'
						),
						array(
							'strong' => array(),
						)
					);
					?>
				</p>

				<?php
				sln_render_cta_button(
					array(
						'text'    => __( 'Get My Free Proposal', 'smart-leading-net' ),
						'url'     => '#contact',
						'variant' => 'primary',
						'class'   => 'home-hero__cta',
					)
				);
				?>
			</div>

			<div class="home-hero__visual">
				<div class="home-hero__artwork">
					<img
						class="home-hero__banner"
						src="<?php echo esc_url( $sln_hero_assets . rawurlencode( 'banner_image.webp' ) ); ?>"
						alt="<?php esc_attr_e( 'Smart Leading growth specialist', 'smart-leading-net' ); ?>"
						width="860"
						height="1025"
						loading="eager"
						decoding="async"
					>

					<div class="home-hero__stat-card home-hero__stat-card--roi" data-animate="slide-right">
					<span class="home-hero__stat-icon home-hero__stat-icon--blue">
						<img src="<?php echo esc_url( $sln_hero_assets . rawurlencode( 'ROI-booted.svg' ) ); ?>" alt="" width="24" height="24" loading="lazy" decoding="async">
					</span>
					<div class="home-hero__stat-copy">
						<strong>3x</strong>
						<span><?php esc_html_e( 'ROI Boosted', 'smart-leading-net' ); ?></span>
					</div>
					</div>

					<div class="home-hero__stat-card home-hero__stat-card--leads" data-animate="slide-down">
					<span class="home-hero__stat-icon home-hero__stat-icon--blue">
						<img src="<?php echo esc_url( $sln_hero_assets . rawurlencode( 'Lead Genertaed.svg' ) ); ?>" alt="" width="17" height="22" loading="lazy" decoding="async">
					</span>
					<div class="home-hero__stat-copy">
						<strong>100k+</strong>
						<span><?php esc_html_e( 'Leads Generated', 'smart-leading-net' ); ?></span>
					</div>
					</div>

					<div class="home-hero__stat-card home-hero__stat-card--websites" data-animate="slide-left">
					<span class="home-hero__stat-icon home-hero__stat-icon--orange">
						<img src="<?php echo esc_url( $sln_hero_assets . rawurlencode( 'websites built.svg' ) ); ?>" alt="" width="24" height="24" loading="lazy" decoding="async">
					</span>
					<div class="home-hero__stat-copy">
						<strong>200+</strong>
						<span><?php esc_html_e( 'Websites Built', 'smart-leading-net' ); ?></span>
					</div>
					</div>

					<div class="home-hero__performance" data-animate="performance-card">
					<h3 class="home-hero__performance-title"><?php esc_html_e( 'Performance Overview', 'smart-leading-net' ); ?></h3>

					<div class="home-hero__performance-chart" aria-hidden="true">
						<svg class="home-hero__chart-svg" viewBox="0 0 360 104" preserveAspectRatio="none">
							<defs>
								<linearGradient id="homeHeroChartFill" x1="0" y1="0" x2="0" y2="1">
									<stop offset="0%" stop-color="#1f4e9e" stop-opacity="0.18"/>
									<stop offset="100%" stop-color="#1f4e9e" stop-opacity="0"/>
								</linearGradient>
							</defs>
							<g class="home-hero__chart-grid">
								<line x1="0" y1="26" x2="360" y2="26"/>
								<line x1="0" y1="52" x2="360" y2="52"/>
								<line x1="0" y1="78" x2="360" y2="78"/>
							</g>
							<path class="home-hero__chart-area" d="M0 82 L45 74 L90 64 L135 52 L180 40 L225 28 L270 18 L315 10 L360 6 L360 104 L0 104 Z" fill="url(#homeHeroChartFill)"/>
							<polyline class="home-hero__chart-line" points="0,82 45,74 90,64 135,52 180,40 225,28 270,18 315,10 360,6" fill="none" stroke="#1f4e9e" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
							<g class="home-hero__chart-dots">
								<circle class="home-hero__chart-dot" cx="0" cy="82" r="5" fill="#ffffff" stroke="#1f4e9e" stroke-width="2"/>
								<circle class="home-hero__chart-dot" cx="45" cy="74" r="5" fill="#ffffff" stroke="#1f4e9e" stroke-width="2"/>
								<circle class="home-hero__chart-dot" cx="90" cy="64" r="5" fill="#ffffff" stroke="#1f4e9e" stroke-width="2"/>
								<circle class="home-hero__chart-dot" cx="135" cy="52" r="5" fill="#ffffff" stroke="#1f4e9e" stroke-width="2"/>
								<circle class="home-hero__chart-dot" cx="180" cy="40" r="5" fill="#ffffff" stroke="#1f4e9e" stroke-width="2"/>
								<circle class="home-hero__chart-dot" cx="225" cy="28" r="5" fill="#ffffff" stroke="#1f4e9e" stroke-width="2"/>
								<circle class="home-hero__chart-dot" cx="270" cy="18" r="5" fill="#ffffff" stroke="#1f4e9e" stroke-width="2"/>
								<circle class="home-hero__chart-dot" cx="315" cy="10" r="5" fill="#ffffff" stroke="#1f4e9e" stroke-width="2"/>
								<circle class="home-hero__chart-dot" cx="360" cy="6" r="5" fill="#ffffff" stroke="#1f4e9e" stroke-width="2"/>
							</g>
						</svg>
					</div>

					<div class="home-hero__performance-stats">
						<div class="home-hero__performance-metric">
							<span class="home-hero__performance-label"><?php esc_html_e( 'Revenue Growth', 'smart-leading-net' ); ?></span>
							<strong class="home-hero__performance-value">$50M+</strong>
						</div>

						<div class="home-hero__performance-ring-wrap">
							<div class="home-hero__performance-ring-inner">
								<svg class="home-hero__progress-ring" viewBox="0 0 84 84" aria-hidden="true">
									<circle class="home-hero__progress-bg" cx="42" cy="42" r="32" fill="none" stroke="#e8eef8" stroke-width="6"/>
									<circle class="home-hero__progress-fill" cx="42" cy="42" r="32" fill="none" stroke="#1f4e9e" stroke-width="6" stroke-linecap="round" transform="rotate(-90 42 42)"/>
								</svg>
								<span class="home-hero__progress-value">87%</span>
							</div>
							<span class="home-hero__performance-label home-hero__performance-label--ring"><?php esc_html_e( 'Campaign Efficiency', 'smart-leading-net' ); ?></span>
						</div>
					</div>
				</div>
				</div>
			</div>
		</div>

		<div class="home-hero__chat" data-animate="fade-up">
			<form class="home-hero__chat-form" action="#" method="post" novalidate>
				<span class="home-hero__chat-icon" aria-hidden="true">
					<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M10 2L11.2 7.2L16.5 6.5L12.8 10.2L14.5 15.5L10 12.5L5.5 15.5L7.2 10.2L3.5 6.5L8.8 7.2L10 2Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
						<path d="M4 17H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
					</svg>
				</span>
				<label class="visually-hidden" for="home-hero-chat-input"><?php esc_html_e( 'Tell us about your project', 'smart-leading-net' ); ?></label>
				<input
					id="home-hero-chat-input"
					class="home-hero__chat-input"
					type="text"
					name="project_message"
					placeholder="<?php esc_attr_e( 'Tell us about your project..', 'smart-leading-net' ); ?>"
					autocomplete="off"
				>
				<button type="submit" class="home-hero__chat-submit" aria-label="<?php esc_attr_e( 'Send message', 'smart-leading-net' ); ?>">
					<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M9 14V4M9 4L5 8M9 4L13 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</button>
			</form>
			<div class="home-hero__chat-response" aria-live="polite" hidden></div>
		</div>
	</div>
</section>
