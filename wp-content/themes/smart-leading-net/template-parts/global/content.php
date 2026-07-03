<?php
/**
 * Default post content template.
 *
 * @package Smart_Leading_Net
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'mb-5' ); ?>>
	<header class="entry-header mb-3">
		<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
		<div class="entry-meta text-muted small">
			<?php echo esc_html( get_the_date() ); ?>
		</div>
	</header>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="entry-thumbnail mb-3">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid rounded' ) ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div>
</article>
