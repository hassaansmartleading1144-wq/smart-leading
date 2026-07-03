<?php
/**
 * Sidebar template.
 *
 * @package Smart_Leading_Net
 */
?>

<aside id="secondary" class="widget-area">
	<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	<?php endif; ?>
</aside>
