<?php
/**
 * Theme footer.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sln_footer_phone_number = '+1 512 764 7877';
$sln_footer_phone_href   = 'tel:+15127647877';
$sln_footer_email        = 'admin@smartleading.com';

$sln_social_links = array(
	'facebook'  => 'https://www.facebook.com/smartleadingsolutionsllc',
	'linkedin'  => 'https://www.linkedin.com/company/smart-leading-solutions/',
	'instagram' => 'https://www.instagram.com/smartleading_solutions/',
);
?>
	</main><!-- #primary -->

	<footer id="colophon" class="site-footer footer">
		<div class="footer-content site-footer__inner container">
			<div class="footer-grid site-footer__grid">
				<div class="footer-about site-footer__col site-footer__col--about">
					<div class="footer-logo site-footer__logo">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
							<img src="<?php echo esc_url( SLN_THEME_URI . '/assets/images/Smart Leading white logo.webp' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="150" height="80" loading="eager" decoding="async">
						</a>
					</div>
					<div class="footer-title"><?php esc_html_e( 'About Company', 'smart-leading-net' ); ?></div>
					<p class="site-footer__text">
						<?php esc_html_e( 'Partner with Smart Leading Solutions for a personalized suite of services that drive measurable results and ensure your digital success', 'smart-leading-net' ); ?>
					</p>
				</div>

				<div class="footer-links-row">
					<div class="footer-services site-footer__col">
						<div class="footer-title"><?php esc_html_e( 'Our Services', 'smart-leading-net' ); ?></div>
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'footer_services_menu',
								'container'      => false,
								'menu_class'     => 'footer-menu site-footer__menu',
								'fallback_cb'    => false,
								'depth'          => 1,
							)
						);
						?>
					</div>

					<div class="footer-quick-links site-footer__col">
						<div class="footer-title"><?php esc_html_e( 'Quick Links', 'smart-leading-net' ); ?></div>
						<?php
						wp_nav_menu(
							array(
								'theme_location' => 'footer_quick_links_menu',
								'container'      => false,
								'menu_class'     => 'footer-menu site-footer__menu',
								'fallback_cb'    => false,
								'depth'          => 1,
							)
						);
						?>
					</div>
				</div>

				<div class="footer-contact site-footer__col site-footer__col--contact">
					<div class="footer-title"><?php esc_html_e( 'Contact Us', 'smart-leading-net' ); ?></div>

					<div class="site-footer__contact-list">
						<a class="site-footer__contact-item contact-item" href="<?php echo esc_url( $sln_footer_phone_href ); ?>">
							<span class="site-footer__contact-icon contact-icon" aria-hidden="true">
								<?php sln_inline_svg( 'call-us-icon-footer.svg', 'site-footer__svg', true ); ?>
							</span>
							<span class="site-footer__contact-content">
								<span class="site-footer__contact-label"><?php esc_html_e( 'Call Us', 'smart-leading-net' ); ?></span>
								<span class="site-footer__contact-value"><?php echo esc_html( $sln_footer_phone_number ); ?></span>
							</span>
						</a>

						<a class="site-footer__contact-item contact-item" href="mailto:<?php echo esc_attr( $sln_footer_email ); ?>">
							<span class="site-footer__contact-icon contact-icon" aria-hidden="true">
								<?php sln_inline_svg( 'envelope-icon-footer.svg', 'site-footer__svg', true ); ?>
							</span>
							<span class="site-footer__contact-content">
								<span class="site-footer__contact-label"><?php esc_html_e( 'E-Mail Address', 'smart-leading-net' ); ?></span>
								<span class="site-footer__contact-value"><?php echo esc_html( $sln_footer_email ); ?></span>
							</span>
						</a>
					</div>

					<div class="footer-social site-footer__social">
						<div class="footer-title footer-title--follow"><?php esc_html_e( 'Follow Us:', 'smart-leading-net' ); ?></div>
						<div class="site-footer__social-links">
							<a class="site-footer__social-link site-footer__social-link--facebook" href="<?php echo esc_url( $sln_social_links['facebook'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Facebook', 'smart-leading-net' ); ?>">
								<?php sln_inline_svg( 'facebook.svg', 'site-footer__social-svg' ); ?>
							</a>
							<a class="site-footer__social-link site-footer__social-link--linkedin" href="<?php echo esc_url( $sln_social_links['linkedin'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'LinkedIn', 'smart-leading-net' ); ?>">
								<?php sln_inline_svg( 'linkedin.svg', 'site-footer__social-svg' ); ?>
							</a>
							<a class="site-footer__social-link site-footer__social-link--instagram" href="<?php echo esc_url( $sln_social_links['instagram'] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Instagram', 'smart-leading-net' ); ?>">
								<?php sln_inline_svg( 'instagram.svg', 'site-footer__social-svg' ); ?>
							</a>
						</div>
					</div>
				</div>
			</div>

			<div class="footer-bottom bottom-footer">
				<p class="footer-bottom__text bottom-footer__text"><?php esc_html_e( '© 2025 - Smart Leading Solutions', 'smart-leading-net' ); ?></p>
			</div>
		</div>
	</footer>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
