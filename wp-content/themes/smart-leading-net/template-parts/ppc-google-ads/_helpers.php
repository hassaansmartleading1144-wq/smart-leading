<?php
/**
 * PPC & Google Ads page — shared template-part helpers.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'sln_ppc_part_render_heading' ) ) {
	/**
	 * Render a standard PPC page section heading.
	 *
	 * @param array<string, mixed> $section Section settings.
	 * @param string               $id      Heading ID.
	 * @param bool                 $center  Whether to center the heading.
	 * @param string               $tone    Highlight tone: azure|orange.
	 */
	function sln_ppc_part_render_heading( $section, $id, $center = false, $tone = 'azure' ) {
		$classes = 'sln-ppc-section-head';

		if ( $center ) {
			$classes .= ' sln-ppc-section-head--center';
		}
		?>
		<div class="<?php echo esc_attr( $classes ); ?> sln-ppc-reveal">
			<?php if ( ! empty( $section['small_heading'] ) ) : ?>
				<span class="sln-ppc-kicker"><?php echo esc_html( $section['small_heading'] ); ?></span>
			<?php endif; ?>

			<h2 id="<?php echo esc_attr( $id ); ?>" class="sln-ppc-title">
				<?php echo esc_html( $section['main_heading'] ?? '' ); ?>
				<?php if ( ! empty( $section['highlighted_text'] ) ) : ?>
					<span class="sln-ppc-highlight--<?php echo esc_attr( $tone ); ?>"><?php echo esc_html( $section['highlighted_text'] ); ?></span>
				<?php endif; ?>
			</h2>

			<?php if ( sln_ppc_plain_text( $section['description'] ?? '' ) ) : ?>
				<div class="sln-ppc-dek"><?php echo sln_ppc_format_content( $section['description'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<?php endif; ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'sln_ppc_part_render_button' ) ) {
	/**
	 * Render a PPC page button.
	 *
	 * @param string $text  Button text.
	 * @param string $url   Button URL.
	 * @param string $class Modifier classes.
	 * @param bool   $arrow Whether to show the arrow span.
	 */
	function sln_ppc_part_render_button( $text, $url, $class = 'sln-ppc-btn--orange', $arrow = true ) {
		if ( '' === (string) $text ) {
			return;
		}
		?>
		<a class="sln-ppc-btn <?php echo esc_attr( $class ); ?>" href="<?php echo esc_url( $url ); ?>">
			<?php echo esc_html( $text ); ?>
			<?php if ( $arrow ) : ?>
				<span class="sln-ppc-btn__arrow" aria-hidden="true">→</span>
			<?php endif; ?>
		</a>
		<?php
	}
}

if ( ! function_exists( 'sln_ppc_part_number_display' ) ) {
	/**
	 * Build a display value from stored numeric pieces.
	 *
	 * @param array<string, mixed> $row       Row data.
	 * @param string              $value_key Numeric value key.
	 * @return string
	 */
	function sln_ppc_part_number_display( $row, $value_key = 'value' ) {
		if ( ! empty( $row['display_value'] ) ) {
			return (string) $row['display_value'];
		}

		$value    = (float) ( $row[ $value_key ] ?? 0 );
		$decimals = (int) ( $row['decimals'] ?? 0 );
		$prefix   = (string) ( $row['prefix'] ?? '' );
		$suffix   = (string) ( $row['suffix'] ?? '' );

		return $prefix . number_format( $value, $decimals ) . $suffix;
	}
}

if ( ! function_exists( 'sln_ppc_part_numeric_attrs' ) ) {
	/**
	 * Return escaped counter data attributes.
	 *
	 * @param array<string, mixed> $row       Row data.
	 * @param string              $value_key Numeric value key.
	 * @return string
	 */
	function sln_ppc_part_numeric_attrs( $row, $value_key = 'value' ) {
		return sprintf(
			'data-pre="%1$s" data-val="%2$s" data-dec="%3$s" data-suf="%4$s"',
			esc_attr( (string) ( $row['prefix'] ?? '' ) ),
			esc_attr( (string) ( $row[ $value_key ] ?? '0' ) ),
			esc_attr( (string) ( $row['decimals'] ?? '0' ) ),
			esc_attr( (string) ( $row['suffix'] ?? '' ) )
		);
	}
}

if ( ! function_exists( 'sln_ppc_part_visual_class' ) ) {
	/**
	 * Resolve CSS modifier from stored visual style.
	 *
	 * @param string $base  Base class.
	 * @param string $style Visual style.
	 * @return string
	 */
	function sln_ppc_part_visual_class( $base, $style ) {
		$map = array(
			'blue'    => 'azure',
			'azure'   => 'azure',
			'orange'  => 'orange',
			'green'   => 'up',
			'mint'    => 'up',
			'up'      => 'up',
			'default' => '',
		);

		$modifier = $map[ $style ] ?? '';

		if ( '' === $modifier ) {
			return $base;
		}

		return $base . ' ' . $base . '--' . $modifier;
	}
}

if ( ! function_exists( 'sln_ppc_part_svg_icon' ) ) {
	/**
	 * Render simple inline SVG icons by key.
	 *
	 * @param string $key Icon key.
	 */
	function sln_ppc_part_svg_icon( $key ) {
		$icons = array(
			'search'   => '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/><path d="m21 21-4.3-4.3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
			'pmax'     => '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M6 8h12l-1 12H7L6 8z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M9 8V6a3 3 0 0 1 6 0v2" stroke="currentColor" stroke-width="2"/></svg>',
			'youtube'  => '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="3" stroke="currentColor" stroke-width="2"/><path d="M10 9.5v5l4.5-2.5-4.5-2.5z" fill="currentColor"/></svg>',
			'lsa'      => '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 21s7-7.5 7-12a7 7 0 1 0-14 0c0 4.5 7 12 7 12z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><circle cx="12" cy="9" r="2.5" stroke="currentColor" stroke-width="2"/></svg>',
			'profile'  => '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 10l1.5-5h15L21 10" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M4 10v9h16v-9" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M9 19v-5h6v5" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>',
			'ai'       => '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3l1.6 4.4L18 9l-4.4 1.6L12 15l-1.6-4.4L6 9l4.4-1.6L12 3z" fill="currentColor"/><path d="M19 15l.8 2.2L22 18l-2.2.8L19 21l-.8-2.2L16 18l2.2-.8L19 15z" fill="currentColor"/></svg>',
			'retarget' => '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 4v5h5M20 20v-5h-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 15a8 8 0 0 0 14 3M19 9A8 8 0 0 0 5 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
			'cro'      => '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2" stroke="currentColor" stroke-width="2"/><path d="M3 9h18" stroke="currentColor" stroke-width="2"/><path d="M8 14h5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
			'data'     => '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 20V10M12 20V4M19 20v-7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>',
		);

		echo $icons[ $key ] ?? '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="2"/><path d="M12 8v8M8 12h8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

if ( ! function_exists( 'sln_ppc_part_check_icon' ) ) {
	/**
	 * Render check/x comparison icon.
	 *
	 * @param string $type x|check.
	 */
	function sln_ppc_part_check_icon( $type ) {
		$is_check = 'check' === $type;
		$class    = $is_check ? 'sln-ppc-why-icon--check' : 'sln-ppc-why-icon--x';
		$path     = $is_check ? '<path d="M8 12l3 3 5-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>' : '<path d="M9 9l6 6M15 9l-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>';
		?>
		<span class="sln-ppc-why-icon <?php echo esc_attr( $class ); ?>" aria-hidden="true">
			<svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"/><?php echo $path; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></svg>
		</span>
		<?php
	}
}
