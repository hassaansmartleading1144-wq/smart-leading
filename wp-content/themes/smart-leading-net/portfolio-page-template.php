<?php
/**
 * Template Name: Portfolio
 *
 * Portfolio page — Contact-style hero + static project grid.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	?>

<div class="portfolio-page">
	<?php get_template_part( 'template-parts/portfolio/hero' ); ?>

	<section class="portfolio-page__projects" aria-label="<?php esc_attr_e( 'Portfolio projects', 'smart-leading-net' ); ?>">
		<div class="sls-container">
			<?php get_template_part( 'template-parts/portfolio/projects', 'grid' ); ?>
		</div>
	</section>

	<?php get_template_part( 'template-parts/sections/credibility' ); ?>
</div>

	<?php
endwhile;

get_footer();
