<?php
/**
 * Web Development Services page — admin field render helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether the current page edit screen should show Web Development fields.
 *
 * @param WP_Post|null $post Post object.
 * @return bool
 */
function sln_wd_admin_is_target_page( $post = null ) {
	if ( ! $post instanceof WP_Post ) {
		global $post;
	}

	if ( ! $post instanceof WP_Post || 'page' !== $post->post_type ) {
		return false;
	}

	return SLN_WD_TEMPLATE === get_page_template_slug( $post->ID );
}

/**
 * Render a text input row.
 *
 * @param string $label Field label.
 * @param string $name  Input name.
 * @param string $value Field value.
 */
function sln_wd_admin_text_field( $label, $name, $value ) {
	?>
	<tr>
		<th scope="row"><label for="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $label ); ?></label></th>
		<td><input id="<?php echo esc_attr( $name ); ?>" type="text" class="large-text" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" /></td>
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
function sln_wd_admin_url_field( $label, $name, $value ) {
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
function sln_wd_admin_textarea_field( $label, $name, $value, $rows = 4 ) {
	?>
	<tr>
		<th scope="row"><label for="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $label ); ?></label></th>
		<td><textarea id="<?php echo esc_attr( $name ); ?>" class="large-text" rows="<?php echo esc_attr( (string) $rows ); ?>" name="<?php echo esc_attr( $name ); ?>"><?php echo esc_textarea( $value ); ?></textarea></td>
	</tr>
	<?php
}

/**
 * Render a newline-separated options textarea row.
 *
 * @param string       $label Field label.
 * @param string       $name  Input name.
 * @param array|string $items Stored options.
 */
function sln_wd_admin_textarea_list_field( $label, $name, $items ) {
	if ( is_array( $items ) ) {
		$value = implode( "\n", $items );
	} else {
		$value = (string) $items;
	}
	?>
	<tr>
		<th scope="row"><label for="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $label ); ?></label></th>
		<td>
			<textarea id="<?php echo esc_attr( $name ); ?>" class="large-text code" rows="6" name="<?php echo esc_attr( $name ); ?>"><?php echo esc_textarea( $value ); ?></textarea>
			<p class="description"><?php esc_html_e( 'One option per line.', 'smart-leading-net' ); ?></p>
		</td>
	</tr>
	<?php
}

/**
 * Render a checkbox row.
 *
 * @param string $label Field label.
 * @param string $name  Input name.
 * @param bool   $value Checked state.
 */
function sln_wd_admin_checkbox_field( $label, $name, $value ) {
	?>
	<tr>
		<th scope="row"><?php echo esc_html( $label ); ?></th>
		<td>
			<label>
				<input type="checkbox" name="<?php echo esc_attr( $name ); ?>" value="1" <?php checked( $value ); ?> />
				<?php esc_html_e( 'Active', 'smart-leading-net' ); ?>
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
function sln_wd_admin_select_field( $label, $name, $value, $options ) {
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
function sln_wd_admin_editor_field( $label, $editor_id, $name, $content ) {
	?>
	<tr>
		<th scope="row"><label for="<?php echo esc_attr( $editor_id ); ?>"><?php echo esc_html( $label ); ?></label></th>
		<td class="sln-wd-admin__editor-field">
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
function sln_wd_admin_media_field( $label, $name, $attachment_id ) {
	?>
	<tr>
		<th scope="row"><?php echo esc_html( $label ); ?></th>
		<td><?php sln_our_services_render_media_field( $name, $attachment_id, 'SVG, PNG, JPG, WEBP' ); ?></td>
	</tr>
	<?php
}

/**
 * Render repeater move/remove controls.
 *
 * @param string $add_label Add button label.
 */
function sln_wd_admin_repeater_toolbar( $add_label ) {
	?>
	<p class="sln-wd-admin__toolbar">
		<button type="button" class="button sln-wd-admin__add-row"><?php echo esc_html( $add_label ); ?></button>
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
function sln_wd_admin_get_section( $post_id, $meta_key, $defaults ) {
	return sln_wd_merge_section(
		$defaults,
		sln_wd_get_meta_or_default( $post_id, $meta_key, $defaults )
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
function sln_wd_admin_get_rows( $post_id, $meta_key, $defaults ) {
	$stored = sln_wd_get_meta_or_default( $post_id, $meta_key, $defaults );

	return is_array( $stored ) && ! empty( $stored ) ? $stored : $defaults;
}

/**
 * Render nested feature string list inputs.
 *
 * @param string             $prefix Name prefix.
 * @param array<int, string> $items  Feature items.
 */
function sln_wd_admin_render_features( $prefix, $items ) {
	if ( empty( $items ) ) {
		$items = array( '' );
	}
	?>
	<div class="sln-wd-admin__nested-list sln-wd-admin__nested-list--features" data-prefix="<?php echo esc_attr( $prefix ); ?>" data-type="features">
		<?php foreach ( $items as $index => $item ) : ?>
			<div class="sln-wd-admin__nested-row sln-wd-admin__nested-row--features">
				<input type="text" class="regular-text" name="<?php echo esc_attr( $prefix . '[' . $index . ']' ); ?>" value="<?php echo esc_attr( $item ); ?>" />
				<button type="button" class="button-link-delete sln-wd-admin__remove-nested"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
			</div>
		<?php endforeach; ?>
		<button type="button" class="button sln-wd-admin__add-nested"><?php esc_html_e( 'Add Feature', 'smart-leading-net' ); ?></button>
	</div>
	<?php
}

/**
 * Render nested process step inputs.
 *
 * @param string                              $prefix Name prefix.
 * @param array<int, array<string, mixed>>    $steps  Process steps.
 */
function sln_wd_admin_render_process_steps( $prefix, $steps ) {
	if ( empty( $steps ) ) {
		$steps = array(
			array(
				'number'      => '',
				'title'       => '',
				'description' => '',
				'active'      => true,
			),
		);
	}
	?>
	<div class="sln-wd-admin__nested-list sln-wd-admin__nested-list--steps" data-prefix="<?php echo esc_attr( $prefix ); ?>" data-type="steps">
		<?php foreach ( $steps as $index => $step ) : ?>
			<div class="sln-wd-admin__nested-row sln-wd-admin__nested-row--steps">
				<input type="text" class="small-text" name="<?php echo esc_attr( $prefix . '[' . $index . '][number]' ); ?>" value="<?php echo esc_attr( $step['number'] ?? '' ); ?>" placeholder="<?php esc_attr_e( 'Number', 'smart-leading-net' ); ?>" />
				<input type="text" class="regular-text" name="<?php echo esc_attr( $prefix . '[' . $index . '][title]' ); ?>" value="<?php echo esc_attr( $step['title'] ?? '' ); ?>" placeholder="<?php esc_attr_e( 'Title', 'smart-leading-net' ); ?>" />
				<textarea class="large-text" rows="2" name="<?php echo esc_attr( $prefix . '[' . $index . '][description]' ); ?>" placeholder="<?php esc_attr_e( 'Description', 'smart-leading-net' ); ?>"><?php echo esc_textarea( $step['description'] ?? '' ); ?></textarea>
				<label>
					<input type="checkbox" name="<?php echo esc_attr( $prefix . '[' . $index . '][active]' ); ?>" value="1" <?php checked( ! empty( $step['active'] ) ); ?> />
					<?php esc_html_e( 'Active', 'smart-leading-net' ); ?>
				</label>
				<button type="button" class="button-link-delete sln-wd-admin__remove-nested"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
			</div>
		<?php endforeach; ?>
		<button type="button" class="button sln-wd-admin__add-nested"><?php esc_html_e( 'Add Step', 'smart-leading-net' ); ?></button>
	</div>
	<?php
}

/**
 * Render nested benefit repeater inputs (text + active).
 *
 * @param string                           $prefix Name prefix.
 * @param array<int, array<string, mixed>> $items  Benefit rows.
 */
function sln_wd_admin_render_benefits( $prefix, $items ) {
	if ( empty( $items ) ) {
		$items = array(
			array(
				'text'   => '',
				'active' => true,
			),
		);
	}
	?>
	<div class="sln-wd-admin__nested-list sln-wd-admin__nested-list--benefits" data-prefix="<?php echo esc_attr( $prefix ); ?>" data-type="benefits">
		<?php foreach ( $items as $index => $item ) : ?>
			<div class="sln-wd-admin__nested-row sln-wd-admin__nested-row--benefits">
				<input type="text" class="regular-text" name="<?php echo esc_attr( $prefix . '[' . $index . '][text]' ); ?>" value="<?php echo esc_attr( $item['text'] ?? '' ); ?>" />
				<label>
					<input type="checkbox" name="<?php echo esc_attr( $prefix . '[' . $index . '][active]' ); ?>" value="1" <?php checked( ! empty( $item['active'] ) ); ?> />
					<?php esc_html_e( 'Active', 'smart-leading-net' ); ?>
				</label>
				<button type="button" class="button-link-delete sln-wd-admin__remove-nested"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
			</div>
		<?php endforeach; ?>
		<button type="button" class="button sln-wd-admin__add-nested"><?php esc_html_e( 'Add Benefit', 'smart-leading-net' ); ?></button>
	</div>
	<?php
}
