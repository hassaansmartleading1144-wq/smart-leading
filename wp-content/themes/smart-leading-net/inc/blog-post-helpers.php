<?php
/**
 * Single blog post helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Meta keys for native post fields.
 *
 * @return array<string, string>
 */
function sln_blog_meta_keys() {
	return array(
		'reviewer_name'         => '_sln_blog_reviewer_name',
		'reviewer_role'         => '_sln_blog_reviewer_role',
		'reviewer_image'        => '_sln_blog_reviewer_image',
		'takeaways'             => '_sln_blog_takeaways',
		'qa_left_title'         => '_sln_blog_qa_left_title',
		'qa_left_items'         => '_sln_blog_qa_left_items',
		'qa_right_title'        => '_sln_blog_qa_right_title',
		'qa_right_items'        => '_sln_blog_qa_right_items',
		'cta_heading'           => '_sln_blog_cta_heading',
		'cta_description'       => '_sln_blog_cta_description',
		'cta_label'             => '_sln_blog_cta_label',
		'cta_url'               => '_sln_blog_cta_url',
		'cta_secondary_label'   => '_sln_blog_cta_secondary_label',
		'cta_secondary_url'     => '_sln_blog_cta_secondary_url',
		'faq'                   => '_sln_blog_faq',
		'author_bio'            => '_sln_blog_author_bio',
		'featured_caption'      => '_sln_blog_featured_caption',
		'toc_include_h3'        => '_sln_blog_toc_include_h3',
	);
}

/**
 * @param int    $post_id Post ID.
 * @param string $key     Logical key from sln_blog_meta_keys().
 * @param mixed  $default Default.
 * @return mixed
 */
function sln_blog_get_meta( $post_id, $key, $default = '' ) {
	$keys = sln_blog_meta_keys();

	if ( empty( $keys[ $key ] ) ) {
		return $default;
	}

	$value = get_post_meta( $post_id, $keys[ $key ], true );

	if ( '' === $value || false === $value || array() === $value ) {
		return $default;
	}

	return $value;
}

/**
 * Estimated reading time in minutes (minimum 1).
 *
 * @param int|null $post_id Post ID.
 * @return int
 */
function sln_blog_reading_time_minutes( $post_id = null ) {
	$post = get_post( $post_id );

	if ( ! $post instanceof WP_Post ) {
		return 1;
	}

	$text  = wp_strip_all_tags( $post->post_title . ' ' . $post->post_excerpt . ' ' . $post->post_content );
	$words = str_word_count( $text );

	return max( 1, (int) ceil( $words / 200 ) );
}

/**
 * Contact page URL.
 *
 * @return string
 */
function sln_blog_contact_url() {
	$page = get_page_by_path( 'contact-us' );

	if ( $page instanceof WP_Post ) {
		return get_permalink( $page );
	}

	return home_url( '/contact-us/' );
}

/**
 * Blog index URL.
 *
 * @return string
 */
function sln_blog_archive_url() {
	$posts_page = (int) get_option( 'page_for_posts' );

	if ( $posts_page ) {
		return get_permalink( $posts_page );
	}

	return get_post_type_archive_link( 'post' ) ? get_post_type_archive_link( 'post' ) : home_url( '/blog/' );
}

/**
 * About page URL.
 *
 * @return string
 */
function sln_blog_about_url() {
	$page = get_page_by_path( 'about-us' );

	if ( $page instanceof WP_Post ) {
		return get_permalink( $page );
	}

	return home_url( '/about-us/' );
}

/**
 * Initials from a display name.
 *
 * @param string $name Name.
 * @return string
 */
function sln_blog_initials( $name ) {
	$parts = preg_split( '/\s+/', trim( (string) $name ) );
	$out   = '';

	foreach ( array_slice( (array) $parts, 0, 2 ) as $part ) {
		$out .= strtoupper( substr( $part, 0, 1 ) );
	}

	return $out ? $out : 'SL';
}

/**
 * Related posts: same category first, then recent fallback.
 *
 * @param int $post_id Current post.
 * @param int $limit   Number of posts.
 * @return WP_Post[]
 */
function sln_blog_related_posts( $post_id, $limit = 3 ) {
	$post_id = (int) $post_id;
	$limit   = (int) $limit;
	$found   = array();

	$cats = wp_get_post_categories( $post_id );

	if ( $cats ) {
		$q = new WP_Query(
			array(
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'posts_per_page'      => $limit,
				'post__not_in'        => array( $post_id ),
				'category__in'        => $cats,
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
				'orderby'             => 'date',
			)
		);

		$found = $q->posts;
	}

	if ( count( $found ) < $limit ) {
		$exclude = array_merge( array( $post_id ), wp_list_pluck( $found, 'ID' ) );
		$q       = new WP_Query(
			array(
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'posts_per_page'      => $limit - count( $found ),
				'post__not_in'        => $exclude,
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			)
		);

		$found = array_merge( $found, $q->posts );
	}

	return $found;
}

/**
 * Whether a major SEO plugin is managing schema.
 *
 * @return bool
 */
function sln_blog_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' )
		|| defined( 'RANK_MATH_VERSION' )
		|| defined( 'SEOPRESS_VERSION' )
		|| defined( 'AIOSEO_VERSION' )
		|| class_exists( 'WPSEO_Options' );
}

/**
 * Add unique IDs to H2/H3 in post content for TOC anchors.
 *
 * @param string $content Content.
 * @return string
 */
function sln_blog_add_heading_ids( $content ) {
	if ( ! is_singular( 'post' ) || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	if ( false === stripos( $content, '<h2' ) && false === stripos( $content, '<h3' ) ) {
		return $content;
	}

	$used = array();

	return preg_replace_callback(
		'/<h([23])([^>]*)>(.*?)<\/h\1>/is',
		function ( $m ) use ( &$used ) {
			$level   = $m[1];
			$attrs   = $m[2];
			$inner   = $m[3];
			$text    = wp_strip_all_tags( $inner );
			$base    = sanitize_title( $text );
			$base    = $base ? $base : 'section';
			$slug    = $base;
			$i       = 2;

			if ( preg_match( '/\sid=(["\'])([^"\']+)\1/i', $attrs, $idm ) ) {
				$slug = $idm[2];
				if ( isset( $used[ $slug ] ) ) {
					$attrs = preg_replace( '/\sid=(["\'])([^"\']+)\1/i', '', $attrs );
				} else {
					$used[ $slug ] = true;
					return $m[0];
				}
			}

			while ( isset( $used[ $slug ] ) ) {
				$slug = $base . '-' . $i;
				++$i;
			}

			$used[ $slug ] = true;

			return '<h' . $level . $attrs . ' id="' . esc_attr( $slug ) . '">' . $inner . '</h' . $level . '>';
		},
		$content
	);
}
add_filter( 'the_content', 'sln_blog_add_heading_ids', 12 );

/**
 * FAQPage JSON-LD when no SEO plugin is present.
 */
function sln_blog_faq_schema() {
	if ( ! is_singular( 'post' ) || sln_blog_seo_plugin_active() ) {
		return;
	}

	$faqs = sln_blog_get_meta( get_the_ID(), 'faq', array() );

	if ( ! is_array( $faqs ) || empty( $faqs ) ) {
		return;
	}

	$entities = array();

	foreach ( $faqs as $item ) {
		$q = isset( $item['q'] ) ? trim( (string) $item['q'] ) : '';
		$a = isset( $item['a'] ) ? trim( wp_strip_all_tags( (string) $item['a'] ) ) : '';

		if ( '' === $q || '' === $a ) {
			continue;
		}

		$entities[] = array(
			'@type'          => 'Question',
			'name'           => $q,
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => $a,
			),
		);
	}

	if ( empty( $entities ) ) {
		return;
	}

	$data = array(
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => $entities,
	);

	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "</script>\n";
}
add_action( 'wp_head', 'sln_blog_faq_schema', 30 );

/**
 * Lines from a textarea field.
 *
 * @param string $text Text.
 * @return string[]
 */
function sln_blog_lines( $text ) {
	$lines = preg_split( '/\r\n|\r|\n/', (string) $text );
	$out   = array();

	foreach ( (array) $lines as $line ) {
		$line = trim( $line );
		if ( '' !== $line ) {
			$out[] = $line;
		}
	}

	return $out;
}

/**
 * Default CTA values.
 *
 * @param int $post_id Post ID.
 * @return array<string, string>
 */
function sln_blog_cta_data( $post_id ) {
	$contact = sln_blog_contact_url();

	$heading = sln_blog_get_meta( $post_id, 'cta_heading', '' );
	$desc    = sln_blog_get_meta( $post_id, 'cta_description', '' );
	$label   = sln_blog_get_meta( $post_id, 'cta_label', '' );
	$url     = sln_blog_get_meta( $post_id, 'cta_url', '' );

	return array(
		'heading'         => $heading ? $heading : __( 'Get a free platform and website audit', 'smart-leading-net' ),
		'description'     => $desc ? $desc : __( "We'll review your current site's speed, SEO and setup, and show you exactly what to fix or rebuild.", 'smart-leading-net' ),
		'label'           => $label ? $label : __( 'Get My Free Proposal', 'smart-leading-net' ),
		'url'             => $url ? $url : $contact,
		'secondary_label' => (string) sln_blog_get_meta( $post_id, 'cta_secondary_label', '' ),
		'secondary_url'   => (string) sln_blog_get_meta( $post_id, 'cta_secondary_url', '' ),
	);
}

/**
 * Whether post content already includes a shortcode.
 *
 * @param int    $post_id   Post ID.
 * @param string $shortcode Shortcode tag.
 * @return bool
 */
function sln_blog_content_has_shortcode( $post_id, $shortcode ) {
	$post = get_post( $post_id );

	if ( ! $post instanceof WP_Post ) {
		return false;
	}

	return has_shortcode( $post->post_content, $shortcode );
}

/**
 * Render the Short Answer box from post fields.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function sln_blog_render_quick_answer( $post_id ) {
	$left_title  = sln_blog_get_meta( $post_id, 'qa_left_title', '' );
	$left_items  = sln_blog_lines( sln_blog_get_meta( $post_id, 'qa_left_items', '' ) );
	$right_title = sln_blog_get_meta( $post_id, 'qa_right_title', '' );
	$right_items = sln_blog_lines( sln_blog_get_meta( $post_id, 'qa_right_items', '' ) );

	if ( ( ! $left_title || empty( $left_items ) ) && ( ! $right_title || empty( $right_items ) ) ) {
		return '';
	}

	ob_start();
	?>
	<div class="quick-answer" role="note" aria-label="<?php esc_attr_e( 'Quick answer', 'smart-leading-net' ); ?>">
		<div class="quick-answer__title">
			<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M13 2 4 14h7l-1 8 9-12h-7z"/></svg>
			<?php esc_html_e( 'The short answer', 'smart-leading-net' ); ?>
		</div>
		<div class="quick-answer__grid">
			<?php if ( $left_title && $left_items ) : ?>
				<div class="qa-col qa-col--shopify">
					<h3><i aria-hidden="true"></i><?php echo esc_html( $left_title ); ?></h3>
					<ul class="qa-list">
						<?php foreach ( $left_items as $line ) : ?>
							<li><?php echo esc_html( $line ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
			<?php if ( $right_title && $right_items ) : ?>
				<div class="qa-col qa-col--wp">
					<h3><i aria-hidden="true"></i><?php echo esc_html( $right_title ); ?></h3>
					<ul class="qa-list">
						<?php foreach ( $right_items as $line ) : ?>
							<li><?php echo esc_html( $line ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php
	return (string) ob_get_clean();
}

/**
 * Render FAQ accordion from post fields.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function sln_blog_render_faq( $post_id ) {
	$faqs = sln_blog_get_meta( $post_id, 'faq', array() );

	if ( ! is_array( $faqs ) || empty( $faqs ) ) {
		return '';
	}

	ob_start();
	$has_heading = false;
	foreach ( $faqs as $faq ) {
		$q = isset( $faq['q'] ) ? trim( (string) $faq['q'] ) : '';
		if ( '' !== $q ) {
			$has_heading = true;
			break;
		}
	}
	if ( ! $has_heading ) {
		ob_end_clean();
		return '';
	}
	?>
	<h2 id="faqs" data-toc="FAQs"><?php esc_html_e( 'Frequently asked questions', 'smart-leading-net' ); ?></h2>
	<div class="faq">
		<?php foreach ( $faqs as $faq ) : ?>
			<?php
			$q = isset( $faq['q'] ) ? $faq['q'] : '';
			$a = isset( $faq['a'] ) ? $faq['a'] : '';
			if ( '' === $q ) {
				continue;
			}
			?>
			<details>
				<summary><?php echo esc_html( $q ); ?><i aria-hidden="true"></i></summary>
				<div class="faq__a"><?php echo wpautop( wp_kses_post( $a ) ); ?></div>
			</details>
		<?php endforeach; ?>
	</div>
	<?php
	return (string) ob_get_clean();
}

/**
 * Shortcode: [sln_blog_quick_answer]
 *
 * @return string
 */
function sln_blog_quick_answer_shortcode() {
	if ( ! is_singular( 'post' ) ) {
		return '';
	}

	return sln_blog_render_quick_answer( get_the_ID() );
}
add_shortcode( 'sln_blog_quick_answer', 'sln_blog_quick_answer_shortcode' );

/**
 * Shortcode: [sln_blog_faq]
 *
 * @return string
 */
function sln_blog_faq_shortcode() {
	if ( ! is_singular( 'post' ) ) {
		return '';
	}

	return sln_blog_render_faq( get_the_ID() );
}
add_shortcode( 'sln_blog_faq', 'sln_blog_faq_shortcode' );

/**
 * Style Gutenberg tables and quotes like the reference article components.
 *
 * @param string $block_content Rendered HTML.
 * @param array  $block         Block data.
 * @return string
 */
function sln_blog_render_designed_blocks( $block_content, $block ) {
	if ( ! is_singular( 'post' ) || ! is_string( $block_content ) ) {
		return $block_content;
	}

	$name = isset( $block['blockName'] ) ? $block['blockName'] : '';

	if ( 'core/table' === $name ) {
		if ( false === strpos( $block_content, 'table-wrap' ) ) {
			$block_content = str_replace( 'wp-block-table', 'wp-block-table table-wrap', $block_content );
		}
		if ( false === strpos( $block_content, 'compare-table' ) ) {
			$block_content = preg_replace( '/<table\b/', '<table class="compare-table"', $block_content, 1 );
		}
	}

	if ( 'core/quote' === $name && false === strpos( $block_content, 'pull-quote' ) ) {
		$block_content = str_replace( 'wp-block-quote', 'wp-block-quote pull-quote', $block_content );
	}

	return $block_content;
}
add_filter( 'render_block', 'sln_blog_render_designed_blocks', 10, 2 );
