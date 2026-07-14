<?php
/**
 * SEO Services page — admin meta boxes.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Debug flag for temporary SEO Services admin notices.
 * Set in wp-config.php: define( 'SLN_SEO_SVC_ADMIN_DEBUG', true );
 */
if ( ! defined( 'SLN_SEO_SVC_ADMIN_DEBUG' ) ) {
	define( 'SLN_SEO_SVC_ADMIN_DEBUG', false );
}

/** @var int */
$GLOBALS['sln_seo_services_registered_meta_boxes'] = 0;

/**
 * Register SEO Services meta boxes.
 */
function sln_seo_services_register_meta_boxes() {
	$screen = get_current_screen();

	if ( ! $screen || 'page' !== $screen->post_type ) {
		return;
	}

	if ( ! sln_page_admin_should_register_template_boxes( 'sln_seo_services_admin_is_target_page' ) ) {
		return;
	}

	$callbacks = array(
		'sln_seo_svc_hero'         => 'sln_seo_services_render_hero_metabox',
		'sln_seo_svc_reality'      => 'sln_seo_services_render_reality_metabox',
		'sln_seo_svc_program'      => 'sln_seo_services_render_program_metabox',
		'sln_seo_svc_results'      => 'sln_seo_services_render_results_metabox',
		'sln_seo_svc_process'      => 'sln_seo_services_render_process_metabox',
		'sln_seo_svc_case_studies' => 'sln_seo_services_render_case_studies_metabox',
		'sln_seo_svc_pricing'      => 'sln_seo_services_render_pricing_metabox',
		'sln_seo_svc_testimonials' => 'sln_seo_services_render_testimonials_metabox',
		'sln_seo_svc_cta_form'     => 'sln_seo_services_render_cta_form_metabox',
		'sln_seo_svc_faq'          => 'sln_seo_services_render_faq_metabox',
	);

	$titles = array(
		'sln_seo_svc_hero'         => __( 'Section 1 — Hero', 'smart-leading-net' ),
		'sln_seo_svc_reality'      => __( 'Section 2 — The Reality', 'smart-leading-net' ),
		'sln_seo_svc_program'      => __( 'Section 3 — SEO Program', 'smart-leading-net' ),
		'sln_seo_svc_results'      => __( 'Section 4 — SEO Results', 'smart-leading-net' ),
		'sln_seo_svc_process'      => __( 'Section 5 — Process', 'smart-leading-net' ),
		'sln_seo_svc_case_studies' => __( 'Section 6 — Case Studies', 'smart-leading-net' ),
		'sln_seo_svc_pricing'      => __( 'Section 7 — Pricing', 'smart-leading-net' ),
		'sln_seo_svc_testimonials' => __( 'Section 8 — Testimonials', 'smart-leading-net' ),
		'sln_seo_svc_cta_form'     => __( 'Section 9 — CTA Form', 'smart-leading-net' ),
		'sln_seo_svc_faq'          => __( 'Section 10 — FAQ', 'smart-leading-net' ),
	);

	foreach ( $titles as $id => $title ) {
		add_meta_box(
			$id,
			$title,
			$callbacks[ $id ],
			'page',
			'normal',
			'default'
		);
		++$GLOBALS['sln_seo_services_registered_meta_boxes'];
	}
}
add_action( 'add_meta_boxes', 'sln_seo_services_register_meta_boxes' );

/**
 * Temporary debug notice on page edit screens.
 */
function sln_seo_services_admin_debug_notice() {
	if ( ! SLN_SEO_SVC_ADMIN_DEBUG ) {
		return;
	}

	$screen = get_current_screen();

	if ( ! $screen || 'page' !== $screen->post_type || ! in_array( $screen->base, array( 'post', 'post-new' ), true ) ) {
		return;
	}

	$post_id = 0;

	if ( isset( $_GET['post'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$post_id = absint( $_GET['post'] );
	}

	$template         = $post_id ? get_page_template_slug( $post_id ) : '';
	$is_target        = $post_id ? sln_seo_services_admin_is_target_page( get_post( $post_id ) ) : false;
	$registered_count = isset( $GLOBALS['sln_seo_services_registered_meta_boxes'] ) ? (int) $GLOBALS['sln_seo_services_registered_meta_boxes'] : 0;
	$admin_loaded     = function_exists( 'sln_seo_services_register_meta_boxes' ) ? 'yes' : 'no';
	$hook_registered  = has_action( 'add_meta_boxes', 'sln_seo_services_register_meta_boxes' ) ? 'yes' : 'no';

	echo '<div class="notice notice-info"><p><strong>SEO Services Admin Debug</strong><br>';
	echo 'Page ID: ' . esc_html( $post_id ? (string) $post_id : '(new page)' ) . '<br>';
	echo 'Template filename: ' . esc_html( $template ? $template : '(default / not saved yet)' ) . '<br>';
	echo 'Expected template: ' . esc_html( SLN_SEO_SVC_TEMPLATE ) . '<br>';
	echo 'Is SEO target page: ' . esc_html( $is_target ? 'yes' : 'no' ) . '<br>';
	echo 'seo-services-admin.php loaded: ' . esc_html( $admin_loaded ) . '<br>';
	echo 'add_meta_boxes hook attached: ' . esc_html( $hook_registered ) . '<br>';
	echo 'Meta boxes registered so far: ' . esc_html( (string) $registered_count ) . ' (registers later on this screen if 0)<br>';
	echo 'Screen ID: ' . esc_html( $screen->id ) . ' | post_type: ' . esc_html( $screen->post_type ) . '<br>';
	echo 'Root cause fixed: JS was hiding boxes when #page_template is missing in block editor.</p></div>';
}
add_action( 'admin_notices', 'sln_seo_services_admin_debug_notice' );

/**
 * Enqueue admin assets on SEO Services page edit screen.
 *
 * @param string $hook Current admin hook.
 */
function sln_seo_services_enqueue_admin_assets( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}

	$screen = get_current_screen();

	if ( ! $screen || 'page' !== $screen->post_type ) {
		return;
	}

	$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$post    = $post_id ? get_post( $post_id ) : null;

	wp_enqueue_media();
	wp_enqueue_script( 'jquery-ui-sortable' );

	wp_enqueue_style(
		'sln-seo-services-admin',
		SLN_THEME_URI . '/assets/css/seo-services-admin.css',
		array(),
		SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'sln-seo-services-admin',
		SLN_THEME_URI . '/assets/js/seo-services-admin.js',
		array( 'jquery', 'jquery-ui-sortable' ),
		SLN_THEME_VERSION,
		true
	);

	wp_localize_script(
		'sln-seo-services-admin',
		'slnSeoServicesAdmin',
		array(
			'editorSettings'   => function_exists( 'sln_growth_page_get_js_editor_settings' ) ? sln_growth_page_get_js_editor_settings() : array(),
			'template'         => SLN_SEO_SVC_TEMPLATE,
			'currentTemplate'  => ( $post instanceof WP_Post ) ? get_page_template_slug( $post->ID ) : '',
			'isTargetPage'     => ( $post instanceof WP_Post ) ? sln_seo_services_admin_is_target_page( $post ) : false,
		)
	);
}
add_action( 'admin_enqueue_scripts', 'sln_seo_services_enqueue_admin_assets' );

/**
 * Render hero metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_seo_services_render_hero_metabox( $post ) {
	$data = sln_seo_services_admin_get_section( $post->ID, SLN_SEO_SVC_HERO_META, sln_seo_services_default_hero() );
	?>
	<table class="form-table sln-seo-svc-admin__table" role="presentation">
		<?php
		sln_seo_services_admin_text_field( __( 'Small Heading', 'smart-leading-net' ), 'sln_seo_svc_hero[small_heading]', $data['small_heading'] );
		sln_seo_services_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_seo_svc_hero[main_heading]', $data['main_heading'] );
		sln_seo_services_admin_text_field( __( 'Highlighted Text', 'smart-leading-net' ), 'sln_seo_svc_hero[highlighted_text]', $data['highlighted_text'] );
		sln_seo_services_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-seo-hero-desc', 'sln_seo_svc_hero[description]', $data['description'] );
		sln_seo_services_admin_text_field( __( 'Primary Button Text', 'smart-leading-net' ), 'sln_seo_svc_hero[primary_button_text]', $data['primary_button_text'] );
		sln_seo_services_admin_url_field( __( 'Primary Button URL', 'smart-leading-net' ), 'sln_seo_svc_hero[primary_button_url]', $data['primary_button_url'] );
		sln_seo_services_admin_text_field( __( 'Secondary Button Text', 'smart-leading-net' ), 'sln_seo_svc_hero[secondary_button_text]', $data['secondary_button_text'] );
		sln_seo_services_admin_url_field( __( 'Secondary Button URL', 'smart-leading-net' ), 'sln_seo_svc_hero[secondary_button_url]', $data['secondary_button_url'] );
		sln_seo_services_admin_media_field( __( 'Hero Image', 'smart-leading-net' ), 'sln_seo_svc_hero[hero_image_id]', absint( $data['hero_image_id'] ) );
		sln_seo_services_admin_text_field( __( 'Trust Badge Text', 'smart-leading-net' ), 'sln_seo_svc_hero[trust_badge_text]', $data['trust_badge_text'] );
		sln_seo_services_admin_text_field( __( 'Certified Team Text', 'smart-leading-net' ), 'sln_seo_svc_hero[certified_team_text]', $data['certified_team_text'] );
		sln_seo_services_admin_text_field( __( 'Hero Stat Value', 'smart-leading-net' ), 'sln_seo_svc_hero[hero_stat_value]', $data['hero_stat_value'] );
		sln_seo_services_admin_text_field( __( 'Hero Stat Label', 'smart-leading-net' ), 'sln_seo_svc_hero[hero_stat_label]', $data['hero_stat_label'] );
		?>
	</table>
	<?php
}

/**
 * Render reality metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_seo_services_render_reality_metabox( $post ) {
	$section = sln_seo_services_admin_get_section( $post->ID, SLN_SEO_SVC_REALITY_SECTION_META, sln_seo_services_default_reality_section() );
	$cards   = sln_seo_services_admin_get_rows( $post->ID, SLN_SEO_SVC_REALITY_CARDS_META, sln_seo_services_default_reality_cards() );
	?>
	<table class="form-table sln-seo-svc-admin__table" role="presentation">
		<?php
		sln_seo_services_admin_text_field( __( 'Small Heading', 'smart-leading-net' ), 'sln_seo_svc_reality_section[small_heading]', $section['small_heading'] );
		sln_seo_services_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_seo_svc_reality_section[main_heading]', $section['main_heading'] );
		sln_seo_services_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-seo-reality-desc', 'sln_seo_svc_reality_section[description]', $section['description'] );
		?>
	</table>
	<div class="sln-seo-svc-admin__repeatable" data-row-selector=".sln-seo-svc-admin__reality-row" data-name-prefix="sln_seo_svc_reality_cards">
		<h3><?php esc_html_e( 'Cards', 'smart-leading-net' ); ?></h3>
		<div class="sln-seo-svc-admin__repeatable-list">
			<?php foreach ( $cards as $index => $card ) : ?>
				<div class="sln-seo-svc-admin__repeatable-row sln-seo-svc-admin__reality-row">
					<div class="sln-seo-svc-admin__row-head">
						<strong><?php echo esc_html( $card['title'] ?: __( 'Card', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-seo-svc-admin__row-actions">
							<button type="button" class="button sln-seo-svc-admin__move-up" aria-label="<?php esc_attr_e( 'Move up', 'smart-leading-net' ); ?>">&#8593;</button>
							<button type="button" class="button sln-seo-svc-admin__move-down" aria-label="<?php esc_attr_e( 'Move down', 'smart-leading-net' ); ?>">&#8595;</button>
							<button type="button" class="button-link-delete sln-seo-svc-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_seo_services_admin_media_field( __( 'Icon Upload', 'smart-leading-net' ), 'sln_seo_svc_reality_cards[' . $index . '][icon_id]', absint( $card['icon_id'] ) );
						?>
						<tr><th scope="row"><?php esc_html_e( 'Fallback Icon Slug', 'smart-leading-net' ); ?></th><td><input type="text" class="regular-text" name="sln_seo_svc_reality_cards[<?php echo esc_attr( (string) $index ); ?>][icon_slug]" value="<?php echo esc_attr( $card['icon_slug'] ?? '' ); ?>" /></td></tr>
						<?php
						sln_seo_services_admin_text_field( __( 'Card Title', 'smart-leading-net' ), 'sln_seo_svc_reality_cards[' . $index . '][title]', $card['title'] );
						sln_seo_services_admin_editor_field( __( 'Card Description', 'smart-leading-net' ), 'sln-seo-reality-card-' . $index, 'sln_seo_svc_reality_cards[' . $index . '][description]', $card['description'] );
						sln_seo_services_admin_url_field( __( 'Card URL', 'smart-leading-net' ), 'sln_seo_svc_reality_cards[' . $index . '][url]', $card['url'] ?? '' );
						sln_seo_services_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_seo_svc_reality_cards[' . $index . '][active]', ! empty( $card['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_seo_services_admin_repeater_toolbar( __( 'Add Card', 'smart-leading-net' ) ); ?>
	</div>
	<table class="form-table sln-seo-svc-admin__table" role="presentation">
		<?php
		sln_seo_services_admin_editor_field( __( 'CTA Text', 'smart-leading-net' ), 'sln-seo-reality-cta-text', 'sln_seo_svc_reality_section[cta_text]', $section['cta_text'] );
		sln_seo_services_admin_text_field( __( 'CTA Button Text', 'smart-leading-net' ), 'sln_seo_svc_reality_section[cta_button_text]', $section['cta_button_text'] );
		sln_seo_services_admin_url_field( __( 'CTA Button URL', 'smart-leading-net' ), 'sln_seo_svc_reality_section[cta_button_url]', $section['cta_button_url'] );
		?>
	</table>
	<?php
}

/**
 * Render program metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_seo_services_render_program_metabox( $post ) {
	$section = sln_seo_services_admin_get_section( $post->ID, SLN_SEO_SVC_PROGRAM_SECTION_META, sln_seo_services_default_program_section() );
	$cards   = sln_seo_services_admin_get_rows( $post->ID, SLN_SEO_SVC_PROGRAM_CARDS_META, sln_seo_services_default_program_cards() );
	?>
	<table class="form-table sln-seo-svc-admin__table" role="presentation">
		<?php
		sln_seo_services_admin_text_field( __( 'Small Heading', 'smart-leading-net' ), 'sln_seo_svc_program_section[small_heading]', $section['small_heading'] );
		sln_seo_services_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_seo_svc_program_section[main_heading]', $section['main_heading'] );
		sln_seo_services_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-seo-program-desc', 'sln_seo_svc_program_section[description]', $section['description'] );
		?>
	</table>
	<div class="sln-seo-svc-admin__repeatable" data-row-selector=".sln-seo-svc-admin__program-row" data-name-prefix="sln_seo_svc_program_cards">
		<h3><?php esc_html_e( 'Service Cards', 'smart-leading-net' ); ?></h3>
		<div class="sln-seo-svc-admin__repeatable-list">
			<?php foreach ( $cards as $index => $card ) : ?>
				<div class="sln-seo-svc-admin__repeatable-row sln-seo-svc-admin__program-row">
					<div class="sln-seo-svc-admin__row-head">
						<strong><?php echo esc_html( $card['title'] ?: __( 'Service Card', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-seo-svc-admin__row-actions">
							<button type="button" class="button sln-seo-svc-admin__move-up">&#8593;</button>
							<button type="button" class="button sln-seo-svc-admin__move-down">&#8595;</button>
							<button type="button" class="button-link-delete sln-seo-svc-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_seo_services_admin_media_field( __( 'SVG Icon', 'smart-leading-net' ), 'sln_seo_svc_program_cards[' . $index . '][icon_id]', absint( $card['icon_id'] ) );
						sln_seo_services_admin_text_field( __( 'Card Title', 'smart-leading-net' ), 'sln_seo_svc_program_cards[' . $index . '][title]', $card['title'] );
						sln_seo_services_admin_editor_field( __( 'Card Description', 'smart-leading-net' ), 'sln-seo-program-card-' . $index, 'sln_seo_svc_program_cards[' . $index . '][description]', $card['description'] );
						?>
						<tr><th scope="row"><?php esc_html_e( 'Bullet Points', 'smart-leading-net' ); ?></th><td><?php sln_seo_services_admin_render_bullets( 'sln_seo_svc_program_cards[' . $index . '][bullets]', $card['bullets'] ?? array() ); ?></td></tr>
						<?php
						sln_seo_services_admin_text_field( __( 'Internal Link Text', 'smart-leading-net' ), 'sln_seo_svc_program_cards[' . $index . '][link_text]', $card['link_text'] ?? '' );
						sln_seo_services_admin_url_field( __( 'Internal Link URL', 'smart-leading-net' ), 'sln_seo_svc_program_cards[' . $index . '][link_url]', $card['link_url'] ?? '' );
						sln_seo_services_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_seo_svc_program_cards[' . $index . '][active]', ! empty( $card['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_seo_services_admin_repeater_toolbar( __( 'Add Service Card', 'smart-leading-net' ) ); ?>
	</div>
	<?php
}

/**
 * Render results metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_seo_services_render_results_metabox( $post ) {
	$section = sln_seo_services_admin_get_section( $post->ID, SLN_SEO_SVC_RESULTS_SECTION_META, sln_seo_services_default_results_section() );
	$blocks  = sln_seo_services_admin_get_rows( $post->ID, SLN_SEO_SVC_RESULTS_BLOCKS_META, sln_seo_services_default_results_blocks() );
	?>
	<table class="form-table sln-seo-svc-admin__table" role="presentation">
		<?php
		sln_seo_services_admin_text_field( __( 'Small Heading', 'smart-leading-net' ), 'sln_seo_svc_results_section[small_heading]', $section['small_heading'] );
		sln_seo_services_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_seo_svc_results_section[main_heading]', $section['main_heading'] );
		sln_seo_services_admin_text_field( __( 'Highlighted Word', 'smart-leading-net' ), 'sln_seo_svc_results_section[highlighted_word]', $section['highlighted_word'] );
		sln_seo_services_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-seo-results-desc', 'sln_seo_svc_results_section[description]', $section['description'] );
		?>
	</table>
	<div class="sln-seo-svc-admin__repeatable" data-row-selector=".sln-seo-svc-admin__results-row" data-name-prefix="sln_seo_svc_results_blocks">
		<h3><?php esc_html_e( 'Result Blocks', 'smart-leading-net' ); ?></h3>
		<div class="sln-seo-svc-admin__repeatable-list">
			<?php foreach ( $blocks as $index => $block ) : ?>
				<div class="sln-seo-svc-admin__repeatable-row sln-seo-svc-admin__results-row">
					<div class="sln-seo-svc-admin__row-head">
						<strong><?php echo esc_html( $block['label'] ?: __( 'Result Block', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-seo-svc-admin__row-actions">
							<button type="button" class="button sln-seo-svc-admin__move-up">&#8593;</button>
							<button type="button" class="button sln-seo-svc-admin__move-down">&#8595;</button>
							<button type="button" class="button-link-delete sln-seo-svc-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_seo_services_admin_text_field( __( 'Number', 'smart-leading-net' ), 'sln_seo_svc_results_blocks[' . $index . '][number]', $block['number'] );
						sln_seo_services_admin_text_field( __( 'Label', 'smart-leading-net' ), 'sln_seo_svc_results_blocks[' . $index . '][label]', $block['label'] );
						sln_seo_services_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-seo-results-block-' . $index, 'sln_seo_svc_results_blocks[' . $index . '][description]', $block['description'] );
						sln_seo_services_admin_media_field( __( 'Icon', 'smart-leading-net' ), 'sln_seo_svc_results_blocks[' . $index . '][icon_id]', absint( $block['icon_id'] ) );
						sln_seo_services_admin_url_field( __( 'Optional Internal URL', 'smart-leading-net' ), 'sln_seo_svc_results_blocks[' . $index . '][url]', $block['url'] ?? '' );
						sln_seo_services_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_seo_svc_results_blocks[' . $index . '][active]', ! empty( $block['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_seo_services_admin_repeater_toolbar( __( 'Add Result Block', 'smart-leading-net' ) ); ?>
	</div>
	<?php
}

/**
 * Render process metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_seo_services_render_process_metabox( $post ) {
	$section = sln_seo_services_admin_get_section( $post->ID, SLN_SEO_SVC_PROCESS_SECTION_META, sln_seo_services_default_process_section() );
	$steps   = sln_seo_services_admin_get_rows( $post->ID, SLN_SEO_SVC_PROCESS_STEPS_META, sln_seo_services_default_process_steps() );
	?>
	<table class="form-table sln-seo-svc-admin__table" role="presentation">
		<?php
		sln_seo_services_admin_text_field( __( 'Small Heading', 'smart-leading-net' ), 'sln_seo_svc_process_section[small_heading]', $section['small_heading'] );
		sln_seo_services_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_seo_svc_process_section[main_heading]', $section['main_heading'] );
		sln_seo_services_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-seo-process-desc', 'sln_seo_svc_process_section[description]', $section['description'] );
		?>
	</table>
	<div class="sln-seo-svc-admin__repeatable" data-row-selector=".sln-seo-svc-admin__process-row" data-name-prefix="sln_seo_svc_process_steps">
		<h3><?php esc_html_e( 'Process Steps', 'smart-leading-net' ); ?></h3>
		<div class="sln-seo-svc-admin__repeatable-list">
			<?php foreach ( $steps as $index => $step ) : ?>
				<div class="sln-seo-svc-admin__repeatable-row sln-seo-svc-admin__process-row">
					<div class="sln-seo-svc-admin__row-head">
						<strong><?php echo esc_html( $step['title'] ?: __( 'Process Step', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-seo-svc-admin__row-actions">
							<button type="button" class="button sln-seo-svc-admin__move-up">&#8593;</button>
							<button type="button" class="button sln-seo-svc-admin__move-down">&#8595;</button>
							<button type="button" class="button-link-delete sln-seo-svc-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_seo_services_admin_text_field( __( 'Step Number', 'smart-leading-net' ), 'sln_seo_svc_process_steps[' . $index . '][step_number]', $step['step_number'] );
						sln_seo_services_admin_media_field( __( 'Icon Upload', 'smart-leading-net' ), 'sln_seo_svc_process_steps[' . $index . '][icon_id]', absint( $step['icon_id'] ) );
						sln_seo_services_admin_text_field( __( 'Step Title', 'smart-leading-net' ), 'sln_seo_svc_process_steps[' . $index . '][title]', $step['title'] );
						sln_seo_services_admin_editor_field( __( 'Step Description', 'smart-leading-net' ), 'sln-seo-process-step-' . $index, 'sln_seo_svc_process_steps[' . $index . '][description]', $step['description'] );
						sln_seo_services_admin_url_field( __( 'Optional URL', 'smart-leading-net' ), 'sln_seo_svc_process_steps[' . $index . '][url]', $step['url'] ?? '' );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_seo_services_admin_repeater_toolbar( __( 'Add Process Step', 'smart-leading-net' ) ); ?>
	</div>
	<?php
}

/**
 * Render case studies metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_seo_services_render_case_studies_metabox( $post ) {
	$section = sln_seo_services_admin_get_section( $post->ID, SLN_SEO_SVC_CASE_STUDIES_SECTION_META, sln_seo_services_default_case_studies_section() );
	$cards   = sln_seo_services_admin_get_rows( $post->ID, SLN_SEO_SVC_CASE_STUDIES_CARDS_META, sln_seo_services_default_case_studies_cards() );
	?>
	<table class="form-table sln-seo-svc-admin__table" role="presentation">
		<?php
		sln_seo_services_admin_text_field( __( 'Small Heading', 'smart-leading-net' ), 'sln_seo_svc_case_studies_section[small_heading]', $section['small_heading'] );
		sln_seo_services_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_seo_svc_case_studies_section[main_heading]', $section['main_heading'] );
		sln_seo_services_admin_text_field( __( 'Highlighted Word', 'smart-leading-net' ), 'sln_seo_svc_case_studies_section[highlighted_word]', $section['highlighted_word'] );
		sln_seo_services_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-seo-cs-desc', 'sln_seo_svc_case_studies_section[description]', $section['description'] );
		sln_seo_services_admin_text_field( __( 'More Case Studies Text', 'smart-leading-net' ), 'sln_seo_svc_case_studies_section[more_case_studies_text]', $section['more_case_studies_text'] );
		sln_seo_services_admin_url_field( __( 'More Case Studies URL', 'smart-leading-net' ), 'sln_seo_svc_case_studies_section[more_case_studies_url]', $section['more_case_studies_url'] );
		?>
	</table>
	<div class="sln-seo-svc-admin__repeatable" data-row-selector=".sln-seo-svc-admin__cs-row" data-name-prefix="sln_seo_svc_case_studies_cards">
		<h3><?php esc_html_e( 'Case Study Cards', 'smart-leading-net' ); ?></h3>
		<div class="sln-seo-svc-admin__repeatable-list">
			<?php foreach ( $cards as $index => $card ) : ?>
				<div class="sln-seo-svc-admin__repeatable-row sln-seo-svc-admin__cs-row">
					<div class="sln-seo-svc-admin__row-head">
						<strong><?php echo esc_html( $card['title'] ?: __( 'Case Study Card', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-seo-svc-admin__row-actions">
							<button type="button" class="button sln-seo-svc-admin__move-up">&#8593;</button>
							<button type="button" class="button sln-seo-svc-admin__move-down">&#8595;</button>
							<button type="button" class="button-link-delete sln-seo-svc-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_seo_services_admin_text_field( __( 'Card Title', 'smart-leading-net' ), 'sln_seo_svc_case_studies_cards[' . $index . '][title]', $card['title'] );
						sln_seo_services_admin_media_field( __( 'Icon Upload', 'smart-leading-net' ), 'sln_seo_svc_case_studies_cards[' . $index . '][icon_id]', absint( $card['icon_id'] ) );
						sln_seo_services_admin_text_field( __( 'Metric', 'smart-leading-net' ), 'sln_seo_svc_case_studies_cards[' . $index . '][metric]', $card['metric'] );
						sln_seo_services_admin_text_field( __( 'Metric Description', 'smart-leading-net' ), 'sln_seo_svc_case_studies_cards[' . $index . '][metric_description]', $card['metric_description'] );
						sln_seo_services_admin_media_field( __( 'Graph Image or SVG', 'smart-leading-net' ), 'sln_seo_svc_case_studies_cards[' . $index . '][graph_id]', absint( $card['graph_id'] ) );
						sln_seo_services_admin_text_field( __( 'Graph Fallback Filename', 'smart-leading-net' ), 'sln_seo_svc_case_studies_cards[' . $index . '][graph_fallback]', $card['graph_fallback'] ?? '' );
						sln_seo_services_admin_text_field( __( 'Footer Text', 'smart-leading-net' ), 'sln_seo_svc_case_studies_cards[' . $index . '][footer_text]', $card['footer_text'] ?? '' );
						sln_seo_services_admin_url_field( __( 'Card URL', 'smart-leading-net' ), 'sln_seo_svc_case_studies_cards[' . $index . '][card_url]', $card['card_url'] ?? '' );
						sln_seo_services_admin_text_field( __( 'Card Color', 'smart-leading-net' ), 'sln_seo_svc_case_studies_cards[' . $index . '][card_color]', $card['card_color'] ?? '#1f4e9e' );
						sln_seo_services_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_seo_svc_case_studies_cards[' . $index . '][active]', ! empty( $card['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_seo_services_admin_repeater_toolbar( __( 'Add Case Study Card', 'smart-leading-net' ) ); ?>
	</div>
	<?php
}

/**
 * Render pricing metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_seo_services_render_pricing_metabox( $post ) {
	$section = sln_seo_services_admin_get_section( $post->ID, SLN_SEO_SVC_PRICING_SECTION_META, sln_seo_services_default_pricing_section() );
	$plans   = sln_seo_services_admin_get_rows( $post->ID, SLN_SEO_SVC_PRICING_PLANS_META, sln_seo_services_default_pricing_plans() );
	?>
	<table class="form-table sln-seo-svc-admin__table" role="presentation">
		<?php
		sln_seo_services_admin_text_field( __( 'Small Heading', 'smart-leading-net' ), 'sln_seo_svc_pricing_section[small_heading]', $section['small_heading'] );
		sln_seo_services_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_seo_svc_pricing_section[main_heading]', $section['main_heading'] );
		sln_seo_services_admin_text_field( __( 'Highlighted Word', 'smart-leading-net' ), 'sln_seo_svc_pricing_section[highlighted_word]', $section['highlighted_word'] );
		sln_seo_services_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-seo-pricing-desc', 'sln_seo_svc_pricing_section[description]', $section['description'] );
		?>
	</table>
	<div class="sln-seo-svc-admin__repeatable" data-row-selector=".sln-seo-svc-admin__pricing-row" data-name-prefix="sln_seo_svc_pricing_plans">
		<h3><?php esc_html_e( 'Pricing Plans', 'smart-leading-net' ); ?></h3>
		<div class="sln-seo-svc-admin__repeatable-list">
			<?php foreach ( $plans as $index => $plan ) : ?>
				<div class="sln-seo-svc-admin__repeatable-row sln-seo-svc-admin__pricing-row">
					<div class="sln-seo-svc-admin__row-head">
						<strong><?php echo esc_html( $plan['plan_name'] ?: __( 'Pricing Plan', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-seo-svc-admin__row-actions">
							<button type="button" class="button sln-seo-svc-admin__move-up">&#8593;</button>
							<button type="button" class="button sln-seo-svc-admin__move-down">&#8595;</button>
							<button type="button" class="button-link-delete sln-seo-svc-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_seo_services_admin_text_field( __( 'Plan Name', 'smart-leading-net' ), 'sln_seo_svc_pricing_plans[' . $index . '][plan_name]', $plan['plan_name'] );
						sln_seo_services_admin_text_field( __( 'Price', 'smart-leading-net' ), 'sln_seo_svc_pricing_plans[' . $index . '][price]', $plan['price'] );
						sln_seo_services_admin_text_field( __( 'Price Suffix', 'smart-leading-net' ), 'sln_seo_svc_pricing_plans[' . $index . '][price_suffix]', $plan['price_suffix'] );
						sln_seo_services_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-seo-pricing-plan-' . $index, 'sln_seo_svc_pricing_plans[' . $index . '][description]', $plan['description'] );
						?>
						<tr><th scope="row"><?php esc_html_e( 'Features', 'smart-leading-net' ); ?></th><td><?php sln_seo_services_admin_render_bullets( 'sln_seo_svc_pricing_plans[' . $index . '][features]', $plan['features'] ?? array() ); ?></td></tr>
						<?php
						sln_seo_services_admin_text_field( __( 'Button Text', 'smart-leading-net' ), 'sln_seo_svc_pricing_plans[' . $index . '][button_text]', $plan['button_text'] );
						sln_seo_services_admin_url_field( __( 'Button URL', 'smart-leading-net' ), 'sln_seo_svc_pricing_plans[' . $index . '][button_url]', $plan['button_url'] );
						?>
						<tr><th scope="row"><?php esc_html_e( 'Most Popular', 'smart-leading-net' ); ?></th><td><label><input type="checkbox" name="sln_seo_svc_pricing_plans[<?php echo esc_attr( (string) $index ); ?>][is_popular]" value="1" <?php checked( ! empty( $plan['is_popular'] ) ); ?> /> <?php esc_html_e( 'Mark as most popular', 'smart-leading-net' ); ?></label></td></tr>
						<?php sln_seo_services_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_seo_svc_pricing_plans[' . $index . '][active]', ! empty( $plan['active'] ) ); ?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_seo_services_admin_repeater_toolbar( __( 'Add Pricing Plan', 'smart-leading-net' ) ); ?>
	</div>
	<?php
}

/**
 * Render testimonials metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_seo_services_render_testimonials_metabox( $post ) {
	$section = sln_seo_services_admin_get_section( $post->ID, SLN_SEO_SVC_TESTIMONIALS_SECTION_META, sln_seo_services_default_testimonials_section() );
	$summary = sln_seo_services_admin_get_section( $post->ID, SLN_SEO_SVC_TESTIMONIALS_SUMMARY_META, sln_seo_services_default_testimonials_summary() );
	$reviews = sln_seo_services_admin_get_rows( $post->ID, SLN_SEO_SVC_TESTIMONIALS_REVIEWS_META, sln_seo_services_default_testimonials_reviews() );
	?>
	<table class="form-table sln-seo-svc-admin__table" role="presentation">
		<?php
		sln_seo_services_admin_text_field( __( 'Small Heading', 'smart-leading-net' ), 'sln_seo_svc_testimonials_section[small_heading]', $section['small_heading'] );
		sln_seo_services_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_seo_svc_testimonials_section[main_heading]', $section['main_heading'] );
		sln_seo_services_admin_text_field( __( 'Highlighted Word', 'smart-leading-net' ), 'sln_seo_svc_testimonials_section[highlighted_word]', $section['highlighted_word'] );
		sln_seo_services_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-seo-testimonials-desc', 'sln_seo_svc_testimonials_section[description]', $section['description'] );
		?>
	</table>
	<h3><?php esc_html_e( 'Summary Block', 'smart-leading-net' ); ?></h3>
	<table class="form-table sln-seo-svc-admin__table" role="presentation">
		<?php
		sln_seo_services_admin_text_field( __( 'Review Count', 'smart-leading-net' ), 'sln_seo_svc_testimonials_summary[review_count]', $summary['review_count'] );
		sln_seo_services_admin_text_field( __( 'Average Rating', 'smart-leading-net' ), 'sln_seo_svc_testimonials_summary[average_rating]', $summary['average_rating'] );
		sln_seo_services_admin_text_field( __( 'Websites Built', 'smart-leading-net' ), 'sln_seo_svc_testimonials_summary[websites_built]', $summary['websites_built'] );
		sln_seo_services_admin_text_field( __( 'Revenue Generated', 'smart-leading-net' ), 'sln_seo_svc_testimonials_summary[revenue_generated]', $summary['revenue_generated'] );
		?>
	</table>
	<div class="sln-seo-svc-admin__repeatable" data-row-selector=".sln-seo-svc-admin__review-row" data-name-prefix="sln_seo_svc_testimonials_reviews">
		<h3><?php esc_html_e( 'Testimonials', 'smart-leading-net' ); ?></h3>
		<div class="sln-seo-svc-admin__repeatable-list">
			<?php foreach ( $reviews as $index => $review ) : ?>
				<div class="sln-seo-svc-admin__repeatable-row sln-seo-svc-admin__review-row">
					<div class="sln-seo-svc-admin__row-head">
						<strong><?php echo esc_html( $review['client_name'] ?: __( 'Testimonial', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-seo-svc-admin__row-actions">
							<button type="button" class="button sln-seo-svc-admin__move-up">&#8593;</button>
							<button type="button" class="button sln-seo-svc-admin__move-down">&#8595;</button>
							<button type="button" class="button-link-delete sln-seo-svc-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_seo_services_admin_text_field( __( 'Rating', 'smart-leading-net' ), 'sln_seo_svc_testimonials_reviews[' . $index . '][rating]', (string) $review['rating'] );
						sln_seo_services_admin_editor_field( __( 'Testimonial', 'smart-leading-net' ), 'sln-seo-review-' . $index, 'sln_seo_svc_testimonials_reviews[' . $index . '][testimonial]', $review['testimonial'] );
						sln_seo_services_admin_text_field( __( 'Client Name', 'smart-leading-net' ), 'sln_seo_svc_testimonials_reviews[' . $index . '][client_name]', $review['client_name'] );
						sln_seo_services_admin_text_field( __( 'Client Position', 'smart-leading-net' ), 'sln_seo_svc_testimonials_reviews[' . $index . '][client_position]', $review['client_position'] );
						sln_seo_services_admin_text_field( __( 'Client Initials', 'smart-leading-net' ), 'sln_seo_svc_testimonials_reviews[' . $index . '][client_initials]', $review['client_initials'] );
						sln_seo_services_admin_media_field( __( 'Client Image', 'smart-leading-net' ), 'sln_seo_svc_testimonials_reviews[' . $index . '][client_image_id]', absint( $review['client_image_id'] ) );
						sln_seo_services_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_seo_svc_testimonials_reviews[' . $index . '][active]', ! empty( $review['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_seo_services_admin_repeater_toolbar( __( 'Add Testimonial', 'smart-leading-net' ) ); ?>
	</div>
	<?php
}

/**
 * Render CTA form metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_seo_services_render_cta_form_metabox( $post ) {
	$data = sln_seo_services_admin_get_section( $post->ID, SLN_SEO_SVC_CTA_FORM_META, sln_seo_services_default_cta_form() );
	?>
	<table class="form-table sln-seo-svc-admin__table" role="presentation">
		<?php
		sln_seo_services_admin_text_field( __( 'Small Heading', 'smart-leading-net' ), 'sln_seo_svc_cta_form[small_heading]', $data['small_heading'] );
		sln_seo_services_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_seo_svc_cta_form[main_heading]', $data['main_heading'] );
		sln_seo_services_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-seo-cta-desc', 'sln_seo_svc_cta_form[description]', $data['description'] );
		sln_seo_services_admin_text_field( __( 'Form Heading', 'smart-leading-net' ), 'sln_seo_svc_cta_form[form_heading]', $data['form_heading'] );
		sln_seo_services_admin_text_field( __( 'Name Placeholder', 'smart-leading-net' ), 'sln_seo_svc_cta_form[name_placeholder]', $data['name_placeholder'] );
		sln_seo_services_admin_text_field( __( 'Email Placeholder', 'smart-leading-net' ), 'sln_seo_svc_cta_form[email_placeholder]', $data['email_placeholder'] );
		sln_seo_services_admin_text_field( __( 'Phone Placeholder', 'smart-leading-net' ), 'sln_seo_svc_cta_form[phone_placeholder]', $data['phone_placeholder'] );
		sln_seo_services_admin_text_field( __( 'Website Placeholder', 'smart-leading-net' ), 'sln_seo_svc_cta_form[website_placeholder]', $data['website_placeholder'] );
		sln_seo_services_admin_text_field( __( 'Button Text', 'smart-leading-net' ), 'sln_seo_svc_cta_form[button_text]', $data['button_text'] );
		sln_seo_services_admin_url_field( __( 'Thank You Page URL', 'smart-leading-net' ), 'sln_seo_svc_cta_form[thank_you_page_url]', $data['thank_you_page_url'] );
		?>
	</table>
	<?php
}

/**
 * Render FAQ metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_seo_services_render_faq_metabox( $post ) {
	$section = sln_seo_services_admin_get_section( $post->ID, SLN_SEO_SVC_FAQ_SECTION_META, sln_seo_services_default_faq_section() );
	$items   = sln_seo_services_admin_get_rows( $post->ID, SLN_SEO_SVC_FAQ_ITEMS_META, sln_seo_services_default_faq_items() );
	?>
	<table class="form-table sln-seo-svc-admin__table" role="presentation">
		<?php
		sln_seo_services_admin_text_field( __( 'Small Heading', 'smart-leading-net' ), 'sln_seo_svc_faq_section[small_heading]', $section['small_heading'] );
		sln_seo_services_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_seo_svc_faq_section[main_heading]', $section['main_heading'] );
		sln_seo_services_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-seo-faq-desc', 'sln_seo_svc_faq_section[description]', $section['description'] );
		sln_seo_services_admin_text_field( __( 'CTA Button Text', 'smart-leading-net' ), 'sln_seo_svc_faq_section[cta_button_text]', $section['cta_button_text'] );
		sln_seo_services_admin_url_field( __( 'CTA Button URL', 'smart-leading-net' ), 'sln_seo_svc_faq_section[cta_button_url]', $section['cta_button_url'] );
		?>
	</table>
	<div class="sln-seo-svc-admin__repeatable" data-row-selector=".sln-seo-svc-admin__faq-row" data-name-prefix="sln_seo_svc_faq_items">
		<h3><?php esc_html_e( 'FAQ Items', 'smart-leading-net' ); ?></h3>
		<div class="sln-seo-svc-admin__repeatable-list">
			<?php foreach ( $items as $index => $item ) : ?>
				<div class="sln-seo-svc-admin__repeatable-row sln-seo-svc-admin__faq-row">
					<div class="sln-seo-svc-admin__row-head">
						<strong><?php echo esc_html( $item['question'] ?: __( 'FAQ Item', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-seo-svc-admin__row-actions">
							<button type="button" class="button sln-seo-svc-admin__move-up">&#8593;</button>
							<button type="button" class="button sln-seo-svc-admin__move-down">&#8595;</button>
							<button type="button" class="button-link-delete sln-seo-svc-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_seo_services_admin_text_field( __( 'Question', 'smart-leading-net' ), 'sln_seo_svc_faq_items[' . $index . '][question]', $item['question'] );
						sln_seo_services_admin_editor_field( __( 'Answer', 'smart-leading-net' ), 'sln-seo-faq-item-' . $index, 'sln_seo_svc_faq_items[' . $index . '][answer]', $item['answer'] );
						sln_seo_services_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_seo_svc_faq_items[' . $index . '][active]', ! empty( $item['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_seo_services_admin_repeater_toolbar( __( 'Add FAQ Item', 'smart-leading-net' ) ); ?>
	</div>
	<?php
}
