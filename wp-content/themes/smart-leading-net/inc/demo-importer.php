<?php
/**
 * One-click demo import for Smart Leading Net theme.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_DEMO_DIR', SLN_THEME_DIR . '/demo' );
define( 'SLN_DEMO_MEDIA_DIR', SLN_DEMO_DIR . '/media' );
define( 'SLN_DEMO_IMPORT_OPTION', 'sln_demo_import_completed' );
define( 'SLN_DEMO_IMPORT_VERSION', '1.0.0' );

/**
 * Demo importer orchestrator.
 */
final class SLN_Demo_Importer {

	/**
	 * Relative upload path => attachment ID.
	 *
	 * @var array<string, int>
	 */
	private $media_map = array();

	/**
	 * Basename (lowercase, no ext) => attachment ID.
	 *
	 * @var array<string, int>
	 */
	private $basename_map = array();

	/**
	 * Page slug => post ID.
	 *
	 * @var array<string, int>
	 */
	private $page_map = array();

	/**
	 * Growth page slug => post ID.
	 *
	 * @var array<string, int>
	 */
	private $growth_page_map = array();

	/**
	 * Parsed demo-content.json.
	 *
	 * @var array<string, mixed>
	 */
	private $content = array();

	/**
	 * Parsed theme-options.json.
	 *
	 * @var array<string, mixed>
	 */
	private $theme_options = array();

	/**
	 * Register admin hooks.
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'register_admin_page' ) );
		add_action( 'admin_post_sln_import_demo', array( __CLASS__, 'handle_import_request' ) );
		add_action( 'admin_notices', array( __CLASS__, 'render_admin_notices' ) );
	}

	/**
	 * Add Appearance submenu page.
	 */
	public static function register_admin_page() {
		add_theme_page(
			__( 'Smart Leading Theme Setup', 'smart-leading-net' ),
			__( 'Smart Leading Setup', 'smart-leading-net' ),
			'manage_options',
			'sln-theme-setup',
			array( __CLASS__, 'render_setup_page' )
		);
	}

	/**
	 * Render setup admin page.
	 */
	public static function render_setup_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'smart-leading-net' ) );
		}

		$imported = (bool) get_option( SLN_DEMO_IMPORT_OPTION, false );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Smart Leading Theme Setup', 'smart-leading-net' ); ?></h1>

			<p><?php esc_html_e( 'Import the complete Smart Leading demo website in one click. This creates pages, menus, media, theme settings, and a sample Growth Page.', 'smart-leading-net' ); ?></p>

			<?php if ( $imported ) : ?>
				<div class="notice notice-info inline">
					<p><?php esc_html_e( 'Demo content has been imported previously. Running import again will update existing pages and settings instead of duplicating them.', 'smart-leading-net' ); ?></p>
				</div>
			<?php endif; ?>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php wp_nonce_field( 'sln_import_demo', 'sln_import_demo_nonce' ); ?>
				<input type="hidden" name="action" value="sln_import_demo">
				<?php submit_button( __( 'Import Demo Website', 'smart-leading-net' ), 'primary', 'submit', false ); ?>
			</form>

			<h2><?php esc_html_e( 'What gets imported', 'smart-leading-net' ); ?></h2>
			<ul style="list-style:disc;padding-left:1.5rem;">
				<li><?php esc_html_e( 'Pages: Home, About Us, Contact Us, Privacy Policy, Terms of Service', 'smart-leading-net' ); ?></li>
				<li><?php esc_html_e( 'Growth Page CPT: Revenue Growth (all section meta)', 'smart-leading-net' ); ?></li>
				<li><?php esc_html_e( 'Primary and footer navigation menus', 'smart-leading-net' ); ?></li>
				<li><?php esc_html_e( 'Theme options: Our Services, Our Projects, Credibility logos', 'smart-leading-net' ); ?></li>
				<li><?php esc_html_e( 'Demo media (images, SVG, WebP) from the theme package', 'smart-leading-net' ); ?></li>
				<li><?php esc_html_e( 'Static front page assignment (Home)', 'smart-leading-net' ); ?></li>
			</ul>
		</div>
		<?php
	}

	/**
	 * Handle import form submission.
	 */
	public static function handle_import_request() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to import demo content.', 'smart-leading-net' ) );
		}

		check_admin_referer( 'sln_import_demo', 'sln_import_demo_nonce' );

		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/nav-menu.php';

		$result = false;

		try {
			$importer = new self();
			$result   = $importer->run();
		} catch ( Throwable $e ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG && defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
				error_log( // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
					'[SLN Demo Import] ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine()
				);
			}
		}

		$redirect = add_query_arg(
			array(
				'page'       => 'sln-theme-setup',
				'sln_import' => $result ? 'success' : 'error',
			),
			admin_url( 'themes.php' )
		);

		wp_safe_redirect( $redirect );
		exit;
	}

	/**
	 * Show import result notices on the setup page.
	 */
	public static function render_admin_notices() {
		if ( ! isset( $_GET['page'] ) || 'sln-theme-setup' !== $_GET['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}

		if ( ! isset( $_GET['sln_import'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}

		$status = sanitize_key( wp_unslash( $_GET['sln_import'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( 'success' === $status ) {
			printf(
				'<div class="notice notice-success is-dismissible"><p>%s</p></div>',
				esc_html__( 'Demo imported successfully.', 'smart-leading-net' )
			);
			return;
		}

		if ( 'error' === $status ) {
			$detail = '';

			if ( defined( 'WP_DEBUG' ) && WP_DEBUG && defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
				$detail = ' ' . esc_html__( 'Check wp-content/debug.log for [SLN Demo Import] entries.', 'smart-leading-net' );
			}

			printf(
				'<div class="notice notice-error is-dismissible"><p>%s%s</p></div>',
				esc_html__( 'Demo import failed. Please check that demo files exist in the theme /demo folder and that PHP max_execution_time allows long imports (recommended: 300 seconds).', 'smart-leading-net' ),
				$detail
			);
		}
	}

	/**
	 * Run the full import pipeline.
	 *
	 * @return bool
	 */
	public function run() {
		if ( function_exists( 'set_time_limit' ) ) {
			set_time_limit( 300 );
		}

		if ( function_exists( 'wp_raise_memory_limit' ) ) {
			wp_raise_memory_limit( 'admin' );
		}

		if ( ! $this->load_demo_files() ) {
			return false;
		}

		$this->import_media();
		$this->import_pages();
		$this->import_growth_pages();
		$this->import_menus();
		$this->import_theme_options();
		$this->import_wordpress_settings();

		update_option( SLN_DEMO_IMPORT_OPTION, SLN_DEMO_IMPORT_VERSION );
		update_option( 'sln_growth_page_flush_rewrite', SLN_GP_CPT_VERSION );
		flush_rewrite_rules( false );

		return true;
	}

	/**
	 * Load JSON demo files.
	 *
	 * @return bool
	 */
	private function load_demo_files() {
		$content_path = SLN_DEMO_DIR . '/demo-content.json';
		$options_path = SLN_DEMO_DIR . '/theme-options.json';

		if ( ! file_exists( $content_path ) ) {
			return false;
		}

		$this->content = json_decode( (string) file_get_contents( $content_path ), true );

		if ( ! is_array( $this->content ) ) {
			return false;
		}

		if ( file_exists( $options_path ) ) {
			$this->theme_options = json_decode( (string) file_get_contents( $options_path ), true );

			if ( ! is_array( $this->theme_options ) ) {
				$this->theme_options = array();
			}
		}

		return true;
	}

	/**
	 * Import media from demo/media into uploads and register attachments.
	 */
	private function import_media() {
		$uploads_root = SLN_DEMO_MEDIA_DIR . '/uploads';

		if ( is_dir( $uploads_root ) ) {
			$iterator = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator( $uploads_root, FilesystemIterator::SKIP_DOTS )
			);

			foreach ( $iterator as $file ) {
				if ( ! $file->isFile() ) {
					continue;
				}

				$filename = $file->getFilename();

				if ( 'index.php' === $filename || 0 === strpos( $filename, '.' ) ) {
					continue;
				}

				$relative = ltrim( str_replace( '\\', '/', substr( $file->getPathname(), strlen( $uploads_root ) ) ), '/' );
				$this->register_demo_file( $file->getPathname(), $relative );
			}
		}

		$theme_media = SLN_DEMO_MEDIA_DIR . '/theme';

		if ( is_dir( $theme_media ) ) {
			foreach ( glob( $theme_media . '/*' ) as $filepath ) {
				if ( ! is_file( $filepath ) ) {
					continue;
				}

				$basename = basename( $filepath );

				if ( 'index.php' === $basename ) {
					continue;
				}

				$this->register_demo_file( $filepath, '2026/05/' . $basename );
			}
		}
	}

	/**
	 * Copy a demo file into uploads and ensure a media attachment exists.
	 *
	 * @param string $source_path   Absolute source path.
	 * @param string $relative_dest Path relative to wp-content/uploads/.
	 */
	private function register_demo_file( $source_path, $relative_dest ) {
		$relative_dest = ltrim( str_replace( '\\', '/', $relative_dest ), '/' );
		$upload_dir    = wp_upload_dir();

		if ( ! empty( $upload_dir['error'] ) ) {
			return;
		}

		$dest_path = trailingslashit( $upload_dir['basedir'] ) . $relative_dest;
		$dest_dir  = dirname( $dest_path );

		if ( ! wp_mkdir_p( $dest_dir ) ) {
			return;
		}

		if ( ! file_exists( $dest_path ) ) {
			copy( $source_path, $dest_path );
		}

		$attachment_id = $this->find_attachment_by_path( $relative_dest );

		if ( ! $attachment_id ) {
			$attachment_id = $this->create_attachment( $dest_path, $relative_dest );
		}

		if ( $attachment_id ) {
			$this->media_map[ $relative_dest ] = $attachment_id;
			$this->index_basename( $relative_dest, $attachment_id );
		}
	}

	/**
	 * Find attachment by _wp_attached_file meta.
	 *
	 * @param string $relative_dest Upload-relative path.
	 * @return int
	 */
	private function find_attachment_by_path( $relative_dest ) {
		$posts = get_posts(
			array(
				'post_type'      => 'attachment',
				'posts_per_page' => 1,
				'post_status'    => 'inherit',
				'meta_key'       => '_wp_attached_file',
				'meta_value'     => $relative_dest,
				'fields'         => 'ids',
			)
		);

		return ! empty( $posts[0] ) ? absint( $posts[0] ) : 0;
	}

	/**
	 * Create a media attachment for an uploaded file.
	 *
	 * @param string $dest_path     Absolute file path.
	 * @param string $relative_dest Upload-relative path.
	 * @return int Attachment ID.
	 */
	private function create_attachment( $dest_path, $relative_dest ) {
		$filetype = wp_check_filetype( basename( $dest_path ), null );
		$mime     = $filetype['type'] ? $filetype['type'] : 'application/octet-stream';

		$attachment = array(
			'post_mime_type' => $mime,
			'post_title'     => sanitize_file_name( pathinfo( $dest_path, PATHINFO_FILENAME ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		$attachment_id = wp_insert_attachment( $attachment, $dest_path );

		if ( is_wp_error( $attachment_id ) || ! $attachment_id ) {
			return 0;
		}

		// SVG and other non-raster files do not need image metadata generation.
		if ( 0 === strpos( $mime, 'image/' ) && 'image/svg+xml' !== $mime ) {
			$metadata = wp_generate_attachment_metadata( $attachment_id, $dest_path );
			if ( is_array( $metadata ) ) {
				wp_update_attachment_metadata( $attachment_id, $metadata );
			}
		}

		return absint( $attachment_id );
	}

	/**
	 * Index attachment by basename for legacy option lookups.
	 *
	 * @param string $relative_dest Upload-relative path.
	 * @param int    $attachment_id Attachment ID.
	 */
	private function index_basename( $relative_dest, $attachment_id ) {
		$basename = strtolower( pathinfo( $relative_dest, PATHINFO_FILENAME ) );
		$this->basename_map[ $basename ] = absint( $attachment_id );

		$normalized = strtolower( sln_credibility_normalize_logo_basename( pathinfo( $relative_dest, PATHINFO_FILENAME ) ) );
		$this->basename_map[ $normalized ] = absint( $attachment_id );
	}

	/**
	 * Import or update demo pages.
	 */
	private function import_pages() {
		if ( empty( $this->content['pages'] ) || ! is_array( $this->content['pages'] ) ) {
			return;
		}

		foreach ( $this->content['pages'] as $page_data ) {
			$page_id = $this->upsert_page( $page_data );

			if ( $page_id && ! empty( $page_data['slug'] ) ) {
				$this->page_map[ $page_data['slug'] ] = $page_id;
			}
		}
	}

	/**
	 * Create or update a page by slug/title.
	 *
	 * @param array<string, mixed> $page_data Page definition.
	 * @return int
	 */
	private function upsert_page( $page_data ) {
		$title = sanitize_text_field( $page_data['title'] ?? '' );
		$slug  = sanitize_title( $page_data['slug'] ?? $title );

		if ( '' === $title ) {
			return 0;
		}

		$existing = get_page_by_path( $slug, OBJECT, 'page' );

		if ( ! $existing ) {
			$existing = get_page_by_title( $title, OBJECT, 'page' );
		}

		$postarr = array(
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_status'  => sanitize_key( $page_data['status'] ?? 'publish' ),
			'post_type'    => 'page',
			'post_content' => $this->replace_urls( (string) ( $page_data['content'] ?? '' ) ),
		);

		if ( $existing instanceof WP_Post ) {
			$postarr['ID'] = $existing->ID;
			$page_id       = wp_update_post( $postarr, true );
		} else {
			$page_id = wp_insert_post( $postarr, true );
		}

		if ( is_wp_error( $page_id ) || ! $page_id ) {
			return 0;
		}

		$page_id = absint( $page_id );

		if ( ! empty( $page_data['template'] ) ) {
			update_post_meta( $page_id, '_wp_page_template', sanitize_text_field( $page_data['template'] ) );
		} else {
			delete_post_meta( $page_id, '_wp_page_template' );
		}

		return $page_id;
	}

	/**
	 * Import Growth Page CPT demo content.
	 */
	private function import_growth_pages() {
		if ( empty( $this->content['growth_pages'] ) || ! is_array( $this->content['growth_pages'] ) ) {
			return;
		}

		foreach ( $this->content['growth_pages'] as $gp_data ) {
			$post_id = $this->upsert_growth_page( $gp_data );

			if ( $post_id && ! empty( $gp_data['slug'] ) ) {
				$this->growth_page_map[ $gp_data['slug'] ] = $post_id;
			}
		}
	}

	/**
	 * Create or update a Growth Page.
	 *
	 * @param array<string, mixed> $gp_data Growth page definition.
	 * @return int
	 */
	private function upsert_growth_page( $gp_data ) {
		$title = sanitize_text_field( $gp_data['title'] ?? '' );
		$slug  = sanitize_title( $gp_data['slug'] ?? $title );

		if ( '' === $title ) {
			return 0;
		}

		$existing = get_posts(
			array(
				'name'           => $slug,
				'post_type'      => SLN_GROWTH_PAGE_POST_TYPE,
				'post_status'    => array( 'publish', 'draft', 'private' ),
				'posts_per_page' => 1,
			)
		);

		$postarr = array(
			'post_title'  => $title,
			'post_name'   => $slug,
			'post_status' => sanitize_key( $gp_data['status'] ?? 'publish' ),
			'post_type'   => SLN_GROWTH_PAGE_POST_TYPE,
		);

		if ( ! empty( $existing[0] ) ) {
			$postarr['ID'] = $existing[0]->ID;
			$post_id       = wp_update_post( $postarr, true );
		} else {
			$post_id = wp_insert_post( $postarr, true );
		}

		if ( is_wp_error( $post_id ) || ! $post_id ) {
			return 0;
		}

		$post_id = absint( $post_id );
		$this->apply_growth_page_meta( $post_id );

		return $post_id;
	}

	/**
	 * Apply all Growth Page meta from theme defaults.
	 *
	 * @param int $post_id Post ID.
	 */
	private function apply_growth_page_meta( $post_id ) {
		$banner = $this->get_demo_growth_page_banner();
		$fields = sln_growth_page_get_banner_field_map();

		foreach ( $fields as $key => $meta_key ) {
			if ( ! isset( $banner[ $key ] ) ) {
				continue;
			}

			$value = $banner[ $key ];

			if ( 'banner_image_id' === $key ) {
				$value = absint( $value );
			} else {
				$value = $this->replace_urls( (string) $value );
			}

			update_post_meta( $post_id, $meta_key, $value );
		}

		update_post_meta( $post_id, SLN_GP_GROWTH_METRICS_META, sln_get_growth_page_default_growth_metrics() );
		update_post_meta( $post_id, SLN_GP_SECTION_ORDERS_META, sln_get_growth_page_default_section_orders() );
		update_post_meta( $post_id, SLN_GP_SERVICES_SECTION_META, sln_get_growth_page_default_services_section() );
		update_post_meta( $post_id, SLN_GP_SERVICES_CARDS_META, sln_get_growth_page_default_service_cards() );
		update_post_meta( $post_id, SLN_GP_CLIENT_STORY_SECTION_META, sln_get_growth_page_default_client_story_section() );
		update_post_meta( $post_id, SLN_GP_CLIENT_STORY_STEPS_META, sln_get_growth_page_default_client_story_steps() );
		update_post_meta( $post_id, SLN_GP_CLIENT_STORY_RESULTS_META, sln_get_growth_page_default_client_story_results() );
		update_post_meta( $post_id, SLN_GP_HOW_WORK_SECTION_META, sln_get_growth_page_default_how_work_section() );
		update_post_meta( $post_id, SLN_GP_HOW_WORK_TABS_META, sln_get_growth_page_default_how_work_tabs() );
		update_post_meta( $post_id, SLN_GP_GROWTH_SERVICES_SECTION_META, sln_get_growth_page_default_growth_services_section() );
		update_post_meta( $post_id, SLN_GP_GROWTH_SERVICES_CARDS_META, sln_get_growth_page_default_growth_services_cards() );
		update_post_meta( $post_id, SLN_GP_CASE_STUDIES_SECTION_META, sln_get_growth_page_default_case_studies_section() );
		update_post_meta( $post_id, SLN_GP_CASE_STUDIES_CARDS_META, $this->remap_growth_case_study_cards( sln_get_growth_page_default_case_studies_cards() ) );
		update_post_meta( $post_id, SLN_GP_WHY_CHOOSE_SECTION_META, sln_get_growth_page_default_why_choose_section() );
		update_post_meta( $post_id, SLN_GP_WHY_CHOOSE_ROWS_META, sln_get_growth_page_default_why_choose_rows() );
		update_post_meta( $post_id, SLN_GP_PRICE_PLAN_SECTION_META, sln_get_growth_page_default_price_plan_section() );
		update_post_meta( $post_id, SLN_GP_PRICE_PLAN_CARDS_META, sln_get_growth_page_default_price_plan_cards() );
		update_post_meta( $post_id, SLN_GP_TESTIMONIALS_SECTION_META, sln_get_growth_page_default_testimonials_section() );
		update_post_meta( $post_id, SLN_GP_TESTIMONIALS_SUMMARY_META, sln_get_growth_page_default_testimonials_summary() );
		update_post_meta( $post_id, SLN_GP_TESTIMONIALS_STATS_META, sln_get_growth_page_default_testimonials_stats() );
		update_post_meta( $post_id, SLN_GP_TESTIMONIALS_REVIEWS_META, sln_get_growth_page_default_testimonials_reviews() );

		$cta = sln_get_growth_page_default_cta_banner_section();
		$cta['background_image_id'] = $this->get_assigned_attachment_id( 'growth_page_cta_background' );
		update_post_meta( $post_id, SLN_GP_CTA_BANNER_SECTION_META, $cta );
	}

	/**
	 * Demo banner content for Revenue Growth page.
	 *
	 * @return array<string, mixed>
	 */
	private function get_demo_growth_page_banner() {
		$contact_url = $this->page_map['contact-us'] ?? 0;
		$contact_link = $contact_url ? get_permalink( $contact_url ) : home_url( '/contact-us/' );

		return array(
			'small_heading'      => __( 'Core Service', 'smart-leading-net' ),
			'core_service_text'  => __( 'Revenue Growth', 'smart-leading-net' ),
			'main_heading'       => __( 'Scale Predictable Revenue With Data-Driven Marketing', 'smart-leading-net' ),
			'highlight_word'     => __( 'Revenue Growth', 'smart-leading-net' ),
			'description'        => __( 'We build high-performance marketing systems that turn traffic into qualified leads and revenue. From paid media to conversion optimization, Smart Leading helps you grow with clarity and confidence.', 'smart-leading-net' ),
			'primary_btn_text'   => __( 'Get My Free Proposal', 'smart-leading-net' ),
			'primary_btn_url'    => $contact_link,
			'secondary_btn_text' => __( 'View Case Studies', 'smart-leading-net' ),
			'secondary_btn_url'  => '#case-studies',
			'banner_image_id'    => $this->get_assigned_attachment_id( 'growth_page_banner' ),
		);
	}

	/**
	 * Remap image IDs in case study cards.
	 *
	 * @param array<int, array<string, mixed>> $cards Cards.
	 * @return array<int, array<string, mixed>>
	 */
	private function remap_growth_case_study_cards( $cards ) {
		foreach ( $cards as $index => $card ) {
			if ( ! empty( $card['icon_id'] ) ) {
				continue;
			}

			if ( ! empty( $card['icon_fallback'] ) ) {
				$basename = pathinfo( $card['icon_fallback'], PATHINFO_FILENAME );
				$cards[ $index ]['icon_id'] = $this->find_attachment_by_basename( $basename );
			}
		}

		return $cards;
	}

	/**
	 * Import navigation menus and assign locations.
	 */
	private function import_menus() {
		if ( empty( $this->content['menus'] ) || ! is_array( $this->content['menus'] ) ) {
			return;
		}

		$locations      = get_theme_mod( 'nav_menu_locations', array() );
		$new_locations  = is_array( $locations ) ? $locations : array();

		foreach ( $this->content['menus'] as $location => $menu_config ) {
			$menu_id = $this->create_or_refresh_menu( $menu_config );

			if ( $menu_id ) {
				$new_locations[ $location ] = $menu_id;
			}
		}

		set_theme_mod( 'nav_menu_locations', $new_locations );
	}

	/**
	 * Create a menu and replace its items.
	 *
	 * @param array<string, mixed> $menu_config Menu config.
	 * @return int Menu term ID.
	 */
	private function create_or_refresh_menu( $menu_config ) {
		$name = sanitize_text_field( $menu_config['name'] ?? '' );

		if ( '' === $name ) {
			return 0;
		}

		$menu = wp_get_nav_menu_object( $name );

		if ( $menu ) {
			$menu_id = (int) $menu->term_id;
			$items   = wp_get_nav_menu_items( $menu_id );

			if ( is_array( $items ) ) {
				foreach ( $items as $item ) {
					wp_delete_post( (int) $item->ID, true );
				}
			}
		} else {
			$menu_id = wp_create_nav_menu( $name );

			if ( is_wp_error( $menu_id ) ) {
				return 0;
			}
		}

		if ( empty( $menu_config['items'] ) || ! is_array( $menu_config['items'] ) ) {
			return (int) $menu_id;
		}

		$position = 1;

		foreach ( $menu_config['items'] as $item ) {
			$this->add_menu_item( (int) $menu_id, $item, $position );
			++$position;
		}

		return (int) $menu_id;
	}

	/**
	 * Add one nav menu item.
	 *
	 * @param int                  $menu_id  Menu term ID.
	 * @param array<string, mixed> $item     Item config.
	 * @param int                  $position Menu order.
	 */
	private function add_menu_item( $menu_id, $item, $position ) {
		$type  = sanitize_key( $item['type'] ?? 'custom' );
		$title = sanitize_text_field( $item['title'] ?? '' );

		$args = array(
			'menu-item-title'     => $title,
			'menu-item-status'    => 'publish',
			'menu-item-position'  => $position,
			'menu-item-type'      => 'custom',
			'menu-item-url'       => '#',
		);

		if ( 'page' === $type && ! empty( $item['slug'] ) ) {
			$page_id = $this->page_map[ $item['slug'] ] ?? 0;

			if ( ! $page_id ) {
				$page = get_page_by_path( sanitize_title( $item['slug'] ) );
				$page_id = $page instanceof WP_Post ? $page->ID : 0;
			}

			if ( $page_id ) {
				$args['menu-item-type']      = 'post_type';
				$args['menu-item-object']    = 'page';
				$args['menu-item-object-id'] = $page_id;
				unset( $args['menu-item-url'] );
			}
		} elseif ( 'growth_page' === $type && ! empty( $item['slug'] ) ) {
			$gp_id = $this->growth_page_map[ $item['slug'] ] ?? 0;

			if ( $gp_id ) {
				$args['menu-item-type']      = 'post_type';
				$args['menu-item-object']    = SLN_GROWTH_PAGE_POST_TYPE;
				$args['menu-item-object-id'] = $gp_id;
				unset( $args['menu-item-url'] );
			}
		} elseif ( 'custom' === $type ) {
			$url = $this->replace_urls( (string) ( $item['url'] ?? '#' ) );

			if ( 0 === strpos( $url, '/' ) && 0 !== strpos( $url, '//' ) ) {
				$url = home_url( $url );
			}

			$args['menu-item-url'] = esc_url_raw( $url );
		}

		wp_update_nav_menu_item( $menu_id, 0, $args );
	}

	/**
	 * Import theme options and mods.
	 */
	private function import_theme_options() {
		$options_config = $this->theme_options['options'] ?? array();

		if ( isset( $options_config['sln_our_services_settings'] ) ) {
			$settings = sln_get_our_services_default_settings();
			$this->assign_our_services_media( $settings );
			update_option( SLN_OUR_SERVICES_OPTION, sln_normalize_our_services_settings( $settings ) );
		}

		if ( isset( $options_config['sln_our_projects_settings'] ) ) {
			$settings = sln_get_our_projects_default_settings();
			$this->assign_our_projects_media( $settings );
			update_option( SLN_OUR_PROJECTS_OPTION, sln_normalize_our_projects_settings( $settings ) );
		}

		if ( isset( $options_config['sln_credibility_settings'] ) ) {
			$settings = sln_get_credibility_default_settings();
			$this->assign_credibility_media( $settings );
			update_option( SLN_CREDIBILITY_OPTION, sln_normalize_credibility_settings( $settings ) );
		}

		if ( ! empty( $this->theme_options['theme_mods'] ) && is_array( $this->theme_options['theme_mods'] ) ) {
			foreach ( $this->theme_options['theme_mods'] as $mod_key => $mod_value ) {
				if ( is_string( $mod_value ) && isset( $this->media_map[ ltrim( $mod_value, '/' ) ] ) ) {
					$mod_value = $this->media_map[ ltrim( $mod_value, '/' ) ];
				}

				set_theme_mod( sanitize_key( $mod_key ), $mod_value );
			}
		}
	}

	/**
	 * Assign attachment IDs to Our Services settings.
	 *
	 * @param array<string, mixed> $settings Settings (by reference).
	 */
	private function assign_our_services_media( &$settings ) {
		if ( empty( $settings['tabs'] ) || ! is_array( $settings['tabs'] ) ) {
			return;
		}

		$icon_map = $this->theme_options['tab_icon_basenames'] ?? array();

		foreach ( $settings['tabs'] as $index => $tab ) {
			$slug = $tab['slug'] ?? '';

			if ( isset( $icon_map[ $slug ] ) ) {
				$settings['tabs'][ $index ]['tab_icon_id'] = $this->find_attachment_by_basename( $icon_map[ $slug ] );
			}

			if ( ! empty( $tab['services'] ) && is_array( $tab['services'] ) ) {
				foreach ( $tab['services'] as $s_index => $service ) {
					if ( empty( $service['icon_id'] ) && ! empty( $service['title'] ) ) {
						$settings['tabs'][ $index ]['services'][ $s_index ]['icon_id'] = $this->find_attachment_by_basename( sanitize_title( $service['title'] ) );
					}
				}
			}

			if ( ! empty( $tab['results'] ) && is_array( $tab['results'] ) ) {
				foreach ( $tab['results'] as $r_index => $result ) {
					if ( 'logo' === ( $result['type'] ?? '' ) && empty( $result['logo_id'] ) && ! empty( $result['logo_alt'] ) ) {
						$settings['tabs'][ $index ]['results'][ $r_index ]['logo_id'] = $this->find_attachment_by_basename( $result['logo_alt'] );
					}
				}
			}
		}
	}

	/**
	 * Assign attachment IDs to Our Projects settings.
	 *
	 * @param array<string, mixed> $settings Settings (by reference).
	 */
	private function assign_our_projects_media( &$settings ) {
		if ( empty( $settings['projects'] ) || ! is_array( $settings['projects'] ) ) {
			return;
		}

		foreach ( $settings['projects'] as $index => $project ) {
			if ( empty( $project['image_id'] ) && ! empty( $project['image'] ) ) {
				$settings['projects'][ $index ]['image_id'] = $this->find_attachment_by_basename( $project['image'] );
			}
		}
	}

	/**
	 * Assign attachment IDs to Credibility logo settings.
	 *
	 * @param array<string, mixed> $settings Settings (by reference).
	 */
	private function assign_credibility_media( &$settings ) {
		if ( empty( $settings['logos'] ) || ! is_array( $settings['logos'] ) ) {
			return;
		}

		foreach ( $settings['logos'] as $index => $logo ) {
			if ( empty( $logo['image_id'] ) && ! empty( $logo['image'] ) ) {
				$settings['logos'][ $index ]['image_id'] = $this->find_attachment_by_basename( $logo['image'] );
			}
		}
	}

	/**
	 * Apply WordPress reading settings and site title.
	 */
	private function import_wordpress_settings() {
		$wp_config = $this->content['wordpress'] ?? $this->theme_options['wordpress'] ?? array();

		if ( ! empty( $wp_config['blogname'] ) ) {
			update_option( 'blogname', sanitize_text_field( $wp_config['blogname'] ) );
		}

		if ( ! empty( $wp_config['page_on_front_slug'] ) ) {
			$slug    = sanitize_title( $wp_config['page_on_front_slug'] );
			$page_id = $this->page_map[ $slug ] ?? 0;

			if ( ! $page_id ) {
				$page = get_page_by_path( $slug );
				$page_id = $page instanceof WP_Post ? $page->ID : 0;
			}

			if ( $page_id ) {
				update_option( 'show_on_front', 'page' );
				update_option( 'page_on_front', $page_id );
			}
		} elseif ( ! empty( $wp_config['show_on_front'] ) ) {
			update_option( 'show_on_front', sanitize_key( $wp_config['show_on_front'] ) );
		}
	}

	/**
	 * Get attachment ID from theme-options media_assignments key.
	 *
	 * @param string $key Assignment key.
	 * @return int
	 */
	private function get_assigned_attachment_id( $key ) {
		$assignments = $this->theme_options['media_assignments'] ?? array();

		if ( empty( $assignments[ $key ] ) ) {
			return 0;
		}

		$path = ltrim( str_replace( '\\', '/', (string) $assignments[ $key ] ), '/' );

		if ( isset( $this->media_map[ $path ] ) ) {
			return absint( $this->media_map[ $path ] );
		}

		return $this->find_attachment_by_basename( pathinfo( $path, PATHINFO_FILENAME ) );
	}

	/**
	 * Find attachment ID by file basename.
	 *
	 * @param string $basename Basename without extension.
	 * @return int
	 */
	private function find_attachment_by_basename( $basename ) {
		$key = strtolower( sln_credibility_normalize_logo_basename( (string) $basename ) );

		if ( isset( $this->basename_map[ $key ] ) ) {
			return absint( $this->basename_map[ $key ] );
		}

		$key = strtolower( sanitize_title( $basename ) );

		if ( isset( $this->basename_map[ $key ] ) ) {
			return absint( $this->basename_map[ $key ] );
		}

		foreach ( $this->basename_map as $map_key => $attachment_id ) {
			if ( false !== strpos( $map_key, $key ) || false !== strpos( $key, $map_key ) ) {
				return absint( $attachment_id );
			}
		}

		return 0;
	}

	/**
	 * Replace legacy localhost URLs with the current site URL.
	 *
	 * @param string $content Content string.
	 * @return string
	 */
	private function replace_urls( $content ) {
		if ( '' === $content ) {
			return $content;
		}

		$from_list = $this->content['url_replace_from'] ?? array(
			'http://localhost/new-smart-leading',
			'https://localhost/new-smart-leading',
		);

		$to = trailingslashit( home_url( '/' ) );

		foreach ( $from_list as $from ) {
			$content = str_replace( $from, $to, $content );
			$content = str_replace( untrailingslashit( $from ) . '/', $to, $content );
		}

		return $content;
	}
}

SLN_Demo_Importer::init();
