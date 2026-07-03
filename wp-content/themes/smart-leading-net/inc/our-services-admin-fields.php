<?php
/**
 * Render repeatable services list for a tab.
 *
 * @param int   $tab_index Tab index.
 * @param array $services  Service rows.
 */
function sln_our_services_render_tab_services( $tab_index, $services ) {
	$option = SLN_OUR_SERVICES_OPTION;
	?>
	<div class="sln-os-admin__subsection">
		<h4><?php esc_html_e( 'Services Grid', 'smart-leading-net' ); ?></h4>
		<p class="description"><?php esc_html_e( 'Drag to reorder. Minimum six service items recommended.', 'smart-leading-net' ); ?></p>

		<div class="sln-os-admin__repeatable sln-os-admin__services-list" data-tab-index="<?php echo esc_attr( $tab_index ); ?>">
			<?php foreach ( $services as $service_index => $service ) : ?>
				<div class="sln-os-admin__repeatable-row sln-os-admin__service-row">
					<span class="sln-os-admin__drag-handle dashicons dashicons-menu" aria-hidden="true"></span>
					<div class="sln-os-admin__repeatable-fields">
						<label>
							<span class="sln-os-admin__field-label"><?php esc_html_e( 'Service SVG Icon', 'smart-leading-net' ); ?></span>
							<?php sln_our_services_render_media_field( $option . '[tabs][' . $tab_index . '][services][' . $service_index . '][icon_id]', $service['icon_id'], 'SVG' ); ?>
						</label>
						<label>
							<span class="sln-os-admin__field-label"><?php esc_html_e( 'Service Title', 'smart-leading-net' ); ?></span>
							<input type="text" class="regular-text" name="<?php echo esc_attr( $option ); ?>[tabs][<?php echo esc_attr( $tab_index ); ?>][services][<?php echo esc_attr( $service_index ); ?>][title]" value="<?php echo esc_attr( $service['title'] ); ?>" />
						</label>
						<label>
							<span class="sln-os-admin__field-label"><?php esc_html_e( 'Service URL', 'smart-leading-net' ); ?></span>
							<input type="url" class="regular-text" name="<?php echo esc_attr( $option ); ?>[tabs][<?php echo esc_attr( $tab_index ); ?>][services][<?php echo esc_attr( $service_index ); ?>][url]" value="<?php echo esc_attr( $service['url'] ); ?>" placeholder="#" />
						</label>
					</div>
					<button type="button" class="button-link-delete sln-os-admin__remove-row"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
				</div>
			<?php endforeach; ?>
		</div>
		<p>
			<button type="button" class="button button-secondary sln-os-admin__add-service" data-tab-index="<?php echo esc_attr( $tab_index ); ?>">
				<?php esc_html_e( 'Add Service', 'smart-leading-net' ); ?>
			</button>
		</p>
	</div>
	<?php
}

/**
 * Render result blocks for a tab.
 *
 * @param int   $tab_index Tab index.
 * @param array $results   Result rows.
 */
function sln_our_services_render_tab_results( $tab_index, $results ) {
	$option = SLN_OUR_SERVICES_OPTION;
	?>
	<div class="sln-os-admin__subsection">
		<h4><?php esc_html_e( 'Results Grid', 'smart-leading-net' ); ?></h4>
		<p class="description"><?php esc_html_e( 'Configure six result blocks for this tab.', 'smart-leading-net' ); ?></p>

		<div class="sln-os-admin__results-list">
			<?php foreach ( $results as $result_index => $result ) : ?>
				<div class="sln-os-admin__result-row" data-index="<?php echo esc_attr( $result_index ); ?>">
					<h5><?php echo esc_html( sprintf( __( 'Block %d', 'smart-leading-net' ), $result_index + 1 ) ); ?></h5>
					<table class="form-table" role="presentation">
						<tr>
							<th scope="row"><label><?php esc_html_e( 'Field Type', 'smart-leading-net' ); ?></label></th>
							<td>
								<select class="sln-os-admin__result-type" name="<?php echo esc_attr( $option ); ?>[tabs][<?php echo esc_attr( $tab_index ); ?>][results][<?php echo esc_attr( $result_index ); ?>][type]">
									<option value="number" <?php selected( 'number', $result['type'] ); ?>><?php esc_html_e( 'Number', 'smart-leading-net' ); ?></option>
									<option value="logo" <?php selected( 'logo', $result['type'] ); ?>><?php esc_html_e( 'Logo', 'smart-leading-net' ); ?></option>
								</select>
							</td>
						</tr>
					</table>
					<div class="sln-os-admin__result-fields sln-os-admin__result-fields--number" <?php echo 'number' === $result['type'] ? '' : 'hidden'; ?>>
						<table class="form-table" role="presentation">
							<tr>
								<th scope="row"><label><?php esc_html_e( 'Value', 'smart-leading-net' ); ?></label></th>
								<td><input type="text" class="regular-text" name="<?php echo esc_attr( $option ); ?>[tabs][<?php echo esc_attr( $tab_index ); ?>][results][<?php echo esc_attr( $result_index ); ?>][number_value]" value="<?php echo esc_attr( $result['number_value'] ); ?>" placeholder="320%" /></td>
							</tr>
							<tr>
								<th scope="row"><label><?php esc_html_e( 'Description', 'smart-leading-net' ); ?></label></th>
								<td><input type="text" class="large-text" name="<?php echo esc_attr( $option ); ?>[tabs][<?php echo esc_attr( $tab_index ); ?>][results][<?php echo esc_attr( $result_index ); ?>][number_subtext]" value="<?php echo esc_attr( $result['number_subtext'] ); ?>" /></td>
							</tr>
						</table>
					</div>
					<div class="sln-os-admin__result-fields sln-os-admin__result-fields--logo" <?php echo 'logo' === $result['type'] ? '' : 'hidden'; ?>>
						<table class="form-table" role="presentation">
							<tr>
								<th scope="row"><?php esc_html_e( 'Logo Upload', 'smart-leading-net' ); ?></th>
								<td><?php sln_our_services_render_media_field( $option . '[tabs][' . $tab_index . '][results][' . $result_index . '][logo_id]', $result['logo_id'], 'WEBP' ); ?></td>
							</tr>
							<tr>
								<th scope="row"><label><?php esc_html_e( 'Logo Alt Text', 'smart-leading-net' ); ?></label></th>
								<td><input type="text" class="regular-text" name="<?php echo esc_attr( $option ); ?>[tabs][<?php echo esc_attr( $tab_index ); ?>][results][<?php echo esc_attr( $result_index ); ?>][logo_alt]" value="<?php echo esc_attr( $result['logo_alt'] ); ?>" /></td>
							</tr>
						</table>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
}
