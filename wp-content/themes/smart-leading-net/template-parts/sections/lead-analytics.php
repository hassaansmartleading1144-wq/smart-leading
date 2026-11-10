<?php
/** Official Lead Analytics product overview. No client performance data is displayed. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$views = array(
 'performance' => array( 'label'=>'Performance', 'heading'=>'Your marketing channels, in one view.', 'intro'=>'Review connected platforms over the date range you choose.', 'items'=>array( array('Website & search','Traffic, search clicks and impressions'), array('Paid campaigns','Ad spend, clicks and conversions'), array('Local presence','Google Business Profile performance') ) ),
 'leads' => array( 'label'=>'Leads', 'heading'=>'Know where your leads come from.', 'intro'=>'Review unique leads with source and campaign context.', 'items'=>array( array('Website forms','Form submissions in your client workspace'), array('Tracked calls','Call activity and source information'), array('CRM & lead forms','GoHighLevel contacts and Meta lead forms') ) ),
 'tasks' => array( 'label'=>'Tasks', 'heading'=>'Follow the work behind your marketing.', 'intro'=>'Keep client tasks, responsibilities and progress together.', 'items'=>array( array('Clear ownership','Assigned team members and due dates'), array('Progress tracking','Open, in progress and completed work'), array('Team notifications','Email notifications when tasks are assigned') ) ),
 'reports' => array( 'label'=>'Reports', 'heading'=>'See progress with the full context.', 'intro'=>'Open reports for your account inside your own workspace.', 'items'=>array( array('Flexible reporting','Choose a reporting date range'), array('Performance summaries','Lead, spend and conversion highlights'), array('Your client workspace','Access to your own tasks and reports') ) ),
);
?>
<section class="sln-la" id="marketing-dashboard" aria-labelledby="sln-la-title">
 <div class="sln-la__wrap"><div class="sln-la__grid">
  <div class="sln-la__copy">
   <h2 id="sln-la-title">Your performance, leads and tasks.<br><span>One marketing dashboard.</span></h2>
   <p class="sln-la__intro">Lead Analytics is our all-in-one marketing analytics and client management platform. See how your marketing is performing, where leads come from and what our team is working on—all in your own client workspace.</p>
   <ol class="sln-la__steps">
    <li><span class="sln-la__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 19h18M6 15v-4m6 4V5m6 10V8"/></svg></span><div><h3>See your marketing performance</h3><p>Review traffic, search visibility, ad spend and conversions from your connected platforms.</p></div></li>
    <li><span class="sln-la__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="9" cy="8" r="3"/><path d="M3 20v-2a6 6 0 0 1 12 0v2m2-12 2 2 3-4"/></svg></span><div><h3>Track unique leads by source</h3><p>Bring calls, website forms, CRM contacts and Meta lead forms together, with duplicate leads counted once.</p></div></li>
    <li><span class="sln-la__icon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="m8 10 2 2 5-5m-7 9h8"/></svg></span><div><h3>Follow tasks and client reports</h3><p>See task owners, due dates and progress, then review reports for your account.</p></div></li>
   </ol>
   <a class="sln-la__cta" href="https://leads.smartleading.net/" target="_blank" rel="noopener noreferrer">Explore Lead Analytics <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14m-5-5 5 5-5 5"/></svg><span class="screen-reader-text"> (opens in a new tab)</span></a>
  </div>
  <div class="sln-la__workspace" aria-label="Lead Analytics platform features">
   <div class="sln-la__brand"><img src="<?php echo esc_url( SLN_THEME_URI . '/assets/images/lead-analytics/logo.webp' ); ?>" alt="Lead Analytics" width="210" height="80" loading="lazy"><span>Client workspace</span></div>
   <div class="sln-la__nav" role="tablist" aria-label="Explore dashboard features">
    <?php foreach ( $views as $key => $view ) : ?><button type="button" role="tab" id="sln-la-tab-<?php echo esc_attr($key); ?>" aria-controls="sln-la-panel-<?php echo esc_attr($key); ?>" aria-selected="<?php echo 'performance' === $key ? 'true' : 'false'; ?>" tabindex="<?php echo 'performance' === $key ? '0' : '-1'; ?>"><?php echo esc_html($view['label']); ?></button><?php endforeach; ?>
   </div>
   <?php foreach ( $views as $key => $view ) : ?>
   <div class="sln-la__panel" role="tabpanel" id="sln-la-panel-<?php echo esc_attr($key); ?>" aria-labelledby="sln-la-tab-<?php echo esc_attr($key); ?>" tabindex="0" <?php echo 'performance' === $key ? '' : 'hidden'; ?>>
    <h3><?php echo esc_html($view['heading']); ?></h3><p><?php echo esc_html($view['intro']); ?></p>
    <ul><?php foreach ( $view['items'] as $item ) : ?><li><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m5 12 4 4L19 6"/></svg><div><strong><?php echo esc_html($item[0]); ?></strong><span><?php echo esc_html($item[1]); ?></span></div></li><?php endforeach; ?></ul>
   </div><?php endforeach; ?>
   <div class="sln-la__workspace-foot"><span>Built by Smart Leading Solutions</span><a href="https://leads.smartleading.net/login" target="_blank" rel="noopener noreferrer">Client login<span class="screen-reader-text"> (opens in a new tab)</span></a></div>
  </div>
 </div><div class="sln-la__integrations"><p>Connect the platforms you use</p><ul><?php foreach ( array('google-analytics'=>'Google Analytics 4','google-search-console'=>'Google Search Console','google-business-profile'=>'Google Business Profile','google-ads'=>'Google Ads','meta'=>'Meta Ads','gohighlevel'=>'GoHighLevel','calls'=>'Call Tracking','forms'=>'Website Forms') as $key => $platform ) : ?><li><span class="sln-la__platform-mark" aria-hidden="true"><?php if ( 'calls' === $key ) : ?><svg viewBox="0 0 24 24"><path d="M5 3h4l2 5-3 2a15 15 0 0 0 6 6l2-3 5 2v4a2 2 0 0 1-2 2C10 21 3 14 3 5a2 2 0 0 1 2-2Z"/></svg><?php elseif ( 'forms' === $key ) : ?><svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M7 8h10M7 12h10M7 16h5"/></svg><?php else : ?><img src="<?php echo esc_url( SLN_THEME_URI . '/assets/images/lead-analytics/' . $key . '.webp' ); ?>" alt="" width="36" height="36" loading="lazy" decoding="async"><?php endif; ?></span><span><?php echo esc_html($platform); ?></span></li><?php endforeach; ?></ul></div><p class="sln-la__context">Available data depends on your connected platforms. Search clicks and website sessions are performance metrics, not CRM lead counts.</p></div>
</section>
