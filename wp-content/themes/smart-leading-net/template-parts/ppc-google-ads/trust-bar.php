<?php
/**
 * PPC & Google Ads page — trust platform bar.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section   = sln_get_ppc_trust_section();
$platforms = sln_get_ppc_trust_platforms();

if ( ! sln_ppc_row_is_active( $section ) || empty( $platforms ) ) {
	return;
}
?>

<div class="sln-ppc-trustbar">
	<div class="sls-container">
		<?php if ( ! empty( $section['label'] ) ) : ?>
			<div class="sln-ppc-trust-label sln-ppc-reveal"><?php echo esc_html( $section['label'] ); ?></div>
		<?php endif; ?>

		<div class="sln-ppc-trust-row sln-ppc-reveal">
			<?php foreach ( $platforms as $platform ) : ?>
				<?php if ( empty( $platform['name'] ) ) : ?>
					<?php continue; ?>
				<?php endif; ?>
				<span class="sln-ppc-trust-pill"><span class="sln-ppc-trust-dot" aria-hidden="true"></span><?php echo esc_html( $platform['name'] ); ?></span>
			<?php endforeach; ?>
		</div>
	</div>
</div>
