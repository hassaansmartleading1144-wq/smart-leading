<?php
/**
 * Growth Services — Growth Page benefits card grid.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$data = sln_get_growth_page_growth_services();

if ( empty( $data['cards'] ) ) {
	return;
}

$has_header = ! empty( $data['label'] )
	|| ! empty( $data['heading_lead'] )
	|| ! empty( $data['highlight_word_1'] )
	|| ! empty( $data['heading_trail'] )
	|| ! empty( $data['highlight_word_2'] )
	|| sln_growth_page_wysiwyg_has_content( $data['description'] );
?>

<section class="growth-services" aria-labelledby="growth-services-heading">
	<div class="growth-services__container sls-container">
		<?php if ( $has_header ) : ?>
			<header class="growth-services__header">
				<?php if ( ! empty( $data['label'] ) ) : ?>
					<p class="growth-services__label"><?php echo esc_html( $data['label'] ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $data['heading_lead'] ) || ! empty( $data['highlight_word_1'] ) || ! empty( $data['heading_trail'] ) || ! empty( $data['highlight_word_2'] ) ) : ?>
					<h2 id="growth-services-heading" class="growth-services__heading growth-services__title">
						<?php if ( ! empty( $data['heading_lead'] ) ) : ?>
							<?php echo esc_html( $data['heading_lead'] ); ?>
						<?php endif; ?>
						<?php if ( ! empty( $data['highlight_word_1'] ) ) : ?>
							<?php echo ! empty( $data['heading_lead'] ) ? ' ' : ''; ?><span class="growth-services__accent growth-services__accent--primary"><?php echo esc_html( $data['highlight_word_1'] ); ?></span>
						<?php endif; ?>
						<?php if ( ! empty( $data['heading_trail'] ) ) : ?>
							<?php echo ( ! empty( $data['heading_lead'] ) || ! empty( $data['highlight_word_1'] ) ) ? ' ' : ''; ?><?php echo esc_html( $data['heading_trail'] ); ?>
						<?php endif; ?>
						<?php if ( ! empty( $data['highlight_word_2'] ) ) : ?>
							<?php echo ' '; ?><span class="growth-services__accent growth-services__accent--accent"><?php echo esc_html( $data['highlight_word_2'] ); ?></span>
						<?php endif; ?>
					</h2>
				<?php endif; ?>

				<?php if ( sln_growth_page_wysiwyg_has_content( $data['description'] ) ) : ?>
					<div class="growth-services__description"><?php echo sln_growth_page_format_wysiwyg_content( $data['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				<?php endif; ?>
			</header>
		<?php endif; ?>

		<div class="growth-services__slider" data-growth-services-slider>
			<div class="growth-services__viewport">
				<div class="growth-services__track">
					<?php foreach ( $data['cards'] as $card ) : ?>
						<article class="growth-services__card">
							<?php if ( ! empty( $card['icon_id'] ) ) : ?>
								<div class="growth-services__icon" aria-hidden="true">
									<?php
									echo sln_get_attachment_inline_svg( absint( $card['icon_id'] ), 'growth-services__icon-svg' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									?>
								</div>
							<?php endif; ?>

							<?php if ( ! empty( $card['title'] ) ) : ?>
								<h3 class="growth-services__card-title"><?php echo esc_html( $card['title'] ); ?></h3>
							<?php endif; ?>

							<?php if ( sln_growth_page_wysiwyg_has_content( $card['description'] ) ) : ?>
								<div class="growth-services__card-text"><?php echo sln_growth_page_format_wysiwyg_content( $card['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
							<?php endif; ?>
						</article>
					<?php endforeach; ?>
				</div>
			</div>

			<?php if ( count( $data['cards'] ) > 1 ) : ?>
				<button type="button" class="growth-services__prev" aria-label="<?php esc_attr_e( 'Previous service', 'smart-leading-net' ); ?>">
					<span aria-hidden="true">&lsaquo;</span>
				</button>
				<button type="button" class="growth-services__next" aria-label="<?php esc_attr_e( 'Next service', 'smart-leading-net' ); ?>">
					<span aria-hidden="true">&rsaquo;</span>
				</button>
				<div class="growth-services__dots" role="tablist" aria-label="<?php esc_attr_e( 'Growth services slides', 'smart-leading-net' ); ?>">
					<?php foreach ( $data['cards'] as $index => $card ) : ?>
						<button
							type="button"
							class="growth-services__dot<?php echo 0 === $index ? ' is-active' : ''; ?>"
							role="tab"
							aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>"
							aria-label="<?php echo esc_attr( sprintf( /* translators: %d: slide number */ __( 'Go to slide %d', 'smart-leading-net' ), $index + 1 ) ); ?>"
							data-slide="<?php echo esc_attr( $index ); ?>"
						></button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
