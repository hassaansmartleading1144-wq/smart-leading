<?php
/**
 * Homepage client work — 30+ counter, client logos and three client cards.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$uploads = trailingslashit( content_url( '/uploads' ) );

$home_logos = array(
	array( 'src' => '2026/07/kitchen-cabinet.webp', 'alt' => 'Kitchen Cabinet Design' ),
	array( 'src' => '2026/05/columbus_cabinets_city_logo.webp', 'alt' => 'Columbus Cabinets City' ),
	array( 'src' => '2026/05/badger-granite-logo.webp', 'alt' => 'Badger Granite' ),
	array( 'src' => '2026/06/same-flooring-1.webp', 'alt' => 'Sam’s Flooring' ),
);

$home_clients = array(
	array(
		'name'  => 'Kitchen Cabinet Design',
		'text'  => 'Built online demand and supported expansion into two locations.',
		'url'   => 'https://www.kitchencabinetdesign.us/',
		'image' => '2026/06/kitchencabinetdesign2-768x4631.webp',
	),
	array(
		'name'  => 'Columbus Cabinets City',
		'text'  => 'Helped grow cabinet sales and expand into flooring.',
		'url'   => 'https://columbuscabinetscity.com/',
		'image' => '2026/06/columbuscabinetcity2-768x4508.webp',
	),
	array(
		'name'  => 'Badger Granite',
		'text'  => 'Helped build countertop demand and expand into cabinets.',
		'url'   => 'https://badgergranite.com/',
		'image' => '2026/06/badgergranite-768x5813.webp',
	),
);
?>

<section class="home-clients" id="sls-cases" aria-labelledby="home-clients-heading">
	<div class="sls-container">
		<div class="home-clients__head">
			<div data-home-reveal>
				<p class="home-eyebrow"><?php esc_html_e( 'Our client work', 'smart-leading-net' ); ?></p>
				<h2 id="home-clients-heading" class="home-section-head__title home-clients__title">
					<?php esc_html_e( 'From their first customers to', 'smart-leading-net' ); ?>
					<span class="home-accent"><?php esc_html_e( 'million dollar businesses.', 'smart-leading-net' ); ?></span>
				</h2>
			</div>
			<div class="home-clients__metric" data-home-reveal style="--reveal-delay: 120ms">
				<span class="home-clients__number" data-home-count="30" data-home-suffix="+">30+</span>
				<p class="home-clients__metric-text"><?php esc_html_e( 'Businesses helped to grow, with selected clients reaching over $1 million in annual sales.', 'smart-leading-net' ); ?></p>
			</div>
		</div>

		<ul class="home-clients__logos" aria-label="<?php esc_attr_e( 'Client logos', 'smart-leading-net' ); ?>">
			<?php foreach ( $home_logos as $logo ) : ?>
				<li class="home-clients__logo">
					<img src="<?php echo esc_url( $uploads . $logo['src'] ); ?>" alt="<?php echo esc_attr( $logo['alt'] ); ?>" width="160" height="56" loading="lazy" decoding="async">
				</li>
			<?php endforeach; ?>
		</ul>

		<div class="home-clients__grid">
			<?php foreach ( $home_clients as $i => $client ) : ?>
				<article class="home-clients__card" data-home-reveal style="--reveal-delay: <?php echo esc_attr( (string) ( $i * 120 ) ); ?>ms">
					<div class="home-clients__shot">
						<img src="<?php echo esc_url( $uploads . $client['image'] ); ?>" alt="<?php echo esc_attr( sprintf( __( '%s website homepage', 'smart-leading-net' ), $client['name'] ) ); ?>" width="768" height="1024" loading="lazy" decoding="async">
					</div>
					<div class="home-clients__body">
						<h3 class="home-clients__name"><?php echo esc_html( $client['name'] ); ?></h3>
						<p class="home-clients__text"><?php echo esc_html( $client['text'] ); ?></p>
						<a class="home-clients__link" href="<?php echo esc_url( $client['url'] ); ?>" target="_blank" rel="noopener">
							<?php esc_html_e( 'Visit client website', 'smart-leading-net' ); ?>
							<span class="screen-reader-text"><?php echo esc_html( ': ' . $client['name'] . ' ' . __( '(opens in a new tab)', 'smart-leading-net' ) ); ?></span>
							<?php echo sln_home_icon( 'external' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</a>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
