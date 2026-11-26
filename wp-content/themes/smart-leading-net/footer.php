<?php
/**
 * Theme footer.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sln_footer_phone_number = '512 764 7877';
$sln_footer_phone_href   = 'tel:+15127647877';
$sln_footer_email        = 'business@smartleading.net';

$sln_footer_columns = array(
	array(
		'title' => 'SEO Services',
		'links' => array(
			array( 'SEO Services', '/seo-services/' ),
			array( 'Local SEO', '/locale-seo/' ),
			array( 'AI SEO Services', '/ai-seo-services/' ),
			array( 'Answer Engine Optimization', '/digital-marketing-services/answer-engine-optimization-aeo/' ),
			array( 'Technical SEO', '/seo-services/technical-seo/' ),
			array( 'SEO Audit', '/seo-services/seo-audit/' ),
			array( 'Ecommerce SEO', '/seo-services/ecommerce-seo/' ),
			array( 'International SEO', '/seo-services/international-seo/' ),
			array( 'Link Building', '/seo-services/link-building/' ),
		),
	),
	array(
		'title' => 'PPC & Web',
		'links' => array(
			array( 'PPC Management', '/ppc/' ),
			array( 'Google Ads Management', '/ppc/google-ads-management/' ),
			array( 'Local Service Ads', '/ppc/local-service-ads/' ),
			array( 'Facebook & Instagram Ads', '/ppc/facebook-ads-management/' ),
			array( 'Ecommerce PPC', '/ppc/ecommerce-ppc/' ),
			array( 'Free PPC Audit', '/ppc/free-ppc-audit/' ),
			array( 'Web Development', '/web-development-services/' ),
		),
	),
	array(
		'title' => 'Company',
		'links' => array(
			array( 'About Us', '/about-us/' ),
			array( 'Our Founders', '/founders-behind/' ),
			array( 'Case Studies', '/case-studies/' ),
			array( 'Portfolio', '/portfolio/' ),
			array( 'Referral Partners', '/referral-partners/' ),
			array( 'Contact Us', '/contact-us/' ),
		),
	),
);

$sln_social_links = array(
	'facebook'  => 'https://www.facebook.com/smartleadingsolutionsllc',
	'linkedin'  => 'https://www.linkedin.com/company/smart-leading-solutions/',
	'instagram' => 'https://www.instagram.com/smartleading_solutions/',
);
?>
	</main><!-- #primary -->

	<footer id="colophon" class="site-footer footer">
		<div class="footer-content site-footer__inner container">
			<div class="footer-kpis">
				<div class="footer-kpis__cta">
					<p class="footer-kpis__cta-title"><?php esc_html_e( 'Spending on ads? See what is working.', 'smart-leading-net' ); ?></p>
					<div class="footer-kpis__buttons">
						<a class="footer-btn footer-btn--audit" href="<?php echo esc_url( home_url( '/ppc/free-ppc-audit/' ) ); ?>">
							<?php esc_html_e( 'Get a Free PPC Audit', 'smart-leading-net' ); ?>
							<?php echo sln_home_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</a>
						<a class="footer-btn footer-btn--ghost" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">
							<?php esc_html_e( 'Free Growth Proposal', 'smart-leading-net' ); ?>
						</a>
					</div>
					<p class="footer-kpis__note"><?php esc_html_e( 'PDF report, video walkthrough and a call, within 2–3 business days.', 'smart-leading-net' ); ?></p>
				</div>
			</div>
			<div class="footer-grid site-footer__grid">
				<div class="footer-about site-footer__col site-footer__col--about">
					<div class="footer-logo site-footer__logo">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
							<img src="<?php echo esc_url( SLN_THEME_URI . '/assets/images/Smart Leading white logo.webp' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="150" height="80" loading="eager" decoding="async">
						</a>
					</div>
					<div class="footer-title"><?php esc_html_e( '', 'smart-leading-net' ); ?></div>
					<p class="site-footer__text">
						<?php esc_html_e( 'You should know which parts of your marketing bring in business. At Smart Leading Solutions, we connect your SEO, advertising, and website data to the outcomes you care about, from qualified inquiries to completed sales. Clear reporting helps you see where your next marketing dollar can make the biggest difference.', 'smart-leading-net' ); ?>
					</p>
				</div>

				<div class="footer-links-row">
					<?php foreach ( $sln_footer_columns as $column ) : ?>
						<nav class="site-footer__col footer-links-col" aria-label="<?php echo esc_attr( $column['title'] ); ?>">
							<div class="footer-title"><?php echo esc_html( $column['title'] ); ?></div>
							<ul class="footer-menu site-footer__menu">
								<?php foreach ( $column['links'] as $link ) : ?>
									<li><a href="<?php echo esc_url( home_url( $link[1] ) ); ?>"><?php echo esc_html( $link[0] ); ?></a></li>
								<?php endforeach; ?>
							</ul>
						</nav>
					<?php endforeach; ?>
				</div>

				<div class="footer-contact site-footer__col site-footer__col--contact">
					<div class="footer-title"><?php esc_html_e( 'Contact Us', 'smart-leading-net' ); ?></div>

					<div class="site-footer__contact-list">
						<a class="site-footer__contact-item contact-item" href="<?php echo esc_url( $sln_footer_phone_href ); ?>">
							<span class="site-footer__contact-icon contact-icon" aria-hidden="true">
								<?php sln_inline_svg( 'call-us-icon-footer.svg', 'site-footer__svg', true ); ?>
							</span>
							<span class="site-footer__contact-content">
								<span class="site-footer__contact-label"><?php esc_html_e( '', 'smart-leading-net' ); ?></span>
								<span class="site-footer__contact-value"><?php echo esc_html( $sln_footer_phone_number ); ?></span>
							</span>
						</a>

						<a class="site-footer__contact-item contact-item" href="mailto:<?php echo esc_attr( $sln_footer_email ); ?>">
							<span class="site-footer__contact-icon contact-icon" aria-hidden="true">
								<?php sln_inline_svg( 'envelope-icon-footer.svg', 'site-footer__svg', true ); ?>
							</span>
							<span class="site-footer__contact-content">
								<span class="site-footer__contact-label"><?php esc_html_e( '', 'smart-leading-net' ); ?></span>
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
				<p class="footer-bottom__text bottom-footer__text">
					<?php
					/* translators: %s: current year */
					echo esc_html( sprintf( __( '© %s Smart Leading Solutions LLC. All rights reserved.', 'smart-leading-net' ), wp_date( 'Y' ) ) );
					?>
				</p>

				<address class="footer-bottom__address">
					<?php echo sln_home_icon( 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php esc_html_e( '5900 Balcones Drive, Suite 58601, Austin, TX 78731, USA', 'smart-leading-net' ); ?>
				</address>

				<nav class="footer-bottom__legal" aria-label="<?php esc_attr_e( 'Legal', 'smart-leading-net' ); ?>">
					<a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'smart-leading-net' ); ?></a>
					<a href="<?php echo esc_url( home_url( '/terms-of-service/' ) ); ?>"><?php esc_html_e( 'Terms of Service', 'smart-leading-net' ); ?></a>
					<a class="footer-bottom__top" href="#page">
						<?php esc_html_e( 'Back to top', 'smart-leading-net' ); ?>
						<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
					</a>
				</nav>
			</div>
		</div>
	</footer>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
