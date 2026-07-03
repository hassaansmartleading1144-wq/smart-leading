<?php
/**
 * Growth Pages — shared Classic Editor helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shared wp_editor settings for Growth Page WYSIWYG fields.
 *
 * @return array<string, mixed>
 */
function sln_growth_page_get_wysiwyg_editor_settings() {
	return array(
		'textarea_rows' => 8,
		'media_buttons' => false,
		'teeny'         => false,
		'quicktags'     => true,
		'editor_class'  => 'sln-gp-admin__wysiwyg',
		'tinymce'       => array(
			'toolbar1'             => 'formatselect,bold,italic,underline,forecolor,backcolor,bullist,numlist,link,unlink,blockquote,alignleft,aligncenter,alignright',
			'toolbar2'             => '',
			'add_unload_trigger'   => false,
		),
	);
}

/**
 * Editor settings passed to wp.editor.initialize() for dynamic repeaters.
 *
 * @return array<string, mixed>
 */
function sln_growth_page_get_js_editor_settings() {
	return array(
		'tinymce'      => array(
			'wpautop'              => true,
			'toolbar1'             => 'formatselect,bold,italic,underline,forecolor,backcolor,bullist,numlist,link,unlink,blockquote,alignleft,aligncenter,alignright',
			'toolbar2'             => '',
			'add_unload_trigger'   => false,
		),
		'quicktags'    => true,
		'mediaButtons' => false,
	);
}

/**
 * Render a Growth Page WYSIWYG field.
 *
 * TinyMCE must flush to textarea before save; growth-pages-admin.js syncs on Update/Publish
 * mousedown only — it must never call preventDefault() on form#post submit.
 *
 * @param string               $editor_id Editor element ID.
 * @param string               $name      Input name attribute.
 * @param string               $content   Field content.
 * @param array<string, mixed> $overrides Optional wp_editor setting overrides.
 */
function sln_growth_page_render_wysiwyg_editor( $editor_id, $name, $content, $overrides = array() ) {
	$settings                  = wp_parse_args( $overrides, sln_growth_page_get_wysiwyg_editor_settings() );
	$settings['textarea_name'] = $name;

	wp_editor( $content, $editor_id, $settings );
}

/**
 * Sanitize WYSIWYG field content while preserving safe HTML.
 *
 * @param string $content Raw content.
 * @return string
 */
function sln_growth_page_sanitize_wysiwyg_content( $content ) {
	return wp_kses_post( wp_unslash( $content ) );
}

/**
 * Check whether WYSIWYG content has visible text or markup.
 *
 * @param string $content Field content.
 * @return bool
 */
function sln_growth_page_wysiwyg_has_content( $content ) {
	return '' !== trim( wp_strip_all_tags( (string) $content ) );
}

/**
 * Format WYSIWYG content for frontend output.
 *
 * @param string $content Field content.
 * @return string
 */
function sln_growth_page_format_wysiwyg_content( $content ) {
	return wp_kses_post( wpautop( (string) $content ) );
}

/**
 * Format WYSIWYG content for inline title + description blocks.
 *
 * Strips paragraph wrappers so text flows on one line with a bold title prefix.
 *
 * @param string $content Field content.
 * @return string
 */
function sln_growth_page_format_inline_wysiwyg_content( $content ) {
	$content = wp_kses_post( (string) $content );

	if ( '' === trim( wp_strip_all_tags( $content ) ) ) {
		return '';
	}

	$content = preg_replace( '#</p>\s*<p>#', ' ', $content );
	$content = preg_replace( '#^<p>#', '', trim( $content ) );
	$content = preg_replace( '#</p>$#', '', trim( $content ) );

	return trim( $content );
}
