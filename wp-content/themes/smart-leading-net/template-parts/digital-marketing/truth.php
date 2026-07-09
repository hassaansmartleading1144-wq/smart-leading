<?php
/**
 * Digital Marketing page — "A Quick Truth" + quote.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dm_contact_url = sln_get_dm_page_contact_url();
?>

<section class="dm-page__section" aria-labelledby="dm-truth-heading">
	<div class="sls-container">
		<p class="dm-page__eyebrow dm-page__reveal"><?php esc_html_e( 'A Quick Truth', 'smart-leading-net' ); ?></p>
		<h2 id="dm-truth-heading" class="dm-page__section-title dm-page__reveal">
			<?php
			echo wp_kses(
				__( 'You Started With a Vision — <span class="dm-page__hl">Now You Need a System.</span>', 'smart-leading-net' ),
				array( 'span' => array( 'class' => true ) )
			);
			?>
		</h2>
		<p class="dm-page__body dm-page__reveal">
			<?php esc_html_e( 'It began with a vision — to do great work, serve more people, and build something that lasts.', 'smart-leading-net' ); ?>
		</p>
		<p class="dm-page__body dm-page__reveal">
			<?php esc_html_e( 'Then marketing became a maze of dashboards, jargon and budgets that vanish with nothing to show. That\'s not your business failing — it\'s marketing done without a system.', 'smart-leading-net' ); ?>
		</p>
		<p class="dm-page__body dm-page__reveal">
			<?php esc_html_e( 'You don\'t need more noise. You need a partner who turns ambition into a predictable engine for growth.', 'smart-leading-net' ); ?>
		</p>
		<blockquote class="dm-page__quote dm-page__reveal">
			<div class="dm-page__quote-mark" aria-hidden="true">“</div>
			<p class="dm-page__quote-big">
				<?php
				echo wp_kses(
					__( 'You deserve a marketing partner that brings <span class="dm-page__hl">measurable results.</span>', 'smart-leading-net' ),
					array( 'span' => array( 'class' => true ) )
				);
				?>
			</p>
			<cite class="dm-page__quote-attr"><?php esc_html_e( '— What we promise every client on day one.', 'smart-leading-net' ); ?></cite>
		</blockquote>
		<div class="dm-page__cta-wrap dm-page__reveal">
			<a class="dm-page__pill" href="<?php echo esc_url( $dm_contact_url ); ?>">
				<?php esc_html_e( 'Book a Free Strategy Call', 'smart-leading-net' ); ?>
				<span class="dm-page__pill-arrow" aria-hidden="true">→</span>
			</a>
		</div>
	</div>
</section>