<?php
/**
 * Careers — hiring process timeline.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$steps = sln_get_careers_hiring_process();
?>

<section class="careers-page__section careers-page__process" id="careers-process" aria-labelledby="careers-process-heading">
	<div class="sls-container">
		<header class="careers-page__section-head careers-page__section-head--center careers-page__reveal">
			<p class="careers-page__eyebrow"><?php esc_html_e( 'Hiring Process', 'smart-leading-net' ); ?></p>
			<h2 id="careers-process-heading" class="careers-page__section-title">
				<?php esc_html_e( 'A Clear Path From Apply To Welcome', 'smart-leading-net' ); ?>
			</h2>
			<p class="careers-page__section-desc">
				<?php esc_html_e( 'Transparent steps, timely communication, and assessments that reflect the real work you will do.', 'smart-leading-net' ); ?>
			</p>
		</header>

		<div class="careers-page__proc-wrap">
			<div class="careers-page__proc-line" aria-hidden="true"></div>
			<?php foreach ( $steps as $step ) : ?>
				<article class="careers-page__proc-step careers-page__reveal">
					<div class="careers-page__proc-num"><?php echo esc_html( $step['number'] ); ?></div>
					<h3 class="careers-page__proc-title"><?php echo esc_html( $step['title'] ); ?></h3>
					<p class="careers-page__proc-text"><?php echo esc_html( $step['text'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
