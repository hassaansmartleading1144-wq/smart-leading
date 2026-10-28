<?php
/**
 * Custom Gutenberg blocks for the AI SEO Services page.
 * Attributes are stored in block markup (not post meta).
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register AI SEO custom blocks.
 */
function sln_ai_seo_register_blocks() {
	$script_path = SLN_THEME_DIR . '/assets/js/ai-seo-blocks.js';
	$script_ver  = file_exists( $script_path ) ? (string) filemtime( $script_path ) : SLN_THEME_VERSION;

	wp_register_script(
		'sln-ai-seo-blocks',
		SLN_THEME_URI . '/assets/js/ai-seo-blocks.js',
		array(
			'wp-blocks',
			'wp-element',
			'wp-block-editor',
			'wp-components',
			'wp-i18n',
			'wp-server-side-render',
		),
		$script_ver,
		true
	);

	register_block_type(
		'sln/ai-seo-mini-lead',
		array(
			'api_version'     => 2,
			'editor_script'   => 'sln-ai-seo-blocks',
			'render_callback' => 'sln_render_ai_seo_mini_lead_block',
			'attributes'      => array(
				'placeholder'   => array(
					'type'    => 'string',
					'default' => 'Enter your website',
				),
				'buttonLabel'   => array(
					'type'    => 'string',
					'default' => 'Send Me a Proposal',
				),
				'variant'       => array(
					'type'    => 'string',
					'default' => 'default',
				),
				'ariaLabel'     => array(
					'type'    => 'string',
					'default' => 'Start a proposal request',
				),
				'inputId'       => array(
					'type'    => 'string',
					'default' => 'ai-mini-website',
				),
				'note'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'showArrow'     => array(
					'type'    => 'boolean',
					'default' => true,
				),
			),
		)
	);

	register_block_type(
		'sln/ai-seo-enquiry-form',
		array(
			'api_version'     => 2,
			'editor_script'   => 'sln-ai-seo-blocks',
			'render_callback' => 'sln_render_ai_seo_enquiry_form_block',
			'attributes'      => array(
				'nameLabel'      => array(
					'type'    => 'string',
					'default' => 'Name',
				),
				'emailLabel'     => array(
					'type'    => 'string',
					'default' => 'Business email',
				),
				'websiteLabel'   => array(
					'type'    => 'string',
					'default' => 'Website',
				),
				'websiteHint'    => array(
					'type'    => 'string',
					'default' => 'For example, yourcompany.com',
				),
				'goalLabel'      => array(
					'type'    => 'string',
					'default' => 'What would you like to understand about AI search?',
				),
				'buttonLabel'    => array(
					'type'    => 'string',
					'default' => 'Send My Enquiry',
				),
				'privacyText'    => array(
					'type'    => 'string',
					'default' => 'How we handle your information is described in our privacy policy.',
				),
				'privacyUrl'     => array(
					'type'    => 'string',
					'default' => '/privacy-policy/',
				),
				'privacyLinkLabel' => array(
					'type'    => 'string',
					'default' => 'Privacy policy',
				),
			),
		)
	);
}
add_action( 'init', 'sln_ai_seo_register_blocks' );

/**
 * Arrow icon markup shared by CTA buttons.
 *
 * @return string
 */
function sln_ai_seo_btn_arrow_svg() {
	return '<span class="sls-btn__arrow" aria-hidden="true"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="M13 6l6 6-6 6"></path></svg></span>';
}

/**
 * Render mini lead capture form.
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function sln_render_ai_seo_mini_lead_block( $attributes ) {
	$placeholder = isset( $attributes['placeholder'] ) ? $attributes['placeholder'] : '';
	$button      = isset( $attributes['buttonLabel'] ) ? $attributes['buttonLabel'] : '';
	$variant     = isset( $attributes['variant'] ) ? $attributes['variant'] : 'default';
	$aria        = isset( $attributes['ariaLabel'] ) ? $attributes['ariaLabel'] : '';
	$input_id    = isset( $attributes['inputId'] ) ? $attributes['inputId'] : 'ai-mini-website';
	$note        = isset( $attributes['note'] ) ? $attributes['note'] : '';
	$show_arrow  = ! empty( $attributes['showArrow'] );

	$btn_class = 'sls-btn sls-btn--primary';
	if ( 'onblue' === $variant ) {
		$btn_class .= ' sls-btn--onblue';
	} elseif ( $show_arrow ) {
		$btn_class .= ' sls-btn--arrow';
	}

	ob_start();
	?>
	<form class="sls-mini" aria-label="<?php echo esc_attr( $aria ); ?>">
		<label class="sls-sr" for="<?php echo esc_attr( $input_id ); ?>"><?php esc_html_e( 'Your website', 'smart-leading-net' ); ?></label>
		<input class="sls-input" id="<?php echo esc_attr( $input_id ); ?>" name="website" type="text" inputmode="url" autocomplete="url" spellcheck="false" placeholder="<?php echo esc_attr( $placeholder ); ?>">
		<button type="submit" class="<?php echo esc_attr( $btn_class ); ?>">
			<?php echo esc_html( $button ); ?>
			<?php
			if ( $show_arrow && 'onblue' !== $variant ) {
				echo sln_ai_seo_btn_arrow_svg(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
		</button>
	</form>
	<?php if ( $note ) : ?>
		<p class="sls-mini__note"><?php echo esc_html( $note ); ?></p>
	<?php endif; ?>
	<?php
	return (string) ob_get_clean();
}

/**
 * Render enquiry form.
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function sln_render_ai_seo_enquiry_form_block( $attributes ) {
	$name_label    = $attributes['nameLabel'] ?? 'Name';
	$email_label   = $attributes['emailLabel'] ?? 'Business email';
	$website_label = $attributes['websiteLabel'] ?? 'Website';
	$website_hint  = $attributes['websiteHint'] ?? '';
	$goal_label    = $attributes['goalLabel'] ?? '';
	$button_label  = $attributes['buttonLabel'] ?? 'Send My Enquiry';
	$privacy_text  = $attributes['privacyText'] ?? '';
	$privacy_url   = $attributes['privacyUrl'] ?? '';
	$privacy_link  = $attributes['privacyLinkLabel'] ?? 'Privacy policy';

	ob_start();
	?>
	<form class="sls-form" data-ai-seo-enquiry="1" aria-labelledby="ai-search-enquiry-h" novalidate>
		<div class="sls-field">
			<label for="ai-enq-name"><?php echo esc_html( $name_label ); ?><span class="req"><?php esc_html_e( '(required)', 'smart-leading-net' ); ?></span></label>
			<input class="sls-input" id="ai-enq-name" name="name" type="text" autocomplete="name" aria-required="true">
			<p class="sls-err" id="ai-enq-name-error" hidden></p>
		</div>
		<div class="sls-field">
			<label for="ai-enq-email"><?php echo esc_html( $email_label ); ?><span class="req"><?php esc_html_e( '(required)', 'smart-leading-net' ); ?></span></label>
			<input class="sls-input" id="ai-enq-email" name="email" type="text" inputmode="email" autocomplete="email" spellcheck="false" aria-required="true">
			<p class="sls-err" id="ai-enq-email-error" hidden></p>
		</div>
		<div class="sls-field">
			<label for="ai-enq-website"><?php echo esc_html( $website_label ); ?><span class="req"><?php esc_html_e( '(optional)', 'smart-leading-net' ); ?></span></label>
			<?php if ( $website_hint ) : ?>
				<p class="sls-hint" id="ai-enq-website-hint"><?php echo esc_html( $website_hint ); ?></p>
			<?php endif; ?>
			<input class="sls-input" id="ai-enq-website" name="website" type="text" inputmode="url" autocomplete="url" spellcheck="false" aria-describedby="ai-enq-website-hint">
			<p class="sls-err" id="ai-enq-website-error" hidden></p>
		</div>
		<div class="sls-field">
			<label for="ai-enq-goal"><?php echo esc_html( $goal_label ); ?><span class="req"><?php esc_html_e( '(required)', 'smart-leading-net' ); ?></span></label>
			<textarea class="sls-input" id="ai-enq-goal" name="goal" rows="5" aria-required="true"></textarea>
			<p class="sls-err" id="ai-enq-goal-error" hidden></p>
		</div>
		<div class="sls-form__foot">
			<button type="submit" class="sls-btn sls-btn--primary sls-btn--arrow">
				<?php echo esc_html( $button_label ); ?>
				<?php echo sln_ai_seo_btn_arrow_svg(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</button>
			<div role="status" aria-live="polite">
				<p class="sls-status" data-ai-seo-status hidden></p>
			</div>
			<?php if ( $privacy_text ) : ?>
				<p class="sls-privacy">
					<?php echo esc_html( $privacy_text ); ?>
					<?php if ( $privacy_url ) : ?>
						<a href="<?php echo esc_url( $privacy_url ); ?>"><?php echo esc_html( $privacy_link ); ?></a>
					<?php endif; ?>
				</p>
			<?php endif; ?>
		</div>
	</form>
	<?php
	return (string) ob_get_clean();
}
