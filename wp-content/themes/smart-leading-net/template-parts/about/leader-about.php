<?php
/**
 * About Us — Leader About section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$about_leader_uploads_dir = '2026/05/';
$about_leader_uploads_url = trailingslashit( content_url( '/uploads/' . $about_leader_uploads_dir ) );
$about_leader_bg          = $about_leader_uploads_url . rawurlencode( 'bg-businesses.webp' );

/**
 * Map logical SVG slugs to filenames in wp-content/uploads/2026/05/.
 *
 * @param string $slug Logical SVG filename.
 * @return string
 */
$about_leader_resolve_svg = static function ( $slug ) use ( $about_leader_uploads_dir ) {
	$aliases = array(
		'growth-business.svg'      => 'growth-buiness.svg',
		'conversion-retention.svg' => 'Conversion Retention.svg',
		'brand-visibility.svg'     => 'Brand Visibility.svg',
		'focused.svg'              => 'focused.svg',
	);

	$filename = isset( $aliases[ $slug ] ) ? $aliases[ $slug ] : $slug;

	return $about_leader_uploads_dir . $filename;
};

$about_leader_blocks = array(
	array(
		'title' => __( 'Custom Strategies. Real Results.', 'smart-leading-net' ),
		'text'  => __( 'We create tailored marketing plans built around your goals — with a sharp focus on what matters most: leads, revenue, and real business growth.', 'smart-leading-net' ),
	),
	array(
		'title' => __( 'Full-Service, Tech-Enabled Marketing', 'smart-leading-net' ),
		'text'  => __( 'Our strategies combine SEO, PPC, content, and more to maximize results. Backed by RevenueCloudFX, you\'ll get transparent reporting and smarter decisions every step of the way.', 'smart-leading-net' ),
	),
	array(
		'title' => __( 'Built for Your Business', 'smart-leading-net' ),
		'text'  => __( 'Forget one-size-fits-all. We deliver fully custom strategies powered by real people, real tech, and a shared passion for helping you grow.', 'smart-leading-net' ),
	),
);

$about_leader_stats = array(
	array(
		'value' => '$10B+',
		'label' => __( 'Revenue Generated', 'smart-leading-net' ),
		'icon'  => 'growth-business.svg',
	),
	array(
		'value' => '24M+',
		'label' => __( 'Leads Delivered', 'smart-leading-net' ),
		'icon'  => 'conversion-retention.svg',
	),
	array(
		'value' => '40+',
		'label' => __( 'Successful Campaigns', 'smart-leading-net' ),
		'icon'  => 'brand-visibility.svg',
	),
	array(
		'value' => '#1',
		'label' => __( 'ROI Focused', 'smart-leading-net' ),
		'icon'  => 'focused.svg',
	),
);
?>

<section
	class="about-leader section-padding"
	aria-labelledby="about-leader-heading"
	
>
	<div class="about-leader__container sls-container">
		<h2 id="about-leader-heading" class="about-leader__title">
			<?php esc_html_e( 'We\'re a Leader in Tech-Enabled Digital Marketing Solutions', 'smart-leading-net' ); ?>
		</h2>

		<div class="about-leader__grid">
			<div class="about-leader__content">
				<?php foreach ( $about_leader_blocks as $block ) : ?>
					<article class="about-leader__block">
						<h3 class="about-leader__block-title"><?php echo esc_html( $block['title'] ); ?></h3>
						<p class="about-leader__block-text"><?php echo esc_html( $block['text'] ); ?></p>
					</article>
				<?php endforeach; ?>
			</div>

			<div class="about-leader__aside">
				<div class="about-leader__stats">
					<?php foreach ( $about_leader_stats as $stat ) : ?>
						<article class="about-leader__stat-card">
							<div class="about-leader__stat-copy">
								<p class="about-leader__stat-value"><?php echo esc_html( $stat['value'] ); ?></p>
								<p class="about-leader__stat-label"><?php echo esc_html( $stat['label'] ); ?></p>
							</div>
							<div class="about-leader__stat-icon" aria-hidden="true">
								<?php
								echo sln_get_upload_inline_svg( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									$about_leader_resolve_svg( $stat['icon'] ),
									'about-leader__stat-icon-svg',
									true
								);
								?>
							</div>
						</article>
					<?php endforeach; ?>
				</div>

				<article class="about-leader__tech-card">
					<h3 class="about-leader__tech-title"><?php esc_html_e( 'IN-HOUSE TECHNOLOGY', 'smart-leading-net' ); ?></h3>
					<div class="about-leader__tech-media">
						<img
							class="about-leader__tech-image"
							src="<?php echo esc_url( sln_get_theme_image_uri( 'in-house-technology.webp' ) ); ?>"
							alt="<?php esc_attr_e( 'In-house technology partners', 'smart-leading-net' ); ?>"
							width="520"
							height="80"
							loading="lazy"
							decoding="async"
						/>
					</div>
				</article>
			</div>
		</div>
	</div>
</section>
