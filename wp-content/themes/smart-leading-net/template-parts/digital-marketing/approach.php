<?php
/**
 * Digital Marketing page — approach (problem → solution).
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$approach_items = sln_get_dm_page_approach_items();
?>

<section class="dm-page__section dm-page__section--tint" aria-labelledby="dm-approach-heading">
	<div class="dm-page__wrap">
		<p class="dm-page__eyebrow dm-page__reveal"><?php esc_html_e( 'Our Approach', 'smart-leading-net' ); ?></p>
		<h2 id="dm-approach-heading" class="dm-page__section-title dm-page__reveal">
			<?php
			echo wp_kses(
				__( 'Your Problem. <span class="dm-page__hl">Our Solution.</span>', 'smart-leading-net' ),
				array( 'span' => array( 'class' => true ) )
			);
			?>
		</h2>
		<p class="dm-page__lead dm-page__reveal">
			<?php esc_html_e( 'Every pain point has a direct, practical answer. Here\'s exactly how we solve each one.', 'smart-leading-net' ); ?>
		</p>
		<ul class="dm-page__list">
			<?php foreach ( $approach_items as $item ) : ?>
				<li class="dm-page__appcard dm-page__reveal">
					<div class="dm-page__appcard-prob">
						<span class="dm-page__appcard-x" aria-hidden="true">✕</span>
						<?php echo esc_html( $item['problem'] ); ?>
					</div>
					<div class="dm-page__appcard-sol">
						<span class="dm-page__appcard-arrow" aria-hidden="true">→</span>
						<span>
							<?php
							echo wp_kses(
								$item['solution'],
								array( 'b' => array() )
							);
							?>
						</span>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>