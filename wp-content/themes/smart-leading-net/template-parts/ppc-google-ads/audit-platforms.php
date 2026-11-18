<?php
/**
 * Free PPC Audit — platforms covered.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_helpers.php';

$section = array(
	'small_heading'    => __( 'Platforms', 'smart-leading-net' ),
	'main_heading'     => __( 'Google Ads Audit, Local Service Ads', 'smart-leading-net' ),
	'highlighted_text' => __( 'and Meta Ads Reviews.', 'smart-leading-net' ),
	'description'      => __( 'Choose one platform or all three. Each gets its own checks, in the same report.', 'smart-leading-net' ),
);

$cards = array(
	array(
		'icon'   => 'search',
		'title'  => __( 'Google Ads Audit', 'smart-leading-net' ),
		'text'   => __( 'A free Google Ads audit service for Search, Shopping, Performance Max and YouTube.', 'smart-leading-net' ),
		'points' => array( __( 'Search terms and negatives', 'smart-leading-net' ), __( 'Conversion and call tracking', 'smart-leading-net' ), __( 'Quality Score and bidding', 'smart-leading-net' ) ),
	),
	array(
		'icon'   => 'shield-check',
		'title'  => __( 'Local Service Ads Review', 'smart-leading-net' ),
		'text'   => __( 'Profile, ranking signals and lead quality in your Local Services account.', 'smart-leading-net' ),
		'points' => array( __( 'Job types and service areas', 'smart-leading-net' ), __( 'Reviews and responsiveness', 'smart-leading-net' ), __( 'Leads you could dispute', 'smart-leading-net' ) ),
	),
	array(
		'icon'   => 'users',
		'title'  => __( 'Meta Ads Audit', 'smart-leading-net' ),
		'text'   => __( 'A Facebook ads audit covering Facebook and Instagram campaigns.', 'smart-leading-net' ),
		'points' => array( __( 'Audiences and creative fatigue', 'smart-leading-net' ), __( 'Meta Pixel and Conversions API', 'smart-leading-net' ), __( 'Lead form quality', 'smart-leading-net' ) ),
	),
);
?>

<section id="audit-platforms" class="sln-ppc-section sln-audplat" aria-labelledby="sln-audplat-heading">
	<div class="sls-container">
		<?php sln_ppc_part_render_heading( $section, 'sln-audplat-heading', true, 'azure' ); ?>

		<div class="sln-audplat-grid">
			<?php foreach ( $cards as $i => $card ) : ?>
				<article class="sln-audplat-card sln-ppc-reveal" style="--i:<?php echo (int) $i; ?>">
					<span class="sln-audplat-icon"><?php echo sln_ppc_icon( $card['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<h3><?php echo esc_html( $card['title'] ); ?></h3>
					<p><?php echo esc_html( $card['text'] ); ?></p>
					<ul>
						<?php foreach ( $card['points'] as $point ) : ?>
							<li><?php echo sln_ppc_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $point ); ?></li>
						<?php endforeach; ?>
					</ul>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
