<?php
/**
 * Portfolio page — static projects grid.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$projects = sln_get_portfolio_projects();

if ( empty( $projects ) ) {
	return;
}
?>

<div class="portfolio-page__grid-wrap">
	<div class="portfolio-page__grid">
		<?php foreach ( $projects as $project ) : ?>
			<a
				class="project-card"
				href="<?php echo esc_url( $project['url'] ); ?>"
				<?php echo ! empty( $project['new_tab'] ) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
			>
				<div class="project-image-wrap">
					<?php if ( ! empty( $project['image_url'] ) ) : ?>
						<img
							class="project-image"
							src="<?php echo esc_url( $project['image_url'] ); ?>"
							alt="<?php echo esc_attr( $project['alt'] ); ?>"
							width="640"
							height="480"
							loading="lazy"
							decoding="async"
						/>
					<?php endif; ?>
				</div>
				<div class="project-title-bar">
					<h2 class="project-title-bar__text"><?php echo esc_html( $project['title'] ); ?></h2>
				</div>
			</a>
		<?php endforeach; ?>
	</div>
</div>
