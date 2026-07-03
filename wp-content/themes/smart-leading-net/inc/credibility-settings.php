<?php
/**
 * Credibility section — settings, defaults, and helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_CREDIBILITY_OPTION', 'sln_credibility_settings' );
define( 'SLN_CREDIBILITY_UPLOADS_DIR', '2026/05' );
define( 'SLN_CREDIBILITY_SLIDE_ONE_COUNT', 14 );
define( 'SLN_CREDIBILITY_ROW_COLUMNS', 7 );

/**
 * Legacy basename aliases for uploads resolution.
 *
 * @return array<string, string>
 */
function sln_credibility_get_logo_aliases() {
	return array(
		'badger-cabinets' => 'badger-cabinest',
		'mic-logo'        => 'mic-logo_',
	);
}

/**
 * Normalize a legacy logo basename.
 *
 * @param string $basename Logo basename.
 * @return string
 */
function sln_credibility_normalize_logo_basename( $basename ) {
	$basename = sanitize_file_name( $basename );
	$aliases  = sln_credibility_get_logo_aliases();
	$key      = strtolower( $basename );

	if ( isset( $aliases[ $key ] ) ) {
		return $aliases[ $key ];
	}

	if ( isset( $aliases[ $basename ] ) ) {
		return $aliases[ $basename ];
	}

	return $basename;
}

/**
 * Default client logo rows.
 *
 * @return array<int, array{image_id:int,image:string,url:string}>
 */
function sln_get_default_credibility_logos() {
	$basenames = array(
		'anatolia_logo',
		'artline_logo',
		'arynova_logo',
		'ASHIKA-Logo',
		'badger-cabinest',
		'badger-granite-logo',
		'cabinetsmke_logo',
		'cb_logo',
		'clarksville_standard_logo_bold_v2',
		'columbus_cabinets_city_logo',
		'cypress-dental-logo',
		'epoxy-logo',
		'esos_countertops_cabinets_logo',
		'glowup-dentistry',
		'keystone-logo',
		'MIC-Logo_',
		'noveMed-urgent-logo',
		'Pali-pali-logo',
		'pink-socials-logo',
		'pressure-king-logo',
		'rug_gallery-logo',
	);

	$logos = array();

	foreach ( $basenames as $basename ) {
		$logos[] = array(
			'image_id' => 0,
			'image'    => $basename,
			'url'      => '',
		);
	}

	return $logos;
}

/**
 * Default settings.
 *
 * @return array{logos:array}
 */
function sln_get_credibility_default_settings() {
	return array(
		'logos' => sln_get_default_credibility_logos(),
	);
}

/**
 * Index uploaded logo files by lowercase basename.
 *
 * @return array<string, string>
 */
function sln_credibility_get_upload_file_index() {
	static $index = null;

	if ( null !== $index ) {
		return $index;
	}

	$index = array();
	$path  = WP_CONTENT_DIR . '/uploads/' . SLN_CREDIBILITY_UPLOADS_DIR . '/';

	if ( ! is_dir( $path ) ) {
		return $index;
	}

	$files = scandir( $path );

	if ( false === $files ) {
		return $index;
	}

	foreach ( $files as $file ) {
		if ( '.' === $file || '..' === $file ) {
			continue;
		}

		$extension = strtolower( pathinfo( $file, PATHINFO_EXTENSION ) );

		if ( ! in_array( $extension, array( 'webp', 'png', 'jpg', 'jpeg', 'svg', 'avif' ), true ) ) {
			continue;
		}

		$key = strtolower( pathinfo( $file, PATHINFO_FILENAME ) );

		if ( ! isset( $index[ $key ] ) ) {
			$index[ $key ] = $file;
		}
	}

	return $index;
}

/**
 * Resolve a legacy uploads logo URL using the actual file extension.
 *
 * @param string $basename Filename without extension.
 * @return string
 */
function sln_get_credibility_legacy_logo_url( $basename ) {
	$basename = trim( $basename );

	if ( '' === $basename ) {
		return '';
	}

	$file_index = sln_credibility_get_upload_file_index();
	$basename   = sln_credibility_normalize_logo_basename( $basename );
	$key        = strtolower( $basename );

	if ( ! isset( $file_index[ $key ] ) ) {
		return '';
	}

	$uploads_url = trailingslashit( content_url( 'uploads/' . SLN_CREDIBILITY_UPLOADS_DIR ) );

	return $uploads_url . $file_index[ $key ];
}

/**
 * Resolve a credibility logo image URL.
 *
 * @param int    $image_id        Attachment ID.
 * @param string $legacy_basename Legacy uploads basename without extension.
 * @return string
 */
function sln_get_credibility_logo_image_url( $image_id, $legacy_basename = '' ) {
	$image_id = absint( $image_id );

	if ( $image_id ) {
		$src = wp_get_attachment_image_url( $image_id, 'full' );

		if ( $src ) {
			return $src;
		}
	}

	if ( $legacy_basename ) {
		return sln_get_credibility_legacy_logo_url( $legacy_basename );
	}

	return '';
}

/**
 * Sanitize logo repeater rows.
 *
 * @param array $logos Raw logos.
 * @return array
 */
function sln_sanitize_credibility_logos( $logos ) {
	$output = array();

	if ( ! is_array( $logos ) ) {
		return sln_get_default_credibility_logos();
	}

	foreach ( $logos as $logo ) {
		if ( ! is_array( $logo ) ) {
			continue;
		}

		$image_id = sln_sanitize_media_attachment_id( $logo['image_id'] ?? 0 );
		$legacy   = sln_credibility_normalize_logo_basename( sln_sanitize_legacy_upload_basename( $logo['image'] ?? '' ) );
		$url      = esc_url_raw( $logo['url'] ?? '' );

		if ( ! $image_id && '' === $legacy ) {
			continue;
		}

		$output[] = array(
			'image_id' => $image_id,
			'image'    => $legacy,
			'url'      => $url,
		);
	}

	if ( empty( $output ) ) {
		return sln_get_default_credibility_logos();
	}

	return $output;
}

/**
 * Normalize saved settings.
 *
 * @param array $saved Saved option data.
 * @return array
 */
function sln_normalize_credibility_settings( $saved ) {
	$defaults = sln_get_credibility_default_settings();
	$merged   = wp_parse_args( is_array( $saved ) ? $saved : array(), $defaults );

	$merged['logos'] = sln_sanitize_credibility_logos( $merged['logos'] ?? array() );

	return $merged;
}

/**
 * Get saved settings.
 *
 * @return array
 */
function sln_get_credibility_settings() {
	$saved = get_option( SLN_CREDIBILITY_OPTION, array() );

	return sln_normalize_credibility_settings( $saved );
}

/**
 * Prepare logos for frontend rendering in admin order.
 *
 * @return array<int, array{url:string,link:string,file:string,alt:string}>
 */
function sln_get_credibility_logos() {
	$settings = sln_get_credibility_settings();
	$logos    = array();

	foreach ( $settings['logos'] as $logo ) {
		$image_url = sln_get_credibility_logo_image_url( $logo['image_id'] ?? 0, $logo['image'] ?? '' );

		if ( '' === $image_url ) {
			continue;
		}

		$filename = wp_basename( wp_parse_url( $image_url, PHP_URL_PATH ) ?? '' );
		$alt      = '';

		if ( ! empty( $logo['image_id'] ) ) {
			$alt = get_post_meta( absint( $logo['image_id'] ), '_wp_attachment_image_alt', true );
		}

		if ( '' === $alt ) {
			$alt = ucwords( str_replace( array( '-', '_' ), ' ', pathinfo( $filename, PATHINFO_FILENAME ) ) );
		}

		$logos[] = array(
			'url'  => $image_url,
			'link' => $logo['url'] ?? '',
			'file' => $filename,
			'alt'  => $alt,
		);
	}

	return $logos;
}

/**
 * Split logos into two rows preserving order.
 *
 * @param array    $logos          Logos in order.
 * @param int|null $first_row_size Fixed first-row size. Null splits evenly.
 * @return array<int, array<int, array{url:string,link:string,file:string,alt:string}>>
 */
function sln_credibility_split_logos_into_rows( $logos, $first_row_size = null ) {
	$count = count( $logos );

	if ( $count <= 0 ) {
		return array();
	}

	if ( null !== $first_row_size ) {
		$row_one = array_slice( $logos, 0, $first_row_size );
		$row_two = array_slice( $logos, $first_row_size );
	} else {
		$split_at = (int) ceil( $count / 2 );
		$row_one  = array_slice( $logos, 0, $split_at );
		$row_two  = array_slice( $logos, $split_at );
	}

	$rows = array();

	if ( ! empty( $row_one ) ) {
		$rows[] = $row_one;
	}

	if ( ! empty( $row_two ) ) {
		$rows[] = $row_two;
	}

	return $rows;
}

/**
 * Build slider slides from admin logos.
 *
 * Slide 1: first 14 logos (7 + 7 rows).
 * Slide 2: remaining logos split across two rows in admin order.
 *
 * @param array $logos Prepared logos.
 * @return array<int, array<int, array<int, array{url:string,link:string,file:string,alt:string}>>>
 */
function sln_credibility_build_logo_slides( $logos ) {
	if ( empty( $logos ) ) {
		return array();
	}

	$slides = array();

	$slide_one_logos = array_slice( $logos, 0, SLN_CREDIBILITY_SLIDE_ONE_COUNT );

	if ( ! empty( $slide_one_logos ) ) {
		$slides[] = sln_credibility_split_logos_into_rows( $slide_one_logos, SLN_CREDIBILITY_ROW_COLUMNS );
	}

	$slide_two_logos = array_slice( $logos, SLN_CREDIBILITY_SLIDE_ONE_COUNT );

	if ( ! empty( $slide_two_logos ) ) {
		$slides[] = sln_credibility_split_logos_into_rows( $slide_two_logos, null );
	}

	return $slides;
}

/**
 * Split logos into two marquee rows.
 *
 * @param array $logos Prepared logos.
 * @return array{row_one:array,row_two:array}
 */
function sln_credibility_build_marquee_rows( $logos ) {
	$count = count( $logos );

	if ( $count <= 0 ) {
		return array(
			'row_one' => array(),
			'row_two' => array(),
		);
	}

	$split_at = (int) ceil( $count / 2 );

	return array(
		'row_one' => array_slice( $logos, 0, $split_at ),
		'row_two' => array_slice( $logos, $split_at ),
	);
}

/**
 * Render logos for a marquee track set.
 *
 * @param array  $logos   Logos in order.
 * @param string $loading Image loading attribute.
 */
function sln_credibility_render_marquee_logos( $logos, $loading = 'lazy' ) {
	if ( empty( $logos ) ) {
		return;
	}

	$loading = 'eager' === $loading ? 'eager' : 'lazy';

	foreach ( $logos as $logo ) :
		?>
		<div class="credibility-marquee__item">
			<div class="credibility-marquee__logo-box">
				<?php if ( ! empty( $logo['link'] ) ) : ?>
					<a class="credibility-marquee__link" href="<?php echo esc_url( $logo['link'] ); ?>" target="_blank" rel="noopener noreferrer">
						<img
							class="credibility-marquee__image"
							src="<?php echo esc_url( $logo['url'] ); ?>"
							data-logo-file="<?php echo esc_attr( $logo['file'] ); ?>"
							alt="<?php echo esc_attr( $logo['alt'] ); ?>"
							loading="<?php echo esc_attr( $loading ); ?>"
							decoding="async"
						/>
					</a>
				<?php else : ?>
					<img
						class="credibility-marquee__image"
						src="<?php echo esc_url( $logo['url'] ); ?>"
						data-logo-file="<?php echo esc_attr( $logo['file'] ); ?>"
						alt="<?php echo esc_attr( $logo['alt'] ); ?>"
						loading="<?php echo esc_attr( $loading ); ?>"
						decoding="async"
					/>
				<?php endif; ?>
			</div>
		</div>
		<?php
	endforeach;
}

/**
 * Render a credibility logo row.
 *
 * @param array<int, array{url:string,link:string,file:string,alt:string}> $logos Row logos.
 */
function sln_credibility_render_logo_row( $logos, $loading = 'lazy' ) {
	if ( empty( $logos ) ) {
		return;
	}

	$loading = 'eager' === $loading ? 'eager' : 'lazy';

	$row_count = count( $logos );
	$start_col = 1;

	if ( $row_count < SLN_CREDIBILITY_ROW_COLUMNS ) {
		$start_col = (int) floor( ( SLN_CREDIBILITY_ROW_COLUMNS - $row_count ) / 2 ) + 1;
	}
	?>
	<div class="credibility__logo-row credibility__logo-row--count-<?php echo esc_attr( (string) $row_count ); ?>">
		<?php foreach ( $logos as $index => $logo ) : ?>
			<div class="credibility__logo-item" style="<?php echo esc_attr( 'grid-column: ' . ( $start_col + $index ) ); ?>">
				<?php if ( ! empty( $logo['link'] ) ) : ?>
					<a class="credibility__logo-link" href="<?php echo esc_url( $logo['link'] ); ?>">
						<img
							class="credibility__logo-image"
							src="<?php echo esc_url( $logo['url'] ); ?>"
							data-logo-file="<?php echo esc_attr( $logo['file'] ); ?>"
							alt="<?php echo esc_attr( $logo['alt'] ); ?>"
							loading="<?php echo esc_attr( $loading ); ?>"
							decoding="async"
						/>
					</a>
				<?php else : ?>
					<img
						class="credibility__logo-image"
						src="<?php echo esc_url( $logo['url'] ); ?>"
						data-logo-file="<?php echo esc_attr( $logo['file'] ); ?>"
						alt="<?php echo esc_attr( $logo['alt'] ); ?>"
						loading="<?php echo esc_attr( $loading ); ?>"
						decoding="async"
					/>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
}

/**
 * Sanitize settings before saving.
 *
 * @param array $input Raw input.
 * @return array
 */
function sln_sanitize_credibility_settings( $input ) {
	if ( ! is_array( $input ) ) {
		return sln_get_credibility_default_settings();
	}

	return sln_normalize_credibility_settings(
		array(
			'logos' => sln_sanitize_credibility_logos( $input['logos'] ?? array() ),
		)
	);
}

/**
 * Register settings.
 */
function sln_register_credibility_settings() {
	register_setting(
		'sln_credibility_settings_group',
		SLN_CREDIBILITY_OPTION,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'sln_sanitize_credibility_settings',
			'default'           => sln_get_credibility_default_settings(),
		)
	);
}
add_action( 'admin_init', 'sln_register_credibility_settings' );
