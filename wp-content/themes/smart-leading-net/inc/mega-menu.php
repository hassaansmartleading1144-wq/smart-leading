<?php
/**
 * Services mega menu.
 *
 * The primary menu's "Services" item (or any top-level item with the CSS
 * class "sln-mega") is replaced by three service categories in the bar
 * (Digital Marketing, SEO & AI, Paid Ads) and a Resources menu. Each opens a
 * full-width panel of link cards and a client result. About Us follows them.
 * The content lives here, like the footer links, so it ships the same way to
 * every site.
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
 * Service categories shown in the menu bar (option B, WebFX style). Each
 * opens its own panel: up to three light cards (coloured icon, heading, a
 * plain list of links) and a blue client-result card with one call to
 * action. Result figures are owner-confirmed (17 Nov 2026,
 * project-context/business-facts.md); the Hospitality figure is an SEO
 * result, so it stays on the SEO panel only.
 *
 * @return array<int, array<string, mixed>>
 */
function sln_mega_menu_categories() {
	$categories = array(
		array(
			'key'    => 'digital',
			'label'  => 'Digital Marketing',
			'url'    => '/digital-marketing-services/',
			'cards'  => array(
				array(
					'title' => 'Revenue growth',
					'icon'  => 'trend',
					'tone'  => 'green',
					'links' => array(
						array( 'Revenue Growth', '/revenue-growth/' ),
						array( 'Lead Generation', '/lead-generation/' ),
						array( 'Brand Visibility', '/brand-visibility/' ),
					),
				),
				array(
					'title' => 'Automation',
					'icon'  => 'pipeline',
					'tone'  => 'violet',
					'links' => array(
						array( 'Growth & Automation', '/growth-automation/' ),
						array( 'Conversions & Retention', '/conversions-retention/' ),
					),
				),
				array(
					'title' => 'Marketing & web',
					'icon'  => 'code',
					'tone'  => 'blue',
					'links' => array(
						array( 'Digital Marketing Services', '/digital-marketing-services/' ),
						array( 'Web Development', '/web-development-services/' ),
					),
				),
			),
			'result' => array(
				'value' => '$50M+',
				'label' => 'revenue tracked through CRM and analytics',
				'cta'   => 'Talk to an expert',
				'url'   => '/contact-us/',
			),
		),
		array(
			'key'    => 'seo',
			'label'  => 'SEO & AI',
			'url'    => '/seo-services/',
			'cards'  => array(
				array(
					'title' => 'Local and search',
					'icon'  => 'pin',
					'tone'  => 'blue',
					'links' => array(
						array( 'Local SEO', '/seo-services/local-seo/' ),
						array( 'Technical SEO', '/seo-services/technical-seo/' ),
						array( 'SEO Audit', '/seo-services/seo-audit/' ),
					),
				),
				array(
					'title' => 'Growth SEO',
					'icon'  => 'globe',
					'tone'  => 'blue',
					'links' => array(
						array( 'Ecommerce SEO', '/seo-services/ecommerce-seo/' ),
						array( 'International SEO', '/seo-services/international-seo/' ),
						array( 'Link Building', '/seo-services/link-building/' ),
					),
				),
				array(
					'title' => 'AI search',
					'icon'  => 'sparkle',
					'tone'  => 'violet',
					'links' => array(
						array( 'AI SEO Services', '/ai-seo-services/' ),
						array( 'Generative Engine Optimization', '/generative-engine-optimization/' ),
					),
				),
			),
			'result' => array(
				'value' => '260%',
				'label' => 'more organic revenue (hospitality)',
				'cta'   => 'Get my free SEO audit',
				'url'   => '/seo-services/local-seo/',
			),
		),
		array(
			'key'    => 'ppc',
			'label'  => 'Paid Ads',
			'url'    => '/ppc/',
			'cards'  => array(
				array(
					'title' => 'Google ads',
					'icon'  => 'target',
					'tone'  => 'orange',
					'links' => array(
						array( 'Google Ads Management', '/ppc/google-ads-management/' ),
						array( 'Local Services Ads', '/ppc/local-service-ads/' ),
						array( 'Ecommerce PPC', '/ppc/ecommerce-ppc/' ),
					),
				),
				array(
					'title' => 'Social ads',
					'icon'  => 'social',
					'tone'  => 'blue',
					'links' => array(
						array( 'Facebook & Instagram Ads', '/ppc/facebook-ads-management/' ),
					),
				),
				array(
					'title' => 'Start here',
					'icon'  => 'check',
					'tone'  => 'green',
					'links' => array(
						array( 'Free PPC Audit', '/ppc/free-ppc-audit/' ),
						array( 'All Paid Ads Services', '/ppc/' ),
					),
				),
			),
			'result' => array(
				'value' => '4.2X',
				'label' => 'higher ROAS (ecommerce)',
				'cta'   => 'Get my free PPC audit',
				'url'   => '/ppc/free-ppc-audit/',
			),
		),
	);

	$categories[] = sln_mega_menu_resources();

	return apply_filters( 'sln_mega_menu_categories', $categories );
}

/**
 * Whether a published page exists at a path (Case Studies is a WordPress page
 * that is not on every site yet, so its link only shows where it exists).
 *
 * @param string $path Page path.
 * @return bool
 */
function sln_mega_menu_page_exists( $path ) {
	$page = get_page_by_path( trim( $path, '/' ) );

	return $page && 'publish' === $page->post_status;
}

/**
 * Latest published guides (blog posts), newest first. WordPress's sample
 * "Hello world!" post is skipped.
 *
 * @param int $count How many.
 * @return array<int, array{0:string,1:string}>
 */
function sln_mega_menu_guides( $count = 3 ) {
	$links = array();
	$posts = get_posts(
		array(
			'numberposts'      => $count + 1,
			'post_status'      => 'publish',
			'suppress_filters' => false,
		)
	);

	foreach ( $posts as $post ) {
		if ( 'hello-world' === $post->post_name || count( $links ) >= $count ) {
			continue;
		}
		$links[] = array( get_the_title( $post ), wp_make_link_relative( get_permalink( $post ) ) );
	}

	return $links;
}

/**
 * Resources menu: our work, guides and free tools, in the same card style.
 *
 * @return array<string, mixed>
 */
function sln_mega_menu_resources() {
	$has_cases = sln_mega_menu_page_exists( '/case-studies/' );

	$work = array();
	if ( $has_cases ) {
		$work[] = array( 'Case Studies', '/case-studies/' );
	}
	$work[] = array( 'Portfolio', '/portfolio/' );

	$cards = array(
		array(
			'title' => 'Our work',
			'icon'  => 'award',
			'tone'  => 'orange',
			'links' => $work,
		),
	);

	$guides = sln_mega_menu_guides();
	if ( $guides ) {
		$cards[] = array(
			'title' => 'Guides',
			'icon'  => 'form',
			'tone'  => 'blue',
			'links' => $guides,
		);
	}

	$cards[] = array(
		'title' => 'Free tools',
		'icon'  => 'sparkle',
		'tone'  => 'violet',
		'links' => array(
			array( 'Free PPC Audit', '/ppc/free-ppc-audit/' ),
			array( 'Lead Analytics', 'https://leads.smartleading.net/' ),
			array( 'Become a Partner', '/referral-partners/' ),
		),
	);

	return array(
		'key'    => 'resources',
		'label'  => 'Resources',
		'url'    => $has_cases ? '/case-studies/' : '/portfolio/',
		'cards'  => $cards,
		'result' => array(
			'value' => '100k+',
			'label' => 'leads generated across 47+ client campaigns',
			'cta'   => 'See our work',
			'url'   => $has_cases ? '/case-studies/' : '/portfolio/',
		),
	);
}

/**
 * About Us dropdown links. Home is dropped from the bar (the logo links home);
 * Portfolio lives in the Resources menu.
 *
 * @return array<int, array{0:string,1:string}>
 */
function sln_about_menu_links() {
	return apply_filters(
		'sln_about_menu_links',
		array(
			array( 'About Us', '/about-us/' ),
			array( 'Our Founders', '/founders-behind/' ),
			array( 'Career', '/careers/' ),
		)
	);
}

/**
 * Normalised menu item title.
 *
 * @param WP_Post $item Menu item.
 * @return string
 */
function sln_menu_item_title( $item ) {
	return strtolower( trim( wp_strip_all_tags( $item->title ) ) );
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
	$about_id   = 0;
	$drop_roots = array();

	foreach ( $items as $item ) {
		if ( 0 !== (int) $item->menu_item_parent ) {
			continue;
		}

		$title = sln_menu_item_title( $item );

		if ( ! $trigger_id && sln_mega_menu_is_trigger( $item ) ) {
			$trigger_id = (int) $item->ID;
		} elseif ( ! $about_id && in_array( $title, array( 'about us', 'about' ), true ) ) {
			$about_id = (int) $item->ID;
		} elseif ( sln_mega_menu_is_merged( $item ) || in_array( $title, array( 'home', 'career', 'careers', 'portfolio', 'case studies', 'resources', 'blog', 'guides' ), true ) ) {
			$drop_roots[] = (int) $item->ID;
		}
	}

	if ( ! $trigger_id && ! $about_id ) {
		return $items;
	}

	$parents = array();
	foreach ( $items as $item ) {
		$parents[ (int) $item->ID ] = (int) $item->menu_item_parent;
	}

	$cut  = array_merge( array_filter( array( $trigger_id, $about_id ) ), $drop_roots );
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
		} elseif ( $id === $about_id ) {
			$item->classes   = array_diff( (array) $item->classes, array( 'menu-item-has-children' ) );
			$item->classes[] = 'sln-about-item';
		}

		$kept[] = $item;
	}

	// About Us sits right after the service menus and Resources.
	if ( $trigger_id && $about_id ) {
		$about = null;
		foreach ( $kept as $i => $item ) {
			if ( (int) $item->ID === $about_id ) {
				$about = $item;
				unset( $kept[ $i ] );
			}
		}
		$ordered = array();
		foreach ( $kept as $item ) {
			$ordered[] = $item;
			if ( $about && (int) $item->ID === $trigger_id ) {
				$ordered[] = $about;
			}
		}
		foreach ( $ordered as $i => $item ) {
			$item->menu_order = $i + 1;
		}
		$kept = $ordered;
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
	$external = 0 === strpos( $path, 'http' );
	$current  = ! $external && sln_mega_menu_current_path() === trailingslashit( $path );
	$attrs    = $current ? ' aria-current="page"' : '';
	$after    = '';

	if ( $external ) {
		$attrs .= ' target="_blank" rel="noopener"';
		$after  = '<span class="screen-reader-text">' . esc_html__( '(opens in a new tab)', 'smart-leading-net' ) . '</span>';
	}

	return sprintf(
		'<a class="%1$s%2$s" href="%3$s"%4$s>%5$s<span>%6$s</span>%7$s</a>',
		esc_attr( $class ),
		$current ? ' is-current' : '',
		esc_url( $external ? $path : home_url( $path ) ),
		$attrs,
		$inner,
		esc_html( $label ),
		$after
	);
}

/**
 * Whether the current page belongs to a category.
 *
 * @param array<string, mixed> $category Category.
 * @return bool
 */
function sln_mega_menu_category_has_current( $category ) {
	$path  = sln_mega_menu_current_path();
	$paths = array( $category['url'] );

	foreach ( $category['cards'] as $card ) {
		foreach ( $card['links'] as $link ) {
			$paths[] = $link[1];
		}
	}

	foreach ( $paths as $candidate ) {
		if ( trailingslashit( $candidate ) === $path ) {
			return true;
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
 * Card heading: coloured icon and title.
 *
 * @param array<string, mixed> $card Card.
 * @param string               $tag  Wrapper tag.
 * @param string               $class CSS class.
 * @return string
 */
function sln_mega_menu_card_head( $card, $tag, $class ) {
	return sprintf(
		'<%1$s class="%2$s"><span class="sln-mega-card__icon sln-mega-card__icon--%3$s" aria-hidden="true">%4$s</span><span>%5$s</span></%1$s>',
		$tag,
		esc_attr( $class ),
		esc_attr( $card['tone'] ),
		sln_home_icon( $card['icon'] ),
		esc_html( $card['title'] )
	);
}

/**
 * Card link list. The arrow sits after the label (CSS order).
 *
 * @param array<int, array{0:string,1:string}> $links Links.
 * @param string                               $class Link CSS class.
 * @return string
 */
function sln_mega_menu_card_links( $links, $class ) {
	$html = '';

	foreach ( $links as $link ) {
		$html .= '<li>' . sln_mega_menu_link( $link[1], $link[0], $class, sln_home_icon( 'arrow' ) ) . '</li>';
	}

	return $html;
}

/**
 * Render the service categories in place of the "Services" item: one <li>
 * per category. The last <li> is left open; the walker closes it.
 *
 * @param WP_Post $item    Menu item.
 * @param string  $context desktop or mobile.
 * @return string
 */
function sln_mega_menu_render( $item, $context ) {
	$categories = sln_mega_menu_categories();
	$html       = '';
	$last       = count( $categories ) - 1;

	foreach ( $categories as $index => $category ) {
		$active = sln_mega_menu_category_has_current( $category );
		$result = $category['result'];

		if ( 'mobile' === $context ) {
			$html .= '<li class="nav-item menu-item sln-mega-item sln-mega-m">';
			$html .= '<details class="sln-mega-m__root"' . ( $active ? ' open' : '' ) . '>';
			$html .= '<summary class="nav-link sln-mega-m__summary' . ( $active ? ' active' : '' ) . '">' . esc_html( $category['label'] ) . sln_mega_menu_chevron() . '</summary>';
			$html .= '<div class="sln-mega-m__body">';

			foreach ( $category['cards'] as $card ) {
				$html .= '<div class="sln-mega-m__card">';
				$html .= sln_mega_menu_card_head( $card, 'p', 'sln-mega-m__card-title' );
				$html .= '<ul class="sln-mega-m__links">' . sln_mega_menu_card_links( $card['links'], 'sln-mega-m__link' ) . '</ul>';
				$html .= '</div>';
			}

			$html .= '<a class="sln-mega-m__result" href="' . esc_url( home_url( $result['url'] ) ) . '"><strong>' . esc_html( $result['value'] ) . '</strong><span>' . esc_html( $result['label'] ) . '</span><span class="sln-mega-m__result-cta">' . esc_html( $result['cta'] ) . '</span></a>';
			$html .= '</div></details>';
		} else {
			$panel_id = 'sln-mega-panel-' . $category['key'];

			$html .= '<li class="nav-item menu-item sln-mega-item" data-sln-mega>';
			$html .= '<button type="button" class="nav-link sln-mega__toggle' . ( $active ? ' active' : '' ) . '" aria-expanded="false" aria-controls="' . esc_attr( $panel_id ) . '"><span class="sln-mega__label">' . esc_html( $category['label'] ) . '</span>' . sln_mega_menu_chevron() . '</button>';
			$html .= '<div class="sln-mega" id="' . esc_attr( $panel_id ) . '" hidden>';
			$html .= '<div class="container sln-mega__inner" style="--cards: ' . count( $category['cards'] ) . '">';

			foreach ( $category['cards'] as $i => $card ) {
				$html .= '<div class="sln-mega-card" style="--i: ' . (int) $i . '">';
				$html .= sln_mega_menu_card_head( $card, 'p', 'sln-mega-card__title' );
				$html .= '<ul class="sln-mega-card__links">' . sln_mega_menu_card_links( $card['links'], 'sln-mega-card__link' ) . '</ul>';
				$html .= '</div>';
			}

			$html .= '<div class="sln-mega-result" style="--i: ' . count( $category['cards'] ) . '">';
			$html .= '<strong class="sln-mega-result__value">' . esc_html( $result['value'] ) . '</strong>';
			$html .= '<span class="sln-mega-result__label">' . esc_html( $result['label'] ) . '</span>';
			$html .= '<a class="sln-mega-result__cta" href="' . esc_url( home_url( $result['url'] ) ) . '">' . esc_html( $result['cta'] ) . sln_home_icon( 'arrow' ) . '</a>';
			$html .= '</div>';
			$html .= '</div></div>';
		}

		if ( $index !== $last ) {
			$html .= "</li>\n";
		}
	}

	return $html;
}

/**
 * Render the About Us <li> with its small dropdown (the walker closes it).
 *
 * @param WP_Post $item    Menu item.
 * @param string  $context desktop or mobile.
 * @return string
 */
function sln_about_menu_render( $item, $context ) {
	$title  = apply_filters( 'the_title', $item->title, $item->ID );
	$links  = sln_about_menu_links();
	$path   = sln_mega_menu_current_path();
	$active = false;

	foreach ( $links as $link ) {
		if ( trailingslashit( $link[1] ) === $path ) {
			$active = true;
		}
	}

	if ( 'mobile' === $context ) {
		$html  = '<li class="nav-item menu-item sln-mega-m sln-about-m">';
		$html .= '<details class="sln-mega-m__root"' . ( $active ? ' open' : '' ) . '>';
		$html .= '<summary class="nav-link sln-mega-m__summary' . ( $active ? ' active' : '' ) . '">' . esc_html( $title ) . sln_mega_menu_chevron() . '</summary>';
		$html .= '<ul class="sln-mega-m__links sln-about-m__links">';
		foreach ( $links as $link ) {
			$html .= '<li>' . sln_mega_menu_link( $link[1], $link[0], 'sln-mega-m__link' ) . '</li>';
		}
		$html .= '</ul></details>';

		return $html;
	}

	$panel_id = 'sln-about-panel-' . (int) $item->ID;

	$html  = '<li class="nav-item menu-item sln-about-item" data-sln-mega>';
	$html .= '<button type="button" class="nav-link sln-mega__toggle' . ( $active ? ' active' : '' ) . '" aria-expanded="false" aria-controls="' . esc_attr( $panel_id ) . '">' . esc_html( $title ) . sln_mega_menu_chevron() . '</button>';
	$html .= '<div class="sln-mega sln-mega--small" id="' . esc_attr( $panel_id ) . '" hidden><ul class="sln-mega__links">';
	foreach ( $links as $link ) {
		$html .= '<li>' . sln_mega_menu_link( $link[1], $link[0], 'sln-mega__link', sln_home_icon( 'arrow' ) ) . '</li>';
	}
	$html .= '</ul></div>';

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
