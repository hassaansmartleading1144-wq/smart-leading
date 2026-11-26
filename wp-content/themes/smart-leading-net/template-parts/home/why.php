<?php
/**
 * Homepage "Why Smart Leading Solutions?" — intro panel plus four reasons.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$home_reasons = array(
	array(
		'icon'  => 'users',
		'title' => 'Your own dedicated team',
		'text'  => 'Experienced specialists in SEO, ads, design, development and automation work on your project. You know who is doing the work.',
	),
	array(
		'icon'  => 'target',
		'title' => 'We know what makes your customers buy',
		'text'  => 'We study their needs, fears, interests and buying habits, so your marketing speaks to the right people.',
	),
	array(
		'icon'  => 'wallet',
		'title' => 'Your money is spent with a purpose',
		'text'  => 'We track real leads, bookings and sales. You can see what works and where your budget goes.',
	),
	array(
		'icon'  => 'key',
		'title' => 'Everything stays yours',
		'text'  => 'Your website, ad accounts and customer data remain under your ownership. You stay in control.',
	),
);
?>

<section class="home-why" id="sls-why" aria-labelledby="home-why-heading">
	<div class="sls-container">
		<div class="home-why__panel">
			<div class="home-why__intro" data-home-reveal>
				<p class="home-eyebrow home-eyebrow--light"><?php esc_html_e( 'Why Smart Leading Solutions?', 'smart-leading-net' ); ?></p>
				<h2 id="home-why-heading" class="home-why__heading">
					<span><?php esc_html_e( 'Good people.', 'smart-leading-net' ); ?></span>
					<span class="home-why__accent"><?php esc_html_e( 'Working for your business.', 'smart-leading-net' ); ?></span>
				</h2>
				<p class="home-why__text"><?php esc_html_e( 'When you hire us, you get a team that knows your market, cares about your money and takes responsibility for the work.', 'smart-leading-net' ); ?></p>
				<?php
				sln_render_cta_button(
					array(
						'text'    => __( 'Meet your marketing team', 'smart-leading-net' ),
						'url'     => sln_home_contact_url(),
						'variant' => 'secondary',
					)
				);
				?>
			</div>

			<ul class="home-why__reasons">
				<?php foreach ( $home_reasons as $i => $reason ) : ?>
					<li class="home-why__reason" data-home-reveal style="--reveal-delay: <?php echo esc_attr( (string) ( 120 + $i * 90 ) ); ?>ms">
						<span class="home-why__icon"><?php echo sln_home_icon( $reason['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<div>
							<h3 class="home-why__reason-title"><?php echo esc_html( $reason['title'] ); ?></h3>
							<p class="home-why__reason-text"><?php echo esc_html( $reason['text'] ); ?></p>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>
