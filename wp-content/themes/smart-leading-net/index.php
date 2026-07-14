<?php
/**
 * Main template file.
 *
 * @package Smart_Leading_Net
 */

get_header();

if ( is_front_page() ) {
	locate_template( 'front-page.php', true, false );
	return;
}

?>
<div class="container py-5">
	<div class="row">
		<div class="col-lg-8">
			<?php if ( have_posts() ) : ?>
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/global/content', get_post_type() );
				endwhile;

				the_posts_navigation(
					array(
						'prev_text' => esc_html__( 'Older posts', 'smart-leading-net' ),
						'next_text' => esc_html__( 'Newer posts', 'smart-leading-net' ),
					)
				);
				?>
			<?php else : ?>
				<?php get_template_part( 'template-parts/global/content', 'none' ); ?>
			<?php endif; ?>
		</div>

		<div class="col-lg-4">
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>

<?php
get_footer();
