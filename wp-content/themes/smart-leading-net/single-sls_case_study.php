<?php
/**
 * Single Case Study template (reusable for every case study).
 *
 * Sections render only when they have content.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sln_cs_dir = get_template_directory();
$sln_cs_uri = get_template_directory_uri();
wp_enqueue_style( 'sln-case-study-single', $sln_cs_uri . '/assets/css/case-study-single.css', array( 'sln-style' ), filemtime( $sln_cs_dir . '/assets/css/case-study-single.css' ) );
wp_enqueue_script( 'sln-case-study-single', $sln_cs_uri . '/assets/js/case-study-single.js', array(), filemtime( $sln_cs_dir . '/assets/js/case-study-single.js' ), true );

get_header();

while ( have_posts() ) :
	the_post();
	$post_id  = get_the_ID();
	$d        = sln_get_case_study_data( $post_id );
	$services = sln_case_study_service_labels( $d );
	$headline = $d['headline'] ? $d['headline'] : get_the_title();
	$hub      = get_page_by_path( 'case-studies' );
	$hub_url  = $hub ? get_permalink( $hub ) : home_url( '/case-studies/' );
	$cta_url  = sln_case_study_cta_url( $d );
	$domain   = $d['website'] ? preg_replace( '#^www\.#', '', (string) wp_parse_url( $d['website'], PHP_URL_HOST ) ) : '';
	$logo_id  = get_post_thumbnail_id();

	/**
	 * Paragraphs from plain textarea content.
	 */
	$paras = static function ( $text ) {
		return wpautop( esc_html( $text ) );
	};

	/**
	 * Headline change for a result: explicit value, else auto from numeric before/after.
	 */
	$change = static function ( $r ) {
		if ( '' !== $r['change'] ) {
			return $r['change'];
		}
		$num = static function ( $v ) {
			$v = str_replace( array( ',', '$', '£', '€', ' ' ), '', (string) $v );
			return is_numeric( rtrim( $v, '%' ) ) ? (float) rtrim( $v, '%' ) : null;
		};
		$b = $num( $r['before'] );
		$a = $num( $r['after'] );
		if ( null === $b || null === $a ) {
			return '';
		}
		$is_pct = '%' === substr( trim( $r['before'] ), -1 ) && '%' === substr( trim( $r['after'] ), -1 );
		if ( $is_pct ) {
			$diff = $a - $b;
			return ( $diff >= 0 ? '+' : '−' ) . rtrim( rtrim( number_format( abs( $diff ), 1 ), '0' ), '.' ) . ' pts';
		}
		if ( 0.0 === $b ) {
			return '';
		}
		$pct = ( $a - $b ) / $b * 100;
		return ( $pct >= 0 ? '+' : '−' ) . number_format( abs( $pct ) ) . '%';
	};

	// Table of contents (only sections that exist).
	$toc = array();
	if ( $d['results'] ) {
		$toc['results'] = __( 'Key results', 'smart-leading-net' );
	}
	if ( $d['about'] ) {
		$toc['about'] = __( 'About the client', 'smart-leading-net' );
	}
	if ( $d['challenge'] ) {
		$toc['challenge'] = __( 'The challenge', 'smart-leading-net' );
	}
	if ( $d['goals'] ) {
		$toc['goals'] = __( 'Goals', 'smart-leading-net' );
	}
	if ( $d['scope'] ) {
		$toc['scope'] = __( 'Project scope', 'smart-leading-net' );
	}
	if ( $d['approach'] ) {
		$toc['approach'] = __( 'What we did', 'smart-leading-net' );
	}
	if ( $d['milestones'] ) {
		$toc['journey'] = __( 'The journey', 'smart-leading-net' );
	}
	if ( $d['evidence'] ) {
		$toc['evidence'] = __( 'Results & evidence', 'smart-leading-net' );
	}
	if ( $d['takeaways'] ) {
		$toc['takeaways'] = __( 'Key takeaways', 'smart-leading-net' );
	}
	// Platforms named in the results' sources, for the "real data" strip.
	$platforms = array(
		'Google Analytics 4'      => '/\bGA4\b|google analytics/i',
		'Google Search Console'   => '/search console|\bGSC\b/i',
		'Google Business Profile' => '/business profile|\bGBP\b|\bGMB\b/i',
		'Google Ads'              => '/google ads/i',
		'GoHighLevel'             => '/highlevel|\bGHL\b/i',
		'CallRail'                => '/callrail/i',
		'Semrush'                 => '/semrush/i',
		'Ahrefs'                  => '/ahrefs/i',
		'Meta Ads'                => '/meta ads|facebook ads/i',
	);
	$sources   = array();
	foreach ( (array) $d['results'] as $r ) {
		$src = trim( (string) $r['source'] );
		if ( '' === $src ) {
			continue;
		}
		$hit = false;
		foreach ( $platforms as $name => $re ) {
			if ( preg_match( $re, $src ) ) {
				$sources[ $name ] = $name;
				$hit              = true;
			}
		}
		if ( ! $hit ) {
			$sources[ $src ] = $src;
		}
	}
	$related = sln_case_study_related( $post_id, $d );
	$sec     = 0;
	?>
<main id="primary" class="sls-csd">

	<header class="sls-csd__hero">
		<div class="sls-csd__wrap">
			<nav class="sls-csd__crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'smart-leading-net' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
				<span aria-hidden="true">/</span>
				<a href="<?php echo esc_url( $hub_url ); ?>">Case Studies</a>
				<span aria-hidden="true">/</span>
				<span aria-current="page"><?php the_title(); ?></span>
			</nav>

			<div class="sls-csd__hero-grid">
				<div class="sls-csd__hero-copy" data-reveal="up">
					<?php if ( $services ) : ?>
						<ul class="sls-csd__tags" aria-label="<?php esc_attr_e( 'Services', 'smart-leading-net' ); ?>">
							<?php foreach ( $services as $label ) : ?>
								<li><?php echo esc_html( $label ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
					<h1><?php echo esc_html( $headline ); ?></h1>
					<?php if ( $d['summary'] ) : ?>
						<p class="sls-csd__lead"><?php echo esc_html( $d['summary'] ); ?></p>
					<?php endif; ?>
					<div class="sls-csd__actions">
						<?php if ( $d['results'] || $d['evidence'] ) : ?>
							<a class="sls-csd__btn" href="#<?php echo $d['results'] ? 'results' : 'evidence'; ?>"><?php esc_html_e( 'See the results', 'smart-leading-net' ); ?></a>
						<?php else : ?>
							<a class="sls-csd__btn" href="<?php echo esc_url( $cta_url ); ?>"><?php echo esc_html( $d['cta_label'] ); ?></a>
						<?php endif; ?>
						<?php if ( $d['website'] ) : ?>
							<a class="sls-csd__btn sls-csd__btn--ghost" href="<?php echo esc_url( $d['website'] ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Visit website', 'smart-leading-net' ); ?> <span aria-hidden="true">↗</span></a>
						<?php endif; ?>
					</div>
				</div>

				<div class="sls-csd__visual" data-reveal="right">
					<?php if ( $d['hero_image'] ) : ?>
						<?php
						$view_tag = $d['website'] ? 'a' : 'div';
						$view_att = $d['website'] ? ' href="' . esc_url( $d['website'] ) . '" target="_blank" rel="noopener"' : ' tabindex="0"';
						?>
						<<?php echo $view_tag . $view_att; // phpcs:ignore WordPress.Security.EscapeOutput ?> class="sls-csd__project" aria-label="<?php echo esc_attr( sprintf( 'Preview of the %s website', get_the_title() ) ); ?>">
							<span class="sls-csd__browser-bar" aria-hidden="true"><i></i><i></i><i></i><span><?php echo esc_html( $domain ); ?></span></span>
							<span class="sls-csd__project-view">
								<?php echo wp_get_attachment_image( $d['hero_image'], 'medium_large', false, array( 'class' => 'sls-csd__project-img', 'loading' => 'eager', 'sizes' => '(max-width: 991px) 100vw, 600px', 'alt' => esc_attr( sprintf( '%s website, full page', get_the_title() ) ) ) ); ?>
								<span class="sls-csd__project-hint" aria-hidden="true"><?php esc_html_e( 'Hover to scroll', 'smart-leading-net' ); ?></span>
							</span>
							<span class="sls-csd__project-title"><?php the_title(); ?><?php if ( $d['website'] ) : ?> <span aria-hidden="true">↗</span><?php endif; ?></span>
						</<?php echo $view_tag; // phpcs:ignore WordPress.Security.EscapeOutput ?>>
					<?php else : ?>
						<div class="sls-csd__logo-card">
							<?php if ( $logo_id ) : ?>
								<?php echo wp_get_attachment_image( $logo_id, 'medium', false, array( 'loading' => 'eager', 'alt' => esc_attr( get_the_title() . ' logo' ) ) ); ?>
							<?php else : ?>
								<span class="sls-csd__logo-text"><?php the_title(); ?></span>
							<?php endif; ?>
							<?php if ( $domain ) : ?><small><?php echo esc_html( $domain ); ?></small><?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</header>

	<?php
	$snapshot = array_filter(
		array(
			'Industry'  => $d['industry'],
			'Location'  => $d['location'],
			'Services'  => implode( ', ', $services ),
			'Timeline'  => $d['period'],
		)
	);
	if ( $snapshot ) :
		?>
	<section class="sls-csd__snapshot" aria-label="<?php esc_attr_e( 'Project snapshot', 'smart-leading-net' ); ?>">
		<div class="sls-csd__wrap">
			<dl class="sls-csd__snap-grid" data-reveal="up">
				<?php foreach ( $snapshot as $label => $value ) : ?>
					<div><dt><?php echo esc_html( $label ); ?></dt><dd><?php echo esc_html( $value ); ?></dd></div>
				<?php endforeach; ?>
			</dl>
		</div>
	</section>
	<?php endif; ?>

	<?php if ( $d['results'] ) : ?>
	<section id="results" class="sls-csd__results">
		<div class="sls-csd__wrap">
			<p class="sls-csd__eyebrow" data-reveal="up"><?php esc_html_e( 'The outcome', 'smart-leading-net' ); ?></p>
			<h2><?php echo esc_html( $d['results_title'] ? $d['results_title'] : __( 'Key results', 'smart-leading-net' ) ); ?></h2>
			<div data-reveal-group class="sls-csd__stats sls-csd__stats--<?php echo (int) min( 4, count( $d['results'] ) ); ?>">
				<?php foreach ( $d['results'] as $r ) : ?>
					<?php $is_total = '' === $r['before'] && '' !== $r['after']; $big = ( $is_total && '' === $r['change'] ) ? $r['after'] : $change( $r ); ?>
					<article class="sls-csd__stat" data-reveal="up">
						<?php if ( $big ) : ?>
							<p class="sls-csd__stat-big" data-count="<?php echo esc_attr( $big ); ?>"><?php echo esc_html( $big ); ?></p>
						<?php endif; ?>
						<h3><?php echo esc_html( $r['label'] ); ?></h3>
						<?php if ( ! $is_total && ( '' !== $r['before'] || '' !== $r['after'] ) ) : ?>
							<p class="sls-csd__stat-ba">
								<span><small>Before</small><?php echo esc_html( '' !== $r['before'] ? $r['before'] : '—' ); ?></span>
								<span class="sls-csd__arrow" aria-hidden="true">→</span>
								<span><small>After</small><strong><?php echo esc_html( '' !== $r['after'] ? $r['after'] : '—' ); ?></strong></span>
							</p>
						<?php endif; ?>
						<?php if ( $r['period'] || $r['source'] ) : ?>
							<p class="sls-csd__stat-meta"><?php echo esc_html( implode( ' · ', array_filter( array( $r['period'], $r['source'] ? 'Source: ' . $r['source'] : '' ) ) ) ); ?></p>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
			<?php if ( $sources ) : ?>
				<div class="sls-csd__sources" data-reveal="up">
					<span class="sls-csd__sources-label"><svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M12 2 4 5v6c0 5 3.4 9.7 8 11 4.6-1.3 8-6 8-11V5l-8-3Zm-1.2 14.2-3.5-3.5 1.4-1.4 2.1 2.1 4.6-4.6 1.4 1.4-6 6Z"/></svg><?php esc_html_e( 'Real data, no estimates. Every number comes from:', 'smart-leading-net' ); ?></span>
					<ul>
						<?php foreach ( $sources as $src ) : ?><li><?php echo esc_html( $src ); ?></li><?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
			<?php if ( $d['results_note'] ) : ?>
				<p class="sls-csd__note" data-reveal="up"><?php echo esc_html( $d['results_note'] ); ?></p>
			<?php endif; ?>
		</div>
	</section>
	<?php endif; ?>

	<?php if ( $d['about'] || $d['challenge'] || $d['goals'] || $d['scope'] || $d['approach'] || $d['milestones'] || $d['evidence'] || $d['takeaways'] || $d['quote'] ) : ?>
	<div class="sls-csd__body">
		<div class="sls-csd__wrap sls-csd__layout">
			<article class="sls-csd__story">

				<?php if ( $d['about'] ) : ?>
				<section id="about" class="sls-csd__block" data-reveal="up">
					<p class="sls-csd__eyebrow"><?php printf( "%02d", ++$sec ); ?></p>
					<h2><?php esc_html_e( 'About the client', 'smart-leading-net' ); ?></h2>
					<?php echo $paras( $d['about'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				</section>
				<?php endif; ?>

				<?php if ( $d['challenge'] ) : ?>
				<section id="challenge" class="sls-csd__block" data-reveal="up">
					<p class="sls-csd__eyebrow"><?php printf( "%02d", ++$sec ); ?></p>
					<h2><?php esc_html_e( 'The challenge', 'smart-leading-net' ); ?></h2>
					<?php echo $paras( $d['challenge'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<?php if ( $d['challenge_img'] ) : ?>
						<figure class="sls-csd__figure">
							<?php echo sln_cs_screenshot( $d['challenge_img'], get_the_title() . ' before SLS', true ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
							<figcaption><?php esc_html_e( 'Starting point', 'smart-leading-net' ); ?></figcaption>
						</figure>
					<?php endif; ?>
				</section>
				<?php endif; ?>

				<?php if ( $d['goals'] ) : ?>
				<section id="goals" class="sls-csd__block" data-reveal="up">
					<p class="sls-csd__eyebrow"><?php printf( "%02d", ++$sec ); ?></p>
					<h2><?php esc_html_e( 'Goals', 'smart-leading-net' ); ?></h2>
					<ul class="sls-csd__goals" data-reveal-group>
						<?php foreach ( $d['goals'] as $goal ) : ?>
							<li data-reveal="left"><?php echo esc_html( $goal ); ?></li>
						<?php endforeach; ?>
					</ul>
				</section>
				<?php endif; ?>

				<?php if ( $d['scope'] ) : ?>
				<section id="scope" class="sls-csd__block" data-reveal="up">
					<p class="sls-csd__eyebrow"><?php printf( '%02d', ++$sec ); ?></p>
					<h2><?php esc_html_e( 'Project scope', 'smart-leading-net' ); ?></h2>
					<div class="sls-csd__scope" data-reveal-group>
						<?php foreach ( $d['scope'] as $i => $item ) : ?>
							<article class="sls-csd__scope-item" data-reveal="up">
								<span class="sls-csd__scope-num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
								<h3><?php echo esc_html( $item['title'] ); ?></h3>
								<?php if ( $item['text'] ) : ?><p><?php echo esc_html( $item['text'] ); ?></p><?php endif; ?>
							</article>
						<?php endforeach; ?>
					</div>
				</section>
				<?php endif; ?>

				<?php if ( $d['approach'] ) : ?>
				<section id="approach" class="sls-csd__block" data-reveal="up">
					<p class="sls-csd__eyebrow"><?php printf( "%02d", ++$sec ); ?></p>
					<h2><?php esc_html_e( 'What we did', 'smart-leading-net' ); ?></h2>
					<ol class="sls-csd__steps" data-reveal-group>
						<?php foreach ( $d['approach'] as $step ) : ?>
							<li data-reveal="left">
								<h3><?php echo esc_html( $step['title'] ); ?></h3>
								<?php if ( $step['text'] ) : ?><?php echo $paras( $step['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ol>
				</section>
				<?php endif; ?>

				<?php if ( $d['milestones'] ) : ?>
				<section id="journey" class="sls-csd__block" data-reveal="up">
					<p class="sls-csd__eyebrow"><?php printf( '%02d', ++$sec ); ?></p>
					<h2><?php esc_html_e( 'The journey', 'smart-leading-net' ); ?></h2>
					<ol class="sls-csd__journey" data-reveal-group>
						<?php foreach ( $d['milestones'] as $m ) : ?>
							<li class="sls-csd__milestone<?php echo $m['image'] ? ' has-image' : ''; ?>" data-reveal="up">
								<span class="sls-csd__milestone-dot" aria-hidden="true"></span>
								<div class="sls-csd__milestone-card">
									<?php if ( $m['label'] ) : ?><p class="sls-csd__milestone-when"><?php echo esc_html( $m['label'] ); ?></p><?php endif; ?>
									<div class="sls-csd__milestone-body">
										<div>
											<?php if ( $m['title'] ) : ?><h3><?php echo esc_html( $m['title'] ); ?></h3><?php endif; ?>
											<?php if ( $m['text'] ) : ?><?php echo $paras( $m['text'] ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php endif; ?>
										</div>
										<?php if ( $m['image'] ) : ?>
											<span class="sls-csd__milestone-img"><?php echo wp_get_attachment_image( $m['image'], 'medium', false, array( 'alt' => esc_attr( $m['title'] ) ) ); ?></span>
										<?php endif; ?>
									</div>
								</div>
							</li>
						<?php endforeach; ?>
					</ol>
				</section>
				<?php endif; ?>

				<?php if ( $d['evidence'] ) : ?>
				<section id="evidence" class="sls-csd__block">
					<p class="sls-csd__eyebrow"><?php printf( "%02d", ++$sec ); ?></p>
					<h2><?php esc_html_e( 'Results & evidence', 'smart-leading-net' ); ?></h2>
					<?php foreach ( $d['evidence'] as $ev ) : ?>
						<figure class="sls-csd__evidence" data-reveal="zoom">
							<?php if ( $ev['title'] ) : ?><h3><?php echo esc_html( $ev['title'] ); ?></h3><?php endif; ?>
							<?php if ( $ev['before'] && $ev['after'] ) : ?>
								<div class="sls-csd__compare">
									<div><span class="sls-csd__badge">Before</span><?php echo sln_cs_screenshot( $ev['before'], 'Before: ' . $ev['title'], true ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
									<div><span class="sls-csd__badge sls-csd__badge--after">After</span><?php echo sln_cs_screenshot( $ev['after'], 'After: ' . $ev['title'], true ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
								</div>
							<?php elseif ( $ev['after'] || $ev['before'] ) : ?>
								<?php $one = $ev['after'] ? $ev['after'] : $ev['before']; ?>
								<div class="sls-csd__shot"><?php echo sln_cs_screenshot( $one, $ev['title'], true ); // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
							<?php endif; ?>
							<?php if ( $ev['text'] ) : ?><figcaption><?php echo esc_html( $ev['text'] ); ?></figcaption><?php endif; ?>
						</figure>
					<?php endforeach; ?>
				</section>
				<?php endif; ?>

				<?php if ( $d['takeaways'] ) : ?>
				<section id="takeaways" class="sls-csd__block" data-reveal="up">
					<p class="sls-csd__eyebrow"><?php printf( '%02d', ++$sec ); ?></p>
					<h2><?php esc_html_e( 'Key takeaways', 'smart-leading-net' ); ?></h2>
					<ul class="sls-csd__takeaways" data-reveal-group>
						<?php foreach ( $d['takeaways'] as $t ) : ?>
							<li data-reveal="up">
								<span class="sls-csd__takeaway-icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="20" height="20"><path fill="currentColor" d="M9 21h6v-1H9v1Zm3-19a7 7 0 0 0-4 12.7V17a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-2.3A7 7 0 0 0 12 2Z"/></svg></span>
								<div>
									<h3><?php echo esc_html( $t['title'] ); ?></h3>
									<?php if ( $t['text'] ) : ?><p><?php echo esc_html( $t['text'] ); ?></p><?php endif; ?>
								</div>
							</li>
						<?php endforeach; ?>
					</ul>
				</section>
				<?php endif; ?>

				<?php if ( $d['quote'] ) : ?>
				<blockquote class="sls-csd__quote" data-reveal="up">
					<p><?php echo esc_html( $d['quote'] ); ?></p>
					<?php if ( $d['quote_name'] ) : ?>
						<footer><strong><?php echo esc_html( $d['quote_name'] ); ?></strong><?php if ( $d['quote_role'] ) : ?><span><?php echo esc_html( $d['quote_role'] ); ?></span><?php endif; ?></footer>
					<?php endif; ?>
				</blockquote>
				<?php endif; ?>
			</article>

			<aside class="sls-csd__aside">
				<div class="sls-csd__aside-inner">
					<?php if ( count( $toc ) > 1 ) : ?>
					<nav class="sls-csd__toc" aria-label="<?php esc_attr_e( 'On this page', 'smart-leading-net' ); ?>">
						<p><?php esc_html_e( 'On this page', 'smart-leading-net' ); ?></p>
						<ul>
							<?php foreach ( $toc as $id => $label ) : ?>
								<li><a href="#<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					</nav>
					<?php endif; ?>
					<div class="sls-csd__mini-cta">
						<p><strong><?php esc_html_e( 'Have a similar goal?', 'smart-leading-net' ); ?></strong></p>
						<p><?php esc_html_e( 'Share your website and we will tell you where the leads are.', 'smart-leading-net' ); ?></p>
						<a class="sls-csd__btn" href="<?php echo esc_url( $cta_url ); ?>"><?php echo esc_html( $d['cta_label'] ); ?></a>
					</div>
				</div>
			</aside>
		</div>
	</div>
	<?php endif; ?>

	<?php if ( $related ) : ?>
	<section class="sls-csd__related">
		<div class="sls-csd__wrap">
			<div class="sls-csd__related-head">
				<h2><?php esc_html_e( 'More case studies', 'smart-leading-net' ); ?></h2>
				<a href="<?php echo esc_url( $hub_url ); ?>"><?php esc_html_e( 'View all projects', 'smart-leading-net' ); ?> →</a>
			</div>
			<div class="sls-csd__cards" data-reveal-group>
				<?php foreach ( $related as $rel ) : ?>
					<?php $rd = sln_get_case_study_data( $rel->ID ); ?>
					<a class="sls-csd__card" data-reveal="up" href="<?php echo esc_url( get_permalink( $rel ) ); ?>">
						<span class="sls-csd__card-logo">
							<?php if ( has_post_thumbnail( $rel ) ) : ?>
								<?php echo get_the_post_thumbnail( $rel, 'medium', array( 'alt' => '' ) ); ?>
							<?php else : ?>
								<strong><?php echo esc_html( get_the_title( $rel ) ); ?></strong>
							<?php endif; ?>
						</span>
						<span class="sls-csd__card-body">
							<?php if ( $rd['industry'] ) : ?><small><?php echo esc_html( $rd['industry'] ); ?></small><?php endif; ?>
							<strong><?php echo esc_html( get_the_title( $rel ) ); ?></strong>
							<?php if ( has_excerpt( $rel ) ) : ?><span><?php echo esc_html( get_the_excerpt( $rel ) ); ?></span><?php endif; ?>
							<em><?php esc_html_e( 'Read case study', 'smart-leading-net' ); ?> →</em>
						</span>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php endif; ?>

	<div class="sls-csd__sticky-cta" aria-hidden="true">
		<span><?php esc_html_e( 'Want results like these?', 'smart-leading-net' ); ?></span>
		<a class="sls-csd__btn" href="<?php echo esc_url( $cta_url ); ?>" tabindex="-1"><?php esc_html_e( 'Talk to us', 'smart-leading-net' ); ?></a>
	</div>

	<section class="sls-csd__cta">
		<div class="sls-csd__wrap">
			<div class="sls-csd__cta-box" data-reveal="up">
				<div>
					<h2><?php echo esc_html( $d['cta_title'] ); ?></h2>
					<?php if ( $d['cta_text'] ) : ?><p><?php echo esc_html( $d['cta_text'] ); ?></p><?php endif; ?>
				</div>
				<a class="sls-csd__btn sls-csd__btn--light" href="<?php echo esc_url( $cta_url ); ?>"><?php echo esc_html( $d['cta_label'] ); ?></a>
			</div>
		</div>
	</section>
</main>
	<?php
endwhile;

get_footer();
