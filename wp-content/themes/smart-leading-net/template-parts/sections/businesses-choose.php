<?php
/**
 * Businesses Choose section — why businesses choose us.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$businesses_choose_uploads = trailingslashit( content_url( '/uploads/2026/05' ) );
$businesses_choose_bg      = $businesses_choose_uploads . rawurlencode( 'bg-businesses.webp' );

$businesses_choose_cards = array(
	array(
		'icon'        => 'growth-buiness.svg',
		'title'       => __( 'Growth in Business', 'smart-leading-net' ),
		'description' => __( 'Campaigns designed to attract the right audience, generate qualified leads, and turn interest into paying customers.', 'smart-leading-net' ),
	),
	array(
		'icon'        => 'focused.svg',
		'title'       => __( 'ROI Focused Services', 'smart-leading-net' ),
		'description' => __( 'Every strategy is built around performance, so your budget works harder and your growth becomes easier to measure.', 'smart-leading-net' ),
	),
	array(
		'icon'        => 'day-free.svg',
		'title'       => __( '7-Day Free Trial', 'smart-leading-net' ),
		'description' => __( 'Experience our strategy, process, and performance approach before committing to a full marketing plan.', 'smart-leading-net' ),
	),
);
?>

<section
	class="businesses-choose"
	aria-labelledby="businesses-choose-heading"
	style="--businesses-choose-bg: url('<?php echo esc_url( $businesses_choose_bg ); ?>');"
>
	<div class="sls-container businesses-choose__container">
		<div class="businesses-choose__grid">
			<div class="businesses-choose__content">
				<p class="businesses-choose__label"><?php esc_html_e( 'Why Businnesses Choose Us?', 'smart-leading-net' ); ?></p>

				<h2 id="businesses-choose-heading" class="businesses-choose__heading businesses-choose__title">
					<?php
					echo wp_kses(
						sprintf(
							/* translators: %s: highlighted phrase "Growth Back" */
							__( 'Solutions for the Problems Holding Your %s', 'smart-leading-net' ),
							'<span class="businesses-choose__heading-accent">' . esc_html__( 'Growth Back', 'smart-leading-net' ) . '</span>'
						),
						array(
							'span' => array(
								'class' => true,
							),
						)
					);
					?>
				</h2>

				<p class="businesses-choose__description">
					<?php esc_html_e( 'Better strategy, stronger campaigns, and clearer results for businesses ready to scale.', 'smart-leading-net' ); ?>
				</p>
			</div>

			<div class="businesses-choose__cards">
				<?php foreach ( $businesses_choose_cards as $card ) : ?>
					<article class="businesses-choose__card">
						<div class="businesses-choose__icon" aria-hidden="true">
							<?php
							echo sln_get_upload_inline_svg( '2026/05/' . $card['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
						</div>

						<div class="businesses-choose__card-title"><?php echo esc_html( $card['title'] ); ?></div>

						<p class="businesses-choose__card-desc"><?php echo esc_html( $card['description'] ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
