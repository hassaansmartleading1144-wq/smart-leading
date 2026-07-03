<?php
/**
 * Expertise section — platforms and capabilities showcase.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$expertise_uploads_url = trailingslashit( content_url( '/uploads/2026/05' ) );
$expertise_gif         = $expertise_uploads_url . rawurlencode( 'video file.gif' );
$expertise_icon        = '2026/05/Group trick.svg';

$expertise_items = array(
	__( 'We help ambitious brands grow through paid ads, search visibility, content, and conversion-focused strategy.', 'smart-leading-net' ),
	__( 'Our team combines creative execution, performance insight, and platform expertise to turn traffic into qualified leads and sales.', 'smart-leading-net' ),
	__( 'From paid media and SEO to websites, automation, and analytics, every solution is built to support measurable business growth.', 'smart-leading-net' ),
);
?>

<section class="expertise is-visible" aria-labelledby="expertise-heading">
	<div class="sls-container expertise__container">
		<div class="expertise__box">
			<div class="expertise__shape expertise__shape--left" aria-hidden="true"></div>
			<div class="expertise__shape expertise__shape--right" aria-hidden="true"></div>

			<div class="expertise__inner">
				<div class="expertise__content">
					<p class="expertise__label"><?php esc_html_e( 'Smart Leading Solutions', 'smart-leading-net' ); ?></p>

					<h2 id="expertise-heading" class="expertise__heading expertise__title">
						<?php
						echo wp_kses(
							sprintf(
								/* translators: %s: highlighted word */
								__( 'Our %s', 'smart-leading-net' ),
								'<span class="expertise__heading-accent">' . esc_html__( 'Expertise', 'smart-leading-net' ) . '</span>'
							),
							array(
								'span' => array(
									'class' => true,
								),
							)
						);
						?>
					</h2>

					<div class="expertise__items">
						<?php foreach ( $expertise_items as $item ) : ?>
							<div class="expertise__item">
								<span class="expertise__check" aria-hidden="true">
									<?php
									echo sln_get_upload_inline_svg( $expertise_icon, 'expertise__check-icon', true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									?>
								</span>
								<p class="expertise__item-text"><?php echo esc_html( $item ); ?></p>
							</div>
						<?php endforeach; ?>
					</div>

					<?php
					sln_render_cta_button(
						array(
							'text'    => __( 'Explore Our Expertise', 'smart-leading-net' ),
							'url'     => '#',
							'variant' => 'primary',
							'class'   => 'expertise__cta',
						)
					);
					?>
				</div>

				<div class="expertise__media">
					<div class="expertise__media-mask">
						<!--<img-->
						<!--	class="expertise__gif"-->
						<!--	src="<?php echo esc_url( $expertise_gif ); ?>"-->
						<!--	alt="<?php esc_attr_e( 'Marketing platforms and tools we work with', 'smart-leading-net' ); ?>"-->
						<!--	width="640"-->
						<!--	height="640"-->
						<!--	loading="lazy"-->
						<!--	decoding="async"-->
						<!--/>-->
						<!--<span class="expertise__media-overlay expertise__media-overlay--top" aria-hidden="true"></span>-->
						<!--<span class="expertise__media-overlay expertise__media-overlay--bottom" aria-hidden="true"></span>-->
						
						<iframe
    src="https://api.leadconnectorhq.com/widget/form/D1Xax46qCYrJaPCKfsWj"
    style="width:100%;height:100%;border:none;border-radius:8px"
    id="inline-D1Xax46qCYrJaPCKfsWj" 
    data-layout="{'id':'INLINE'}"
    data-trigger-type="alwaysShow"
    data-trigger-value=""
    data-activation-type="alwaysActivated"
    data-activation-value=""
    data-deactivation-type="neverDeactivate"
    data-deactivation-value=""
    data-form-name="Form 0"
    data-height="882"
    data-layout-iframe-id="inline-D1Xax46qCYrJaPCKfsWj"
    data-form-id="D1Xax46qCYrJaPCKfsWj"
    title="Form 0"
    
        >
</iframe>
<script src="https://link.msgsndr.com/js/form_embed.js"></script>
						
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
