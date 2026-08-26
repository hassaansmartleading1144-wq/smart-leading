<?php
/**
 * AEO Services page — admin field render helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Resolve the page currently being edited in wp-admin.
 *
 * @param WP_Post|null $post Post object.
 * @return WP_Post|null
 */
function sln_aeo_admin_resolve_editing_post( $post = null ) {
	if ( $post instanceof WP_Post ) {
		return $post;
	}

	$post_id = 0;

	if ( isset( $_GET['post'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$post_id = absint( $_GET['post'] );
	} elseif ( isset( $_POST['post_ID'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$post_id = absint( $_POST['post_ID'] );
	}

	if ( $post_id ) {
		$found = get_post( $post_id );

		if ( $found instanceof WP_Post ) {
			return $found;
		}
	}

	global $post;

	return $post instanceof WP_Post ? $post : null;
}

/**
 * Whether the current page edit screen should show AEO fields.
 *
 * @param WP_Post|null $post Post object.
 * @return bool
 */
function sln_aeo_admin_is_target_page( $post = null ) {
	$post = sln_aeo_admin_resolve_editing_post( $post );

	if ( ! $post instanceof WP_Post || 'page' !== $post->post_type ) {
		return false;
	}

	$template = (string) get_page_template_slug( $post->ID );
	$expected = defined( 'SLN_AEO_TEMPLATE' ) ? SLN_AEO_TEMPLATE : 'aeo-page-template.php';

	if ( $expected === $template || basename( $template ) === $expected ) {
		return true;
	}

	if ( defined( 'SLN_AEO_SLUG' ) && SLN_AEO_SLUG === $post->post_name ) {
		return true;
	}

	$ensured_id = absint( get_option( 'sln_aeo_page_ensured' ) );

	if ( $ensured_id && (int) $post->ID === $ensured_id ) {
		return true;
	}

	return false !== stripos( (string) $post->post_title, 'Answer Engine Optimization' );
}

/**
 * Render a text input row.
 *
 * @param string $label Field label.
 * @param string $name  Input name.
 * @param string $value Field value.
 */
function sln_aeo_admin_text_field( $label, $name, $value ) {
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
function sln_aeo_admin_url_field( $label, $name, $value ) {
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
 * @param int    $rows  Rows.
 */
function sln_aeo_admin_textarea_field( $label, $name, $value, $rows = 4 ) {
	?>
	<tr>
		<th scope="row"><label for="<?php echo esc_attr( $name ); ?>"><?php echo esc_html( $label ); ?></label></th>
		<td><textarea id="<?php echo esc_attr( $name ); ?>" class="large-text" name="<?php echo esc_attr( $name ); ?>" rows="<?php echo esc_attr( (string) $rows ); ?>"><?php echo esc_textarea( $value ); ?></textarea></td>
	</tr>
	<?php
}

/**
 * Render a checkbox row.
 *
 * @param string $label Field label.
 * @param string $name  Input name.
 * @param bool   $value Checked state.
 * @param string $help  Checkbox label.
 */
function sln_aeo_admin_checkbox_field( $label, $name, $value, $help = '' ) {
	if ( '' === $help ) {
		$help = __( 'Active', 'smart-leading-net' );
	}
	?>
	<tr>
		<th scope="row"><?php echo esc_html( $label ); ?></th>
		<td>
			<label>
				<input type="checkbox" name="<?php echo esc_attr( $name ); ?>" value="1" <?php checked( $value ); ?> />
				<?php echo esc_html( $help ); ?>
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
function sln_aeo_admin_select_field( $label, $name, $value, $options ) {
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
 * Icon select options, including an empty choice.
 *
 * @return array<string, string>
 */
function sln_aeo_admin_icon_options() {
	$options = array(
		'' => __( '— Select icon —', 'smart-leading-net' ),
	);

	if ( function_exists( 'sln_aeo_get_icon_choices' ) ) {
		$options = array_merge( $options, sln_aeo_get_icon_choices() );
	}

	return $options;
}

/**
 * Evolution stage modifier options.
 *
 * @return array<string, string>
 */
function sln_aeo_admin_stage_modifier_options() {
	return array(
		''      => __( 'Default', 'smart-leading-net' ),
		'mid'   => __( 'Mid (highlighted)', 'smart-leading-net' ),
		'final' => __( 'Final', 'smart-leading-net' ),
	);
}

/**
 * Render repeater add button.
 *
 * @param string $add_label Add button label.
 */
function sln_aeo_admin_repeater_toolbar( $add_label, $name_prefix = '' ) {
	if ( $name_prefix ) {
		printf(
			'<input type="hidden" name="%s" value="1" />',
			esc_attr( $name_prefix . '_posted' )
		);
	}
	?>
	<p class="sln-aeo-admin__toolbar">
		<button type="button" class="button sln-aeo-admin__add-row"><?php echo esc_html( $add_label ); ?></button>
	</p>
	<?php
}

/**
 * Repeater row action buttons.
 */
function sln_aeo_admin_row_actions() {
	?>
	<span class="sln-aeo-admin__row-actions">
		<button type="button" class="button sln-aeo-admin__move-up" aria-label="<?php esc_attr_e( 'Move up', 'smart-leading-net' ); ?>">&#8593;</button>
		<button type="button" class="button sln-aeo-admin__move-down" aria-label="<?php esc_attr_e( 'Move down', 'smart-leading-net' ); ?>">&#8595;</button>
		<button type="button" class="button-link-delete sln-aeo-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
	</span>
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
function sln_aeo_admin_get_section( $post_id, $meta_key, $defaults ) {
	return sln_aeo_merge_section(
		$defaults,
		sln_aeo_get_meta_or_default( $post_id, $meta_key, $defaults )
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
function sln_aeo_admin_get_rows( $post_id, $meta_key, $defaults ) {
	if ( ! metadata_exists( 'post', $post_id, $meta_key ) ) {
		return $defaults;
	}

	$stored = get_post_meta( $post_id, $meta_key, true );

	if ( is_array( $stored ) && ! empty( $stored ) ) {
		return $stored;
	}

	return sln_aeo_admin_blank_rows( $defaults );
}

/**
 * One empty repeater row so "Add" still works after all items are removed.
 *
 * @param array<int, array<string, mixed>> $defaults Default rows.
 * @return array<int, array<string, mixed>>
 */
function sln_aeo_admin_blank_rows( $defaults ) {
	if ( empty( $defaults[0] ) || ! is_array( $defaults[0] ) ) {
		return $defaults;
	}

	$blank = $defaults[0];

	foreach ( $blank as $key => $value ) {
		if ( 'active' === $key ) {
			$blank[ $key ] = true;
		} elseif ( is_bool( $value ) ) {
			$blank[ $key ] = false;
		} elseif ( is_string( $value ) ) {
			$blank[ $key ] = '';
		}
	}

	return array( $blank );
}
