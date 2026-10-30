<?php
/**
 * Frontend render callbacks for Founders Behind blocks.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hero.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_founders_hero( $attrs ) {
	if ( ! sln_founders_is_public( $attrs ) ) {
		return '';
	}
	$tag     = sln_founders_heading_tag( $attrs['headingTag'] ?? 'h1', 'h1' );
	$id      = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] : 'founders-hero';
	$hid     = $id . '-title';
	$image   = sln_founders_image( $attrs, 'large' );
	$has_img = '' !== $image;
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => 'sf-section sf-hero' . ( $has_img ? ' sf-hero--has-media' : ' sf-hero--text' ),
			'id'              => $id,
			'aria-labelledby' => $hid,
		)
	);
	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="sf-wrap sf-hero__grid">
			<div class="sf-hero__copy">
				<?php if ( ! empty( $attrs['eyebrow'] ) ) : ?>
					<p class="sf-eyebrow"><?php echo sln_founders_kses( $attrs['eyebrow'] ); ?></p>
				<?php endif; ?>
				<<?php echo tag_escape( $tag ); ?> class="sf-hero__title" id="<?php echo esc_attr( $hid ); ?>"><?php echo sln_founders_kses( $attrs['heading'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
				<?php if ( ! empty( $attrs['lead'] ) ) : ?>
					<p class="sf-lead"><?php echo sln_founders_kses( $attrs['lead'] ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $attrs['note'] ) ) : ?>
					<p class="sf-hero__note"><?php echo sln_founders_kses( $attrs['note'] ); ?></p>
				<?php endif; ?>
				<div class="sf-actions">
					<?php
					echo sln_founders_render_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						array(
							'label'  => $attrs['primaryLabel'] ?? '',
							'url'    => $attrs['primaryUrl'] ?? '',
							'show'   => $attrs['primaryShow'] ?? true,
							'newTab' => $attrs['primaryNewTab'] ?? false,
							'style'  => 'primary',
						)
					);
					echo sln_founders_render_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						array(
							'label'  => $attrs['secondaryLabel'] ?? '',
							'url'    => $attrs['secondaryUrl'] ?? '',
							'show'   => $attrs['secondaryShow'] ?? true,
							'newTab' => $attrs['secondaryNewTab'] ?? false,
							'style'  => 'outline',
						)
					);
					?>
				</div>
			</div>
			<?php if ( $has_img ) : ?>
				<div class="sf-hero__media">
					<figure class="sf-portrait sf-portrait--wide">
						<?php echo $image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</figure>
				</div>
			<?php endif; ?>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * Profiles section.
 *
 * @param array    $attrs Attributes.
 * @param string   $content Inner.
 * @param WP_Block $block Block.
 * @return string
 */
function sln_render_founders_profiles( $attrs, $content, $block ) {
	if ( ! sln_founders_is_public( $attrs ) ) {
		return '';
	}
	$tag     = sln_founders_heading_tag( $attrs['headingTag'] ?? 'h2' );
	$id      = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] : 'founders';
	$hid     = $id . '-title';
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => 'sf-section sf-bg-white',
			'id'              => $id,
			'aria-labelledby' => $hid,
		)
	);
	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="sf-wrap">
			<div class="sf-section-head sf-read">
				<?php if ( ! empty( $attrs['eyebrow'] ) ) : ?>
					<p class="sf-eyebrow"><?php echo sln_founders_kses( $attrs['eyebrow'] ); ?></p>
				<?php endif; ?>
				<<?php echo tag_escape( $tag ); ?> class="sf-section-title" id="<?php echo esc_attr( $hid ); ?>"><?php echo sln_founders_kses( $attrs['heading'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
			</div>
			<?php echo sln_founders_render_named_inners( $block, 'smartleading/founders-profile' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * Single founder profile.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_founders_profile( $attrs ) {
	$name    = $attrs['name'] ?? '';
	$uid     = 'sf-founder-' . sanitize_title( $name ? $name : wp_unique_id( 'f' ) );
	$image   = sln_founders_image( $attrs, 'large' );
	$reverse = ! empty( $attrs['reverse'] );
	$class   = 'sf-founder';
	if ( $reverse ) {
		$class .= ' sf-founder--reverse';
	}
	if ( '' === $image ) {
		$class .= ' sf-founder--no-media';
	}
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => $class,
			'aria-labelledby' => $uid,
		)
	);
	$roles = array_filter(
		array(
			trim( (string) ( $attrs['role1'] ?? '' ) ),
			trim( (string) ( $attrs['role2'] ?? '' ) ),
			trim( (string) ( $attrs['role3'] ?? '' ) ),
		)
	);
	ob_start();
	?>
	<article <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<?php if ( $image ) : ?>
		<div class="sf-founder__media">
			<figure class="sf-portrait">
				<?php echo $image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</figure>
		</div>
		<?php endif; ?>
		<div class="sf-founder__body">
			<h3 id="<?php echo esc_attr( $uid ); ?>"><?php echo sln_founders_kses( $name ); ?></h3>
			<?php if ( $roles ) : ?>
				<p class="sf-founder__role">
					<?php foreach ( $roles as $role ) : ?>
						<span><?php echo esc_html( $role ); ?></span>
					<?php endforeach; ?>
				</p>
			<?php endif; ?>
			<?php if ( ! empty( $attrs['bio'] ) ) : ?>
				<div class="sf-founder__bio"><p><?php echo sln_founders_kses( $attrs['bio'] ); ?></p></div>
			<?php endif; ?>
			<?php if ( ! empty( $attrs['extra'] ) ) : ?>
				<div class="sf-founder__extra"><p><?php echo sln_founders_kses( $attrs['extra'] ); ?></p></div>
			<?php endif; ?>
			<?php if ( ! empty( $attrs['socialUrl'] ) && ! empty( $attrs['socialLabel'] ) ) : ?>
				<a class="sf-founder__link" href="<?php echo esc_url( $attrs['socialUrl'] ); ?>"<?php echo ! empty( $attrs['socialNewTab'] ) ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
					<?php echo sln_founders_linkedin_icon(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span><?php echo esc_html( $attrs['socialLabel'] ); ?></span>
					<?php if ( ! empty( $attrs['socialNewTab'] ) ) : ?>
						<span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'smart-leading-net' ); ?></span>
					<?php endif; ?>
				</a>
			<?php endif; ?>
		</div>
	</article>
	<?php
	return (string) ob_get_clean();
}

/**
 * Origin story.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_founders_origin( $attrs ) {
	if ( ! sln_founders_is_public( $attrs ) ) {
		return '';
	}
	$tag     = sln_founders_heading_tag( $attrs['headingTag'] ?? 'h2' );
	$id      = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] : 'founders-origin';
	$hid     = $id . '-title';
	$image   = sln_founders_image( $attrs, 'large' );
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => 'sf-section sf-bg-blue',
			'id'              => $id,
			'aria-labelledby' => $hid,
		)
	);
	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="sf-wrap sf-origin__grid">
			<div class="sf-read">
				<?php if ( ! empty( $attrs['eyebrow'] ) ) : ?>
					<p class="sf-eyebrow"><?php echo sln_founders_kses( $attrs['eyebrow'] ); ?></p>
				<?php endif; ?>
				<<?php echo tag_escape( $tag ); ?> class="sf-section-title" id="<?php echo esc_attr( $hid ); ?>"><?php echo sln_founders_kses( $attrs['heading'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
				<?php if ( ! empty( $attrs['story'] ) ) : ?>
					<div class="sf-origin__story"><?php echo wp_kses_post( wpautop( wp_strip_all_tags( $attrs['story'] ) ) ); ?></div>
				<?php endif; ?>
			</div>
			<?php if ( $image ) : ?>
				<figure class="sf-portrait sf-portrait--wide">
					<?php echo $image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</figure>
			<?php endif; ?>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * Complementary roles.
 *
 * @param array    $attrs Attributes.
 * @param string   $content Inner.
 * @param WP_Block $block Block.
 * @return string
 */
function sln_render_founders_roles( $attrs, $content, $block ) {
	if ( ! sln_founders_is_public( $attrs ) ) {
		return '';
	}
	$tag     = sln_founders_heading_tag( $attrs['headingTag'] ?? 'h2' );
	$id      = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] : 'founders-roles';
	$hid     = $id . '-title';
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => 'sf-section sf-bg-white',
			'id'              => $id,
			'aria-labelledby' => $hid,
		)
	);
	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="sf-wrap">
			<div class="sf-section-head sf-read">
				<?php if ( ! empty( $attrs['eyebrow'] ) ) : ?>
					<p class="sf-eyebrow"><?php echo sln_founders_kses( $attrs['eyebrow'] ); ?></p>
				<?php endif; ?>
				<<?php echo tag_escape( $tag ); ?> class="sf-section-title" id="<?php echo esc_attr( $hid ); ?>"><?php echo sln_founders_kses( $attrs['heading'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
				<?php if ( ! empty( $attrs['lead'] ) ) : ?>
					<p class="sf-lead"><?php echo sln_founders_kses( $attrs['lead'] ); ?></p>
				<?php endif; ?>
			</div>
			<div class="sf-roles"><?php echo sln_founders_render_named_inners( $block, 'smartleading/founders-role' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php if ( ! empty( $attrs['shared'] ) ) : ?>
				<p class="sf-roles__shared"><?php echo sln_founders_kses( $attrs['shared'] ); ?></p>
			<?php endif; ?>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * Role card.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_founders_role( $attrs ) {
	$wrapper = get_block_wrapper_attributes( array( 'class' => 'sf-role' ) );
	ob_start();
	?>
	<div <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<?php if ( ! empty( $attrs['name'] ) ) : ?>
			<p class="sf-role__name"><?php echo sln_founders_kses( $attrs['name'] ); ?></p>
		<?php endif; ?>
		<?php if ( ! empty( $attrs['role'] ) ) : ?>
			<h3><?php echo sln_founders_kses( $attrs['role'] ); ?></h3>
		<?php endif; ?>
		<?php if ( ! empty( $attrs['text'] ) ) : ?>
			<p><?php echo sln_founders_kses( $attrs['text'] ); ?></p>
		<?php endif; ?>
	</div>
	<?php
	return (string) ob_get_clean();
}

/**
 * Principles.
 *
 * @param array    $attrs Attributes.
 * @param string   $content Inner.
 * @param WP_Block $block Block.
 * @return string
 */
function sln_render_founders_principles( $attrs, $content, $block ) {
	if ( ! sln_founders_is_public( $attrs ) ) {
		return '';
	}
	$tag     = sln_founders_heading_tag( $attrs['headingTag'] ?? 'h2' );
	$id      = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] : 'founders-principles';
	$hid     = $id . '-title';
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => 'sf-section sf-bg-orange',
			'id'              => $id,
			'aria-labelledby' => $hid,
		)
	);
	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="sf-wrap">
			<div class="sf-section-head sf-read">
				<?php if ( ! empty( $attrs['eyebrow'] ) ) : ?>
					<p class="sf-eyebrow"><?php echo sln_founders_kses( $attrs['eyebrow'] ); ?></p>
				<?php endif; ?>
				<<?php echo tag_escape( $tag ); ?> class="sf-section-title" id="<?php echo esc_attr( $hid ); ?>"><?php echo sln_founders_kses( $attrs['heading'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
			</div>
			<div class="sf-principles"><?php echo sln_founders_render_named_inners( $block, 'smartleading/founders-principle' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}

/**
 * Principle card.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_founders_principle( $attrs ) {
	$wrapper = get_block_wrapper_attributes( array( 'class' => 'sf-principle' ) );
	ob_start();
	?>
	<div <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<?php if ( ! empty( $attrs['heading'] ) ) : ?>
			<h3><?php echo sln_founders_kses( $attrs['heading'] ); ?></h3>
		<?php endif; ?>
		<?php if ( ! empty( $attrs['text'] ) ) : ?>
			<p><?php echo sln_founders_kses( $attrs['text'] ); ?></p>
		<?php endif; ?>
	</div>
	<?php
	return (string) ob_get_clean();
}

/**
 * Closing CTA.
 *
 * @param array $attrs Attributes.
 * @return string
 */
function sln_render_founders_cta( $attrs ) {
	if ( ! sln_founders_is_public( $attrs ) ) {
		return '';
	}
	$tag     = sln_founders_heading_tag( $attrs['headingTag'] ?? 'h2' );
	$id      = ! empty( $attrs['anchor'] ) ? $attrs['anchor'] : 'founders-close';
	$hid     = $id . '-title';
	$wrapper = get_block_wrapper_attributes(
		array(
			'class'           => 'sf-section sf-bg-white',
			'id'              => $id,
			'aria-labelledby' => $hid,
		)
	);
	$sec_url   = $attrs['secondaryUrl'] ?? '';
	$sec_label = $attrs['secondaryLabel'] ?? '';
	$sec_show  = ! isset( $attrs['secondaryShow'] ) || $attrs['secondaryShow'];
	ob_start();
	?>
	<section <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<div class="sf-wrap">
			<div class="sf-close">
				<div>
					<<?php echo tag_escape( $tag ); ?> class="sf-close__title" id="<?php echo esc_attr( $hid ); ?>"><?php echo sln_founders_kses( $attrs['heading'] ?? '' ); ?></<?php echo tag_escape( $tag ); ?>>
					<?php if ( ! empty( $attrs['lead'] ) ) : ?>
						<p><?php echo sln_founders_kses( $attrs['lead'] ); ?></p>
					<?php endif; ?>
				</div>
				<div class="sf-actions">
					<?php
					echo sln_founders_render_button( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						array(
							'label'  => $attrs['primaryLabel'] ?? '',
							'url'    => $attrs['primaryUrl'] ?? '',
							'show'   => $attrs['primaryShow'] ?? true,
							'newTab' => $attrs['primaryNewTab'] ?? false,
							'style'  => 'light',
						)
					);
					?>
					<?php if ( $sec_show && '' !== trim( wp_strip_all_tags( $sec_label ) ) ) : ?>
						<a class="sf-close__link" href="<?php echo esc_url( $sec_url ); ?>"<?php echo ! empty( $attrs['secondaryNewTab'] ) ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>><?php echo esc_html( wp_strip_all_tags( $sec_label ) ); ?></a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
	return (string) ob_get_clean();
}
