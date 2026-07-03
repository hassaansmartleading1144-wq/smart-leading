<?php
/**
 * Digital Marketing page — hero section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dm_contact_url = sln_get_dm_page_contact_url();
$dm_stats       = sln_get_dm_page_hero_stats();
?>

<section class="dm-page__hero" aria-labelledby="dm-hero-heading">
	<span class="dm-page__blob dm-page__blob--orange" aria-hidden="true"></span>
	<span class="dm-page__blob dm-page__blob--blue" aria-hidden="true"></span>
	<div class="dm-page__wrap">
		<p class="dm-page__eyebrow dm-page__eyebrow--light"><?php esc_html_e( 'Smart Leading Solutions', 'smart-leading-net' ); ?></p>
		<h1 id="dm-hero-heading" class="dm-page__hero-title">
			<?php
			echo wp_kses(
				__( 'Turn Your Business Challenges Into <span class="dm-page__hl">Growth Opportunities.</span>', 'smart-leading-net' ),
				array( 'span' => array( 'class' => true ) )
			);
			?>
		</h1>
		<p class="dm-page__lead dm-page__lead--light">
			<?php esc_html_e( 'A focused digital marketing roadmap — built to bring you more leads, stronger revenue, and measurable long-term growth.', 'smart-leading-net' ); ?>
		</p>
		<div class="dm-page__cta-wrap">
			<a class="dm-page__pill" href="<?php echo esc_url( $dm_contact_url ); ?>">
				<?php esc_html_e( 'Book a Free Strategy Call', 'smart-leading-net' ); ?>
				<span class="dm-page__pill-arrow" aria-hidden="true">→</span>
			</a>
		</div>
		<div class="dm-page__statgrid" data-dm-countup>
			<?php foreach ( $dm_stats as $stat ) : ?>
				<div class="dm-page__stat">
					<div class="dm-page__stat-num">
						<span
							class="dm-page__count"
							data-pre="<?php echo esc_attr( $stat['prefix'] ); ?>"
							data-val="<?php echo esc_attr( $stat['value'] ); ?>"
							data-dec="<?php echo esc_attr( $stat['decimals'] ); ?>"
							data-suf="<?php echo esc_attr( $stat['suffix'] ); ?>"
						><?php echo esc_html( $stat['prefix'] . '0' . $stat['suffix'] ); ?></span>
						<?php if ( ! empty( $stat['unit'] ) ) : ?>
							<span class="dm-page__stat-unit"><?php echo esc_html( $stat['unit'] ); ?></span>
						<?php endif; ?>
					</div>
					<div class="dm-page__stat-label"><?php echo esc_html( $stat['label'] ); ?></div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
