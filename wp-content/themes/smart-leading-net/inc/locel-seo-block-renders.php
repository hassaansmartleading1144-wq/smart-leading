<?php
/**
 * Frontend render callbacks for Locale SEO Gutenberg blocks.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hero.
 *
 * @param array    $attrs   Attributes.
 * @param string   $content Inner HTML.
 * @param WP_Block $block   Block.
 * @return string
 */
function sln_render_locel_seo_hero( $attrs, $content, $block ) {
	$tag         = sln_locel_heading_tag( $attrs['headingTag'] ?? 'h1', 'h1' );
	$heading_id  = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] . '-heading' : 'hero-title';
	$show_crumbs = ! isset( $attrs['showBreadcrumb'] ) || $attrs['showBreadcrumb'];
	$wrapper     = get_block_wrapper_attributes(
		array(
			'class'           => 'hero',
			'aria-labelledby' => $heading_id,
		)
	);
	$cards = sln_locel_render_named_inners( $block, 'smartleading/locel-seo-map-card' );

	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="lseo-wrap">
			<?php if ( $show_crumbs ) : ?>
				<nav class="crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'smart-leading-net' ); ?>">
					<ol>
						<li><a href="<?php echo esc_url( $attrs['crumbHomeUrl'] ?? home_url( '/' ) ); ?>"><?php echo esc_html( $attrs['crumbHomeLabel'] ?? 'Home' ); ?></a></li>
						<?php if ( ! empty( $attrs['crumbMid1Label'] ) ) : ?>
							<li><span><?php echo esc_html( $attrs['crumbMid1Label'] ); ?></span></li>
						<?php endif; ?>
						<?php if ( ! empty( $attrs['crumbMid2Label'] ) ) : ?>
							<li><a href="<?php echo esc_url( $attrs['crumbMid2Url'] ?? '#' ); ?>"><?php echo esc_html( $attrs['crumbMid2Label'] ); ?></a></li>
						<?php endif; ?>
						<li><span aria-current="page"><?php echo esc_html( $attrs['crumbCurrent'] ?? 'Local SEO' ); ?></span></li>
					</ol>
				</nav>
			<?php endif; ?>
			<div class="hero__grid">
				<div>
					<?php if ( ! empty( $attrs['eyebrow'] ) ) : ?>
						<p class="eyebrow"><?php echo sln_locel_kses( $attrs['eyebrow'] ); ?></p>
					<?php endif; ?>
					<<?php echo tag_escape( $tag ); ?> id="<?php echo esc_attr( $heading_id ); ?>"><?php echo sln_locel_kses( $attrs['heading'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
					<?php if ( ! empty( $attrs['description'] ) ) : ?>
						<p class="hero__lead"><?php echo sln_locel_kses( $attrs['description'] ); ?></p>
					<?php endif; ?>
					<div class="hero__cta">
						<?php
						echo sln_locel_render_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							array(
								'label'  => $attrs['primaryLabel'] ?? '',
								'url'    => $attrs['primaryUrl'] ?? '',
								'show'   => ! isset( $attrs['primaryShow'] ) || $attrs['primaryShow'],
								'newTab' => ! empty( $attrs['primaryNewTab'] ),
								'style'  => 'primary',
								'arrow'  => true,
							)
						);
						echo sln_locel_render_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							array(
								'label'  => $attrs['secondaryLabel'] ?? '',
								'url'    => $attrs['secondaryUrl'] ?? '',
								'show'   => ! isset( $attrs['secondaryShow'] ) || $attrs['secondaryShow'],
								'newTab' => ! empty( $attrs['secondaryNewTab'] ),
								'style'  => 'outline',
								'arrow'  => false,
							)
						);
						?>
					</div>
				</div>
				<figure class="hero__fig" aria-label="<?php echo esc_attr( $attrs['mapAria'] ?? __( 'Local customer journey: search, Business Profile or website, then an enquiry.', 'smart-leading-net' ) ); ?>">
					<div class="map">
						<?php echo sln_locel_map_decoration(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php echo $cards; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</figure>
			</div>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * Hero map card.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_locel_seo_map_card( $attrs ) {
	$variant = $attrs['variant'] ?? 'search';
	$allowed = array( 'search', 'profile', 'site', 'goal' );
	if ( ! in_array( $variant, $allowed, true ) ) {
		$variant = 'search';
	}
	$wrapper = get_block_wrapper_attributes( array( 'class' => 'mcard mcard--' . $variant ) );
	$chips   = array_filter( array_map( 'trim', explode( '|', (string) ( $attrs['chips'] ?? '' ) ) ) );
	ob_start();
	?>
	<div <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<p class="mcard__label"><span class="mcard__n" aria-hidden="true"><?php echo esc_html( $attrs['number'] ?? '' ); ?></span><?php echo sln_locel_kses( $attrs['label'] ?? '' ); ?></p>
		<?php if ( 'search' === $variant ) : ?>
			<div class="mcard__search"><?php echo sln_locel_icon( 'search' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo sln_locel_kses( $attrs['searchText'] ?? '' ); ?></span></div>
		<?php else : ?>
			<?php if ( ! empty( $attrs['title'] ) ) : ?>
				<p class="mcard__title"><?php echo sln_locel_kses( $attrs['title'] ); ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $attrs['meta'] ) ) : ?>
				<p class="mcard__meta"><?php echo sln_locel_kses( $attrs['meta'] ); ?></p>
			<?php endif; ?>
			<?php if ( 'site' === $variant ) : ?>
				<div class="mcard__lines" aria-hidden="true"><span></span><span style="width:70%"></span></div>
			<?php endif; ?>
		<?php endif; ?>
		<?php if ( $chips ) : ?>
			<ul class="chips<?php echo 'goal' === $variant ? ' chips--goal' : ''; ?>">
				<?php foreach ( $chips as $chip ) : ?>
					<li><?php echo esc_html( $chip ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
	<?php
	return (string) ob_get_clean();
}

/**
 * Discovery section.
 *
 * @param array    $attrs   Attributes.
 * @param string   $content Inner.
 * @param WP_Block $block   Block.
 * @return string
 */
function sln_render_locel_seo_discovery( $attrs, $content, $block ) {
	$tag     = sln_locel_heading_tag( $attrs['headingTag'] ?? 'h2' );
	$id      = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] : 'local-seo-discovery';
	$hid     = $id . '-h';
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => 'lseo-sec',
			'id'              => $id,
			'aria-labelledby' => $hid,
		)
	);
	$stops   = sln_locel_render_named_inners( $block, 'smartleading/locel-seo-stop' );
	$factors = sln_locel_render_named_inners( $block, 'smartleading/locel-seo-factor' );
	$merges  = array();
	foreach ( sln_locel_iter_inner_blocks( $block ) as $inner ) {
		if ( 'smartleading/locel-seo-merge-col' === $inner->name ) {
			$merges[] = $inner->render();
		}
	}
	$has_structured = ( '' !== $stops || '' !== $factors || ! empty( $merges ) );
	$connector      = '<p class="merge__link">' . sln_locel_icon( 'link' ) . '<span>' . sln_locel_kses( $attrs['connectorLabel'] ?? 'Connected,<br>but different' ) . '</span></p>';
	$merge_html     = '';
	if ( $merges ) {
		$first      = array_shift( $merges );
		$merge_html = $first . $connector . implode( '', $merges );
	}
	$inner_fallback = '';
	if ( ! $has_structured && is_string( $content ) && '' !== trim( wp_strip_all_tags( $content ) ) ) {
		$inner_fallback = $content;
	}
	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="lseo-wrap">
			<div class="head">
				<<?php echo tag_escape( $tag ); ?> class="title" id="<?php echo esc_attr( $hid ); ?>"><?php echo sln_locel_kses( $attrs['heading'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
				<p class="lead"><?php echo sln_locel_kses( $attrs['lead'] ?? '' ); ?></p>
			</div>
			<ol class="road"><?php echo $stops; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></ol>
			<?php echo $inner_fallback; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<div class="merge">
				<div class="merge__top">
					<?php echo $merge_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				<svg class="merge__lines" viewBox="0 0 100 40" preserveAspectRatio="none" aria-hidden="true"><path d="M23 0 C 23 26, 50 14, 50 40 M77 0 C 77 26, 50 14, 50 40"></path></svg>
				<p class="merge__goal"><?php echo sln_locel_icon( 'target' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo sln_locel_kses( $attrs['goalText'] ?? '' ); ?></p>
			</div>
			<div class="factors">
				<div class="factors__intro">
					<strong><?php echo sln_locel_kses( $attrs['factorsTitle'] ?? '' ); ?></strong>
					<span><?php echo sln_locel_kses( $attrs['factorsNote'] ?? '' ); ?></span>
				</div>
				<div class="factors__list"><?php echo $factors; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			</div>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * Discovery stop.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_locel_seo_stop( $attrs ) {
	$wrapper = get_block_wrapper_attributes( array( 'class' => 'stop' ) );
	ob_start();
	?>
	<li <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<span class="stop__pin" aria-hidden="true"><?php echo sln_locel_icon( $attrs['icon'] ?? 'pin' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		<div>
			<h3><?php echo sln_locel_kses( $attrs['title'] ?? '' ); ?></h3>
			<p><?php echo sln_locel_kses( $attrs['text'] ?? '' ); ?></p>
		</div>
	</li>
	<?php
	return (string) ob_get_clean();
}

/**
 * Merge column.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_locel_seo_merge_col( $attrs ) {
	$wrapper = get_block_wrapper_attributes( array( 'class' => 'merge__col card' ) );
	$ticks   = array_filter(
		array(
			$attrs['tick1'] ?? '',
			$attrs['tick2'] ?? '',
			$attrs['tick3'] ?? '',
		)
	);
	ob_start();
	?>
	<div <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<h3><?php echo sln_locel_kses( $attrs['title'] ?? '' ); ?></h3>
		<p><?php echo sln_locel_kses( $attrs['text'] ?? '' ); ?></p>
		<?php if ( $ticks ) : ?>
			<ul class="ticks">
				<?php foreach ( $ticks as $tick ) : ?>
					<li><?php echo sln_locel_icon( 'tick' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo sln_locel_kses( $tick ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
	<?php
	return (string) ob_get_clean();
}

/**
 * Ranking factor.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_locel_seo_factor( $attrs ) {
	$wrapper = get_block_wrapper_attributes( array( 'class' => 'factor' ) );
	ob_start();
	?>
	<div <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<?php echo sln_locel_factor_art( $attrs['art'] ?? 'relevance' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<h3><?php echo sln_locel_kses( $attrs['title'] ?? '' ); ?></h3>
		<p><?php echo sln_locel_kses( $attrs['text'] ?? '' ); ?></p>
	</div>
	<?php
	return (string) ob_get_clean();
}

/**
 * Challenges.
 *
 * @param array    $attrs Attributes.
 * @param string   $content Inner.
 * @param WP_Block $block Block.
 * @return string
 */
function sln_render_locel_seo_challenges( $attrs, $content, $block ) {
	$tag     = sln_locel_heading_tag( $attrs['headingTag'] ?? 'h2' );
	$id      = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] : 'local-seo-challenges';
	$hid     = $id . '-h';
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => 'lseo-sec lseo-sec--soft',
			'id'              => $id,
			'aria-labelledby' => $hid,
		)
	);
	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="lseo-wrap">
			<div class="head">
				<<?php echo tag_escape( $tag ); ?> class="title" id="<?php echo esc_attr( $hid ); ?>"><?php echo sln_locel_kses( $attrs['heading'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
				<div class="head__side">
					<p class="lead"><?php echo sln_locel_kses( $attrs['lead'] ?? '' ); ?></p>
					<?php
					echo sln_locel_render_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						array(
							'label'  => $attrs['primaryLabel'] ?? '',
							'url'    => $attrs['primaryUrl'] ?? '',
							'show'   => ! isset( $attrs['primaryShow'] ) || $attrs['primaryShow'],
							'newTab' => ! empty( $attrs['primaryNewTab'] ),
							'style'  => 'outline',
							'arrow'  => false,
						)
					);
					?>
				</div>
			</div>
			<ul class="signs"><?php
			$challenge_cards = sln_locel_render_named_inners( $block, 'smartleading/locel-seo-challenge' );
			if ( '' === $challenge_cards && is_string( $content ) ) {
				$challenge_cards = $content;
			}
			echo $challenge_cards; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			?></ul>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * Challenge card.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_locel_seo_challenge( $attrs ) {
	$wrapper = get_block_wrapper_attributes( array( 'class' => 'signcard card' ) );
	ob_start();
	?>
	<li <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="signcard__top"><span class="signcard__icon" aria-hidden="true"><?php echo sln_locel_icon( $attrs['icon'] ?? 'searchx' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><h3><?php echo sln_locel_kses( $attrs['title'] ?? '' ); ?></h3></div>
		<p><?php echo sln_locel_kses( $attrs['text'] ?? '' ); ?></p>
		<p class="signcard__sign"><?php echo sln_locel_kses( $attrs['sign'] ?? '' ); ?></p>
	</li>
	<?php
	return (string) ob_get_clean();
}

/**
 * Beyond traffic / video.
 *
 * @param array    $attrs Attributes.
 * @param string   $content Inner.
 * @param WP_Block $block Block.
 * @return string
 */
function sln_render_locel_seo_beyond( $attrs, $content, $block ) {
	$tag     = sln_locel_heading_tag( $attrs['headingTag'] ?? 'h2' );
	$id      = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] : 'local-seo-beyond';
	$hid     = $id . '-h';
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => 'lseo-sec band on-dark',
			'id'              => $id,
			'aria-labelledby' => $hid,
		)
	);
	$video_url = trim( (string) ( $attrs['videoUrl'] ?? '' ) );
	if ( '' === $video_url ) {
		$video_url = sln_locel_default_video_url();
	}
	$ig_code   = sln_locel_instagram_code( $video_url );
	$ig_perm   = $ig_code ? sln_locel_instagram_permalink( $ig_code ) : '';
	$embed     = $ig_code ? '' : sln_locel_video_embed( $video_url );
	$play_id   = $id . '-player';
	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="lseo-wrap">
			<div class="head head--center">
				<?php if ( ! empty( $attrs['eyebrow'] ) ) : ?>
					<p class="eyebrow eyebrow--center"><?php echo sln_locel_kses( $attrs['eyebrow'] ); ?></p>
				<?php endif; ?>
				<<?php echo tag_escape( $tag ); ?> class="title" id="<?php echo esc_attr( $hid ); ?>"><?php echo sln_locel_kses( $attrs['heading'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
				<p class="lead"><?php echo sln_locel_kses( $attrs['lead'] ?? '' ); ?></p>
			</div>
			<figure class="video" aria-labelledby="video-cap">
				<?php if ( $ig_code ) : ?>
					<div class="video__frame is-reel" id="<?php echo esc_attr( $play_id ); ?>" data-lseo-ig-player data-ig-permalink="<?php echo esc_url( $ig_perm ); ?>">
						<div class="video__ig" data-lseo-ig-host hidden></div>
						<div class="video__inner">
							<p class="video__title"><?php echo sln_locel_kses( $attrs['videoTitle'] ?? '' ); ?></p>
							<button class="video__play" type="button" data-lseo-ig-play aria-controls="<?php echo esc_attr( $play_id ); ?>" aria-label="<?php esc_attr_e( 'Play Instagram Reel', 'smart-leading-net' ); ?>">
								<?php echo sln_locel_icon( 'play' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</button>
						</div>
					</div>
				<?php else : ?>
					<div class="video__frame<?php echo $embed ? '' : ' is-placeholder'; ?>">
						<?php if ( $embed ) : ?>
							<?php echo $embed; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php else : ?>
							<span class="tag"><?php echo esc_html( $attrs['placeholderTag'] ?? 'Video placeholder' ); ?></span>
							<div class="video__inner">
								<p class="video__title"><?php echo sln_locel_kses( $attrs['videoTitle'] ?? '' ); ?></p>
								<span class="video__play" role="img" aria-label="<?php esc_attr_e( 'Video not supplied yet', 'smart-leading-net' ); ?>"><?php echo sln_locel_icon( 'play' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				<figcaption id="video-cap">
					<?php echo sln_locel_kses( $attrs['videoCaption'] ?? '' ); ?>
					<?php if ( $ig_perm ) : ?>
						<a class="video__watch" href="<?php echo esc_url( $ig_perm ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Watch on Instagram', 'smart-leading-net' ); ?></a>
					<?php endif; ?>
				</figcaption>
			</figure>
			<ol class="points"><?php echo sln_locel_render_named_inners( $block, 'smartleading/locel-seo-point' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></ol>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * Numbered point.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_locel_seo_point( $attrs ) {
	$wrapper = get_block_wrapper_attributes();
	ob_start();
	?>
	<li <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<span class="points__n" aria-hidden="true"><?php echo esc_html( $attrs['number'] ?? '' ); ?></span>
		<div>
			<h3><?php echo sln_locel_kses( $attrs['title'] ?? '' ); ?></h3>
			<p><?php echo sln_locel_kses( $attrs['text'] ?? '' ); ?></p>
		</div>
	</li>
	<?php
	return (string) ob_get_clean();
}

/**
 * Approach bento.
 *
 * @param array    $attrs Attributes.
 * @param string   $content Inner.
 * @param WP_Block $block Block.
 * @return string
 */
function sln_render_locel_seo_approach( $attrs, $content, $block ) {
	$tag     = sln_locel_heading_tag( $attrs['headingTag'] ?? 'h2' );
	$id      = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] : 'local-seo-approach';
	$hid     = $id . '-h';
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => 'lseo-sec',
			'id'              => $id,
			'aria-labelledby' => $hid,
		)
	);
	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="lseo-wrap">
			<div class="head">
				<div>
					<?php if ( ! empty( $attrs['eyebrow'] ) ) : ?>
						<p class="eyebrow"><?php echo sln_locel_kses( $attrs['eyebrow'] ); ?></p>
					<?php endif; ?>
					<<?php echo tag_escape( $tag ); ?> class="title" id="<?php echo esc_attr( $hid ); ?>"><?php echo sln_locel_kses( $attrs['heading'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
				</div>
				<p class="lead"><?php echo sln_locel_kses( $attrs['lead'] ?? '' ); ?></p>
			</div>
			<ul class="bento"><?php echo sln_locel_render_named_inners( $block, 'smartleading/locel-seo-tile' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></ul>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * Service tile.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_locel_seo_tile( $attrs ) {
	$wide  = ! empty( $attrs['wide'] );
	$blue  = ! empty( $attrs['blue'] );
	$mock  = $attrs['mock'] ?? 'none';
	$class = 'tile';
	if ( $wide ) {
		$class .= ' tile--wide';
	}
	if ( $blue ) {
		$class .= ' tile--blue';
	}
	$wrapper = get_block_wrapper_attributes( array( 'class' => $class ) );
	$body    = '<span class="tile__icon" aria-hidden="true">' . sln_locel_icon( $attrs['icon'] ?? 'shop' ) . '</span><h3>' . sln_locel_kses( $attrs['title'] ?? '' ) . '</h3><p>' . sln_locel_kses( $attrs['text'] ?? '' ) . '</p><p class="tile__focus"><b>Focus:</b> ' . sln_locel_kses( $attrs['focus'] ?? '' ) . '</p>';
	ob_start();
	?>
	<li <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<?php if ( $wide && 'none' !== $mock ) : ?>
			<div class="tile__body"><?php echo $body; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php echo sln_locel_tile_mock( $mock ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php else : ?>
			<?php echo $body; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php endif; ?>
	</li>
	<?php
	return (string) ob_get_clean();
}

/**
 * Ticket CTA.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_locel_seo_ticket( $attrs ) {
	$tag     = sln_locel_heading_tag( $attrs['headingTag'] ?? 'h2' );
	$id      = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] : 'local-seo-offer';
	$hid     = $id . '-h';
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => 'ticket-sec',
			'id'              => $id,
			'aria-labelledby' => $hid,
		)
	);
	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="lseo-wrap">
			<div class="ticket on-dark">
				<div class="ticket__main">
					<?php echo sln_locel_icon( 'audit' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<div>
						<<?php echo tag_escape( $tag ); ?> id="<?php echo esc_attr( $hid ); ?>"><?php echo sln_locel_kses( $attrs['heading'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
						<p><?php echo sln_locel_kses( $attrs['text'] ?? '' ); ?></p>
					</div>
				</div>
				<div class="ticket__cta">
					<?php
					echo sln_locel_render_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						array(
							'label'  => $attrs['primaryLabel'] ?? '',
							'url'    => $attrs['primaryUrl'] ?? '',
							'show'   => ! isset( $attrs['primaryShow'] ) || $attrs['primaryShow'],
							'newTab' => ! empty( $attrs['primaryNewTab'] ),
							'style'  => 'light',
							'arrow'  => true,
						)
					);
					?>
				</div>
			</div>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * Case study (demo).
 *
 * @param array    $attrs Attributes.
 * @param string   $content Inner.
 * @param WP_Block $block Block.
 * @return string
 */
function sln_render_locel_seo_case( $attrs, $content, $block ) {
	$tag     = sln_locel_heading_tag( $attrs['headingTag'] ?? 'h2' );
	$id      = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] : 'local-seo-case';
	$hid     = $id . '-h';
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => 'lseo-sec lseo-sec--warm',
			'id'              => $id,
			'aria-labelledby' => $hid,
		)
	);
	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="lseo-wrap">
			<?php if ( ! empty( $attrs['banner'] ) ) : ?>
				<p class="demo-banner" role="note"><?php echo sln_locel_icon( 'warn' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo sln_locel_kses( $attrs['banner'] ); ?></p>
			<?php endif; ?>
			<div class="head">
				<div>
					<?php if ( ! empty( $attrs['eyebrow'] ) ) : ?>
						<p class="eyebrow"><?php echo sln_locel_kses( $attrs['eyebrow'] ); ?></p>
					<?php endif; ?>
					<<?php echo tag_escape( $tag ); ?> class="title" id="<?php echo esc_attr( $hid ); ?>"><?php echo sln_locel_kses( $attrs['heading'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
				</div>
			</div>
			<div class="case">
				<ol class="story"><?php echo sln_locel_render_named_inners( $block, 'smartleading/locel-seo-story-item' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></ol>
				<div class="metric" role="group" aria-label="<?php echo esc_attr( $attrs['metricAria'] ?? '' ); ?>">
					<span class="tag"><?php echo esc_html( $attrs['metricTag'] ?? '' ); ?></span>
					<p class="metric__value"><?php echo esc_html( $attrs['metricFrom'] ?? '' ); ?><?php echo sln_locel_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span class="sr-only">to</span><?php echo esc_html( $attrs['metricTo'] ?? '' ); ?></p>
					<p class="metric__unit"><?php echo sln_locel_kses( $attrs['metricUnit'] ?? '' ); ?></p>
					<div class="metric__bars" aria-hidden="true"><span style="width:57%"></span><span style="width:100%"></span></div>
					<p class="metric__cap"><?php echo sln_locel_kses( $attrs['metricCap'] ?? '' ); ?></p>
					<p class="metric__logo"><?php echo sln_locel_kses( $attrs['metricLogo'] ?? '' ); ?></p>
				</div>
			</div>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * Story item.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_locel_seo_story_item( $attrs ) {
	$wrapper = get_block_wrapper_attributes();
	ob_start();
	?>
	<li <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<span class="story__label"><?php echo sln_locel_kses( $attrs['label'] ?? '' ); ?></span>
		<p><?php echo sln_locel_kses( $attrs['text'] ?? '' ); ?></p>
	</li>
	<?php
	return (string) ob_get_clean();
}

/**
 * Process stairs.
 *
 * @param array    $attrs Attributes.
 * @param string   $content Inner.
 * @param WP_Block $block Block.
 * @return string
 */
function sln_render_locel_seo_process( $attrs, $content, $block ) {
	$tag     = sln_locel_heading_tag( $attrs['headingTag'] ?? 'h2' );
	$id      = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] : 'local-seo-process';
	$hid     = $id . '-h';
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => 'lseo-sec',
			'id'              => $id,
			'aria-labelledby' => $hid,
		)
	);
	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="lseo-wrap">
			<div class="head">
				<div>
					<?php if ( ! empty( $attrs['eyebrow'] ) ) : ?>
						<p class="eyebrow"><?php echo sln_locel_kses( $attrs['eyebrow'] ); ?></p>
					<?php endif; ?>
					<<?php echo tag_escape( $tag ); ?> class="title" id="<?php echo esc_attr( $hid ); ?>"><?php echo sln_locel_kses( $attrs['heading'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
				</div>
				<p class="lead"><?php echo sln_locel_kses( $attrs['lead'] ?? '' ); ?></p>
			</div>
			<ol class="stairs"><?php echo sln_locel_render_named_inners( $block, 'smartleading/locel-seo-stair' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></ol>
			<?php if ( ! empty( $attrs['note'] ) ) : ?>
				<p class="foot-note"><?php echo sln_locel_icon( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo sln_locel_kses( $attrs['note'] ); ?></p>
			<?php endif; ?>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * Stair step.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_locel_seo_stair( $attrs ) {
	$wrapper = get_block_wrapper_attributes( array( 'class' => 'stair card' ) );
	ob_start();
	?>
	<li <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<span class="stair__n" aria-hidden="true"><?php echo esc_html( $attrs['number'] ?? '' ); ?></span>
		<h3><?php echo sln_locel_kses( $attrs['title'] ?? '' ); ?></h3>
		<p><?php echo sln_locel_kses( $attrs['text'] ?? '' ); ?></p>
	</li>
	<?php
	return (string) ob_get_clean();
}

/**
 * Reporting.
 *
 * @param array    $attrs Attributes.
 * @param string   $content Inner.
 * @param WP_Block $block Block.
 * @return string
 */
function sln_render_locel_seo_reporting( $attrs, $content, $block ) {
	$tag     = sln_locel_heading_tag( $attrs['headingTag'] ?? 'h2' );
	$id      = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] : 'local-seo-reporting';
	$hid     = $id . '-h';
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => 'lseo-sec lseo-sec--soft',
			'id'              => $id,
			'aria-labelledby' => $hid,
		)
	);
	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="lseo-wrap report">
			<div>
				<?php if ( ! empty( $attrs['eyebrow'] ) ) : ?>
					<p class="eyebrow"><?php echo sln_locel_kses( $attrs['eyebrow'] ); ?></p>
				<?php endif; ?>
				<<?php echo tag_escape( $tag ); ?> class="title" id="<?php echo esc_attr( $hid ); ?>"><?php echo sln_locel_kses( $attrs['heading'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
				<p class="lead"><?php echo sln_locel_kses( $attrs['lead'] ?? '' ); ?></p>
				<ol class="funnel"><?php echo sln_locel_render_named_inners( $block, 'smartleading/locel-seo-funnel-step' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></ol>
			</div>
			<figure class="crm" aria-label="<?php echo esc_attr( $attrs['crmAria'] ?? '' ); ?>">
				<p class="crm__note"><?php echo sln_locel_icon( 'warn' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo sln_locel_kses( $attrs['crmNote'] ?? '' ); ?></p>
				<div class="crm__body">
					<div class="crm__top">
						<span class="crm__brand"><?php echo esc_html( $attrs['crmBrand'] ?? 'SLS Leads CRM' ); ?></span>
						<ul class="crm__tabs">
							<li><?php echo esc_html( $attrs['tab1'] ?? 'Visibility' ); ?></li>
							<li class="is-on"><?php echo esc_html( $attrs['tab2'] ?? 'Enquiries' ); ?></li>
							<li><?php echo esc_html( $attrs['tab3'] ?? 'Outcomes' ); ?></li>
						</ul>
					</div>
					<div class="crm__kpis">
						<div class="crm__kpi"><span><?php echo esc_html( $attrs['kpi1'] ?? 'Visibility' ); ?></span><i></i></div>
						<div class="crm__kpi"><span><?php echo esc_html( $attrs['kpi2'] ?? 'Recorded enquiries' ); ?></span><i></i></div>
						<div class="crm__kpi"><span><?php echo esc_html( $attrs['kpi3'] ?? 'Qualified' ); ?></span><i></i></div>
					</div>
					<div class="crm__chart" aria-hidden="true"><span style="height:22%"></span><span style="height:34%"></span><span style="height:28%"></span><span style="height:46%"></span><span style="height:40%"></span><span style="height:58%"></span><span style="height:52%"></span><span style="height:70%"></span><span style="height:64%"></span><span style="height:82%"></span></div>
					<table>
						<thead><tr><th scope="col"><?php echo esc_html( $attrs['col1'] ?? 'Source' ); ?></th><th scope="col"><?php echo esc_html( $attrs['col2'] ?? 'Enquiry' ); ?></th><th scope="col"><?php echo esc_html( $attrs['col3'] ?? 'Status' ); ?></th></tr></thead>
						<tbody>
							<tr><td><?php echo esc_html( $attrs['row1Source'] ?? 'Call' ); ?></td><td><i></i></td><td><span class="pill pill--new"><?php echo esc_html( $attrs['row1Status'] ?? 'New' ); ?></span></td></tr>
							<tr><td><?php echo esc_html( $attrs['row2Source'] ?? 'Form' ); ?></td><td><i></i></td><td><span class="pill pill--ok"><?php echo esc_html( $attrs['row2Status'] ?? 'Qualified' ); ?></span></td></tr>
							<tr><td><?php echo esc_html( $attrs['row3Source'] ?? 'Quote request' ); ?></td><td><i></i></td><td><span class="pill pill--no"><?php echo esc_html( $attrs['row3Status'] ?? 'Not a fit' ); ?></span></td></tr>
						</tbody>
					</table>
				</div>
			</figure>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * Funnel step.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_locel_seo_funnel_step( $attrs ) {
	$wrapper = get_block_wrapper_attributes();
	ob_start();
	?>
	<li <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<span class="funnel__icon" aria-hidden="true"><?php echo sln_locel_icon( $attrs['icon'] ?? 'eye' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		<div>
			<h3><?php echo sln_locel_kses( $attrs['title'] ?? '' ); ?></h3>
			<p><?php echo sln_locel_kses( $attrs['text'] ?? '' ); ?></p>
			<span class="funnel__q"><?php echo sln_locel_kses( $attrs['question'] ?? '' ); ?></span>
		</div>
	</li>
	<?php
	return (string) ob_get_clean();
}

/**
 * Technology / CRM slider.
 *
 * @param array    $attrs Attributes.
 * @param string   $content Inner.
 * @param WP_Block $block Block.
 * @return string
 */
function sln_render_locel_seo_tech( $attrs, $content, $block ) {
	$tag     = sln_locel_heading_tag( $attrs['headingTag'] ?? 'h2' );
	$id      = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] : 'local-seo-technology';
	$hid     = $id . '-h';
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => 'lseo-sec',
			'id'              => $id,
			'aria-labelledby' => $hid,
		)
	);
	$slides = sln_locel_render_named_inners( $block, 'smartleading/locel-seo-slide' );
	$stats  = sln_locel_render_named_inners( $block, 'smartleading/locel-seo-stat' );
	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="lseo-wrap">
			<div class="head">
				<div>
					<?php if ( ! empty( $attrs['eyebrow'] ) ) : ?>
						<p class="eyebrow"><?php echo sln_locel_kses( $attrs['eyebrow'] ); ?></p>
					<?php endif; ?>
					<<?php echo tag_escape( $tag ); ?> class="title" id="<?php echo esc_attr( $hid ); ?>"><?php echo sln_locel_kses( $attrs['heading'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
				</div>
				<div class="tech-side">
					<p class="lead"><?php echo sln_locel_kses( $attrs['lead'] ?? '' ); ?></p>
					<div><span class="tag"><?php echo esc_html( $attrs['conceptTag'] ?? '' ); ?></span></div>
					<p class="tech-link"><span><?php echo sln_locel_kses( $attrs['linkLabel'] ?? '' ); ?></span><span class="tbc"><?php echo sln_locel_kses( $attrs['linkPending'] ?? '' ); ?></span></p>
				</div>
			</div>
			<div class="slider" data-lseo-slider role="region" aria-roledescription="carousel" aria-label="<?php echo esc_attr( $attrs['sliderLabel'] ?? '' ); ?>">
				<ul class="slider__track" data-lseo-track><?php echo $slides; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></ul>
			</div>
			<div class="slider__bar">
				<div class="slider__left">
					<p class="slider__count" aria-live="polite"><?php esc_html_e( 'Feature', 'smart-leading-net' ); ?> <span data-lseo-count>1</span> <?php esc_html_e( 'of', 'smart-leading-net' ); ?> <span data-lseo-total>4</span></p>
					<div class="slider__dots" data-lseo-dots aria-hidden="true"></div>
				</div>
				<div class="slider__btns">
					<button type="button" data-lseo-prev aria-label="<?php esc_attr_e( 'Previous feature', 'smart-leading-net' ); ?>"><?php echo sln_locel_icon( 'prev' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
					<button type="button" data-lseo-next aria-label="<?php esc_attr_e( 'Next feature', 'smart-leading-net' ); ?>"><?php echo sln_locel_icon( 'next' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
				</div>
			</div>
			<ul class="stats" style="margin-top:44px"><?php echo $stats; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></ul>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * CRM slide.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_locel_seo_slide( $attrs ) {
	$wrapper = get_block_wrapper_attributes( array( 'class' => 'slide' ) );
	$items   = array_filter(
		array(
			$attrs['item1'] ?? '',
			$attrs['item2'] ?? '',
			$attrs['item3'] ?? '',
		)
	);
	ob_start();
	?>
	<li <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> role="group" aria-roledescription="slide">
		<div>
			<span class="tag"><?php echo esc_html( $attrs['tag'] ?? 'Concept' ); ?></span>
			<h3><?php echo sln_locel_kses( $attrs['title'] ?? '' ); ?></h3>
			<p><?php echo sln_locel_kses( $attrs['text'] ?? '' ); ?></p>
			<?php if ( $items ) : ?>
				<ul>
					<?php foreach ( $items as $item ) : ?>
						<li><?php echo sln_locel_kses( $item ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
		<div class="slide__mock" aria-hidden="true"><div><span></span><span></span><span></span></div><span></span><span></span></div>
	</li>
	<?php
	return (string) ob_get_clean();
}

/**
 * Placeholder statistic.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_locel_seo_stat( $attrs ) {
	$wrapper = get_block_wrapper_attributes();
	ob_start();
	?>
	<li <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<span class="tbc"><?php echo sln_locel_kses( $attrs['value'] ?? '[Stat]' ); ?></span>
		<strong><?php echo sln_locel_kses( $attrs['label'] ?? '' ); ?></strong>
		<span class="stats__note"><?php echo sln_locel_kses( $attrs['note'] ?? '' ); ?></span>
	</li>
	<?php
	return (string) ob_get_clean();
}

/**
 * Working with SLS.
 *
 * @param array    $attrs Attributes.
 * @param string   $content Inner.
 * @param WP_Block $block Block.
 * @return string
 */
function sln_render_locel_seo_working( $attrs, $content, $block ) {
	$tag     = sln_locel_heading_tag( $attrs['headingTag'] ?? 'h2' );
	$id      = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] : 'local-seo-working';
	$hid     = $id . '-h';
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => 'lseo-sec lseo-sec--soft',
			'id'              => $id,
			'aria-labelledby' => $hid,
		)
	);
	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="lseo-wrap">
			<div class="head head--center">
				<?php if ( ! empty( $attrs['eyebrow'] ) ) : ?>
					<p class="eyebrow eyebrow--center"><?php echo sln_locel_kses( $attrs['eyebrow'] ); ?></p>
				<?php endif; ?>
				<<?php echo tag_escape( $tag ); ?> class="title" id="<?php echo esc_attr( $hid ); ?>"><?php echo sln_locel_kses( $attrs['heading'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
			</div>
			<svg class="quote-mark" viewBox="0 0 54 42" aria-hidden="true"><path fill="currentColor" d="M0 42V27C0 12.6 6.6 3.6 19.8 0l3 5.4C15.6 8.4 12.3 13.2 12 20.4h10.8V42zm31.2 0V27c0-14.4 6.6-23.4 19.8-27l3 5.4c-7.2 3-10.5 7.8-10.8 15H54V42z"></path></svg>
			<p class="statement"><?php echo sln_locel_kses( $attrs['statement'] ?? '' ); ?></p>
			<ul class="values"><?php echo sln_locel_render_named_inners( $block, 'smartleading/locel-seo-value' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></ul>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * Value column.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_locel_seo_value( $attrs ) {
	$wrapper = get_block_wrapper_attributes( array( 'class' => 'value' ) );
	ob_start();
	?>
	<li <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<span class="value__icon" aria-hidden="true"><?php echo sln_locel_icon( $attrs['icon'] ?? 'priorities' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
		<h3><?php echo sln_locel_kses( $attrs['title'] ?? '' ); ?></h3>
		<p><?php echo sln_locel_kses( $attrs['text'] ?? '' ); ?></p>
	</li>
	<?php
	return (string) ob_get_clean();
}

/**
 * Comparison table.
 *
 * @param array    $attrs Attributes.
 * @param string   $content Inner.
 * @param WP_Block $block Block.
 * @return string
 */
function sln_render_locel_seo_compare( $attrs, $content, $block ) {
	$tag     = sln_locel_heading_tag( $attrs['headingTag'] ?? 'h2' );
	$id      = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] : 'local-seo-compare';
	$hid     = $id . '-h';
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => 'lseo-sec',
			'id'              => $id,
			'aria-labelledby' => $hid,
		)
	);
	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="lseo-wrap">
			<div class="head head--center">
				<?php if ( ! empty( $attrs['eyebrow'] ) ) : ?>
					<p class="eyebrow eyebrow--center"><?php echo sln_locel_kses( $attrs['eyebrow'] ); ?></p>
				<?php endif; ?>
				<<?php echo tag_escape( $tag ); ?> class="title" id="<?php echo esc_attr( $hid ); ?>"><?php echo sln_locel_kses( $attrs['heading'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
				<p class="lead"><?php echo sln_locel_kses( $attrs['lead'] ?? '' ); ?></p>
			</div>
			<table class="options">
				<caption class="sr-only"><?php echo esc_html( $attrs['caption'] ?? '' ); ?></caption>
				<thead>
					<tr>
						<td></td>
						<th scope="col" class="is-sls"><span class="options__name"><?php echo esc_html( $attrs['col1Name'] ?? '' ); ?></span><span class="options__sub"><?php echo esc_html( $attrs['col1Sub'] ?? '' ); ?></span></th>
						<th scope="col"><span class="options__name"><?php echo esc_html( $attrs['col2Name'] ?? '' ); ?></span><span class="options__sub"><?php echo esc_html( $attrs['col2Sub'] ?? '' ); ?></span></th>
						<th scope="col"><span class="options__name"><?php echo esc_html( $attrs['col3Name'] ?? '' ); ?></span><span class="options__sub"><?php echo esc_html( $attrs['col3Sub'] ?? '' ); ?></span></th>
					</tr>
				</thead>
				<tbody><?php echo sln_locel_render_named_inners( $block, 'smartleading/locel-seo-compare-row' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></tbody>
			</table>
			<?php if ( ! empty( $attrs['foot'] ) ) : ?>
				<p class="options-foot"><?php echo sln_locel_kses( $attrs['foot'] ); ?></p>
			<?php endif; ?>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * Comparison row.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_locel_seo_compare_row( $attrs ) {
	$wrapper = get_block_wrapper_attributes();
	ob_start();
	?>
	<tr <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<th scope="row"><?php echo sln_locel_kses( $attrs['label'] ?? '' ); ?></th>
		<td class="is-sls" data-label="<?php echo esc_attr( $attrs['col1Label'] ?? 'Working with SLS' ); ?>"><div class="cell"><?php echo sln_locel_icon( 'tick' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo sln_locel_kses( $attrs['sls'] ?? '' ); ?></span></div></td>
		<td data-label="<?php echo esc_attr( $attrs['col2Label'] ?? 'Another SEO agency' ); ?>"><div class="cell cell--dot"><?php echo sln_locel_icon( 'dot' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo sln_locel_kses( $attrs['agency'] ?? '' ); ?></span></div></td>
		<td data-label="<?php echo esc_attr( $attrs['col3Label'] ?? 'In-house or DIY' ); ?>"><div class="cell cell--dot"><?php echo sln_locel_icon( 'dot' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo sln_locel_kses( $attrs['diy'] ?? '' ); ?></span></div></td>
	</tr>
	<?php
	return (string) ob_get_clean();
}

/**
 * FAQ.
 *
 * @param array    $attrs Attributes.
 * @param string   $content Inner.
 * @param WP_Block $block Block.
 * @return string
 */
function sln_render_locel_seo_faq( $attrs, $content, $block ) {
	$tag     = sln_locel_heading_tag( $attrs['headingTag'] ?? 'h2' );
	$id      = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] : 'local-seo-faq';
	$hid     = $id . '-h';
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => 'lseo-sec lseo-sec--faq',
			'id'              => $id,
			'aria-labelledby' => $hid,
		)
	);
	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="lseo-wrap">
			<div class="head head--center">
				<?php if ( ! empty( $attrs['eyebrow'] ) ) : ?>
					<p class="eyebrow eyebrow--center"><?php echo sln_locel_kses( $attrs['eyebrow'] ); ?></p>
				<?php endif; ?>
				<<?php echo tag_escape( $tag ); ?> class="title" id="<?php echo esc_attr( $hid ); ?>"><?php echo sln_locel_kses( $attrs['heading'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
				<p class="lead"><?php echo sln_locel_kses( $attrs['lead'] ?? '' ); ?></p>
			</div>
			<div class="faq">
				<div class="faq-list" data-lseo-faq><?php echo sln_locel_render_named_inners( $block, 'smartleading/locel-seo-faq-item' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			</div>
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
function sln_render_locel_seo_faq_item( $attrs ) {
	$wrapper = get_block_wrapper_attributes( array( 'class' => 'faq-item' ) );
	$uid     = wp_unique_id( 'lseo-faq-' );
	ob_start();
	?>
	<div <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<h3>
			<button class="faq-q" type="button" id="<?php echo esc_attr( $uid ); ?>-q" aria-expanded="false" aria-controls="<?php echo esc_attr( $uid ); ?>-a">
				<?php echo sln_locel_kses( $attrs['question'] ?? '' ); ?>
				<span class="faq-ic" aria-hidden="true"><?php echo sln_locel_icon( 'plus' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
			</button>
		</h3>
		<div class="faq-a" id="<?php echo esc_attr( $uid ); ?>-a" role="region" aria-labelledby="<?php echo esc_attr( $uid ); ?>-q" aria-hidden="true">
			<div class="faq-a__in"><p><?php echo sln_locel_kses( $attrs['answer'] ?? '' ); ?></p></div>
		</div>
	</div>
	<?php
	return (string) ob_get_clean();
}

/**
 * Audit form.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_locel_seo_audit( $attrs ) {
	$tag     = sln_locel_heading_tag( $attrs['headingTag'] ?? 'h2' );
	$id      = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] : 'local-seo-audit-request';
	$hid     = $id . '-h';
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => 'lseo-sec audit on-dark',
			'id'              => $id,
			'aria-labelledby' => $hid,
		)
	);
	$uid = wp_unique_id( 'lseo-f-' );
	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<svg class="audit__map" viewBox="0 0 1200 700" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
			<g fill="none" stroke="#fff" stroke-linecap="round">
				<path d="M-20 110H1220M-20 360H1220M-20 610H1220M150 -20V720M480 -20V720M800 -20V720M1080 -20V720" stroke-width="3"></path>
				<path d="M-20 230H1220M-20 480H1220M320 -20V720M640 -20V720M950 -20V720" stroke-width="9"></path>
				<path d="M-40 700 1240 20" stroke-width="12"></path>
				<circle cx="880" cy="170" r="60" stroke-width="2" stroke-dasharray="6 8"></circle><circle cx="880" cy="170" r="120" stroke-width="2" stroke-dasharray="6 8"></circle>
			</g>
		</svg>
		<div class="lseo-wrap">
			<div>
				<?php if ( ! empty( $attrs['eyebrow'] ) ) : ?>
					<p class="eyebrow"><?php echo sln_locel_kses( $attrs['eyebrow'] ); ?></p>
				<?php endif; ?>
				<<?php echo tag_escape( $tag ); ?> class="title" id="<?php echo esc_attr( $hid ); ?>"><?php echo sln_locel_kses( $attrs['heading'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
				<p class="lead"><?php echo sln_locel_kses( $attrs['lead'] ?? '' ); ?></p>
				<ul class="ticks">
					<?php if ( ! empty( $attrs['tick1'] ) ) : ?>
						<li><?php echo sln_locel_icon( 'tick' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo sln_locel_kses( $attrs['tick1'] ); ?></li>
					<?php endif; ?>
					<?php if ( ! empty( $attrs['tick2'] ) ) : ?>
						<li><?php echo sln_locel_icon( 'tick' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo sln_locel_kses( $attrs['tick2'] ); ?></li>
					<?php endif; ?>
				</ul>
			</div>
			<form class="form-card" data-lseo-audit novalidate>
				<div class="lseo-hp" aria-hidden="true">
					<label for="<?php echo esc_attr( $uid ); ?>-hp"><?php esc_html_e( 'Leave this field empty', 'smart-leading-net' ); ?></label>
					<input id="<?php echo esc_attr( $uid ); ?>-hp" type="text" name="company_url" value="" tabindex="-1" autocomplete="off">
				</div>
				<div class="form-grid">
					<div class="field">
						<label for="<?php echo esc_attr( $uid ); ?>-name"><?php echo esc_html( $attrs['nameLabel'] ?? 'Full name' ); ?></label>
						<input class="input" id="<?php echo esc_attr( $uid ); ?>-name" name="name" type="text" autocomplete="name" required>
						<span class="error" data-error-for="name" aria-live="polite"></span>
					</div>
					<div class="field">
						<label for="<?php echo esc_attr( $uid ); ?>-email"><?php echo esc_html( $attrs['emailLabel'] ?? 'Business email' ); ?></label>
						<input class="input" id="<?php echo esc_attr( $uid ); ?>-email" name="email" type="email" autocomplete="email" required>
						<span class="error" data-error-for="email" aria-live="polite"></span>
					</div>
					<div class="field">
						<label for="<?php echo esc_attr( $uid ); ?>-website"><?php echo esc_html( $attrs['websiteLabel'] ?? 'Website' ); ?><span><?php echo esc_html( $attrs['optionalLabel'] ?? '(optional)' ); ?></span></label>
						<input class="input" id="<?php echo esc_attr( $uid ); ?>-website" name="website" type="text" inputmode="url" autocomplete="url" placeholder="https://">
						<span class="hint"><?php echo esc_html( $attrs['websiteHint'] ?? '' ); ?></span>
						<span class="error" data-error-for="website" aria-live="polite"></span>
					</div>
					<div class="field">
						<label for="<?php echo esc_attr( $uid ); ?>-area"><?php echo esc_html( $attrs['areaLabel'] ?? 'City or service area' ); ?></label>
						<input class="input" id="<?php echo esc_attr( $uid ); ?>-area" name="area" type="text" autocomplete="address-level2" required>
						<span class="error" data-error-for="area" aria-live="polite"></span>
					</div>
					<div class="field field--full">
						<label for="<?php echo esc_attr( $uid ); ?>-extra"><?php echo esc_html( $attrs['extraLabel'] ?? '' ); ?><span><?php echo esc_html( $attrs['optionalLabel'] ?? '(optional)' ); ?></span></label>
						<textarea class="input" id="<?php echo esc_attr( $uid ); ?>-extra" name="extra"></textarea>
					</div>
				</div>
				<p class="privacy"><?php echo sln_locel_kses( $attrs['privacyText'] ?? '' ); ?> <a href="<?php echo esc_url( $attrs['privacyUrl'] ?? home_url( '/privacy-policy/' ) ); ?>"><?php echo esc_html( $attrs['privacyLinkLabel'] ?? 'Privacy Policy' ); ?></a>.</p>
				<?php
				echo sln_locel_render_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					array(
						'label'  => $attrs['buttonLabel'] ?? 'Request My Free Audit',
						'style'  => 'primary',
						'arrow'  => true,
						'block'  => true,
						'button' => true,
					)
				);
				?>
				<p class="status" data-lseo-status role="status" hidden><?php echo sln_locel_icon( 'info' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span></span></p>
			</form>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}
