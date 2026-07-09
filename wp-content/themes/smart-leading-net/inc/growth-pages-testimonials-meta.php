<?php
/**
 * Growth Pages — Testimonials section meta box and helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SLN_GP_TESTIMONIALS_SECTION_META', '_sln_gp_testimonials_section' );
define( 'SLN_GP_TESTIMONIALS_STATS_META', '_sln_gp_testimonials_stats' );
define( 'SLN_GP_TESTIMONIALS_SUMMARY_META', '_sln_gp_testimonials_summary' );
define( 'SLN_GP_TESTIMONIALS_REVIEWS_META', '_sln_gp_testimonials_reviews' );
define( 'SLN_GP_TESTIMONIALS_UPLOADS', '2026/05/' );

/**
 * Default icon filenames for stats when no attachment is set.
 *
 * @return array<int, string>
 */
function sln_get_growth_page_testimonials_default_stat_icons() {
	return array(
		'client-reviews.svg',
		'rating-star.svg',
		'website-review.svg',
		'revenue review.svg',
	);
}

/**
 * Default Testimonials section content.
 *
 * @return array<string, string>
 */
function sln_get_growth_page_default_testimonials_section() {
	return array(
		'label'          => __( 'Testimonials', 'smart-leading-net' ),
		'heading_lead'   => __( 'Trusted Partnerships Built On', 'smart-leading-net' ),
		'highlight_word' => __( 'Results', 'smart-leading-net' ),
	);
}

/**
 * Default stats footer summary.
 *
 * @return array<string, mixed>
 */
function sln_get_growth_page_default_testimonials_summary() {
	return array(
		'review_title'  => __( '28k+ Client Reviews', 'smart-leading-net' ),
		'star_rating'   => 5,
		'verified_text' => __( 'Verified', 'smart-leading-net' ),
	);
}

/**
 * Default stat row.
 *
 * @param array<string, mixed> $args Stat defaults.
 * @return array<string, mixed>
 */
function sln_get_growth_page_default_testimonials_stat( $args ) {
	$defaults = array(
		'icon_id'          => 0,
		'icon_fallback'    => '',
		'counter_value'    => '',
		'counter_prefix'   => '',
		'counter_suffix'   => '',
		'counter_decimals' => 0,
		'label'            => '',
	);

	return wp_parse_args( $args, $defaults );
}

/**
 * Default stats rows.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_get_growth_page_default_testimonials_stats() {
	$icons = sln_get_growth_page_testimonials_default_stat_icons();

	return array(
		sln_get_growth_page_default_testimonials_stat(
			array(
				'icon_fallback'    => $icons[0],
				'counter_value'    => '28',
				'counter_suffix'   => 'K+',
				'counter_decimals' => 0,
				'label'            => __( 'Client Reviews', 'smart-leading-net' ),
			)
		),
		sln_get_growth_page_default_testimonials_stat(
			array(
				'icon_fallback'    => $icons[1],
				'counter_value'    => '4.9',
				'counter_suffix'   => '★',
				'counter_decimals' => 1,
				'label'            => __( 'Average Rating', 'smart-leading-net' ),
			)
		),
		sln_get_growth_page_default_testimonials_stat(
			array(
				'icon_fallback'    => $icons[2],
				'counter_value'    => '200',
				'counter_suffix'   => '+',
				'counter_decimals' => 0,
				'label'            => __( 'Website Build', 'smart-leading-net' ),
			)
		),
		sln_get_growth_page_default_testimonials_stat(
			array(
				'icon_fallback'    => $icons[3],
				'counter_value'    => '50',
				'counter_prefix'   => '$',
				'counter_suffix'   => 'M+',
				'counter_decimals' => 0,
				'label'            => __( 'Revenue Generated', 'smart-leading-net' ),
			)
		),
	);
}

/**
 * Default testimonial review row.
 *
 * @param array<string, mixed> $args Review defaults.
 * @return array<string, mixed>
 */
function sln_get_growth_page_default_testimonials_review( $args ) {
	$defaults = array(
		'rating'           => 5,
		'text'             => '',
		'author_initials'  => '',
		'author_name'      => '',
		'author_title'     => '',
		'active'           => true,
	);

	return wp_parse_args( $args, $defaults );
}

/**
 * Default testimonial reviews.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_get_growth_page_default_testimonials_reviews() {
	return array(
		sln_get_growth_page_default_testimonials_review(
			array(
				'rating'          => 5,
				'text'            => __( 'Highly cooperative and honest with their work. They developed my business website and I am super happy with them. On time delivery and 24hrs support. They also manage our social media — they know what they\'re doing.', 'smart-leading-net' ),
				'author_initials' => 'SM',
				'author_name'     => __( 'Sarah Mitchell', 'smart-leading-net' ),
				'author_title'    => __( 'Growth Labs', 'smart-leading-net' ),
				'active'          => true,
			)
		),
		sln_get_growth_page_default_testimonials_review(
			array(
				'rating'          => 5,
				'text'            => __( 'Before working with Smart Leading, our campaigns lacked direction. They helped us build a clear strategy, improve conversions, and understand exactly where our growth was coming from.', 'smart-leading-net' ),
				'author_initials' => 'JC',
				'author_name'     => __( 'James Carter', 'smart-leading-net' ),
				'author_title'    => __( 'Managing Director', 'smart-leading-net' ),
				'active'          => true,
			)
		),
	);
}

/**
 * Sanitize a stats row.
 *
 * @param array<string, mixed> $stat Raw stat data.
 * @return array<string, mixed>
 */
function sln_sanitize_growth_page_testimonials_stat( $stat ) {
	if ( ! is_array( $stat ) ) {
		return array();
	}

	$icon_id = sln_sanitize_media_attachment_id( $stat['icon_id'] ?? 0, array( 'image/svg+xml' ) );

	$decimals = isset( $stat['counter_decimals'] ) ? absint( $stat['counter_decimals'] ) : 0;

	return array(
		'icon_id'          => $icon_id,
		'counter_value'    => isset( $stat['counter_value'] ) ? sanitize_text_field( wp_unslash( $stat['counter_value'] ) ) : '',
		'counter_prefix'   => isset( $stat['counter_prefix'] ) ? sanitize_text_field( wp_unslash( $stat['counter_prefix'] ) ) : '',
		'counter_suffix'   => isset( $stat['counter_suffix'] ) ? sanitize_text_field( wp_unslash( $stat['counter_suffix'] ) ) : '',
		'counter_decimals' => min( 2, $decimals ),
		'label'            => isset( $stat['label'] ) ? sanitize_text_field( wp_unslash( $stat['label'] ) ) : '',
	);
}

/**
 * Sanitize footer summary fields.
 *
 * @param array<string, mixed> $summary Raw summary data.
 * @return array<string, mixed>
 */
function sln_sanitize_growth_page_testimonials_summary( $summary ) {
	if ( ! is_array( $summary ) ) {
		return sln_get_growth_page_default_testimonials_summary();
	}

	$rating = isset( $summary['star_rating'] ) ? absint( $summary['star_rating'] ) : 5;

	return array(
		'review_title'  => isset( $summary['review_title'] ) ? sanitize_text_field( wp_unslash( $summary['review_title'] ) ) : '',
		'star_rating'   => max( 1, min( 5, $rating ) ),
		'verified_text' => isset( $summary['verified_text'] ) ? sanitize_text_field( wp_unslash( $summary['verified_text'] ) ) : '',
	);
}

/**
 * Sanitize a testimonial review row.
 *
 * @param array<string, mixed> $review Raw review data.
 * @return array<string, mixed>
 */
function sln_sanitize_growth_page_testimonials_review( $review ) {
	if ( ! is_array( $review ) ) {
		return array();
	}

	$rating = isset( $review['rating'] ) ? absint( $review['rating'] ) : 5;

	return array(
		'rating'          => max( 1, min( 5, $rating ) ),
		'text'            => isset( $review['text'] ) ? wp_kses_post( wp_unslash( $review['text'] ) ) : '',
		'author_initials' => isset( $review['author_initials'] ) ? sanitize_text_field( wp_unslash( $review['author_initials'] ) ) : '',
		'author_name'     => isset( $review['author_name'] ) ? sanitize_text_field( wp_unslash( $review['author_name'] ) ) : '',
		'author_title'    => isset( $review['author_title'] ) ? sanitize_text_field( wp_unslash( $review['author_title'] ) ) : '',
		'active'          => ! empty( $review['active'] ),
	);
}

/**
 * Format a stat number for initial display.
 *
 * @param array<string, mixed> $stat Stat row.
 * @return string
 */
function sln_growth_page_testimonials_format_stat_number( $stat ) {
	$value    = isset( $stat['counter_value'] ) ? (string) $stat['counter_value'] : '';
	$prefix   = isset( $stat['counter_prefix'] ) ? (string) $stat['counter_prefix'] : '';
	$suffix   = isset( $stat['counter_suffix'] ) ? (string) $stat['counter_suffix'] : '';
	$decimals = isset( $stat['counter_decimals'] ) ? absint( $stat['counter_decimals'] ) : 0;

	if ( '' === trim( $value ) ) {
		return '';
	}

	if ( is_numeric( $value ) ) {
		$numeric = (float) $value;

		if ( $decimals > 0 ) {
			$value = number_format( $numeric, $decimals, '.', '' );
		} else {
			$value = (string) (int) $numeric;
		}
	}

	return $prefix . $value . $suffix;
}

/**
 * Get Testimonials section data for frontend.
 *
 * @param int|null $post_id Post ID.
 * @return array<string, mixed>
 */
function sln_get_growth_page_testimonials( $post_id = null ) {
	if ( function_exists( 'sln_is_seo_services_page' ) && sln_is_seo_services_page() ) {
		return sln_get_seo_page_testimonials_data();
	}

	$post_id = $post_id ? absint( $post_id ) : get_the_ID();

	$section_defaults = sln_get_growth_page_default_testimonials_section();
	$summary_defaults = sln_get_growth_page_default_testimonials_summary();

	$section     = sln_growth_page_get_section_settings( $post_id, SLN_GP_TESTIMONIALS_SECTION_META, $section_defaults );
	$stats_exist = metadata_exists( 'post', $post_id, SLN_GP_TESTIMONIALS_STATS_META );
	$stats       = sln_growth_page_get_repeater_rows( $post_id, SLN_GP_TESTIMONIALS_STATS_META, sln_get_growth_page_default_testimonials_stats() );
	$summary     = sln_growth_page_get_section_settings( $post_id, SLN_GP_TESTIMONIALS_SUMMARY_META, $summary_defaults );
	$reviews     = sln_growth_page_get_repeater_rows( $post_id, SLN_GP_TESTIMONIALS_REVIEWS_META, sln_get_growth_page_default_testimonials_reviews() );

	$active_stats   = array();
	$icon_fallbacks = sln_get_growth_page_testimonials_default_stat_icons();
	$fallback_index = 0;

	foreach ( $stats as $stat ) {
		if ( ! is_array( $stat ) ) {
			continue;
		}

		$sanitized = sln_sanitize_growth_page_testimonials_stat( $stat );

		if ( '' === trim( $sanitized['counter_value'] ) && '' === trim( $sanitized['label'] ) ) {
			continue;
		}

		$icon_fallback = '';

		if ( empty( $sanitized['icon_id'] ) && ! $stats_exist && isset( $icon_fallbacks[ $fallback_index ] ) ) {
			$icon_fallback = $icon_fallbacks[ $fallback_index ];
		}

		$active_stats[] = array(
			'icon_id'          => $sanitized['icon_id'],
			'icon_fallback'    => $icon_fallback,
			'counter_value'    => $sanitized['counter_value'],
			'counter_prefix'   => $sanitized['counter_prefix'],
			'counter_suffix'   => $sanitized['counter_suffix'],
			'counter_decimals' => $sanitized['counter_decimals'],
			'label'            => $sanitized['label'],
			'display_number'   => sln_growth_page_testimonials_format_stat_number( $sanitized ),
		);

		++$fallback_index;
	}

	$active_reviews = array();

	foreach ( $reviews as $review ) {
		if ( ! is_array( $review ) ) {
			continue;
		}

		$sanitized = sln_sanitize_growth_page_testimonials_review( $review );

		if ( empty( $sanitized['active'] ) ) {
			continue;
		}

		if ( '' === trim( $sanitized['text'] ) && '' === trim( $sanitized['author_name'] ) ) {
			continue;
		}

		$active_reviews[] = $sanitized;
	}

	$uploads_url = trailingslashit( content_url( '/uploads/' . SLN_GP_TESTIMONIALS_UPLOADS ) );
	$bg_file     = WP_CONTENT_DIR . '/uploads/' . SLN_GP_TESTIMONIALS_UPLOADS . 'testimonials-bg.webp';

	if ( file_exists( $bg_file ) ) {
		$background_url = $uploads_url . rawurlencode( 'testimonials-bg.webp' );
	} else {
		$background_url = $uploads_url . 'case-studies-bg.webp';
	}

	return array(
		'label'          => $section['label'],
		'heading_lead'   => $section['heading_lead'],
		'highlight_word' => $section['highlight_word'],
		'background_url' => $background_url,
		'stats'          => $active_stats,
		'summary'        => sln_sanitize_growth_page_testimonials_summary( $summary ),
		'reviews'        => $active_reviews,
	);
}

/**
 * Check whether Testimonials section should render.
 *
 * @param int|null $post_id Post ID.
 * @return bool
 */
function sln_growth_page_testimonials_has_content( $post_id = null ) {
	$data = sln_get_growth_page_testimonials( $post_id );

	return ! empty( $data['stats'] ) || ! empty( $data['reviews'] );
}

/**
 * Get raw stats for admin.
 *
 * @param int $post_id Post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_growth_page_testimonials_stats_for_admin( $post_id ) {
	if ( ! metadata_exists( 'post', $post_id, SLN_GP_TESTIMONIALS_STATS_META ) ) {
		return sln_get_growth_page_default_testimonials_stats();
	}

	$stats = get_post_meta( $post_id, SLN_GP_TESTIMONIALS_STATS_META, true );

	if ( ! is_array( $stats ) ) {
		return array();
	}

	return array_map(
		static function ( $stat ) {
			$stat = is_array( $stat ) ? $stat : array();

			return wp_parse_args(
				$stat,
				array(
					'icon_id'          => 0,
					'counter_value'    => '',
					'counter_prefix'   => '',
					'counter_suffix'   => '',
					'counter_decimals' => 0,
					'label'            => '',
				)
			);
		},
		$stats
	);
}

/**
 * Get raw reviews for admin.
 *
 * @param int $post_id Post ID.
 * @return array<int, array<string, mixed>>
 */
function sln_get_growth_page_testimonials_reviews_for_admin( $post_id ) {
	if ( ! metadata_exists( 'post', $post_id, SLN_GP_TESTIMONIALS_REVIEWS_META ) ) {
		return sln_get_growth_page_default_testimonials_reviews();
	}

	$reviews = get_post_meta( $post_id, SLN_GP_TESTIMONIALS_REVIEWS_META, true );

	if ( ! is_array( $reviews ) ) {
		return array();
	}

	return array_map(
		static function ( $review ) {
			$review = is_array( $review ) ? $review : array();

			return wp_parse_args(
				$review,
				array(
					'rating'          => 5,
					'text'            => '',
					'author_initials' => '',
					'author_name'     => '',
					'author_title'    => '',
					'active'          => true,
				)
			);
		},
		$reviews
	);
}

/**
 * Register Testimonials meta box.
 */
function sln_growth_page_register_testimonials_meta_box() {
	add_meta_box(
		'sln-growth-page-testimonials',
		__( 'Testimonials Section', 'smart-leading-net' ),
		'sln_growth_page_render_testimonials_meta_box',
		SLN_GROWTH_PAGE_POST_TYPE,
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'sln_growth_page_register_testimonials_meta_box' );

/**
 * Render a stats row in admin.
 *
 * @param int                  $index Stat index.
 * @param array<string, mixed> $stat  Stat data.
 */
function sln_growth_page_render_testimonials_stat_row( $index, $stat ) {
	$stat = wp_parse_args(
		$stat,
		array(
			'icon_id'          => 0,
			'counter_value'    => '',
			'counter_prefix'   => '',
			'counter_suffix'   => '',
			'counter_decimals' => 0,
			'label'            => '',
		)
	);
	?>
	<div class="sln-os-admin__repeatable-row sln-gp-admin__tm-stat-row">
		<div class="sln-gp-admin__card-controls">
			<button type="button" class="button button-small sln-gp-admin__tm-stat-move-up"><?php esc_html_e( 'Move Up', 'smart-leading-net' ); ?></button>
			<button type="button" class="button button-small sln-gp-admin__tm-stat-move-down"><?php esc_html_e( 'Move Down', 'smart-leading-net' ); ?></button>
		</div>
		<div class="sln-os-admin__repeatable-fields sln-gp-admin__card-fields">
			<label class="sln-gp-admin__field-full">
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Icon SVG Upload', 'smart-leading-net' ); ?></span>
				<?php
				sln_our_services_render_media_field(
					'sln_gp_testimonials_stats[' . $index . '][icon_id]',
					absint( $stat['icon_id'] ),
					'SVG'
				);
				?>
			</label>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Number', 'smart-leading-net' ); ?></span>
				<input type="text" class="regular-text" name="sln_gp_testimonials_stats[<?php echo esc_attr( $index ); ?>][counter_value]" value="<?php echo esc_attr( $stat['counter_value'] ); ?>" placeholder="<?php esc_attr_e( '28', 'smart-leading-net' ); ?>" />
			</label>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Number Prefix', 'smart-leading-net' ); ?></span>
				<input type="text" class="regular-text" name="sln_gp_testimonials_stats[<?php echo esc_attr( $index ); ?>][counter_prefix]" value="<?php echo esc_attr( $stat['counter_prefix'] ); ?>" placeholder="<?php esc_attr_e( '$', 'smart-leading-net' ); ?>" />
			</label>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Number Suffix', 'smart-leading-net' ); ?></span>
				<input type="text" class="regular-text" name="sln_gp_testimonials_stats[<?php echo esc_attr( $index ); ?>][counter_suffix]" value="<?php echo esc_attr( $stat['counter_suffix'] ); ?>" placeholder="<?php esc_attr_e( 'K+', 'smart-leading-net' ); ?>" />
			</label>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Decimal Places', 'smart-leading-net' ); ?></span>
				<input type="number" min="0" max="2" step="1" class="small-text" name="sln_gp_testimonials_stats[<?php echo esc_attr( $index ); ?>][counter_decimals]" value="<?php echo esc_attr( $stat['counter_decimals'] ); ?>" />
			</label>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Label', 'smart-leading-net' ); ?></span>
				<input type="text" class="large-text" name="sln_gp_testimonials_stats[<?php echo esc_attr( $index ); ?>][label]" value="<?php echo esc_attr( $stat['label'] ); ?>" />
			</label>
		</div>
		<button type="button" class="button-link-delete sln-gp-admin__tm-remove-stat"><?php esc_html_e( 'Remove Stat', 'smart-leading-net' ); ?></button>
	</div>
	<?php
}

/**
 * Render a testimonial review row in admin.
 *
 * @param int                  $index  Review index.
 * @param array<string, mixed> $review Review data.
 */
function sln_growth_page_render_testimonials_review_row( $index, $review ) {
	$review = wp_parse_args(
		$review,
		array(
			'rating'          => 5,
			'text'            => '',
			'author_initials' => '',
			'author_name'     => '',
			'author_title'    => '',
			'active'          => true,
		)
	);
	?>
	<div class="sln-os-admin__repeatable-row sln-gp-admin__tm-review-row">
		<div class="sln-gp-admin__card-controls">
			<button type="button" class="button button-small sln-gp-admin__tm-review-move-up"><?php esc_html_e( 'Move Up', 'smart-leading-net' ); ?></button>
			<button type="button" class="button button-small sln-gp-admin__tm-review-move-down"><?php esc_html_e( 'Move Down', 'smart-leading-net' ); ?></button>
		</div>
		<div class="sln-os-admin__repeatable-fields sln-gp-admin__card-fields">
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Rating', 'smart-leading-net' ); ?></span>
				<input type="number" min="1" max="5" step="1" class="small-text" name="sln_gp_testimonials_reviews[<?php echo esc_attr( $index ); ?>][rating]" value="<?php echo esc_attr( $review['rating'] ); ?>" />
			</label>
			<label class="sln-gp-admin__field-full">
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Review Text', 'smart-leading-net' ); ?></span>
				<textarea class="large-text" rows="4" name="sln_gp_testimonials_reviews[<?php echo esc_attr( $index ); ?>][text]"><?php echo esc_textarea( $review['text'] ); ?></textarea>
			</label>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Author Initials', 'smart-leading-net' ); ?></span>
				<input type="text" class="regular-text" name="sln_gp_testimonials_reviews[<?php echo esc_attr( $index ); ?>][author_initials]" value="<?php echo esc_attr( $review['author_initials'] ); ?>" />
			</label>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Name', 'smart-leading-net' ); ?></span>
				<input type="text" class="large-text" name="sln_gp_testimonials_reviews[<?php echo esc_attr( $index ); ?>][author_name]" value="<?php echo esc_attr( $review['author_name'] ); ?>" />
			</label>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Position', 'smart-leading-net' ); ?></span>
				<input type="text" class="large-text" name="sln_gp_testimonials_reviews[<?php echo esc_attr( $index ); ?>][author_title]" value="<?php echo esc_attr( $review['author_title'] ); ?>" />
			</label>
			<label>
				<span class="sln-os-admin__field-label"><?php esc_html_e( 'Active Testimonial', 'smart-leading-net' ); ?></span>
				<select name="sln_gp_testimonials_reviews[<?php echo esc_attr( $index ); ?>][active]">
					<option value="1" <?php selected( ! empty( $review['active'] ) ); ?>><?php esc_html_e( 'Yes', 'smart-leading-net' ); ?></option>
					<option value="0" <?php selected( empty( $review['active'] ) ); ?>><?php esc_html_e( 'No', 'smart-leading-net' ); ?></option>
				</select>
			</label>
		</div>
		<button type="button" class="button-link-delete sln-gp-admin__tm-remove-review"><?php esc_html_e( 'Remove Testimonial', 'smart-leading-net' ); ?></button>
	</div>
	<?php
}

/**
 * Render Testimonials meta box.
 *
 * @param WP_Post $post Current post.
 */
function sln_growth_page_render_testimonials_meta_box( $post ) {
	wp_nonce_field( 'sln_growth_page_save_testimonials', 'sln_growth_page_testimonials_nonce', false );

	$defaults = sln_get_growth_page_default_testimonials_section();
	$section  = get_post_meta( $post->ID, SLN_GP_TESTIMONIALS_SECTION_META, true );
	$section  = is_array( $section ) ? array_intersect_key( wp_parse_args( $section, $defaults ), $defaults ) : $defaults;

	$summary_defaults = sln_get_growth_page_default_testimonials_summary();
	$summary          = get_post_meta( $post->ID, SLN_GP_TESTIMONIALS_SUMMARY_META, true );
	$summary          = is_array( $summary ) ? array_intersect_key( wp_parse_args( $summary, $summary_defaults ), $summary_defaults ) : $summary_defaults;

	$stats   = sln_get_growth_page_testimonials_stats_for_admin( $post->ID );
	$reviews = sln_get_growth_page_testimonials_reviews_for_admin( $post->ID );
	$orders  = sln_get_growth_page_section_orders( $post->ID );
	$order   = isset( $orders['testimonials'] ) ? absint( $orders['testimonials'] ) : 9;
	?>
	<div class="sln-gp-admin">
		<p class="description"><?php esc_html_e( 'Manage the Testimonials section. Uses the same frontend design as the Home Page Testimonials section.', 'smart-leading-net' ); ?></p>

		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row"><label for="sln_gp_testimonials_label"><?php esc_html_e( 'Small Heading', 'smart-leading-net' ); ?></label></th>
					<td><input type="text" class="large-text" id="sln_gp_testimonials_label" name="sln_gp_testimonials_section[label]" value="<?php echo esc_attr( $section['label'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_testimonials_heading_lead"><?php esc_html_e( 'Main Heading', 'smart-leading-net' ); ?></label></th>
					<td><input type="text" class="large-text" id="sln_gp_testimonials_heading_lead" name="sln_gp_testimonials_section[heading_lead]" value="<?php echo esc_attr( $section['heading_lead'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_testimonials_highlight_word"><?php esc_html_e( 'Highlighted Word', 'smart-leading-net' ); ?></label></th>
					<td><input type="text" class="large-text" id="sln_gp_testimonials_highlight_word" name="sln_gp_testimonials_section[highlight_word]" value="<?php echo esc_attr( $section['highlight_word'] ); ?>" /></td>
				</tr>
				<tr>
					<th scope="row"><label for="sln_gp_section_order_testimonials"><?php esc_html_e( 'Section Order Number', 'smart-leading-net' ); ?></label></th>
					<td>
						<input type="number" min="1" max="99" step="1" class="small-text" id="sln_gp_section_order_testimonials" name="sln_gp_section_orders[testimonials]" value="<?php echo esc_attr( $order ); ?>" />
					</td>
				</tr>
			</tbody>
		</table>

		<div class="sln-os-admin__subsection">
			<h3><?php esc_html_e( 'Stats (Review Count, Average Rating, Website Build, Revenue Generated)', 'smart-leading-net' ); ?></h3>
			<div class="sln-os-admin__repeatable sln-gp-admin__tm-stats-list">
				<?php foreach ( $stats as $index => $stat ) : ?>
					<?php sln_growth_page_render_testimonials_stat_row( $index, $stat ); ?>
				<?php endforeach; ?>
			</div>
			<p><button type="button" class="button button-secondary sln-gp-admin__add-tm-stat"><?php esc_html_e( 'Add Stat', 'smart-leading-net' ); ?></button></p>
		</div>

		<div class="sln-os-admin__subsection">
			<h3><?php esc_html_e( 'Bottom Review Summary', 'smart-leading-net' ); ?></h3>
			<table class="form-table" role="presentation">
				<tbody>
					<tr>
						<th scope="row"><label for="sln_gp_testimonials_review_title"><?php esc_html_e( 'Review Card Title', 'smart-leading-net' ); ?></label></th>
						<td><input type="text" class="large-text" id="sln_gp_testimonials_review_title" name="sln_gp_testimonials_summary[review_title]" value="<?php echo esc_attr( $summary['review_title'] ); ?>" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="sln_gp_testimonials_star_rating"><?php esc_html_e( 'Average Rating', 'smart-leading-net' ); ?></label></th>
						<td><input type="number" min="1" max="5" step="1" class="small-text" id="sln_gp_testimonials_star_rating" name="sln_gp_testimonials_summary[star_rating]" value="<?php echo esc_attr( $summary['star_rating'] ); ?>" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="sln_gp_testimonials_verified_text"><?php esc_html_e( 'Verified Text', 'smart-leading-net' ); ?></label></th>
						<td><input type="text" class="regular-text" id="sln_gp_testimonials_verified_text" name="sln_gp_testimonials_summary[verified_text]" value="<?php echo esc_attr( $summary['verified_text'] ); ?>" /></td>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="sln-os-admin__subsection">
			<h3><?php esc_html_e( 'Testimonial Cards', 'smart-leading-net' ); ?></h3>
			<div class="sln-os-admin__repeatable sln-gp-admin__tm-reviews-list">
				<?php foreach ( $reviews as $index => $review ) : ?>
					<?php sln_growth_page_render_testimonials_review_row( $index, $review ); ?>
				<?php endforeach; ?>
			</div>
			<p><button type="button" class="button button-secondary sln-gp-admin__add-tm-review"><?php esc_html_e( 'Add Testimonial', 'smart-leading-net' ); ?></button></p>
		</div>
	</div>
	<?php
}

/**
 * Save Testimonials meta box values.
 *
 * @param int $post_id Post ID.
 */
function sln_growth_page_save_testimonials_meta( $post_id ) {
	if ( ! sln_growth_page_should_save_meta( $post_id, 'sln_growth_page_testimonials_nonce', 'sln_growth_page_save_testimonials' ) ) {
		return;
	}

	if ( isset( $_POST['sln_gp_testimonials_section'] ) && is_array( $_POST['sln_gp_testimonials_section'] ) ) {
		$raw     = wp_unslash( $_POST['sln_gp_testimonials_section'] );
		$section = array(
			'label'          => isset( $raw['label'] ) ? sanitize_text_field( $raw['label'] ) : '',
			'heading_lead'   => isset( $raw['heading_lead'] ) ? sanitize_text_field( $raw['heading_lead'] ) : '',
			'highlight_word' => isset( $raw['highlight_word'] ) ? sanitize_text_field( $raw['highlight_word'] ) : '',
		);

		update_post_meta( $post_id, SLN_GP_TESTIMONIALS_SECTION_META, $section );
	}

	if ( isset( $_POST['sln_gp_testimonials_summary'] ) && is_array( $_POST['sln_gp_testimonials_summary'] ) ) {
		update_post_meta(
			$post_id,
			SLN_GP_TESTIMONIALS_SUMMARY_META,
			sln_sanitize_growth_page_testimonials_summary( wp_unslash( $_POST['sln_gp_testimonials_summary'] ) )
		);
	}

	sln_growth_page_update_repeater_meta(
		$post_id,
		SLN_GP_TESTIMONIALS_STATS_META,
		'sln_gp_testimonials_stats',
		'sln_sanitize_growth_page_testimonials_stat',
		static function ( $stat ) {
			return '' !== trim( $stat['counter_value'] ) || '' !== trim( $stat['label'] );
		}
	);

	sln_growth_page_update_repeater_meta(
		$post_id,
		SLN_GP_TESTIMONIALS_REVIEWS_META,
		'sln_gp_testimonials_reviews',
		'sln_sanitize_growth_page_testimonials_review',
		static function ( $review ) {
			return '' !== trim( $review['text'] ) || '' !== trim( $review['author_name'] );
		}
	);
}

/**
 * Render star icons for testimonials.
 *
 * @param int  $rating Star count (1-5).
 * @param bool $hidden Whether stars are decorative only.
 */
function sln_growth_page_render_testimonial_stars( $rating, $hidden = false ) {
	$rating = max( 1, min( 5, absint( $rating ) ) );
	?>
	<div class="testimonials__stars<?php echo $hidden ? ' testimonials__stars--footer' : ''; ?>" <?php echo $hidden ? 'aria-hidden="true"' : 'aria-label="' . esc_attr( sprintf( /* translators: %d: star rating */ __( '%d out of 5 stars', 'smart-leading-net' ), $rating ) ) . '"'; ?>>
		<?php for ( $i = 0; $i < $rating; $i++ ) : ?>
			<span<?php echo $hidden ? '' : ' aria-hidden="true"'; ?>>★</span>
		<?php endfor; ?>
	</div>
	<?php
}
