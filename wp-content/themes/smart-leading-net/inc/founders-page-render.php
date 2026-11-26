<?php
/**
 * Founders page — front-end render helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Escape a heading and wrap {words} in the orange highlight span.
 *
 * @param string $text Heading text.
 * @return string
 */
function sln_founders_highlight( $text ) {
	$escaped = esc_html( $text );
	return preg_replace( '/\{([^}]+)\}/', '<span class="sfp-em">$1</span>', $escaped );
}

/**
 * Print an inline SVG icon.
 *
 * @param string $name Icon key.
 */
function sln_founders_icon( $name ) {
	$stroke = 'fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"';
	$icons  = array(
		'arrow-right' => '<svg viewBox="0 0 18 18" ' . $stroke . '><path d="M3.5 9h11M10 4.5 14.5 9 10 13.5"/></svg>',
		'arrow-down'  => '<svg viewBox="0 0 16 16" ' . $stroke . '><path d="M8 3v10M4 9l4 4 4-4"/></svg>',
		'external'    => '<svg viewBox="0 0 16 16" ' . $stroke . '><path d="M5 11 11 5M6 5h5v5"/></svg>',
		'search'      => '<svg viewBox="0 0 24 24" ' . $stroke . '><circle cx="11" cy="11" r="6.5"/><path d="m20 20-4.2-4.2"/></svg>',
		'users'       => '<svg viewBox="0 0 24 24" ' . $stroke . '><circle cx="9" cy="8" r="3.5"/><path d="M2.5 20c.7-3.5 3.3-5.5 6.5-5.5s5.8 2 6.5 5.5M16 4.8a3.5 3.5 0 0 1 0 6.4M18.5 14.8c1.6.8 2.7 2.6 3 5.2"/></svg>',
		'target'      => '<svg viewBox="0 0 24 24" ' . $stroke . '><circle cx="12" cy="12" r="8.5"/><circle cx="12" cy="12" r="4.5"/><circle cx="12" cy="12" r="1"/></svg>',
		'chart'       => '<svg viewBox="0 0 24 24" ' . $stroke . '><path d="M4 20V4M4 20h16"/><path d="m7.5 15 3.5-4 3 2.5 5-6"/></svg>',
		'award'       => '<svg viewBox="0 0 24 24" ' . $stroke . '><circle cx="12" cy="9" r="5.5"/><path d="m8.5 13.5-1.5 7 5-2.5 5 2.5-1.5-7"/></svg>',
		'person'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"><circle cx="12" cy="8.5" r="3.75"/><path d="M4.75 20c.9-3.6 3.8-5.75 7.25-5.75S18.35 16.4 19.25 20"/></svg>',
		'linkedin'    => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M4.98 3.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5ZM3 9.75h4v11H3v-11Zm6.5 0h3.8v1.5h.06c.53-1 1.83-1.75 3.64-1.75 3.9 0 4.5 2.4 4.5 5.5v5.75h-4v-5.1c0-1.25-.03-2.85-1.75-2.85-1.75 0-2.25 1.35-2.25 2.75v5.2h-4v-11Z"/></svg>',
		'x'           => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.75 3h3.07l-6.7 7.66L22 21h-6.17l-4.83-6.32L5.47 21H2.4l7.17-8.2L2 3h6.33l4.37 5.78L17.75 3Zm-1.08 16.2h1.7L7.4 4.73H5.58L16.67 19.2Z"/></svg>',
		'facebook'    => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 21v-7.5h2.5l.4-3h-2.9V8.6c0-.87.25-1.45 1.5-1.45h1.55V4.47A20 20 0 0 0 14.3 4.3c-2.25 0-3.8 1.37-3.8 3.9v2.3H8v3h2.5V21h3Z"/></svg>',
		'instagram'   => '<svg viewBox="0 0 24 24" ' . $stroke . '><rect x="3.5" y="3.5" width="17" height="17" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r=".6" fill="currentColor"/></svg>',
		'youtube'     => '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M21.6 7.2a2.5 2.5 0 0 0-1.77-1.77C18.27 5 12 5 12 5s-6.27 0-7.83.43A2.5 2.5 0 0 0 2.4 7.2 26 26 0 0 0 2 12a26 26 0 0 0 .4 4.8 2.5 2.5 0 0 0 1.77 1.77C5.73 19 12 19 12 19s6.27 0 7.83-.43a2.5 2.5 0 0 0 1.77-1.77A26 26 0 0 0 22 12a26 26 0 0 0-.4-4.8ZM10 15V9l5.2 3L10 15Z"/></svg>',
		'website'     => '<svg viewBox="0 0 24 24" ' . $stroke . '><circle cx="12" cy="12" r="8.5"/><path d="M3.5 12h17M12 3.5c2.3 2.4 3.4 5.2 3.4 8.5s-1.1 6.1-3.4 8.5c-2.3-2.4-3.4-5.2-3.4-8.5S9.7 5.9 12 3.5Z"/></svg>',
		'email'       => '<svg viewBox="0 0 24 24" ' . $stroke . '><rect x="3" y="5" width="18" height="14" rx="2.5"/><path d="m4 7 8 6 8-6"/></svg>',
	);

	if ( isset( $icons[ $name ] ) ) {
		echo str_replace( '<svg ', '<svg aria-hidden="true" focusable="false" ', $icons[ $name ] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

/**
 * Initials for the monogram fallback.
 *
 * @param string $name Full name.
 * @return string
 */
function sln_founders_initials( $name ) {
	$parts    = preg_split( '/\s+/', trim( $name ) );
	$initials = '';
	foreach ( array_slice( (array) $parts, 0, 2 ) as $part ) {
		$initials .= mb_strtoupper( mb_substr( $part, 0, 1 ) );
	}
	return $initials;
}

/**
 * Print a founder portrait, or a fallback when no photo is set.
 *
 * @param array $founder  Founder data.
 * @param bool  $is_editor Whether the viewer can edit the page.
 * @param bool  $is_hero  Hero (eager) vs profile (lazy).
 */
function sln_founders_portrait( $founder, $is_editor, $is_hero = false ) {
	echo '<div class="sfp-portrait">';

	if ( $founder['photo_id'] && wp_attachment_is_image( $founder['photo_id'] ) ) {
		$alt = trim( (string) get_post_meta( $founder['photo_id'], '_wp_attachment_image_alt', true ) );
		if ( '' === $alt ) {
			/* translators: 1: founder name, 2: role. */
			$alt = sprintf( __( 'Portrait of %1$s, %2$s of Smart Leading Solutions', 'smart-leading-net' ), $founder['name'], $founder['role'] );
		}
		echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			$founder['photo_id'],
			$is_hero ? 'medium_large' : 'large',
			false,
			array(
				'alt'     => $alt,
				'loading' => $is_hero ? 'eager' : 'lazy',
				'sizes'   => $is_hero ? '(min-width: 992px) 270px, 45vw' : '(min-width: 992px) 520px, 100vw',
			)
		);
	} elseif ( $is_editor ) {
		echo '<div class="sfp-placeholder" role="img" aria-label="' . esc_attr( sprintf( 'Placeholder for a portrait of %s', $founder['name'] ) ) . '">';
		sln_founders_icon( 'person' );
		echo '<span><strong>' . esc_html__( 'Add photo', 'smart-leading-net' ) . '</strong>' . esc_html( $founder['name'] ) . ' · 4:5, min. 1040 × 1300px</span>';
		echo '</div>';
	} else {
		echo '<div class="sfp-monogram" aria-hidden="true">' . esc_html( sln_founders_initials( $founder['name'] ) ) . '</div>';
	}

	echo '</div>';
}

/**
 * Editor-only reminder box (never shown to visitors).
 *
 * @param bool   $is_editor Whether the viewer can edit the page.
 * @param string $message   Message.
 */
function sln_founders_editor_note( $is_editor, $message ) {
	if ( $is_editor ) {
		echo '<p class="sfp-bio-slot"><strong>' . esc_html__( 'Only you can see this:', 'smart-leading-net' ) . '</strong> ' . esc_html( $message ) . '</p>';
	}
}

/**
 * Render one founder profile section.
 *
 * @param array $f         Founder data.
 * @param int   $index     Position (odd = reversed layout).
 * @param bool  $is_editor Whether the viewer can edit the page.
 */
function sln_founders_render_profile( $f, $index, $is_editor ) {
	$id_base  = 'sfp-' . $f['anchor'];
	$networks = sln_founders_social_networks();
	$first    = strtok( $f['name'], ' ' );
	$socials  = array_filter( $f['socials'] );
	$courses  = $f['courses'];
	$classes  = 'sfp-section sfp-profile' . ( $index % 2 ? ' sfp-profile--reverse' : '' );
	?>
	<section class="<?php echo esc_attr( $classes ); ?>" id="<?php echo esc_attr( $f['anchor'] ); ?>" aria-labelledby="<?php echo esc_attr( $id_base ); ?>-name">
		<div class="sls-container">
			<div class="sfp-profile__grid">
				<div class="sfp-profile__media sfp-reveal">
					<?php sln_founders_portrait( $f, $is_editor ); ?>
				</div>
				<div class="sfp-profile__content">
					<p class="sfp-label sfp-reveal"><?php esc_html_e( 'Founder', 'smart-leading-net' ); ?></p>
					<h2 id="<?php echo esc_attr( $id_base ); ?>-name" class="sfp-reveal" data-delay="1"><?php echo esc_html( $f['name'] ); ?></h2>

					<?php if ( $f['role'] || $f['focus'] ) : ?>
						<dl class="sfp-profile__meta sfp-reveal" data-delay="1">
							<?php if ( $f['role'] ) : ?>
								<div><dt><?php esc_html_e( 'Role', 'smart-leading-net' ); ?></dt><dd class="sfp-role"><?php echo esc_html( $f['role'] ); ?></dd></div>
							<?php endif; ?>
							<?php if ( $f['focus'] ) : ?>
								<div><dt><?php esc_html_e( 'Professional focus', 'smart-leading-net' ); ?></dt><dd><?php echo esc_html( $f['focus'] ); ?></dd></div>
							<?php endif; ?>
						</dl>
					<?php endif; ?>

					<?php if ( $f['intro'] ) : ?>
						<p class="sfp-profile__intro sfp-reveal" data-delay="2"><?php echo esc_html( $f['intro'] ); ?></p>
					<?php endif; ?>

					<?php if ( $f['bio'] ) : ?>
						<div class="sfp-profile__bio sfp-reveal" data-delay="2"><?php echo wp_kses_post( wpautop( $f['bio'] ) ); ?></div>
					<?php else : ?>
						<?php sln_founders_editor_note( $is_editor, sprintf( 'add %s’s full biography in the “Founders Page” box when editing this page.', $first ) ); ?>
					<?php endif; ?>

					<?php if ( $socials ) : ?>
						<ul class="sfp-socials sfp-reveal" data-delay="2" aria-label="<?php echo esc_attr( sprintf( '%s online', $f['name'] ) ); ?>">
							<?php foreach ( $socials as $key => $url ) : ?>
								<?php
								$is_mail = 'email' === $key;
								$href    = $is_mail ? 'mailto:' . antispambot( $url ) : $url;
								/* translators: 1: founder first name, 2: network. */
								$label = $is_mail ? sprintf( __( 'Email %s', 'smart-leading-net' ), $first ) : sprintf( __( '%1$s on %2$s', 'smart-leading-net' ), $first, $networks[ $key ] );
								?>
								<li>
									<a class="sfp-social sfp-social--<?php echo esc_attr( $key ); ?>" href="<?php echo esc_url( $href, $is_mail ? array( 'mailto' ) : null ); ?>"<?php echo $is_mail ? '' : ' target="_blank" rel="noopener"'; ?>>
										<?php sln_founders_icon( $key ); ?>
										<span class="sfp-social__label"><?php echo esc_html( 'website' === $key ? __( 'Website', 'smart-leading-net' ) : ( $is_mail ? __( 'Email', 'smart-leading-net' ) : $networks[ $key ] ) ); ?></span>
										<span class="sfp-visually-hidden"><?php echo esc_html( $label ); ?><?php echo $is_mail ? '' : esc_html__( ' (opens in a new tab)', 'smart-leading-net' ); ?></span>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php else : ?>
						<?php sln_founders_editor_note( $is_editor, sprintf( 'add %s’s social profile links in the “Founders Page” box.', $first ) ); ?>
					<?php endif; ?>
				</div>
			</div>

			<?php if ( $courses ) : ?>
				<div class="sfp-courses" aria-labelledby="<?php echo esc_attr( $id_base ); ?>-courses">
					<div class="sfp-courses__head sfp-reveal">
						<h3 id="<?php echo esc_attr( $id_base ); ?>-courses">
							<span class="sfp-courses__icon"><?php sln_founders_icon( 'award' ); ?></span>
							<?php esc_html_e( 'Courses & certifications', 'smart-leading-net' ); ?>
						</h3>
						<span class="sfp-courses__count"><?php echo esc_html( sprintf( _n( '%d completed', '%d completed', count( $courses ), 'smart-leading-net' ), count( $courses ) ) ); ?></span>
					</div>
					<ul class="sfp-courses__list" data-sfp-collapse="6">
						<?php foreach ( $courses as $c_i => $course ) : ?>
							<?php
							$has_cert = ! empty( $course['cert_id'] ) && wp_attachment_is_image( $course['cert_id'] );
							if ( $has_cert && ! $course['url'] ) {
								$course['url'] = (string) wp_get_attachment_image_url( $course['cert_id'], 'full' );
							}
							?>
							<li class="sfp-course<?php echo $has_cert ? ' sfp-course--cert' : ''; ?> sfp-reveal" data-delay="<?php echo esc_attr( (string) min( 2, $c_i % 3 ) ); ?>">
								<?php if ( $has_cert ) : ?>
									<figure class="sfp-course__cert" aria-hidden="true">
										<?php echo wp_get_attachment_image( $course['cert_id'], 'medium_large', false, array( 'alt' => '', 'loading' => 'lazy', 'sizes' => '(min-width: 1100px) 440px, (min-width: 640px) 50vw, 100vw' ) ); ?>
										<span class="sfp-course__cert-cta"><?php esc_html_e( 'View certificate', 'smart-leading-net' ); ?><?php sln_founders_icon( 'external' ); ?></span>
									</figure>
								<?php endif; ?>
								<div class="sfp-course__row">
								<?php if ( ! empty( $course['badge_id'] ) && wp_attachment_is_image( $course['badge_id'] ) ) : ?>
									<span class="sfp-course__badge sfp-course__badge--img" aria-hidden="true"><?php echo wp_get_attachment_image( $course['badge_id'], 'thumbnail', false, array( 'alt' => '', 'loading' => 'lazy' ) ); ?></span>
								<?php else : ?>
									<span class="sfp-course__badge" aria-hidden="true"><?php sln_founders_icon( 'award' ); ?></span>
								<?php endif; ?>
								<div class="sfp-course__body">
									<p class="sfp-course__title"><?php echo esc_html( $course['title'] ); ?></p>
									<?php if ( $course['provider'] || $course['year'] ) : ?>
										<p class="sfp-course__meta">
											<?php echo esc_html( $course['provider'] ); ?>
											<?php if ( $course['provider'] && $course['year'] ) : ?>
												<span aria-hidden="true">·</span>
											<?php endif; ?>
											<?php echo esc_html( $course['year'] ); ?>
										</p>
									<?php endif; ?>
									<?php if ( $course['url'] && $has_cert ) : ?>
										<?php /* Whole card is the link; the visible "View certificate" label is the image overlay. */ ?>
										<a class="sfp-course__link sfp-course__link--overlay" href="<?php echo esc_url( $course['url'] ); ?>" target="_blank" rel="noopener">
											<span class="sfp-visually-hidden"><?php echo esc_html( __( 'View certificate', 'smart-leading-net' ) . ': ' . $course['title'] . __( ' (opens in a new tab)', 'smart-leading-net' ) ); ?></span>
										</a>
									<?php elseif ( $course['url'] ) : ?>
										<a class="sfp-course__link" href="<?php echo esc_url( $course['url'] ); ?>" target="_blank" rel="noopener">
											<?php esc_html_e( 'View certificate', 'smart-leading-net' ); ?>
											<span class="sfp-visually-hidden"><?php echo esc_html( ': ' . $course['title'] . __( ' (opens in a new tab)', 'smart-leading-net' ) ); ?></span>
											<?php sln_founders_icon( 'external' ); ?>
										</a>
									<?php endif; ?>
								</div>
								</div>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php elseif ( $is_editor ) : ?>
				<div class="sfp-courses"><?php sln_founders_editor_note( true, sprintf( 'add the courses %s has completed in the “Founders Page” box. This section stays hidden from visitors until at least one course is added.', $first ) ); ?></div>
			<?php endif; ?>
		</div>
	</section>
	<?php
}
