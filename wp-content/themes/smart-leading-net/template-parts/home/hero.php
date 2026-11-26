<?php
/**
 * Homepage hero — three rotating messages beside an animated Lead Analytics
 * dashboard (template-parts/home/hero-dashboard.php).
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hero_assets = trailingslashit( content_url( '/uploads/2026/05' ) );
$hero_cta    = sln_home_contact_url();

/*
 * Two fixed stat cards beside the hero dashboard (they stay put while the
 * slides change). 100k+ leads and 3x ROAS are owner-confirmed (17 Nov 2026).
 */
$hero_card_slots = array(
	'a' => array( 'icon' => 'funnel', 'value' => '100k+', 'label' => 'Leads generated', 'tone' => 'blue' ),
	'b' => array( 'icon' => 'trend', 'value' => '3x', 'label' => 'Average ROAS improvement', 'tone' => 'orange' ),
);

/*
 * Google reviews badge above the headline. Review count from the owner
 * (26 Nov 2026). The stars and rating appear once $hero_google_rating is
 * filled in from the Google Business Profile; add the reviews link too.
 */
$hero_google_rating  = ''; // e.g. '4.9' — owner to supply.
$hero_google_reviews = '100+';
$hero_google_url     = ''; // Google Business Profile reviews link.

$hero_slides = array(
	array(
		'lead'   => 'Want more customers,',
		'accent' => 'sales and appointments?',
		'text'   => 'Get a dedicated team for SEO, paid ads, websites and sales automation. We work together to bring you more of the customers your business needs.',
		'cta'    => 'Let’s talk about your business',
	),
	array(
		'lead'   => 'Spending on marketing but',
		'accent' => 'not getting enough sales?',
		'text'   => 'We find where you are losing customers and improve your ads, website, tracking and follow ups.',
		'cta'    => 'Review my marketing',
	),
	array(
		'lead'   => 'Want customers to find you',
		'accent' => 'before your competitors?',
		'text'   => 'Build your presence on Google, Maps and AI search. Give buyers a reason to trust your business.',
		'cta'    => 'Improve my visibility',
	),
);
?>

<section class="hero-banner hero-banner--home" aria-roledescription="carousel" aria-label="<?php esc_attr_e( 'Homepage banner', 'smart-leading-net' ); ?>" style="--hero-banner-pattern: url('<?php echo esc_url( $hero_assets . rawurlencode( 'banner background.webp' ) ); ?>');" data-home-hero>
	<div class="sls-container hero-banner__container">
		<div class="hero-banner__stage">
			<div class="hero-banner__content">

				<?php $hero_trust_tag = $hero_google_url ? 'a' : 'div'; ?>
				<<?php echo $hero_trust_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="home-hero__trust"<?php echo $hero_google_url ? ' href="' . esc_url( $hero_google_url ) . '" target="_blank" rel="noopener"' : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> data-animate="fade-up">
					<span class="home-hero__g" aria-hidden="true">
						<svg viewBox="0 0 48 48" width="22" height="22" focusable="false"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/></svg>
					</span>
					<span class="home-hero__trust-text">
						<?php if ( $hero_google_rating ) : ?>
							<span class="home-hero__rating-line">
								<span class="home-hero__stars" aria-hidden="true">★★★★★</span>
								<strong><?php echo esc_html( $hero_google_rating ); ?></strong>
								<span class="screen-reader-text"><?php esc_html_e( 'out of 5', 'smart-leading-net' ); ?></span>
							</span>
							<span class="home-hero__trust-sub"><?php echo esc_html( sprintf( __( 'from %s Google reviews', 'smart-leading-net' ), $hero_google_reviews ) ); ?></span>
						<?php else : ?>
							<strong><?php echo esc_html( sprintf( __( '%s reviews on Google', 'smart-leading-net' ), $hero_google_reviews ) ); ?></strong>
							<span class="home-hero__trust-sub"><?php esc_html_e( 'From clients in the USA & Australia', 'smart-leading-net' ); ?></span>
						<?php endif; ?>
					</span>
					<?php if ( $hero_google_url ) : ?>
						<span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'smart-leading-net' ); ?></span>
					<?php endif; ?>
				</<?php echo $hero_trust_tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>

				<div class="home-hero__slides" aria-live="polite">
					<?php foreach ( $hero_slides as $index => $slide ) : ?>
						<?php $tag = 0 === $index ? 'h1' : 'h2'; ?>
						<div
							class="home-hero__slide<?php echo 0 === $index ? ' is-active' : ''; ?>"
							id="home-hero-slide-<?php echo esc_attr( (string) $index ); ?>"
							role="group"
							aria-roledescription="slide"
							aria-label="<?php echo esc_attr( sprintf( '%d of %d', $index + 1, count( $hero_slides ) ) ); ?>"
							data-home-slide="<?php echo esc_attr( (string) $index ); ?>"
							<?php echo 0 === $index ? '' : 'aria-hidden="true" inert'; ?>
						>
							<<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> class="hero-banner__heading home-hero__heading">
								<span class="hero-banner__heading-line"><?php echo esc_html( $slide['lead'] ); ?></span>
								<span class="home-hero__accent"><?php echo esc_html( $slide['accent'] ); ?></span>
							</<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>

							<p class="hero-banner__description home-hero__text"><?php echo esc_html( $slide['text'] ); ?></p>

							<?php
							sln_render_cta_button(
								array(
									'text'    => $slide['cta'],
									'url'     => $hero_cta,
									'variant' => 'secondary',
									'class'   => 'home-hero__cta',
								)
							);
							?>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="home-hero__dots" role="tablist" aria-label="<?php esc_attr_e( 'Choose banner', 'smart-leading-net' ); ?>">
					<?php foreach ( $hero_slides as $index => $slide ) : ?>
						<button
							type="button"
							class="home-hero__dot<?php echo 0 === $index ? ' is-active' : ''; ?>"
							role="tab"
							aria-controls="home-hero-slide-<?php echo esc_attr( (string) $index ); ?>"
							aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>"
							aria-label="<?php echo esc_attr( sprintf( __( 'Show banner %d', 'smart-leading-net' ), $index + 1 ) ); ?>"
							data-home-dot="<?php echo esc_attr( (string) $index ); ?>"
						><span class="home-hero__dot-fill"></span></button>
					<?php endforeach; ?>
				</div>
			</div>

			<?php get_template_part( 'template-parts/home/hero-dashboard', null, array( 'card_slots' => $hero_card_slots ) ); ?>
		</div>
	</div>
</section>
