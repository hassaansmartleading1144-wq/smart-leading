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
$sln_phone_number       = '+1 512 764 7877';
$sln_phone_href         = 'tel:+15127647877';
$sln_email              = 'admin@smartleading.net';

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
					<div class="header-top__location d-none d-lg-flex">
						<span class="header-top__icon" aria-hidden="true">
							<?php sln_inline_svg( 'address-map.svg', 'header-top__svg' ); ?>
						</span>
						<span class="header-top__text"><?php esc_html_e( 'USA, Australia, UK, Norway, Finland, Pakistan', 'smart-leading-net' ); ?></span>
					</div>

					<div class="header-top__contact">
						<a class="header-top__email d-lg-none" href="mailto:<?php echo esc_attr( $sln_email ); ?>">
							<?php
							/* translators: %s: email address */
							printf( esc_html__( 'Email: %s', 'smart-leading-net' ), esc_html( $sln_email ) );
							?>
						</a>

						<div class="header-top__right d-none d-lg-flex">
							<a class="header-top__email" href="mailto:<?php echo esc_attr( $sln_email ); ?>">
								<?php
								/* translators: %s: email address */
								printf( esc_html__( 'Email: %s', 'smart-leading-net' ), esc_html( $sln_email ) );
								?>
							</a>
							<span class="header-top__divider" aria-hidden="true"></span>
							<div class="header-top__social">
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

						<div class="header-top__social d-lg-none">
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
							<img src="<?php echo esc_url( SLN_THEME_URI . '/assets/images/Google-Partner.webp' ); ?>" alt="<?php esc_attr_e( 'Google Partner', 'smart-leading-net' ); ?>" width="120" height="40" loading="lazy" decoding="async">
						</a>

						<span class="header-main__divider d-none d-lg-inline-block" aria-hidden="true"></span>

						<a class="header-main__phone d-none d-lg-inline-flex" href="<?php echo esc_url( $sln_phone_href ); ?>">
							<span class="header-main__phone-icon" aria-hidden="true">
								<svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M6.62 10.79a15.053 15.053 0 006.59 6.59l2.2-2.2a1.003 1.003 0 011.01-.24c1.12.37 2.33.57 3.57.57a1 1 0 011 1V20a1 1 0 01-1 1C10.07 21 3 13.93 3 5a1 1 0 011-1h3.5a1 1 0 011 1c0 1.24.2 2.45.57 3.57a1.003 1.003 0 01-.25 1.01l-2.2 2.21z" fill="currentColor"/>
								</svg>
							</span>
							<span class="header-main__phone-text"><?php echo esc_html( $sln_phone_number ); ?></span>
						</a>

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
				<h2 class="offcanvas-title visually-hidden" id="slnMobileMenuLabel"><?php esc_html_e( 'Menu', 'smart-leading-net' ); ?></h2>
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
			</div>
		</div>
	</header>

	<main id="primary" class="site-main">
