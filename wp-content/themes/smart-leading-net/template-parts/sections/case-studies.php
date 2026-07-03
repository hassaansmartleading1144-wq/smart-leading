<?php
/**
 * Case Studies section — proven results cards.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$case_studies_uploads     = '2026/05/';
$case_studies_uploads_url = trailingslashit( content_url( '/uploads/2026/05' ) );
$case_studies_bg        = $case_studies_uploads_url . 'case-studies-bg.webp';

$case_studies_cards = array(
	array(
		'theme'  => 'teal',
		'title'  => __( 'Case Study: Manufacturing', 'smart-leading-net' ),
		'number' => '30%',
		'text'   => __( 'ROI Increase From Paid Search', 'smart-leading-net' ),
		'icon'   => 'surface1.svg',
		'chart'  => 'paid-chart.svg',
	),
	array(
		'theme'        => 'orange',
		'title'        => __( 'Case Study: ECommerce', 'smart-leading-net' ),
		'number'       => '4.2X',
		'text'         => __( 'Higher Return On Ad Spend', 'smart-leading-net' ),
		'icon'         => 'ecommerce.svg',
		'chart'        => 'ad-spend-chart.svg',
		'footer_strip' => __( 'Data-Backed Strategies. Real Business Impact.', 'smart-leading-net' ),
	),
	array(
		'theme'          => 'blue',
		'title'          => __( 'Case Study: Hospitality', 'smart-leading-net' ),
		'number'         => '260%',
		'text'           => __( 'Increase In Organic Revenue', 'smart-leading-net' ),
		'icon'           => 'hospitality.svg',
		'chart'          => 'organic-chart.svg',
		'footer_bullets' => array(
			__( 'More Traffic', 'smart-leading-net' ),
			__( 'Higher Rankings', 'smart-leading-net' ),
			__( 'More Revenue', 'smart-leading-net' ),
		),
	),
);
?>

<section
	class="case-studies"
	aria-labelledby="case-studies-heading"
	style="--case-studies-bg: url('<?php echo esc_url( $case_studies_bg ); ?>');"
>
	<div class="sls-container case-studies__container">
		<div class="case-studies__intro">
			<header class="case-studies__header">
				<p class="case-studies__label"><?php esc_html_e( 'Case Studies', 'smart-leading-net' ); ?></p>

				<h2 id="case-studies-heading" class="case-studies__heading case-studies__title">
					<?php
					echo wp_kses(
						sprintf(
							/* translators: %s: highlighted phrase */
							__( 'Proven Results For %s', 'smart-leading-net' ),
							'<span class="case-studies__heading-accent">' . esc_html__( 'Growth-Focused Businesses', 'smart-leading-net' ) . '</span>'
						),
						array(
							'span' => array(
								'class' => true,
							),
						)
					);
					?>
				</h2>

				<p class="case-studies__description">
					<?php esc_html_e( 'See how we help brands turn strategy, paid media, websites, and optimisation into measurable growth through smart execution and data-backed decisions.', 'smart-leading-net' ); ?>
				</p>
			</header>
		</div>

		<div class="case-studies__cards-area">
			<div class="case-studies__grid">
				<a class="case-studies__link" href="#">
					<?php esc_html_e( 'More Case Studies', 'smart-leading-net' ); ?>
					<span aria-hidden="true">&rarr;</span>
				</a>

			<?php foreach ( $case_studies_cards as $card ) : ?>
				<article class="case-studies__card case-studies__card--<?php echo esc_attr( $card['theme'] ); ?>">
					<div class="case-studies__card-main">
						<div class="case-studies__card-top">
							<h3 class="case-studies__card-title"><?php echo esc_html( $card['title'] ); ?></h3>

							<div class="case-studies__icon" aria-hidden="true">
								<?php
								echo sln_get_upload_inline_svg( $case_studies_uploads . $card['icon'], '', true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								?>
							</div>
						</div>

						<div class="case-studies__card-body">
							<div class="case-studies__metric">
								<p class="case-studies__number"><?php echo esc_html( $card['number'] ); ?></p>
								<span class="case-studies__divider" aria-hidden="true"></span>
								<p class="case-studies__text"><?php echo esc_html( $card['text'] ); ?></p>
							</div>

							<div class="case-studies__chart" aria-hidden="true">
								<?php
								echo sln_get_upload_inline_svg( $case_studies_uploads . $card['chart'], 'case-studies__chart-svg', true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								?>
							</div>
						</div>
					</div>

					<?php if ( ! empty( $card['footer_strip'] ) ) : ?>
						<div class="case-studies__footer case-studies__footer--strip">
							<p><?php echo esc_html( $card['footer_strip'] ); ?></p>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $card['footer_bullets'] ) ) : ?>
						<div class="case-studies__footer case-studies__footer--bullets">
							<ul class="case-studies__bullets">
								<?php foreach ( $card['footer_bullets'] as $bullet ) : ?>
									<li><?php echo esc_html( $bullet ); ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
