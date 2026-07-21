<?php
/**
 * Template Name: Digital Marketing Services
 *
 * Digital marketing services landing page — standard WordPress page template.
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

<main id="primary" class="site-main sln-dm-page">
	<?php
	get_template_part( 'template-parts/digital-marketing/hero' );
	get_template_part( 'template-parts/digital-marketing/reality' );
	get_template_part( 'template-parts/digital-marketing/approach' );
	get_template_part( 'template-parts/digital-marketing/quick-truth' );
	get_template_part( 'template-parts/digital-marketing/services' );
	get_template_part( 'template-parts/digital-marketing/paid-advertising' );
	get_template_part( 'template-parts/digital-marketing/process' );
	get_template_part( 'template-parts/digital-marketing/proof' );
	get_template_part( 'template-parts/digital-marketing/pricing' );
	get_template_part( 'template-parts/digital-marketing/faq' );
	get_template_part( 'template-parts/digital-marketing/final-cta' );
	?>

	<?php if ( get_the_content() ) : ?>
	<div class="sln-dm-section">
		<div class="sls-container sln-dm-wrap">
			<div class="sln-dm-editor-content">
				<?php the_content(); ?>
			</div>
		</div>
	</div>
	<?php endif; ?>
</main>

	<?php
endwhile;

get_footer();
