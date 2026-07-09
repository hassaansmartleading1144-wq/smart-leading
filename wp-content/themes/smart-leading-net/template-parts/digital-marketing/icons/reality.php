<?php
/**
 * Digital Marketing page — reality section icons.
 *
 * @package Smart_Leading_Net
 *
 * @var array $args Template args.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$icon = isset( $args['icon'] ) ? (string) $args['icon'] : 'revenue-stuck';

switch ( $icon ) {
	case 'sales-inconsistent':
		?>
		<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 5v14M8 9l4-4 4 4M8 15l4 4 4-4"/></svg>
		<?php
		break;
	case 'poor-leads':
		?>
		<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="9" cy="8" r="3"/><path d="M3 20c0-3.3 2.7-6 6-6"/><path d="M16 11l5 5M21 11l-5 5"/></svg>
		<?php
		break;
	case 'not-converting':
		?>
		<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M7 7h10v10"/><path d="M7 17L17 7"/></svg>
		<?php
		break;
	case 'competitors':
		?>
		<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M4 5h16v14H4z"/><path d="M4 9h8M4 13h8M4 17h8"/><path d="M14 9l3 2-3 2"/></svg>
		<?php
		break;
	case 'brand-trust':
		?>
		<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 3l7 3v6c0 4.4-3 8.5-7 9-4-.5-7-4.6-7-9V6l7-3z"/></svg>
		<?php
		break;
	case 'no-offer':
		?>
		<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M9.5 9.5a3 3 0 0 1 5 2.2c0 2-2 2.3-2.5 3.3"/><path d="M12 17h.01"/></svg>
		<?php
		break;
	case 'missed-followups':
		?>
		<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 11v2a4 4 0 0 0 4 4h11"/><path d="M7 15l-4-4 4-4"/><path d="M14 5l3 3-3 3"/></svg>
		<?php
		break;
	case 'low-visibility':
		?>
		<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/><path d="M4 4l16 16"/></svg>
		<?php
		break;
	default:
		?>
		<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M14 9V5a3 3 0 0 0-6 0v4"/><rect x="4" y="9" width="16" height="11" rx="2"/></svg>
		<?php
		break;
}
