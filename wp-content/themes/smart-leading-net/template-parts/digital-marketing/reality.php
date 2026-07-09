<?php
/**
 * Digital Marketing page — The Reality section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cards = sln_get_dm_page_reality_cards();
?>

<section class="dm-page__section dm-page__reality" id="dm-reality" aria-labelledby="dm-reality-heading">
	<div class="sls-container">
		<header class="dm-page__section-head dm-page__reveal">
			<p class="dm-page__eyebrow"><?php esc_html_e( 'The Reality', 'smart-leading-net' ); ?></p>
			<h2 id="dm-reality-heading" class="dm-page__section-title">
				<?php esc_html_e( 'We Understand the Challenges Holding Your Business Back.', 'smart-leading-net' ); ?>
			</h2>
			<p class="dm-page__section-desc dm-page__section-desc--italic">
				<?php esc_html_e( 'Most owners come to us after years of frustration. If any of this feels familiar, you\'re in the right place.', 'smart-leading-net' ); ?>
			</p>
		</header>

		<div class="dm-page__reality-grid">
			<?php foreach ( $cards as $card ) : ?>
				<article class="dm-page__reality-card dm-page__reveal">
					<div class="dm-page__reality-card-head">
						<div class="dm-page__reality-icon" aria-hidden="true">
							<?php get_template_part( 'template-parts/digital-marketing/icons/reality', null, array( 'icon' => $card['icon'] ) ); ?>
						</div>
						<h3 class="dm-page__reality-title"><?php echo esc_html( $card['title'] ); ?></h3>
					</div>
					<p class="dm-page__reality-text"><?php echo esc_html( $card['text'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>

		<div class="dm-page__reality-banner dm-page__reveal">
			<p><?php esc_html_e( 'If even one of these feels familiar — you\'re exactly who we built this for.', 'smart-leading-net' ); ?></p>
		</div>
	</div>
</section>
