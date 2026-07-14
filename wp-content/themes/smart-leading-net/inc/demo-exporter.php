<?php
/**
 * Demo exporter — snapshot live Smart Leading theme data as a downloadable package.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Demo exporter.
 */
final class SLN_Demo_Exporter {

	/**
	 * Attachment IDs discovered during export.
	 *
	 * @var array<int, int>
	 */
	private $attachment_ids = array();

	/**
	 * Register hooks.
	 */
	public static function init() {
		add_action( 'admin_post_sln_export_demo', array( __CLASS__, 'handle_export_request' ) );
	}

	/**
	 * Handle export download.
	 */
	public static function handle_export_request() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to export demo data.', 'smart-leading-net' ) );
		}

		check_admin_referer( 'sln_export_demo', 'sln_export_demo_nonce' );

		if ( function_exists( 'set_time_limit' ) ) {
			set_time_limit( 300 );
		}

		if ( function_exists( 'wp_raise_memory_limit' ) ) {
			wp_raise_memory_limit( 'admin' );
		}

		$exporter = new self();
		$package  = $exporter->build_package();
		$json     = sln_demo_package_encode( $package );

		if ( ! $json ) {
			wp_die( esc_html__( 'Failed to encode demo package.', 'smart-leading-net' ) );
		}

		$upload_dir = wp_upload_dir();
		$zip_path   = '';

		if ( class_exists( 'ZipArchive' ) && empty( $upload_dir['error'] ) ) {
			$zip_path = $exporter->build_zip_package( $package, $json, $upload_dir['basedir'] );
		}

		if ( $zip_path && file_exists( $zip_path ) ) {
			nocache_headers();
			header( 'Content-Type: application/zip' );
			header( 'Content-Disposition: attachment; filename="sln-demo-export-' . gmdate( 'Y-m-d' ) . '.zip"' );
			header( 'Content-Length: ' . (string) filesize( $zip_path ) );
			readfile( $zip_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_readfile
			unlink( $zip_path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.unlink_unlink
			exit;
		}

		nocache_headers();
		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename="sln-demo-export-' . gmdate( 'Y-m-d' ) . '.json"' );
		header( 'Content-Length: ' . (string) strlen( $json ) );
		echo $json; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		exit;
	}

	/**
	 * Build full export package array.
	 *
	 * @return array<string, mixed>
	 */
	public function build_package() {
		$pages        = $this->export_pages();
		$growth_pages = $this->export_growth_pages();
		$options      = $this->export_options();
		$menus        = $this->export_menus();
		$wordpress    = $this->export_wordpress_settings();
		$theme_mods   = $this->export_theme_mods();

		$annotated = sln_demo_package_annotate_media_paths(
			array(
				'pages'        => $pages,
				'growth_pages' => $growth_pages,
				'options'      => $options,
			)
		);

		sln_demo_package_collect_attachment_ids( $annotated, $this->attachment_ids );

		$media_registry = sln_demo_package_build_media_registry( $this->attachment_ids );

		return array(
			'format'          => SLN_DEMO_PACKAGE_FORMAT,
			'version'         => SLN_DEMO_PACKAGE_VERSION,
			'exported_at'     => gmdate( 'c' ),
			'site_url'        => home_url( '/' ),
			'theme_version'   => defined( 'SLN_THEME_VERSION' ) ? SLN_THEME_VERSION : '',
			'url_replace_from'=> array(
				untrailingslashit( home_url() ),
				trailingslashit( home_url() ),
			),
			'content'         => array(
				'pages'        => $annotated['pages'],
				'growth_pages' => $annotated['growth_pages'],
				'menus'        => $menus,
				'wordpress'    => $wordpress,
			),
			'options'         => $annotated['options'],
			'theme_mods'      => $theme_mods,
			'media_registry'  => $media_registry,
		);
	}

	/**
	 * Create a ZIP with JSON + referenced media files.
	 *
	 * @param array<string, mixed> $package   Package.
	 * @param string               $json      Encoded JSON.
	 * @param string               $basedir   Uploads basedir.
	 * @return string Absolute zip path or empty string.
	 */
	private function build_zip_package( array $package, $json, $basedir ) {
		$tmp = trailingslashit( $basedir ) . 'sln-demo-export-' . wp_generate_password( 8, false ) . '.zip';
		$zip = new ZipArchive();

		if ( true !== $zip->open( $tmp, ZipArchive::CREATE | ZipArchive::OVERWRITE ) ) {
			return '';
		}

		$zip->addFromString( 'sln-demo-export.json', $json );

		if ( ! empty( $package['media_registry'] ) && is_array( $package['media_registry'] ) ) {
			foreach ( $package['media_registry'] as $entry ) {
				if ( empty( $entry['file'] ) ) {
					continue;
				}

				$relative = ltrim( str_replace( '\\', '/', $entry['file'] ), '/' );
				$abs      = trailingslashit( $basedir ) . $relative;

				if ( file_exists( $abs ) ) {
					$zip->addFile( $abs, 'media/' . $relative );
				}
			}
		}

		$zip->close();

		return $tmp;
	}

	/**
	 * Export pages (templates + meta).
	 *
	 * @return array<int, array<string, mixed>>
	 */
	private function export_pages() {
		$query = new WP_Query(
			array(
				'post_type'      => 'page',
				'post_status'    => array( 'publish', 'draft', 'private' ),
				'posts_per_page' => -1,
				'orderby'        => 'menu_order title',
				'order'          => 'ASC',
			)
		);

		$pages = array();

		foreach ( $query->posts as $post ) {
			if ( ! $post instanceof WP_Post ) {
				continue;
			}

			$template = (string) get_page_template_slug( $post->ID );
			$meta     = array();

			if ( defined( 'SLN_SEO_SVC_TEMPLATE' ) && SLN_SEO_SVC_TEMPLATE === $template ) {
				foreach ( sln_demo_package_seo_meta_keys() as $meta_key ) {
					if ( metadata_exists( 'post', $post->ID, $meta_key ) ) {
						$meta[ $meta_key ] = get_post_meta( $post->ID, $meta_key, true );
					}
				}
			}

			if ( defined( 'SLN_PORTFOLIO_TEMPLATE' ) && SLN_PORTFOLIO_TEMPLATE === $template ) {
				foreach ( sln_demo_package_portfolio_meta_keys() as $meta_key ) {
					if ( metadata_exists( 'post', $post->ID, $meta_key ) ) {
						$meta[ $meta_key ] = get_post_meta( $post->ID, $meta_key, true );
					}
				}
			}

			$pages[] = array(
				'title'    => $post->post_title,
				'slug'     => $post->post_name,
				'status'   => $post->post_status,
				'template' => $template,
				'content'  => $post->post_content,
				'meta'     => $meta,
			);

			sln_demo_package_collect_attachment_ids( $meta, $this->attachment_ids );
		}

		wp_reset_postdata();

		return $pages;
	}

	/**
	 * Export Growth Pages with meta.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	private function export_growth_pages() {
		if ( ! post_type_exists( SLN_GROWTH_PAGE_POST_TYPE ) ) {
			return array();
		}

		$query = new WP_Query(
			array(
				'post_type'      => SLN_GROWTH_PAGE_POST_TYPE,
				'post_status'    => array( 'publish', 'draft', 'private' ),
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);

		$items = array();

		foreach ( $query->posts as $post ) {
			if ( ! $post instanceof WP_Post ) {
				continue;
			}

			$meta   = array();
			$banner = array();

			if ( function_exists( 'sln_growth_page_get_banner_field_map' ) ) {
				foreach ( sln_growth_page_get_banner_field_map() as $field => $meta_key ) {
					$banner[ $field ] = get_post_meta( $post->ID, $meta_key, true );
				}
			}

			$meta['banner'] = $banner;

			foreach ( sln_demo_package_growth_meta_keys() as $meta_key ) {
				if ( metadata_exists( 'post', $post->ID, $meta_key ) ) {
					$meta[ $meta_key ] = get_post_meta( $post->ID, $meta_key, true );
				}
			}

			$items[] = array(
				'title'  => $post->post_title,
				'slug'   => $post->post_name,
				'status' => $post->post_status,
				'meta'   => $meta,
			);

			sln_demo_package_collect_attachment_ids( $meta, $this->attachment_ids );
		}

		wp_reset_postdata();

		return $items;
	}

	/**
	 * Export theme options (GHL token redacted to placeholder when set).
	 *
	 * @return array<string, mixed>
	 */
	private function export_options() {
		$options = array();

		foreach ( sln_demo_package_option_keys() as $option_key ) {
			if ( SLN_GHL_OPTION === $option_key && function_exists( 'sln_ghl_get_settings' ) ) {
				$value = sln_ghl_get_settings();
			} else {
				$value = get_option( $option_key, null );
			}

			if ( null === $value ) {
				continue;
			}

			if ( SLN_GHL_OPTION === $option_key && is_array( $value ) ) {
				$token = (string) ( $value['private_token'] ?? '' );
				$value['private_token'] = '' !== $token ? '__PRESERVE_OR_SET_MANUALLY__' : '';
			}

			$options[ $option_key ] = $value;
			sln_demo_package_collect_attachment_ids( $value, $this->attachment_ids );
		}

		return $options;
	}

	/**
	 * Export nav menus assigned to theme locations.
	 *
	 * @return array<string, array<string, mixed>>
	 */
	private function export_menus() {
		$locations = get_nav_menu_locations();
		$menus     = array();

		$registered = get_registered_nav_menus();

		foreach ( array_keys( $registered ) as $location ) {
			$term_id = isset( $locations[ $location ] ) ? absint( $locations[ $location ] ) : 0;

			if ( ! $term_id ) {
				continue;
			}

			$term = get_term( $term_id, 'nav_menu' );

			if ( ! $term || is_wp_error( $term ) ) {
				continue;
			}

			$items = wp_get_nav_menu_items( $term_id );
			$tree  = array();

			if ( is_array( $items ) ) {
				$by_parent = array();

				foreach ( $items as $item ) {
					$parent = absint( $item->menu_item_parent );
					$by_parent[ $parent ][] = $item;
				}

				$tree = $this->flatten_menu_items( $by_parent, 0 );
			}

			$menus[ $location ] = array(
				'name'  => $term->name,
				'items' => $tree,
			);
		}

		return $menus;
	}

	/**
	 * Flatten menu items preserving order and children nested as child arrays.
	 *
	 * @param array<int, array<int, WP_Post>> $by_parent Parent map.
	 * @param int                             $parent_id Parent item ID.
	 * @return array<int, array<string, mixed>>
	 */
	private function flatten_menu_items( array $by_parent, $parent_id ) {
		if ( empty( $by_parent[ $parent_id ] ) ) {
			return array();
		}

		$rows = array();

		foreach ( $by_parent[ $parent_id ] as $item ) {
			$row = $this->serialize_menu_item( $item );

			$children = $this->flatten_menu_items( $by_parent, (int) $item->ID );

			if ( ! empty( $children ) ) {
				$row['children'] = $children;
			}

			$rows[] = $row;
		}

		return $rows;
	}

	/**
	 * Serialize one menu item.
	 *
	 * @param WP_Post $item Menu item.
	 * @return array<string, mixed>
	 */
	private function serialize_menu_item( $item ) {
		$type      = $item->type;
		$object    = $item->object;
		$object_id = absint( $item->object_id );
		$row       = array(
			'title' => $item->title,
			'type'  => 'custom',
		);

		if ( 'post_type' === $type && 'page' === $object ) {
			$slug = get_post_field( 'post_name', $object_id );
			$row['type'] = 'page';
			$row['slug'] = $slug ? $slug : sanitize_title( $item->title );
		} elseif ( 'post_type' === $type && SLN_GROWTH_PAGE_POST_TYPE === $object ) {
			$slug = get_post_field( 'post_name', $object_id );
			$row['type'] = 'growth_page';
			$row['slug'] = $slug ? $slug : sanitize_title( $item->title );
		} else {
			$row['type'] = 'custom';
			$row['url']  = $item->url;
		}

		return $row;
	}

	/**
	 * Export reading / identity settings.
	 *
	 * @return array<string, mixed>
	 */
	private function export_wordpress_settings() {
		$front_id = absint( get_option( 'page_on_front' ) );
		$blog_id  = absint( get_option( 'page_for_posts' ) );

		return array(
			'show_on_front'       => (string) get_option( 'show_on_front', 'posts' ),
			'page_on_front_slug'  => $front_id ? (string) get_post_field( 'post_name', $front_id ) : '',
			'page_for_posts_slug' => $blog_id ? (string) get_post_field( 'post_name', $blog_id ) : '',
			'blogname'            => (string) get_option( 'blogname', '' ),
		);
	}

	/**
	 * Export relevant theme mods (locations as location => menu name).
	 *
	 * @return array<string, mixed>
	 */
	private function export_theme_mods() {
		$locations = get_nav_menu_locations();
		$mapped    = array();

		foreach ( $locations as $location => $term_id ) {
			$term = get_term( absint( $term_id ), 'nav_menu' );

			if ( $term && ! is_wp_error( $term ) ) {
				$mapped[ $location ] = $term->name;
			}
		}

		return array(
			'nav_menu_locations' => $mapped,
		);
	}
}

SLN_Demo_Exporter::init();
