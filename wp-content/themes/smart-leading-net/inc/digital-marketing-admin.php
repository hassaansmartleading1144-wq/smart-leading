<?php
/**
 * Digital Marketing page — admin meta boxes.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** @var int */
$GLOBALS['sln_dm_registered_meta_boxes'] = 0;

/**
 * Register Digital Marketing meta boxes.
 */
function sln_dm_register_meta_boxes() {
	$screen = get_current_screen();

	if ( ! $screen || 'page' !== $screen->post_type ) {
		return;
	}

	if ( ! sln_page_admin_should_register_template_boxes( 'sln_dm_admin_is_target_page' ) ) {
		return;
	}

	$callbacks = array(
		'sln_dm_hero'       => 'sln_dm_render_hero_metabox',
		'sln_dm_reality'    => 'sln_dm_render_reality_metabox',
		'sln_dm_approach'   => 'sln_dm_render_approach_metabox',
		'sln_dm_truth'      => 'sln_dm_render_truth_metabox',
		'sln_dm_services'   => 'sln_dm_render_services_metabox',
		'sln_dm_ads'        => 'sln_dm_render_ads_metabox',
		'sln_dm_process'    => 'sln_dm_render_process_metabox',
		'sln_dm_proof'      => 'sln_dm_render_proof_metabox',
		'sln_dm_pricing'    => 'sln_dm_render_pricing_metabox',
		'sln_dm_faq'        => 'sln_dm_render_faq_metabox',
		'sln_dm_final_cta'  => 'sln_dm_render_final_cta_metabox',
	);

	$titles = array(
		'sln_dm_hero'      => __( 'Section 1 — Hero', 'smart-leading-net' ),
		'sln_dm_reality'   => __( 'Section 2 — The Reality', 'smart-leading-net' ),
		'sln_dm_approach'  => __( 'Section 3 — Our Approach', 'smart-leading-net' ),
		'sln_dm_truth'     => __( 'Section 4 — Quick Truth', 'smart-leading-net' ),
		'sln_dm_services'  => __( 'Section 5 — Services', 'smart-leading-net' ),
		'sln_dm_ads'       => __( 'Section 6 — Paid Advertising', 'smart-leading-net' ),
		'sln_dm_process'   => __( 'Section 7 — Process', 'smart-leading-net' ),
		'sln_dm_proof'     => __( 'Section 8 — Proof of Work', 'smart-leading-net' ),
		'sln_dm_pricing'   => __( 'Section 9 — Pricing', 'smart-leading-net' ),
		'sln_dm_faq'       => __( 'Section 10 — FAQ', 'smart-leading-net' ),
		'sln_dm_final_cta' => __( 'Section 11 — Final CTA', 'smart-leading-net' ),
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
		++$GLOBALS['sln_dm_registered_meta_boxes'];
	}
}
add_action( 'add_meta_boxes', 'sln_dm_register_meta_boxes' );

/**
 * Enqueue admin assets on Digital Marketing page edit screen.
 *
 * @param string $hook Current admin hook.
 */
function sln_dm_enqueue_admin_assets( $hook ) {
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
		'sln-digital-marketing-admin',
		SLN_THEME_URI . '/assets/css/digital-marketing-admin.css',
		array(),
		SLN_THEME_VERSION
	);

	wp_enqueue_script(
		'sln-digital-marketing-admin',
		SLN_THEME_URI . '/assets/js/digital-marketing-admin.js',
		array( 'jquery', 'jquery-ui-sortable' ),
		SLN_THEME_VERSION,
		true
	);

	wp_localize_script(
		'sln-digital-marketing-admin',
		'slnDigitalMarketingAdmin',
		array(
			'editorSettings'  => function_exists( 'sln_growth_page_get_js_editor_settings' ) ? sln_growth_page_get_js_editor_settings() : array(),
			'template'        => SLN_DM_TEMPLATE,
			'currentTemplate' => ( $post instanceof WP_Post ) ? get_page_template_slug( $post->ID ) : '',
			'isTargetPage'    => ( $post instanceof WP_Post ) ? sln_dm_admin_is_target_page( $post ) : false,
		)
	);
}
add_action( 'admin_enqueue_scripts', 'sln_dm_enqueue_admin_assets' );

/**
 * Render hero metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_dm_render_hero_metabox( $post ) {
	$data    = sln_dm_admin_get_section( $post->ID, SLN_DM_HERO_META, sln_dm_default_hero() );
	$stats   = sln_dm_admin_get_rows( $post->ID, SLN_DM_HERO_STATS_META, sln_dm_default_hero_stats() );
	$metrics = sln_dm_admin_get_rows( $post->ID, SLN_DM_DASHBOARD_METRICS_META, sln_dm_default_dashboard_metrics() );
	?>
	<table class="form-table sln-dm-admin__table" role="presentation">
		<?php
		sln_dm_admin_text_field( __( 'Small Heading', 'smart-leading-net' ), 'sln_dm_hero[small_heading]', $data['small_heading'] );
		sln_dm_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_dm_hero[main_heading]', $data['main_heading'] );
		sln_dm_admin_text_field( __( 'Highlighted Text', 'smart-leading-net' ), 'sln_dm_hero[highlighted_text]', $data['highlighted_text'] );
		sln_dm_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-dm-hero-desc', 'sln_dm_hero[description]', $data['description'] );
		sln_dm_admin_text_field( __( 'Primary Button Text', 'smart-leading-net' ), 'sln_dm_hero[primary_button_text]', $data['primary_button_text'] );
		sln_dm_admin_url_field( __( 'Primary Button URL', 'smart-leading-net' ), 'sln_dm_hero[primary_button_url]', $data['primary_button_url'] );
		sln_dm_admin_text_field( __( 'Dashboard Title', 'smart-leading-net' ), 'sln_dm_hero[dashboard_title]', $data['dashboard_title'] );
		sln_dm_admin_text_field( __( 'Chip 1 Text', 'smart-leading-net' ), 'sln_dm_hero[chip_1_text]', $data['chip_1_text'] );
		sln_dm_admin_text_field( __( 'Chip 2 Text', 'smart-leading-net' ), 'sln_dm_hero[chip_2_text]', $data['chip_2_text'] );
		sln_dm_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_dm_hero[active]', ! empty( $data['active'] ) );
		?>
	</table>

	<div class="sln-dm-admin__repeatable" data-row-selector=".sln-dm-admin__hero-stat-row" data-name-prefix="sln_dm_hero_stats">
		<h3><?php esc_html_e( 'Hero Stats', 'smart-leading-net' ); ?></h3>
		<div class="sln-dm-admin__repeatable-list">
			<?php foreach ( $stats as $index => $stat ) : ?>
				<div class="sln-dm-admin__repeatable-row sln-dm-admin__hero-stat-row">
					<div class="sln-dm-admin__row-head">
						<strong><?php echo esc_html( $stat['label'] ?: __( 'Hero Stat', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-dm-admin__row-actions">
							<button type="button" class="button sln-dm-admin__move-up" aria-label="<?php esc_attr_e( 'Move up', 'smart-leading-net' ); ?>">&#8593;</button>
							<button type="button" class="button sln-dm-admin__move-down" aria-label="<?php esc_attr_e( 'Move down', 'smart-leading-net' ); ?>">&#8595;</button>
							<button type="button" class="button-link-delete sln-dm-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_dm_admin_text_field( __( 'Prefix', 'smart-leading-net' ), 'sln_dm_hero_stats[' . $index . '][prefix]', $stat['prefix'] ?? '' );
						sln_dm_admin_text_field( __( 'Number', 'smart-leading-net' ), 'sln_dm_hero_stats[' . $index . '][number]', $stat['number'] ?? '' );
						sln_dm_admin_text_field( __( 'Decimals', 'smart-leading-net' ), 'sln_dm_hero_stats[' . $index . '][decimals]', $stat['decimals'] ?? '0' );
						sln_dm_admin_text_field( __( 'Suffix', 'smart-leading-net' ), 'sln_dm_hero_stats[' . $index . '][suffix]', $stat['suffix'] ?? '' );
						sln_dm_admin_text_field( __( 'Secondary Unit Text', 'smart-leading-net' ), 'sln_dm_hero_stats[' . $index . '][unit]', $stat['unit'] ?? '' );
						sln_dm_admin_text_field( __( 'Label', 'smart-leading-net' ), 'sln_dm_hero_stats[' . $index . '][label]', $stat['label'] ?? '' );
						sln_dm_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_dm_hero_stats[' . $index . '][active]', ! empty( $stat['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_dm_admin_repeater_toolbar( __( 'Add Hero Stat', 'smart-leading-net' ) ); ?>
	</div>

	<div class="sln-dm-admin__repeatable" data-row-selector=".sln-dm-admin__dashboard-metric-row" data-name-prefix="sln_dm_dashboard_metrics">
		<h3><?php esc_html_e( 'Dashboard Metrics', 'smart-leading-net' ); ?></h3>
		<div class="sln-dm-admin__repeatable-list">
			<?php foreach ( $metrics as $index => $metric ) : ?>
				<div class="sln-dm-admin__repeatable-row sln-dm-admin__dashboard-metric-row">
					<div class="sln-dm-admin__row-head">
						<strong><?php echo esc_html( $metric['label'] ?: __( 'Dashboard Metric', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-dm-admin__row-actions">
							<button type="button" class="button sln-dm-admin__move-up">&#8593;</button>
							<button type="button" class="button sln-dm-admin__move-down">&#8595;</button>
							<button type="button" class="button-link-delete sln-dm-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_dm_admin_text_field( __( 'Prefix', 'smart-leading-net' ), 'sln_dm_dashboard_metrics[' . $index . '][prefix]', $metric['prefix'] ?? '' );
						sln_dm_admin_text_field( __( 'Value', 'smart-leading-net' ), 'sln_dm_dashboard_metrics[' . $index . '][value]', $metric['value'] ?? '' );
						sln_dm_admin_text_field( __( 'Suffix', 'smart-leading-net' ), 'sln_dm_dashboard_metrics[' . $index . '][suffix]', $metric['suffix'] ?? '' );
						sln_dm_admin_text_field( __( 'Label', 'smart-leading-net' ), 'sln_dm_dashboard_metrics[' . $index . '][label]', $metric['label'] ?? '' );
						sln_dm_admin_text_field( __( 'Decimals', 'smart-leading-net' ), 'sln_dm_dashboard_metrics[' . $index . '][decimals]', $metric['decimals'] ?? '0' );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_dm_admin_repeater_toolbar( __( 'Add Dashboard Metric', 'smart-leading-net' ) ); ?>
	</div>
	<?php
}

/**
 * Render reality metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_dm_render_reality_metabox( $post ) {
	$section = sln_dm_admin_get_section( $post->ID, SLN_DM_REALITY_SECTION_META, sln_dm_default_reality_section() );
	$cards   = sln_dm_admin_get_rows( $post->ID, SLN_DM_REALITY_CARDS_META, sln_dm_default_reality_cards() );
	?>
	<table class="form-table sln-dm-admin__table" role="presentation">
		<?php
		sln_dm_admin_text_field( __( 'Small Heading', 'smart-leading-net' ), 'sln_dm_reality_section[small_heading]', $section['small_heading'] );
		sln_dm_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_dm_reality_section[main_heading]', $section['main_heading'] );
		sln_dm_admin_text_field( __( 'Highlighted Text', 'smart-leading-net' ), 'sln_dm_reality_section[highlighted_text]', $section['highlighted_text'] );
		sln_dm_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-dm-reality-desc', 'sln_dm_reality_section[description]', $section['description'] );
		sln_dm_admin_text_field( __( 'Note Text', 'smart-leading-net' ), 'sln_dm_reality_section[note_text]', $section['note_text'] );
		sln_dm_admin_text_field( __( 'Note Highlight', 'smart-leading-net' ), 'sln_dm_reality_section[note_highlight]', $section['note_highlight'] );
		?>
		<tr>
			<th scope="row"><?php esc_html_e( 'Note Active', 'smart-leading-net' ); ?></th>
			<td>
				<label>
					<input type="checkbox" name="sln_dm_reality_section[note_active]" value="1" <?php checked( ! empty( $section['note_active'] ) ); ?> />
					<?php esc_html_e( 'Show note', 'smart-leading-net' ); ?>
				</label>
			</td>
		</tr>
	</table>

	<div class="sln-dm-admin__repeatable" data-row-selector=".sln-dm-admin__reality-row" data-name-prefix="sln_dm_reality_cards">
		<h3><?php esc_html_e( 'Cards', 'smart-leading-net' ); ?></h3>
		<div class="sln-dm-admin__repeatable-list">
			<?php foreach ( $cards as $index => $card ) : ?>
				<div class="sln-dm-admin__repeatable-row sln-dm-admin__reality-row">
					<div class="sln-dm-admin__row-head">
						<strong><?php echo esc_html( $card['title'] ?: __( 'Card', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-dm-admin__row-actions">
							<button type="button" class="button sln-dm-admin__move-up">&#8593;</button>
							<button type="button" class="button sln-dm-admin__move-down">&#8595;</button>
							<button type="button" class="button-link-delete sln-dm-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_dm_admin_media_field( __( 'Icon', 'smart-leading-net' ), 'sln_dm_reality_cards[' . $index . '][icon_id]', absint( $card['icon_id'] ?? 0 ) );
						sln_dm_admin_text_field( __( 'Icon Text', 'smart-leading-net' ), 'sln_dm_reality_cards[' . $index . '][icon_text]', $card['icon_text'] ?? '' );
						sln_dm_admin_select_field( __( 'Icon Style', 'smart-leading-net' ), 'sln_dm_reality_cards[' . $index . '][icon_style]', $card['icon_style'] ?? 'orange', sln_dm_admin_icon_style_options() );
						sln_dm_admin_text_field( __( 'Title', 'smart-leading-net' ), 'sln_dm_reality_cards[' . $index . '][title]', $card['title'] ?? '' );
						?>
						<tr>
							<th scope="row"><label for="sln-dm-reality-card-desc-<?php echo esc_attr( (string) $index ); ?>"><?php esc_html_e( 'Description', 'smart-leading-net' ); ?></label></th>
							<td><textarea id="sln-dm-reality-card-desc-<?php echo esc_attr( (string) $index ); ?>" class="large-text" rows="4" name="sln_dm_reality_cards[<?php echo esc_attr( (string) $index ); ?>][description]"><?php echo esc_textarea( $card['description'] ?? '' ); ?></textarea></td>
						</tr>
						<?php
						sln_dm_admin_url_field( __( 'URL', 'smart-leading-net' ), 'sln_dm_reality_cards[' . $index . '][url]', $card['url'] ?? '' );
						sln_dm_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_dm_reality_cards[' . $index . '][active]', ! empty( $card['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_dm_admin_repeater_toolbar( __( 'Add Card', 'smart-leading-net' ) ); ?>
	</div>
	<?php
}

/**
 * Render approach metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_dm_render_approach_metabox( $post ) {
	$section = sln_dm_admin_get_section( $post->ID, SLN_DM_APPROACH_SECTION_META, sln_dm_default_approach_section() );
	$items   = sln_dm_admin_get_rows( $post->ID, SLN_DM_APPROACH_ITEMS_META, sln_dm_default_approach_items() );
	?>
	<table class="form-table sln-dm-admin__table" role="presentation">
		<?php
		sln_dm_admin_text_field( __( 'Small Heading', 'smart-leading-net' ), 'sln_dm_approach_section[small_heading]', $section['small_heading'] );
		sln_dm_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_dm_approach_section[main_heading]', $section['main_heading'] );
		sln_dm_admin_text_field( __( 'Highlighted Text', 'smart-leading-net' ), 'sln_dm_approach_section[highlighted_text]', $section['highlighted_text'] );
		sln_dm_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-dm-approach-desc', 'sln_dm_approach_section[description]', $section['description'] );
		?>
	</table>

	<div class="sln-dm-admin__repeatable" data-row-selector=".sln-dm-admin__approach-row" data-name-prefix="sln_dm_approach_items">
		<h3><?php esc_html_e( 'Problem / Solution Items', 'smart-leading-net' ); ?></h3>
		<div class="sln-dm-admin__repeatable-list">
			<?php foreach ( $items as $index => $item ) : ?>
				<div class="sln-dm-admin__repeatable-row sln-dm-admin__approach-row">
					<div class="sln-dm-admin__row-head">
						<strong><?php echo esc_html( $item['problem'] ?: __( 'Approach Item', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-dm-admin__row-actions">
							<button type="button" class="button sln-dm-admin__move-up">&#8593;</button>
							<button type="button" class="button sln-dm-admin__move-down">&#8595;</button>
							<button type="button" class="button-link-delete sln-dm-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_dm_admin_text_field( __( 'Problem', 'smart-leading-net' ), 'sln_dm_approach_items[' . $index . '][problem]', $item['problem'] ?? '' );
						sln_dm_admin_editor_field( __( 'Solution', 'smart-leading-net' ), 'sln-dm-approach-item-' . $index, 'sln_dm_approach_items[' . $index . '][solution]', $item['solution'] ?? '' );
						sln_dm_admin_url_field( __( 'URL', 'smart-leading-net' ), 'sln_dm_approach_items[' . $index . '][url]', $item['url'] ?? '' );
						sln_dm_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_dm_approach_items[' . $index . '][active]', ! empty( $item['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_dm_admin_repeater_toolbar( __( 'Add Item', 'smart-leading-net' ) ); ?>
	</div>
	<?php
}

/**
 * Render truth metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_dm_render_truth_metabox( $post ) {
	$section    = sln_dm_admin_get_section( $post->ID, SLN_DM_TRUTH_SECTION_META, sln_dm_default_truth_section() );
	$paragraphs = sln_dm_admin_get_rows( $post->ID, SLN_DM_TRUTH_PARAGRAPHS_META, sln_dm_default_truth_paragraphs() );
	$quote      = sln_dm_admin_get_section( $post->ID, SLN_DM_TRUTH_QUOTE_META, sln_dm_default_truth_quote() );
	?>
	<table class="form-table sln-dm-admin__table" role="presentation">
		<?php
		sln_dm_admin_text_field( __( 'Small Heading', 'smart-leading-net' ), 'sln_dm_truth_section[small_heading]', $section['small_heading'] );
		sln_dm_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_dm_truth_section[main_heading]', $section['main_heading'] );
		sln_dm_admin_text_field( __( 'Highlighted Text', 'smart-leading-net' ), 'sln_dm_truth_section[highlighted_text]', $section['highlighted_text'] );
		sln_dm_admin_text_field( __( 'Button Text', 'smart-leading-net' ), 'sln_dm_truth_section[button_text]', $section['button_text'] );
		sln_dm_admin_url_field( __( 'Button URL', 'smart-leading-net' ), 'sln_dm_truth_section[button_url]', $section['button_url'] );
		sln_dm_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_dm_truth_section[active]', ! empty( $section['active'] ) );
		?>
	</table>

	<div class="sln-dm-admin__repeatable" data-row-selector=".sln-dm-admin__truth-paragraph-row" data-name-prefix="sln_dm_truth_paragraphs">
		<h3><?php esc_html_e( 'Paragraphs', 'smart-leading-net' ); ?></h3>
		<div class="sln-dm-admin__repeatable-list">
			<?php foreach ( $paragraphs as $index => $paragraph ) : ?>
				<div class="sln-dm-admin__repeatable-row sln-dm-admin__truth-paragraph-row">
					<div class="sln-dm-admin__row-head">
						<strong><?php echo esc_html( wp_trim_words( $paragraph['text'] ?? '', 8, '…' ) ?: __( 'Paragraph', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-dm-admin__row-actions">
							<button type="button" class="button sln-dm-admin__move-up">&#8593;</button>
							<button type="button" class="button sln-dm-admin__move-down">&#8595;</button>
							<button type="button" class="button-link-delete sln-dm-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<tr>
							<th scope="row"><label for="sln-dm-truth-paragraph-<?php echo esc_attr( (string) $index ); ?>"><?php esc_html_e( 'Text', 'smart-leading-net' ); ?></label></th>
							<td><textarea id="sln-dm-truth-paragraph-<?php echo esc_attr( (string) $index ); ?>" class="large-text" rows="4" name="sln_dm_truth_paragraphs[<?php echo esc_attr( (string) $index ); ?>][text]"><?php echo esc_textarea( $paragraph['text'] ?? '' ); ?></textarea></td>
						</tr>
						<?php sln_dm_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_dm_truth_paragraphs[' . $index . '][active]', ! empty( $paragraph['active'] ) ); ?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_dm_admin_repeater_toolbar( __( 'Add Paragraph', 'smart-leading-net' ) ); ?>
	</div>

	<h3><?php esc_html_e( 'Quote Block', 'smart-leading-net' ); ?></h3>
	<table class="form-table sln-dm-admin__table" role="presentation">
		<?php
		sln_dm_admin_text_field( __( 'Quote Text', 'smart-leading-net' ), 'sln_dm_truth_quote[quote_text]', $quote['quote_text'] );
		sln_dm_admin_text_field( __( 'Highlighted Text', 'smart-leading-net' ), 'sln_dm_truth_quote[highlighted_text]', $quote['highlighted_text'] );
		sln_dm_admin_text_field( __( 'Attribution', 'smart-leading-net' ), 'sln_dm_truth_quote[attribution]', $quote['attribution'] );
		sln_dm_admin_text_field( __( 'Graph Label', 'smart-leading-net' ), 'sln_dm_truth_quote[graph_label]', $quote['graph_label'] );
		sln_dm_admin_text_field( __( 'Graph Growth', 'smart-leading-net' ), 'sln_dm_truth_quote[graph_growth]', $quote['graph_growth'] );
		sln_dm_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_dm_truth_quote[active]', ! empty( $quote['active'] ) );
		?>
	</table>
	<?php
}

/**
 * Render services metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_dm_render_services_metabox( $post ) {
	$section = sln_dm_admin_get_section( $post->ID, SLN_DM_SERVICES_SECTION_META, sln_dm_default_services_section() );
	$items   = sln_dm_admin_get_rows( $post->ID, SLN_DM_SERVICES_ITEMS_META, sln_dm_default_services_items() );
	?>
	<table class="form-table sln-dm-admin__table" role="presentation">
		<?php
		sln_dm_admin_text_field( __( 'Small Heading', 'smart-leading-net' ), 'sln_dm_services_section[small_heading]', $section['small_heading'] );
		sln_dm_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_dm_services_section[main_heading]', $section['main_heading'] );
		sln_dm_admin_text_field( __( 'Highlighted Text', 'smart-leading-net' ), 'sln_dm_services_section[highlighted_text]', $section['highlighted_text'] );
		sln_dm_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-dm-services-desc', 'sln_dm_services_section[description]', $section['description'] );
		?>
	</table>

	<div class="sln-dm-admin__repeatable" data-row-selector=".sln-dm-admin__services-row" data-name-prefix="sln_dm_services_items">
		<h3><?php esc_html_e( 'Service Items', 'smart-leading-net' ); ?></h3>
		<div class="sln-dm-admin__repeatable-list">
			<?php foreach ( $items as $index => $item ) : ?>
				<div class="sln-dm-admin__repeatable-row sln-dm-admin__services-row">
					<div class="sln-dm-admin__row-head">
						<strong><?php echo esc_html( $item['title'] ?: __( 'Service Item', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-dm-admin__row-actions">
							<button type="button" class="button sln-dm-admin__move-up">&#8593;</button>
							<button type="button" class="button sln-dm-admin__move-down">&#8595;</button>
							<button type="button" class="button-link-delete sln-dm-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_dm_admin_media_field( __( 'Icon', 'smart-leading-net' ), 'sln_dm_services_items[' . $index . '][icon_id]', absint( $item['icon_id'] ?? 0 ) );
						sln_dm_admin_text_field( __( 'Icon Text', 'smart-leading-net' ), 'sln_dm_services_items[' . $index . '][icon_text]', $item['icon_text'] ?? '' );
						sln_dm_admin_select_field( __( 'Icon Style', 'smart-leading-net' ), 'sln_dm_services_items[' . $index . '][icon_style]', $item['icon_style'] ?? 'orange', sln_dm_admin_icon_style_options() );
						sln_dm_admin_text_field( __( 'Title', 'smart-leading-net' ), 'sln_dm_services_items[' . $index . '][title]', $item['title'] ?? '' );
						?>
						<tr>
							<th scope="row"><label for="sln-dm-services-desc-<?php echo esc_attr( (string) $index ); ?>"><?php esc_html_e( 'Description', 'smart-leading-net' ); ?></label></th>
							<td><textarea id="sln-dm-services-desc-<?php echo esc_attr( (string) $index ); ?>" class="large-text" rows="3" name="sln_dm_services_items[<?php echo esc_attr( (string) $index ); ?>][description]"><?php echo esc_textarea( $item['description'] ?? '' ); ?></textarea></td>
						</tr>
						<?php
						sln_dm_admin_url_field( __( 'URL', 'smart-leading-net' ), 'sln_dm_services_items[' . $index . '][url]', $item['url'] ?? '' );
						?>
						<tr>
							<th scope="row"><?php esc_html_e( 'New Tab', 'smart-leading-net' ); ?></th>
							<td>
								<label>
									<input type="checkbox" name="sln_dm_services_items[<?php echo esc_attr( (string) $index ); ?>][new_tab]" value="1" <?php checked( ! empty( $item['new_tab'] ) ); ?> />
									<?php esc_html_e( 'Open in new tab', 'smart-leading-net' ); ?>
								</label>
							</td>
						</tr>
						<?php sln_dm_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_dm_services_items[' . $index . '][active]', ! empty( $item['active'] ) ); ?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_dm_admin_repeater_toolbar( __( 'Add Service Item', 'smart-leading-net' ) ); ?>
	</div>
	<?php
}

/**
 * Render ads metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_dm_render_ads_metabox( $post ) {
	$section  = sln_dm_admin_get_section( $post->ID, SLN_DM_ADS_SECTION_META, sln_dm_default_ads_section() );
	$channels = sln_dm_admin_get_rows( $post->ID, SLN_DM_ADS_CHANNELS_META, sln_dm_default_ads_channels() );
	?>
	<table class="form-table sln-dm-admin__table" role="presentation">
		<?php
		sln_dm_admin_text_field( __( 'Small Heading', 'smart-leading-net' ), 'sln_dm_ads_section[small_heading]', $section['small_heading'] );
		sln_dm_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_dm_ads_section[main_heading]', $section['main_heading'] );
		sln_dm_admin_text_field( __( 'Highlighted Text', 'smart-leading-net' ), 'sln_dm_ads_section[highlighted_text]', $section['highlighted_text'] );
		sln_dm_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-dm-ads-desc', 'sln_dm_ads_section[description]', $section['description'] );
		?>
	</table>

	<div class="sln-dm-admin__repeatable" data-row-selector=".sln-dm-admin__ads-row" data-name-prefix="sln_dm_ads_channels">
		<h3><?php esc_html_e( 'Channels', 'smart-leading-net' ); ?></h3>
		<div class="sln-dm-admin__repeatable-list">
			<?php foreach ( $channels as $index => $channel ) : ?>
				<div class="sln-dm-admin__repeatable-row sln-dm-admin__ads-row">
					<div class="sln-dm-admin__row-head">
						<strong><?php echo esc_html( $channel['name'] ?: __( 'Channel', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-dm-admin__row-actions">
							<button type="button" class="button sln-dm-admin__move-up">&#8593;</button>
							<button type="button" class="button sln-dm-admin__move-down">&#8595;</button>
							<button type="button" class="button-link-delete sln-dm-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_dm_admin_media_field( __( 'Icon', 'smart-leading-net' ), 'sln_dm_ads_channels[' . $index . '][icon_id]', absint( $channel['icon_id'] ?? 0 ) );
						sln_dm_admin_text_field( __( 'Icon Text', 'smart-leading-net' ), 'sln_dm_ads_channels[' . $index . '][icon_text]', $channel['icon_text'] ?? '' );
						sln_dm_admin_text_field( __( 'Name', 'smart-leading-net' ), 'sln_dm_ads_channels[' . $index . '][name]', $channel['name'] ?? '' );
						?>
						<tr>
							<th scope="row"><label for="sln-dm-ads-desc-<?php echo esc_attr( (string) $index ); ?>"><?php esc_html_e( 'Description', 'smart-leading-net' ); ?></label></th>
							<td><textarea id="sln-dm-ads-desc-<?php echo esc_attr( (string) $index ); ?>" class="large-text" rows="3" name="sln_dm_ads_channels[<?php echo esc_attr( (string) $index ); ?>][description]"><?php echo esc_textarea( $channel['description'] ?? '' ); ?></textarea></td>
						</tr>
						<?php
						sln_dm_admin_url_field( __( 'URL', 'smart-leading-net' ), 'sln_dm_ads_channels[' . $index . '][url]', $channel['url'] ?? '' );
						sln_dm_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_dm_ads_channels[' . $index . '][active]', ! empty( $channel['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_dm_admin_repeater_toolbar( __( 'Add Channel', 'smart-leading-net' ) ); ?>
	</div>
	<?php
}

/**
 * Render process metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_dm_render_process_metabox( $post ) {
	$section = sln_dm_admin_get_section( $post->ID, SLN_DM_PROCESS_SECTION_META, sln_dm_default_process_section() );
	$steps   = sln_dm_admin_get_rows( $post->ID, SLN_DM_PROCESS_STEPS_META, sln_dm_default_process_steps() );
	?>
	<table class="form-table sln-dm-admin__table" role="presentation">
		<?php
		sln_dm_admin_text_field( __( 'Small Heading', 'smart-leading-net' ), 'sln_dm_process_section[small_heading]', $section['small_heading'] );
		sln_dm_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_dm_process_section[main_heading]', $section['main_heading'] );
		sln_dm_admin_text_field( __( 'Highlighted Text', 'smart-leading-net' ), 'sln_dm_process_section[highlighted_text]', $section['highlighted_text'] );
		sln_dm_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-dm-process-desc', 'sln_dm_process_section[description]', $section['description'] );
		sln_dm_admin_text_field( __( 'Bottom Note', 'smart-leading-net' ), 'sln_dm_process_section[bottom_note]', $section['bottom_note'] );
		?>
	</table>

	<div class="sln-dm-admin__repeatable" data-row-selector=".sln-dm-admin__process-row" data-name-prefix="sln_dm_process_steps">
		<h3><?php esc_html_e( 'Steps', 'smart-leading-net' ); ?></h3>
		<div class="sln-dm-admin__repeatable-list">
			<?php foreach ( $steps as $index => $step ) : ?>
				<div class="sln-dm-admin__repeatable-row sln-dm-admin__process-row">
					<div class="sln-dm-admin__row-head">
						<strong><?php echo esc_html( $step['title'] ?: __( 'Process Step', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-dm-admin__row-actions">
							<button type="button" class="button sln-dm-admin__move-up">&#8593;</button>
							<button type="button" class="button sln-dm-admin__move-down">&#8595;</button>
							<button type="button" class="button-link-delete sln-dm-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_dm_admin_text_field( __( 'Number', 'smart-leading-net' ), 'sln_dm_process_steps[' . $index . '][number]', $step['number'] ?? '' );
						sln_dm_admin_text_field( __( 'Title', 'smart-leading-net' ), 'sln_dm_process_steps[' . $index . '][title]', $step['title'] ?? '' );
						?>
						<tr>
							<th scope="row"><?php esc_html_e( 'Bullets', 'smart-leading-net' ); ?></th>
							<td><?php sln_dm_admin_render_bullets( 'sln_dm_process_steps[' . $index . '][bullets]', $step['bullets'] ?? array() ); ?></td>
						</tr>
						<?php
						sln_dm_admin_url_field( __( 'URL', 'smart-leading-net' ), 'sln_dm_process_steps[' . $index . '][url]', $step['url'] ?? '' );
						sln_dm_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_dm_process_steps[' . $index . '][active]', ! empty( $step['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_dm_admin_repeater_toolbar( __( 'Add Step', 'smart-leading-net' ) ); ?>
	</div>
	<?php
}

/**
 * Render proof metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_dm_render_proof_metabox( $post ) {
	$section      = sln_dm_admin_get_section( $post->ID, SLN_DM_PROOF_SECTION_META, sln_dm_default_proof_section() );
	$case_studies = sln_dm_admin_get_rows( $post->ID, SLN_DM_CASE_STUDIES_META, sln_dm_default_case_studies() );
	?>
	<table class="form-table sln-dm-admin__table" role="presentation">
		<?php
		sln_dm_admin_text_field( __( 'Small Heading', 'smart-leading-net' ), 'sln_dm_proof_section[small_heading]', $section['small_heading'] );
		sln_dm_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_dm_proof_section[main_heading]', $section['main_heading'] );
		sln_dm_admin_text_field( __( 'Highlighted Text', 'smart-leading-net' ), 'sln_dm_proof_section[highlighted_text]', $section['highlighted_text'] );
		sln_dm_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-dm-proof-desc', 'sln_dm_proof_section[description]', $section['description'] );
		sln_dm_admin_text_field( __( 'Disclaimer', 'smart-leading-net' ), 'sln_dm_proof_section[disclaimer]', $section['disclaimer'] );
		?>
	</table>

	<div class="sln-dm-admin__repeatable" data-row-selector=".sln-dm-admin__proof-row" data-name-prefix="sln_dm_case_studies">
		<h3><?php esc_html_e( 'Case Studies', 'smart-leading-net' ); ?></h3>
		<div class="sln-dm-admin__repeatable-list">
			<?php foreach ( $case_studies as $index => $study ) : ?>
				<div class="sln-dm-admin__repeatable-row sln-dm-admin__proof-row">
					<div class="sln-dm-admin__row-head">
						<strong><?php echo esc_html( $study['name'] ?: __( 'Case Study', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-dm-admin__row-actions">
							<button type="button" class="button sln-dm-admin__move-up">&#8593;</button>
							<button type="button" class="button sln-dm-admin__move-down">&#8595;</button>
							<button type="button" class="button-link-delete sln-dm-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_dm_admin_text_field( __( 'Name', 'smart-leading-net' ), 'sln_dm_case_studies[' . $index . '][name]', $study['name'] ?? '' );
						sln_dm_admin_text_field( __( 'Tag', 'smart-leading-net' ), 'sln_dm_case_studies[' . $index . '][tag]', $study['tag'] ?? '' );
						?>
						<tr>
							<th scope="row"><?php esc_html_e( 'Metrics', 'smart-leading-net' ); ?></th>
							<td><?php sln_dm_admin_render_metrics( 'sln_dm_case_studies[' . $index . '][metrics]', $study['metrics'] ?? array() ); ?></td>
						</tr>
						<tr>
							<th scope="row"><label for="sln-dm-proof-quote-<?php echo esc_attr( (string) $index ); ?>"><?php esc_html_e( 'Quote', 'smart-leading-net' ); ?></label></th>
							<td><textarea id="sln-dm-proof-quote-<?php echo esc_attr( (string) $index ); ?>" class="large-text" rows="4" name="sln_dm_case_studies[<?php echo esc_attr( (string) $index ); ?>][quote]"><?php echo esc_textarea( $study['quote'] ?? '' ); ?></textarea></td>
						</tr>
						<?php
						sln_dm_admin_text_field( __( 'Attribution', 'smart-leading-net' ), 'sln_dm_case_studies[' . $index . '][attribution]', $study['attribution'] ?? '' );
						sln_dm_admin_url_field( __( 'URL', 'smart-leading-net' ), 'sln_dm_case_studies[' . $index . '][url]', $study['url'] ?? '' );
						sln_dm_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_dm_case_studies[' . $index . '][active]', ! empty( $study['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_dm_admin_repeater_toolbar( __( 'Add Case Study', 'smart-leading-net' ) ); ?>
	</div>
	<?php
}

/**
 * Render pricing metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_dm_render_pricing_metabox( $post ) {
	$section = sln_dm_admin_get_section( $post->ID, SLN_DM_PRICING_SECTION_META, sln_dm_default_pricing_section() );
	$plans   = sln_dm_admin_get_rows( $post->ID, SLN_DM_PRICING_PLANS_META, sln_dm_default_pricing_plans() );
	?>
	<table class="form-table sln-dm-admin__table" role="presentation">
		<?php
		sln_dm_admin_text_field( __( 'Small Heading', 'smart-leading-net' ), 'sln_dm_pricing_section[small_heading]', $section['small_heading'] );
		sln_dm_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_dm_pricing_section[main_heading]', $section['main_heading'] );
		sln_dm_admin_text_field( __( 'Highlighted Text', 'smart-leading-net' ), 'sln_dm_pricing_section[highlighted_text]', $section['highlighted_text'] );
		sln_dm_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-dm-pricing-desc', 'sln_dm_pricing_section[description]', $section['description'] );
		sln_dm_admin_text_field( __( 'Bottom Note', 'smart-leading-net' ), 'sln_dm_pricing_section[bottom_note]', $section['bottom_note'] );
		?>
	</table>

	<div class="sln-dm-admin__repeatable" data-row-selector=".sln-dm-admin__pricing-row" data-name-prefix="sln_dm_pricing_plans">
		<h3><?php esc_html_e( 'Pricing Plans', 'smart-leading-net' ); ?></h3>
		<div class="sln-dm-admin__repeatable-list">
			<?php foreach ( $plans as $index => $plan ) : ?>
				<div class="sln-dm-admin__repeatable-row sln-dm-admin__pricing-row">
					<div class="sln-dm-admin__row-head">
						<strong><?php echo esc_html( $plan['name'] ?: __( 'Pricing Plan', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-dm-admin__row-actions">
							<button type="button" class="button sln-dm-admin__move-up">&#8593;</button>
							<button type="button" class="button sln-dm-admin__move-down">&#8595;</button>
							<button type="button" class="button-link-delete sln-dm-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_dm_admin_text_field( __( 'Name', 'smart-leading-net' ), 'sln_dm_pricing_plans[' . $index . '][name]', $plan['name'] ?? '' );
						sln_dm_admin_text_field( __( 'Tagline', 'smart-leading-net' ), 'sln_dm_pricing_plans[' . $index . '][tagline]', $plan['tagline'] ?? '' );
						sln_dm_admin_text_field( __( 'Price', 'smart-leading-net' ), 'sln_dm_pricing_plans[' . $index . '][price]', $plan['price'] ?? '' );
						sln_dm_admin_text_field( __( 'Price Prefix', 'smart-leading-net' ), 'sln_dm_pricing_plans[' . $index . '][price_prefix]', $plan['price_prefix'] ?? '' );
						sln_dm_admin_text_field( __( 'Price Suffix', 'smart-leading-net' ), 'sln_dm_pricing_plans[' . $index . '][price_suffix]', $plan['price_suffix'] ?? '' );
						?>
						<tr>
							<th scope="row"><?php esc_html_e( 'Most Popular', 'smart-leading-net' ); ?></th>
							<td>
								<label>
									<input type="checkbox" name="sln_dm_pricing_plans[<?php echo esc_attr( (string) $index ); ?>][is_popular]" value="1" <?php checked( ! empty( $plan['is_popular'] ) ); ?> />
									<?php esc_html_e( 'Mark as most popular', 'smart-leading-net' ); ?>
								</label>
							</td>
						</tr>
						<?php
						sln_dm_admin_text_field( __( 'Popular Badge', 'smart-leading-net' ), 'sln_dm_pricing_plans[' . $index . '][popular_badge]', $plan['popular_badge'] ?? '' );
						?>
						<tr>
							<th scope="row"><?php esc_html_e( 'Features', 'smart-leading-net' ); ?></th>
							<td><?php sln_dm_admin_render_bullets( 'sln_dm_pricing_plans[' . $index . '][features]', $plan['features'] ?? array() ); ?></td>
						</tr>
						<?php
						sln_dm_admin_text_field( __( 'Button Text', 'smart-leading-net' ), 'sln_dm_pricing_plans[' . $index . '][button_text]', $plan['button_text'] ?? '' );
						sln_dm_admin_url_field( __( 'Button URL', 'smart-leading-net' ), 'sln_dm_pricing_plans[' . $index . '][button_url]', $plan['button_url'] ?? '' );
						sln_dm_admin_select_field( __( 'Button Style', 'smart-leading-net' ), 'sln_dm_pricing_plans[' . $index . '][button_style]', $plan['button_style'] ?? 'primary', sln_dm_admin_button_style_options() );
						sln_dm_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_dm_pricing_plans[' . $index . '][active]', ! empty( $plan['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_dm_admin_repeater_toolbar( __( 'Add Pricing Plan', 'smart-leading-net' ) ); ?>
	</div>
	<?php
}

/**
 * Render FAQ metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_dm_render_faq_metabox( $post ) {
	$section = sln_dm_admin_get_section( $post->ID, SLN_DM_FAQ_SECTION_META, sln_dm_default_faq_section() );
	$items   = sln_dm_admin_get_rows( $post->ID, SLN_DM_FAQ_ITEMS_META, sln_dm_default_faq_items() );
	?>
	<table class="form-table sln-dm-admin__table" role="presentation">
		<?php
		sln_dm_admin_text_field( __( 'Small Heading', 'smart-leading-net' ), 'sln_dm_faq_section[small_heading]', $section['small_heading'] );
		sln_dm_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_dm_faq_section[main_heading]', $section['main_heading'] );
		sln_dm_admin_text_field( __( 'Highlighted Text', 'smart-leading-net' ), 'sln_dm_faq_section[highlighted_text]', $section['highlighted_text'] );
		sln_dm_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-dm-faq-desc', 'sln_dm_faq_section[description]', $section['description'] );
		?>
	</table>

	<div class="sln-dm-admin__repeatable" data-row-selector=".sln-dm-admin__faq-row" data-name-prefix="sln_dm_faq_items">
		<h3><?php esc_html_e( 'FAQ Items', 'smart-leading-net' ); ?></h3>
		<div class="sln-dm-admin__repeatable-list">
			<?php foreach ( $items as $index => $item ) : ?>
				<div class="sln-dm-admin__repeatable-row sln-dm-admin__faq-row">
					<div class="sln-dm-admin__row-head">
						<strong><?php echo esc_html( $item['question'] ?: __( 'FAQ Item', 'smart-leading-net' ) ); ?></strong>
						<span class="sln-dm-admin__row-actions">
							<button type="button" class="button sln-dm-admin__move-up">&#8593;</button>
							<button type="button" class="button sln-dm-admin__move-down">&#8595;</button>
							<button type="button" class="button-link-delete sln-dm-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<table class="form-table" role="presentation">
						<?php
						sln_dm_admin_text_field( __( 'Question', 'smart-leading-net' ), 'sln_dm_faq_items[' . $index . '][question]', $item['question'] ?? '' );
						sln_dm_admin_editor_field( __( 'Answer', 'smart-leading-net' ), 'sln-dm-faq-item-' . $index, 'sln_dm_faq_items[' . $index . '][answer]', $item['answer'] ?? '' );
						sln_dm_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_dm_faq_items[' . $index . '][active]', ! empty( $item['active'] ) );
						?>
					</table>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_dm_admin_repeater_toolbar( __( 'Add FAQ Item', 'smart-leading-net' ) ); ?>
	</div>
	<?php
}

/**
 * Render final CTA metabox.
 *
 * @param WP_Post $post Current post.
 */
function sln_dm_render_final_cta_metabox( $post ) {
	$data = sln_dm_admin_get_section( $post->ID, SLN_DM_FINAL_CTA_META, sln_dm_default_final_cta() );
	?>
	<table class="form-table sln-dm-admin__table" role="presentation">
		<?php
		sln_dm_admin_text_field( __( 'Small Heading', 'smart-leading-net' ), 'sln_dm_final_cta[small_heading]', $data['small_heading'] );
		sln_dm_admin_text_field( __( 'Main Heading', 'smart-leading-net' ), 'sln_dm_final_cta[main_heading]', $data['main_heading'] );
		sln_dm_admin_text_field( __( 'Highlighted Text', 'smart-leading-net' ), 'sln_dm_final_cta[highlighted_text]', $data['highlighted_text'] );
		sln_dm_admin_editor_field( __( 'Description', 'smart-leading-net' ), 'sln-dm-final-cta-desc', 'sln_dm_final_cta[description]', $data['description'] );
		?>
		<tr>
			<th scope="row"><?php esc_html_e( 'Benefits', 'smart-leading-net' ); ?></th>
			<td><?php sln_dm_admin_render_benefits( 'sln_dm_final_cta[benefits]', $data['benefits'] ?? array() ); ?></td>
		</tr>
		<?php
		sln_dm_admin_text_field( __( 'Button Text', 'smart-leading-net' ), 'sln_dm_final_cta[button_text]', $data['button_text'] );
		sln_dm_admin_url_field( __( 'Button URL', 'smart-leading-net' ), 'sln_dm_final_cta[button_url]', $data['button_url'] );
		sln_dm_admin_text_field( __( 'Website Text', 'smart-leading-net' ), 'sln_dm_final_cta[website_text]', $data['website_text'] );
		sln_dm_admin_url_field( __( 'Website URL', 'smart-leading-net' ), 'sln_dm_final_cta[website_url]', $data['website_url'] );
		sln_dm_admin_text_field( __( 'Bottom Note', 'smart-leading-net' ), 'sln_dm_final_cta[bottom_note]', $data['bottom_note'] );
		sln_dm_admin_checkbox_field( __( 'Status', 'smart-leading-net' ), 'sln_dm_final_cta[active]', ! empty( $data['active'] ) );
		?>
	</table>
	<?php
}
