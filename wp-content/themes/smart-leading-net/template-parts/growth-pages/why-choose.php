<?php
/**
 * Why Choose — Growth Page comparison table section.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$data = sln_get_growth_page_why_choose();

if ( empty( $data['rows'] ) ) {
	return;
}

$has_header = ! empty( $data['label'] )
	|| ! empty( $data['heading_lead'] )
	|| ! empty( $data['highlight_word'] )
	|| ! empty( $data['heading_trail'] )
	|| sln_growth_page_wysiwyg_has_content( $data['description'] );

$has_button = ! empty( $data['button_text'] ) && ! empty( $data['button_url'] );
?>

<section class="why-choose" aria-labelledby="why-choose-heading">
	<div class="why-choose__container sls-container">
		<?php if ( $has_header ) : ?>
			<header class="why-choose__header">
				<?php if ( ! empty( $data['label'] ) ) : ?>
					<p class="why-choose__label"><?php echo esc_html( $data['label'] ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $data['heading_lead'] ) || ! empty( $data['highlight_word'] ) || ! empty( $data['heading_trail'] ) ) : ?>
					<h2 id="why-choose-heading" class="why-choose__heading why-choose__title">
						<?php if ( ! empty( $data['heading_lead'] ) ) : ?>
							<?php echo esc_html( $data['heading_lead'] ); ?>
						<?php endif; ?>
						<?php if ( ! empty( $data['highlight_word'] ) ) : ?>
							<?php echo ( ! empty( $data['heading_lead'] ) ? ' ' : '' ); ?><span class="why-choose__accent"><?php echo esc_html( $data['highlight_word'] ); ?></span>
						<?php endif; ?>
						<?php if ( ! empty( $data['heading_trail'] ) ) : ?>
							<?php echo ( ! empty( $data['heading_lead'] ) || ! empty( $data['highlight_word'] ) ) ? ' ' : ''; ?><?php echo esc_html( $data['heading_trail'] ); ?>
						<?php endif; ?>
					</h2>
				<?php endif; ?>

				<?php if ( sln_growth_page_wysiwyg_has_content( $data['description'] ) ) : ?>
					<div class="why-choose__description"><?php echo sln_growth_page_format_wysiwyg_content( $data['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				<?php endif; ?>
			</header>
		<?php endif; ?>

		<div class="why-choose__table-card">
			<div class="why-choose__table-wrap">
				<table class="why-choose__table">
					<thead>
						<tr>
							<th scope="col" class="why-choose__th why-choose__th--feature"><?php esc_html_e( 'What You Get', 'smart-leading-net' ); ?></th>
							<th scope="col" class="why-choose__th why-choose__th--brand"><?php esc_html_e( 'Smart Leading', 'smart-leading-net' ); ?></th>
							<th scope="col" class="why-choose__th"><?php esc_html_e( 'In-House Team', 'smart-leading-net' ); ?></th>
							<th scope="col" class="why-choose__th"><?php esc_html_e( 'Typical Agency', 'smart-leading-net' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $data['rows'] as $index => $row ) : ?>
							<tr class="why-choose__row<?php echo 0 === $index % 2 ? ' why-choose__row--odd' : ' why-choose__row--even'; ?>">
								<th scope="row" class="why-choose__feature"><?php echo esc_html( $row['feature'] ); ?></th>
								<td class="why-choose__cell why-choose__cell--brand"><?php sln_growth_page_render_why_choose_cell( $row['smart_leading'] ); ?></td>
								<td class="why-choose__cell"><?php sln_growth_page_render_why_choose_cell( $row['in_house'] ); ?></td>
								<td class="why-choose__cell"><?php sln_growth_page_render_why_choose_cell( $row['agency'] ); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>

		<?php if ( $has_button ) : ?>
			<div class="why-choose__cta">
				<?php
				sln_render_cta_button(
					array(
						'text'       => $data['button_text'],
						'url'        => $data['button_url'],
						'variant'    => 'primary',
						'show_arrow' => false,
						'class'      => 'why-choose__button',
					)
				);
				?>
			</div>
		<?php endif; ?>
	</div>
</section>
