<?php
/**
 * Digital Marketing page — A Quick Truth / vision + quote.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section     = sln_get_dm_truth_section();
$paragraphs  = sln_get_dm_truth_paragraphs();
$quote       = sln_get_dm_truth_quote();
$post_id     = get_the_ID();
$grad_id     = 'sln-dm-qgrad-' . ( $post_id ? (string) $post_id : '0' );

if ( ! sln_dm_row_is_active( $section ) ) {
	return;
}

$show_quote = sln_dm_row_is_active( $quote );
?>

<section class="sln-dm-section" id="dm-truth" aria-labelledby="sln-dm-truth-heading">
	<div class="sls-container sln-dm-wrap">
		<div class="sln-dm-truth-grid<?php echo $show_quote ? '' : ' sln-dm-truth-grid--solo'; ?>">
			<div class="sln-dm-truth-grid__copy">
				<div class="sln-dm-rule sln-dm-animate" aria-hidden="true"></div>
				<?php if ( ! empty( $section['small_heading'] ) ) : ?>
					<p class="sln-dm-eyebrow sln-dm-animate"><?php echo esc_html( $section['small_heading'] ); ?></p>
				<?php endif; ?>
				<h2 id="sln-dm-truth-heading" class="sln-dm-title sln-dm-animate">
					<?php
					echo esc_html( $section['main_heading'] ?? '' );
					if ( ! empty( $section['highlighted_text'] ) ) {
						echo ' <span class="sln-dm-hl">' . esc_html( $section['highlighted_text'] ) . '</span>';
					}
					?>
				</h2>

				<?php foreach ( $paragraphs as $paragraph ) : ?>
					<?php if ( sln_dm_plain_text( $paragraph['text'] ?? '' ) ) : ?>
						<p class="sln-dm-body sln-dm-animate"><?php echo esc_html( sln_dm_plain_text( $paragraph['text'] ) ); ?></p>
					<?php endif; ?>
				<?php endforeach; ?>

				<?php if ( ! empty( $section['button_text'] ) ) : ?>
					<div class="sln-dm-truth-grid__cta sln-dm-animate">
						<?php
						sln_render_dm_page_button(
							array(
								'text'    => $section['button_text'],
								'url'     => $section['button_url'] ?? '#dm-contact',
								'variant' => 'primary',
								'arrow'   => true,
							)
						);
						?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( $show_quote ) : ?>
				<blockquote class="sln-dm-quote sln-dm-animate" data-sln-dm-quote>
					<div class="sln-dm-quote__mark" aria-hidden="true">“</div>
					<p class="sln-dm-quote__text">
						<?php
						echo esc_html( $quote['quote_text'] ?? '' );
						if ( ! empty( $quote['highlighted_text'] ) ) {
							echo ' <span class="sln-dm-hl">' . esc_html( $quote['highlighted_text'] ) . '</span>';
						}
						?>
					</p>
					<?php if ( ! empty( $quote['attribution'] ) ) : ?>
						<cite class="sln-dm-quote__attr"><?php echo esc_html( $quote['attribution'] ); ?></cite>
					<?php endif; ?>

					<div class="sln-dm-quote__graph">
						<div class="sln-dm-quote__graph-head">
							<span><?php echo esc_html( $quote['graph_label'] ?? '' ); ?></span>
							<?php if ( ! empty( $quote['graph_growth'] ) ) : ?>
								<strong><?php echo esc_html( $quote['graph_growth'] ); ?></strong>
							<?php endif; ?>
						</div>
						<svg viewBox="0 0 260 70" xmlns="http://www.w3.org/2000/svg" class="sln-dm-quote__graph-svg" aria-hidden="true">
							<defs>
								<linearGradient id="<?php echo esc_attr( $grad_id ); ?>" x1="0" y1="0" x2="0" y2="1">
									<stop offset="0" stop-color="#ED7322" stop-opacity=".45"/>
									<stop offset="1" stop-color="#ED7322" stop-opacity="0"/>
								</linearGradient>
							</defs>
							<path class="sln-dm-quote__area" d="M0,58 L30,52 L65,55 L100,40 L135,44 L170,26 L205,30 L220,18 L220,70 L0,70 Z" fill="url(#<?php echo esc_attr( $grad_id ); ?>)"/>
							<polyline class="sln-dm-quote__trend" points="0,58 30,52 65,55 100,40 135,44 170,26 205,30 220,18"/>
							<circle class="sln-dm-quote__dot" cx="220" cy="18" r="4.5" fill="#FF8A3D"/>
						</svg>
					</div>
				</blockquote>
			<?php endif; ?>
		</div>
	</div>
</section>
