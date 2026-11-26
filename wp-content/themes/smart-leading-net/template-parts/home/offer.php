<?php
/**
 * Homepage offer band — "No sales growth? No covered agency fee."
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<section class="home-offer" aria-labelledby="home-offer-heading">
	<div class="sls-container">
		<div class="home-offer__card" data-home-reveal>
			<div class="home-offer__seal" aria-hidden="true">
				<span class="home-offer__ring"></span>
				<?php echo sln_home_icon( 'shield', 'home-offer__seal-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>

			<div class="home-offer__content">
				<h2 id="home-offer-heading" class="home-offer__heading">
					<?php esc_html_e( 'No sales growth?', 'smart-leading-net' ); ?>
					<span class="home-offer__accent"><?php esc_html_e( 'No covered agency fee.', 'smart-leading-net' ); ?></span>
				</h2>
				<p class="home-offer__text"><?php esc_html_e( 'For eligible businesses, we agree on a sales target and measure the results together. If we miss the agreed target, the covered agency fee is waived.', 'smart-leading-net' ); ?></p>
				<p class="home-offer__note"><?php esc_html_e( 'Subject to eligibility and written terms. Advertising and third party costs are separate.', 'smart-leading-net' ); ?></p>
			</div>

			<div class="home-offer__action">
				<?php
				sln_render_cta_button(
					array(
						'text'    => __( 'See if you qualify', 'smart-leading-net' ),
						'url'     => sln_home_contact_url(),
						'variant' => 'secondary',
					)
				);
				?>
			</div>
		</div>
	</div>
</section>
