<?php
/**
 * SEO page — pain point icons.
 *
 * @package Smart_Leading_Net
 *
 * @var array $args Template args.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$icon = isset( $args['icon'] ) ? (string) $args['icon'] : 'search-minus';

switch ( $icon ) {
	case 'chart':
		?>
		<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="M19 9l-5 5-4-4-3 3"/></svg>
		<?php
		break;
	case 'technical':
		?>
		<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M12 18v4M2 12h4M18 12h4"/><circle cx="12" cy="12" r="4"/></svg>
		<?php
		break;
	case 'lock':
		?>
		<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 9V5a3 3 0 0 0-6 0v4"/><rect x="4" y="9" width="16" height="11" rx="2"/></svg>
		<?php
		break;
	case 'clock':
		?>
		<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
		<?php
		break;
	case 'ai':
		?>
		<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
		<?php
		break;
	default:
		?>
		<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/><path d="M8 11h6" stroke-width="2.4"/></svg>
		<?php
		break;
}
