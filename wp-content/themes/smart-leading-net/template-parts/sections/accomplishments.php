<?php
/**
 * Accomplishments section — stats and results grid.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$accomplishments_uploads = '2026/05/';

$accomplishments_cards = array(
	array(
		'theme'       => 'blue',
		'icon'        => 'Lead Genertaed.svg',
		'counter'     => 100,
		'prefix'      => '',
		'suffix'      => 'k+',
		'title'       => __( 'Leads Generated', 'smart-leading-net' ),
		'description' => __( 'Generated across 47+ client campaigns the last 24 months.', 'smart-leading-net' ),
	),
	array(
		'theme'       => 'green',
		'icon'        => 'ROI-booted.svg',
		'counter'     => 3,
		'prefix'      => '',
		'suffix'      => 'X',
		'title'       => __( 'ROI Boosted', 'smart-leading-net' ),
		'description' => __( 'Average return on ad spend improved by 3X for our clients.', 'smart-leading-net' ),
	),
	array(
		'theme'       => 'orange',
		'icon'        => 'Generated Revenue.svg',
		'counter'     => 50,
		'prefix'      => '$',
		'suffix'      => 'M+',
		'title'       => __( 'Generated Revenue', 'smart-leading-net' ),
		'description' => __( 'Revenue tracked through CRM & analytics since 2022.', 'smart-leading-net' ),
	),
	array(
		'theme'       => 'purple',
		'icon'        => 'websites built.svg',
		'counter'     => 200,
		'prefix'      => '',
		'suffix'      => '%',
		'title'       => __( 'Average Growth', 'smart-leading-net' ),
		'description' => __( 'Average business growth achieved by our clients.', 'smart-leading-net' ),
	),
);
?>

<section class="accomplishments" aria-labelledby="accomplishments-heading">
	<div class="sls-container accomplishments__container">
		<header class="accomplishments__header">
			<p class="accomplishments__label"><?php esc_html_e( 'Our Accomplishments', 'smart-leading-net' ); ?></p>

			<h2 id="accomplishments-heading" class="accomplishments__heading section-title">
				<span class="accomplishments__heading-part accomplishments__heading-part--blue"><?php esc_html_e( 'Real Results.', 'smart-leading-net' ); ?></span>
				<span class="accomplishments__heading-part accomplishments__heading-part--dark"><?php esc_html_e( 'Real Revenue.', 'smart-leading-net' ); ?></span>
				<span class="accomplishments__heading-part accomplishments__heading-part--blue"><?php esc_html_e( 'Real Growth', 'smart-leading-net' ); ?></span>
			</h2>

			<p class="accomplishments__description section-description">
				<?php esc_html_e( 'We create data-driven strategies that help businesses generate leads, increase revenue, and scale with confidence.', 'smart-leading-net' ); ?>
			</p>
		</header>

		<div class="accomplishments__grid">
			<?php foreach ( $accomplishments_cards as $card ) : ?>
				<article class="accomplishments__card accomplishments__card--<?php echo esc_attr( $card['theme'] ); ?>">
					<div class="accomplishments__icon accomplishments__icon--<?php echo esc_attr( $card['theme'] ); ?>" aria-hidden="true">
						<?php
						echo sln_get_upload_inline_svg( $accomplishments_uploads . $card['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					</div>

					<p
						class="accomplishments__number"
						data-counter-value="<?php echo esc_attr( (string) $card['counter'] ); ?>"
						data-counter-prefix="<?php echo esc_attr( $card['prefix'] ); ?>"
						data-counter-suffix="<?php echo esc_attr( $card['suffix'] ); ?>"
					>
						<?php
						echo esc_html( $card['prefix'] . '0' . $card['suffix'] );
						?>
					</p>

					<h3 class="accomplishments__card-title"><?php echo esc_html( $card['title'] ); ?></h3>

					<div class="accomplishments__divider" aria-hidden="true">
						<span class="accomplishments__divider-colored"></span>
						<span class="accomplishments__divider-gray"></span>
					</div>

					<p class="accomplishments__card-desc">
						<span class="accomplishments__check" aria-hidden="true">
							<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
								<circle cx="10" cy="10" r="8.25" stroke="currentColor" stroke-width="1.5"/>
								<path d="M6.5 10L9 12.5L13.5 7.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</span>
						<span class="accomplishments__card-desc-text"><?php echo esc_html( $card['description'] ); ?></span>
					</p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
