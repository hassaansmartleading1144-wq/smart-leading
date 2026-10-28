<?php
/**
 * Native meta boxes for blog posts (no ACF).
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register post meta boxes.
 */
function sln_blog_add_meta_boxes() {
	add_meta_box(
		'sln_blog_extras',
		__( 'Blog post extras', 'smart-leading-net' ),
		'sln_blog_render_meta_box',
		'post',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'sln_blog_add_meta_boxes' );

/**
 * Admin assets for the post editor.
 *
 * @param string $hook Hook.
 */
function sln_blog_admin_assets( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}

	$screen = get_current_screen();

	if ( ! $screen || 'post' !== $screen->post_type ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_script(
		'sln-blog-post-admin',
		SLN_THEME_URI . '/assets/js/blog-post-admin.js',
		array( 'jquery' ),
		SLN_THEME_VERSION,
		true
	);
	wp_enqueue_style(
		'sln-blog-post-editor',
		SLN_THEME_URI . '/assets/css/single-post-editor.css',
		array(),
		SLN_THEME_VERSION
	);
}
add_action( 'admin_enqueue_scripts', 'sln_blog_admin_assets' );

/**
 * Render extras meta box.
 *
 * @param WP_Post $post Post.
 */
function sln_blog_render_meta_box( $post ) {
	wp_nonce_field( 'sln_blog_save_meta', 'sln_blog_meta_nonce' );

	$reviewer_name  = sln_blog_get_meta( $post->ID, 'reviewer_name', '' );
	$reviewer_role  = sln_blog_get_meta( $post->ID, 'reviewer_role', '' );
	$reviewer_image = (int) sln_blog_get_meta( $post->ID, 'reviewer_image', 0 );
	$takeaways      = sln_blog_get_meta( $post->ID, 'takeaways', array() );
	$qa_left_title  = sln_blog_get_meta( $post->ID, 'qa_left_title', '' );
	$qa_left_items  = sln_blog_get_meta( $post->ID, 'qa_left_items', '' );
	$qa_right_title = sln_blog_get_meta( $post->ID, 'qa_right_title', '' );
	$qa_right_items = sln_blog_get_meta( $post->ID, 'qa_right_items', '' );
	$cta            = array(
		'heading'         => sln_blog_get_meta( $post->ID, 'cta_heading', '' ),
		'description'     => sln_blog_get_meta( $post->ID, 'cta_description', '' ),
		'label'           => sln_blog_get_meta( $post->ID, 'cta_label', '' ),
		'url'             => sln_blog_get_meta( $post->ID, 'cta_url', '' ),
		'secondary_label' => sln_blog_get_meta( $post->ID, 'cta_secondary_label', '' ),
		'secondary_url'   => sln_blog_get_meta( $post->ID, 'cta_secondary_url', '' ),
	);
	$faqs             = sln_blog_get_meta( $post->ID, 'faq', array() );
	$author_bio       = sln_blog_get_meta( $post->ID, 'author_bio', '' );
	$toc_include_h3   = (string) sln_blog_get_meta( $post->ID, 'toc_include_h3', '' );

	if ( ! is_array( $takeaways ) ) {
		$takeaways = array();
	}
	if ( ! is_array( $faqs ) ) {
		$faqs = array();
	}

	$thumb_url = $reviewer_image ? wp_get_attachment_image_url( $reviewer_image, 'thumbnail' ) : '';
	?>
	<p class="description"><?php esc_html_e( 'Write the article in the block editor. Use this box for takeaways, optional quick answer, reviewer, CTA, FAQs, and author bio override. Title, excerpt, featured image, categories, tags, and author stay in the standard WordPress fields. Insert designed sections from the Blog components patterns.', 'smart-leading-net' ); ?></p>

	<h3><?php esc_html_e( 'Table of contents', 'smart-leading-net' ); ?></h3>
	<p>
		<label>
			<input type="checkbox" name="sln_blog_toc_include_h3" value="1" <?php checked( '1', $toc_include_h3 ); ?> />
			<?php esc_html_e( 'Include H3 headings in the sidebar table of contents (H2 is always included).', 'smart-leading-net' ); ?>
		</label>
	</p>

	<h3><?php esc_html_e( 'Technical reviewer', 'smart-leading-net' ); ?></h3>
	<p>
		<label><?php esc_html_e( 'Name', 'smart-leading-net' ); ?><br />
			<input type="text" class="widefat" name="sln_blog_reviewer_name" value="<?php echo esc_attr( $reviewer_name ); ?>" />
		</label>
	</p>
	<p>
		<label><?php esc_html_e( 'Role', 'smart-leading-net' ); ?><br />
			<input type="text" class="widefat" name="sln_blog_reviewer_role" value="<?php echo esc_attr( $reviewer_role ); ?>" />
		</label>
	</p>
	<p>
		<label><?php esc_html_e( 'Image', 'smart-leading-net' ); ?></label><br />
		<input type="hidden" id="sln_blog_reviewer_image" name="sln_blog_reviewer_image" value="<?php echo esc_attr( (string) $reviewer_image ); ?>" />
		<button type="button" class="button" id="sln_blog_reviewer_image_btn"><?php esc_html_e( 'Select image', 'smart-leading-net' ); ?></button>
		<button type="button" class="button" id="sln_blog_reviewer_image_clear"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
		<span id="sln_blog_reviewer_image_preview" style="display:inline-block;margin-left:8px;vertical-align:middle;">
			<?php if ( $thumb_url ) : ?>
				<img src="<?php echo esc_url( $thumb_url ); ?>" alt="" style="width:48px;height:48px;object-fit:cover;border-radius:50%;" />
			<?php endif; ?>
		</span>
	</p>

	<h3><?php esc_html_e( 'Key takeaways', 'smart-leading-net' ); ?></h3>
	<p class="description"><?php esc_html_e( 'Repeatable. HTML like <strong> is allowed. Leave empty to hide the box.', 'smart-leading-net' ); ?></p>
	<div id="sln_blog_takeaways" data-sln-repeater>
		<?php
		if ( empty( $takeaways ) ) {
			$takeaways = array( '' );
		}
		foreach ( $takeaways as $i => $item ) :
			?>
			<p class="sln-repeater-row">
				<input type="text" class="widefat" name="sln_blog_takeaways[]" value="<?php echo esc_attr( is_string( $item ) ? $item : '' ); ?>" />
				<button type="button" class="button sln-repeater-remove"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
			</p>
		<?php endforeach; ?>
	</div>
	<p><button type="button" class="button" data-sln-add="#sln_blog_takeaways" data-sln-tpl="takeaway"><?php esc_html_e( 'Add takeaway', 'smart-leading-net' ); ?></button></p>

	<h3><?php esc_html_e( 'Quick answer (optional)', 'smart-leading-net' ); ?></h3>
	<p class="description"><?php esc_html_e( 'Two columns. One item per line. Leave titles empty to hide.', 'smart-leading-net' ); ?></p>
	<p>
		<label><?php esc_html_e( 'Left column title', 'smart-leading-net' ); ?><br />
			<input type="text" class="widefat" name="sln_blog_qa_left_title" value="<?php echo esc_attr( $qa_left_title ); ?>" />
		</label>
	</p>
	<p>
		<label><?php esc_html_e( 'Left column items', 'smart-leading-net' ); ?><br />
			<textarea class="widefat" rows="5" name="sln_blog_qa_left_items"><?php echo esc_textarea( is_string( $qa_left_items ) ? $qa_left_items : '' ); ?></textarea>
		</label>
	</p>
	<p>
		<label><?php esc_html_e( 'Right column title', 'smart-leading-net' ); ?><br />
			<input type="text" class="widefat" name="sln_blog_qa_right_title" value="<?php echo esc_attr( $qa_right_title ); ?>" />
		</label>
	</p>
	<p>
		<label><?php esc_html_e( 'Right column items', 'smart-leading-net' ); ?><br />
			<textarea class="widefat" rows="5" name="sln_blog_qa_right_items"><?php echo esc_textarea( is_string( $qa_right_items ) ? $qa_right_items : '' ); ?></textarea>
		</label>
	</p>

	<h3><?php esc_html_e( 'Inline / sidebar CTA', 'smart-leading-net' ); ?></h3>
	<p>
		<label><?php esc_html_e( 'Heading', 'smart-leading-net' ); ?><br />
			<input type="text" class="widefat" name="sln_blog_cta_heading" value="<?php echo esc_attr( $cta['heading'] ); ?>" />
		</label>
	</p>
	<p>
		<label><?php esc_html_e( 'Description', 'smart-leading-net' ); ?><br />
			<textarea class="widefat" rows="3" name="sln_blog_cta_description"><?php echo esc_textarea( $cta['description'] ); ?></textarea>
		</label>
	</p>
	<p>
		<label><?php esc_html_e( 'Button label', 'smart-leading-net' ); ?><br />
			<input type="text" class="widefat" name="sln_blog_cta_label" value="<?php echo esc_attr( $cta['label'] ); ?>" />
		</label>
	</p>
	<p>
		<label><?php esc_html_e( 'Button URL', 'smart-leading-net' ); ?><br />
			<input type="url" class="widefat" name="sln_blog_cta_url" value="<?php echo esc_attr( $cta['url'] ); ?>" />
		</label>
	</p>
	<p>
		<label><?php esc_html_e( 'Secondary button label (optional)', 'smart-leading-net' ); ?><br />
			<input type="text" class="widefat" name="sln_blog_cta_secondary_label" value="<?php echo esc_attr( $cta['secondary_label'] ); ?>" />
		</label>
	</p>
	<p>
		<label><?php esc_html_e( 'Secondary button URL', 'smart-leading-net' ); ?><br />
			<input type="url" class="widefat" name="sln_blog_cta_secondary_url" value="<?php echo esc_attr( $cta['secondary_url'] ); ?>" />
		</label>
	</p>

	<h3><?php esc_html_e( 'FAQs', 'smart-leading-net' ); ?></h3>
	<div id="sln_blog_faqs" data-sln-repeater>
		<?php
		if ( empty( $faqs ) ) {
			$faqs = array( array( 'q' => '', 'a' => '' ) );
		}
		foreach ( $faqs as $faq ) :
			$q = isset( $faq['q'] ) ? $faq['q'] : '';
			$a = isset( $faq['a'] ) ? $faq['a'] : '';
			?>
			<div class="sln-repeater-row" style="margin-bottom:12px;padding:12px;border:1px solid #dcdcde;background:#fff;">
				<p>
					<label><?php esc_html_e( 'Question', 'smart-leading-net' ); ?><br />
						<input type="text" class="widefat" name="sln_blog_faq_q[]" value="<?php echo esc_attr( $q ); ?>" />
					</label>
				</p>
				<p>
					<label><?php esc_html_e( 'Answer', 'smart-leading-net' ); ?><br />
						<textarea class="widefat" rows="3" name="sln_blog_faq_a[]"><?php echo esc_textarea( $a ); ?></textarea>
					</label>
				</p>
				<button type="button" class="button sln-repeater-remove"><?php esc_html_e( 'Remove FAQ', 'smart-leading-net' ); ?></button>
			</div>
		<?php endforeach; ?>
	</div>
	<p><button type="button" class="button" data-sln-add="#sln_blog_faqs" data-sln-tpl="faq"><?php esc_html_e( 'Add FAQ', 'smart-leading-net' ); ?></button></p>

	<h3><?php esc_html_e( 'Author bio override (optional)', 'smart-leading-net' ); ?></h3>
	<p>
		<textarea class="widefat" rows="4" name="sln_blog_author_bio"><?php echo esc_textarea( $author_bio ); ?></textarea>
	</p>
	<?php
}

/**
 * Save post extras.
 *
 * @param int $post_id Post ID.
 */
function sln_blog_save_meta( $post_id ) {
	if ( ! isset( $_POST['sln_blog_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sln_blog_meta_nonce'] ) ), 'sln_blog_save_meta' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( 'post' !== get_post_type( $post_id ) ) {
		return;
	}

	$keys = sln_blog_meta_keys();

	$text_map = array(
		'reviewer_name'       => 'sln_blog_reviewer_name',
		'reviewer_role'       => 'sln_blog_reviewer_role',
		'qa_left_title'       => 'sln_blog_qa_left_title',
		'qa_right_title'      => 'sln_blog_qa_right_title',
		'cta_heading'         => 'sln_blog_cta_heading',
		'cta_label'           => 'sln_blog_cta_label',
		'cta_secondary_label' => 'sln_blog_cta_secondary_label',
		'featured_caption'    => 'sln_blog_featured_caption',
	);

	foreach ( $text_map as $logical => $field ) {
		$value = isset( $_POST[ $field ] ) ? sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) : '';
		update_post_meta( $post_id, $keys[ $logical ], $value );
	}

	$area_map = array(
		'qa_left_items'  => 'sln_blog_qa_left_items',
		'qa_right_items' => 'sln_blog_qa_right_items',
		'cta_description'=> 'sln_blog_cta_description',
		'author_bio'     => 'sln_blog_author_bio',
	);

	foreach ( $area_map as $logical => $field ) {
		$value = isset( $_POST[ $field ] ) ? sanitize_textarea_field( wp_unslash( $_POST[ $field ] ) ) : '';
		update_post_meta( $post_id, $keys[ $logical ], $value );
	}

	$url_map = array(
		'cta_url'           => 'sln_blog_cta_url',
		'cta_secondary_url' => 'sln_blog_cta_secondary_url',
	);

	foreach ( $url_map as $logical => $field ) {
		$value = isset( $_POST[ $field ] ) ? esc_url_raw( wp_unslash( $_POST[ $field ] ) ) : '';
		update_post_meta( $post_id, $keys[ $logical ], $value );
	}

	$image = isset( $_POST['sln_blog_reviewer_image'] ) ? absint( $_POST['sln_blog_reviewer_image'] ) : 0;
	update_post_meta( $post_id, $keys['reviewer_image'], $image );

	$takeaways = array();
	if ( isset( $_POST['sln_blog_takeaways'] ) && is_array( $_POST['sln_blog_takeaways'] ) ) {
		foreach ( wp_unslash( $_POST['sln_blog_takeaways'] ) as $line ) {
			$line = wp_kses_post( $line );
			$line = trim( $line );
			if ( '' !== $line ) {
				$takeaways[] = $line;
			}
		}
	}
	update_post_meta( $post_id, $keys['takeaways'], $takeaways );

	$faqs = array();
	$qs   = isset( $_POST['sln_blog_faq_q'] ) && is_array( $_POST['sln_blog_faq_q'] ) ? wp_unslash( $_POST['sln_blog_faq_q'] ) : array();
	$as   = isset( $_POST['sln_blog_faq_a'] ) && is_array( $_POST['sln_blog_faq_a'] ) ? wp_unslash( $_POST['sln_blog_faq_a'] ) : array();
	$count = max( count( $qs ), count( $as ) );

	for ( $i = 0; $i < $count; $i++ ) {
		$q = isset( $qs[ $i ] ) ? sanitize_text_field( $qs[ $i ] ) : '';
		$a = isset( $as[ $i ] ) ? wp_kses_post( $as[ $i ] ) : '';
		if ( '' === $q && '' === trim( wp_strip_all_tags( $a ) ) ) {
			continue;
		}
		$faqs[] = array(
			'q' => $q,
			'a' => $a,
		);
	}
	update_post_meta( $post_id, $keys['faq'], $faqs );

	$toc_h3 = isset( $_POST['sln_blog_toc_include_h3'] ) ? '1' : '';
	update_post_meta( $post_id, $keys['toc_include_h3'], $toc_h3 );
}
add_action( 'save_post_post', 'sln_blog_save_meta' );
