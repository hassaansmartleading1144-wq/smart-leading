<?php
/**
 * Single post template.
 *
 * @package Smart_Leading_Net
 */

get_header();
?>

<div class="container py-5">
	<div class="row justify-content-center">
		<div class="col-lg-8">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/global/content', 'single' );
			endwhile;
			?>
		</div>
	</div>
</div>

<?php
get_footer();
