<?php

/**

 * Bootstrap 5 nav walker — desktop & mobile contexts.

 *

 * @package Smart_Leading_Net

 */



if ( ! defined( 'ABSPATH' ) ) {

	exit;

}



/**

 * Custom walker for Bootstrap 5 navigation menus.

 */

class SLN_Bootstrap_Nav_Walker extends Walker_Nav_Menu {



	/**

	 * Menu rendering context: desktop or mobile.

	 *

	 * @var string

	 */

	protected $menu_context = 'desktop';



	/**

	 * Start the list before the elements are added.

	 *

	 * @param string $output Nav menu HTML.

	 * @param int    $depth  Depth level.

	 * @param array  $args   Menu arguments.

	 */

	public function start_lvl( &$output, $depth = 0, $args = null ) {

		$indent       = str_repeat( "\t", $depth );

		$nested_class = $depth >= 1 ? ' sub-menu--nested' : '';



		$output .= "\n$indent<ul class=\"dropdown-menu sub-menu{$nested_class}\">\n";

	}



	/**

	 * Start the element output.

	 *

	 * @param string $output Nav menu HTML.

	 * @param object $item   Menu item.

	 * @param int    $depth  Depth level.

	 * @param array  $args   Menu arguments.

	 * @param int    $id     Item ID.

	 */

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {

		if ( isset( $args->sln_menu_context ) ) {

			$this->menu_context = $args->sln_menu_context;

		}



		$indent       = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$has_children = in_array( 'menu-item-has-children', $item->classes, true );

		$li_classes   = array( 'nav-item', 'menu-item' );



		if ( $has_children ) {

			$li_classes[] = 'menu-item-has-children';



			if ( 0 === $depth ) {

				$li_classes[] = 'dropdown';



				if ( 'desktop' === $this->menu_context ) {

					$li_classes[] = 'sln-dropdown-hover';

				}

			} elseif ( 'mobile' === $this->menu_context ) {
				// Nested toggles handled in main.js (sln-mobile-nested-toggle).
			} else {

				$li_classes[] = 'sln-dropdown-hover-nested';

			}

		}



		$output .= $indent . '<li class="' . esc_attr( implode( ' ', $li_classes ) ) . '">';



		$atts           = array();

		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';

		$atts['target'] = ! empty( $item->target ) ? $item->target : '';

		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';

		$atts['href']   = ! empty( $item->url ) ? $item->url : '';



		if ( $has_children && 0 === $depth ) {

			$atts['class'] = 'nav-link dropdown-toggle';



			if ( 'mobile' === $this->menu_context ) {

				$atts['data-bs-toggle'] = 'dropdown';

				$atts['role']           = 'button';

				$atts['aria-expanded']  = 'false';

			}

		} elseif ( $has_children && $depth > 0 ) {
			$atts['class'] = 'dropdown-item dropdown-toggle sln-submenu-parent';

			if ( 'mobile' === $this->menu_context ) {
				$atts['class']       = 'dropdown-item sln-submenu-parent sln-mobile-nested-toggle';
				$atts['role']        = 'button';
				$atts['aria-expanded'] = 'false';
			}

		} elseif ( $depth > 0 ) {

			$atts['class'] = 'dropdown-item';

		} else {

			$atts['class'] = 'nav-link';

		}



		if ( $item->current || $item->current_item_ancestor ) {

			$atts['class']       .= ' active';

			$atts['aria-current'] = 'page';

		}



		$attributes = '';



		foreach ( $atts as $attr => $value ) {

			if ( ! empty( $value ) ) {

				$attributes .= ' ' . $attr . '="' . esc_attr( $value ) . '"';

			}

		}



		$title = apply_filters( 'the_title', $item->title, $item->ID );



		$output .= '<a' . $attributes . '>';

		$output .= esc_html( $title );



		if ( $has_children ) {

			$output .= '<span class="sln-nav-chevron" aria-hidden="true">';



			if ( $depth > 0 && 'desktop' === $this->menu_context ) {

				$output .= '<svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">';

				$output .= '<path d="M1.5 1L6.5 6L1.5 11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>';

				$output .= '</svg>';

			} else {

				$output .= '<svg width="12" height="8" viewBox="0 0 12 8" fill="none" xmlns="http://www.w3.org/2000/svg">';

				$output .= '<path d="M1 1.5L6 6.5L11 1.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>';

				$output .= '</svg>';

			}



			$output .= '</span>';

		}



		$output .= '</a>';

	}

}


