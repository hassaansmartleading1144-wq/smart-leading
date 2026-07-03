<?php
/**
 * Digital Marketing page — final CTA.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dm_contact_url = sln_get_dm_page_contact_url();
$checks         = sln_get_dm_page_final_checks();
?>

<section class="dm-page__final" id="dm-contact" aria-labelledby="dm-final-heading">
	<span class="dm-page__blob dm-page__blob--orange" aria-hidden="true"></span>
	<div class="dm-page__wrap">
		<p class="dm-page__eyebrow dm-page__eyebrow--light dm-page__reveal"><?php esc_html_e( 'Your Next Step', 'smart-leading-net' ); ?></p>
		<h2 id="dm-final-heading" class="dm-page__section-title dm-page__section-title--light dm-page__reveal">
			<?php
			echo wp_kses(
				__( 'Let\'s Build a Clear Path to <span class="dm-page__hl">More Leads &amp; Revenue.</span>', 'smart-leading-net' ),
				array( 'span' => array( 'class' => true ) )
			);
			?>
		</h2>
		<p class="dm-page__lead dm-page__lead--light dm-page__reveal">
			<?php esc_html_e( 'Your next client is already online — searching for what you offer. The only question is whether they find you, or your competitor.', 'smart-leading-net' ); ?>
		</p>
		<ul class="dm-page__checks">
			<?php foreach ( $checks as $check ) : ?>
				<li class="dm-page__check dm-page__reveal">
					<span class="dm-page__check-tick" aria-hidden="true">✓</span>
					<?php echo esc_html( $check ); ?>
				</li>
			<?php endforeach; ?>
		</ul>
		<div class="dm-page__cta-wrap dm-page__reveal">
			<a class="dm-page__pill" href="<?php echo esc_url( $dm_contact_url ); ?>">
				<?php esc_html_e( 'Book a Free Strategy Call', 'smart-leading-net' ); ?>
				<span class="dm-page__pill-arrow" aria-hidden="true">→</span>
			</a>
		</div>
		<p class="dm-page__web dm-page__reveal">
			<span class="dm-page__web-icon" aria-hidden="true">⊕</span>
			<?php esc_html_e( 'www.smartleading.net', 'smart-leading-net' ); ?>
		</p>
		<p class="dm-page__finalnote dm-page__reveal">
			<?php esc_html_e( 'No commitment. No pressure. Just an honest conversation about your growth.', 'smart-leading-net' ); ?>
		</p>
	</div>
</section>
