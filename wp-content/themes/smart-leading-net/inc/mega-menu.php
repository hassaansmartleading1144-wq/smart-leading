<?php
/**
 * Services mega menu.
 *
 * The primary menu's "Services" item (or any top-level item with the CSS
 * class "sln-mega") opens a full-width panel with the SEO, Paid Ads, Revenue
 * Growth and Web & AI pages plus two free-audit offers. The panel content
 * lives here, like the footer links, so it ships the same way to every site.
 * A top-level "Revenue Platform" item (or CSS class "sln-mega-merge") is
 * folded into the panel and removed from the bar.
 *
 * Desktop: opens on click, Enter/Space, or hover with a short delay; Esc,
 * click outside and focus leaving close it (assets/js/mega-menu.js).
 * Mobile menu: native <details> accordions, no script needed.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Service groups shown in the panel. Paths are relative to the site root.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_mega_menu_groups() {
	$groups = array(
		array(
			'title' => 'SEO',
			'url'   => '/seo-services/',
			'icon'  => 'search',
			'tone'  => 'blue',
			'desc'  => 'Get found on Google, Maps and AI search',
			'links' => array(
				array( 'Local SEO', '/seo-services/local-seo/' ),
				array( 'Technical SEO', '/seo-services/technical-seo/' ),
				array( 'SEO Audit', '/seo-services/seo-audit/' ),
				array( 'Ecommerce SEO', '/seo-services/ecommerce-seo/' ),
				array( 'International SEO', '/seo-services/international-seo/' ),
				array( 'Link Building', '/seo-services/link-building/' ),
			),
		),
		array(
			'title' => 'Paid Ads (PPC)',
			'url'   => '/ppc/',
			'icon'  => 'target',
			'tone'  => 'orange',
			'desc'  => 'Ads that bring calls, leads and sales',
			'links' => array(
				array( 'Google Ads Management', '/ppc/google-ads-management/' ),
				array( 'Facebook & Instagram Ads', '/ppc/facebook-ads-management/' ),
				array( 'Local Services Ads', '/ppc/local-service-ads/' ),
				array( 'Ecommerce PPC', '/ppc/ecommerce-ppc/' ),
			),
		),
		array(
			'title' => 'Revenue Growth',
			'url'   => '/revenue-growth/',
			'icon'  => 'trend',
			'tone'  => 'green',
			'desc'  => 'Turn more of your leads into customers',
			'links' => array(
				array( 'Lead Generation', '/lead-generation/' ),
				array( 'Brand Visibility', '/brand-visibility/' ),
				array( 'Growth & Automation', '/growth-automation/' ),
				array( 'Conversions & Retention', '/conversions-retention/' ),
			),
		),
		array(
			'title' => 'Web & AI',
			'url'   => '',
			'icon'  => 'code',
			'tone'  => 'violet',
			'desc'  => 'Websites and visibility in AI answers',
			'links' => array(
				array( 'Web Development', '/web-development-services/' ),
				array( 'AI SEO Services', '/ai-seo-services/' ),
				array( 'Generative Engine Optimization', '/generative-engine-optimization/' ),
				array( 'Digital Marketing', '/digital-marketing-services/' ),
			),
		),
	);

	return apply_filters( 'sln_mega_menu_groups', $groups );
}

/**
 * Free offers shown beside the service groups. Free PPC Audit terms are
 * owner-confirmed (18 Nov 2026); the Local SEO audit is owner-confirmed with
 * its scope deferred, so its line makes no promise about format or timing.
 *
 * @return array<int, array<string, string>>
 */
function sln_mega_menu_offers() {
	$offers = array(
		array(
			'label' => 'Free PPC Audit',
			'text'  => 'PDF report, video walkthrough and a call within 2–3 business days.',
			'cta'   => 'Get my PPC audit',
			'url'   => '/ppc/free-ppc-audit/',
			'icon'  => 'target',
			'tone'  => 'orange',
		),
		array(
			'label' => 'Free Local SEO Audit',
			'text'  => 'See what is stopping local customers from finding you.',
			'cta'   => 'Get my SEO audit',
			'url'   => '/seo-services/local-seo/',
			'icon'  => 'pin',
			'tone'  => 'blue',
		),
	);

	return apply_filters( 'sln_mega_menu_offers', $offers );
}

/**
 * Whether a menu item is the mega menu trigger.
 *
 * @param WP_Post $item Menu item.
 * @return bool
 */
function sln_mega_menu_is_trigger( $item ) {
	$classes = array_filter( (array) $item->classes );

	return in_array( 'sln-mega', $classes, true ) || 'services' === strtolower( trim( wp_strip_all_tags( $item->title ) ) );
}

/**
 * Whether a top-level item is folded into the mega menu.
 *
 * @param WP_Post $item Menu item.
 * @return bool
 */
function sln_mega_menu_is_merged( $item ) {
	$classes = array_filter( (array) $item->classes );

	return in_array( 'sln-mega-merge', $classes, true ) || 'revenue platform' === strtolower( trim( wp_strip_all_tags( $item->title ) ) );
}

/**
 * Primary menu: mark the trigger, drop its old dropdown children and the
 * merged "Revenue Platform" branch (its pages are in the panel now).
 *
 * @param array    $items Menu items.
 * @param stdClass $args  wp_nav_menu() arguments.
 * @return array
 */
function sln_mega_menu_filter_items( $items, $args ) {
	if ( empty( $args->theme_location ) || 'primary' !== $args->theme_location ) {
		return $items;
	}

	$trigger_id = 0;
	$drop_roots = array();

	foreach ( $items as $item ) {
		if ( 0 !== (int) $item->menu_item_parent ) {
			continue;
		}

		if ( ! $trigger_id && sln_mega_menu_is_trigger( $item ) ) {
			$trigger_id = (int) $item->ID;
		} elseif ( sln_mega_menu_is_merged( $item ) ) {
			$drop_roots[] = (int) $item->ID;
		}
	}

	if ( ! $trigger_id ) {
		return $items;
	}

	$parents = array();
	foreach ( $items as $item ) {
		$parents[ (int) $item->ID ] = (int) $item->menu_item_parent;
	}

	$cut  = array_merge( array( $trigger_id ), $drop_roots );
	$kept = array();

	foreach ( $items as $item ) {
		$id = (int) $item->ID;

		if ( in_array( $id, $drop_roots, true ) ) {
			continue;
		}

		// Remove every descendant of the trigger and of merged items.
		$parent      = $parents[ $id ];
		$is_inside   = false;
		$guard       = 0;
		while ( $parent && $guard < 20 ) {
			if ( in_array( $parent, $cut, true ) ) {
				$is_inside = true;
				break;
			}
			$parent = isset( $parents[ $parent ] ) ? $parents[ $parent ] : 0;
			++$guard;
		}

		if ( $is_inside ) {
			continue;
		}

		if ( $id === $trigger_id ) {
			$item->classes   = array_diff( (array) $item->classes, array( 'menu-item-has-children' ) );
			$item->classes[] = 'sln-mega-item';
		}

		$kept[] = $item;
	}

	return $kept;
}
add_filter( 'wp_nav_menu_objects', 'sln_mega_menu_filter_items', 10, 2 );

/**
 * Current request path, for highlighting the active page.
 *
 * @return string
 */
function sln_mega_menu_current_path() {
	$uri  = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '/'; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$path = (string) wp_parse_url( $uri, PHP_URL_PATH );
	$home = (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH );

	if ( '' !== $home && '/' !== $home && 0 === strpos( $path, rtrim( $home, '/' ) ) ) {
		$path = substr( $path, strlen( rtrim( $home, '/' ) ) );
	}

	return trailingslashit( '/' . ltrim( $path, '/' ) );
}

/**
 * Link markup with aria-current on the active page.
 *
 * @param string $path  Site-relative path.
 * @param string $label Link text.
 * @param string $class CSS class.
 * @param string $inner Extra inner HTML placed before the label (trusted).
 * @return string
 */
function sln_mega_menu_link( $path, $label, $class, $inner = '' ) {
	$current = sln_mega_menu_current_path() === trailingslashit( $path );

	return sprintf(
		'<a class="%1$s%2$s" href="%3$s"%4$s>%5$s<span>%6$s</span></a>',
		esc_attr( $class ),
		$current ? ' is-current' : '',
		esc_url( home_url( $path ) ),
		$current ? ' aria-current="page"' : '',
		$inner,
		esc_html( $label )
	);
}

/**
 * Whether the current page is one of the panel's pages.
 *
 * @return bool
 */
function sln_mega_menu_has_current() {
	$path = sln_mega_menu_current_path();

	foreach ( sln_mega_menu_groups() as $group ) {
		if ( $group['url'] && trailingslashit( $group['url'] ) === $path ) {
			return true;
		}
		foreach ( $group['links'] as $link ) {
			if ( trailingslashit( $link[1] ) === $path ) {
				return true;
			}
		}
	}

	return false;
}

/**
 * Chevron icon.
 *
 * @return string
 */
function sln_mega_menu_chevron() {
	return '<span class="sln-nav-chevron" aria-hidden="true"><svg width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1.5L6 6.5L11 1.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>';
}

/**
 * Offer cards.
 *
 * @return string
 */
function sln_mega_menu_offers_html() {
	$html = '';

	foreach ( sln_mega_menu_offers() as $offer ) {
		$html .= sprintf(
			'<a class="sln-mega__offer sln-mega__offer--%1$s" href="%2$s"><span class="sln-mega__offer-icon">%3$s</span><span class="sln-mega__offer-body"><strong>%4$s</strong><span>%5$s</span><span class="sln-mega__offer-cta">%6$s %7$s</span></span></a>',
			esc_attr( $offer['tone'] ),
			esc_url( home_url( $offer['url'] ) ),
			sln_home_icon( $offer['icon'] ),
			esc_html( $offer['label'] ),
			esc_html( $offer['text'] ),
			esc_html( $offer['cta'] ),
			sln_home_icon( 'arrow' )
		);
	}

	return $html;
}

/**
 * Render the trigger <li> (opening tag and contents; the walker closes it).
 *
 * @param WP_Post $item    Menu item.
 * @param string  $context desktop or mobile.
 * @return string
 */
function sln_mega_menu_render( $item, $context ) {
	$title   = apply_filters( 'the_title', $item->title, $item->ID );
	$active  = sln_mega_menu_has_current();
	$groups  = sln_mega_menu_groups();

	if ( 'mobile' === $context ) {
		$html  = '<li class="nav-item menu-item sln-mega-item sln-mega-m">';
		$html .= '<details class="sln-mega-m__root"' . ( $active ? ' open' : '' ) . '>';
		$html .= '<summary class="nav-link sln-mega-m__summary' . ( $active ? ' active' : '' ) . '">' . esc_html( $title ) . sln_mega_menu_chevron() . '</summary>';
		$html .= '<div class="sln-mega-m__body">';

		foreach ( $groups as $group ) {
			$html .= '<details class="sln-mega-m__group">';
			$html .= '<summary class="sln-mega-m__group-title"><span class="sln-mega__icon sln-mega__icon--' . esc_attr( $group['tone'] ) . '">' . sln_home_icon( $group['icon'] ) . '</span><span>' . esc_html( $group['title'] ) . '</span>' . sln_mega_menu_chevron() . '</summary>';
			$html .= '<ul class="sln-mega-m__links">';
			if ( $group['url'] ) {
				/* translators: %s: service group name. */
				$html .= '<li>' . sln_mega_menu_link( $group['url'], sprintf( __( 'All %s services', 'smart-leading-net' ), $group['title'] ), 'sln-mega-m__link sln-mega-m__link--all' ) . '</li>';
			}
			foreach ( $group['links'] as $link ) {
				$html .= '<li>' . sln_mega_menu_link( $link[1], $link[0], 'sln-mega-m__link' ) . '</li>';
			}
			$html .= '</ul></details>';
		}

		$html .= '<div class="sln-mega-m__offers">' . sln_mega_menu_offers_html() . '</div>';
		$html .= '</div></details>';

		return $html;
	}

	$panel_id = 'sln-mega-panel-' . (int) $item->ID;

	$html  = '<li class="nav-item menu-item sln-mega-item" data-sln-mega>';
	$html .= '<button type="button" class="nav-link sln-mega__toggle' . ( $active ? ' active' : '' ) . '" aria-expanded="false" aria-controls="' . esc_attr( $panel_id ) . '">' . esc_html( $title ) . sln_mega_menu_chevron() . '</button>';
	$html .= '<div class="sln-mega" id="' . esc_attr( $panel_id ) . '" hidden>';
	$html .= '<div class="container sln-mega__inner">';
	$html .= '<div class="sln-mega__groups">';

	foreach ( $groups as $group ) {
		$head_inner = '<span class="sln-mega__icon sln-mega__icon--' . esc_attr( $group['tone'] ) . '">' . sln_home_icon( $group['icon'] ) . '</span>';

		$html .= '<div class="sln-mega__group">';
		if ( $group['url'] ) {
			$html .= sln_mega_menu_link( $group['url'], $group['title'], 'sln-mega__group-head', $head_inner );
		} else {
			$html .= '<span class="sln-mega__group-head">' . $head_inner . '<span>' . esc_html( $group['title'] ) . '</span></span>';
		}
		$html .= '<p class="sln-mega__group-desc">' . esc_html( $group['desc'] ) . '</p>';
		$html .= '<ul class="sln-mega__links">';
		foreach ( $group['links'] as $link ) {
			$html .= '<li>' . sln_mega_menu_link( $link[1], $link[0], 'sln-mega__link' ) . '</li>';
		}
		$html .= '</ul></div>';
	}

	$html .= '</div>';
	$html .= '<aside class="sln-mega__side" aria-label="' . esc_attr__( 'Free offers', 'smart-leading-net' ) . '">';
	$html .= '<span class="sln-mega__side-label">' . esc_html__( 'Free offers', 'smart-leading-net' ) . '</span>';
	$html .= sln_mega_menu_offers_html();
	$html .= '<a class="sln-mega__expert" href="' . esc_url( home_url( '/contact-us/' ) ) . '">' . esc_html__( 'Not sure where to start? Talk to an expert', 'smart-leading-net' ) . sln_home_icon( 'arrow' ) . '</a>';
	$html .= '</aside>';
	$html .= '</div></div>';

	return $html;
}

/**
 * Mega menu styles and script, on every page (the header is everywhere).
 */
function sln_mega_menu_assets() {
	$css = SLN_THEME_DIR . '/assets/css/mega-menu.css';
	$js  = SLN_THEME_DIR . '/assets/js/mega-menu.js';

	wp_enqueue_style( 'sln-mega-menu', SLN_THEME_URI . '/assets/css/mega-menu.css', array( 'sln-header' ), file_exists( $css ) ? (string) filemtime( $css ) : SLN_THEME_VERSION );
	wp_enqueue_script( 'sln-mega-menu', SLN_THEME_URI . '/assets/js/mega-menu.js', array(), file_exists( $js ) ? (string) filemtime( $js ) : SLN_THEME_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'sln_mega_menu_assets', 20 );
