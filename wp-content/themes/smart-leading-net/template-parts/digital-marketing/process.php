<?php
/**
 * Digital Marketing page — process timeline.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$steps = sln_get_dm_page_process_steps();
?>

<section class="dm-page__section" aria-labelledby="dm-process-heading">
	<div class="dm-page__wrap">
		<p class="dm-page__eyebrow dm-page__reveal"><?php esc_html_e( 'How We Work', 'smart-leading-net' ); ?></p>
		<h2 id="dm-process-heading" class="dm-page__section-title dm-page__reveal">
			<?php
			echo wp_kses(
				__( 'A Clear Path To <span class="dm-page__hl">Predictable Growth.</span>', 'smart-leading-net' ),
				array( 'span' => array( 'class' => true ) )
			);
			?>
		</h2>
		<p class="dm-page__lead dm-page__reveal">
			<?php esc_html_e( 'Four steps, clear ownership, and measurable results at every stage.', 'smart-leading-net' ); ?>
		</p>
		<div class="dm-page__timeline" data-dm-timeline>
			<span class="dm-page__timeline-fill" aria-hidden="true"></span>
			<?php foreach ( $steps as $step ) : ?>
				<div class="dm-page__tstep">
					<div class="dm-page__tnum" aria-hidden="true"><?php echo esc_html( $step['number'] ); ?></div>
					<div>
						<h3 class="dm-page__tstep-title"><?php echo esc_html( $step['title'] ); ?></h3>
						<ul class="dm-page__tstep-list">
							<?php foreach ( $step['items'] as $item ) : ?>
								<li><?php echo esc_html( $item ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<p class="dm-page__procnote dm-page__reveal">
			<?php esc_html_e( 'Every step is communicated clearly — no jargon, just visible progress.', 'smart-leading-net' ); ?>
		</p>
	</div>
</section>
