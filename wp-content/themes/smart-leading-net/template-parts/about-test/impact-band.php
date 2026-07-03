<?php
/**
 * New About For Test — impact metrics band.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$metrics = array(
	array(
		'value' => '28K+',
		'label' => __( 'Client Reviews', 'smart-leading-net' ),
	),
	array(
		'value' => '24M+',
		'label' => __( 'Leads Delivered', 'smart-leading-net' ),
	),
	array(
		'value' => '40+',
		'label' => __( 'Industries Served', 'smart-leading-net' ),
	),
	array(
		'value' => '#1',
		'label' => __( 'ROI-Focused Culture', 'smart-leading-net' ),
	),
);
?>

<section class="nat-impact" aria-label="<?php esc_attr_e( 'Company impact metrics', 'smart-leading-net' ); ?>">
	<div class="nat-impact__container sls-container">
		<div class="nat-impact__grid">
			<?php foreach ( $metrics as $metric ) : ?>
				<article class="nat-impact__item">
					<p class="nat-impact__value"><?php echo esc_html( $metric['value'] ); ?></p>
					<p class="nat-impact__label"><?php echo esc_html( $metric['label'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
