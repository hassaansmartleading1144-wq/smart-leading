<?php
/**
 * New About For Test — brand intro (homepage-aligned hero messaging).
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uploads_url   = trailingslashit( content_url( '/uploads/2026/05' ) );
$underline_src = $uploads_url . rawurlencode( 'line sls.svg' );

$stats = array(
	array(
		'value' => '$50M+',
		'label' => __( 'Revenue Driven', 'smart-leading-net' ),
		'icon'  => 'Generated Revenue.svg',
		'tone'  => 'blue',
	),
	array(
		'value' => '4.9★',
		'label' => __( 'Client Rating', 'smart-leading-net' ),
		'icon'  => 'rating-star.svg',
		'tone'  => 'orange',
	),
	array(
		'value' => '15+',
		'label' => __( 'Years Experience', 'smart-leading-net' ),
		'icon'  => 'certified team.svg',
		'tone'  => 'blue',
	),
);
?>

<section class="nat-intro section-padding" aria-labelledby="nat-intro-heading">
	<div class="nat-intro__container sls-container">
		<div class="nat-intro__grid">
			<div class="nat-intro__content">
				<div class="nat-intro__badges">
					<span class="nat-intro__badge nat-intro__badge--primary"><?php esc_html_e( 'TECH-ENABLED AGENCY', 'smart-leading-net' ); ?></span>
					<span class="nat-intro__badge nat-intro__badge--certified">
						<img src="<?php echo esc_url( $uploads_url . rawurlencode( 'certified team.svg' ) ); ?>" alt="" width="19" height="18" loading="lazy" decoding="async">
						<?php esc_html_e( 'Google Partner Certified', 'smart-leading-net' ); ?>
					</span>
				</div>

				<h2 id="nat-intro-heading" class="nat-intro__title">
					<?php esc_html_e( 'Marketing Built for', 'smart-leading-net' ); ?>
					<span class="nat-intro__title-accent-wrap">
						<span class="nat-intro__title-accent"><?php esc_html_e( 'Measurable Growth', 'smart-leading-net' ); ?></span>
						<img class="nat-intro__underline" src="<?php echo esc_url( $underline_src ); ?>" alt="" width="223" height="13" loading="lazy" decoding="async">
					</span>
				</h2>

				<p class="nat-intro__description">
					<?php esc_html_e( 'Smart Leading Solutions combines strategy, performance marketing, and in-house technology to help businesses attract qualified leads, convert more customers, and scale revenue with clarity — not guesswork.', 'smart-leading-net' ); ?>
				</p>

				<div class="nat-intro__actions">
					<?php
					sln_render_cta_button(
						array(
							'text'    => __( 'Talk to Our Team', 'smart-leading-net' ),
							'url'     => sln_get_page_url_by_slug( 'contact-us' ),
							'variant' => 'primary',
							'class'   => 'nat-intro__cta',
						)
					);
					sln_render_cta_button(
						array(
							'text'       => __( 'View Original About Us', 'smart-leading-net' ),
							'url'        => sln_get_page_url_by_slug( 'about-us' ),
							'variant'    => 'outline',
							'class'      => 'nat-intro__cta-secondary',
							'show_arrow' => false,
						)
					);
					?>
				</div>
			</div>

			<div class="nat-intro__visual">
				<div class="nat-intro__artwork">
					<img
						class="nat-intro__image"
						src="<?php echo esc_url( $uploads_url . rawurlencode( 'banner_image.webp' ) ); ?>"
						alt="<?php esc_attr_e( 'Smart Leading growth team', 'smart-leading-net' ); ?>"
						width="560"
						height="668"
						loading="lazy"
						decoding="async"
					>

					<?php foreach ( $stats as $index => $stat ) : ?>
						<div class="nat-intro__stat nat-intro__stat--<?php echo esc_attr( (string) ( $index + 1 ) ); ?> nat-intro__stat--<?php echo esc_attr( $stat['tone'] ); ?>">
							<span class="nat-intro__stat-icon" aria-hidden="true">
								<img src="<?php echo esc_url( $uploads_url . rawurlencode( $stat['icon'] ) ); ?>" alt="" width="24" height="24" loading="lazy" decoding="async">
							</span>
							<div class="nat-intro__stat-copy">
								<strong><?php echo esc_html( $stat['value'] ); ?></strong>
								<span><?php echo esc_html( $stat['label'] ); ?></span>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</section>
