<?php
/**
 * Homepage hero visual — a clean Lead Analytics card drawn in code.
 * Three tabs, one per hero slide; home.js switches the tab with the slide.
 * All dashboard figures are sample data and the card says so.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$card_slots = isset( $args['card_slots'] ) ? $args['card_slots'] : array();

$dash_tabs = array( 'Overview', 'New leads', 'Visibility' );

$dash_kpis = array(
	array( 'label' => 'Leads', 'value' => 1284, 'change' => '+18%' ),
	array( 'label' => 'Calls', 'value' => 412, 'change' => '+9%' ),
	array( 'label' => 'Booked', 'value' => 96, 'change' => '+24%' ),
);
$dash_bars = array( 38, 46, 42, 58, 54, 70, 78, 92 );

$dash_feed = array(
	array( 'icon' => 'phone', 'tone' => 'blue', 'title' => 'Phone call', 'meta' => 'Google Ads', 'tag' => 'New', 'tag_tone' => 'blue' ),
	array( 'icon' => 'form', 'tone' => 'violet', 'title' => 'Quote form', 'meta' => 'Facebook Ads', 'tag' => 'New', 'tag_tone' => 'blue' ),
	array( 'icon' => 'reply', 'tone' => 'orange', 'title' => 'Missed call', 'meta' => 'Auto reply sent', 'tag' => 'Replied', 'tag_tone' => 'orange' ),
	array( 'icon' => 'calendar', 'tone' => 'green', 'title' => 'Website chat', 'meta' => 'Google search', 'tag' => 'Booked', 'tag_tone' => 'green' ),
);

$dash_keywords = array(
	array( 'term' => 'plumber near me', 'where' => 'Google Maps', 'rank' => 2 ),
	array( 'term' => 'emergency plumber', 'where' => 'Google search', 'rank' => 1 ),
	array( 'term' => 'water heater repair', 'where' => 'Google search', 'rank' => 3 ),
);
$dash_ai = array( 'ChatGPT', 'Gemini', 'AI Overviews' );
?>

<div class="hero-banner__visual home-dash-wrap" aria-hidden="true">
	<div class="home-dash">
		<div class="home-dash__top">
			<span class="home-dash__brand">
				<span class="home-dash__logo"><?php echo sln_home_icon( 'chart' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<?php esc_html_e( 'Lead Analytics', 'smart-leading-net' ); ?>
			</span>
			<span class="home-dash__tabs">
				<?php foreach ( $dash_tabs as $i => $tab ) : ?>
					<span class="home-dash__tab<?php echo 0 === $i ? ' is-active' : ''; ?>" data-dash-nav="<?php echo esc_attr( (string) $i ); ?>"><?php echo esc_html( $tab ); ?></span>
				<?php endforeach; ?>
			</span>
		</div>

		<div class="home-dash__main">
			<?php /* View 0 — overview */ ?>
			<section class="home-dash__view is-active" data-dash-view="0">
				<div class="home-dash__kpis">
					<?php foreach ( $dash_kpis as $kpi ) : ?>
						<div class="home-dash__kpi">
							<span class="home-dash__kpi-label"><?php echo esc_html( $kpi['label'] ); ?></span>
							<strong class="home-dash__kpi-value" data-dash-count="<?php echo esc_attr( (string) $kpi['value'] ); ?>"><?php echo esc_html( number_format( $kpi['value'] ) ); ?></strong>
							<span class="home-dash__up"><?php echo sln_home_icon( 'trend' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $kpi['change'] ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="home-dash__chart">
					<span class="home-dash__label"><?php esc_html_e( 'Leads per week', 'smart-leading-net' ); ?></span>
					<div class="home-dash__bars">
						<?php foreach ( $dash_bars as $b => $height ) : ?>
							<span class="home-dash__bar-col<?php echo count( $dash_bars ) - 1 === $b ? ' is-peak' : ''; ?>" style="--h: <?php echo esc_attr( (string) $height ); ?>%; --d: <?php echo esc_attr( (string) ( $b * 50 ) ); ?>ms"></span>
						<?php endforeach; ?>
					</div>
				</div>
			</section>

			<?php /* View 1 — new leads */ ?>
			<section class="home-dash__view" data-dash-view="1">
				<ul class="home-dash__list">
					<?php foreach ( $dash_feed as $row ) : ?>
						<li class="home-dash__row">
							<span class="home-dash__icon home-dash__tone--<?php echo esc_attr( $row['tone'] ); ?>"><?php echo sln_home_icon( $row['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<span class="home-dash__row-text">
								<strong><?php echo esc_html( $row['title'] ); ?></strong>
								<span><?php echo esc_html( $row['meta'] ); ?></span>
							</span>
							<span class="home-dash__tag home-dash__tag--<?php echo esc_attr( $row['tag_tone'] ); ?>"><?php echo esc_html( $row['tag'] ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
			</section>

			<?php /* View 2 — search visibility */ ?>
			<section class="home-dash__view" data-dash-view="2">
				<ul class="home-dash__list">
					<?php foreach ( $dash_keywords as $kw ) : ?>
						<li class="home-dash__row">
							<span class="home-dash__rank">#<?php echo esc_html( (string) $kw['rank'] ); ?></span>
							<span class="home-dash__row-text">
								<strong><?php echo esc_html( $kw['term'] ); ?></strong>
								<span><?php echo esc_html( $kw['where'] ); ?></span>
							</span>
							<span class="home-dash__tag home-dash__tag--green"><?php esc_html_e( 'Top 3', 'smart-leading-net' ); ?></span>
						</li>
					<?php endforeach; ?>
				</ul>
				<div class="home-dash__ai">
					<span class="home-dash__label"><?php esc_html_e( 'Mentioned in AI search', 'smart-leading-net' ); ?></span>
					<span class="home-dash__chips">
						<?php foreach ( $dash_ai as $engine ) : ?>
							<span class="home-dash__chip"><?php echo sln_home_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $engine ); ?></span>
						<?php endforeach; ?>
					</span>
				</div>
			</section>
		</div>

		<p class="home-dash__note"><?php esc_html_e( 'Sample data for illustration', 'smart-leading-net' ); ?></p>
	</div>

	<?php if ( $card_slots ) : ?>
		<div class="home-hero__cards">
			<?php foreach ( $card_slots as $slot => $face ) : ?>
				<div class="home-hero__card home-hero__card--<?php echo esc_attr( $slot ); ?>">
					<span class="home-hero__card-icon home-hero__card-icon--<?php echo esc_attr( $face['tone'] ); ?>"><?php echo sln_home_icon( $face['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<span class="home-hero__card-text">
						<strong class="home-hero__card-value"><?php echo esc_html( $face['value'] ); ?></strong>
						<span class="home-hero__card-label"><?php echo esc_html( $face['label'] ); ?></span>
					</span>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
