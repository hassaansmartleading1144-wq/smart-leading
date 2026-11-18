<?php
/**
 * Free PPC Audit — what you receive (report, video, call).
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_helpers.php';

$section = array(
	'small_heading'    => __( 'What You Get', 'smart-leading-net' ),
	'main_heading'     => __( 'Your PPC Audit Report,', 'smart-leading-net' ),
	'highlighted_text' => __( 'Explained Three Ways.', 'smart-leading-net' ),
	'description'      => __( 'Delivered within 2–3 business days of getting access to your accounts.', 'smart-leading-net' ),
);

$items = array(
	array( 'file-question', __( 'PDF Audit Report', 'smart-leading-net' ), __( 'Findings for each platform, ranked by impact, with an action plan you can hand to anyone.', 'smart-leading-net' ) ),
	array( 'play', __( 'Video Walkthrough', 'smart-leading-net' ), __( 'A recorded walkthrough of your account showing exactly where each issue is.', 'smart-leading-net' ) ),
	array( 'message', __( 'Call to Go Through It', 'smart-leading-net' ), __( 'A call to talk through the findings, answer your questions and agree on next steps.', 'smart-leading-net' ) ),
);
?>

<section id="audit-report" class="sln-ppc-section sln-ppc-section--dark sln-ppc-grid-bg sln-audrep" aria-labelledby="sln-audrep-heading">
	<div class="sls-container">
		<?php sln_ppc_part_render_heading( $section, 'sln-audrep-heading', true, 'azure' ); ?>

		<div class="sln-audrep-wrap">
			<div class="sln-audrep-doc sln-ppc-reveal" aria-hidden="true">
				<div class="sln-audrep-doc-head"><b><?php esc_html_e( 'PPC Audit Report', 'smart-leading-net' ); ?></b><span><?php esc_html_e( 'Example layout', 'smart-leading-net' ); ?></span></div>
				<?php foreach ( array( array( 'High', 'is-high' ), array( 'High', 'is-high' ), array( 'Medium', 'is-med' ), array( 'Quick win', 'is-win' ) ) as $r => $row ) : ?>
					<div class="sln-audrep-row" style="--i:<?php echo (int) $r; ?>"><i></i><em></em><span class="<?php echo esc_attr( $row[1] ); ?>"><?php echo esc_html( $row[0] ); ?></span></div>
				<?php endforeach; ?>
				<div class="sln-audrep-plan"><?php echo sln_ppc_icon( 'clipboard-check' ); // phpcs:ignore ?><?php esc_html_e( 'Action plan', 'smart-leading-net' ); ?></div>
			</div>

			<ol class="sln-audrep-list">
				<?php foreach ( $items as $i => $item ) : ?>
					<li class="sln-ppc-reveal" style="--i:<?php echo (int) $i; ?>">
						<span class="sln-audrep-icon"><?php echo sln_ppc_icon( $item[0] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<span><b><?php echo esc_html( $item[1] ); ?></b><?php echo esc_html( $item[2] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</div>
</section>
