<?php
/**
 * Our Projects section — portfolio carousel.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$our_projects_items = sln_get_our_projects_items();
?>

<section class="our-projects" aria-labelledby="our-projects-heading">
	<div class="sls-container our-projects__container">
		<div class="our-projects__wrapper">
			<div class="our-projects__header">
				<div class="our-projects__header-left">
					<p class="our-projects__label">
						<span class="our-projects__label-icon" aria-hidden="true">*</span>
						<?php esc_html_e( 'OUR PROJECTS', 'smart-leading-net' ); ?>
					</p>

					<h2 id="our-projects-heading" class="our-projects__heading our-projects__title">
						<span class="our-projects__heading-line"><?php esc_html_e( 'Showcasing Our Success', 'smart-leading-net' ); ?></span>
						<span class="our-projects__heading-line">
							<?php
							echo wp_kses(
								sprintf(
									/* translators: %s: highlighted word */
									__( 'With Innovative %s', 'smart-leading-net' ),
									'<span class="our-projects__heading-accent">' . esc_html__( 'Projects', 'smart-leading-net' ) . '</span>'
								),
								array(
									'span' => array(
										'class' => true,
									),
								)
							);
							?>
						</span>
					</h2>
				</div>

				<div class="our-projects__header-right projects-header-right">
					<p class="our-projects__description">
						<?php esc_html_e( 'Explore digital platforms built to solve business challenges. From user journeys to conversion-focused architecture, we build funnels that drive market performance.', 'smart-leading-net' ); ?>
					</p>

					<div class="our-projects__button-wrap">
						<?php
						sln_render_cta_button(
							array(
								'text'    => __( 'View All Project', 'smart-leading-net' ),
								'url'     => '#',
								'variant' => 'primary',
							)
						);
						?>
					</div>
				</div>
			</div>

			<?php if ( ! empty( $our_projects_items ) ) : ?>
				<div class="our-projects__slider-wrap">
					<div class="swiper our-projects__slider">
						<div class="swiper-wrapper">
							<?php foreach ( $our_projects_items as $project ) : ?>
								<div class="swiper-slide">
									<a
										class="project-card"
										href="<?php echo esc_url( $project['url'] ); ?>"
										target="_blank"
										rel="noopener noreferrer"
									>
										<div class="project-image-wrap">
											<?php if ( ! empty( $project['image_url'] ) ) : ?>
												<img
													class="project-image"
													src="<?php echo esc_url( $project['image_url'] ); ?>"
													alt="<?php echo esc_attr( $project['title'] ); ?>"
													width="640"
													height="480"
													loading="lazy"
													decoding="async"
												/>
											<?php endif; ?>
										</div>
										<div class="project-title-bar">
											<h3 class="project-title-bar__text"><?php echo esc_html( $project['title'] ); ?></h3>
										</div>
									</a>
								</div>
							<?php endforeach; ?>
						</div>

						<div class="slider-dots our-projects__pagination swiper-pagination" aria-label="<?php esc_attr_e( 'Project slides', 'smart-leading-net' ); ?>"></div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
