<?php
/**
 * Template Name: Founders Behind
 * Template Post Type: page
 *
 * Founders page. Founder photos, bios, socials and courses are edited in the
 * "Founders Page" meta box on this page (inc/founders-page-admin.php).
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$sfp_data     = sln_founders_get_data( get_the_ID() );
	$sfp_hero     = $sfp_data['hero'];
	$sfp_founders = $sfp_data['founders'];
	$sfp_editor   = current_user_can( 'edit_post', get_the_ID() );
	?>

<main id="primary" class="site-main sls-founders-page">

	<!-- ============ 1. Hero ============ -->
	<section class="sfp-section sfp-hero" aria-labelledby="sfp-hero-title">
		<div class="sls-container sfp-hero__grid">
			<div class="sfp-hero__copy">
				<?php if ( $sfp_hero['label'] ) : ?>
					<p class="sfp-label sfp-reveal"><?php echo esc_html( $sfp_hero['label'] ); ?></p>
				<?php endif; ?>
				<h1 id="sfp-hero-title" class="sfp-reveal" data-delay="1"><?php echo sln_founders_highlight( $sfp_hero['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h1>
				<?php if ( $sfp_hero['intro'] ) : ?>
					<p class="sfp-hero__intro sfp-reveal" data-delay="2"><?php echo esc_html( $sfp_hero['intro'] ); ?></p>
				<?php endif; ?>
				<?php if ( $sfp_hero['since'] ) : ?>
					<p class="sfp-hero__since sfp-reveal" data-delay="2"><span><?php esc_html_e( 'Since', 'smart-leading-net' ); ?></span> <?php echo esc_html( $sfp_hero['since'] ); ?></p>
				<?php endif; ?>
				<?php if ( count( $sfp_founders ) > 1 ) : ?>
					<nav class="sfp-hero__jump sfp-reveal" data-delay="2" aria-label="<?php esc_attr_e( 'Jump to a founder', 'smart-leading-net' ); ?>">
						<?php foreach ( $sfp_founders as $sfp_f ) : ?>
							<a href="#<?php echo esc_attr( $sfp_f['anchor'] ); ?>"><?php echo esc_html( $sfp_f['name'] ); ?><?php sln_founders_icon( 'arrow-down' ); ?></a>
						<?php endforeach; ?>
					</nav>
				<?php endif; ?>
			</div>

			<?php if ( $sfp_hero['joint_photo'] ) : ?>
				<figure class="sfp-hero__joint sfp-reveal" data-delay="1">
					<?php
					echo wp_get_attachment_image(
						$sfp_hero['joint_photo'],
						'large',
						false,
						array(
							'class'         => 'sfp-hero__joint-img',
							'fetchpriority' => 'high',
							'loading'       => 'eager',
						)
					);
					?>
				</figure>
			<?php else : ?>
				<div class="sfp-hero__pair sfp-reveal" data-delay="1">
					<?php foreach ( array_slice( $sfp_founders, 0, 2 ) as $sfp_f ) : ?>
						<figure>
							<a class="sfp-hero__person" href="#<?php echo esc_attr( $sfp_f['anchor'] ); ?>">
								<?php sln_founders_portrait( $sfp_f, $sfp_editor, true ); ?>
								<figcaption><?php echo esc_html( $sfp_f['name'] ); ?> <span><?php echo esc_html( $sfp_f['role'] ); ?></span></figcaption>
							</a>
						</figure>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<!-- ============ 2. Shared focus ============ -->
	<section class="sfp-section sfp-surface sfp-focus" aria-labelledby="sfp-focus-title">
		<div class="sls-container sfp-focus__grid">
			<div class="sfp-focus__label"><p class="sfp-label sfp-reveal"><?php esc_html_e( 'Our Focus', 'smart-leading-net' ); ?></p></div>
			<div>
				<h2 id="sfp-focus-title" class="sfp-reveal">Understand the business. Focus on <span class="sfp-em">meaningful growth.</span></h2>
				<p class="sfp-focus__body sfp-reveal" data-delay="1">Smart Leading starts with understanding a business, its customers and its website. That understanding helps shape content and marketing around the right audience, with lead generation as the goal.</p>
				<ol class="sfp-flow sfp-reveal" data-delay="2" aria-label="<?php esc_attr_e( 'How Smart Leading\'s focus connects', 'smart-leading-net' ); ?>">
					<li><span class="sfp-flow__icon"><?php sln_founders_icon( 'search' ); ?></span>Business understanding<span class="sfp-flow__arrow" aria-hidden="true">→</span></li>
					<li><span class="sfp-flow__icon"><?php sln_founders_icon( 'users' ); ?></span>Audience relevance<span class="sfp-flow__arrow" aria-hidden="true">→</span></li>
					<li><span class="sfp-flow__icon"><?php sln_founders_icon( 'target' ); ?></span>Lead generation<span class="sfp-flow__arrow" aria-hidden="true">→</span></li>
					<li><span class="sfp-flow__icon"><?php sln_founders_icon( 'chart' ); ?></span>Measurement</li>
				</ol>
			</div>
		</div>
	</section>

	<!-- ============ 3. Founder profiles ============ -->
	<?php foreach ( $sfp_founders as $sfp_i => $sfp_f ) : ?>
		<?php sln_founders_render_profile( $sfp_f, $sfp_i, $sfp_editor ); ?>
	<?php endforeach; ?>

	<!-- ============ 4. Principles ============ -->
	<section class="sfp-section sfp-surface sfp-principles" aria-labelledby="sfp-principles-title">
		<div class="sls-container">
			<div class="sfp-principles__head">
				<p class="sfp-label sfp-reveal"><?php esc_html_e( 'Principles', 'smart-leading-net' ); ?></p>
				<h2 id="sfp-principles-title" class="sfp-reveal" data-delay="1">What guides <span class="sfp-em">Smart Leading.</span></h2>
			</div>
			<ol class="sfp-principles__list">
				<li class="sfp-principle sfp-reveal">
					<span class="sfp-principle__num" aria-hidden="true">01</span>
					<h3>Understand before recommending</h3>
					<p>Start with the business, its customers and the website before shaping the marketing direction.</p>
				</li>
				<li class="sfp-principle sfp-reveal">
					<span class="sfp-principle__num" aria-hidden="true">02</span>
					<h3>Keep lead generation in focus</h3>
					<p>Connect marketing decisions to the business goal of creating more relevant opportunities.</p>
				</li>
				<li class="sfp-principle sfp-reveal">
					<span class="sfp-principle__num" aria-hidden="true">03</span>
					<h3>Value honest measurement</h3>
					<p>Focus on trackable growth and clear interpretation rather than presenting activity as proof of success.</p>
				</li>
			</ol>
		</div>
	</section>

	<!-- ============ 5. Closing CTA ============ -->
	<section class="sfp-section sfp-cta" aria-labelledby="sfp-cta-title">
		<div class="sls-container">
			<div class="sfp-cta__panel sfp-reveal">
				<div>
					<h2 id="sfp-cta-title">Tell us what you want to grow.</h2>
					<p>Share a little about your business and the challenges you want to address. Start a conversation with Smart Leading Solutions about a suitable direction.</p>
				</div>
				<a class="sfp-button" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">
					<?php esc_html_e( 'Start a Conversation', 'smart-leading-net' ); ?>
					<?php sln_founders_icon( 'arrow-right' ); ?>
				</a>
			</div>
		</div>
	</section>

</main>

	<?php
endwhile;

get_footer();
