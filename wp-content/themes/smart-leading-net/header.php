<?php
/**
 * Theme header.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sln_google_partner_url = 'https://www.google.com/partners/agency?id=7238450490';
$sln_phone_number       = '512 764 7877';
$sln_phone_href         = 'tel:+15127647877';
$sln_email              = 'business@smartleading.net';

$sln_social_links = array(
	'instagram' => 'https://www.instagram.com/smartleading_solutions/',
	'facebook'  => 'https://www.facebook.com/smartleadingsolutionsllc',
	'linkedin'  => 'https://www.linkedin.com/company/smart-leading-solutions/',
);
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/webp" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon-sl.webp">
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon-sl.webp">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site site-wrapper">
	<a class="visually-hidden-focusable skip-link" href="#primary"><?php esc_html_e( 'Skip to content', 'smart-leading-net' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="header-top">
			<div class="container">
				<div class="header-top__inner">
					<?php
					// The audit link opens Contact Us until a general Free Audit page exists.
					$sln_topbar_audit_url   = home_url( '/contact-us/' );
					$sln_topbar_partner_url = home_url( '/referral-partners/' );
					?>
					<div class="header-top__promo">
						<span class="header-top__promo-text d-none d-xl-inline"><?php esc_html_e( 'Not sure what is holding your business back?', 'smart-leading-net' ); ?></span>
						<a class="header-top__audit" href="<?php echo esc_url( $sln_topbar_audit_url ); ?>">
							<span class="header-top__pill-dot" aria-hidden="true"></span>
							<span class="d-none d-sm-inline"><?php esc_html_e( 'Get my free audit', 'smart-leading-net' ); ?></span>
							<span class="d-sm-none"><?php esc_html_e( 'Free audit', 'smart-leading-net' ); ?></span>
							<svg class="header-top__arrow" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
						</a>
					</div>

					<div class="header-top__contact">
						<a class="header-top__link d-none d-md-inline-flex" href="https://leads.smartleading.net/" target="_blank" rel="noopener noreferrer">
							<svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M3 20h18"/><path d="M6 16v-4"/><path d="M11 16V8"/><path d="M16 16v-6"/></svg>
							<?php esc_html_e( 'Lead Analytics', 'smart-leading-net' ); ?>
							<span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'smart-leading-net' ); ?></span>
						</a>
						<a class="header-top__link" href="<?php echo esc_url( $sln_topbar_partner_url ); ?>">
							<svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
							<?php esc_html_e( 'Become a Partner', 'smart-leading-net' ); ?>
						</a>
						<span class="header-top__divider d-none d-lg-inline-block" aria-hidden="true"></span>
						<div class="header-top__social d-none d-lg-flex">
							<a class="header-top__social-link" href="<?php echo esc_url( $sln_social_links['instagram'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Instagram', 'smart-leading-net' ); ?>">
								<?php sln_inline_svg( 'instagram.svg', 'header-top__svg' ); ?>
							</a>
							<a class="header-top__social-link" href="<?php echo esc_url( $sln_social_links['facebook'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Facebook', 'smart-leading-net' ); ?>">
								<?php sln_inline_svg( 'facebook.svg', 'header-top__svg' ); ?>
							</a>
							<a class="header-top__social-link" href="<?php echo esc_url( $sln_social_links['linkedin'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'LinkedIn', 'smart-leading-net' ); ?>">
								<?php sln_inline_svg( 'linkedin.svg', 'header-top__svg' ); ?>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="header-main">
			<div class="container">
				<div class="header-main__logo logo">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
						<?php sln_inline_svg( 'smart-leading-logo.svg', 'header-main__logo-svg', true ); ?>
					</a>
				</div>

				<div class="menu-area d-none d-lg-flex">
					<nav class="main-navigation header-main__nav" aria-label="<?php esc_attr_e( 'Primary Navigation', 'smart-leading-net' ); ?>">
						<?php
						wp_nav_menu(
							array(
								'theme_location'   => 'primary',
								'container'        => false,
								'menu_class'       => 'header-main__menu',
								'fallback_cb'      => false,
								'depth'            => 0,
								'walker'           => new SLN_Bootstrap_Nav_Walker(),
								'sln_menu_context' => 'desktop',
							)
						);
						?>
					</nav>
				</div>

				<div class="header-main__actions">
						<a class="header-main__partner" href="<?php echo esc_url( $sln_google_partner_url ); ?>" target="_blank" rel="noopener noreferrer">
							<svg class="header-main__partner-g" viewBox="0 0 48 48" width="26" height="26" aria-hidden="true" focusable="false"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/></svg>
							<span class="header-main__partner-text">
								<span class="header-main__partner-brand"><?php esc_html_e( 'Google', 'smart-leading-net' ); ?></span>
								<span class="header-main__partner-label"><?php esc_html_e( 'Partner', 'smart-leading-net' ); ?></span>
							</span>
							<span class="screen-reader-text"><?php esc_html_e( '(view our Google Partner profile, opens in a new tab)', 'smart-leading-net' ); ?></span>
						</a>

						<div class="header-main__ctas d-none d-lg-flex">
							<a class="header-main__cta header-main__cta--expert" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">
								<span class="header-main__cta-icon" aria-hidden="true">
									<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" focusable="false"><path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2a1.003 1.003 0 011.01-.24c1.12.37 2.33.57 3.57.57a1 1 0 011 1V20a1 1 0 01-1 1C10.07 21 3 13.93 3 5a1 1 0 011-1h3.5a1 1 0 011 1c0 1.24.2 2.45.57 3.57a1.003 1.003 0 01-.25 1.01l-2.2 2.21z"/></svg>
								</span>
								<?php esc_html_e( 'Talk to an Expert', 'smart-leading-net' ); ?>
							</a>
						</div>

						<button
						class="header-main__toggle d-lg-none"
						type="button"
						data-bs-toggle="offcanvas"
						data-bs-target="#slnMobileMenu"
						aria-controls="slnMobileMenu"
						aria-label="<?php esc_attr_e( 'Toggle navigation', 'smart-leading-net' ); ?>"
					>
						<span class="header-main__toggle-bar"></span>
						<span class="header-main__toggle-bar"></span>
						<span class="header-main__toggle-bar"></span>
					</button>
				</div>
			</div>
		</div>

		<div class="offcanvas offcanvas-end sln-mobile-menu" tabindex="-1" id="slnMobileMenu" aria-labelledby="slnMobileMenuLabel">
			<div class="offcanvas-header">
				<span class="offcanvas-title visually-hidden" id="slnMobileMenuLabel"><?php esc_html_e( 'Menu', 'smart-leading-net' ); ?></span>
				<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="<?php esc_attr_e( 'Close', 'smart-leading-net' ); ?>"></button>
			</div>
			<div class="offcanvas-body">
				<nav aria-label="<?php esc_attr_e( 'Mobile Navigation', 'smart-leading-net' ); ?>">
					<?php
					wp_nav_menu(
						array(
							'theme_location'   => 'primary',
							'container'        => false,
							'menu_class'       => 'navbar-nav sln-mobile-menu__list',
							'fallback_cb'      => false,
							'depth'            => 0,
							'walker'           => new SLN_Bootstrap_Nav_Walker(),
							'sln_menu_context' => 'mobile',
						)
					);
					?>
				</nav>

				<div class="sln-mobile-menu__ctas">
					<a class="header-main__cta header-main__cta--expert" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">
						<span class="header-main__cta-icon" aria-hidden="true">
							<svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor" focusable="false"><path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2a1.003 1.003 0 011.01-.24c1.12.37 2.33.57 3.57.57a1 1 0 011 1V20a1 1 0 01-1 1C10.07 21 3 13.93 3 5a1 1 0 011-1h3.5a1 1 0 011 1c0 1.24.2 2.45.57 3.57a1.003 1.003 0 01-.25 1.01l-2.2 2.21z"/></svg>
						</span>
						<?php esc_html_e( 'Talk to an Expert', 'smart-leading-net' ); ?>
					</a>
					<a class="header-main__cta header-main__cta--analytics" href="https://leads.smartleading.net/" target="_blank" rel="noopener noreferrer">
						<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M3 20h18"/><path d="M6 16v-4"/><path d="M11 16V8"/><path d="M16 16v-6"/><path d="m5 8 5-4 4 3 5-4"/></svg>
						<?php esc_html_e( 'Lead Analytics', 'smart-leading-net' ); ?>
						<span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'smart-leading-net' ); ?></span>
					</a>
				</div>
			</div>
		</div>
	</header>

	<main id="primary" class="site-main">
