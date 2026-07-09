<?php
/**
 * Digital Marketing page — services ("What We Do").
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$services = sln_get_dm_page_services();
?>

<section class="dm-page__section dm-page__section--tint" aria-labelledby="dm-services-heading">
	<div class="dm-page__wrap">
		<p class="dm-page__eyebrow dm-page__reveal"><?php esc_html_e( 'What We Do', 'smart-leading-net' ); ?></p>
		<h2 id="dm-services-heading" class="dm-page__section-title dm-page__reveal">
			<?php
			echo wp_kses(
				__( 'Everything You Need, <span class="dm-page__hl">Under One Roof.</span>', 'smart-leading-net' ),
				array( 'span' => array( 'class' => true ) )
			);
			?>
		</h2>
		<p class="dm-page__lead dm-page__reveal">
			<?php esc_html_e( 'A complete growth system — not scattered tactics. One team, one strategy, one set of numbers.', 'smart-leading-net' ); ?>
		</p>
		<ul class="dm-page__list">
			<?php foreach ( $services as $service ) : ?>
				<li class="dm-page__svc dm-page__reveal">
					<div class="dm-page__svc-icon dm-page__svc-icon--<?php echo esc_attr( $service['variant'] ); ?>" aria-hidden="true"><?php echo esc_html( $service['icon'] ); ?></div>
					<h3 class="dm-page__svc-title"><?php echo esc_html( $service['title'] ); ?></h3>
					<p class="dm-page__svc-text"><?php echo esc_html( $service['text'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>