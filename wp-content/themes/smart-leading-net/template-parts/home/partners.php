<?php
/**
 * Homepage partner strip — official logos with captions. On mobile it
 * becomes a swipeable slider showing two badges at a time.
 *
 * Logos come from each company's own site (assets/images/partners/).
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$partner_logos = trailingslashit( SLN_THEME_URI ) . 'assets/images/partners/';

$home_partners = array(
	array( 'logo' => 'google-partner.webp', 'w' => 120, 'h' => 40, 'name' => 'Google Premier Partner', 'note' => 'Ads, Search & Analytics', 'url' => 'https://www.google.com/partners/agency?id=7238450490' ),
	array( 'logo' => 'meta.svg', 'w' => 85, 'h' => 17, 'name' => 'Meta Certified', 'note' => 'Facebook & Instagram' ),
	array( 'logo' => 'ibm.svg', 'w' => 58, 'h' => 23, 'name' => 'IBM', 'note' => 'AI agent certification' ),
	array( 'logo' => 'shopify.svg', 'w' => 304, 'h' => 87, 'name' => 'Shopify Partner', 'note' => 'Store development' ),
	array( 'logo' => 'wordpress.png', 'w' => 480, 'h' => 163, 'name' => 'WordPress', 'note' => 'Custom development' ),
);
?>

<section class="home-partners" aria-label="<?php esc_attr_e( 'Expertise and credentials', 'smart-leading-net' ); ?>" data-home-partners>
	<div class="sls-container">
		<ul class="home-partners__list">
			<?php foreach ( $home_partners as $i => $partner ) : ?>
				<li class="home-partners__item" data-home-reveal style="--reveal-delay: <?php echo esc_attr( (string) ( $i * 80 ) ); ?>ms">
					<?php if ( ! empty( $partner['url'] ) ) : ?>
						<a class="home-partners__card" href="<?php echo esc_url( $partner['url'] ); ?>" target="_blank" rel="noopener noreferrer">
					<?php else : ?>
						<div class="home-partners__card">
					<?php endif; ?>
						<span class="home-partners__logo">
							<img
								src="<?php echo esc_url( $partner_logos . $partner['logo'] ); ?>"
								alt="<?php echo esc_attr( $partner['name'] ); ?>"
								width="<?php echo esc_attr( (string) $partner['w'] ); ?>"
								height="<?php echo esc_attr( (string) $partner['h'] ); ?>"
								loading="lazy"
								decoding="async"
							>
						</span>
						<span class="home-partners__text">
							<strong class="home-partners__name"><?php echo esc_html( $partner['name'] ); ?></strong>
							<span class="home-partners__note"><?php echo esc_html( $partner['note'] ); ?></span>
						</span>
					<?php if ( ! empty( $partner['url'] ) ) : ?>
						</a>
					<?php else : ?>
						</div>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>

		<div class="home-partners__dots" aria-hidden="true">
			<?php foreach ( $home_partners as $i => $partner ) : ?>
				<span class="home-partners__dot<?php echo 0 === $i ? ' is-active' : ''; ?>"></span>
			<?php endforeach; ?>
		</div>
	</div>
</section>
