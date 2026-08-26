<?php
/**
 * AEO Services page — admin meta boxes.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** @var int */
$GLOBALS['sln_aeo_registered_meta_boxes'] = 0;

/**
 * Register AEO Services meta boxes.
 */
function sln_aeo_register_meta_boxes( $post_type_or_post = null, $post = null ) {
	static $registered = false;

	if ( $registered ) {
		return;
	}

	if ( $post_type_or_post instanceof WP_Post ) {
		$editing = $post_type_or_post;
	} elseif ( $post instanceof WP_Post ) {
		$editing = $post;
	} else {
		$editing = sln_aeo_admin_resolve_editing_post();
	}

	if ( $editing instanceof WP_Post && 'page' !== $editing->post_type ) {
		return;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

	if ( $screen && 'page' !== $screen->post_type && 'page' !== $screen->id ) {
		return;
	}

	if ( $editing instanceof WP_Post && $editing->ID && ! sln_aeo_admin_is_target_page( $editing ) ) {
		return;
	}

	$callbacks = array(
		'sln_aeo_hero'       => 'sln_aeo_render_hero_metabox',
		'sln_aeo_evolution'  => 'sln_aeo_render_evolution_metabox',
		'sln_aeo_why'        => 'sln_aeo_render_why_metabox',
		'sln_aeo_strategy'   => 'sln_aeo_render_strategy_metabox',
		'sln_aeo_services'   => 'sln_aeo_render_services_metabox',
		'sln_aeo_how'        => 'sln_aeo_render_how_metabox',
		'sln_aeo_comparison' => 'sln_aeo_render_comparison_metabox',
		'sln_aeo_platforms'  => 'sln_aeo_render_platforms_metabox',
		'sln_aeo_industries' => 'sln_aeo_render_industries_metabox',
		'sln_aeo_results'    => 'sln_aeo_render_results_metabox',
		'sln_aeo_faq'        => 'sln_aeo_render_faq_metabox',
		'sln_aeo_final_cta'  => 'sln_aeo_render_final_cta_metabox',
	);

	$titles = array(
		'sln_aeo_hero'       => __( 'Section 1 — Hero', 'smart-leading-net' ),
		'sln_aeo_evolution'  => __( 'Section 2 — Search Evolution', 'smart-leading-net' ),
		'sln_aeo_why'        => __( 'Section 3 — Why AEO Matters', 'smart-leading-net' ),
		'sln_aeo_strategy'   => __( 'Section 4 — AEO Strategy', 'smart-leading-net' ),
		'sln_aeo_services'   => __( 'Section 5 — AEO Services', 'smart-leading-net' ),
		'sln_aeo_how'        => __( 'Section 6 — How AEO Works', 'smart-leading-net' ),
		'sln_aeo_comparison' => __( 'Section 7 — Traditional SEO vs AEO', 'smart-leading-net' ),
		'sln_aeo_platforms'  => __( 'Section 8 — AEO Platforms', 'smart-leading-net' ),
		'sln_aeo_industries' => __( 'Section 9 — Industries', 'smart-leading-net' ),
		'sln_aeo_results'    => __( 'Section 10 — Results', 'smart-leading-net' ),
		'sln_aeo_faq'        => __( 'Section 11 — FAQ', 'smart-leading-net' ),
		'sln_aeo_final_cta'  => __( 'Section 12 — Final CTA', 'smart-leading-net' ),
	);

	foreach ( $titles as $id => $title ) {
		add_meta_box(
			$id,
			$title,
			$callbacks[ $id ],
			'page',
			'normal',
			'high',
			array(
				'__block_editor_compatible_meta_box' => true,
			)
		);
		++$GLOBALS['sln_aeo_registered_meta_boxes'];
	}

	$registered = true;
}
add_action( 'add_meta_boxes', 'sln_aeo_register_meta_boxes' );
add_action( 'add_meta_boxes_page', 'sln_aeo_register_meta_boxes' );

/**
 * Enqueue admin assets on AEO page edit screen.
 *
 * @param string $hook Current admin hook.
 */
function sln_aeo_enqueue_admin_assets( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}

	$screen = get_current_screen();

	if ( ! $screen || 'page' !== $screen->post_type ) {
		return;
	}

	$post = sln_aeo_admin_resolve_editing_post();

	wp_enqueue_script( 'jquery-ui-sortable' );

	wp_enqueue_style(
		'sln-aeo-admin',
		SLN_THEME_URI . '/assets/css/aeo-admin.css',
		array(),
		SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'sln-aeo-admin',
		SLN_THEME_URI . '/assets/js/aeo-admin.js',
		array( 'jquery', 'jquery-ui-sortable' ),
		SLN_THEME_VERSION,
		true
	);

	wp_localize_script(
		'sln-aeo-admin',
		'slnAeoAdmin',
		array(
			'template'        => SLN_AEO_TEMPLATE,
			'currentTemplate' => ( $post instanceof WP_Post ) ? get_page_template_slug( $post->ID ) : '',
			'isTargetPage'    => ( $post instanceof WP_Post ) ? sln_aeo_admin_is_target_page( $post ) : false,
		)
	);
}
add_action( 'admin_enqueue_scripts', 'sln_aeo_enqueue_admin_assets' );

/**
 * Point editors to the AEO section fields below the block editor.
 */
function sln_aeo_admin_editor_notice() {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

	if ( ! $screen || 'page' !== $screen->post_type || ! in_array( $screen->base, array( 'post', 'post-new' ), true ) ) {
		return;
	}

	if ( ! sln_aeo_admin_is_target_page() ) {
		return;
	}

	echo '<div class="notice notice-info"><p><strong>';
	esc_html_e( 'AEO Services fields:', 'smart-leading-net' );
	echo '</strong> ';
	esc_html_e( 'Section editors for Hero, Search Evolution, Why AEO Matters, Strategy, Services, How AEO Works, Comparison, Platforms, Industries, Results, FAQ, and Final CTA are below the page editor.', 'smart-leading-net' );
	echo '</p></div>';
}
add_action( 'admin_notices', 'sln_aeo_admin_editor_notice' );

/**
 * Render hero metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_aeo_render_hero_metabox( $post ) {
	if ( function_exists( 'sln_aeo_print_save_nonce' ) ) {
		sln_aeo_print_save_nonce();
	}

	$data  = sln_aeo_admin_get_section( $post->ID, SLN_AEO_HERO_META, sln_aeo_default_hero() );
	$proof = sln_aeo_admin_get_rows( $post->ID, SLN_AEO_HERO_PROOF_META, sln_aeo_default_hero_proof() );
	?>
	<table class="form-table sln-aeo-admin__table" role="presentation">
		<?php
		sln_aeo_admin_text_field( __( 'Eyebrow', 'smart-leading-net' ), 'sln_aeo_hero[eyebrow]', $data['eyebrow'] );
		sln_aeo_admin_text_field( __( 'Heading', 'smart-leading-net' ), 'sln_aeo_hero[heading]', $data['heading'] );
		sln_aeo_admin_text_field( __( 'Accent heading text', 'smart-leading-net' ), 'sln_aeo_hero[accent]', $data['accent'] );
		sln_aeo_admin_text_field( __( 'Heading suffix', 'smart-leading-net' ), 'sln_aeo_hero[heading_suffix]', $data['heading_suffix'] );
		sln_aeo_admin_textarea_field( __( 'Description', 'smart-leading-net' ), 'sln_aeo_hero[description]', $data['description'] );
		sln_aeo_admin_text_field( __( 'Primary CTA text', 'smart-leading-net' ), 'sln_aeo_hero[primary_cta_text]', $data['primary_cta_text'] );
		sln_aeo_admin_url_field( __( 'Primary CTA URL', 'smart-leading-net' ), 'sln_aeo_hero[primary_cta_url]', $data['primary_cta_url'] );
		sln_aeo_admin_text_field( __( 'Secondary CTA text', 'smart-leading-net' ), 'sln_aeo_hero[secondary_cta_text]', $data['secondary_cta_text'] );
		sln_aeo_admin_url_field( __( 'Secondary CTA URL', 'smart-leading-net' ), 'sln_aeo_hero[secondary_cta_url]', $data['secondary_cta_url'] );
		?>
	</table>

	<div class="sln-aeo-admin__repeatable" data-row-selector=".sln-aeo-admin__proof-row" data-name-prefix="sln_aeo_hero_proof">
		<h3><?php esc_html_e( 'Proof items', 'smart-leading-net' ); ?></h3>
		<div class="sln-aeo-admin__repeatable-list">
			<?php foreach ( $proof as $index => $item ) : ?>
				<div class="sln-aeo-admin__repeatable-row sln-aeo-admin__proof-row">
					<div class="sln-aeo-admin__row-head">
						<strong><?php echo esc_html( $item['label'] ?: __( 'Proof item', 'smart-leading-net' ) ); ?></strong>
						<?php sln_aeo_admin_row_actions(); ?>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_aeo_admin_text_field( __( 'Label', 'smart-leading-net' ), 'sln_aeo_hero_proof[' . $index . '][label]', $item['label'] ?? '' );
						sln_aeo_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_aeo_hero_proof[' . $index . '][active]', ! empty( $item['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_aeo_admin_repeater_toolbar( __( 'Add proof item', 'smart-leading-net' ), 'sln_aeo_hero_proof' ); ?>
	</div>
	<?php
}

/**
 * Render search evolution metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_aeo_render_evolution_metabox( $post ) {
	$section = sln_aeo_admin_get_section( $post->ID, SLN_AEO_EVOLUTION_SECTION_META, sln_aeo_default_evolution_section() );
	$stages  = sln_aeo_admin_get_rows( $post->ID, SLN_AEO_EVOLUTION_STAGES_META, sln_aeo_default_evolution_stages() );
	?>
	<table class="form-table sln-aeo-admin__table" role="presentation">
		<?php
		sln_aeo_admin_text_field( __( 'Eyebrow', 'smart-leading-net' ), 'sln_aeo_evolution_section[eyebrow]', $section['eyebrow'] );
		sln_aeo_admin_text_field( __( 'Heading', 'smart-leading-net' ), 'sln_aeo_evolution_section[heading]', $section['heading'] );
		sln_aeo_admin_textarea_field( __( 'Description', 'smart-leading-net' ), 'sln_aeo_evolution_section[description]', $section['description'] );
		?>
	</table>

	<div class="sln-aeo-admin__repeatable" data-row-selector=".sln-aeo-admin__stage-row" data-name-prefix="sln_aeo_evolution_stages">
		<h3><?php esc_html_e( 'Stages', 'smart-leading-net' ); ?></h3>
		<div class="sln-aeo-admin__repeatable-list">
			<?php foreach ( $stages as $index => $stage ) : ?>
				<div class="sln-aeo-admin__repeatable-row sln-aeo-admin__stage-row">
					<div class="sln-aeo-admin__row-head">
						<strong><?php echo esc_html( $stage['title'] ?: __( 'Stage', 'smart-leading-net' ) ); ?></strong>
						<?php sln_aeo_admin_row_actions(); ?>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_aeo_admin_select_field( __( 'Icon', 'smart-leading-net' ), 'sln_aeo_evolution_stages[' . $index . '][icon]', $stage['icon'] ?? '', sln_aeo_admin_icon_options() );
						sln_aeo_admin_text_field( __( 'Stage label', 'smart-leading-net' ), 'sln_aeo_evolution_stages[' . $index . '][label]', $stage['label'] ?? '' );
						sln_aeo_admin_text_field( __( 'Title', 'smart-leading-net' ), 'sln_aeo_evolution_stages[' . $index . '][title]', $stage['title'] ?? '' );
						sln_aeo_admin_textarea_field( __( 'Description', 'smart-leading-net' ), 'sln_aeo_evolution_stages[' . $index . '][description]', $stage['description'] ?? '' );
						sln_aeo_admin_select_field( __( 'Style', 'smart-leading-net' ), 'sln_aeo_evolution_stages[' . $index . '][modifier]', $stage['modifier'] ?? '', sln_aeo_admin_stage_modifier_options() );
						sln_aeo_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_aeo_evolution_stages[' . $index . '][active]', ! empty( $stage['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_aeo_admin_repeater_toolbar( __( 'Add stage', 'smart-leading-net' ), 'sln_aeo_evolution_stages' ); ?>
	</div>
	<?php
}

/**
 * Render why AEO matters metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_aeo_render_why_metabox( $post ) {
	$section = sln_aeo_admin_get_section( $post->ID, SLN_AEO_WHY_SECTION_META, sln_aeo_default_why_section() );
	$cards   = sln_aeo_admin_get_rows( $post->ID, SLN_AEO_WHY_CARDS_META, sln_aeo_default_why_cards() );
	?>
	<table class="form-table sln-aeo-admin__table" role="presentation">
		<?php
		sln_aeo_admin_text_field( __( 'Eyebrow', 'smart-leading-net' ), 'sln_aeo_why_section[eyebrow]', $section['eyebrow'] );
		sln_aeo_admin_text_field( __( 'Heading', 'smart-leading-net' ), 'sln_aeo_why_section[heading]', $section['heading'] );
		sln_aeo_admin_textarea_field( __( 'Description', 'smart-leading-net' ), 'sln_aeo_why_section[description]', $section['description'] );
		?>
	</table>

	<div class="sln-aeo-admin__repeatable" data-row-selector=".sln-aeo-admin__why-row" data-name-prefix="sln_aeo_why_cards">
		<h3><?php esc_html_e( 'Cards', 'smart-leading-net' ); ?></h3>
		<div class="sln-aeo-admin__repeatable-list">
			<?php foreach ( $cards as $index => $card ) : ?>
				<div class="sln-aeo-admin__repeatable-row sln-aeo-admin__why-row">
					<div class="sln-aeo-admin__row-head">
						<strong><?php echo esc_html( $card['title'] ?: __( 'Card', 'smart-leading-net' ) ); ?></strong>
						<?php sln_aeo_admin_row_actions(); ?>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_aeo_admin_select_field( __( 'Icon', 'smart-leading-net' ), 'sln_aeo_why_cards[' . $index . '][icon]', $card['icon'] ?? '', sln_aeo_admin_icon_options() );
						sln_aeo_admin_text_field( __( 'Title', 'smart-leading-net' ), 'sln_aeo_why_cards[' . $index . '][title]', $card['title'] ?? '' );
						sln_aeo_admin_textarea_field( __( 'Description', 'smart-leading-net' ), 'sln_aeo_why_cards[' . $index . '][description]', $card['description'] ?? '' );
						sln_aeo_admin_checkbox_field( __( 'Layout', 'smart-leading-net' ), 'sln_aeo_why_cards[' . $index . '][wide]', ! empty( $card['wide'] ), __( 'Wide card', 'smart-leading-net' ) );
						sln_aeo_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_aeo_why_cards[' . $index . '][active]', ! empty( $card['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_aeo_admin_repeater_toolbar( __( 'Add card', 'smart-leading-net' ), 'sln_aeo_why_cards' ); ?>
	</div>
	<?php
}

/**
 * Render strategy metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_aeo_render_strategy_metabox( $post ) {
	$section = sln_aeo_admin_get_section( $post->ID, SLN_AEO_STRATEGY_SECTION_META, sln_aeo_default_strategy_section() );
	$steps   = sln_aeo_admin_get_rows( $post->ID, SLN_AEO_STRATEGY_STEPS_META, sln_aeo_default_strategy_steps() );
	?>
	<table class="form-table sln-aeo-admin__table" role="presentation">
		<?php
		sln_aeo_admin_text_field( __( 'Eyebrow', 'smart-leading-net' ), 'sln_aeo_strategy_section[eyebrow]', $section['eyebrow'] );
		sln_aeo_admin_text_field( __( 'Heading', 'smart-leading-net' ), 'sln_aeo_strategy_section[heading]', $section['heading'] );
		sln_aeo_admin_textarea_field( __( 'Description', 'smart-leading-net' ), 'sln_aeo_strategy_section[description]', $section['description'] );
		?>
	</table>

	<div class="sln-aeo-admin__repeatable" data-row-selector=".sln-aeo-admin__strategy-row" data-name-prefix="sln_aeo_strategy_steps">
		<h3><?php esc_html_e( 'Steps', 'smart-leading-net' ); ?></h3>
		<div class="sln-aeo-admin__repeatable-list">
			<?php foreach ( $steps as $index => $step ) : ?>
				<div class="sln-aeo-admin__repeatable-row sln-aeo-admin__strategy-row">
					<div class="sln-aeo-admin__row-head">
						<strong><?php echo esc_html( $step['title'] ?: __( 'Step', 'smart-leading-net' ) ); ?></strong>
						<?php sln_aeo_admin_row_actions(); ?>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_aeo_admin_text_field( __( 'Step number', 'smart-leading-net' ), 'sln_aeo_strategy_steps[' . $index . '][number]', $step['number'] ?? '' );
						sln_aeo_admin_text_field( __( 'Title', 'smart-leading-net' ), 'sln_aeo_strategy_steps[' . $index . '][title]', $step['title'] ?? '' );
						sln_aeo_admin_textarea_field( __( 'Description', 'smart-leading-net' ), 'sln_aeo_strategy_steps[' . $index . '][description]', $step['description'] ?? '' );
						sln_aeo_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_aeo_strategy_steps[' . $index . '][active]', ! empty( $step['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_aeo_admin_repeater_toolbar( __( 'Add step', 'smart-leading-net' ), 'sln_aeo_strategy_steps' ); ?>
	</div>
	<?php
}

/**
 * Render services metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_aeo_render_services_metabox( $post ) {
	$section  = sln_aeo_admin_get_section( $post->ID, SLN_AEO_SERVICES_SECTION_META, sln_aeo_default_services_section() );
	$services = sln_aeo_admin_get_rows( $post->ID, SLN_AEO_SERVICES_ITEMS_META, sln_aeo_default_services() );
	?>
	<table class="form-table sln-aeo-admin__table" role="presentation">
		<?php
		sln_aeo_admin_text_field( __( 'Eyebrow', 'smart-leading-net' ), 'sln_aeo_services_section[eyebrow]', $section['eyebrow'] );
		sln_aeo_admin_text_field( __( 'Heading', 'smart-leading-net' ), 'sln_aeo_services_section[heading]', $section['heading'] );
		sln_aeo_admin_textarea_field( __( 'Description', 'smart-leading-net' ), 'sln_aeo_services_section[description]', $section['description'] );
		?>
	</table>

	<div class="sln-aeo-admin__repeatable" data-row-selector=".sln-aeo-admin__service-row" data-name-prefix="sln_aeo_services_items">
		<h3><?php esc_html_e( 'Services', 'smart-leading-net' ); ?></h3>
		<div class="sln-aeo-admin__repeatable-list">
			<?php foreach ( $services as $index => $service ) : ?>
				<div class="sln-aeo-admin__repeatable-row sln-aeo-admin__service-row">
					<div class="sln-aeo-admin__row-head">
						<strong><?php echo esc_html( $service['title'] ?: __( 'Service', 'smart-leading-net' ) ); ?></strong>
						<?php sln_aeo_admin_row_actions(); ?>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_aeo_admin_select_field( __( 'Icon', 'smart-leading-net' ), 'sln_aeo_services_items[' . $index . '][icon]', $service['icon'] ?? '', sln_aeo_admin_icon_options() );
						sln_aeo_admin_text_field( __( 'Title', 'smart-leading-net' ), 'sln_aeo_services_items[' . $index . '][title]', $service['title'] ?? '' );
						sln_aeo_admin_textarea_field( __( 'Description', 'smart-leading-net' ), 'sln_aeo_services_items[' . $index . '][description]', $service['description'] ?? '' );
						sln_aeo_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_aeo_services_items[' . $index . '][active]', ! empty( $service['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_aeo_admin_repeater_toolbar( __( 'Add service', 'smart-leading-net' ), 'sln_aeo_services_items' ); ?>
	</div>
	<?php
}

/**
 * Render how AEO works metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_aeo_render_how_metabox( $post ) {
	$section = sln_aeo_admin_get_section( $post->ID, SLN_AEO_HOW_SECTION_META, sln_aeo_default_how_section() );
	$steps   = sln_aeo_admin_get_rows( $post->ID, SLN_AEO_HOW_STEPS_META, sln_aeo_default_flow_nodes() );
	?>
	<table class="form-table sln-aeo-admin__table" role="presentation">
		<?php
		sln_aeo_admin_text_field( __( 'Eyebrow', 'smart-leading-net' ), 'sln_aeo_how_section[eyebrow]', $section['eyebrow'] );
		sln_aeo_admin_text_field( __( 'Heading', 'smart-leading-net' ), 'sln_aeo_how_section[heading]', $section['heading'] );
		sln_aeo_admin_textarea_field( __( 'Description', 'smart-leading-net' ), 'sln_aeo_how_section[description]', $section['description'] );
		?>
	</table>

	<div class="sln-aeo-admin__repeatable" data-row-selector=".sln-aeo-admin__how-row" data-name-prefix="sln_aeo_how_steps">
		<h3><?php esc_html_e( 'Flow steps', 'smart-leading-net' ); ?></h3>
		<div class="sln-aeo-admin__repeatable-list">
			<?php foreach ( $steps as $index => $step ) : ?>
				<div class="sln-aeo-admin__repeatable-row sln-aeo-admin__how-row">
					<div class="sln-aeo-admin__row-head">
						<strong><?php echo esc_html( $step['title'] ?: __( 'Flow step', 'smart-leading-net' ) ); ?></strong>
						<?php sln_aeo_admin_row_actions(); ?>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_aeo_admin_select_field( __( 'Icon', 'smart-leading-net' ), 'sln_aeo_how_steps[' . $index . '][icon]', $step['icon'] ?? '', sln_aeo_admin_icon_options() );
						sln_aeo_admin_text_field( __( 'Title', 'smart-leading-net' ), 'sln_aeo_how_steps[' . $index . '][title]', $step['title'] ?? '' );
						sln_aeo_admin_textarea_field( __( 'Description', 'smart-leading-net' ), 'sln_aeo_how_steps[' . $index . '][description]', $step['description'] ?? '' );
						sln_aeo_admin_checkbox_field( __( 'Highlight', 'smart-leading-net' ), 'sln_aeo_how_steps[' . $index . '][highlight]', ! empty( $step['highlight'] ), __( 'Highlight this step', 'smart-leading-net' ) );
						sln_aeo_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_aeo_how_steps[' . $index . '][active]', ! empty( $step['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_aeo_admin_repeater_toolbar( __( 'Add flow step', 'smart-leading-net' ), 'sln_aeo_how_steps' ); ?>
	</div>
	<?php
}

/**
 * Render comparison metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_aeo_render_comparison_metabox( $post ) {
	$section = sln_aeo_admin_get_section( $post->ID, SLN_AEO_COMPARISON_SECTION_META, sln_aeo_default_comparison_section() );
	$rows    = sln_aeo_admin_get_rows( $post->ID, SLN_AEO_COMPARISON_ROWS_META, sln_aeo_default_comparison_rows() );
	?>
	<table class="form-table sln-aeo-admin__table" role="presentation">
		<?php
		sln_aeo_admin_text_field( __( 'Eyebrow', 'smart-leading-net' ), 'sln_aeo_comparison_section[eyebrow]', $section['eyebrow'] );
		sln_aeo_admin_text_field( __( 'Heading', 'smart-leading-net' ), 'sln_aeo_comparison_section[heading]', $section['heading'] );
		sln_aeo_admin_textarea_field( __( 'Description', 'smart-leading-net' ), 'sln_aeo_comparison_section[description]', $section['description'] );
		sln_aeo_admin_text_field( __( 'Traditional SEO heading', 'smart-leading-net' ), 'sln_aeo_comparison_section[seo_heading]', $section['seo_heading'] );
		sln_aeo_admin_text_field( __( 'AEO heading', 'smart-leading-net' ), 'sln_aeo_comparison_section[aeo_heading]', $section['aeo_heading'] );
		?>
	</table>

	<div class="sln-aeo-admin__repeatable" data-row-selector=".sln-aeo-admin__compare-row" data-name-prefix="sln_aeo_comparison_rows">
		<h3><?php esc_html_e( 'Comparison rows', 'smart-leading-net' ); ?></h3>
		<div class="sln-aeo-admin__repeatable-list">
			<?php foreach ( $rows as $index => $row ) : ?>
				<div class="sln-aeo-admin__repeatable-row sln-aeo-admin__compare-row">
					<div class="sln-aeo-admin__row-head">
						<strong><?php echo esc_html( $row['seo'] ?: __( 'Comparison row', 'smart-leading-net' ) ); ?></strong>
						<?php sln_aeo_admin_row_actions(); ?>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_aeo_admin_text_field( __( 'Traditional SEO text', 'smart-leading-net' ), 'sln_aeo_comparison_rows[' . $index . '][seo]', $row['seo'] ?? '' );
						sln_aeo_admin_text_field( __( 'AEO text', 'smart-leading-net' ), 'sln_aeo_comparison_rows[' . $index . '][aeo]', $row['aeo'] ?? '' );
						sln_aeo_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_aeo_comparison_rows[' . $index . '][active]', ! empty( $row['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_aeo_admin_repeater_toolbar( __( 'Add comparison row', 'smart-leading-net' ), 'sln_aeo_comparison_rows' ); ?>
	</div>
	<?php
}

/**
 * Render platforms metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_aeo_render_platforms_metabox( $post ) {
	$section   = sln_aeo_admin_get_section( $post->ID, SLN_AEO_PLATFORMS_SECTION_META, sln_aeo_default_platforms_section() );
	$platforms = sln_aeo_admin_get_rows( $post->ID, SLN_AEO_PLATFORM_ITEMS_META, sln_aeo_default_platform_items() );
	?>
	<table class="form-table sln-aeo-admin__table" role="presentation">
		<?php
		sln_aeo_admin_text_field( __( 'Eyebrow', 'smart-leading-net' ), 'sln_aeo_platforms_section[eyebrow]', $section['eyebrow'] );
		sln_aeo_admin_text_field( __( 'Heading', 'smart-leading-net' ), 'sln_aeo_platforms_section[heading]', $section['heading'] );
		?>
	</table>

	<div class="sln-aeo-admin__repeatable" data-row-selector=".sln-aeo-admin__platform-row" data-name-prefix="sln_aeo_platform_items">
		<h3><?php esc_html_e( 'Platforms', 'smart-leading-net' ); ?></h3>
		<div class="sln-aeo-admin__repeatable-list">
			<?php foreach ( $platforms as $index => $platform ) : ?>
				<div class="sln-aeo-admin__repeatable-row sln-aeo-admin__platform-row">
					<div class="sln-aeo-admin__row-head">
						<strong><?php echo esc_html( $platform['name'] ?: __( 'Platform', 'smart-leading-net' ) ); ?></strong>
						<?php sln_aeo_admin_row_actions(); ?>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_aeo_admin_text_field( __( 'Platform name', 'smart-leading-net' ), 'sln_aeo_platform_items[' . $index . '][name]', $platform['name'] ?? '' );
						sln_aeo_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_aeo_platform_items[' . $index . '][active]', ! empty( $platform['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_aeo_admin_repeater_toolbar( __( 'Add platform', 'smart-leading-net' ), 'sln_aeo_platform_items' ); ?>
	</div>
	<?php
}

/**
 * Render industries metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_aeo_render_industries_metabox( $post ) {
	$section    = sln_aeo_admin_get_section( $post->ID, SLN_AEO_INDUSTRIES_SECTION_META, sln_aeo_default_industries_section() );
	$industries = sln_aeo_admin_get_rows( $post->ID, SLN_AEO_INDUSTRY_ITEMS_META, sln_aeo_default_industries() );
	?>
	<table class="form-table sln-aeo-admin__table" role="presentation">
		<?php
		sln_aeo_admin_text_field( __( 'Eyebrow', 'smart-leading-net' ), 'sln_aeo_industries_section[eyebrow]', $section['eyebrow'] );
		sln_aeo_admin_text_field( __( 'Heading', 'smart-leading-net' ), 'sln_aeo_industries_section[heading]', $section['heading'] );
		?>
	</table>

	<div class="sln-aeo-admin__repeatable" data-row-selector=".sln-aeo-admin__industry-row" data-name-prefix="sln_aeo_industry_items">
		<h3><?php esc_html_e( 'Industries', 'smart-leading-net' ); ?></h3>
		<div class="sln-aeo-admin__repeatable-list">
			<?php foreach ( $industries as $index => $industry ) : ?>
				<div class="sln-aeo-admin__repeatable-row sln-aeo-admin__industry-row">
					<div class="sln-aeo-admin__row-head">
						<strong><?php echo esc_html( $industry['title'] ?: __( 'Industry', 'smart-leading-net' ) ); ?></strong>
						<?php sln_aeo_admin_row_actions(); ?>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_aeo_admin_select_field( __( 'Icon', 'smart-leading-net' ), 'sln_aeo_industry_items[' . $index . '][icon]', $industry['icon'] ?? '', sln_aeo_admin_icon_options() );
						sln_aeo_admin_text_field( __( 'Industry name', 'smart-leading-net' ), 'sln_aeo_industry_items[' . $index . '][title]', $industry['title'] ?? '' );
						sln_aeo_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_aeo_industry_items[' . $index . '][active]', ! empty( $industry['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_aeo_admin_repeater_toolbar( __( 'Add industry', 'smart-leading-net' ), 'sln_aeo_industry_items' ); ?>
	</div>
	<?php
}

/**
 * Render results metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_aeo_render_results_metabox( $post ) {
	$section = sln_aeo_admin_get_section( $post->ID, SLN_AEO_RESULTS_SECTION_META, sln_aeo_default_results_section() );
	$results = sln_aeo_admin_get_rows( $post->ID, SLN_AEO_RESULT_ITEMS_META, sln_aeo_default_results() );
	?>
	<table class="form-table sln-aeo-admin__table" role="presentation">
		<?php
		sln_aeo_admin_text_field( __( 'Eyebrow', 'smart-leading-net' ), 'sln_aeo_results_section[eyebrow]', $section['eyebrow'] );
		sln_aeo_admin_text_field( __( 'Heading', 'smart-leading-net' ), 'sln_aeo_results_section[heading]', $section['heading'] );
		sln_aeo_admin_textarea_field( __( 'Description', 'smart-leading-net' ), 'sln_aeo_results_section[description]', $section['description'] );
		?>
	</table>

	<div class="sln-aeo-admin__repeatable" data-row-selector=".sln-aeo-admin__result-row" data-name-prefix="sln_aeo_result_items">
		<h3><?php esc_html_e( 'Results', 'smart-leading-net' ); ?></h3>
		<div class="sln-aeo-admin__repeatable-list">
			<?php foreach ( $results as $index => $result ) : ?>
				<div class="sln-aeo-admin__repeatable-row sln-aeo-admin__result-row">
					<div class="sln-aeo-admin__row-head">
						<strong><?php echo esc_html( $result['title'] ?: __( 'Result', 'smart-leading-net' ) ); ?></strong>
						<?php sln_aeo_admin_row_actions(); ?>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_aeo_admin_text_field( __( 'Title', 'smart-leading-net' ), 'sln_aeo_result_items[' . $index . '][title]', $result['title'] ?? '' );
						sln_aeo_admin_textarea_field( __( 'Description', 'smart-leading-net' ), 'sln_aeo_result_items[' . $index . '][description]', $result['description'] ?? '' );
						sln_aeo_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_aeo_result_items[' . $index . '][active]', ! empty( $result['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_aeo_admin_repeater_toolbar( __( 'Add result', 'smart-leading-net' ), 'sln_aeo_result_items' ); ?>
	</div>
	<?php
}

/**
 * Render FAQ metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_aeo_render_faq_metabox( $post ) {
	$section = sln_aeo_admin_get_section( $post->ID, SLN_AEO_FAQ_SECTION_META, sln_aeo_default_faq_section() );
	$items   = sln_aeo_admin_get_rows( $post->ID, SLN_AEO_FAQ_ITEMS_META, sln_aeo_default_faq_items() );
	?>
	<table class="form-table sln-aeo-admin__table" role="presentation">
		<?php
		sln_aeo_admin_text_field( __( 'Eyebrow', 'smart-leading-net' ), 'sln_aeo_faq_section[eyebrow]', $section['eyebrow'] );
		sln_aeo_admin_text_field( __( 'Heading', 'smart-leading-net' ), 'sln_aeo_faq_section[heading]', $section['heading'] );
		?>
	</table>

	<div class="sln-aeo-admin__repeatable" data-row-selector=".sln-aeo-admin__faq-row" data-name-prefix="sln_aeo_faq_items">
		<h3><?php esc_html_e( 'FAQs', 'smart-leading-net' ); ?></h3>
		<div class="sln-aeo-admin__repeatable-list">
			<?php foreach ( $items as $index => $item ) : ?>
				<div class="sln-aeo-admin__repeatable-row sln-aeo-admin__faq-row">
					<div class="sln-aeo-admin__row-head">
						<strong><?php echo esc_html( $item['question'] ?: __( 'FAQ', 'smart-leading-net' ) ); ?></strong>
						<?php sln_aeo_admin_row_actions(); ?>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_aeo_admin_text_field( __( 'Question', 'smart-leading-net' ), 'sln_aeo_faq_items[' . $index . '][question]', $item['question'] ?? '' );
						sln_aeo_admin_textarea_field( __( 'Answer', 'smart-leading-net' ), 'sln_aeo_faq_items[' . $index . '][answer]', $item['answer'] ?? '', 5 );
						sln_aeo_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_aeo_faq_items[' . $index . '][active]', ! empty( $item['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_aeo_admin_repeater_toolbar( __( 'Add FAQ', 'smart-leading-net' ), 'sln_aeo_faq_items' ); ?>
	</div>
	<?php
}

/**
 * Render final CTA metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_aeo_render_final_cta_metabox( $post ) {
	$data = sln_aeo_admin_get_section( $post->ID, SLN_AEO_FINAL_CTA_META, sln_aeo_default_final_cta() );
	?>
	<table class="form-table sln-aeo-admin__table" role="presentation">
		<?php
		sln_aeo_admin_text_field( __( 'Eyebrow', 'smart-leading-net' ), 'sln_aeo_final_cta[eyebrow]', $data['eyebrow'] );
		sln_aeo_admin_text_field( __( 'Heading', 'smart-leading-net' ), 'sln_aeo_final_cta[heading]', $data['heading'] );
		sln_aeo_admin_textarea_field( __( 'Description', 'smart-leading-net' ), 'sln_aeo_final_cta[description]', $data['description'] );
		sln_aeo_admin_text_field( __( 'Primary CTA text', 'smart-leading-net' ), 'sln_aeo_final_cta[primary_cta_text]', $data['primary_cta_text'] );
		sln_aeo_admin_url_field( __( 'Primary CTA URL', 'smart-leading-net' ), 'sln_aeo_final_cta[primary_cta_url]', $data['primary_cta_url'] );
		sln_aeo_admin_text_field( __( 'Secondary CTA text', 'smart-leading-net' ), 'sln_aeo_final_cta[secondary_cta_text]', $data['secondary_cta_text'] );
		sln_aeo_admin_url_field( __( 'Secondary CTA URL', 'smart-leading-net' ), 'sln_aeo_final_cta[secondary_cta_url]', $data['secondary_cta_url'] );
		?>
	</table>
	<?php
}
