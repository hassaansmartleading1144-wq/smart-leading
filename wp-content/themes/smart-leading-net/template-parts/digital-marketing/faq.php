<?php
/**
 * Digital Marketing page — FAQ accordion.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$faq_items = sln_get_dm_page_faq_items();
?>

<section class="dm-page__section dm-page__section--tint" aria-labelledby="dm-faq-heading">
	<div class="dm-page__wrap">
		<p class="dm-page__eyebrow dm-page__reveal"><?php esc_html_e( 'Common Questions', 'smart-leading-net' ); ?></p>
		<h2 id="dm-faq-heading" class="dm-page__section-title dm-page__reveal">
			<?php
			echo wp_kses(
				__( 'Questions? <span class="dm-page__hl">We\'ve Got Answers.</span>', 'smart-leading-net' ),
				array( 'span' => array( 'class' => true ) )
			);
			?>
		</h2>
		<p class="dm-page__lead dm-page__reveal">
			<?php esc_html_e( 'Everything you want to know before we start working together — answered straight.', 'smart-leading-net' ); ?>
		</p>
		<div class="dm-page__faqs">
			<?php foreach ( $faq_items as $index => $item ) : ?>
				<details class="dm-page__faq dm-page__reveal">
					<summary>
						<span class="dm-page__faq-q" aria-hidden="true">Q</span>
						<span class="dm-page__faq-question"><?php echo esc_html( $item['question'] ); ?></span>
						<span class="dm-page__faq-chev" aria-hidden="true">▼</span>
					</summary>
					<div class="dm-page__faq-answer" id="dm-faq-answer-<?php echo esc_attr( (string) ( $index + 1 ) ); ?>">
						<?php echo esc_html( $item['answer'] ); ?>
					</div>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>
