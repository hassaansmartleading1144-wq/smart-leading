<?php
/**
 * PPC & Google Ads page — admin field render helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether the current page edit screen should show PPC & Google Ads fields.
 *
 * @param WP_Post|null $post Post object.
 * @return bool
 */
function sln_ppc_admin_is_target_page( $post = null ) {
	if ( ! $post instanceof WP_Post ) {
		global $post;
	}

	if ( ! $post instanceof WP_Post || 'page' !== $post->post_type ) {
		return false;
	}

	return SLN_PPC_TEMPLATE === get_page_template_slug( $post->ID );
}

/**
 * Render a text input row.
 *
 * @param string $label Field label.
 * @param string $name  Input name.
 * @param string $value Field value.
 */
function sln_ppc_admin_text_field( $label, $name, $value ) {
	?>
	<tr>
		<th scope="row"><label for="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $label ); ?></label></th>
		<td><input id="<?php echo esc_attr( $name ); ?>" type="text" class="large-text" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" /></td>
	</tr>
	<?php
}

/**
 * Render a number input row.
 *
 * @param string $label Field label.
 * @param string $name  Input name.
 * @param mixed  $value Field value.
 * @param string $step  Number step.
 */
function sln_ppc_admin_number_field( $label, $name, $value, $step = '1' ) {
	?>
	<tr>
		<th scope="row"><label for="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $label ); ?></label></th>
		<td><input id="<?php echo esc_attr( $name ); ?>" type="number" step="<?php echo esc_attr( $step ); ?>" class="regular-text" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" /></td>
	</tr>
	<?php
}

/**
 * Render a URL input row.
 *
 * @param string $label Field label.
 * @param string $name  Input name.
 * @param string $value Field value.
 */
function sln_ppc_admin_url_field( $label, $name, $value ) {
	?>
	<tr>
		<th scope="row"><label for="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $label ); ?></label></th>
		<td><input id="<?php echo esc_attr( $name ); ?>" type="text" class="large-text code" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php esc_attr_e( 'https://, /page-slug/, #anchor', 'smart-leading-net' ); ?>" /></td>
	</tr>
	<?php
}

/**
 * Render a textarea row.
 *
 * @param string $label Field label.
 * @param string $name  Input name.
 * @param string $value Field value.
 * @param int    $rows  Textarea rows.
 */
function sln_ppc_admin_textarea_field( $label, $name, $value, $rows = 4 ) {
	?>
	<tr>
		<th scope="row"><label for="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $label ); ?></label></th>
		<td><textarea id="<?php echo esc_attr( $name ); ?>" class="large-text" rows="<?php echo esc_attr( (string) $rows ); ?>" name="<?php echo esc_attr( $name ); ?>"><?php echo esc_textarea( $value ); ?></textarea></td>
	</tr>
	<?php
}

/**
 * Render a checkbox row.
 *
 * @param string $label Field label.
 * @param string $name  Input name.
 * @param bool   $value Checked state.
 * @param string $text  Checkbox label text.
 */
function sln_ppc_admin_checkbox_field( $label, $name, $value, $text = '' ) {
	if ( '' === $text ) {
		$text = __( 'Active', 'smart-leading-net' );
	}
	?>
	<tr>
		<th scope="row"><?php echo esc_html( $label ); ?></th>
		<td>
			<label>
				<input type="checkbox" name="<?php echo esc_attr( $name ); ?>" value="1" <?php checked( $value ); ?> />
				<?php echo esc_html( $text ); ?>
			</label>
		</td>
	</tr>
	<?php
}

/**
 * Render a select row.
 *
 * @param string               $label   Field label.
 * @param string               $name    Input name.
 * @param string               $value   Selected value.
 * @param array<string,string> $options Option map.
 */
function sln_ppc_admin_select_field( $label, $name, $value, $options ) {
	?>
	<tr>
		<th scope="row"><label for="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $label ); ?></label></th>
		<td>
			<select id="<?php echo esc_attr( $name ); ?>" name="<?php echo esc_attr( $name ); ?>">
				<?php foreach ( $options as $option_value => $option_label ) : ?>
					<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $value, $option_value ); ?>><?php echo esc_html( $option_label ); ?></option>
				<?php endforeach; ?>
			</select>
		</td>
	</tr>
	<?php
}

/**
 * Render a WYSIWYG row.
 *
 * @param string $label     Field label.
 * @param string $editor_id Editor ID.
 * @param string $name      Input name.
 * @param string $content   Field content.
 */
function sln_ppc_admin_editor_field( $label, $editor_id, $name, $content ) {
	?>
	<tr>
		<th scope="row"><label for="<?php echo esc_attr( $editor_id ); ?>"><?php echo esc_html( $label ); ?></label></th>
		<td class="sln-ppc-admin__editor-field">
			<?php sln_growth_page_render_wysiwyg_editor( $editor_id, $name, $content ); ?>
		</td>
	</tr>
	<?php
}

/**
 * Render a media upload row.
 *
 * @param string $label         Field label.
 * @param string $name          Input name.
 * @param int    $attachment_id Attachment ID.
 */
function sln_ppc_admin_media_field( $label, $name, $attachment_id ) {
	?>
	<tr>
		<th scope="row"><?php echo esc_html( $label ); ?></th>
		<td><?php sln_our_services_render_media_field( $name, $attachment_id, 'SVG, PNG, JPG, WEBP' ); ?></td>
	</tr>
	<?php
}

/**
 * Visual style select options.
 *
 * @return array<string, string>
 */
function sln_ppc_admin_visual_style_options() {
	return array(
		'default' => __( 'Default', 'smart-leading-net' ),
		'orange'  => __( 'Orange', 'smart-leading-net' ),
		'blue'    => __( 'Blue', 'smart-leading-net' ),
		'green'   => __( 'Green', 'smart-leading-net' ),
	);
}

/**
 * Button style select options.
 *
 * @return array<string, string>
 */
function sln_ppc_admin_button_style_options() {
	return array(
		'primary' => __( 'Primary', 'smart-leading-net' ),
		'ghost'   => __( 'Ghost', 'smart-leading-net' ),
	);
}

/**
 * Render repeater move/remove controls.
 *
 * @param string $add_label Add button label.
 */
function sln_ppc_admin_repeater_toolbar( $add_label ) {
	?>
	<p class="sln-ppc-admin__toolbar">
		<button type="button" class="button sln-ppc-admin__add-row"><?php echo esc_html( $add_label ); ?></button>
	</p>
	<?php
}

/**
 * Stored section data for admin forms.
 *
 * @param int    $post_id  Post ID.
 * @param string $meta_key Meta key.
 * @param array  $defaults Defaults.
 * @return array
 */
function sln_ppc_admin_get_section( $post_id, $meta_key, $defaults ) {
	return sln_ppc_merge_section(
		$defaults,
		sln_ppc_get_meta_or_default( $post_id, $meta_key, $defaults )
	);
}

/**
 * Stored repeater rows for admin forms.
 *
 * @param int    $post_id  Post ID.
 * @param string $meta_key Meta key.
 * @param array  $defaults Defaults.
 * @return array
 */
function sln_ppc_admin_get_rows( $post_id, $meta_key, $defaults ) {
	$stored = sln_ppc_get_meta_or_default( $post_id, $meta_key, $defaults );

	return is_array( $stored ) && ! empty( $stored ) ? $stored : $defaults;
}

/**
 * Render nested bullet repeater inputs.
 *
 * @param string             $prefix Name prefix.
 * @param array<int, string> $items  Bullet items.
 */
function sln_ppc_admin_render_bullets( $prefix, $items ) {
	if ( empty( $items ) ) {
		$items = array( '' );
	}
	?>
	<div class="sln-ppc-admin__nested-list" data-prefix="<?php echo esc_attr( $prefix ); ?>">
		<?php foreach ( $items as $index => $item ) : ?>
			<div class="sln-ppc-admin__nested-row">
				<input type="text" class="regular-text" name="<?php echo esc_attr( $prefix . '[' . $index . ']' ); ?>" value="<?php echo esc_attr( $item ); ?>" />
				<button type="button" class="button-link-delete sln-ppc-admin__remove-nested"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
			</div>
		<?php endforeach; ?>
		<button type="button" class="button sln-ppc-admin__add-nested"><?php esc_html_e( 'Add Bullet', 'smart-leading-net' ); ?></button>
	</div>
	<?php
}

/**
 * Render nested pricing feature inputs.
 *
 * @param string                           $prefix Name prefix.
 * @param array<int, array<string, mixed>> $items  Feature rows.
 */
function sln_ppc_admin_render_features( $prefix, $items ) {
	if ( empty( $items ) ) {
		$items = array(
			array(
				'text'      => '',
				'highlight' => false,
				'active'    => true,
			),
		);
	}
	?>
	<div class="sln-ppc-admin__nested-list sln-ppc-admin__nested-list--features" data-prefix="<?php echo esc_attr( $prefix ); ?>" data-type="features">
		<?php foreach ( $items as $index => $item ) : ?>
			<div class="sln-ppc-admin__nested-row sln-ppc-admin__nested-row--features">
				<input type="text" class="regular-text" name="<?php echo esc_attr( $prefix . '[' . $index . '][text]' ); ?>" value="<?php echo esc_attr( $item['text'] ?? '' ); ?>" />
				<label>
					<input type="checkbox" name="<?php echo esc_attr( $prefix . '[' . $index . '][highlight]' ); ?>" value="1" <?php checked( ! empty( $item['highlight'] ) ); ?> />
					<?php esc_html_e( 'Highlight', 'smart-leading-net' ); ?>
				</label>
				<label>
					<input type="checkbox" name="<?php echo esc_attr( $prefix . '[' . $index . '][active]' ); ?>" value="1" <?php checked( ! empty( $item['active'] ) ); ?> />
					<?php esc_html_e( 'Active', 'smart-leading-net' ); ?>
				</label>
				<button type="button" class="button-link-delete sln-ppc-admin__remove-nested"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
			</div>
		<?php endforeach; ?>
		<button type="button" class="button sln-ppc-admin__add-nested"><?php esc_html_e( 'Add Feature', 'smart-leading-net' ); ?></button>
	</div>
	<?php
}

/**
 * Render nested case metric inputs.
 *
 * @param string                           $prefix Name prefix.
 * @param array<int, array<string, mixed>> $items  Metric rows.
 */
function sln_ppc_admin_render_case_metrics( $prefix, $items ) {
	if ( empty( $items ) ) {
		$items = array(
			array(
				'prefix'        => '',
				'value'         => '',
				'decimals'      => '0',
				'suffix'        => '',
				'display_value' => '',
				'label'         => '',
				'visual_style'  => 'default',
			),
		);
	}
	?>
	<div class="sln-ppc-admin__nested-list sln-ppc-admin__nested-list--metrics" data-prefix="<?php echo esc_attr( $prefix ); ?>" data-type="metrics">
		<?php foreach ( $items as $index => $item ) : ?>
			<div class="sln-ppc-admin__nested-row sln-ppc-admin__nested-row--metrics">
				<input type="text" class="small-text" name="<?php echo esc_attr( $prefix . '[' . $index . '][prefix]' ); ?>" value="<?php echo esc_attr( $item['prefix'] ?? '' ); ?>" placeholder="<?php esc_attr_e( 'Prefix', 'smart-leading-net' ); ?>" />
				<input type="text" class="small-text" name="<?php echo esc_attr( $prefix . '[' . $index . '][value]' ); ?>" value="<?php echo esc_attr( $item['value'] ?? '' ); ?>" placeholder="<?php esc_attr_e( 'Value', 'smart-leading-net' ); ?>" />
				<input type="number" class="small-text" name="<?php echo esc_attr( $prefix . '[' . $index . '][decimals]' ); ?>" value="<?php echo esc_attr( $item['decimals'] ?? '0' ); ?>" placeholder="<?php esc_attr_e( 'Decimals', 'smart-leading-net' ); ?>" />
				<input type="text" class="small-text" name="<?php echo esc_attr( $prefix . '[' . $index . '][suffix]' ); ?>" value="<?php echo esc_attr( $item['suffix'] ?? '' ); ?>" placeholder="<?php esc_attr_e( 'Suffix', 'smart-leading-net' ); ?>" />
				<input type="text" class="regular-text" name="<?php echo esc_attr( $prefix . '[' . $index . '][display_value]' ); ?>" value="<?php echo esc_attr( $item['display_value'] ?? '' ); ?>" placeholder="<?php esc_attr_e( 'Display', 'smart-leading-net' ); ?>" />
				<input type="text" class="regular-text" name="<?php echo esc_attr( $prefix . '[' . $index . '][label]' ); ?>" value="<?php echo esc_attr( $item['label'] ?? '' ); ?>" placeholder="<?php esc_attr_e( 'Label', 'smart-leading-net' ); ?>" />
				<select name="<?php echo esc_attr( $prefix . '[' . $index . '][visual_style]' ); ?>">
					<?php foreach ( sln_ppc_admin_visual_style_options() as $option_value => $option_label ) : ?>
						<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $item['visual_style'] ?? 'default', $option_value ); ?>><?php echo esc_html( $option_label ); ?></option>
					<?php endforeach; ?>
				</select>
				<button type="button" class="button-link-delete sln-ppc-admin__remove-nested"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
			</div>
		<?php endforeach; ?>
		<button type="button" class="button sln-ppc-admin__add-nested"><?php esc_html_e( 'Add Metric', 'smart-leading-net' ); ?></button>
	</div>
	<?php
}

/**
 * Render case-study progress fields.
 *
 * @param string               $prefix Progress prefix.
 * @param array<string, mixed> $data   Progress data.
 */
function sln_ppc_admin_render_progress_fields( $prefix, $data ) {
	if ( ! is_array( $data ) ) {
		$data = array();
	}
	?>
	<div class="sln-ppc-admin__inline-fields">
		<input type="text" class="regular-text" name="<?php echo esc_attr( $prefix . '[label]' ); ?>" value="<?php echo esc_attr( $data['label'] ?? '' ); ?>" placeholder="<?php esc_attr_e( 'Label', 'smart-leading-net' ); ?>" />
		<input type="text" class="regular-text" name="<?php echo esc_attr( $prefix . '[value]' ); ?>" value="<?php echo esc_attr( $data['value'] ?? '' ); ?>" placeholder="<?php esc_attr_e( 'Value', 'smart-leading-net' ); ?>" />
		<input type="number" class="small-text" name="<?php echo esc_attr( $prefix . '[width]' ); ?>" value="<?php echo esc_attr( $data['width'] ?? 0 ); ?>" min="0" max="100" placeholder="<?php esc_attr_e( 'Width', 'smart-leading-net' ); ?>" />
	</div>
	<?php
}

/**
 * Render a field definition row.
 *
 * @param array<string, mixed> $field       Field definition.
 * @param string               $prefix      Name prefix.
 * @param array<string, mixed> $data        Data source.
 * @param string               $editor_base Editor ID base.
 */
function sln_ppc_admin_render_field( $field, $prefix, $data, $editor_base = '' ) {
	$key   = $field['key'];
	$type  = $field['type'] ?? 'text';
	$label = $field['label'];
	$name  = $prefix . '[' . $key . ']';
	$value = $data[ $key ] ?? ( $field['default'] ?? '' );

	switch ( $type ) {
		case 'url':
			sln_ppc_admin_url_field( $label, $name, $value );
			break;
		case 'textarea':
			sln_ppc_admin_textarea_field( $label, $name, $value, absint( $field['rows'] ?? 4 ) );
			break;
		case 'checkbox':
			sln_ppc_admin_checkbox_field( $label, $name, ! empty( $value ), $field['text'] ?? '' );
			break;
		case 'select':
			sln_ppc_admin_select_field( $label, $name, $value, $field['options'] ?? array() );
			break;
		case 'editor':
			$editor_id = $field['editor_id'] ?? sanitize_title( $editor_base . '-' . $key );
			sln_ppc_admin_editor_field( $label, $editor_id, $name, $value );
			break;
		case 'media':
			sln_ppc_admin_media_field( $label, $name, absint( $value ) );
			break;
		case 'number':
			sln_ppc_admin_number_field( $label, $name, $value, $field['step'] ?? '1' );
			break;
		case 'bullets':
			?>
			<tr>
				<th scope="row"><?php echo esc_html( $label ); ?></th>
				<td><?php sln_ppc_admin_render_bullets( $name, is_array( $value ) ? $value : array() ); ?></td>
			</tr>
			<?php
			break;
		case 'features':
			?>
			<tr>
				<th scope="row"><?php echo esc_html( $label ); ?></th>
				<td><?php sln_ppc_admin_render_features( $name, is_array( $value ) ? $value : array() ); ?></td>
			</tr>
			<?php
			break;
		case 'case_metrics':
			?>
			<tr>
				<th scope="row"><?php echo esc_html( $label ); ?></th>
				<td><?php sln_ppc_admin_render_case_metrics( $name, is_array( $value ) ? $value : array() ); ?></td>
			</tr>
			<?php
			break;
		case 'progress':
			?>
			<tr>
				<th scope="row"><?php echo esc_html( $label ); ?></th>
				<td><?php sln_ppc_admin_render_progress_fields( $name, is_array( $value ) ? $value : array() ); ?></td>
			</tr>
			<?php
			break;
		default:
			sln_ppc_admin_text_field( $label, $name, $value );
			break;
	}
}

/**
 * Render field definitions in a table.
 *
 * @param string               $prefix      Name prefix.
 * @param array<string, mixed> $data        Data source.
 * @param array<int, array>    $fields      Field definitions.
 * @param string               $editor_base Editor ID base.
 */
function sln_ppc_admin_render_fields_table( $prefix, $data, $fields, $editor_base = '' ) {
	?>
	<table class="form-table sln-ppc-admin__table" role="presentation">
		<?php
		foreach ( $fields as $field ) {
			sln_ppc_admin_render_field( $field, $prefix, $data, $editor_base );
		}
		?>
	</table>
	<?php
}

/**
 * Render a repeatable group.
 *
 * @param array<string, mixed> $args Repeater arguments.
 */
function sln_ppc_admin_render_repeater( $args ) {
	$title            = $args['title'];
	$name_prefix      = $args['name_prefix'];
	$row_class        = $args['row_class'];
	$rows             = $args['rows'];
	$fields           = $args['fields'];
	$add_label        = $args['add_label'];
	$row_title_key      = $args['row_title_key'] ?? '';
	$row_title          = $args['row_title'] ?? null;
	$row_title_fallback = $args['row_title_fallback'] ?? __( 'Item', 'smart-leading-net' );

	if ( empty( $rows ) ) {
		$rows = array( array() );
	}
	?>
	<div class="sln-ppc-admin__repeatable" data-row-selector=".<?php echo esc_attr( $row_class ); ?>" data-name-prefix="<?php echo esc_attr( $name_prefix ); ?>">
		<h3><?php echo esc_html( $title ); ?></h3>
		<div class="sln-ppc-admin__repeatable-list">
			<?php foreach ( $rows as $index => $row ) : ?>
				<?php
				if ( ! is_array( $row ) ) {
					$row = array();
				}

				if ( is_callable( $row_title ) ) {
					$heading = (string) call_user_func( $row_title, $row );
				} elseif ( $row_title_key ) {
					$heading = (string) ( $row[ $row_title_key ] ?? '' );
				} else {
					$heading = '';
				}
				?>
				<div class="sln-ppc-admin__repeatable-row <?php echo esc_attr( $row_class ); ?>">
					<div class="sln-ppc-admin__row-head">
						<strong><?php echo esc_html( $heading ? wp_trim_words( $heading, 10, '...' ) : $row_title_fallback ); ?></strong>
						<span class="sln-ppc-admin__row-actions">
							<button type="button" class="button sln-ppc-admin__move-up" aria-label="<?php esc_attr_e( 'Move up', 'smart-leading-net' ); ?>">&#8593;</button>
							<button type="button" class="button sln-ppc-admin__move-down" aria-label="<?php esc_attr_e( 'Move down', 'smart-leading-net' ); ?>">&#8595;</button>
							<button type="button" class="button-link-delete sln-ppc-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
						</span>
					</div>
					<?php sln_ppc_admin_render_fields_table( $name_prefix . '[' . $index . ']', $row, $fields, sanitize_title( $name_prefix . '-' . $index ) ); ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php sln_ppc_admin_repeater_toolbar( $add_label ); ?>
	</div>
	<?php
}
