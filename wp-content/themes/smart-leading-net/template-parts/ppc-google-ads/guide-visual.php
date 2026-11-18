<?php
/**
 * PPC guide — animated illustrations (decorative, aria-hidden by the caller).
 *
 * No performance numbers: these show how the work looks, not results.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$visual = (string) ( $args['visual'] ?? '' );

switch ( $visual ) :
	case 'search-terms':
		$terms = array(
			array( 'kitchen remodel near me', true ),
			array( 'free kitchen design software', false ),
			array( 'kitchen remodeling contractor', true ),
			array( 'kitchen remodel jobs hiring', false ),
			array( 'diy kitchen cabinet ideas', false ),
			array( 'kitchen renovation quote', true ),
		);
		?>
		<div class="sln-ppc-gv-card">
			<div class="sln-ppc-gv-title"><?php echo sln_ppc_icon( 'search-x' ); // phpcs:ignore ?> Search terms report</div>
			<ul class="sln-ppc-gv-terms">
				<?php foreach ( $terms as $i => $term ) : ?>
					<li class="<?php echo $term[1] ? 'is-keep' : 'is-block'; ?>" style="--i:<?php echo (int) $i; ?>">
						<span class="sln-ppc-gv-term"><?php echo esc_html( $term[0] ); ?></span>
						<span class="sln-ppc-gv-pill"><?php echo $term[1] ? 'Keep' : 'Negative'; ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
		break;

	case 'match-types':
		$rows = array(
			array( 'Exact', '[kitchen remodeling contractor]' ),
			array( 'Phrase', '"kitchen remodel quote"' ),
			array( 'Exact', '[kitchen renovation near me]' ),
		);
		?>
		<div class="sln-ppc-gv-card">
			<div class="sln-ppc-gv-title"><?php echo sln_ppc_icon( 'target' ); // phpcs:ignore ?> Ad group: Kitchen Remodeling</div>
			<ul class="sln-ppc-gv-kws">
				<?php foreach ( $rows as $i => $row ) : ?>
					<li style="--i:<?php echo (int) $i; ?>"><span class="sln-ppc-gv-mt"><?php echo esc_html( $row[0] ); ?></span><?php echo esc_html( $row[1] ); ?></li>
				<?php endforeach; ?>
			</ul>
			<div class="sln-ppc-gv-blocked">
				<span>Blocked:</span>
				<?php foreach ( array( 'free', 'jobs', 'diy', 'salary' ) as $i => $neg ) : ?>
					<em style="--i:<?php echo (int) $i; ?>">−<?php echo esc_html( $neg ); ?></em>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
		break;

	case 'ad-preview':
		$meters = array( 'Expected click-through rate', 'Ad relevance', 'Landing page experience' );
		?>
		<div class="sln-ppc-gv-card sln-ppc-gv-ad">
			<div class="sln-ppc-gv-ad-top"><b>Sponsored</b> · yourbusiness.com</div>
			<div class="sln-ppc-gv-ad-title">Kitchen Remodeling Near You | Request a Quote</div>
			<div class="sln-ppc-gv-ad-desc">Local, licensed team. See designs, get a clear quote and book a visit.</div>
			<div class="sln-ppc-gv-assets">
				<?php foreach ( array( 'Call', 'Get a quote', 'Directions' ) as $i => $asset ) : ?>
					<span style="--i:<?php echo (int) $i; ?>"><?php echo esc_html( $asset ); ?></span>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="sln-ppc-gv-meters">
			<?php foreach ( $meters as $i => $meter ) : ?>
				<div class="sln-ppc-gv-meter" style="--i:<?php echo (int) $i; ?>">
					<span><?php echo esc_html( $meter ); ?></span>
					<i><b></b></i>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
		break;

	case 'map-radius':
		?>
		<div class="sln-ppc-gv-card sln-ppc-gv-map">
			<svg viewBox="0 0 320 170" class="sln-ppc-gv-mapsvg">
				<g class="sln-ppc-gv-roads">
					<path d="M0 40h320M0 95h320M0 140h320M50 0v170M130 0v170M210 0v170M280 0v170"/>
					<path d="M0 160 320 10"/>
				</g>
				<circle class="sln-ppc-gv-radius" cx="160" cy="88" r="62" pathLength="1"/>
				<g class="sln-ppc-gv-dots">
					<circle cx="128" cy="70" r="5" style="--i:0"/>
					<circle cx="190" cy="104" r="5" style="--i:1"/>
					<circle cx="150" cy="122" r="5" style="--i:2"/>
					<circle cx="196" cy="60" r="5" style="--i:3"/>
					<circle class="is-out" cx="40" cy="30" r="5" style="--i:4"/>
					<circle class="is-out" cx="292" cy="150" r="5" style="--i:5"/>
				</g>
				<path class="sln-ppc-gv-pin" d="M160 92s-14-12-14-24a14 14 0 0 1 28 0c0 12-14 24-14 24z"/>
				<circle cx="160" cy="68" r="5" fill="#fff"/>
			</svg>
			<div class="sln-ppc-gv-sched">
				<?php foreach ( array( 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun' ) as $i => $day ) : ?>
					<div style="--i:<?php echo (int) $i; ?>" class="<?php echo $i > 5 ? 'is-off' : ''; ?>"><i></i><span><?php echo esc_html( $day ); ?></span></div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
		break;

	case 'bidding':
		?>
		<div class="sln-ppc-gv-card">
			<div class="sln-ppc-gv-title"><?php echo sln_ppc_icon( 'gauge' ); // phpcs:ignore ?> Bids follow tracked leads</div>
			<svg viewBox="0 0 320 150" class="sln-ppc-gv-chart">
				<path class="sln-ppc-gv-grid" d="M20 130h290M20 90h290M20 50h290"/>
				<path class="sln-ppc-gv-line sln-ppc-gv-line--muted" pathLength="1" d="M20 112 70 104 120 110 170 98 220 104 270 96 310 100"/>
				<path class="sln-ppc-gv-line" pathLength="1" d="M20 118 70 108 120 96 170 80 220 66 270 52 310 40"/>
				<g class="sln-ppc-gv-points">
					<circle cx="120" cy="96" r="6" style="--i:0"/>
					<circle cx="220" cy="66" r="6" style="--i:1"/>
					<circle cx="310" cy="40" r="6" style="--i:2"/>
				</g>
			</svg>
			<div class="sln-ppc-gv-legend"><span class="is-main">Bidding on conversions</span><span>Bidding on clicks</span></div>
		</div>
		<?php
		break;

	case 'landing-page':
		?>
		<div class="sln-ppc-gv-card sln-ppc-gv-browser">
			<div class="sln-ppc-gv-browser-bar"><i></i><i></i><i></i><span>yourbusiness.com/kitchen-remodeling</span></div>
			<div class="sln-ppc-gv-page">
				<div class="sln-ppc-gv-page-copy">
					<b style="--i:0"></b><b style="--i:1" class="is-short"></b>
					<p style="--i:2"></p><p style="--i:3"></p>
					<span class="sln-ppc-gv-cta">Call now</span>
				</div>
				<div class="sln-ppc-gv-form">
					<i style="--i:0"></i><i style="--i:1"></i><i style="--i:2"></i>
					<span class="sln-ppc-gv-cta sln-ppc-gv-cta--orange">Get a quote</span>
				</div>
			</div>
		</div>
		<?php
		break;
	case 'serp':
		?>
		<div class="sln-ppc-gv-card sln-ppc-gv-serp">
			<div class="sln-ppc-gv-serpbar"><?php echo sln_ppc_icon( 'search' ); // phpcs:ignore ?> <span>emergency plumber near me</span></div>
			<?php foreach ( array( 'Sponsored', 'Sponsored' ) as $i => $tag ) : ?>
				<div class="sln-ppc-gv-res is-ad" style="--i:<?php echo (int) $i; ?>"><b><?php echo esc_html( $tag ); ?></b><i></i><em></em></div>
			<?php endforeach; ?>
			<?php foreach ( array( 0, 1 ) as $i ) : ?>
				<div class="sln-ppc-gv-res" style="--i:<?php echo (int) ( $i + 2 ); ?>"><i></i><em></em></div>
			<?php endforeach; ?>
			<span class="sln-ppc-gv-click" aria-hidden="true"></span>
		</div>
		<?php
		break;

	case 'auction':
		$bidders = array(
			array( 'Your ad', 'Strong', 'Strong', true ),
			array( 'Competitor A', 'High', 'Weak', false ),
			array( 'Competitor B', 'Low', 'Average', false ),
		);
		?>
		<div class="sln-ppc-gv-card">
			<div class="sln-ppc-gv-title"><?php echo sln_ppc_icon( 'gauge' ); // phpcs:ignore ?> Ad Rank = bid + ad quality</div>
			<div class="sln-ppc-gv-auction">
				<?php foreach ( $bidders as $i => $b ) : ?>
					<div class="sln-ppc-gv-bidder<?php echo $b[3] ? ' is-win' : ''; ?>" style="--i:<?php echo (int) $i; ?>">
						<span class="sln-ppc-gv-bname"><?php echo esc_html( $b[0] ); ?></span>
						<span class="sln-ppc-gv-chip">Bid: <?php echo esc_html( $b[1] ); ?></span>
						<span class="sln-ppc-gv-chip">Quality: <?php echo esc_html( $b[2] ); ?></span>
						<span class="sln-ppc-gv-pos"><?php echo esc_html( '#' . ( $i + 1 ) ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
		break;

	case 'quality-score':
		$meters = array( 'Expected click-through rate', 'Ad relevance', 'Landing page experience' );
		?>
		<div class="sln-ppc-gv-card sln-ppc-gv-qs">
			<div class="sln-ppc-gv-title"><?php echo sln_ppc_icon( 'target' ); // phpcs:ignore ?> Quality Score</div>
			<div class="sln-ppc-gv-qsdial"><span>1</span><i><b></b></i><span>10</span></div>
		</div>
		<div class="sln-ppc-gv-meters">
			<?php foreach ( $meters as $i => $meter ) : ?>
				<div class="sln-ppc-gv-meter" style="--i:<?php echo (int) $i; ?>"><span><?php echo esc_html( $meter ); ?></span><i><b></b></i></div>
			<?php endforeach; ?>
		</div>
		<?php
		break;

	case 'ad-types':
		$types = array(
			array( 'search', 'Search' ),
			array( 'shield-check', 'Local Services' ),
			array( 'cart', 'Shopping' ),
			array( 'layers', 'Performance Max' ),
			array( 'play', 'YouTube' ),
			array( 'users', 'Meta Ads' ),
			array( 'layout', 'Display' ),
			array( 'refresh', 'Retargeting' ),
		);
		?>
		<div class="sln-ppc-gv-types">
			<?php foreach ( $types as $i => $t ) : ?>
				<span class="sln-ppc-gv-type" style="--i:<?php echo (int) $i; ?>"><?php echo sln_ppc_icon( $t[0] ); // phpcs:ignore ?><?php echo esc_html( $t[1] ); ?></span>
			<?php endforeach; ?>
		</div>
		<?php
		break;

	case 'tracking':
		$sources = array( array( 'phone-off', 'Calls' ), array( 'file-question', 'Website forms' ), array( 'shield-check', 'Local Services leads' ), array( 'users', 'Meta lead forms' ) );
		?>
		<div class="sln-ppc-gv-flow">
			<div class="sln-ppc-gv-sources">
				<?php foreach ( array( 'Calls', 'Website forms', 'Local Services leads', 'Meta lead forms' ) as $i => $src ) : ?>
					<span style="--i:<?php echo (int) $i; ?>"><?php echo esc_html( $src ); ?></span>
				<?php endforeach; ?>
			</div>
			<svg class="sln-ppc-gv-lines" viewBox="0 0 60 160" preserveAspectRatio="none">
				<?php foreach ( array( 20, 60, 100, 140 ) as $i => $y ) : ?>
					<path pathLength="1" style="--i:<?php echo (int) $i; ?>" d="M0 <?php echo (int) $y; ?> C30 <?php echo (int) $y; ?>, 30 80, 60 80"/>
				<?php endforeach; ?>
			</svg>
			<div class="sln-ppc-gv-hubbox"><?php echo sln_ppc_icon( 'line-chart' ); // phpcs:ignore ?><b>Lead Analytics</b><span>One count, by source</span></div>
		</div>
		<?php
		break;

	case 'channels':
		$chs = array( array( 'search', 'Google Search', 'Ready to buy' ), array( 'shield-check', 'Local Services', 'Local calls' ), array( 'cart', 'Shopping & PMax', 'Product sales' ), array( 'play', 'YouTube', 'Build demand' ), array( 'users', 'Meta Ads', 'Reach & leads' ), array( 'refresh', 'Retargeting', 'Win back' ) );
		?>
		<div class="sln-ppc-gv-channels">
			<?php foreach ( $chs as $i => $c ) : ?>
				<div class="sln-ppc-gv-channel" style="--i:<?php echo (int) $i; ?>">
					<?php echo sln_ppc_icon( $c[0] ); // phpcs:ignore ?>
					<b><?php echo esc_html( $c[1] ); ?></b>
					<span><?php echo esc_html( $c[2] ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
		break;

	case 'report':
		$rows = array( array( 'Google Search', 78 ), array( 'Local Services', 62 ), array( 'Meta Ads', 48 ), array( 'Retargeting', 30 ) );
		?>
		<div class="sln-ppc-gv-card">
			<div class="sln-ppc-gv-title"><?php echo sln_ppc_icon( 'eye' ); // phpcs:ignore ?> Leads by channel</div>
			<div class="sln-ppc-gv-report">
				<?php foreach ( $rows as $i => $r ) : ?>
					<div class="sln-ppc-gv-rrow" style="--i:<?php echo (int) $i; ?>; --w:<?php echo (int) $r[1]; ?>%">
						<span><?php echo esc_html( $r[0] ); ?></span><i><b></b></i>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="sln-ppc-gv-legend"><span class="is-main">Tracked leads</span><span>Cost per lead shown per channel</span></div>
		</div>
		<?php
		break;
	case 'verify':
		$checks = array( 'Category and service area', 'Business details', 'Background check', 'License check', 'Insurance check' );
		?>
		<div class="sln-ppc-gv-card">
			<div class="sln-ppc-gv-title"><?php echo sln_ppc_icon( 'clipboard-check' ); // phpcs:ignore ?> Verification checklist</div>
			<ul class="sln-ppc-gv-checks">
				<?php foreach ( $checks as $i => $c ) : ?>
					<li style="--i:<?php echo (int) $i; ?>"><i><?php echo sln_ppc_icon( 'check' ); // phpcs:ignore ?></i><?php echo esc_html( $c ); ?></li>
				<?php endforeach; ?>
			</ul>
			<div class="sln-ppc-gv-gg"><span><?php echo sln_ppc_icon( 'shield-check' ); // phpcs:ignore ?></span> Google Guaranteed</div>
		</div>
		<?php
		break;

	case 'lsa-profile':
		?>
		<div class="sln-ppc-gv-card sln-ppc-gv-lsa">
			<div class="sln-ppc-gv-lsa-head"><b>Your Cleaning Co.</b><span class="sln-ppc-gv-ggtag"><?php echo sln_ppc_icon( 'shield-check' ); // phpcs:ignore ?> Google Guaranteed</span></div>
			<div class="sln-ppc-gv-stars" aria-hidden="true"><?php for ( $i = 0; $i < 5; $i++ ) : ?><i style="--i:<?php echo (int) $i; ?>">★</i><?php endfor; ?></div>
			<div class="sln-ppc-gv-lsa-meta"><span style="--i:0">Serves your area</span><span style="--i:1">Open now</span><span style="--i:2">Deep cleaning · Move-out</span></div>
			<div class="sln-ppc-gv-lsa-btns"><span>Call</span><span>Message</span><span class="is-main">Book</span></div>
		</div>
		<?php
		break;

	case 'reviews':
		?>
		<div class="sln-ppc-gv-card">
			<div class="sln-ppc-gv-title"><?php echo sln_ppc_icon( 'sparkles' ); // phpcs:ignore ?> Ranking signals</div>
			<div class="sln-ppc-gv-signals">
				<?php foreach ( array( 'Reviews', 'Responsiveness', 'Proximity', 'Business hours' ) as $i => $sig ) : ?>
					<div class="sln-ppc-gv-meter" style="--i:<?php echo (int) $i; ?>"><span><?php echo esc_html( $sig ); ?></span><i><b></b></i></div>
				<?php endforeach; ?>
			</div>
			<div class="sln-ppc-gv-review"><i>★★★★★</i><span>"On time and spotless. Booking again."</span><em>Example review</em></div>
		</div>
		<?php
		break;

	case 'lsa-budget':
		$days = array( 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun' );
		?>
		<div class="sln-ppc-gv-card">
			<div class="sln-ppc-gv-title"><?php echo sln_ppc_icon( 'gauge' ); // phpcs:ignore ?> Weekly budget</div>
			<div class="sln-ppc-gv-slider"><i><b></b></i><span>Matched to your capacity</span></div>
			<div class="sln-ppc-gv-sched">
				<?php foreach ( $days as $i => $day ) : ?>
					<div style="--i:<?php echo (int) $i; ?>" class="<?php echo 4 === $i ? 'is-off' : ''; ?>"><i></i><span><?php echo esc_html( $day ); ?></span></div>
				<?php endforeach; ?>
			</div>
			<div class="sln-ppc-gv-legend"><span class="is-main">Taking leads</span><span>Fully booked: paused</span></div>
		</div>
		<?php
		break;

	case 'disputes':
		$leads = array(
			array( 'Deep clean, 3-bed house', 'Valid', 'ok' ),
			array( 'Asked for a service you don\'t offer', 'Disputed', 'bad' ),
			array( 'Move-out clean, this week', 'Valid', 'ok' ),
			array( 'Caller outside your service area', 'Disputed', 'bad' ),
			array( 'Spam call', 'Disputed', 'bad' ),
		);
		?>
		<div class="sln-ppc-gv-card">
			<div class="sln-ppc-gv-title"><?php echo sln_ppc_icon( 'filter-x' ); // phpcs:ignore ?> Lead inbox</div>
			<ul class="sln-ppc-gv-terms">
				<?php foreach ( $leads as $i => $l ) : ?>
					<li class="<?php echo 'ok' === $l[2] ? 'is-keep' : 'is-block'; ?>" style="--i:<?php echo (int) $i; ?>">
						<span class="sln-ppc-gv-term"><?php echo esc_html( $l[0] ); ?></span>
						<span class="sln-ppc-gv-pill"><?php echo esc_html( $l[1] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
		break;
	case 'feed':
		?>
		<div class="sln-ppc-gv-phone">
			<div class="sln-ppc-gv-feed">
				<div class="sln-ppc-gv-post"><i></i><em></em><b></b></div>
				<div class="sln-ppc-gv-post is-ad">
					<div class="sln-ppc-gv-post-head"><span class="sln-ppc-gv-avatar"></span><span><b>Your Business</b><small>Sponsored</small></span></div>
					<div class="sln-ppc-gv-post-img"><span>Free design consult</span></div>
					<div class="sln-ppc-gv-post-cta">Get quote</div>
				</div>
				<div class="sln-ppc-gv-post"><i></i><em></em><b></b></div>
			</div>
			<div class="sln-ppc-gv-aud">
				<?php foreach ( array( 'Within 15 mi', 'Homeowners', 'Interested in remodeling' ) as $i => $a ) : ?>
					<span style="--i:<?php echo (int) $i; ?>"><?php echo esc_html( $a ); ?></span>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
		break;

	case 'creatives':
		$vars = array( array( 'Image', 'is-a' ), array( 'Video', 'is-b' ), array( 'Carousel', 'is-c' ) );
		?>
		<div class="sln-ppc-gv-creatives">
			<?php foreach ( $vars as $i => $v ) : ?>
				<div class="sln-ppc-gv-creative <?php echo esc_attr( $v[1] ); ?>" style="--i:<?php echo (int) $i; ?>">
					<div class="sln-ppc-gv-cr-img"><?php if ( 1 === $i ) : ?><?php echo sln_ppc_icon( 'play' ); // phpcs:ignore ?><?php endif; ?></div>
					<b><?php echo esc_html( $v[0] ); ?></b>
					<i></i><em></em>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="sln-ppc-gv-formats">
			<?php foreach ( array( 'Feed', 'Stories', 'Reels' ) as $i => $f ) : ?>
				<span style="--i:<?php echo (int) $i; ?>"><?php echo esc_html( $f ); ?></span>
			<?php endforeach; ?>
		</div>
		<?php
		break;

	case 'lead-form':
		$qs = array( array( 'What do you need?', 'Kitchen remodel' ), array( 'When do you want to start?', 'Within 3 months' ), array( 'Your zip code', 'In your area' ) );
		?>
		<div class="sln-ppc-gv-card sln-ppc-gv-form2">
			<div class="sln-ppc-gv-title"><?php echo sln_ppc_icon( 'clipboard-check' ); // phpcs:ignore ?> Instant form</div>
			<?php foreach ( $qs as $i => $q ) : ?>
				<div class="sln-ppc-gv-q" style="--i:<?php echo (int) $i; ?>"><span><?php echo esc_html( $q[0] ); ?></span><b><?php echo esc_html( $q[1] ); ?></b></div>
			<?php endforeach; ?>
			<span class="sln-ppc-gv-cta sln-ppc-gv-cta--orange">Submit</span>
			<div class="sln-ppc-gv-qualified"><?php echo sln_ppc_icon( 'check' ); // phpcs:ignore ?> Qualified lead</div>
		</div>
		<?php
		break;

	case 'pixel':
		$events = array( 'PageView', 'ViewContent', 'Lead', 'Contact' );
		?>
		<div class="sln-ppc-gv-card">
			<div class="sln-ppc-gv-title"><?php echo sln_ppc_icon( 'line-chart' ); // phpcs:ignore ?> Meta Pixel events</div>
			<ul class="sln-ppc-gv-events">
				<?php foreach ( $events as $i => $e ) : ?>
					<li style="--i:<?php echo (int) $i; ?>" class="<?php echo in_array( $e, array( 'Lead', 'Contact' ), true ) ? 'is-key' : ''; ?>"><i></i><span><?php echo esc_html( $e ); ?></span><em>Received</em></li>
				<?php endforeach; ?>
			</ul>
			<div class="sln-ppc-gv-legend"><span class="is-main">Optimizing for: Lead</span><span>Not just clicks</span></div>
		</div>
		<?php
		break;

	case 'ab-test':
		?>
		<div class="sln-ppc-gv-card">
			<div class="sln-ppc-gv-title"><?php echo sln_ppc_icon( 'gauge' ); // phpcs:ignore ?> Creative test</div>
			<div class="sln-ppc-gv-ab">
				<div class="sln-ppc-gv-abv" style="--i:0; --w:45%"><b>Ad A</b><i><em></em></i><span>Leads</span></div>
				<div class="sln-ppc-gv-abv is-win" style="--i:1; --w:82%"><b>Ad B</b><i><em></em></i><span>Leads</span><strong>Winner</strong></div>
			</div>
			<div class="sln-ppc-gv-legend"><span class="is-main">Budget moves to the winner</span><span>Illustrative</span></div>
		</div>
		<?php
		break;

	case 'retarget':
		$path = array( array( 'search', 'Visits your site' ), array( 'eye', 'Leaves without enquiring' ), array( 'users', 'Sees your ad on Instagram' ), array( 'check', 'Comes back and enquires' ) );
		?>
		<div class="sln-ppc-gv-journey">
			<?php foreach ( $path as $i => $p ) : ?>
				<div class="sln-ppc-gv-jstep" style="--i:<?php echo (int) $i; ?>">
					<span><?php echo sln_ppc_icon( $p[0] ); // phpcs:ignore ?></span>
					<b><?php echo esc_html( $p[1] ); ?></b>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
		break;
	case 'product-feed':
		$rows = array(
			array( 'Title', 'Knife set', 'Chef knife set, 8-piece, stainless steel' ),
			array( 'GTIN', 'Missing', '0012345678905' ),
			array( 'Image', 'Low quality', 'White background, 1200px' ),
			array( 'Status', 'Disapproved', 'Approved' ),
		);
		?>
		<div class="sln-ppc-gv-card">
			<div class="sln-ppc-gv-title"><?php echo sln_ppc_icon( 'clipboard-check' ); // phpcs:ignore ?> Product feed</div>
			<ul class="sln-ppc-gv-feedrows">
				<?php foreach ( $rows as $i => $r ) : ?>
					<li style="--i:<?php echo (int) $i; ?>"><b><?php echo esc_html( $r[0] ); ?></b><span class="is-old"><?php echo esc_html( $r[1] ); ?></span><span class="is-new"><?php echo esc_html( $r[2] ); ?></span></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php
		break;

	case 'products':
		$items = array( array( 'Best seller', 'is-top' ), array( 'High margin', 'is-top' ), array( 'Steady', '' ), array( 'Low margin', 'is-low' ), array( 'Best seller', 'is-top' ), array( 'Not selling', 'is-low' ) );
		?>
		<div class="sln-ppc-gv-pgrid">
			<?php foreach ( $items as $i => $it ) : ?>
				<div class="sln-ppc-gv-prod <?php echo esc_attr( $it[1] ); ?>" style="--i:<?php echo (int) $i; ?>"><i></i><em></em><span><?php echo esc_html( $it[0] ); ?></span></div>
			<?php endforeach; ?>
		</div>
		<div class="sln-ppc-gv-legend"><span class="is-main">More budget</span><span>Limited or excluded</span></div>
		<?php
		break;

	case 'asset-group':
		?>
		<div class="sln-ppc-gv-card">
			<div class="sln-ppc-gv-title"><?php echo sln_ppc_icon( 'sparkles' ); // phpcs:ignore ?> Asset group: Kitchen</div>
			<div class="sln-ppc-gv-assetrow">
				<?php foreach ( array( 'Headlines', 'Images', 'Video', 'Products' ) as $i => $a ) : ?>
					<span style="--i:<?php echo (int) $i; ?>"><?php echo esc_html( $a ); ?></span>
				<?php endforeach; ?>
			</div>
			<div class="sln-ppc-gv-signals2">
				<b>Audience signals</b>
				<?php foreach ( array( 'Past customers', 'Site visitors', 'Searched: chef knives' ) as $i => $sig ) : ?>
					<em style="--i:<?php echo (int) $i; ?>"><?php echo esc_html( $sig ); ?></em>
				<?php endforeach; ?>
			</div>
			<div class="sln-ppc-gv-excl"><?php echo sln_ppc_icon( 'filter-x' ); // phpcs:ignore ?> Brand searches excluded</div>
		</div>
		<?php
		break;

	case 'revenue':
		$events = array( 'view_item', 'add_to_cart', 'begin_checkout', 'purchase' );
		?>
		<div class="sln-ppc-gv-card">
			<div class="sln-ppc-gv-title"><?php echo sln_ppc_icon( 'line-chart' ); // phpcs:ignore ?> GA4 ecommerce events</div>
			<ul class="sln-ppc-gv-events">
				<?php foreach ( $events as $i => $e ) : ?>
					<li style="--i:<?php echo (int) $i; ?>" class="<?php echo 'purchase' === $e ? 'is-key' : ''; ?>"><i></i><span><?php echo esc_html( $e ); ?></span><em><?php echo 'purchase' === $e ? 'value sent' : 'received'; ?></em></li>
				<?php endforeach; ?>
			</ul>
			<div class="sln-ppc-gv-legend"><span class="is-main">Bidding on revenue</span><span>Not just clicks</span></div>
		</div>
		<?php
		break;
endswitch;
