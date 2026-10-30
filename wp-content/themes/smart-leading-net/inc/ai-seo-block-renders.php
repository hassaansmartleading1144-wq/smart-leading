<?php
/**
 * Frontend render callbacks for AI SEO Gutenberg blocks.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hero section.
 *
 * @param array  $attrs   Attributes.
 * @param string $content Inner blocks.
 * @return string
 */
function sln_render_ai_seo_hero( $attrs, $content ) {
	$tag          = sln_ai_seo_heading_tag( $attrs['headingTag'] ?? 'h1', 'h1' );
	$eyebrow      = $attrs['eyebrow'] ?? '';
	$heading      = preg_replace( '/<\/?span[^>]*>/', '', (string) ( $attrs['heading'] ?? '' ) );
	$description  = $attrs['description'] ?? '';
	$show_crumbs  = ! isset( $attrs['showBreadcrumb'] ) || $attrs['showBreadcrumb'];
	$home_label   = $attrs['crumbHomeLabel'] ?? 'Home';
	$home_url     = $attrs['crumbHomeUrl'] ?? home_url( '/' );
	$mid_label    = $attrs['crumbMidLabel'] ?? 'SEO Services';
	$mid_url      = $attrs['crumbMidUrl'] ?? home_url( '/seo-services/' );
	$current      = $attrs['crumbCurrent'] ?? 'AI SEO Services';
	$journey_title = $attrs['journeyTitle'] ?? '';
	$journey_sub   = $attrs['journeySub'] ?? '';
	$show_journey  = ! isset( $attrs['showJourney'] ) || $attrs['showJourney'];
	$image_url     = $attrs['imageUrl'] ?? '';
	$image_alt     = $attrs['imageAlt'] ?? '';
	$image_pos     = $attrs['imagePosition'] ?? 'none';
	$heading_id    = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] . '-heading' : 'ai-search-h1';

	$wrapper = get_block_wrapper_attributes(
		array(
			'class'            => 'sls-hero',
			'aria-labelledby'  => $heading_id,
		)
	);

	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="sls-wrap">
			<?php if ( $show_crumbs ) : ?>
				<nav class="sls-crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'smart-leading-net' ); ?>">
					<ol>
						<li><a href="<?php echo esc_url( $home_url ); ?>"><?php echo esc_html( $home_label ); ?></a></li>
						<?php if ( $mid_label ) : ?>
							<li><a href="<?php echo esc_url( $mid_url ); ?>"><?php echo esc_html( $mid_label ); ?></a></li>
						<?php endif; ?>
						<li><span aria-current="page"><?php echo esc_html( $current ); ?></span></li>
					</ol>
				</nav>
			<?php endif; ?>
			<div class="sls-hero__grid">
				<div>
					<?php if ( $eyebrow ) : ?>
						<p class="sls-eyebrow"><?php echo sln_ai_seo_kses( $eyebrow ); ?></p>
					<?php endif; ?>
					<<?php echo tag_escape( $tag ); ?> id="<?php echo esc_attr( $heading_id ); ?>" class="sls-title-hero"><?php echo sln_ai_seo_kses( $heading ); ?></<?php echo tag_escape( $tag ); ?>>
					<?php if ( $description ) : ?>
						<p class="sls-lead sls-hero__lead"><?php echo sln_ai_seo_kses( $description ); ?></p>
					<?php endif; ?>
					<div class="sls-actions">
						<?php
						echo sln_ai_seo_render_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							array(
								'label'  => $attrs['primaryLabel'] ?? '',
								'url'    => $attrs['primaryUrl'] ?? '',
								'show'   => ! isset( $attrs['primaryShow'] ) || $attrs['primaryShow'],
								'newTab' => ! empty( $attrs['primaryNewTab'] ),
								'style'  => 'primary-arrow',
							)
						);
						echo sln_ai_seo_render_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							array(
								'label'  => $attrs['secondaryLabel'] ?? '',
								'url'    => $attrs['secondaryUrl'] ?? '',
								'show'   => ! isset( $attrs['secondaryShow'] ) || $attrs['secondaryShow'],
								'newTab' => ! empty( $attrs['secondaryNewTab'] ),
								'style'  => 'secondary',
							)
						);
						?>
					</div>
				</div>
				<?php if ( 'none' !== $image_pos && $image_url ) : ?>
					<figure class="sls-hero__media">
						<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" loading="lazy" decoding="async" />
					</figure>
				<?php elseif ( $show_journey ) : ?>
					<figure class="sls-journey" aria-labelledby="ai-journey-cap">
						<figcaption class="sls-journey__cap" id="ai-journey-cap">
							<span class="sls-journey__title"><?php echo esc_html( $journey_title ); ?></span>
							<span class="sls-journey__sub"><?php echo esc_html( $journey_sub ); ?></span>
						</figcaption>
						<ol><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></ol>
					</figure>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * Journey step.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_ai_seo_journey_step( $attrs ) {
	$num   = $attrs['number'] ?? '';
	$title = $attrs['title'] ?? '';
	$text  = $attrs['text'] ?? '';
	$end   = ! empty( $attrs['isEnd'] );
	$show_answer = ! empty( $attrs['showAnswer'] );
	$class = 'sls-journey__step' . ( $end ? ' sls-journey__step--end' : '' );
	$wrapper = get_block_wrapper_attributes( array( 'class' => $class ) );

	ob_start();
	?>
	<li <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<span class="sls-journey__dot" aria-hidden="true"><?php echo esc_html( $num ); ?></span>
		<div>
			<p class="sls-journey__name"><?php echo sln_ai_seo_kses( $title ); ?></p>
			<p class="sls-journey__text"><?php echo sln_ai_seo_kses( $text ); ?></p>
			<?php if ( $show_answer ) : ?>
				<div class="sls-answer" aria-hidden="true">
					<div class="sls-answer__lines"><span style="width:100%"></span><span style="width:88%"></span><span style="width:64%"></span></div>
					<div class="sls-answer__src"><span><?php echo esc_html( $attrs['sourceA'] ?? 'Source A' ); ?></span><span><?php echo esc_html( $attrs['sourceB'] ?? 'Source B' ); ?></span><span><?php echo esc_html( $attrs['sourceC'] ?? 'Source C' ); ?></span></div>
				</div>
			<?php endif; ?>
		</div>
	</li>
	<?php
	return (string) ob_get_clean();
}

/**
 * Meaning / terminology section.
 *
 * @param array  $attrs   Attributes.
 * @param string $content Inner.
 * @return string
 */
function sln_render_ai_seo_meaning( $attrs, $content ) {
	$tag     = sln_ai_seo_heading_tag( $attrs['headingTag'] ?? 'h2' );
	$heading = $attrs['heading'] ?? '';
	$id      = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] : 'ai-search-meaning';
	$hid     = $id . '-h';
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => sln_ai_seo_section_class( $attrs['background'] ?? 'default' ),
			'id'              => $id,
			'aria-labelledby' => $hid,
		)
	);
	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="sls-wrap sls-split">
			<div class="sls-meaning__copy">
				<?php if ( ! empty( $attrs['eyebrow'] ) ) : ?>
					<p class="sls-eyebrow"><?php echo sln_ai_seo_kses( $attrs['eyebrow'] ); ?></p>
				<?php endif; ?>
				<<?php echo tag_escape( $tag ); ?> id="<?php echo esc_attr( $hid ); ?>" class="sls-title-section"><?php echo sln_ai_seo_kses( $heading ); ?></<?php echo tag_escape( $tag ); ?>>
				<?php if ( ! empty( $attrs['lead'] ) ) : ?>
					<p class="sls-lead"><?php echo sln_ai_seo_kses( $attrs['lead'] ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $attrs['body'] ) ) : ?>
					<p><?php echo sln_ai_seo_kses( $attrs['body'] ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $attrs['note'] ) ) : ?>
					<p><?php echo sln_ai_seo_kses( $attrs['note'] ); ?></p>
				<?php endif; ?>
			</div>
			<div>
				<dl class="sls-terms"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></dl>
				<?php if ( ! empty( $attrs['overlapText'] ) ) : ?>
					<div class="sls-overlap">
						<?php echo sln_ai_seo_icon_svg( 'overlap' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<p><?php echo sln_ai_seo_kses( $attrs['overlapText'] ); ?></p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * Term row.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_ai_seo_term( $attrs ) {
	$wrapper = get_block_wrapper_attributes( array( 'class' => 'sls-term' ) );
	ob_start();
	?>
	<div <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<dt><?php echo esc_html( $attrs['abbr'] ?? '' ); ?><?php if ( ! empty( $attrs['full'] ) ) : ?><small><?php echo esc_html( $attrs['full'] ); ?></small><?php endif; ?></dt>
		<dd><strong><?php echo sln_ai_seo_kses( $attrs['title'] ?? '' ); ?></strong><?php echo sln_ai_seo_kses( $attrs['text'] ?? '' ); ?></dd>
	</div>
	<?php
	return (string) ob_get_clean();
}

/**
 * Benefits section.
 *
 * @param array  $attrs   Attributes.
 * @param string $content Inner.
 * @return string
 */
function sln_render_ai_seo_benefits( $attrs, $content ) {
	$tag     = sln_ai_seo_heading_tag( $attrs['headingTag'] ?? 'h2' );
	$id      = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] : 'ai-search-benefits';
	$hid     = $id . '-h';
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => sln_ai_seo_section_class( $attrs['background'] ?? 'tint' ),
			'id'              => $id,
			'aria-labelledby' => $hid,
		)
	);
	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="sls-wrap">
			<div class="sls-center">
				<<?php echo tag_escape( $tag ); ?> id="<?php echo esc_attr( $hid ); ?>" class="sls-title-section"><?php echo sln_ai_seo_kses( $attrs['heading'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
				<?php if ( ! empty( $attrs['lead'] ) ) : ?>
					<p class="sls-lead"><?php echo sln_ai_seo_kses( $attrs['lead'] ); ?></p>
				<?php endif; ?>
			</div>
			<ul class="sls-grid3"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></ul>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * Benefit card.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_ai_seo_benefit_card( $attrs ) {
	$tag     = sln_ai_seo_heading_tag( $attrs['headingTag'] ?? 'h3', 'h3' );
	$wrapper = get_block_wrapper_attributes( array( 'class' => 'sls-card sls-benefit' ) );
	ob_start();
	?>
	<li <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<?php echo sln_ai_seo_icon_svg( $attrs['icon'] ?? 'pie', 'sls-benefit__icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<<?php echo tag_escape( $tag ); ?> class="sls-title-card"><?php echo sln_ai_seo_kses( $attrs['title'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
		<p><?php echo sln_ai_seo_kses( $attrs['text'] ?? '' ); ?></p>
	</li>
	<?php
	return (string) ob_get_clean();
}

/**
 * Platforms section.
 *
 * @param array  $attrs   Attributes.
 * @param string $content Inner chips.
 * @return string
 */
function sln_render_ai_seo_platforms( $attrs, $content ) {
	$tag     = sln_ai_seo_heading_tag( $attrs['headingTag'] ?? 'h2' );
	$id      = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] : 'ai-search-platforms';
	$hid     = $id . '-h';
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => sln_ai_seo_section_class( $attrs['background'] ?? 'default' ),
			'id'              => $id,
			'aria-labelledby' => $hid,
		)
	);
	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="sls-wrap sls-platforms">
			<div class="sls-platforms__intro">
				<?php if ( ! empty( $attrs['eyebrow'] ) ) : ?>
					<p class="sls-eyebrow"><?php echo sln_ai_seo_kses( $attrs['eyebrow'] ); ?></p>
				<?php endif; ?>
				<<?php echo tag_escape( $tag ); ?> id="<?php echo esc_attr( $hid ); ?>" class="sls-title-section"><?php echo sln_ai_seo_kses( $attrs['heading'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
				<?php if ( ! empty( $attrs['text'] ) ) : ?>
					<p><?php echo sln_ai_seo_kses( $attrs['text'] ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $attrs['note'] ) ) : ?>
					<p class="sls-platforms__note"><?php echo sln_ai_seo_kses( $attrs['note'] ); ?></p>
				<?php endif; ?>
			</div>
			<div>
				<ul class="sls-chips" aria-label="<?php echo esc_attr( $attrs['chipsLabel'] ?? '' ); ?>">
					<?php if ( ! empty( $attrs['chipsHeading'] ) ) : ?>
						<li class="sls-chips__label" aria-hidden="true"><?php echo esc_html( $attrs['chipsHeading'] ); ?></li>
					<?php endif; ?>
					<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</ul>
			</div>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * Chip.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_ai_seo_chip( $attrs ) {
	$wrapper = get_block_wrapper_attributes();
	return '<li ' . $wrapper . '>' . esc_html( $attrs['label'] ?? '' ) . '</li>';
}

/**
 * Approach / numbered cards.
 *
 * @param array  $attrs   Attributes.
 * @param string $content Inner.
 * @return string
 */
function sln_render_ai_seo_approach( $attrs, $content ) {
	$tag     = sln_ai_seo_heading_tag( $attrs['headingTag'] ?? 'h2' );
	$id      = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] : 'ai-search-approach';
	$hid     = $id . '-h';
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => sln_ai_seo_section_class( $attrs['background'] ?? 'tint' ),
			'id'              => $id,
			'aria-labelledby' => $hid,
		)
	);
	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="sls-wrap">
			<div class="sls-head">
				<div>
					<?php if ( ! empty( $attrs['eyebrow'] ) ) : ?>
						<p class="sls-eyebrow"><?php echo sln_ai_seo_kses( $attrs['eyebrow'] ); ?></p>
					<?php endif; ?>
					<<?php echo tag_escape( $tag ); ?> id="<?php echo esc_attr( $hid ); ?>" class="sls-title-section"><?php echo sln_ai_seo_kses( $attrs['heading'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
				</div>
				<?php if ( ! empty( $attrs['lead'] ) ) : ?>
					<p class="sls-lead"><?php echo sln_ai_seo_kses( $attrs['lead'] ); ?></p>
				<?php endif; ?>
			</div>
			<ul class="sls-grid3"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></ul>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * Numbered service card.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_ai_seo_card( $attrs ) {
	$tag     = sln_ai_seo_heading_tag( $attrs['headingTag'] ?? 'h3', 'h3' );
	$wrapper = get_block_wrapper_attributes( array( 'class' => 'sls-card' ) );
	ob_start();
	?>
	<li <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<?php if ( ! empty( $attrs['number'] ) ) : ?>
			<span class="sls-card__num" aria-hidden="true"><?php echo esc_html( $attrs['number'] ); ?></span>
		<?php endif; ?>
		<<?php echo tag_escape( $tag ); ?> class="sls-title-card"><?php echo sln_ai_seo_kses( $attrs['title'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
		<p><?php echo sln_ai_seo_kses( $attrs['text'] ?? '' ); ?></p>
	</li>
	<?php
	return (string) ob_get_clean();
}

/**
 * Pricing section.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_ai_seo_pricing( $attrs ) {
	$tag     = sln_ai_seo_heading_tag( $attrs['headingTag'] ?? 'h2' );
	$id      = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] : 'ai-search-pricing';
	$hid     = $id . '-h';
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => sln_ai_seo_section_class( $attrs['background'] ?? 'default' ),
			'id'              => $id,
			'aria-labelledby' => $hid,
		)
	);
	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="sls-wrap sls-pricing">
			<div>
				<?php if ( ! empty( $attrs['eyebrow'] ) ) : ?>
					<p class="sls-eyebrow"><?php echo sln_ai_seo_kses( $attrs['eyebrow'] ); ?></p>
				<?php endif; ?>
				<<?php echo tag_escape( $tag ); ?> id="<?php echo esc_attr( $hid ); ?>" class="sls-title-section"><?php echo sln_ai_seo_kses( $attrs['heading'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
				<?php if ( ! empty( $attrs['lead'] ) ) : ?>
					<p class="sls-lead"><?php echo sln_ai_seo_kses( $attrs['lead'] ); ?></p>
				<?php endif; ?>
				<?php echo sln_render_ai_seo_mini_lead_block( $attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<div class="sls-price">
				<span class="sls-price__icon" aria-hidden="true"><?php echo sln_ai_seo_icon_svg( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<p class="sls-price__label"><?php echo sln_ai_seo_kses( $attrs['priceLabel'] ?? '' ); ?></p>
				<p class="sls-price__amt"><?php echo sln_ai_seo_kses( $attrs['priceAmount'] ?? '' ); ?><?php if ( ! empty( $attrs['pricePeriod'] ) ) : ?><span><?php echo esc_html( $attrs['pricePeriod'] ); ?></span><?php endif; ?></p>
				<span class="sls-price__bar" aria-hidden="true"></span>
				<p class="sls-price__for"><?php echo sln_ai_seo_kses( $attrs['priceFor'] ?? '' ); ?></p>
				<?php if ( ! empty( $attrs['priceFine'] ) ) : ?>
					<p class="sls-price__fine"><?php echo sln_ai_seo_kses( $attrs['priceFine'] ); ?></p>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * Process section.
 *
 * @param array  $attrs   Attributes.
 * @param string $content Inner.
 * @return string
 */
function sln_render_ai_seo_process( $attrs, $content ) {
	$tag     = sln_ai_seo_heading_tag( $attrs['headingTag'] ?? 'h2' );
	$id      = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] : 'ai-search-process';
	$hid     = $id . '-h';
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => sln_ai_seo_section_class( $attrs['background'] ?? 'tint' ),
			'id'              => $id,
			'aria-labelledby' => $hid,
		)
	);
	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="sls-wrap">
			<div class="sls-head">
				<div>
					<?php if ( ! empty( $attrs['eyebrow'] ) ) : ?>
						<p class="sls-eyebrow"><?php echo sln_ai_seo_kses( $attrs['eyebrow'] ); ?></p>
					<?php endif; ?>
					<<?php echo tag_escape( $tag ); ?> id="<?php echo esc_attr( $hid ); ?>" class="sls-title-section"><?php echo sln_ai_seo_kses( $attrs['heading'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
				</div>
				<?php if ( ! empty( $attrs['lead'] ) ) : ?>
					<p class="sls-lead"><?php echo sln_ai_seo_kses( $attrs['lead'] ); ?></p>
				<?php endif; ?>
			</div>
			<ol class="sls-steps"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></ol>
			<?php if ( ! empty( $attrs['primaryLabel'] ) && ( ! isset( $attrs['primaryShow'] ) || $attrs['primaryShow'] ) ) : ?>
				<div class="sls-process__cta">
					<?php
					echo sln_ai_seo_render_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						array(
							'label'  => $attrs['primaryLabel'] ?? '',
							'url'    => $attrs['primaryUrl'] ?? '',
							'show'   => true,
							'newTab' => ! empty( $attrs['primaryNewTab'] ),
							'style'  => 'primary-arrow',
						)
					);
					?>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * Process step.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_ai_seo_process_step( $attrs ) {
	$tag     = sln_ai_seo_heading_tag( $attrs['headingTag'] ?? 'h3', 'h3' );
	$wrapper = get_block_wrapper_attributes( array( 'class' => 'sls-step' ) );
	ob_start();
	?>
	<li <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<span class="sls-step__n" aria-hidden="true"><?php echo esc_html( $attrs['number'] ?? '' ); ?></span>
		<<?php echo tag_escape( $tag ); ?> class="sls-title-card"><?php echo sln_ai_seo_kses( $attrs['title'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
		<p><?php echo sln_ai_seo_kses( $attrs['text'] ?? '' ); ?></p>
		<?php if ( ! empty( $attrs['output'] ) ) : ?>
			<p class="sls-step__out"><b><?php echo esc_html( $attrs['outputLabel'] ?? 'Output:' ); ?></b> <?php echo sln_ai_seo_kses( $attrs['output'] ); ?></p>
		<?php endif; ?>
	</li>
	<?php
	return (string) ob_get_clean();
}

/**
 * Measurement table section.
 *
 * @param array  $attrs   Attributes.
 * @param string $content Inner rows.
 * @return string
 */
function sln_render_ai_seo_measurement( $attrs, $content ) {
	$tag     = sln_ai_seo_heading_tag( $attrs['headingTag'] ?? 'h2' );
	$id      = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] : 'ai-search-measurement';
	$hid     = $id . '-h';
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => sln_ai_seo_section_class( $attrs['background'] ?? 'default' ),
			'id'              => $id,
			'aria-labelledby' => $hid,
		)
	);
	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="sls-wrap">
			<div class="sls-head">
				<div>
					<?php if ( ! empty( $attrs['eyebrow'] ) ) : ?>
						<p class="sls-eyebrow"><?php echo sln_ai_seo_kses( $attrs['eyebrow'] ); ?></p>
					<?php endif; ?>
					<<?php echo tag_escape( $tag ); ?> id="<?php echo esc_attr( $hid ); ?>" class="sls-title-section"><?php echo sln_ai_seo_kses( $attrs['heading'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
				</div>
				<?php if ( ! empty( $attrs['lead'] ) ) : ?>
					<p class="sls-lead"><?php echo sln_ai_seo_kses( $attrs['lead'] ); ?></p>
				<?php endif; ?>
			</div>
			<div class="sls-table-wrap">
				<table class="sls-table">
					<caption class="sls-sr"><?php echo esc_html( $attrs['caption'] ?? '' ); ?></caption>
					<thead>
						<tr>
							<th scope="col"><?php echo esc_html( $attrs['colSignal'] ?? 'Signal' ); ?></th>
							<th scope="col"><?php echo esc_html( $attrs['colCan'] ?? 'What it can tell your team' ); ?></th>
							<th scope="col"><?php echo esc_html( $attrs['colCannot'] ?? 'What it cannot show' ); ?></th>
						</tr>
					</thead>
					<tbody><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></tbody>
				</table>
			</div>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * Measurement row.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_ai_seo_signal_row( $attrs ) {
	$wrapper = get_block_wrapper_attributes();
	ob_start();
	?>
	<tr <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<th scope="row"><?php if ( ! empty( $attrs['number'] ) ) : ?><span aria-hidden="true"><?php echo esc_html( $attrs['number'] ); ?></span><?php endif; ?><?php echo sln_ai_seo_kses( $attrs['title'] ?? '' ); ?></th>
		<td data-label="<?php echo esc_attr( $attrs['canLabel'] ?? 'What it can tell your team' ); ?>"><?php echo sln_ai_seo_kses( $attrs['canTell'] ?? '' ); ?></td>
		<td data-label="<?php echo esc_attr( $attrs['cannotLabel'] ?? 'What it cannot show' ); ?>"><?php echo sln_ai_seo_kses( $attrs['cannotShow'] ?? '' ); ?></td>
	</tr>
	<?php
	return (string) ob_get_clean();
}

/**
 * Principles section.
 *
 * @param array  $attrs   Attributes.
 * @param string $content Inner.
 * @return string
 */
function sln_render_ai_seo_principles( $attrs, $content ) {
	$tag     = sln_ai_seo_heading_tag( $attrs['headingTag'] ?? 'h2' );
	$id      = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] : 'ai-search-principles';
	$hid     = $id . '-h';
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => sln_ai_seo_section_class( $attrs['background'] ?? 'blue' ),
			'id'              => $id,
			'aria-labelledby' => $hid,
		)
	);
	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="sls-wrap sls-principles">
			<div class="sls-principles__intro">
				<?php if ( ! empty( $attrs['eyebrow'] ) ) : ?>
					<p class="sls-eyebrow"><?php echo sln_ai_seo_kses( $attrs['eyebrow'] ); ?></p>
				<?php endif; ?>
				<<?php echo tag_escape( $tag ); ?> id="<?php echo esc_attr( $hid ); ?>" class="sls-title-section"><?php echo sln_ai_seo_kses( $attrs['heading'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
				<?php if ( ! empty( $attrs['text'] ) ) : ?>
					<p><?php echo sln_ai_seo_kses( $attrs['text'] ); ?></p>
				<?php endif; ?>
			</div>
			<ol class="sls-plist"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></ol>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * Principle item.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_ai_seo_principle( $attrs ) {
	$tag     = sln_ai_seo_heading_tag( $attrs['headingTag'] ?? 'h3', 'h3' );
	$wrapper = get_block_wrapper_attributes();
	ob_start();
	?>
	<li <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<span class="sls-plist__n" aria-hidden="true"><?php echo esc_html( $attrs['number'] ?? '' ); ?></span>
		<div>
			<<?php echo tag_escape( $tag ); ?> class="sls-title-card"><?php echo sln_ai_seo_kses( $attrs['title'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
			<p><?php echo sln_ai_seo_kses( $attrs['text'] ?? '' ); ?></p>
		</div>
	</li>
	<?php
	return (string) ob_get_clean();
}

/**
 * Scope section.
 *
 * @param array  $attrs   Attributes.
 * @param string $content Inner.
 * @return string
 */
function sln_render_ai_seo_scope( $attrs, $content ) {
	$tag     = sln_ai_seo_heading_tag( $attrs['headingTag'] ?? 'h2' );
	$id      = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] : 'ai-search-scope';
	$hid     = $id . '-h';
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => sln_ai_seo_section_class( $attrs['background'] ?? 'default' ),
			'id'              => $id,
			'aria-labelledby' => $hid,
		)
	);
	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="sls-wrap sls-scope">
			<div class="sls-scope__copy">
				<?php if ( ! empty( $attrs['eyebrow'] ) ) : ?>
					<p class="sls-eyebrow"><?php echo sln_ai_seo_kses( $attrs['eyebrow'] ); ?></p>
				<?php endif; ?>
				<<?php echo tag_escape( $tag ); ?> id="<?php echo esc_attr( $hid ); ?>" class="sls-title-section"><?php echo sln_ai_seo_kses( $attrs['heading'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
				<?php if ( ! empty( $attrs['lead'] ) ) : ?>
					<p class="sls-lead"><?php echo sln_ai_seo_kses( $attrs['lead'] ); ?></p>
				<?php endif; ?>
				<div class="sls-actions">
					<?php
					echo sln_ai_seo_render_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						array(
							'label'  => $attrs['primaryLabel'] ?? '',
							'url'    => $attrs['primaryUrl'] ?? '',
							'show'   => ! isset( $attrs['primaryShow'] ) || $attrs['primaryShow'],
							'newTab' => ! empty( $attrs['primaryNewTab'] ),
							'style'  => 'primary-arrow',
						)
					);
					?>
				</div>
			</div>
			<div class="sls-scope__card">
				<ul aria-label="<?php echo esc_attr( $attrs['listLabel'] ?? '' ); ?>"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></ul>
			</div>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * Scope item.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_ai_seo_scope_item( $attrs ) {
	$wrapper = get_block_wrapper_attributes();
	ob_start();
	?>
	<li <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<span class="sls-tick" aria-hidden="true"><?php echo sln_ai_seo_icon_svg( 'tick' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		<div>
			<b><?php echo sln_ai_seo_kses( $attrs['title'] ?? '' ); ?></b>
			<span class="d"><?php echo sln_ai_seo_kses( $attrs['text'] ?? '' ); ?></span>
		</div>
	</li>
	<?php
	return (string) ob_get_clean();
}

/**
 * Banner CTA.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_ai_seo_banner( $attrs ) {
	$tag     = sln_ai_seo_heading_tag( $attrs['headingTag'] ?? 'h2' );
	$id      = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] : 'ai-search-banner';
	$hid     = $id . '-h';
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => sln_ai_seo_section_class( 'banner' ),
			'id'              => $id,
			'aria-labelledby' => $hid,
		)
	);
	$img = $attrs['imageUrl'] ?? '';
	$alt = $attrs['imageAlt'] ?? '';
	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="sls-wrap">
			<div class="sls-banner">
				<div class="sls-banner__proof">
					<?php if ( $img ) : ?>
						<figure class="sls-banner__photo">
							<img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $alt ); ?>" loading="lazy" decoding="async" width="170" height="250" />
						</figure>
					<?php else : ?>
						<div class="sls-banner__photo" role="img" aria-label="<?php echo esc_attr( $alt ? $alt : __( 'Team photo placeholder', 'smart-leading-net' ) ); ?>"><?php echo esc_html( $attrs['photoPlaceholder'] ?? '[SLS team photo]' ); ?></div>
					<?php endif; ?>
					<div class="sls-banner__stat">
						<p class="sls-banner__k"><?php echo sln_ai_seo_kses( $attrs['statKicker'] ?? '' ); ?></p>
						<p class="sls-banner__n"><?php echo sln_ai_seo_kses( $attrs['statNumber'] ?? '' ); ?></p>
						<p class="sls-banner__l"><?php echo sln_ai_seo_kses( $attrs['statLabel'] ?? '' ); ?></p>
					</div>
				</div>
				<div class="sls-banner__copy">
					<<?php echo tag_escape( $tag ); ?> id="<?php echo esc_attr( $hid ); ?>" class="sls-title-section"><?php echo sln_ai_seo_kses( $attrs['heading'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
					<?php if ( ! empty( $attrs['text'] ) ) : ?>
						<p><?php echo sln_ai_seo_kses( $attrs['text'] ); ?></p>
					<?php endif; ?>
					<?php
					$mini               = $attrs;
					$mini['variant']    = 'onblue';
					$mini['showArrow']  = false;
					$mini['inputId']    = $attrs['inputId'] ?? 'ai-mini-website-2';
					echo sln_render_ai_seo_mini_lead_block( $mini ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					?>
				</div>
			</div>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * FAQ section.
 *
 * @param array  $attrs   Attributes.
 * @param string $content Inner.
 * @return string
 */
function sln_render_ai_seo_faq( $attrs, $content ) {
	$tag     = sln_ai_seo_heading_tag( $attrs['headingTag'] ?? 'h2' );
	$id      = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] : 'ai-search-faq';
	$hid     = $id . '-h';
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => sln_ai_seo_section_class( $attrs['background'] ?? 'default' ),
			'id'              => $id,
			'aria-labelledby' => $hid,
		)
	);
	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="sls-wrap sls-faqwrap">
			<div class="sls-faqwrap__intro">
				<?php if ( ! empty( $attrs['eyebrow'] ) ) : ?>
					<p class="sls-eyebrow"><?php echo sln_ai_seo_kses( $attrs['eyebrow'] ); ?></p>
				<?php endif; ?>
				<<?php echo tag_escape( $tag ); ?> id="<?php echo esc_attr( $hid ); ?>" class="sls-title-section"><?php echo sln_ai_seo_kses( $attrs['heading'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
				<?php if ( ! empty( $attrs['intro'] ) ) : ?>
					<p><?php echo sln_ai_seo_kses( $attrs['intro'] ); ?></p>
				<?php endif; ?>
			</div>
			<div class="sls-faq"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * FAQ item.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_ai_seo_faq_item( $attrs ) {
	$wrapper = get_block_wrapper_attributes( array( 'class' => 'wp-block-details' ) );
	ob_start();
	?>
	<details <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<summary><?php echo sln_ai_seo_kses( $attrs['question'] ?? '' ); ?></summary>
		<p><?php echo sln_ai_seo_kses( $attrs['answer'] ?? '' ); ?></p>
	</details>
	<?php
	return (string) ob_get_clean();
}

/**
 * Enquiry section.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_ai_seo_enquiry( $attrs ) {
	$tag     = sln_ai_seo_heading_tag( $attrs['headingTag'] ?? 'h2' );
	$id      = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] : 'ai-search-enquiry';
	$hid     = $id . '-h';
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => sln_ai_seo_section_class( $attrs['background'] ?? 'tint' ),
			'id'              => $id,
			'aria-labelledby' => $hid,
		)
	);
	$points = array_filter(
		array(
			$attrs['point1'] ?? '',
			$attrs['point2'] ?? '',
			$attrs['point3'] ?? '',
		)
	);
	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="sls-wrap sls-enq">
			<div class="sls-enq__copy">
				<?php if ( ! empty( $attrs['eyebrow'] ) ) : ?>
					<p class="sls-eyebrow"><?php echo sln_ai_seo_kses( $attrs['eyebrow'] ); ?></p>
				<?php endif; ?>
				<<?php echo tag_escape( $tag ); ?> id="<?php echo esc_attr( $hid ); ?>" class="sls-title-section"><?php echo sln_ai_seo_kses( $attrs['heading'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
				<?php if ( ! empty( $attrs['lead'] ) ) : ?>
					<p class="sls-lead"><?php echo sln_ai_seo_kses( $attrs['lead'] ); ?></p>
				<?php endif; ?>
				<?php if ( $points ) : ?>
					<ul class="sls-enq__list">
						<?php foreach ( $points as $point ) : ?>
							<li><?php echo sln_ai_seo_kses( $point ); ?></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<?php if ( ! empty( $attrs['nextText'] ) ) : ?>
					<p class="sls-enq__next"><?php echo sln_ai_seo_kses( $attrs['nextText'] ); ?></p>
				<?php endif; ?>
			</div>
			<?php echo sln_render_ai_seo_enquiry_form_block( $attrs ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}
