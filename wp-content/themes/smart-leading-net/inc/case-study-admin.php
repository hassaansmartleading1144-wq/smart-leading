<?php
/**
 * Case Studies — admin meta box (structured fields, repeaters, media pickers).
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the meta box.
 */
function sln_case_study_add_meta_box() {
	add_meta_box(
		'sln-case-study-fields',
		__( 'Case Study Content', 'smart-leading-net' ),
		'sln_case_study_render_meta_box',
		SLN_CASE_STUDY_POST_TYPE,
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'sln_case_study_add_meta_box' );

/**
 * Admin assets on case study edit screens.
 *
 * @param string $hook Admin page hook.
 */
function sln_case_study_admin_assets( $hook ) {
	$screen = get_current_screen();
	if ( ! $screen || SLN_CASE_STUDY_POST_TYPE !== $screen->post_type || ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	wp_enqueue_media();
	$dir = get_template_directory();
	$uri = get_template_directory_uri();
	wp_enqueue_style( 'sln-case-study-admin', $uri . '/assets/css/case-study-admin.css', array(), filemtime( $dir . '/assets/css/case-study-admin.css' ) );
	wp_enqueue_script( 'sln-case-study-admin', $uri . '/assets/js/case-study-admin.js', array( 'jquery', 'jquery-ui-sortable' ), filemtime( $dir . '/assets/js/case-study-admin.js' ), true );
}
add_action( 'admin_enqueue_scripts', 'sln_case_study_admin_assets' );

/**
 * Field name helper.
 *
 * @param string $key Field key.
 * @return string
 */
function sln_cs_name( $key ) {
	return 'sln_cs[' . $key . ']';
}

/**
 * Text / url input.
 */
function sln_cs_input( $name, $value, $label, $help = '', $type = 'text', $placeholder = '' ) {
	printf(
		'<label class="sln-csa__field"><span>%1$s</span><input type="%2$s" name="%3$s" value="%4$s" placeholder="%5$s" class="widefat">%6$s</label>',
		esc_html( $label ),
		esc_attr( $type ),
		esc_attr( $name ),
		esc_attr( $value ),
		esc_attr( $placeholder ),
		$help ? '<small>' . esc_html( $help ) . '</small>' : ''
	);
}

/**
 * Textarea.
 */
function sln_cs_textarea( $name, $value, $label, $help = '', $rows = 4, $placeholder = '' ) {
	printf(
		'<label class="sln-csa__field"><span>%1$s</span><textarea name="%2$s" rows="%3$d" class="widefat" placeholder="%4$s">%5$s</textarea>%6$s</label>',
		esc_html( $label ),
		esc_attr( $name ),
		(int) $rows,
		esc_attr( $placeholder ),
		esc_textarea( $value ),
		$help ? '<small>' . esc_html( $help ) . '</small>' : ''
	);
}

/**
 * Media picker.
 */
function sln_cs_media( $name, $id, $label, $help = '' ) {
	$src = $id ? wp_get_attachment_image_url( $id, 'medium' ) : '';
	?>
	<div class="sln-csa__field sln-csa__media">
		<span><?php echo esc_html( $label ); ?></span>
		<div class="sln-csa__media-preview"><?php if ( $src ) : ?><img src="<?php echo esc_url( $src ); ?>" alt=""><?php endif; ?></div>
		<input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $id ? $id : '' ); ?>" class="sln-csa__media-id">
		<button type="button" class="button sln-csa__media-select"><?php esc_html_e( 'Select image', 'smart-leading-net' ); ?></button>
		<button type="button" class="button-link-delete sln-csa__media-remove"><?php esc_html_e( 'Remove', 'smart-leading-net' ); ?></button>
		<?php if ( $help ) : ?><small><?php echo esc_html( $help ); ?></small><?php endif; ?>
	</div>
	<?php
}

/**
 * Render one repeater row. $i may be "__i__" for the JS template.
 *
 * @param string $key  Repeater key.
 * @param mixed  $i    Row index.
 * @param array  $row  Row values.
 */
function sln_cs_repeater_row( $key, $i, $row ) {
	$n = static function ( $field ) use ( $key, $i ) {
		return 'sln_cs[' . $key . '][' . $i . '][' . $field . ']';
	};
	$v = static function ( $field ) use ( $row ) {
		return isset( $row[ $field ] ) ? $row[ $field ] : '';
	};
	echo '<div class="sln-csa__row"><div class="sln-csa__row-head"><span class="sln-csa__row-handle">&#9776;</span><button type="button" class="button-link-delete sln-csa__row-remove">' . esc_html__( 'Remove', 'smart-leading-net' ) . '</button></div><div class="sln-csa__grid">';
	switch ( $key ) {
		case 'results':
			sln_cs_input( $n( 'label' ), $v( 'label' ), 'Metric', '', 'text', 'e.g. Monthly quote requests' );
			sln_cs_input( $n( 'change' ), $v( 'change' ), 'Headline change (optional)', 'Big number shown on the card. Leave blank to auto-calculate % from before/after.', 'text', 'e.g. +105%' );
			sln_cs_input( $n( 'before' ), $v( 'before' ), 'Before', 'Leave blank to show After as a single total.', 'text', 'e.g. 20' );
			sln_cs_input( $n( 'after' ), $v( 'after' ), 'After', '', 'text', 'e.g. 41' );
			sln_cs_input( $n( 'period' ), $v( 'period' ), 'Comparison period', '', 'text', 'e.g. Jan–Mar 2025 vs Jan–Mar 2026' );
			sln_cs_input( $n( 'source' ), $v( 'source' ), 'Data source', '', 'text', 'e.g. GoHighLevel, Google Search Console' );
			break;
		case 'goals':
			sln_cs_input( $n( 'text' ), $v( 'text' ), 'Goal', '', 'text', 'e.g. Get more quote requests from Google' );
			break;
		case 'scope':
			sln_cs_input( $n( 'title' ), $v( 'title' ), 'Scope item', '', 'text', 'e.g. Google Business Profile management' );
			sln_cs_textarea( $n( 'text' ), $v( 'text' ), 'Short description', '', 2 );
			break;
		case 'milestones':
			sln_cs_input( $n( 'label' ), $v( 'label' ), 'When', '', 'text', 'e.g. First 3 months' );
			sln_cs_input( $n( 'title' ), $v( 'title' ), 'Milestone headline', '', 'text', 'e.g. Calls doubled' );
			sln_cs_textarea( $n( 'text' ), $v( 'text' ), 'What happened (with numbers, dates and source)', '', 3 );
			sln_cs_media( $n( 'image' ), (int) $v( 'image' ), 'Image or logo (optional)' );
			break;
		case 'takeaways':
			sln_cs_input( $n( 'title' ), $v( 'title' ), 'Takeaway', '', 'text', 'e.g. Track every call, not just form fills' );
			sln_cs_textarea( $n( 'text' ), $v( 'text' ), 'Why it matters (tie it to this project)', '', 2 );
			break;
		case 'approach':
			sln_cs_input( $n( 'title' ), $v( 'title' ), 'Step title', '', 'text', 'e.g. Rebuilt Google Business Profile' );
			sln_cs_textarea( $n( 'text' ), $v( 'text' ), 'What we did and why', '', 3 );
			break;
		case 'evidence':
			sln_cs_input( $n( 'title' ), $v( 'title' ), 'Evidence title', '', 'text', 'e.g. Search Console clicks, 6 months' );
			sln_cs_textarea( $n( 'text' ), $v( 'text' ), 'Caption (dates + source + what it shows)', '', 2 );
			sln_cs_media( $n( 'before' ), (int) $v( 'before' ), 'Before image (optional)' );
			sln_cs_media( $n( 'after' ), (int) $v( 'after' ), 'After / main image' );
			break;
	}
	echo '</div></div>';
}

/**
 * Repeater block.
 */
function sln_cs_repeater( $key, $rows, $add_label ) {
	if ( 'goals' === $key ) {
		$rows = array_map(
			static function ( $g ) {
				return array( 'text' => $g );
			},
			(array) $rows
		);
	}
	echo '<div class="sln-csa__repeater" data-key="' . esc_attr( $key ) . '"><div class="sln-csa__rows">';
	foreach ( (array) $rows as $i => $row ) {
		sln_cs_repeater_row( $key, $i, $row );
	}
	echo '</div><script type="text/html" class="sln-csa__tpl">';
	ob_start();
	sln_cs_repeater_row( $key, '__i__', array() );
	echo str_replace( '</script', '<\/script', ob_get_clean() ); // phpcs:ignore WordPress.Security.EscapeOutput
	echo '</script><button type="button" class="button sln-csa__add">+ ' . esc_html( $add_label ) . '</button></div>';
}

/**
 * Render the meta box.
 *
 * @param WP_Post $post Post.
 */
function sln_case_study_render_meta_box( $post ) {
	$d = sln_get_case_study_data( $post->ID );
	wp_nonce_field( 'sln_case_study_save', 'sln_case_study_nonce' );
	?>
	<div class="sln-csa">
		<p class="sln-csa__intro">Fill in what you have. <strong>Any section left empty is hidden on the page.</strong> Only use real, dated numbers with a source. Set the <em>client logo</em> in the right sidebar and a one-line card summary in <em>Excerpt</em>.</p>

		<nav class="sln-csa__tabs" role="tablist">
			<button type="button" class="is-active" data-tab="hero">1. Hero &amp; snapshot</button>
			<button type="button" data-tab="results">2. Key results</button>
			<button type="button" data-tab="story">3. Client, challenge &amp; goals</button>
			<button type="button" data-tab="approach">4. Scope, work &amp; journey</button>
			<button type="button" data-tab="evidence">5. Evidence</button>
			<button type="button" data-tab="quote">6. Quote &amp; CTA</button>
		</nav>

		<section class="sln-csa__panel is-active" data-panel="hero">
			<?php
			sln_cs_input( sln_cs_name( 'headline' ), $d['headline'], 'Page headline', 'Result-led if you have numbers ("How X doubled quote requests from Google"). Leave blank to use the post title.' );
			sln_cs_textarea( sln_cs_name( 'summary' ), $d['summary'], 'Short summary', '1–2 sentences under the headline.', 3 );
			sln_cs_media( sln_cs_name( 'hero_image' ), (int) $d['hero_image'], 'Hero website preview (optional)', 'Full-page website screenshot (same as the Portfolio). It scrolls on hover. If empty, the client logo is shown.' );
			?>
			<div class="sln-csa__grid">
				<?php
				sln_cs_input( sln_cs_name( 'industry' ), $d['industry'], 'Industry', '', 'text', 'e.g. Home Improvement' );
				sln_cs_input( sln_cs_name( 'location' ), $d['location'], 'Location', '', 'text', 'e.g. Austin, Texas, USA' );
				sln_cs_input( sln_cs_name( 'period' ), $d['period'], 'Project period', '', 'text', 'e.g. Jan 2025 – Present' );
				sln_cs_input( sln_cs_name( 'website' ), $d['website'], 'Client website', '', 'url', 'https://' );
				?>
			</div>
			<fieldset class="sln-csa__field">
				<span>Services delivered</span>
				<div class="sln-csa__checks">
					<?php foreach ( sln_case_study_services() as $key => $label ) : ?>
						<label><input type="checkbox" name="sln_cs[services][]" value="<?php echo esc_attr( $key ); ?>" <?php checked( in_array( $key, (array) $d['services'], true ) ); ?>> <?php echo esc_html( $label ); ?></label>
					<?php endforeach; ?>
				</div>
			</fieldset>
		</section>

		<section class="sln-csa__panel" data-panel="results">
			<?php
			sln_cs_input( sln_cs_name( 'results_title' ), $d['results_title'], 'Section title' );
			echo '<p class="description">Add 2–3 headline numbers. Each needs before, after, period and source.</p>';
			sln_cs_repeater( 'results', $d['results'], 'Add result' );
			sln_cs_textarea( sln_cs_name( 'results_note' ), $d['results_note'], 'Results note (optional)', 'Tracking gaps or other changes that affected the numbers.', 2 );
			?>
		</section>

		<section class="sln-csa__panel" data-panel="story">
			<?php
			sln_cs_textarea( sln_cs_name( 'about' ), $d['about'], 'About the client', 'Who they are, who their customers are, where they work. 2–3 sentences.', 4 );
			sln_cs_textarea( sln_cs_name( 'challenge' ), $d['challenge'], 'The challenge', 'What was wrong before SLS started. Blank line = new paragraph.', 5 );
			sln_cs_media( sln_cs_name( 'challenge_img' ), (int) $d['challenge_img'], 'Challenge / "before" image (optional)' );
			echo '<h4>Goals</h4>';
			sln_cs_repeater( 'goals', $d['goals'], 'Add goal' );
			?>
		</section>

		<section class="sln-csa__panel" data-panel="approach">
			<h4>Project scope</h4>
			<p class="description">What the engagement included (services and deliverables).</p>
			<?php sln_cs_repeater( 'scope', $d['scope'], 'Add scope item' ); ?>
			<h4>What we did</h4>
			<p class="description">3–5 steps describing the actual work, each with a one-line reason.</p>
			<?php sln_cs_repeater( 'approach', $d['approach'], 'Add step' ); ?>
			<h4>The journey (milestones)</h4>
			<p class="description">Timeline moments, e.g. "First 3 months", "Month 6", "Today".</p>
			<?php sln_cs_repeater( 'milestones', $d['milestones'], 'Add milestone' ); ?>
			<h4>Key takeaways</h4>
			<p class="description">2–4 lessons another business owner can use, each backed by this project.</p>
			<?php sln_cs_repeater( 'takeaways', $d['takeaways'], 'Add takeaway' ); ?>
		</section>

		<section class="sln-csa__panel" data-panel="evidence">
			<p class="description">Screenshots from Search Console, GA4, GoHighLevel, ad platforms, map grids, or before/after website shots. Add both images to show a side-by-side comparison.</p>
			<?php sln_cs_repeater( 'evidence', $d['evidence'], 'Add evidence' ); ?>
		</section>

		<section class="sln-csa__panel" data-panel="quote">
			<?php
			sln_cs_textarea( sln_cs_name( 'quote' ), $d['quote'], 'Client quote (only real, approved)', '', 3 );
			echo '<div class="sln-csa__grid">';
			sln_cs_input( sln_cs_name( 'quote_name' ), $d['quote_name'], 'Name' );
			sln_cs_input( sln_cs_name( 'quote_role' ), $d['quote_role'], 'Role / company' );
			echo '</div><h4>Call to action</h4>';
			sln_cs_input( sln_cs_name( 'cta_title' ), $d['cta_title'], 'CTA heading' );
			sln_cs_textarea( sln_cs_name( 'cta_text' ), $d['cta_text'], 'CTA text', '', 2 );
			echo '<div class="sln-csa__grid">';
			sln_cs_input( sln_cs_name( 'cta_label' ), $d['cta_label'], 'Button label' );
			sln_cs_input( sln_cs_name( 'cta_url' ), $d['cta_url'], 'Button link', 'Blank = Contact Us page.', 'url' );
			echo '</div>';
			?>
		</section>
	</div>
	<?php
}

/**
 * Save the meta box.
 *
 * @param int $post_id Post ID.
 */
function sln_case_study_save( $post_id ) {
	if ( ! isset( $_POST['sln_case_study_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sln_case_study_nonce'] ) ), 'sln_case_study_save' ) ) {
		return;
	}
	if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	// phpcs:ignore WordPress.Security.ValidatedSanitizedInput -- sanitized in sln_sanitize_case_study_data().
	$raw = isset( $_POST['sln_cs'] ) ? $_POST['sln_cs'] : array();
	update_post_meta( $post_id, SLN_CASE_STUDY_META, sln_sanitize_case_study_data( $raw ) );
}
add_action( 'save_post_' . SLN_CASE_STUDY_POST_TYPE, 'sln_case_study_save' );
