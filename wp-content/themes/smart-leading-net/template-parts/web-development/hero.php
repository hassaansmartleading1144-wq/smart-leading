<?php
/**
 * Web Development — Figma hero (node 16731:34).
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hero = sln_get_wd_hero();

if ( ! sln_wd_row_is_active( $hero ) ) {
	return;
}

$trust_items = array();
if ( ! empty( $hero['rating_line'] ) ) {
	$parts = preg_split( '/\s*[·|]\s*/u', (string) $hero['rating_line'] );
	foreach ( (array) $parts as $part ) {
		$part = trim( (string) $part );
		if ( '' !== $part ) {
			$trust_items[] = $part;
		}
	}
}

$has_custom_hero = absint( $hero['hero_image_id'] ?? 0 ) > 0;

$float_cards = array(
	array(
		'label' => $hero['float_stat_1_label'] ?? __( 'Websites built', 'smart-leading-net' ),
		'value' => $hero['float_stat_1_value'] ?? '120+',
		'mod'   => '',
		'icon'  => 'chart',
	),
	array(
		'label' => $hero['float_stat_2_label'] ?? __( 'To go live', 'smart-leading-net' ),
		'value' => $hero['float_stat_2_value'] ?? __( '~4 wk', 'smart-leading-net' ),
		'mod'   => 'wd-hero__float--orange',
		'icon'  => 'speed',
	),
	array(
		'label' => $hero['float_stat_3_label'] ?? __( 'Happy clients', 'smart-leading-net' ),
		'value' => $hero['float_stat_3_value'] ?? '98%',
		'mod'   => 'wd-hero__float--green',
		'icon'  => 'personal',
	),
	array(
		'label' => $hero['float_stat_4_label'] ?? __( 'Website performance loads in', 'smart-leading-net' ),
		'value' => $hero['float_stat_4_value'] ?? '1.8s',
		'mod'   => 'wd-hero__float--purple',
		'icon'  => 'speed',
	),
	array(
		'label' => $hero['float_stat_5_label'] ?? __( 'Performance score', 'smart-leading-net' ),
		'value' => $hero['float_stat_5_value'] ?? '98 / 100',
		'mod'   => 'wd-hero__float--green',
		'icon'  => 'chart',
	),
);
?>

<section class="wd-hero" aria-labelledby="wd-hero-heading">
	<div class="wd-wrap">
		<div class="wd-hero__inner">
			<div class="wd-hero__copy wd-animate">
				<?php if ( ! empty( $hero['trust_badge'] ) || ! empty( $hero['certified_text'] ) ) : ?>
					<p class="wd-hero__badge">
						<span class="wd-hero__badge-icon" aria-hidden="true">
							<svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3l7 3v5c0 4.5-2.8 8.4-7 10-4.2-1.6-7-5.5-7-10V6l7-3z" fill="currentColor"/><path d="M8.5 12.2l2.4 2.4 4.6-5" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
						</span>
						<span>
							<?php
							echo esc_html( $hero['trust_badge'] ?? '' );
							if ( ! empty( $hero['trust_badge'] ) && ! empty( $hero['certified_text'] ) ) {
								echo ' — ';
							}
							echo esc_html( $hero['certified_text'] ?? '' );
							?>
						</span>
					</p>
				<?php endif; ?>

				<h1 id="wd-hero-heading" class="wd-hero__title">
					<?php
					echo esc_html( $hero['main_heading'] ?? '' );
					if ( ! empty( $hero['highlighted_text'] ) ) {
						echo ' <span class="wd-hl">' . esc_html( $hero['highlighted_text'] ) . '</span>';
					}
					?>
				</h1>

				<?php if ( sln_wd_plain_text( $hero['description'] ?? '' ) ) : ?>
					<div class="wd-lead"><?php echo sln_wd_format_content( $hero['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				<?php endif; ?>

				<?php if ( ! empty( $hero['primary_button_text'] ) || ! empty( $hero['secondary_button_text'] ) ) : ?>
					<div class="wd-hero__cta">
						<?php
						if ( ! empty( $hero['primary_button_text'] ) ) {
							sln_render_wd_page_button(
								array(
									'text'    => $hero['primary_button_text'],
									'url'     => $hero['primary_button_url'] ?? '#wd-contact',
									'variant' => 'primary',
									'arrow'   => true,
									'class'   => 'wd-cta--hero',
								)
							);
						}
						if ( ! empty( $hero['secondary_button_text'] ) ) {
							sln_render_wd_page_button(
								array(
									'text'    => $hero['secondary_button_text'],
									'url'     => $hero['secondary_button_url'] ?? '#wd-cases',
									'variant' => 'outline',
									'arrow'   => false,
									'class'   => 'wd-cta--hero-secondary',
								)
							);
						}
						?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $trust_items ) ) : ?>
					<ul class="wd-hero__trust">
						<li class="wd-hero__trust-item wd-hero__trust-item--rating">
							<span class="wd-hero__stars" aria-hidden="true">★★★★★</span>
							<span><?php echo esc_html( $trust_items[0] ); ?></span>
						</li>
						<?php foreach ( array_slice( $trust_items, 1 ) as $item ) : ?>
							<li class="wd-hero__trust-item"><?php echo esc_html( $item ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>

			<div class="wd-hero__visual wd-animate<?php echo $has_custom_hero ? ' wd-hero__visual--custom' : ' wd-hero__visual--composite'; ?>">
				<div class="wd-hero__visual-stage">
					<?php
					sln_wd_render_figure(
						array(
							'attachment_id' => absint( $hero['hero_image_id'] ?? 0 ),
							'fallback'      => 'hero-mockup.png',
							'alt'           => __( 'Website performance dashboard mockup with growth metrics', 'smart-leading-net' ),
							'width'         => 820,
							'height'        => 720,
							'loading'       => 'eager',
							'class'         => 'wd-hero__figure',
						)
					);
					?>

					<?php if ( $has_custom_hero ) : ?>
						<?php foreach ( $float_cards as $index => $card ) : ?>
							<div class="wd-hero__float wd-hero__float--<?php echo esc_attr( (string) ( $index + 1 ) ); ?> <?php echo esc_attr( $card['mod'] ); ?>">
								<span class="wd-hero__float-icon" aria-hidden="true"><?php echo sln_wd_fallback_icon( $card['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
								<div>
									<span class="wd-hero__float-label"><?php echo esc_html( $card['label'] ); ?></span>
									<strong class="wd-hero__float-value"><?php echo esc_html( $card['value'] ); ?></strong>
								</div>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>
