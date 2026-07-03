<?php
/**
 * Page content template.
 *
 * @package Smart_Leading_Net
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header mb-4">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="entry-thumbnail mb-4">
			<?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid rounded' ) ); ?>
		</div>
	<?php endif; ?>

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<nav class="page-links mt-4">' . esc_html__( 'Pages:', 'smart-leading-net' ),
				'after'  => '</nav>',
			)
		);
		?>
	</div>
</article>
