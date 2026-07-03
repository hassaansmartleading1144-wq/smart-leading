<?php
/**
 * Digital Marketing page — pain points ("The Reality").
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pain_points = sln_get_dm_page_pain_points();
?>

<section class="dm-page__section" aria-labelledby="dm-pain-heading">
	<div class="dm-page__wrap">
		<p class="dm-page__eyebrow dm-page__reveal"><?php esc_html_e( 'The Reality', 'smart-leading-net' ); ?></p>
		<h2 id="dm-pain-heading" class="dm-page__section-title dm-page__reveal">
			<?php
			echo wp_kses(
				__( 'We Understand the Challenges <span class="dm-page__hl">Holding You Back.</span>', 'smart-leading-net' ),
				array( 'span' => array( 'class' => true ) )
			);
			?>
		</h2>
		<p class="dm-page__lead dm-page__reveal">
			<?php esc_html_e( 'Most owners come to us after years of frustration. If any of this feels familiar, you\'re in the right place.', 'smart-leading-net' ); ?>
		</p>
		<ul class="dm-page__list">
			<?php foreach ( $pain_points as $card ) : ?>
				<li class="dm-page__pcard dm-page__reveal">
					<div class="dm-page__pcard-icon dm-page__pcard-icon--<?php echo esc_attr( $card['variant'] ); ?>" aria-hidden="true">!</div>
					<div>
						<h3 class="dm-page__pcard-title"><?php echo esc_html( $card['title'] ); ?></h3>
						<p class="dm-page__pcard-text"><?php echo esc_html( $card['text'] ); ?></p>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
		<div class="dm-page__note dm-page__reveal">
			<?php
			echo wp_kses(
				__( 'If even one of these feels familiar — <strong>you\'re exactly who we built this for.</strong>', 'smart-leading-net' ),
				array( 'strong' => array() )
			);
			?>
		</div>
	</div>
</section>
