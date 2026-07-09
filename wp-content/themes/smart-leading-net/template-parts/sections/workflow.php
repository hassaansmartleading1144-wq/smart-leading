<?php
/**
 * Workflow section — marketing process steps.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$workflow_bg      = sln_get_workflow_background_url();
$workflow_uploads = '2026/05/';

$workflow_steps = array(
	array(
		'number'      => '01',
		'icon'        => 'duscovery.svg',
		'title'       => __( 'Discovery Call', 'smart-leading-net' ),
		'description' => __( 'We learn your goals, audience, and current challenges to build your custom growth roadmap.', 'smart-leading-net' ),
	),
	array(
		'number'      => '02',
		'icon'        => 'strategy.svg',
		'title'       => __( 'Strategy & Audit', 'smart-leading-net' ),
		'description' => __( 'Full audit of your website, seo, ads, and competition to identify the highest-impact opportunities.', 'smart-leading-net' ),
	),
	array(
		'number'      => '03',
		'icon'        => 'launch.svg',
		'title'       => __( 'Build & Launch', 'smart-leading-net' ),
		'description' => __( 'We design, build, and launch your campaigns, funnels, and automation systems with precision.', 'smart-leading-net' ),
	),
	array(
		'number'      => '04',
		'icon'        => 'optimze.svg',
		'title'       => __( 'Optimize & Scale', 'smart-leading-net' ),
		'description' => __( 'Continuous testing, monitoring, and scaling what\'s driving real revenue and roi for you.', 'smart-leading-net' ),
	),
);

$workflow_section_style = 'position:relative;overflow:hidden;background-color:#ecf2fc;';

if ( $workflow_bg ) {
	$workflow_section_style .= sprintf(
		'',
		esc_url( $workflow_bg )
	);
}
?>

<style id="workflow-critical-css"><?php echo sln_get_workflow_critical_css(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></style>

<section
	class="workflow is-visible"
	aria-labelledby="workflow-heading"
	style="<?php echo esc_attr( $workflow_section_style ); ?>"
>
	<div class="sls-container workflow__container">
		<header class="workflow__header">
			<p class="workflow__label"><?php esc_html_e( 'Marketing Workflow', 'smart-leading-net' ); ?></p>

			<h2 id="workflow-heading" class="workflow__heading workflow__title">
				<?php
				echo wp_kses(
					sprintf(
						/* translators: %s: highlighted phrase */
						__( 'From Discovery Call To %s', 'smart-leading-net' ),
						'<span class="workflow__heading-accent">' . esc_html__( 'Revenue Growth', 'smart-leading-net' ) . '</span>'
					),
					array(
						'span' => array(
							'class' => true,
						),
					)
				);
				?>
			</h2>

			<p class="workflow__description">
				<?php esc_html_e( 'Our process combines smart strategy, lead generation, and marketing automation to grow your revenue exponentially.', 'smart-leading-net' ); ?>
			</p>
		</header>

		<div class="workflow__grid">
			<?php foreach ( $workflow_steps as $step ) : ?>
				<article class="workflow__card">
					<div class="workflow__card-shell">
						<div class="workflow__badge-group" aria-hidden="true">
							<div class="workflow__badge">
								<span class="workflow__badge-number"><?php echo esc_html( $step['number'] ); ?></span>
							</div>
							<span class="workflow__badge-pointer"></span>
						</div>

						<div class="workflow__card-body">
							<div class="workflow__card-content">
								<span class="workflow__icon">
									<?php
									echo sln_get_upload_inline_svg( $workflow_uploads . $step['icon'], 'workflow__icon-svg', true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									?>
								</span>

								<h3 class="workflow__card-title"><?php echo esc_html( $step['title'] ); ?></h3>

								<p class="workflow__card-text"><?php echo esc_html( $step['description'] ); ?></p>
							</div>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
