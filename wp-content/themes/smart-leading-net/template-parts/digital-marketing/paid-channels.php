<?php
/**
 * Digital Marketing page — paid advertising channels.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$channels = sln_get_dm_page_paid_channels();
?>

<section class="dm-page__section dm-page__section--dark" aria-labelledby="dm-channels-heading">
	<div class="dm-page__wrap">
		<p class="dm-page__eyebrow dm-page__reveal"><?php esc_html_e( 'Paid Advertising', 'smart-leading-net' ); ?></p>
		<h2 id="dm-channels-heading" class="dm-page__section-title dm-page__section-title--light dm-page__reveal">
			<?php
			echo wp_kses(
				__( 'Ads That <span class="dm-page__hl">Actually Pay You Back.</span>', 'smart-leading-net' ),
				array( 'span' => array( 'class' => true ) )
			);
			?>
		</h2>
		<p class="dm-page__lead dm-page__lead--light dm-page__reveal">
			<?php esc_html_e( 'Every major paid channel — managed under one strategy, one team, one transparent dashboard.', 'smart-leading-net' ); ?>
		</p>
		<div class="dm-page__chgrid dm-page__reveal">
			<?php foreach ( $channels as $channel ) : ?>
				<div class="dm-page__chan">
					<div class="dm-page__chan-chip" aria-hidden="true"><?php echo esc_html( $channel['chip'] ); ?></div>
					<div class="dm-page__chan-name"><?php echo esc_html( $channel['name'] ); ?></div>
					<div class="dm-page__chan-desc"><?php echo esc_html( $channel['desc'] ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
		<p class="dm-page__incl dm-page__reveal">
			<?php esc_html_e( 'Included in every campaign:', 'smart-leading-net' ); ?><br>
			<strong><?php esc_html_e( 'Strategy · Laser Targeting · Weekly A/B Testing · Transparent Reporting', 'smart-leading-net' ); ?></strong>
		</p>
	</div>
</section>