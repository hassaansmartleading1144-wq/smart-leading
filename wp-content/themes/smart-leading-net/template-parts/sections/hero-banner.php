<?php
/**
 * Hero banner section — homepage hero with composite artwork and chat input.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hero_assets = trailingslashit( content_url( '/uploads/2026/05' ) );
$hero_lcp    = sln_get_hero_lcp_image_url();
$hero_srcset = sln_get_hero_lcp_image_srcset();
$hero_sizes  = sln_get_hero_lcp_image_sizes();
?>

<section class="hero-banner" aria-label="<?php esc_attr_e( 'Hero', 'smart-leading-net' ); ?>" style="--hero-banner-pattern: url('<?php echo esc_url( $hero_assets . rawurlencode( 'banner background.webp' ) ); ?>');">
	<div class="sls-container hero-banner__container">
		<div class="hero-banner__stage">
			<div class="hero-banner__content">
				<div class="hero-banner__badge" data-animate="fade-up">
					<span class="hero-banner__badge-pill hero-banner__badge-pill--trusted">
						<?php esc_html_e( 'TRUSTED AGENCY', 'smart-leading-net' ); ?>
					</span>
					<span class="hero-banner__badge-seal" aria-hidden="true">
						<img
							src="<?php echo esc_url( $hero_assets . rawurlencode( 'certified team.svg' ) ); ?>"
							alt=""
							width="19"
							height="18"
							loading="eager"
							decoding="async"
						>
					</span>
					<span class="hero-banner__badge-pill hero-banner__badge-pill--certified">
						<?php esc_html_e( 'Certified Team', 'smart-leading-net' ); ?>
					</span>
				</div>

				<h1 class="hero-banner__heading" data-animate="fade-up">
					<span class="hero-banner__heading-line">
						<?php esc_html_e( 'Growth Systems Built', 'smart-leading-net' ); ?>
					</span>
					<span class="hero-banner__heading-line hero-banner__heading-line--accent">
						<?php esc_html_e( 'To Drive', 'smart-leading-net' ); ?>
						<span class="hero-banner__revenue-wrap">
							<span class="hero-banner__revenue"><?php esc_html_e( 'Revenue', 'smart-leading-net' ); ?></span>
							<img
								class="hero-banner__revenue-underline"
								src="<?php echo esc_url( $hero_assets . rawurlencode( 'line sls.svg' ) ); ?>"
								alt=""
								width="223"
								height="13"
								loading="eager"
								decoding="async"
							>
						</span>
					</span>
				</h1>

				<p class="hero-banner__description" data-animate="fade-up">
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
						'text'       => __( 'Get My Free Proposal', 'smart-leading-net' ),
						'url'        => '#contact',
						'variant'    => 'primary',
						'class'      => 'hero-banner__cta',
						'attributes' => array(
							'data-animate' => 'fade-up',
						),
					)
				);
				?>
			</div>

			<div class="hero-banner__visual">
				<div class="hero-banner__artwork-wrap">
					<img
						class="hero-banner__artwork"
						src="<?php echo esc_url( $hero_lcp ); ?>"
						srcset="<?php echo esc_attr( $hero_srcset ); ?>"
						sizes="<?php echo esc_attr( $hero_sizes ); ?>"
						alt="<?php esc_attr_e( 'Smart Leading growth specialist with performance metrics', 'smart-leading-net' ); ?>"
						width="860"
						height="1025"
						loading="eager"
						fetchpriority="high"
						decoding="async"
					>

					<div class="hero-banner__performance">
						<h2 class="hero-banner__performance-title"><?php esc_html_e( 'Performance Overview', 'smart-leading-net' ); ?></h2>

						<div class="hero-banner__performance-chart" aria-hidden="true">
							<svg class="hero-banner__chart" viewBox="0 0 360 110" preserveAspectRatio="none">
								<defs>
									<linearGradient id="heroBannerChartFill" x1="0" y1="0" x2="0" y2="1">
										<stop offset="0%" stop-color="#1f4e9e" stop-opacity="0.2"/>
										<stop offset="100%" stop-color="#1f4e9e" stop-opacity="0"/>
									</linearGradient>
								</defs>
								<g class="hero-banner__chart-grid">
									<line x1="0" y1="22" x2="360" y2="22"/>
									<line x1="0" y1="44" x2="360" y2="44"/>
									<line x1="0" y1="66" x2="360" y2="66"/>
									<line x1="0" y1="88" x2="360" y2="88"/>
								</g>
								<path
									class="hero-banner__chart-area"
									d="M0 84 C 18 78, 34 72, 51 70 C 68 76, 88 80, 103 76 C 118 68, 138 58, 154 58 C 170 62, 188 68, 206 66 C 222 58, 240 50, 257 48 C 272 40, 290 30, 309 30 C 325 22, 345 14, 360 10 L360 110 L0 110 Z"
									fill="url(#heroBannerChartFill)"
								/>
								<path
									class="hero-banner__chart-line"
									d="M0 84 C 18 78, 34 72, 51 70 C 68 76, 88 80, 103 76 C 118 68, 138 58, 154 58 C 170 62, 188 68, 206 66 C 222 58, 240 50, 257 48 C 272 40, 290 30, 309 30 C 325 22, 345 14, 360 10"
									fill="none"
									stroke="#1f4e9e"
									stroke-width="2.5"
									stroke-linecap="round"
									stroke-linejoin="round"
								/>
								<g class="hero-banner__chart-dots">
									<circle class="hero-banner__chart-dot" cx="0" cy="84" r="4.5" fill="#ffffff" stroke="#1f4e9e" stroke-width="2"/>
									<circle class="hero-banner__chart-dot" cx="51" cy="70" r="4.5" fill="#ffffff" stroke="#1f4e9e" stroke-width="2"/>
									<circle class="hero-banner__chart-dot" cx="103" cy="76" r="4.5" fill="#ffffff" stroke="#1f4e9e" stroke-width="2"/>
									<circle class="hero-banner__chart-dot" cx="154" cy="58" r="4.5" fill="#ffffff" stroke="#1f4e9e" stroke-width="2"/>
									<circle class="hero-banner__chart-dot" cx="206" cy="66" r="4.5" fill="#ffffff" stroke="#1f4e9e" stroke-width="2"/>
									<circle class="hero-banner__chart-dot" cx="257" cy="48" r="4.5" fill="#ffffff" stroke="#1f4e9e" stroke-width="2"/>
									<circle class="hero-banner__chart-dot" cx="309" cy="30" r="4.5" fill="#ffffff" stroke="#1f4e9e" stroke-width="2"/>
									<circle class="hero-banner__chart-dot" cx="360" cy="10" r="4.5" fill="#ffffff" stroke="#1f4e9e" stroke-width="2"/>
								</g>
							</svg>
						</div>

						<div class="hero-banner__performance-stats">
							<div class="hero-banner__performance-metric">
								<span class="hero-banner__performance-label"><?php esc_html_e( 'Revenue Growth', 'smart-leading-net' ); ?></span>
								<strong class="hero-banner__performance-value">$50M+</strong>
							</div>

							<div class="hero-banner__performance-ring-wrap">
								<div class="hero-banner__performance-ring">
									<svg class="hero-banner__progress-ring" viewBox="0 0 84 84" aria-hidden="true">
										<circle class="hero-banner__progress-bg" cx="42" cy="42" r="32" fill="none" stroke="#e2eeff" stroke-width="6"/>
										<circle class="hero-banner__progress-fill" cx="42" cy="42" r="32" fill="none" stroke="#1f4e9e" stroke-width="6" stroke-linecap="round" transform="rotate(-90 42 42)"/>
									</svg>
									<span class="hero-banner__progress-value" data-progress-target="87">0%</span>
								</div>
								<span class="hero-banner__performance-label hero-banner__performance-label--ring"><?php esc_html_e( 'Campaign Efficiency', 'smart-leading-net' ); ?></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="hero-banner__chat sls-ai-chat" data-animate="fade-up">
			<div class="sls-ai-chat__history" id="sls-ai-chat-history" hidden>
				<div class="sls-ai-chat__messages" id="sls-ai-chat-messages" aria-live="polite" aria-relevant="additions"></div>
				<div class="sls-ai-chat__typing" id="sls-ai-chat-typing" hidden><?php esc_html_e( 'A Smart Leading Team Member is typing...', 'smart-leading-net' ); ?></div>
			</div>
			<form class="hero-banner__chat-form sls-ai-chat__form" action="#" method="post" novalidate>
				<span class="hero-banner__chat-icon" aria-hidden="true">
					<svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M11 2.5L12.4 8.4L18.5 7.6L14.2 11.8L16.2 17.8L11 14.5L5.8 17.8L7.8 11.8L3.5 7.6L9.6 8.4L11 2.5Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
						<path d="M4.5 19H17.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
					</svg>
				</span>
				<label class="visually-hidden" for="hero-banner-chat-input"><?php esc_html_e( 'Message Smart Leading', 'smart-leading-net' ); ?></label>
				<input
					id="hero-banner-chat-input"
					class="hero-banner__chat-input sls-ai-chat__input"
					type="text"
					name="project_message"
					placeholder="<?php esc_attr_e( 'Tell us about your project..', 'smart-leading-net' ); ?>"
					autocomplete="off"
					maxlength="1000"
				>
				<button type="submit" class="hero-banner__chat-submit sls-ai-chat__submit" aria-label="<?php esc_attr_e( 'Send message', 'smart-leading-net' ); ?>">
					<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M9 14V4M9 4L5 8M9 4L13 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</button>
			</form>
		</div>
	</div>
</section>
