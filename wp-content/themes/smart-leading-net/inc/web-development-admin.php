<?php
/**
 * Web Development Services page — admin meta boxes.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** @var int */
$GLOBALS['sln_wd_registered_meta_boxes'] = 0;

/**
 * Register Web Development meta boxes.
 */
function sln_wd_register_meta_boxes() {
	$screen = get_current_screen();

	if ( ! $screen || 'page' !== $screen->post_type ) {
		return;
	}

	if ( ! sln_page_admin_should_register_template_boxes( 'sln_wd_admin_is_target_page' ) ) {
		return;
	}

	$callbacks = array(
		'sln_wd_hero'      => 'sln_wd_render_hero_metabox',
		'sln_wd_stats'     => 'sln_wd_render_stats_metabox',
		'sln_wd_platforms' => 'sln_wd_render_platforms_metabox',
		'sln_wd_extras'    => 'sln_wd_render_extras_metabox',
		'sln_wd_matters'   => 'sln_wd_render_matters_metabox',
		'sln_wd_process'   => 'sln_wd_render_process_metabox',
		'sln_wd_promises'  => 'sln_wd_render_promises_metabox',
		'sln_wd_services'  => 'sln_wd_render_services_metabox',
		'sln_wd_compare'   => 'sln_wd_render_compare_metabox',
		'sln_wd_benefits'  => 'sln_wd_render_benefits_metabox',
		'sln_wd_cases'     => 'sln_wd_render_cases_metabox',
		'sln_wd_pricing'   => 'sln_wd_render_pricing_metabox',
		'sln_wd_care'      => 'sln_wd_render_care_metabox',
		'sln_wd_faq'       => 'sln_wd_render_faq_metabox',
		'sln_wd_final'     => 'sln_wd_render_final_metabox',
	);

	$titles = array(
		'sln_wd_hero'      => __( 'Section 1 — Hero', 'smart-leading-net' ),
		'sln_wd_stats'     => __( 'Section 2 — Statistics', 'smart-leading-net' ),
		'sln_wd_platforms' => __( 'Section 3 — Platforms', 'smart-leading-net' ),
		'sln_wd_extras'    => __( 'Section 4 — Helpful Extras', 'smart-leading-net' ),
		'sln_wd_matters'   => __( 'Section 5 — Why It Matters', 'smart-leading-net' ),
		'sln_wd_process'   => __( 'Section 6 — How It Works', 'smart-leading-net' ),
		'sln_wd_promises'  => __( 'Section 7 — Our Promise', 'smart-leading-net' ),
		'sln_wd_services'  => __( 'Section 8 — What We Do', 'smart-leading-net' ),
		'sln_wd_compare'   => __( 'Section 9 — Why Choose Us', 'smart-leading-net' ),
		'sln_wd_benefits'  => __( 'Section 10 — Summary Benefits', 'smart-leading-net' ),
		'sln_wd_cases'     => __( 'Section 11 — Case Studies', 'smart-leading-net' ),
		'sln_wd_pricing'   => __( 'Section 12 — Website Pricing', 'smart-leading-net' ),
		'sln_wd_care'      => __( 'Section 13 — Care Plans', 'smart-leading-net' ),
		'sln_wd_faq'       => __( 'Section 14 — FAQ', 'smart-leading-net' ),
		'sln_wd_final'     => __( 'Section 15 — Final CTA and Form', 'smart-leading-net' ),
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
		++$GLOBALS['sln_wd_registered_meta_boxes'];
	}
}
add_action( 'add_meta_boxes', 'sln_wd_register_meta_boxes' );

/**
 * Enqueue admin assets on Web Development page edit screen.
 *
 * @param string $hook Current admin hook.
 */
function sln_wd_enqueue_admin_assets( $hook ) {
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
		'sln-web-development-admin',
		SLN_THEME_URI . '/assets/css/web-development-admin.css',
		array(),
		SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'sln-web-development-admin',
		SLN_THEME_URI . '/assets/js/web-development-admin.js',
		array( 'jquery', 'jquery-ui-sortable' ),
		SLN_THEME_VERSION,
		true
	);

	wp_localize_script(
		'sln-web-development-admin',
		'slnWebDevelopmentAdmin',
		array(
			'editorSettings'  => function_exists( 'sln_growth_page_get_js_editor_settings' ) ? sln_growth_page_get_js_editor_settings() : array(),
			'template'        => SLN_WD_TEMPLATE,
			'currentTemplate' => ( $post instanceof WP_Post ) ? get_page_template_slug( $post->ID ) : '',
			'isTargetPage'    => ( $post instanceof WP_Post ) ? sln_wd_admin_is_target_page( $post ) : false,
		)
	);
}
add_action( 'admin_enqueue_scripts', 'sln_wd_enqueue_admin_assets' );

/**
 * Render hero metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_wd_render_hero_metabox( $post ) {
	$data = sln_wd_admin_get_section( $post->ID, SLN_WD_HERO_META, sln_wd_default_hero() );
	?>
	<table class="form-table sln-wd-admin__table" role="presentation">
		<?php
		sln_wd_admin_text_field( __( 'Trust Badge', 'smart-leading-net' ), 'sln_wd_hero[trust_badge]', $data['trust_badge'] );
		sln_wd_admin_text_field( __( 'Certified Team Text', 'smart-leading-net' ), 'sln_wd_hero[certified_text]', $data['certified_text'] );
		sln_wd_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_wd_hero[main_heading]', $data['main_heading'] );
		sln_wd_admin_text_field( __( 'Highlighted Text', 'smart-leading-net' ), 'sln_wd_hero[highlighted_text]', $data['highlighted_text'] );
		sln_wd_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-wd-hero-desc', 'sln_wd_hero[description]', $data['description'] );
		sln_wd_admin_text_field( __( 'Primary Button Text', 'smart-leading-net' ), 'sln_wd_hero[primary_button_text]', $data['primary_button_text'] );
		sln_wd_admin_url_field( __( 'Primary Button URL', 'smart-leading-net' ), 'sln_wd_hero[primary_button_url]', $data['primary_button_url'] );
		sln_wd_admin_text_field( __( 'Secondary Button Text', 'smart-leading-net' ), 'sln_wd_hero[secondary_button_text]', $data['secondary_button_text'] );
		sln_wd_admin_url_field( __( 'Secondary Button URL', 'smart-leading-net' ), 'sln_wd_hero[secondary_button_url]', $data['secondary_button_url'] );
		sln_wd_admin_media_field( __( 'Hero Image / Dashboard Mockup', 'smart-leading-net' ), 'sln_wd_hero[hero_image_id]', absint( $data['hero_image_id'] ?? 0 ) );
		sln_wd_admin_text_field( __( 'Float Stat 1 Label', 'smart-leading-net' ), 'sln_wd_hero[float_stat_1_label]', $data['float_stat_1_label'] ?? '' );
		sln_wd_admin_text_field( __( 'Float Stat 1 Value', 'smart-leading-net' ), 'sln_wd_hero[float_stat_1_value]', $data['float_stat_1_value'] ?? '' );
		sln_wd_admin_text_field( __( 'Float Stat 2 Label', 'smart-leading-net' ), 'sln_wd_hero[float_stat_2_label]', $data['float_stat_2_label'] ?? '' );
		sln_wd_admin_text_field( __( 'Float Stat 2 Value', 'smart-leading-net' ), 'sln_wd_hero[float_stat_2_value]', $data['float_stat_2_value'] ?? '' );
		sln_wd_admin_text_field( __( 'Float Stat 3 Label', 'smart-leading-net' ), 'sln_wd_hero[float_stat_3_label]', $data['float_stat_3_label'] ?? '' );
		sln_wd_admin_text_field( __( 'Float Stat 3 Value', 'smart-leading-net' ), 'sln_wd_hero[float_stat_3_value]', $data['float_stat_3_value'] ?? '' );
		sln_wd_admin_text_field( __( 'Float Stat 4 Label', 'smart-leading-net' ), 'sln_wd_hero[float_stat_4_label]', $data['float_stat_4_label'] ?? '' );
		sln_wd_admin_text_field( __( 'Float Stat 4 Value', 'smart-leading-net' ), 'sln_wd_hero[float_stat_4_value]', $data['float_stat_4_value'] ?? '' );
		sln_wd_admin_text_field( __( 'Float Stat 5 Label', 'smart-leading-net' ), 'sln_wd_hero[float_stat_5_label]', $data['float_stat_5_label'] ?? '' );
		sln_wd_admin_text_field( __( 'Float Stat 5 Value', 'smart-leading-net' ), 'sln_wd_hero[float_stat_5_value]', $data['float_stat_5_value'] ?? '' );
		sln_wd_admin_text_field( __( 'Rating / Trust Line', 'smart-leading-net' ), 'sln_wd_hero[rating_line]', $data['rating_line'] );
		sln_wd_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_wd_hero[active]', ! empty( $data['active'] ) );
		?>
	</table>
	<?php
}

/**
 * Render statistics metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_wd_render_stats_metabox( $post ) {
	$stats = sln_wd_admin_get_rows( $post->ID, SLN_WD_STATS_META, sln_wd_default_stats() );
	?>
	<div class="sln-wd-admin__repeatable" data-row-selector=".sln-wd-admin__stat-row" data-name-prefix="sln_wd_stats">
		<div class="sln-wd-admin__repeatable-list">
			<?php foreach ( $stats as $index => $stat ) : ?>
				<div class="sln-wd-admin__repeatable-row sln-wd-admin__stat-row">
					<div class="sln-wd-admin__row-head">
						<strong><?php echo esc_html( $stat['label'] ?: __( 'Stat', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-wd-admin__row-actions">
							<button type="button" class="button sln-wd-admin__move-up" aria-label="<?php esc_attr_e( 'Move up', 'smart-leading-net' ); ?>">&#8593;</button>
							<button type="button" class="button sln-wd-admin__move-down" aria-label="<?php esc_attr_e( 'Move down', 'smart-leading-net' ); ?>">&#8595;</button>
							<button type="button" class="button-link-delete sln-wd-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_wd_admin_text_field( __( 'Prefix', 'smart-leading-net' ), 'sln_wd_stats[' . $index . '][prefix]', $stat['prefix'] ?? '' );
						sln_wd_admin_text_field( __( 'Value', 'smart-leading-net' ), 'sln_wd_stats[' . $index . '][value]', $stat['value'] ?? '' );
						sln_wd_admin_text_field( __( 'Suffix', 'smart-leading-net' ), 'sln_wd_stats[' . $index . '][suffix]', $stat['suffix'] ?? '' );
						sln_wd_admin_text_field( __( 'Label', 'smart-leading-net' ), 'sln_wd_stats[' . $index . '][label]', $stat['label'] ?? '' );
						sln_wd_admin_media_field( __( 'Icon', 'smart-leading-net' ), 'sln_wd_stats[' . $index . '][icon_id]', absint( $stat['icon_id'] ?? 0 ) );
						sln_wd_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_wd_stats[' . $index . '][active]', ! empty( $stat['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_wd_admin_repeater_toolbar( __( 'Add Stat', 'smart-leading-net' ) ); ?>
	</div>
	<?php
}

/**
 * Render platforms metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_wd_render_platforms_metabox( $post ) {
	$section   = sln_wd_admin_get_section( $post->ID, SLN_WD_PLATFORMS_SECTION_META, sln_wd_default_platforms_section() );
	$platforms = sln_wd_admin_get_rows( $post->ID, SLN_WD_PLATFORMS_META, sln_wd_default_platforms() );
	?>
	<table class="form-table sln-wd-admin__table" role="presentation">
		<?php
		sln_wd_admin_text_field( __( 'Small Heading', 'smart-leading-net' ), 'sln_wd_platforms_section[small_heading]', $section['small_heading'] );
		sln_wd_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_wd_platforms_section[main_heading]', $section['main_heading'] );
		sln_wd_admin_text_field( __( 'Highlighted Text', 'smart-leading-net' ), 'sln_wd_platforms_section[highlighted_text]', $section['highlighted_text'] );
		sln_wd_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-wd-platforms-desc', 'sln_wd_platforms_section[description]', $section['description'] );
		sln_wd_admin_text_field( __( 'Bottom Note', 'smart-leading-net' ), 'sln_wd_platforms_section[bottom_note]', $section['bottom_note'] );
		sln_wd_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_wd_platforms_section[active]', ! empty( $section['active'] ) );
		?>
	</table>

	<div class="sln-wd-admin__repeatable" data-row-selector=".sln-wd-admin__platform-row" data-name-prefix="sln_wd_platforms">
		<h3><?php esc_html_e( 'Platform Cards', 'smart-leading-net' ); ?></h3>
		<div class="sln-wd-admin__repeatable-list">
			<?php foreach ( $platforms as $index => $platform ) : ?>
				<div class="sln-wd-admin__repeatable-row sln-wd-admin__platform-row">
					<div class="sln-wd-admin__row-head">
						<strong><?php echo esc_html( $platform['title'] ?: __( 'Platform', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-wd-admin__row-actions">
							<button type="button" class="button sln-wd-admin__move-up">&#8593;</button>
							<button type="button" class="button sln-wd-admin__move-down">&#8595;</button>
							<button type="button" class="button-link-delete sln-wd-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_wd_admin_media_field( __( 'Icon', 'smart-leading-net' ), 'sln_wd_platforms[' . $index . '][icon_id]', absint( $platform['icon_id'] ?? 0 ) );
						sln_wd_admin_text_field( __( 'Title', 'smart-leading-net' ), 'sln_wd_platforms[' . $index . '][title]', $platform['title'] ?? '' );
						sln_wd_admin_textarea_field( __( 'Description', 'smart-leading-net' ), 'sln_wd_platforms[' . $index . '][description]', $platform['description'] ?? '' );
						sln_wd_admin_url_field( __( 'URL', 'smart-leading-net' ), 'sln_wd_platforms[' . $index . '][url]', $platform['url'] ?? '' );
						sln_wd_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_wd_platforms[' . $index . '][active]', ! empty( $platform['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_wd_admin_repeater_toolbar( __( 'Add Platform', 'smart-leading-net' ) ); ?>
	</div>
	<?php
}

/**
 * Render helpful extras metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_wd_render_extras_metabox( $post ) {
	$section = sln_wd_admin_get_section( $post->ID, SLN_WD_EXTRAS_SECTION_META, sln_wd_default_extras_section() );
	$items   = sln_wd_admin_get_rows( $post->ID, SLN_WD_EXTRAS_META, sln_wd_default_extras() );
	?>
	<table class="form-table sln-wd-admin__table" role="presentation">
		<?php
		sln_wd_admin_text_field( __( 'Heading', 'smart-leading-net' ), 'sln_wd_extras_section[heading]', $section['heading'] );
		sln_wd_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-wd-extras-desc', 'sln_wd_extras_section[description]', $section['description'] );
		sln_wd_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_wd_extras_section[active]', ! empty( $section['active'] ) );
		?>
	</table>

	<div class="sln-wd-admin__repeatable" data-row-selector=".sln-wd-admin__extra-row" data-name-prefix="sln_wd_extras">
		<h3><?php esc_html_e( 'Extra Items', 'smart-leading-net' ); ?></h3>
		<div class="sln-wd-admin__repeatable-list">
			<?php foreach ( $items as $index => $item ) : ?>
				<div class="sln-wd-admin__repeatable-row sln-wd-admin__extra-row">
					<div class="sln-wd-admin__row-head">
						<strong><?php echo esc_html( $item['title'] ?: __( 'Extra Item', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-wd-admin__row-actions">
							<button type="button" class="button sln-wd-admin__move-up">&#8593;</button>
							<button type="button" class="button sln-wd-admin__move-down">&#8595;</button>
							<button type="button" class="button-link-delete sln-wd-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_wd_admin_media_field( __( 'Icon', 'smart-leading-net' ), 'sln_wd_extras[' . $index . '][icon_id]', absint( $item['icon_id'] ?? 0 ) );
						sln_wd_admin_text_field( __( 'Title', 'smart-leading-net' ), 'sln_wd_extras[' . $index . '][title]', $item['title'] ?? '' );
						sln_wd_admin_textarea_field( __( 'Description', 'smart-leading-net' ), 'sln_wd_extras[' . $index . '][description]', $item['description'] ?? '' );
						sln_wd_admin_url_field( __( 'URL', 'smart-leading-net' ), 'sln_wd_extras[' . $index . '][url]', $item['url'] ?? '' );
						sln_wd_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_wd_extras[' . $index . '][active]', ! empty( $item['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_wd_admin_repeater_toolbar( __( 'Add Extra Item', 'smart-leading-net' ) ); ?>
	</div>
	<?php
}

/**
 * Render why it matters metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_wd_render_matters_metabox( $post ) {
	$section = sln_wd_admin_get_section( $post->ID, SLN_WD_MATTERS_SECTION_META, sln_wd_default_matters_section() );
	$cards   = sln_wd_admin_get_rows( $post->ID, SLN_WD_MATTERS_CARDS_META, sln_wd_default_matters_cards() );
	?>
	<table class="form-table sln-wd-admin__table" role="presentation">
		<?php
		sln_wd_admin_text_field( __( 'Small Heading', 'smart-leading-net' ), 'sln_wd_matters_section[small_heading]', $section['small_heading'] );
		sln_wd_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_wd_matters_section[main_heading]', $section['main_heading'] );
		sln_wd_admin_text_field( __( 'Highlighted Text', 'smart-leading-net' ), 'sln_wd_matters_section[highlighted_text]', $section['highlighted_text'] );
		sln_wd_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-wd-matters-desc', 'sln_wd_matters_section[description]', $section['description'] );
		sln_wd_admin_media_field( __( 'Section Image', 'smart-leading-net' ), 'sln_wd_matters_section[image_id]', absint( $section['image_id'] ?? 0 ) );
		sln_wd_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_wd_matters_section[active]', ! empty( $section['active'] ) );
		?>
	</table>

	<div class="sln-wd-admin__repeatable" data-row-selector=".sln-wd-admin__matters-card-row" data-name-prefix="sln_wd_matters_cards">
		<h3><?php esc_html_e( 'Feature Cards', 'smart-leading-net' ); ?></h3>
		<div class="sln-wd-admin__repeatable-list">
			<?php foreach ( $cards as $index => $card ) : ?>
				<div class="sln-wd-admin__repeatable-row sln-wd-admin__matters-card-row">
					<div class="sln-wd-admin__row-head">
						<strong><?php echo esc_html( $card['title'] ?: __( 'Feature Card', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-wd-admin__row-actions">
							<button type="button" class="button sln-wd-admin__move-up">&#8593;</button>
							<button type="button" class="button sln-wd-admin__move-down">&#8595;</button>
							<button type="button" class="button-link-delete sln-wd-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_wd_admin_media_field( __( 'Icon', 'smart-leading-net' ), 'sln_wd_matters_cards[' . $index . '][icon_id]', absint( $card['icon_id'] ?? 0 ) );
						sln_wd_admin_text_field( __( 'Title', 'smart-leading-net' ), 'sln_wd_matters_cards[' . $index . '][title]', $card['title'] ?? '' );
						sln_wd_admin_textarea_field( __( 'Description', 'smart-leading-net' ), 'sln_wd_matters_cards[' . $index . '][description]', $card['description'] ?? '' );
						sln_wd_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_wd_matters_cards[' . $index . '][active]', ! empty( $card['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_wd_admin_repeater_toolbar( __( 'Add Feature Card', 'smart-leading-net' ) ); ?>
	</div>
	<?php
}

/**
 * Render process metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_wd_render_process_metabox( $post ) {
	$section = sln_wd_admin_get_section( $post->ID, SLN_WD_PROCESS_SECTION_META, sln_wd_default_process_section() );
	$stages  = sln_wd_admin_get_rows( $post->ID, SLN_WD_PROCESS_STAGES_META, sln_wd_default_process_stages() );
	?>
	<table class="form-table sln-wd-admin__table" role="presentation">
		<?php
		sln_wd_admin_text_field( __( 'Small Heading', 'smart-leading-net' ), 'sln_wd_process_section[small_heading]', $section['small_heading'] );
		sln_wd_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_wd_process_section[main_heading]', $section['main_heading'] );
		sln_wd_admin_text_field( __( 'Highlighted Text', 'smart-leading-net' ), 'sln_wd_process_section[highlighted_text]', $section['highlighted_text'] );
		sln_wd_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-wd-process-desc', 'sln_wd_process_section[description]', $section['description'] );
		sln_wd_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_wd_process_section[active]', ! empty( $section['active'] ) );
		?>
	</table>

	<div class="sln-wd-admin__repeatable" data-row-selector=".sln-wd-admin__process-stage-row" data-name-prefix="sln_wd_process_stages">
		<h3><?php esc_html_e( 'Process Stages', 'smart-leading-net' ); ?></h3>
		<div class="sln-wd-admin__repeatable-list">
			<?php foreach ( $stages as $index => $stage ) : ?>
				<div class="sln-wd-admin__repeatable-row sln-wd-admin__process-stage-row">
					<div class="sln-wd-admin__row-head">
						<strong><?php echo esc_html( $stage['title'] ?: __( 'Process Stage', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-wd-admin__row-actions">
							<button type="button" class="button sln-wd-admin__move-up">&#8593;</button>
							<button type="button" class="button sln-wd-admin__move-down">&#8595;</button>
							<button type="button" class="button-link-delete sln-wd-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_wd_admin_text_field( __( 'Stage Number', 'smart-leading-net' ), 'sln_wd_process_stages[' . $index . '][number]', $stage['number'] ?? '' );
						sln_wd_admin_text_field( __( 'Stage Title', 'smart-leading-net' ), 'sln_wd_process_stages[' . $index . '][title]', $stage['title'] ?? '' );
						?>
						<tr>
							<th scope="row"><?php esc_html_e( 'Steps', 'smart-leading-net' ); ?></th>
							<td><?php sln_wd_admin_render_process_steps( 'sln_wd_process_stages[' . $index . '][steps]', $stage['steps'] ?? array() ); ?></td>
						</tr>
						<?php sln_wd_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_wd_process_stages[' . $index . '][active]', ! empty( $stage['active'] ) ); ?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_wd_admin_repeater_toolbar( __( 'Add Stage', 'smart-leading-net' ) ); ?>
	</div>
	<?php
}

/**
 * Render promises metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_wd_render_promises_metabox( $post ) {
	$section  = sln_wd_admin_get_section( $post->ID, SLN_WD_PROMISES_SECTION_META, sln_wd_default_promises_section() );
	$promises = sln_wd_admin_get_rows( $post->ID, SLN_WD_PROMISES_META, sln_wd_default_promises() );
	?>
	<table class="form-table sln-wd-admin__table" role="presentation">
		<?php
		sln_wd_admin_text_field( __( 'Section Heading', 'smart-leading-net' ), 'sln_wd_promises_section[heading]', $section['heading'] );
		sln_wd_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_wd_promises_section[active]', ! empty( $section['active'] ) );
		?>
	</table>

	<div class="sln-wd-admin__repeatable" data-row-selector=".sln-wd-admin__promise-row" data-name-prefix="sln_wd_promises">
		<h3><?php esc_html_e( 'Promise Cards', 'smart-leading-net' ); ?></h3>
		<div class="sln-wd-admin__repeatable-list">
			<?php foreach ( $promises as $index => $promise ) : ?>
				<div class="sln-wd-admin__repeatable-row sln-wd-admin__promise-row">
					<div class="sln-wd-admin__row-head">
						<strong><?php echo esc_html( $promise['main_text'] ?: __( 'Promise', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-wd-admin__row-actions">
							<button type="button" class="button sln-wd-admin__move-up">&#8593;</button>
							<button type="button" class="button sln-wd-admin__move-down">&#8595;</button>
							<button type="button" class="button-link-delete sln-wd-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_wd_admin_media_field( __( 'Icon', 'smart-leading-net' ), 'sln_wd_promises[' . $index . '][icon_id]', absint( $promise['icon_id'] ?? 0 ) );
						sln_wd_admin_text_field( __( 'Main Text', 'smart-leading-net' ), 'sln_wd_promises[' . $index . '][main_text]', $promise['main_text'] ?? '' );
						sln_wd_admin_text_field( __( 'Supporting Text', 'smart-leading-net' ), 'sln_wd_promises[' . $index . '][supporting_text]', $promise['supporting_text'] ?? '' );
						sln_wd_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_wd_promises[' . $index . '][active]', ! empty( $promise['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_wd_admin_repeater_toolbar( __( 'Add Promise', 'smart-leading-net' ) ); ?>
	</div>
	<?php
}

/**
 * Render services metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_wd_render_services_metabox( $post ) {
	$section  = sln_wd_admin_get_section( $post->ID, SLN_WD_SERVICES_SECTION_META, sln_wd_default_services_section() );
	$services = sln_wd_admin_get_rows( $post->ID, SLN_WD_SERVICES_META, sln_wd_default_services() );
	?>
	<table class="form-table sln-wd-admin__table" role="presentation">
		<?php
		sln_wd_admin_text_field( __( 'Small Heading', 'smart-leading-net' ), 'sln_wd_services_section[small_heading]', $section['small_heading'] );
		sln_wd_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_wd_services_section[main_heading]', $section['main_heading'] );
		sln_wd_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-wd-services-desc', 'sln_wd_services_section[description]', $section['description'] );
		sln_wd_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_wd_services_section[active]', ! empty( $section['active'] ) );
		?>
	</table>

	<div class="sln-wd-admin__repeatable" data-row-selector=".sln-wd-admin__service-row" data-name-prefix="sln_wd_services">
		<h3><?php esc_html_e( 'Service Cards', 'smart-leading-net' ); ?></h3>
		<div class="sln-wd-admin__repeatable-list">
			<?php foreach ( $services as $index => $service ) : ?>
				<div class="sln-wd-admin__repeatable-row sln-wd-admin__service-row">
					<div class="sln-wd-admin__row-head">
						<strong><?php echo esc_html( $service['title'] ?: __( 'Service', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-wd-admin__row-actions">
							<button type="button" class="button sln-wd-admin__move-up">&#8593;</button>
							<button type="button" class="button sln-wd-admin__move-down">&#8595;</button>
							<button type="button" class="button-link-delete sln-wd-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_wd_admin_media_field( __( 'Icon', 'smart-leading-net' ), 'sln_wd_services[' . $index . '][icon_id]', absint( $service['icon_id'] ?? 0 ) );
						sln_wd_admin_text_field( __( 'Title', 'smart-leading-net' ), 'sln_wd_services[' . $index . '][title]', $service['title'] ?? '' );
						sln_wd_admin_textarea_field( __( 'Description', 'smart-leading-net' ), 'sln_wd_services[' . $index . '][description]', $service['description'] ?? '' );
						sln_wd_admin_url_field( __( 'URL', 'smart-leading-net' ), 'sln_wd_services[' . $index . '][url]', $service['url'] ?? '' );
						sln_wd_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_wd_services[' . $index . '][active]', ! empty( $service['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_wd_admin_repeater_toolbar( __( 'Add Service', 'smart-leading-net' ) ); ?>
	</div>
	<?php
}

/**
 * Render comparison metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_wd_render_compare_metabox( $post ) {
	$section = sln_wd_admin_get_section( $post->ID, SLN_WD_COMPARE_SECTION_META, sln_wd_default_compare_section() );
	$rows    = sln_wd_admin_get_rows( $post->ID, SLN_WD_COMPARE_ROWS_META, sln_wd_default_compare_rows() );
	?>
	<table class="form-table sln-wd-admin__table" role="presentation">
		<?php
		sln_wd_admin_text_field( __( 'Small Heading', 'smart-leading-net' ), 'sln_wd_compare_section[small_heading]', $section['small_heading'] );
		sln_wd_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_wd_compare_section[main_heading]', $section['main_heading'] );
		sln_wd_admin_text_field( __( 'Highlighted Text', 'smart-leading-net' ), 'sln_wd_compare_section[highlighted_text]', $section['highlighted_text'] );
		sln_wd_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-wd-compare-desc', 'sln_wd_compare_section[description]', $section['description'] );
		sln_wd_admin_text_field( __( 'Features Column Heading', 'smart-leading-net' ), 'sln_wd_compare_section[col_features]', $section['col_features'] );
		sln_wd_admin_text_field( __( 'Smart Leading Column Heading', 'smart-leading-net' ), 'sln_wd_compare_section[col_sl]', $section['col_sl'] );
		sln_wd_admin_text_field( __( 'In-house Column Heading', 'smart-leading-net' ), 'sln_wd_compare_section[col_inhouse]', $section['col_inhouse'] );
		sln_wd_admin_text_field( __( 'Agency Column Heading', 'smart-leading-net' ), 'sln_wd_compare_section[col_agency]', $section['col_agency'] );
		sln_wd_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_wd_compare_section[active]', ! empty( $section['active'] ) );
		?>
	</table>

	<div class="sln-wd-admin__repeatable" data-row-selector=".sln-wd-admin__compare-row" data-name-prefix="sln_wd_compare_rows">
		<h3><?php esc_html_e( 'Comparison Rows', 'smart-leading-net' ); ?></h3>
		<div class="sln-wd-admin__repeatable-list">
			<?php foreach ( $rows as $index => $row ) : ?>
				<div class="sln-wd-admin__repeatable-row sln-wd-admin__compare-row">
					<div class="sln-wd-admin__row-head">
						<strong><?php echo esc_html( $row['feature'] ?: __( 'Comparison Row', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-wd-admin__row-actions">
							<button type="button" class="button sln-wd-admin__move-up">&#8593;</button>
							<button type="button" class="button sln-wd-admin__move-down">&#8595;</button>
							<button type="button" class="button-link-delete sln-wd-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_wd_admin_text_field( __( 'Feature', 'smart-leading-net' ), 'sln_wd_compare_rows[' . $index . '][feature]', $row['feature'] ?? '' );
						sln_wd_admin_text_field( __( 'Smart Leading', 'smart-leading-net' ), 'sln_wd_compare_rows[' . $index . '][smart_leading]', $row['smart_leading'] ?? '' );
						sln_wd_admin_text_field( __( 'In-house', 'smart-leading-net' ), 'sln_wd_compare_rows[' . $index . '][in_house]', $row['in_house'] ?? '' );
						sln_wd_admin_text_field( __( 'Agency', 'smart-leading-net' ), 'sln_wd_compare_rows[' . $index . '][agency]', $row['agency'] ?? '' );
						sln_wd_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_wd_compare_rows[' . $index . '][active]', ! empty( $row['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_wd_admin_repeater_toolbar( __( 'Add Comparison Row', 'smart-leading-net' ) ); ?>
	</div>
	<?php
}

/**
 * Render summary benefits metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_wd_render_benefits_metabox( $post ) {
	$section  = sln_wd_admin_get_section( $post->ID, SLN_WD_BENEFITS_SECTION_META, sln_wd_default_benefits_section() );
	$benefits = sln_wd_admin_get_rows( $post->ID, SLN_WD_BENEFITS_META, sln_wd_default_benefits() );
	?>
	<table class="form-table sln-wd-admin__table" role="presentation">
		<?php sln_wd_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_wd_benefits_section[active]', ! empty( $section['active'] ) ); ?>
	</table>

	<div class="sln-wd-admin__repeatable" data-row-selector=".sln-wd-admin__benefit-row" data-name-prefix="sln_wd_benefits">
		<h3><?php esc_html_e( 'Benefit Cards', 'smart-leading-net' ); ?></h3>
		<div class="sln-wd-admin__repeatable-list">
			<?php foreach ( $benefits as $index => $benefit ) : ?>
				<div class="sln-wd-admin__repeatable-row sln-wd-admin__benefit-row">
					<div class="sln-wd-admin__row-head">
						<strong><?php echo esc_html( $benefit['main_text'] ?: __( 'Benefit', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-wd-admin__row-actions">
							<button type="button" class="button sln-wd-admin__move-up">&#8593;</button>
							<button type="button" class="button sln-wd-admin__move-down">&#8595;</button>
							<button type="button" class="button-link-delete sln-wd-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_wd_admin_media_field( __( 'Icon', 'smart-leading-net' ), 'sln_wd_benefits[' . $index . '][icon_id]', absint( $benefit['icon_id'] ?? 0 ) );
						sln_wd_admin_text_field( __( 'Main Text', 'smart-leading-net' ), 'sln_wd_benefits[' . $index . '][main_text]', $benefit['main_text'] ?? '' );
						sln_wd_admin_text_field( __( 'Supporting Text', 'smart-leading-net' ), 'sln_wd_benefits[' . $index . '][supporting_text]', $benefit['supporting_text'] ?? '' );
						sln_wd_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_wd_benefits[' . $index . '][active]', ! empty( $benefit['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_wd_admin_repeater_toolbar( __( 'Add Benefit', 'smart-leading-net' ) ); ?>
	</div>
	<?php
}

/**
 * Render case studies metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_wd_render_cases_metabox( $post ) {
	$section = sln_wd_admin_get_section( $post->ID, SLN_WD_CASES_SECTION_META, sln_wd_default_cases_section() );
	$cases   = sln_wd_admin_get_rows( $post->ID, SLN_WD_CASES_META, sln_wd_default_cases() );
	?>
	<table class="form-table sln-wd-admin__table" role="presentation">
		<?php
		sln_wd_admin_text_field( __( 'Small Heading', 'smart-leading-net' ), 'sln_wd_cases_section[small_heading]', $section['small_heading'] );
		sln_wd_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_wd_cases_section[main_heading]', $section['main_heading'] );
		sln_wd_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-wd-cases-desc', 'sln_wd_cases_section[description]', $section['description'] );
		sln_wd_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_wd_cases_section[active]', ! empty( $section['active'] ) );
		?>
	</table>

	<div class="sln-wd-admin__repeatable" data-row-selector=".sln-wd-admin__case-row" data-name-prefix="sln_wd_cases">
		<h3><?php esc_html_e( 'Case Studies', 'smart-leading-net' ); ?></h3>
		<div class="sln-wd-admin__repeatable-list">
			<?php foreach ( $cases as $index => $case ) : ?>
				<div class="sln-wd-admin__repeatable-row sln-wd-admin__case-row">
					<div class="sln-wd-admin__row-head">
						<strong><?php echo esc_html( $case['client_name'] ?: __( 'Case Study', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-wd-admin__row-actions">
							<button type="button" class="button sln-wd-admin__move-up">&#8593;</button>
							<button type="button" class="button sln-wd-admin__move-down">&#8595;</button>
							<button type="button" class="button-link-delete sln-wd-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_wd_admin_media_field( __( 'Project Image', 'smart-leading-net' ), 'sln_wd_cases[' . $index . '][image_id]', absint( $case['image_id'] ?? 0 ) );
						sln_wd_admin_text_field( __( 'Client Name', 'smart-leading-net' ), 'sln_wd_cases[' . $index . '][client_name]', $case['client_name'] ?? '' );
						sln_wd_admin_text_field( __( 'Industry', 'smart-leading-net' ), 'sln_wd_cases[' . $index . '][industry]', $case['industry'] ?? '' );
						sln_wd_admin_text_field( __( 'Metric Value', 'smart-leading-net' ), 'sln_wd_cases[' . $index . '][metric_value]', $case['metric_value'] ?? '' );
						sln_wd_admin_textarea_field( __( 'Metric Description', 'smart-leading-net' ), 'sln_wd_cases[' . $index . '][metric_description]', $case['metric_description'] ?? '' );
						sln_wd_admin_url_field( __( 'Project URL', 'smart-leading-net' ), 'sln_wd_cases[' . $index . '][project_url]', $case['project_url'] ?? '' );
						sln_wd_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_wd_cases[' . $index . '][active]', ! empty( $case['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_wd_admin_repeater_toolbar( __( 'Add Case Study', 'smart-leading-net' ) ); ?>
	</div>
	<?php
}

/**
 * Render pricing metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_wd_render_pricing_metabox( $post ) {
	$section = sln_wd_admin_get_section( $post->ID, SLN_WD_PRICING_SECTION_META, sln_wd_default_pricing_section() );
	$plans   = sln_wd_admin_get_rows( $post->ID, SLN_WD_PRICING_PLANS_META, sln_wd_default_pricing_plans() );
	?>
	<table class="form-table sln-wd-admin__table" role="presentation">
		<?php
		sln_wd_admin_text_field( __( 'Small Heading', 'smart-leading-net' ), 'sln_wd_pricing_section[small_heading]', $section['small_heading'] );
		sln_wd_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_wd_pricing_section[main_heading]', $section['main_heading'] );
		sln_wd_admin_text_field( __( 'Highlighted Text', 'smart-leading-net' ), 'sln_wd_pricing_section[highlighted_text]', $section['highlighted_text'] ?? '' );
		sln_wd_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-wd-pricing-desc', 'sln_wd_pricing_section[description]', $section['description'] );
		sln_wd_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_wd_pricing_section[active]', ! empty( $section['active'] ) );
		?>
	</table>

	<div class="sln-wd-admin__repeatable" data-row-selector=".sln-wd-admin__pricing-row" data-name-prefix="sln_wd_pricing_plans">
		<h3><?php esc_html_e( 'Pricing Plans', 'smart-leading-net' ); ?></h3>
		<div class="sln-wd-admin__repeatable-list">
			<?php foreach ( $plans as $index => $plan ) : ?>
				<div class="sln-wd-admin__repeatable-row sln-wd-admin__pricing-row">
					<div class="sln-wd-admin__row-head">
						<strong><?php echo esc_html( $plan['name'] ?: __( 'Pricing Plan', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-wd-admin__row-actions">
							<button type="button" class="button sln-wd-admin__move-up">&#8593;</button>
							<button type="button" class="button sln-wd-admin__move-down">&#8595;</button>
							<button type="button" class="button-link-delete sln-wd-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_wd_admin_text_field( __( 'Plan Name', 'smart-leading-net' ), 'sln_wd_pricing_plans[' . $index . '][name]', $plan['name'] ?? '' );
						sln_wd_admin_text_field( __( 'Price', 'smart-leading-net' ), 'sln_wd_pricing_plans[' . $index . '][price]', $plan['price'] ?? '' );
						sln_wd_admin_text_field( __( 'Timeline', 'smart-leading-net' ), 'sln_wd_pricing_plans[' . $index . '][timeline]', $plan['timeline'] ?? '' );
						sln_wd_admin_textarea_field( __( 'Description', 'smart-leading-net' ), 'sln_wd_pricing_plans[' . $index . '][description]', $plan['description'] ?? '' );
						?>
						<tr>
							<th scope="row"><?php esc_html_e( 'Features', 'smart-leading-net' ); ?></th>
							<td><?php sln_wd_admin_render_features( 'sln_wd_pricing_plans[' . $index . '][features]', $plan['features'] ?? array() ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Most Popular', 'smart-leading-net' ); ?></th>
							<td>
								<label>
									<input type="checkbox" name="sln_wd_pricing_plans[<?php echo esc_attr( (string) $index ); ?>][is_popular]" value="1" <?php checked( ! empty( $plan['is_popular'] ) ); ?> />
									<?php esc_html_e( 'Mark as most popular', 'smart-leading-net' ); ?>
								</label>
							</td>
						</tr>
						<?php
						sln_wd_admin_text_field( __( 'Button Text', 'smart-leading-net' ), 'sln_wd_pricing_plans[' . $index . '][button_text]', $plan['button_text'] ?? '' );
						sln_wd_admin_url_field( __( 'Button URL', 'smart-leading-net' ), 'sln_wd_pricing_plans[' . $index . '][button_url]', $plan['button_url'] ?? '' );
						sln_wd_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_wd_pricing_plans[' . $index . '][active]', ! empty( $plan['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_wd_admin_repeater_toolbar( __( 'Add Pricing Plan', 'smart-leading-net' ) ); ?>
	</div>
	<?php
}

/**
 * Render care plans metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_wd_render_care_metabox( $post ) {
	$section = sln_wd_admin_get_section( $post->ID, SLN_WD_CARE_SECTION_META, sln_wd_default_care_section() );
	$plans   = sln_wd_admin_get_rows( $post->ID, SLN_WD_CARE_PLANS_META, sln_wd_default_care_plans() );
	?>
	<table class="form-table sln-wd-admin__table" role="presentation">
		<?php
		sln_wd_admin_text_field( __( 'Small Heading', 'smart-leading-net' ), 'sln_wd_care_section[small_heading]', $section['small_heading'] );
		sln_wd_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_wd_care_section[main_heading]', $section['main_heading'] );
		sln_wd_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-wd-care-desc', 'sln_wd_care_section[description]', $section['description'] );
		sln_wd_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_wd_care_section[active]', ! empty( $section['active'] ) );
		?>
	</table>

	<div class="sln-wd-admin__repeatable" data-row-selector=".sln-wd-admin__care-row" data-name-prefix="sln_wd_care_plans">
		<h3><?php esc_html_e( 'Care Plans', 'smart-leading-net' ); ?></h3>
		<div class="sln-wd-admin__repeatable-list">
			<?php foreach ( $plans as $index => $plan ) : ?>
				<div class="sln-wd-admin__repeatable-row sln-wd-admin__care-row">
					<div class="sln-wd-admin__row-head">
						<strong><?php echo esc_html( $plan['name'] ?: __( 'Care Plan', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-wd-admin__row-actions">
							<button type="button" class="button sln-wd-admin__move-up">&#8593;</button>
							<button type="button" class="button sln-wd-admin__move-down">&#8595;</button>
							<button type="button" class="button-link-delete sln-wd-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_wd_admin_text_field( __( 'Plan Name', 'smart-leading-net' ), 'sln_wd_care_plans[' . $index . '][name]', $plan['name'] ?? '' );
						sln_wd_admin_text_field( __( 'Price', 'smart-leading-net' ), 'sln_wd_care_plans[' . $index . '][price]', $plan['price'] ?? '' );
						sln_wd_admin_textarea_field( __( 'Description', 'smart-leading-net' ), 'sln_wd_care_plans[' . $index . '][description]', $plan['description'] ?? '' );
						?>
						<tr>
							<th scope="row"><?php esc_html_e( 'Features', 'smart-leading-net' ); ?></th>
							<td><?php sln_wd_admin_render_features( 'sln_wd_care_plans[' . $index . '][features]', $plan['features'] ?? array() ); ?></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e( 'Most Popular', 'smart-leading-net' ); ?></th>
							<td>
								<label>
									<input type="checkbox" name="sln_wd_care_plans[<?php echo esc_attr( (string) $index ); ?>][is_popular]" value="1" <?php checked( ! empty( $plan['is_popular'] ) ); ?> />
									<?php esc_html_e( 'Mark as most popular', 'smart-leading-net' ); ?>
								</label>
							</td>
						</tr>
						<?php
						sln_wd_admin_text_field( __( 'Button Text', 'smart-leading-net' ), 'sln_wd_care_plans[' . $index . '][button_text]', $plan['button_text'] ?? '' );
						sln_wd_admin_url_field( __( 'Button URL', 'smart-leading-net' ), 'sln_wd_care_plans[' . $index . '][button_url]', $plan['button_url'] ?? '' );
						sln_wd_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_wd_care_plans[' . $index . '][active]', ! empty( $plan['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_wd_admin_repeater_toolbar( __( 'Add Care Plan', 'smart-leading-net' ) ); ?>
	</div>
	<?php
}

/**
 * Render FAQ metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_wd_render_faq_metabox( $post ) {
	$section = sln_wd_admin_get_section( $post->ID, SLN_WD_FAQ_SECTION_META, sln_wd_default_faq_section() );
	$items   = sln_wd_admin_get_rows( $post->ID, SLN_WD_FAQ_ITEMS_META, sln_wd_default_faq_items() );
	?>
	<table class="form-table sln-wd-admin__table" role="presentation">
		<?php
		sln_wd_admin_text_field( __( 'Small Heading', 'smart-leading-net' ), 'sln_wd_faq_section[small_heading]', $section['small_heading'] );
		sln_wd_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_wd_faq_section[main_heading]', $section['main_heading'] );
		sln_wd_admin_text_field( __( 'Highlighted Text', 'smart-leading-net' ), 'sln_wd_faq_section[highlighted_text]', $section['highlighted_text'] );
		sln_wd_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-wd-faq-desc', 'sln_wd_faq_section[description]', $section['description'] );
		sln_wd_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_wd_faq_section[active]', ! empty( $section['active'] ) );
		?>
	</table>

	<div class="sln-wd-admin__repeatable" data-row-selector=".sln-wd-admin__faq-row" data-name-prefix="sln_wd_faq_items">
		<h3><?php esc_html_e( 'FAQ Items', 'smart-leading-net' ); ?></h3>
		<div class="sln-wd-admin__repeatable-list">
			<?php foreach ( $items as $index => $item ) : ?>
				<div class="sln-wd-admin__repeatable-row sln-wd-admin__faq-row">
					<div class="sln-wd-admin__row-head">
						<strong><?php echo esc_html( $item['question'] ?: __( 'FAQ Item', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-wd-admin__row-actions">
							<button type="button" class="button sln-wd-admin__move-up">&#8593;</button>
							<button type="button" class="button sln-wd-admin__move-down">&#8595;</button>
							<button type="button" class="button-link-delete sln-wd-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_wd_admin_text_field( __( 'Question', 'smart-leading-net' ), 'sln_wd_faq_items[' . $index . '][question]', $item['question'] ?? '' );
						sln_wd_admin_editor_field( __( 'Answer', 'smart-leading-net' ), 'sln-wd-faq-item-' . $index, 'sln_wd_faq_items[' . $index . '][answer]', $item['answer'] ?? '' );
						sln_wd_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_wd_faq_items[' . $index . '][active]', ! empty( $item['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_wd_admin_repeater_toolbar( __( 'Add FAQ Item', 'smart-leading-net' ) ); ?>
	</div>
	<?php
}

/**
 * Render final CTA and form metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_wd_render_final_metabox( $post ) {
	$data = sln_wd_admin_get_section( $post->ID, SLN_WD_FINAL_SECTION_META, sln_wd_default_final() );
	?>
	<table class="form-table sln-wd-admin__table" role="presentation">
		<?php
		sln_wd_admin_text_field( __( 'Small Heading', 'smart-leading-net' ), 'sln_wd_final_section[small_heading]', $data['small_heading'] );
		sln_wd_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_wd_final_section[main_heading]', $data['main_heading'] );
		sln_wd_admin_text_field( __( 'Highlighted Text', 'smart-leading-net' ), 'sln_wd_final_section[highlighted_text]', $data['highlighted_text'] );
		sln_wd_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-wd-final-desc', 'sln_wd_final_section[description]', $data['description'] );
		?>
		<tr>
			<th scope="row"><?php esc_html_e( 'Benefits', 'smart-leading-net' ); ?></th>
			<td><?php sln_wd_admin_render_benefits( 'sln_wd_final_section[benefits]', $data['benefits'] ?? array() ); ?></td>
		</tr>
		<?php
		sln_wd_admin_text_field( __( 'Form Heading', 'smart-leading-net' ), 'sln_wd_final_section[form_heading]', $data['form_heading'] );
		sln_wd_admin_text_field( __( 'Name Label', 'smart-leading-net' ), 'sln_wd_final_section[name_label]', $data['name_label'] );
		sln_wd_admin_text_field( __( 'Name Placeholder', 'smart-leading-net' ), 'sln_wd_final_section[name_placeholder]', $data['name_placeholder'] );
		sln_wd_admin_text_field( __( 'Email Label', 'smart-leading-net' ), 'sln_wd_final_section[email_label]', $data['email_label'] );
		sln_wd_admin_text_field( __( 'Email Placeholder', 'smart-leading-net' ), 'sln_wd_final_section[email_placeholder]', $data['email_placeholder'] );
		sln_wd_admin_text_field( __( 'Website Label', 'smart-leading-net' ), 'sln_wd_final_section[website_label]', $data['website_label'] );
		sln_wd_admin_text_field( __( 'Website Placeholder', 'smart-leading-net' ), 'sln_wd_final_section[website_placeholder]', $data['website_placeholder'] );
		sln_wd_admin_text_field( __( 'Country Label', 'smart-leading-net' ), 'sln_wd_final_section[country_label]', $data['country_label'] );
		sln_wd_admin_text_field( __( 'Country Placeholder', 'smart-leading-net' ), 'sln_wd_final_section[country_placeholder]', $data['country_placeholder'] );
		sln_wd_admin_textarea_list_field( __( 'Countries', 'smart-leading-net' ), 'sln_wd_final_section[countries]', $data['countries'] ?? array() );
		sln_wd_admin_text_field( __( 'Need Label', 'smart-leading-net' ), 'sln_wd_final_section[need_label]', $data['need_label'] );
		sln_wd_admin_textarea_list_field( __( 'Need Options', 'smart-leading-net' ), 'sln_wd_final_section[need_options]', $data['need_options'] ?? array() );
		sln_wd_admin_text_field( __( 'Budget Label', 'smart-leading-net' ), 'sln_wd_final_section[budget_label]', $data['budget_label'] );
		sln_wd_admin_textarea_list_field( __( 'Budget Options', 'smart-leading-net' ), 'sln_wd_final_section[budget_options]', $data['budget_options'] ?? array() );
		sln_wd_admin_text_field( __( 'Message Label', 'smart-leading-net' ), 'sln_wd_final_section[message_label]', $data['message_label'] );
		sln_wd_admin_text_field( __( 'Message Placeholder', 'smart-leading-net' ), 'sln_wd_final_section[message_placeholder]', $data['message_placeholder'] );
		sln_wd_admin_text_field( __( 'Submit Text', 'smart-leading-net' ), 'sln_wd_final_section[submit_text]', $data['submit_text'] );
		sln_wd_admin_text_field( __( 'Form Note', 'smart-leading-net' ), 'sln_wd_final_section[form_note]', $data['form_note'] );
		sln_wd_admin_url_field( __( 'Thank You URL', 'smart-leading-net' ), 'sln_wd_final_section[thank_you_url]', $data['thank_you_url'] );
		sln_wd_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_wd_final_section[active]', ! empty( $data['active'] ) );
		?>
	</table>
	<?php
}
