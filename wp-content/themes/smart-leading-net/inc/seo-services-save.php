<?php
/**
 * SEO Services page — save handlers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register save hook for SEO Services pages.
 */
function sln_seo_services_register_save_hooks() {
	add_action( 'save_post_page', 'sln_seo_services_save_meta', 10, 2 );
}
add_action( 'init', 'sln_seo_services_register_save_hooks', 20 );

/**
 * Output master save nonce after title.
 *
 * @param WP_Post $post Current post.
 */
function sln_seo_services_output_save_nonce( $post ) {
	if ( ! $post instanceof WP_Post || 'page' !== $post->post_type ) {
		return;
	}

	if ( ! sln_seo_services_admin_is_target_page( $post ) ) {
		return;
	}

	wp_nonce_field( 'sln_seo_services_save_meta', 'sln_seo_services_master_nonce', false );
}
add_action( 'edit_form_after_title', 'sln_seo_services_output_save_nonce' );

/**
 * Whether save should proceed.
 *
 * @param int $post_id Post ID.
 * @return bool
 */
function sln_seo_services_should_save_meta( $post_id ) {
	$post_id = absint( $post_id );

	if ( ! $post_id || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) {
		return false;
	}

	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
		return false;
	}

	if ( 'page' !== get_post_type( $post_id ) ) {
		return false;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return false;
	}

	if ( ! isset( $_POST['sln_seo_services_master_nonce'] ) ) {
		return false;
	}

	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sln_seo_services_master_nonce'] ) ), 'sln_seo_services_save_meta' ) ) {
		return false;
	}

	$template = isset( $_POST['page_template'] )
		? sanitize_text_field( wp_unslash( $_POST['page_template'] ) )
		: get_page_template_slug( $post_id );

	return SLN_SEO_SVC_TEMPLATE === $template;
}

/**
 * Save all SEO Services meta.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 */
function sln_seo_services_save_meta( $post_id, $post ) {
	unset( $post );

	if ( ! sln_seo_services_should_save_meta( $post_id ) ) {
		return;
	}

	sln_seo_services_save_hero_meta( $post_id );
	sln_seo_services_save_reality_meta( $post_id );
	sln_seo_services_save_program_meta( $post_id );
	sln_seo_services_save_results_meta( $post_id );
	sln_seo_services_save_process_meta( $post_id );
	sln_seo_services_save_case_studies_meta( $post_id );
	sln_seo_services_save_pricing_meta( $post_id );
	sln_seo_services_save_testimonials_meta( $post_id );
	sln_seo_services_save_cta_form_meta( $post_id );
	sln_seo_services_save_faq_meta( $post_id );
}

/**
 * Persist associative section when posted.
 *
 * @param int      $post_id  Post ID.
 * @param string   $meta_key Meta key.
 * @param string   $post_key POST key.
 * @param callable $sanitize Sanitizer callback.
 */
function sln_seo_services_save_section_meta( $post_id, $meta_key, $post_key, $sanitize ) {
	if ( ! isset( $_POST[ $post_key ] ) || ! is_array( $_POST[ $post_key ] ) ) {
		return;
	}

	$raw      = wp_unslash( $_POST[ $post_key ] );
	$defaults = call_user_func( $sanitize, array() );
	$clean    = call_user_func( $sanitize, $raw );

	update_post_meta( $post_id, $meta_key, array_merge( $defaults, $clean ) );
}

/**
 * Persist repeater rows.
 *
 * @param int      $post_id      Post ID.
 * @param string   $meta_key     Meta key.
 * @param string   $post_key     POST key.
 * @param callable $sanitize_row Row sanitizer.
 */
function sln_seo_services_save_repeater_meta( $post_id, $meta_key, $post_key, $sanitize_row ) {
	if ( ! isset( $_POST[ $post_key ] ) || ! is_array( $_POST[ $post_key ] ) ) {
		return;
	}

	$items = array();

	foreach ( wp_unslash( $_POST[ $post_key ] ) as $raw_row ) {
		if ( ! is_array( $raw_row ) ) {
			continue;
		}

		$row = call_user_func( $sanitize_row, $raw_row );

		if ( ! empty( $row ) ) {
			$items[] = $row;
		}
	}

	update_post_meta( $post_id, $meta_key, $items );
}

/**
 * Sanitize hero section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, mixed>
 */
function sln_seo_services_sanitize_hero( $raw ) {
	return array(
		'small_heading'         => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'          => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'highlighted_text'      => sanitize_text_field( $raw['highlighted_text'] ?? '' ),
		'description'           => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'primary_button_text'   => sanitize_text_field( $raw['primary_button_text'] ?? '' ),
		'primary_button_url'    => sln_seo_services_sanitize_url( $raw['primary_button_url'] ?? '' ),
		'secondary_button_text' => sanitize_text_field( $raw['secondary_button_text'] ?? '' ),
		'secondary_button_url'  => sln_seo_services_sanitize_url( $raw['secondary_button_url'] ?? '' ),
		'hero_image_id'         => sln_sanitize_media_attachment_id( $raw['hero_image_id'] ?? 0 ),
		'trust_badge_text'      => sanitize_text_field( $raw['trust_badge_text'] ?? '' ),
		'certified_team_text'   => sanitize_text_field( $raw['certified_team_text'] ?? '' ),
		'hero_stat_value'       => sanitize_text_field( $raw['hero_stat_value'] ?? '' ),
		'hero_stat_label'       => sanitize_text_field( $raw['hero_stat_label'] ?? '' ),
	);
}

/**
 * Save hero meta.
 *
 * @param int $post_id Post ID.
 */
function sln_seo_services_save_hero_meta( $post_id ) {
	sln_seo_services_save_section_meta( $post_id, SLN_SEO_SVC_HERO_META, 'sln_seo_svc_hero', 'sln_seo_services_sanitize_hero' );
}

/**
 * Sanitize reality section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, string>
 */
function sln_seo_services_sanitize_reality_section( $raw ) {
	return array(
		'small_heading'   => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'    => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'description'     => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'cta_text'        => sln_growth_page_sanitize_wysiwyg_content( $raw['cta_text'] ?? '' ),
		'cta_button_text' => sanitize_text_field( $raw['cta_button_text'] ?? '' ),
		'cta_button_url'  => sln_seo_services_sanitize_url( $raw['cta_button_url'] ?? '' ),
	);
}

/**
 * Sanitize reality card row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_seo_services_sanitize_reality_card( $raw ) {
	return array(
		'icon_id'     => sln_sanitize_media_attachment_id( $raw['icon_id'] ?? 0 ),
		'icon_slug'   => sanitize_key( $raw['icon_slug'] ?? '' ),
		'title'       => sanitize_text_field( $raw['title'] ?? '' ),
		'description' => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'url'         => sln_seo_services_sanitize_url( $raw['url'] ?? '' ),
		'active'      => ! empty( $raw['active'] ),
	);
}

/**
 * Save reality meta.
 *
 * @param int $post_id Post ID.
 */
function sln_seo_services_save_reality_meta( $post_id ) {
	sln_seo_services_save_section_meta( $post_id, SLN_SEO_SVC_REALITY_SECTION_META, 'sln_seo_svc_reality_section', 'sln_seo_services_sanitize_reality_section' );
	sln_seo_services_save_repeater_meta( $post_id, SLN_SEO_SVC_REALITY_CARDS_META, 'sln_seo_svc_reality_cards', 'sln_seo_services_sanitize_reality_card' );
}

/**
 * Sanitize program section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, string>
 */
function sln_seo_services_sanitize_program_section( $raw ) {
	return array(
		'small_heading' => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'  => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'description'   => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
	);
}

/**
 * Sanitize string list.
 *
 * @param mixed $raw Raw list.
 * @return array<int, string>
 */
function sln_seo_services_sanitize_string_list( $raw ) {
	if ( ! is_array( $raw ) ) {
		return array();
	}

	$list = array();

	foreach ( $raw as $item ) {
		$item = sanitize_text_field( $item );

		if ( '' !== $item ) {
			$list[] = $item;
		}
	}

	return $list;
}

/**
 * Sanitize program card row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_seo_services_sanitize_program_card( $raw ) {
	return array(
		'icon_id'     => sln_sanitize_media_attachment_id( $raw['icon_id'] ?? 0 ),
		'title'       => sanitize_text_field( $raw['title'] ?? '' ),
		'description' => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'bullets'     => sln_seo_services_sanitize_string_list( $raw['bullets'] ?? array() ),
		'link_text'   => sanitize_text_field( $raw['link_text'] ?? '' ),
		'link_url'    => sln_seo_services_sanitize_url( $raw['link_url'] ?? '' ),
		'active'      => ! empty( $raw['active'] ),
	);
}

/**
 * Save program meta.
 *
 * @param int $post_id Post ID.
 */
function sln_seo_services_save_program_meta( $post_id ) {
	sln_seo_services_save_section_meta( $post_id, SLN_SEO_SVC_PROGRAM_SECTION_META, 'sln_seo_svc_program_section', 'sln_seo_services_sanitize_program_section' );
	sln_seo_services_save_repeater_meta( $post_id, SLN_SEO_SVC_PROGRAM_CARDS_META, 'sln_seo_svc_program_cards', 'sln_seo_services_sanitize_program_card' );
}

/**
 * Sanitize results section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, string>
 */
function sln_seo_services_sanitize_results_section( $raw ) {
	return array(
		'small_heading'    => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'     => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'highlighted_word' => sanitize_text_field( $raw['highlighted_word'] ?? '' ),
		'description'      => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
	);
}

/**
 * Sanitize results block row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_seo_services_sanitize_results_block( $raw ) {
	return array(
		'number'      => sanitize_text_field( $raw['number'] ?? '' ),
		'label'       => sanitize_text_field( $raw['label'] ?? '' ),
		'description' => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'icon_id'     => sln_sanitize_media_attachment_id( $raw['icon_id'] ?? 0 ),
		'url'         => sln_seo_services_sanitize_url( $raw['url'] ?? '' ),
		'active'      => ! empty( $raw['active'] ),
	);
}

/**
 * Save results meta.
 *
 * @param int $post_id Post ID.
 */
function sln_seo_services_save_results_meta( $post_id ) {
	sln_seo_services_save_section_meta( $post_id, SLN_SEO_SVC_RESULTS_SECTION_META, 'sln_seo_svc_results_section', 'sln_seo_services_sanitize_results_section' );
	sln_seo_services_save_repeater_meta( $post_id, SLN_SEO_SVC_RESULTS_BLOCKS_META, 'sln_seo_svc_results_blocks', 'sln_seo_services_sanitize_results_block' );
}

/**
 * Sanitize process section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, string>
 */
function sln_seo_services_sanitize_process_section( $raw ) {
	return array(
		'small_heading' => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'  => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'description'   => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
	);
}

/**
 * Sanitize process step row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_seo_services_sanitize_process_step( $raw ) {
	return array(
		'step_number' => sanitize_text_field( $raw['step_number'] ?? '' ),
		'icon_id'     => sln_sanitize_media_attachment_id( $raw['icon_id'] ?? 0 ),
		'title'       => sanitize_text_field( $raw['title'] ?? '' ),
		'description' => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'url'         => sln_seo_services_sanitize_url( $raw['url'] ?? '' ),
	);
}

/**
 * Save process meta.
 *
 * @param int $post_id Post ID.
 */
function sln_seo_services_save_process_meta( $post_id ) {
	sln_seo_services_save_section_meta( $post_id, SLN_SEO_SVC_PROCESS_SECTION_META, 'sln_seo_svc_process_section', 'sln_seo_services_sanitize_process_section' );
	sln_seo_services_save_repeater_meta( $post_id, SLN_SEO_SVC_PROCESS_STEPS_META, 'sln_seo_svc_process_steps', 'sln_seo_services_sanitize_process_step' );
}

/**
 * Sanitize case studies section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, string>
 */
function sln_seo_services_sanitize_case_studies_section( $raw ) {
	return array(
		'small_heading'          => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'           => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'highlighted_word'       => sanitize_text_field( $raw['highlighted_word'] ?? '' ),
		'description'            => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'more_case_studies_text' => sanitize_text_field( $raw['more_case_studies_text'] ?? '' ),
		'more_case_studies_url'  => sln_seo_services_sanitize_url( $raw['more_case_studies_url'] ?? '' ),
	);
}

/**
 * Sanitize case study card row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_seo_services_sanitize_case_studies_card( $raw ) {
	return array(
		'title'              => sanitize_text_field( $raw['title'] ?? '' ),
		'icon_id'            => sln_sanitize_media_attachment_id( $raw['icon_id'] ?? 0 ),
		'icon_fallback'      => sanitize_file_name( $raw['icon_fallback'] ?? '' ),
		'metric'             => sanitize_text_field( $raw['metric'] ?? '' ),
		'metric_description' => sanitize_text_field( $raw['metric_description'] ?? '' ),
		'graph_id'           => sln_sanitize_media_attachment_id( $raw['graph_id'] ?? 0 ),
		'graph_fallback'     => sanitize_file_name( $raw['graph_fallback'] ?? '' ),
		'footer_text'        => sanitize_text_field( $raw['footer_text'] ?? '' ),
		'card_url'           => sln_seo_services_sanitize_url( $raw['card_url'] ?? '' ),
		'card_color'         => sanitize_hex_color( $raw['card_color'] ?? '#1f4e9e' ) ?: '#1f4e9e',
		'active'             => ! empty( $raw['active'] ),
	);
}

/**
 * Save case studies meta.
 *
 * @param int $post_id Post ID.
 */
function sln_seo_services_save_case_studies_meta( $post_id ) {
	sln_seo_services_save_section_meta( $post_id, SLN_SEO_SVC_CASE_STUDIES_SECTION_META, 'sln_seo_svc_case_studies_section', 'sln_seo_services_sanitize_case_studies_section' );
	sln_seo_services_save_repeater_meta( $post_id, SLN_SEO_SVC_CASE_STUDIES_CARDS_META, 'sln_seo_svc_case_studies_cards', 'sln_seo_services_sanitize_case_studies_card' );
}

/**
 * Sanitize pricing section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, string>
 */
function sln_seo_services_sanitize_pricing_section( $raw ) {
	return array(
		'small_heading'    => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'     => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'highlighted_word' => sanitize_text_field( $raw['highlighted_word'] ?? '' ),
		'description'      => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
	);
}

/**
 * Sanitize pricing plan row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_seo_services_sanitize_pricing_plan( $raw ) {
	return array(
		'plan_name'    => sanitize_text_field( $raw['plan_name'] ?? '' ),
		'price'        => sanitize_text_field( $raw['price'] ?? '' ),
		'price_suffix' => sanitize_text_field( $raw['price_suffix'] ?? '' ),
		'description'  => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'features'     => sln_seo_services_sanitize_string_list( $raw['features'] ?? array() ),
		'button_text'  => sanitize_text_field( $raw['button_text'] ?? '' ),
		'button_url'   => sln_seo_services_sanitize_url( $raw['button_url'] ?? '' ),
		'is_popular'   => ! empty( $raw['is_popular'] ),
		'active'       => ! empty( $raw['active'] ),
	);
}

/**
 * Save pricing meta.
 *
 * @param int $post_id Post ID.
 */
function sln_seo_services_save_pricing_meta( $post_id ) {
	sln_seo_services_save_section_meta( $post_id, SLN_SEO_SVC_PRICING_SECTION_META, 'sln_seo_svc_pricing_section', 'sln_seo_services_sanitize_pricing_section' );
	sln_seo_services_save_repeater_meta( $post_id, SLN_SEO_SVC_PRICING_PLANS_META, 'sln_seo_svc_pricing_plans', 'sln_seo_services_sanitize_pricing_plan' );
}

/**
 * Sanitize testimonials section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, string>
 */
function sln_seo_services_sanitize_testimonials_section( $raw ) {
	return array(
		'small_heading'    => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'     => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'highlighted_word' => sanitize_text_field( $raw['highlighted_word'] ?? '' ),
		'description'      => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
	);
}

/**
 * Sanitize testimonials summary.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, string>
 */
function sln_seo_services_sanitize_testimonials_summary( $raw ) {
	return array(
		'review_count'      => sanitize_text_field( $raw['review_count'] ?? '' ),
		'average_rating'    => sanitize_text_field( $raw['average_rating'] ?? '' ),
		'websites_built'    => sanitize_text_field( $raw['websites_built'] ?? '' ),
		'revenue_generated' => sanitize_text_field( $raw['revenue_generated'] ?? '' ),
		'review_title'      => sanitize_text_field( $raw['review_title'] ?? '' ),
		'star_rating'       => sanitize_text_field( $raw['star_rating'] ?? '' ),
		'verified_text'     => sanitize_text_field( $raw['verified_text'] ?? '' ),
	);
}

/**
 * Sanitize testimonial review row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_seo_services_sanitize_testimonial_review( $raw ) {
	return array(
		'rating'            => max( 1, min( 5, absint( $raw['rating'] ?? 5 ) ) ),
		'testimonial'       => sln_growth_page_sanitize_wysiwyg_content( $raw['testimonial'] ?? '' ),
		'client_name'       => sanitize_text_field( $raw['client_name'] ?? '' ),
		'client_position'   => sanitize_text_field( $raw['client_position'] ?? '' ),
		'client_initials'   => sanitize_text_field( $raw['client_initials'] ?? '' ),
		'client_image_id'   => sln_sanitize_media_attachment_id( $raw['client_image_id'] ?? 0 ),
		'active'            => ! empty( $raw['active'] ),
	);
}

/**
 * Save testimonials meta.
 *
 * @param int $post_id Post ID.
 */
function sln_seo_services_save_testimonials_meta( $post_id ) {
	sln_seo_services_save_section_meta( $post_id, SLN_SEO_SVC_TESTIMONIALS_SECTION_META, 'sln_seo_svc_testimonials_section', 'sln_seo_services_sanitize_testimonials_section' );
	sln_seo_services_save_section_meta( $post_id, SLN_SEO_SVC_TESTIMONIALS_SUMMARY_META, 'sln_seo_svc_testimonials_summary', 'sln_seo_services_sanitize_testimonials_summary' );
	sln_seo_services_save_repeater_meta( $post_id, SLN_SEO_SVC_TESTIMONIALS_REVIEWS_META, 'sln_seo_svc_testimonials_reviews', 'sln_seo_services_sanitize_testimonial_review' );
}

/**
 * Sanitize CTA form section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, string>
 */
function sln_seo_services_sanitize_cta_form( $raw ) {
	return array(
		'small_heading'       => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'        => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'description'         => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'form_heading'        => sanitize_text_field( $raw['form_heading'] ?? '' ),
		'name_placeholder'    => sanitize_text_field( $raw['name_placeholder'] ?? '' ),
		'email_placeholder'   => sanitize_text_field( $raw['email_placeholder'] ?? '' ),
		'phone_placeholder'   => sanitize_text_field( $raw['phone_placeholder'] ?? '' ),
		'website_placeholder' => sanitize_text_field( $raw['website_placeholder'] ?? '' ),
		'button_text'         => sanitize_text_field( $raw['button_text'] ?? '' ),
		'thank_you_page_url'  => sln_seo_services_sanitize_url( $raw['thank_you_page_url'] ?? '' ),
	);
}

/**
 * Save CTA form meta.
 *
 * @param int $post_id Post ID.
 */
function sln_seo_services_save_cta_form_meta( $post_id ) {
	sln_seo_services_save_section_meta( $post_id, SLN_SEO_SVC_CTA_FORM_META, 'sln_seo_svc_cta_form', 'sln_seo_services_sanitize_cta_form' );
}

/**
 * Sanitize FAQ section.
 *
 * @param array<string, mixed> $raw Raw POST data.
 * @return array<string, string>
 */
function sln_seo_services_sanitize_faq_section( $raw ) {
	return array(
		'small_heading'   => sanitize_text_field( $raw['small_heading'] ?? '' ),
		'main_heading'    => sanitize_text_field( $raw['main_heading'] ?? '' ),
		'description'     => sln_growth_page_sanitize_wysiwyg_content( $raw['description'] ?? '' ),
		'cta_button_text' => sanitize_text_field( $raw['cta_button_text'] ?? '' ),
		'cta_button_url'  => sln_seo_services_sanitize_url( $raw['cta_button_url'] ?? '' ),
	);
}

/**
 * Sanitize FAQ item row.
 *
 * @param array<string, mixed> $raw Raw row.
 * @return array<string, mixed>
 */
function sln_seo_services_sanitize_faq_item( $raw ) {
	return array(
		'question' => sanitize_text_field( $raw['question'] ?? '' ),
		'answer'   => sln_growth_page_sanitize_wysiwyg_content( $raw['answer'] ?? '' ),
		'active'   => ! empty( $raw['active'] ),
	);
}

/**
 * Save FAQ meta.
 *
 * @param int $post_id Post ID.
 */
function sln_seo_services_save_faq_meta( $post_id ) {
	sln_seo_services_save_section_meta( $post_id, SLN_SEO_SVC_FAQ_SECTION_META, 'sln_seo_svc_faq_section', 'sln_seo_services_sanitize_faq_section' );
	sln_seo_services_save_repeater_meta( $post_id, SLN_SEO_SVC_FAQ_ITEMS_META, 'sln_seo_svc_faq_items', 'sln_seo_services_sanitize_faq_item' );
}
