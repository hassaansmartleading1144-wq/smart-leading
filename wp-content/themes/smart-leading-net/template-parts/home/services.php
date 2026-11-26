<?php
/**
 * Homepage services — nine service cards, each with a real logo: Google,
 * Google Ads, Meta, WordPress, Shopify, Gmail, Lead Analytics, HighLevel and
 * ChatGPT. A service without a logo falls back to an SLS-made badge.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$home_services = sln_home_services();
$logo_base     = trailingslashit( SLN_THEME_URI ) . 'assets/images/partners/';
?>

<section class="home-services" id="sls-services" aria-labelledby="home-services-heading" data-home-slider>
	<div class="sls-container">
		<header class="home-section-head" data-home-reveal>
			<p class="home-eyebrow"><?php esc_html_e( 'Our services', 'smart-leading-net' ); ?></p>
			<h2 id="home-services-heading" class="home-section-head__title">
				<?php esc_html_e( 'What do you need to', 'smart-leading-net' ); ?>
				<span class="home-accent"><?php esc_html_e( 'grow your business?', 'smart-leading-net' ); ?></span>
			</h2>
			<p class="home-section-head__text"><?php esc_html_e( 'Choose the service you need, or let us take care of your entire marketing and sales setup.', 'smart-leading-net' ); ?></p>
		</header>

		<ul class="home-services__grid">
			<?php foreach ( $home_services as $i => $service ) : ?>
				<li class="home-services__item" data-home-reveal style="--reveal-delay: <?php echo esc_attr( (string) ( ( $i % 3 ) * 90 ) ); ?>ms">
					<article class="home-services__card">
						<div class="home-services__top">
							<?php if ( ! empty( $service['logo'] ) ) : ?>
								<?php $logo = $service['logo']; ?>
								<span class="home-services__logo<?php echo ! empty( $logo['square'] ) ? ' home-services__logo--square' : ''; ?><?php echo ! empty( $logo['padded'] ) ? ' home-services__logo--padded' : ''; ?>">
									<img
										src="<?php echo esc_url( $logo_base . $logo['file'] ); ?>"
										alt=""
										width="<?php echo esc_attr( (string) $logo['w'] ); ?>"
										height="<?php echo esc_attr( (string) $logo['h'] ); ?>"
										loading="lazy"
										decoding="async"
									>
								</span>
							<?php else : ?>
								<?php $badge = $service['badge']; ?>
								<span class="home-services__badge" style="--badge-from: <?php echo esc_attr( $badge['from'] ); ?>; --badge-to: <?php echo esc_attr( $badge['to'] ); ?>;" aria-hidden="true">
									<?php echo sln_home_icon( $badge['icon'], 'home-services__badge-main' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									<span class="home-services__badge-accent"><?php echo sln_home_icon( $badge['accent'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
								</span>
							<?php endif; ?>

							<span class="home-services__go" aria-hidden="true"><?php echo sln_home_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						</div>

						<h3 class="home-services__title"><?php echo esc_html( $service['title'] ); ?></h3>
						<p class="home-services__benefit"><?php echo esc_html( $service['benefit'] ); ?></p>

						<ul class="home-services__chips">
							<?php foreach ( $service['details'] as $detail ) : ?>
								<li class="home-services__chip"><?php echo esc_html( $detail ); ?></li>
							<?php endforeach; ?>
						</ul>

						<?php if ( ! empty( $service['note'] ) ) : ?>
							<p class="home-services__note"><?php echo esc_html( $service['note'] ); ?></p>
						<?php endif; ?>

						<a class="home-services__link" href="<?php echo esc_url( $service['url'] ); ?>">
							<?php esc_html_e( 'Explore service', 'smart-leading-net' ); ?>
							<span class="screen-reader-text"><?php echo esc_html( ': ' . $service['title'] ); ?></span>
							<?php echo sln_home_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</a>
					</article>
				</li>
			<?php endforeach; ?>
		</ul>

		<div class="home-services__nav" data-slider-nav hidden>
			<button type="button" class="home-services__arrow" data-slider-prev aria-label="<?php esc_attr_e( 'Previous service', 'smart-leading-net' ); ?>">
				<?php echo sln_home_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</button>
			<div class="home-services__dots" data-slider-dots></div>
			<button type="button" class="home-services__arrow home-services__arrow--next" data-slider-next aria-label="<?php esc_attr_e( 'Next service', 'smart-leading-net' ); ?>">
				<?php echo sln_home_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</button>
		</div>
	</div>
</section>
