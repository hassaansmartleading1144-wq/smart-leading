<?php
/**
 * PPC hub — services grid linking to every PPC sub-page.
 *
 * Cards show the service and a one-line summary; on hover (or always on
 * touch screens) three inclusions slide up with the link.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_helpers.php';

$cards = sln_ppc_hub_services();

if ( ! $cards ) {
	return;
}

$section = array(
	'small_heading'    => __( 'Our PPC Services', 'smart-leading-net' ),
	'main_heading'     => __( 'PPC Services for', 'smart-leading-net' ),
	'highlighted_text' => __( 'Every Paid Channel.', 'smart-leading-net' ),
	'description'      => __( 'Choose one channel or combine them. Every PPC service is managed by the same team and tracked in the same Lead Analytics dashboard, so you can compare cost per lead across platforms.', 'smart-leading-net' ),
);
?>

<section id="ppc-services" class="sln-ppc-section sln-hubsvc" aria-labelledby="sln-hubsvc-heading">
	<div class="sls-container">
		<?php sln_ppc_part_render_heading( $section, 'sln-hubsvc-heading', true, 'azure' ); ?>

		<div class="sln-hubsvc-grid">
			<?php foreach ( $cards as $index => $card ) : ?>
				<a class="sln-hubsvc-card sln-ppc-reveal<?php echo 0 === $index ? ' sln-hubsvc-card--feature' : ''; ?>" href="<?php echo esc_url( $card['url'] ); ?>" style="--i:<?php echo (int) $index; ?>">
					<span class="sln-hubsvc-icon"><?php echo sln_ppc_icon( $card['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span class="sln-hubsvc-num"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span>
					<h3 class="sln-hubsvc-title"><?php echo esc_html( $card['title'] ); ?></h3>
					<p class="sln-hubsvc-text"><?php echo esc_html( $card['text'] ); ?></p>
					<span class="sln-hubsvc-more">
						<ul>
							<?php foreach ( $card['points'] as $point ) : ?>
								<li><?php echo sln_ppc_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $point ); ?></li>
							<?php endforeach; ?>
						</ul>
						<span class="sln-hubsvc-cta"><?php echo $card['live'] ? esc_html__( 'Explore service', 'smart-leading-net' ) : esc_html__( 'Ask about this service', 'smart-leading-net' ); ?> <span aria-hidden="true">→</span></span>
					</span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
