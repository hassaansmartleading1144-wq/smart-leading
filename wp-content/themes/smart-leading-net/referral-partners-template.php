<?php
/**
 * Template Name: Referral Partners
 *
 * Referral partner program — recurring monthly commission for introducing
 * businesses to Smart Leading Solutions.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$rp_terms      = sln_referral_terms();
$rp_commission = sln_referral_commission_line();
$rp_payout     = $rp_terms['payout_days'];
$rp_services   = sln_home_services();
$rp_logo_base  = trailingslashit( SLN_THEME_URI ) . 'assets/images/partners/';
$rp_types      = sln_referral_partner_types();

$rp_steps = array(
	array(
		'icon'  => 'users',
		'title' => __( 'Apply to join', 'smart-leading-net' ),
		'text'  => __( 'Fill in the short form below. We call you to explain how the program works and answer your questions.', 'smart-leading-net' ),
	),
	array(
		'icon'  => 'reply',
		'title' => __( 'Introduce a business', 'smart-leading-net' ),
		'text'  => __( 'Tell us about a business owner who needs more customers, or connect us by email. We take it from there.', 'smart-leading-net' ),
	),
	array(
		'icon'  => 'check',
		'title' => __( 'They become a client', 'smart-leading-net' ),
		'text'  => __( 'We talk with them, find what is holding their business back and agree a plan that fits their budget.', 'smart-leading-net' ),
	),
	array(
		'icon'  => 'wallet',
		'title' => __( 'You get paid every month', 'smart-leading-net' ),
		/* translators: %s: number of days, e.g. 30–60 */
		'text'  => sprintf( __( 'Once the client has been with us for %s days, your commission is paid every month.', 'smart-leading-net' ), $rp_payout ),
	),
);

$rp_audience = array(
	array(
		'icon'  => 'code',
		'title' => __( 'Freelancers & consultants', 'smart-leading-net' ),
		'text'  => __( 'Web designers, developers, business coaches, bookkeepers and accountants who work with business owners every day.', 'smart-leading-net' ),
		'tags'  => array( __( 'Web designers', 'smart-leading-net' ), __( 'Coaches', 'smart-leading-net' ), __( 'Accountants', 'smart-leading-net' ) ),
	),
	array(
		'icon'  => 'star',
		'title' => __( 'Our clients', 'smart-leading-net' ),
		'text'  => __( 'Happy with your results? Recommend us to other business owners you know and earn from every one who joins.', 'smart-leading-net' ),
		'tags'  => array( __( 'Current clients', 'smart-leading-net' ), __( 'Past clients', 'smart-leading-net' ) ),
	),
	array(
		'icon'  => 'social',
		'title' => __( 'Anyone with a network', 'smart-leading-net' ),
		'text'  => __( 'You do not need to work in marketing. If you know a business that needs more leads, sales or appointments, you can refer it.', 'smart-leading-net' ),
		'tags'  => array( __( 'Business owners', 'smart-leading-net' ), __( 'Community groups', 'smart-leading-net' ) ),
	),
);

$rp_reasons = array(
	array(
		'icon'  => 'award',
		'title' => __( 'Google Partner agency', 'smart-leading-net' ),
		'text'  => __( 'Your contacts work with a Google Partner agency.', 'smart-leading-net' ),
	),
	array(
		'icon'  => 'calendar',
		'title' => __( 'Working since 2018', 'smart-leading-net' ),
		'text'  => __( 'Clients across the USA and Australia.', 'smart-leading-net' ),
	),
	array(
		'icon'  => 'chart',
		'title' => __( 'Clear lead reporting', 'smart-leading-net' ),
		'text'  => __( 'Clients see which marketing brings in leads and sales.', 'smart-leading-net' ),
	),
	array(
		'icon'  => 'shield',
		'title' => __( 'No fake results', 'smart-leading-net' ),
		'text'  => __( 'Honest reporting that protects your reputation.', 'smart-leading-net' ),
	),
);

$rp_faq = array(
	array(
		'q' => __( 'How much can I earn?', 'smart-leading-net' ),
		'a' => $rp_commission,
	),
	array(
		'q' => __( 'When do I get paid?', 'smart-leading-net' ),
		/* translators: %s: number of days */
		'a' => sprintf( __( 'Your commission starts once the client you referred has been with us for %s days. After that, you are paid every month.', 'smart-leading-net' ), $rp_payout ),
	),
	array(
		'q' => __( 'Do I need to be a Smart Leading client?', 'smart-leading-net' ),
		'a' => __( 'No. Freelancers, consultants, our clients and anyone who knows a business owner can apply.', 'smart-leading-net' ),
	),
	array(
		'q' => __( 'Do I have to sell anything?', 'smart-leading-net' ),
		'a' => __( 'No. You make the introduction. Our team talks with the business, explains our services and handles everything after that.', 'smart-leading-net' ),
	),
	array(
		'q' => __( 'Which services can I refer?', 'smart-leading-net' ),
		'a' => __( 'Any of our services: SEO and AI search, Google Ads, Meta Ads, websites, Shopify, email and SMS, conversion tracking, GoHighLevel CRM and AI agents.', 'smart-leading-net' ),
	),
);
?>
<?php get_header(); ?>

<div class="rp-page">

	<section class="rp-hero" aria-labelledby="rp-hero-heading">
		<div class="sls-container rp-hero__inner">
			<div class="rp-hero__content" data-rp-reveal>
				<p class="rp-eyebrow"><?php esc_html_e( 'Referral Partner Program', 'smart-leading-net' ); ?></p>
				<h1 id="rp-hero-heading" class="rp-hero__title">
					<?php esc_html_e( 'Know a business that needs more customers?', 'smart-leading-net' ); ?>
					<span class="rp-accent"><?php esc_html_e( 'Refer them. Earn every month.', 'smart-leading-net' ); ?></span>
				</h1>
				<p class="rp-hero__text"><?php esc_html_e( 'Introduce business owners to Smart Leading Solutions. When they become a client, you earn a recurring commission on their monthly fee, for helping a business you know grow.', 'smart-leading-net' ); ?></p>

				<ul class="rp-hero__points">
					<li><?php echo sln_home_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Monthly recurring commission', 'smart-leading-net' ); ?></li>
					<li><?php echo sln_home_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'No selling, just an introduction', 'smart-leading-net' ); ?></li>
					<li><?php echo sln_home_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Open to freelancers, clients and anyone', 'smart-leading-net' ); ?></li>
				</ul>

				<div class="rp-hero__actions">
					<?php
					sln_render_cta_button(
						array(
							'text'    => __( 'Become a Partner', 'smart-leading-net' ),
							'url'     => '#rp-apply',
							'variant' => 'primary',
						)
					);
					?>
					<a class="rp-link" href="#rp-how"><?php esc_html_e( 'See how it works', 'smart-leading-net' ); ?></a>
				</div>
			</div>

			<div class="rp-flow" aria-hidden="true" data-rp-reveal style="--rp-delay: 150ms">
				<div class="rp-flow__card rp-flow__card--one">
					<span class="rp-flow__icon"><?php echo sln_home_icon( 'reply' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span class="rp-flow__text"><strong><?php esc_html_e( 'You introduce', 'smart-leading-net' ); ?></strong><?php esc_html_e( 'a local business', 'smart-leading-net' ); ?></span>
				</div>
				<span class="rp-flow__line rp-flow__line--one"></span>
				<div class="rp-flow__card rp-flow__card--two">
					<span class="rp-flow__icon rp-flow__icon--green"><?php echo sln_home_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span class="rp-flow__text"><strong><?php esc_html_e( 'New client', 'smart-leading-net' ); ?></strong><?php esc_html_e( 'signed with SLS', 'smart-leading-net' ); ?></span>
				</div>
				<span class="rp-flow__line rp-flow__line--two"></span>
				<div class="rp-flow__card rp-flow__card--three">
					<div class="rp-flow__head">
						<span class="rp-flow__icon rp-flow__icon--orange"><?php echo sln_home_icon( 'wallet' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<span class="rp-flow__text"><strong><?php esc_html_e( 'Commission paid', 'smart-leading-net' ); ?></strong><?php esc_html_e( 'every month', 'smart-leading-net' ); ?></span>
					</div>
					<div class="rp-flow__bars">
						<?php foreach ( array( 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun' ) as $i => $month ) : ?>
							<span class="rp-flow__bar" style="--i: <?php echo esc_attr( (string) $i ); ?>"><span></span><em><?php echo esc_html( $month ); ?></em></span>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="rp-section rp-how" id="rp-how" aria-labelledby="rp-how-heading">
		<div class="sls-container">
			<header class="rp-head" data-rp-reveal>
				<p class="rp-eyebrow"><?php esc_html_e( 'How it works', 'smart-leading-net' ); ?></p>
				<h2 id="rp-how-heading" class="rp-head__title"><?php esc_html_e( 'Four simple steps to', 'smart-leading-net' ); ?> <span class="rp-accent"><?php esc_html_e( 'monthly income.', 'smart-leading-net' ); ?></span></h2>
				<p class="rp-head__text"><?php esc_html_e( 'You make the introduction. Our team does the selling, the work and the reporting.', 'smart-leading-net' ); ?></p>
			</header>

			<ol class="rp-steps">
				<?php foreach ( $rp_steps as $i => $step ) : ?>
					<li class="rp-step" data-rp-reveal style="--rp-delay: <?php echo esc_attr( (string) ( $i * 120 ) ); ?>ms">
						<span class="rp-step__badge" aria-hidden="true"><?php echo sln_home_icon( $step['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<div class="rp-step__card">
							<h3 class="rp-step__title"><?php echo esc_html( $step['title'] ); ?></h3>
							<p class="rp-step__text"><?php echo esc_html( $step['text'] ); ?></p>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>

			<div class="rp-earn" data-rp-reveal>
				<span class="rp-earn__icon" aria-hidden="true"><?php echo sln_home_icon( 'revenue' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<div class="rp-earn__body">
					<p class="rp-earn__label"><?php esc_html_e( 'What you earn', 'smart-leading-net' ); ?></p>
					<p class="rp-earn__text"><?php echo esc_html( $rp_commission ); ?></p>
				</div>
				<a class="rp-earn__btn" href="#rp-apply"><?php esc_html_e( 'Apply now', 'smart-leading-net' ); ?><?php echo sln_home_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
			</div>
		</div>
	</section>

	<section class="rp-section rp-who" aria-labelledby="rp-who-heading">
		<div class="sls-container">
			<header class="rp-head" data-rp-reveal>
				<p class="rp-eyebrow"><?php esc_html_e( 'Who can join', 'smart-leading-net' ); ?></p>
				<h2 id="rp-who-heading" class="rp-head__title"><?php esc_html_e( 'Made for people who', 'smart-leading-net' ); ?> <span class="rp-accent"><?php esc_html_e( 'know business owners.', 'smart-leading-net' ); ?></span></h2>
			</header>

			<div class="rp-who__grid">
				<?php foreach ( $rp_audience as $i => $item ) : ?>
					<article class="rp-who__card" data-rp-reveal style="--rp-delay: <?php echo esc_attr( (string) ( $i * 110 ) ); ?>ms">
						<span class="rp-who__icon" aria-hidden="true"><?php echo sln_home_icon( $item['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<h3 class="rp-who__title"><?php echo esc_html( $item['title'] ); ?></h3>
						<p class="rp-who__text"><?php echo esc_html( $item['text'] ); ?></p>
						<ul class="rp-who__tags">
							<?php foreach ( $item['tags'] as $tag ) : ?>
								<li><?php echo esc_html( $tag ); ?></li>
							<?php endforeach; ?>
						</ul>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="rp-section rp-services" aria-labelledby="rp-services-heading">
		<div class="sls-container">
			<header class="rp-head" data-rp-reveal>
				<p class="rp-eyebrow"><?php esc_html_e( 'What you can refer', 'smart-leading-net' ); ?></p>
				<h2 id="rp-services-heading" class="rp-head__title"><?php esc_html_e( 'One partner.', 'smart-leading-net' ); ?> <span class="rp-accent"><?php esc_html_e( 'Nine ways to help.', 'smart-leading-net' ); ?></span></h2>
				<p class="rp-head__text"><?php esc_html_e( 'Whatever the business needs, from more website visitors to a better sales process, you can refer it to us.', 'smart-leading-net' ); ?></p>
			</header>

			<ul class="rp-services__grid">
				<?php foreach ( $rp_services as $i => $service ) : ?>
					<li class="rp-services__item" data-rp-reveal style="--rp-delay: <?php echo esc_attr( (string) ( ( $i % 3 ) * 80 ) ); ?>ms">
						<?php if ( ! empty( $service['logo'] ) ) : ?>
							<span class="rp-services__logo<?php echo empty( $service['logo']['square'] ) ? ' rp-services__logo--wide' : ''; ?>">
								<img src="<?php echo esc_url( $rp_logo_base . $service['logo']['file'] ); ?>" alt="" width="<?php echo esc_attr( (string) $service['logo']['w'] ); ?>" height="<?php echo esc_attr( (string) $service['logo']['h'] ); ?>" loading="lazy" decoding="async">
							</span>
						<?php else : ?>
							<span class="rp-services__badge" style="--badge-from: <?php echo esc_attr( $service['badge']['from'] ); ?>; --badge-to: <?php echo esc_attr( $service['badge']['to'] ); ?>;" aria-hidden="true">
								<?php echo sln_home_icon( $service['badge']['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</span>
						<?php endif; ?>
						<span class="rp-services__name"><?php echo esc_html( $service['title'] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>

	<section class="rp-section rp-trust" aria-labelledby="rp-trust-heading">
		<div class="sls-container">
			<div class="rp-trust__panel" data-rp-reveal>
				<div class="rp-trust__intro">
					<p class="rp-eyebrow rp-eyebrow--light"><?php esc_html_e( 'Why partners refer us', 'smart-leading-net' ); ?></p>
					<h2 id="rp-trust-heading" class="rp-trust__title"><?php esc_html_e( 'Your name is on the introduction. We look after it.', 'smart-leading-net' ); ?></h2>
				</div>
				<ul class="rp-trust__grid">
					<?php foreach ( $rp_reasons as $reason ) : ?>
						<li class="rp-trust__item">
							<span class="rp-trust__icon" aria-hidden="true"><?php echo sln_home_icon( $reason['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<span>
								<strong><?php echo esc_html( $reason['title'] ); ?></strong>
								<?php echo esc_html( $reason['text'] ); ?>
							</span>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</section>

	<section class="rp-section rp-apply" id="rp-apply" aria-labelledby="rp-apply-heading">
		<div class="sls-container rp-apply__inner">
			<div class="rp-faq" data-rp-reveal>
				<p class="rp-eyebrow"><?php esc_html_e( 'Questions', 'smart-leading-net' ); ?></p>
				<h2 class="rp-head__title rp-faq__title"><?php esc_html_e( 'Good to know', 'smart-leading-net' ); ?></h2>
				<div class="rp-faq__list">
					<?php foreach ( $rp_faq as $i => $item ) : ?>
						<details class="rp-faq__item"<?php echo 0 === $i ? ' open' : ''; ?>>
							<summary class="rp-faq__q"><?php echo esc_html( $item['q'] ); ?><span class="rp-faq__plus" aria-hidden="true"></span></summary>
							<p class="rp-faq__a"><?php echo esc_html( $item['a'] ); ?></p>
						</details>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="rp-form-card" data-rp-reveal style="--rp-delay: 120ms">
				<h2 id="rp-apply-heading" class="rp-form-card__title"><?php esc_html_e( 'Become a referral partner', 'smart-leading-net' ); ?></h2>
				<p class="rp-form-card__text"><?php esc_html_e( 'Tell us a little about yourself. We will contact you to explain the program and get you started.', 'smart-leading-net' ); ?></p>

				<form class="rp-form" id="rp-form" action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" novalidate>
					<div class="rp-form__grid">
						<div class="rp-field">
							<label class="rp-label" for="rp-name"><?php esc_html_e( 'Full Name', 'smart-leading-net' ); ?> <span aria-hidden="true">*</span></label>
							<input class="rp-input" type="text" id="rp-name" name="rp_name" autocomplete="name" placeholder="<?php esc_attr_e( 'Your full name', 'smart-leading-net' ); ?>" required>
						</div>
						<div class="rp-field">
							<label class="rp-label" for="rp-email"><?php esc_html_e( 'Email', 'smart-leading-net' ); ?> <span aria-hidden="true">*</span></label>
							<input class="rp-input" type="email" id="rp-email" name="rp_email" autocomplete="email" placeholder="<?php esc_attr_e( 'you@example.com', 'smart-leading-net' ); ?>" required>
						</div>
						<div class="rp-field">
							<label class="rp-label" for="rp-phone"><?php esc_html_e( 'Phone', 'smart-leading-net' ); ?> <span aria-hidden="true">*</span></label>
							<input class="rp-input" type="tel" id="rp-phone" name="rp_phone" autocomplete="tel" placeholder="<?php esc_attr_e( 'Phone number', 'smart-leading-net' ); ?>" required>
						</div>
						<div class="rp-field">
							<label class="rp-label" for="rp-website"><?php esc_html_e( 'Website or LinkedIn', 'smart-leading-net' ); ?> <span class="rp-optional"><?php esc_html_e( '(optional)', 'smart-leading-net' ); ?></span></label>
							<input class="rp-input" type="text" id="rp-website" name="rp_website" autocomplete="url" placeholder="<?php esc_attr_e( 'https://', 'smart-leading-net' ); ?>">
						</div>
					</div>

					<fieldset class="rp-field rp-types">
						<legend class="rp-label"><?php esc_html_e( 'I am a…', 'smart-leading-net' ); ?> <span aria-hidden="true">*</span></legend>
						<div class="rp-types__options">
							<?php foreach ( $rp_types as $value => $label ) : ?>
								<label class="rp-type">
									<input type="radio" name="rp_type" value="<?php echo esc_attr( $value ); ?>" required>
									<span><?php echo esc_html( $label ); ?></span>
								</label>
							<?php endforeach; ?>
						</div>
					</fieldset>

					<div class="rp-field">
						<label class="rp-label" for="rp-message"><?php esc_html_e( 'Who would you like to refer?', 'smart-leading-net' ); ?> <span class="rp-optional"><?php esc_html_e( '(optional)', 'smart-leading-net' ); ?></span></label>
						<textarea class="rp-input rp-textarea" id="rp-message" name="rp_message" rows="3" placeholder="<?php esc_attr_e( 'For example: a plumbing company in Austin that needs more calls', 'smart-leading-net' ); ?>"></textarea>
					</div>

					<button type="submit" class="rp-submit">
						<span class="rp-submit__text"><?php esc_html_e( 'Apply to Become a Partner', 'smart-leading-net' ); ?></span>
						<?php echo sln_home_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</button>

					<p class="rp-form__message" role="alert" aria-live="assertive" hidden></p>
				</form>
			</div>
		</div>
	</section>

</div>

<?php
get_footer();
