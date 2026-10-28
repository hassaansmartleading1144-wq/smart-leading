<?php
/**
 * Single blog post layout (design from smart-leading-blog-single.html).
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id         = get_the_ID();
$categories      = get_the_category();
$primary_cat     = ! empty( $categories ) ? $categories[0] : null;
$tags            = get_the_tags();
$author_id       = (int) get_the_author_meta( 'ID' );
$author_name     = get_the_author();
$author_bio      = sln_blog_get_meta( $post_id, 'author_bio', '' );
if ( '' === $author_bio ) {
	$author_bio = get_the_author_meta( 'description', $author_id );
}
$reviewer_name   = sln_blog_get_meta( $post_id, 'reviewer_name', '' );
$reviewer_role   = sln_blog_get_meta( $post_id, 'reviewer_role', '' );
$reviewer_image  = (int) sln_blog_get_meta( $post_id, 'reviewer_image', 0 );
$takeaways       = sln_blog_get_meta( $post_id, 'takeaways', array() );
$qa_left_title   = sln_blog_get_meta( $post_id, 'qa_left_title', '' );
$qa_left_items   = sln_blog_lines( sln_blog_get_meta( $post_id, 'qa_left_items', '' ) );
$qa_right_title  = sln_blog_get_meta( $post_id, 'qa_right_title', '' );
$qa_right_items  = sln_blog_lines( sln_blog_get_meta( $post_id, 'qa_right_items', '' ) );
$faqs            = sln_blog_get_meta( $post_id, 'faq', array() );
$cta             = sln_blog_cta_data( $post_id );
$related         = sln_blog_related_posts( $post_id, 3 );
$contact_url     = sln_blog_contact_url();
$about_url       = sln_blog_about_url();
$archive_url     = sln_blog_archive_url();
$excerpt         = has_excerpt() ? get_the_excerpt() : '';
$phone           = '512 764 7877';
$phone_tel       = '+15127647877';
$show_quick      = ( $qa_left_title && $qa_left_items ) || ( $qa_right_title && $qa_right_items );
$author_posts    = count_user_posts( $author_id, 'post', true );

$icon_cal   = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3.5" y="5" width="17" height="15" rx="2"/><path d="M3.5 10h17M8 3v4M16 3v4"/></svg>';
$icon_upd   = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 11a8 8 0 1 0-2.3 5.7"/><path d="M20 5v6h-6"/></svg>';
$verified   = '<svg class="verified" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l2.4 2.1 3.2-.3.9 3.1 2.8 1.6-1 3 1 3-2.8 1.6-.9 3.1-3.2-.3L12 22l-2.4-2.1-3.2.3-.9-3.1L2.7 15.5l1-3-1-3 2.8-1.6.9-3.1 3.2.3z"/><path d="M8 12.3l2.6 2.6L16.2 9.3" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>';

$share_linkedin = '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6.9 8.8H3.6V20h3.3V8.8zM5.2 3.5a1.9 1.9 0 1 0 0 3.8 1.9 1.9 0 0 0 0-3.8zM20.4 13.6c0-3-1.6-5-4.4-5-1.4 0-2.4.7-2.9 1.5V8.8H10V20h3.3v-5.8c0-1.5.6-2.6 2-2.6s1.8 1.2 1.8 2.6V20h3.3v-6.4z"/></svg>';
$share_fb       = '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M13.5 21v-7.5h2.6l.4-3h-3V8.6c0-.9.3-1.5 1.5-1.5h1.6V4.4c-.3 0-1.2-.1-2.3-.1-2.3 0-3.8 1.4-3.8 3.9v2.3H8v3h2.5V21h3z"/></svg>';
$share_x        = '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.8 3h3.1l-6.8 7.8L22 21h-6.2l-4.9-6.4L5.3 21H2.2l7.3-8.3L2 3h6.3l4.4 5.9L17.8 3zm-1.1 16.2h1.7L7.4 4.7H5.6l11.1 14.5z"/></svg>';
$share_copy     = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 13.5a4.5 4.5 0 0 0 6.4.4l2.7-2.7a4.5 4.5 0 0 0-6.4-6.4l-1.2 1.2"/><path d="M14 10.5a4.5 4.5 0 0 0-6.4-.4l-2.7 2.7a4.5 4.5 0 0 0 6.4 6.4l1.2-1.2"/></svg>';
$share_email    = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3.5 6.5 12 13l8.5-6.5"/></svg>';
$ai_icon        = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l1.8 4.6L18.5 9l-4.7 1.4L12 15l-1.8-4.6L5.5 9l4.7-1.4z"/><path d="M18 15l.8 2 2 .8-2 .8-.8 2-.8-2-2-.8 2-.8z"/></svg>';
?>

<div class="sln-blog"<?php echo '1' === (string) sln_blog_get_meta( $post_id, 'toc_include_h3', '' ) ? ' data-toc-h3="1"' : ''; ?>>
	<div class="sln-blog-progress read-progress" aria-hidden="true"><span id="readBar"></span></div>

	<article <?php post_class(); ?>>
		<section class="post-hero">
			<div class="sln-blog-wrap">
				<nav class="breadcrumb" aria-label="<?php esc_attr_e( 'Breadcrumb', 'smart-leading-net' ); ?>">
					<ol>
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'smart-leading-net' ); ?></a></li>
						<li><a href="<?php echo esc_url( $archive_url ); ?>"><?php esc_html_e( 'Blog', 'smart-leading-net' ); ?></a></li>
						<?php if ( $primary_cat ) : ?>
							<li><a href="<?php echo esc_url( get_category_link( $primary_cat ) ); ?>"><?php echo esc_html( $primary_cat->name ); ?></a></li>
						<?php endif; ?>
						<li><span aria-current="page"><?php echo esc_html( wp_trim_words( get_the_title(), 8, '…' ) ); ?></span></li>
					</ol>
				</nav>

				<div class="post-hero__head">
					<?php if ( $primary_cat ) : ?>
						<a class="category-pill" href="<?php echo esc_url( get_category_link( $primary_cat ) ); ?>"><i aria-hidden="true"></i><?php echo esc_html( $primary_cat->name ); ?></a>
					<?php endif; ?>

					<h1 class="post-title"><?php the_title(); ?></h1>

					<?php if ( $excerpt ) : ?>
						<p class="post-dek"><?php echo esc_html( $excerpt ); ?></p>
					<?php endif; ?>

					<div class="post-meta">
						<div class="meta-item">
							<span class="avatar" aria-hidden="true">
								<?php echo get_avatar( $author_id, 104, '', $author_name ); ?>
							</span>
							<div>
								<span class="meta-label"><?php esc_html_e( 'Written by', 'smart-leading-net' ); ?></span>
								<a class="meta-name" href="<?php echo esc_url( $about_url ); ?>">
									<?php echo esc_html( $author_name ); ?>
									<?php echo $verified; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</a>
								<?php if ( get_the_author_meta( 'nickname', $author_id ) && get_the_author_meta( 'nickname', $author_id ) !== $author_name ) : ?>
									<span class="meta-label"><?php echo esc_html( get_the_author_meta( 'nickname', $author_id ) ); ?></span>
								<?php endif; ?>
							</div>
						</div>

						<?php if ( $reviewer_name ) : ?>
							<div class="meta-item">
								<?php if ( $reviewer_image ) : ?>
									<span class="avatar" aria-hidden="true">
										<?php echo wp_get_attachment_image( $reviewer_image, 'thumbnail' ); ?>
									</span>
								<?php else : ?>
									<span class="avatar avatar--initials" aria-hidden="true"><?php echo esc_html( sln_blog_initials( $reviewer_name ) ); ?></span>
								<?php endif; ?>
								<div>
									<span class="meta-label"><?php esc_html_e( 'Technically reviewed by', 'smart-leading-net' ); ?></span>
									<span class="meta-name"><?php echo esc_html( $reviewer_name ); ?></span>
									<?php if ( $reviewer_role ) : ?>
										<span class="meta-label"><?php echo esc_html( $reviewer_role ); ?></span>
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>

						<div class="meta-item">
							<div class="meta-dates">
								<span><?php echo $icon_cal; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php esc_html_e( 'Published', 'smart-leading-net' ); ?> <b><time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></b></span>
								<span><?php echo $icon_upd; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php esc_html_e( 'Updated', 'smart-leading-net' ); ?> <b><time datetime="<?php echo esc_attr( get_the_modified_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_modified_date() ); ?></time></b></span>
							</div>
						</div>
					</div>

					<div class="post-actions">
						<div class="ai-summary">
							<span><?php esc_html_e( 'Summarize with AI', 'smart-leading-net' ); ?></span>
							<a class="chip" data-ai="chatgpt" href="https://chatgpt.com/" target="_blank" rel="noopener"><?php echo $ai_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>ChatGPT</a>
							<a class="chip" data-ai="claude" href="https://claude.ai/" target="_blank" rel="noopener"><?php echo $ai_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>Claude</a>
							<a class="chip" data-ai="perplexity" href="https://www.perplexity.ai/" target="_blank" rel="noopener"><?php echo $ai_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>Perplexity</a>
						</div>
						<div class="share-inline">
							<span><?php esc_html_e( 'Share', 'smart-leading-net' ); ?></span>
							<a class="icon-btn" data-share="linkedin" href="#" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'Share on LinkedIn', 'smart-leading-net' ); ?>"><?php echo $share_linkedin; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
							<a class="icon-btn" data-share="facebook" href="#" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'Share on Facebook', 'smart-leading-net' ); ?>"><?php echo $share_fb; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
							<a class="icon-btn" data-share="x" href="#" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'Share on X', 'smart-leading-net' ); ?>"><?php echo $share_x; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
							<button class="icon-btn" type="button" data-copy aria-label="<?php esc_attr_e( 'Copy link to this article', 'smart-leading-net' ); ?>"><?php echo $share_copy; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
						</div>
					</div>
				</div>

				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="post-figure">
						<div class="frame">
							<?php
							the_post_thumbnail(
								'sln-blog-featured',
								array(
									'class'    => 'sln-blog-featured',
									'loading'  => 'eager',
									'sizes'    => '(max-width: 1240px) calc(100vw - 48px), 1192px',
									'alt'      => the_title_attribute( array( 'echo' => false ) ),
								)
							);
							?>
						</div>
					</figure>
				<?php endif; ?>
			</div>
		</section>

		<div class="sln-blog-wrap post-layout">
			<div class="article-body" id="articleBody">
				<details class="toc-mobile">
					<summary><?php esc_html_e( 'On this page', 'smart-leading-net' ); ?>
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
					</summary>
					<ol class="toc" data-toc-list><li class="toc__fill" aria-hidden="true"></li></ol>
				</details>

				<?php if ( is_array( $takeaways ) && ! empty( $takeaways ) ) : ?>
					<aside class="takeaways" aria-labelledby="tldr">
						<div class="takeaways__head">
							<span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18h6M10 21h4M12 3a6 6 0 0 0-3.6 10.8c.7.5 1.1 1.3 1.1 2.2h5c0-.9.4-1.7 1.1-2.2A6 6 0 0 0 12 3z"/></svg></span>
							<h2 id="tldr"><?php esc_html_e( 'Key takeaways', 'smart-leading-net' ); ?></h2>
						</div>
						<ul class="check-list">
							<?php foreach ( $takeaways as $item ) : ?>
								<li><?php echo wp_kses_post( $item ); ?></li>
							<?php endforeach; ?>
						</ul>
					</aside>
				<?php endif; ?>

				<?php
				if ( $show_quick && ! sln_blog_content_has_shortcode( $post_id, 'sln_blog_quick_answer' ) ) {
					echo sln_blog_render_quick_answer( $post_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}

				the_content();

				if ( is_array( $faqs ) && ! empty( $faqs ) && ! sln_blog_content_has_shortcode( $post_id, 'sln_blog_faq' ) ) {
					echo sln_blog_render_faq( $post_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				?>

				<div class="post-footer">
					<div class="helpful" id="helpful">
						<p><?php esc_html_e( 'Was this article helpful?', 'smart-leading-net' ); ?></p>
						<div class="helpful__btns">
							<button type="button" data-vote="yes"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 11v9H4v-9zM7 11l4-8a2.5 2.5 0 0 1 2.5 2.5V9h5.2a2 2 0 0 1 2 2.3l-1.2 7A2 2 0 0 1 17.5 20H7"/></svg><?php esc_html_e( 'Yes', 'smart-leading-net' ); ?></button>
							<button type="button" data-vote="no"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 13V4h3v9zM17 13l-4 8a2.5 2.5 0 0 1-2.5-2.5V15H5.3a2 2 0 0 1-2-2.3l1.2-7A2 2 0 0 1 6.5 4H17"/></svg><?php esc_html_e( 'No', 'smart-leading-net' ); ?></button>
						</div>
						<p class="helpful__thanks" role="status"><?php esc_html_e( 'Thanks for your feedback.', 'smart-leading-net' ); ?></p>
					</div>
					<?php if ( $tags ) : ?>
						<div class="tags">
							<span><?php esc_html_e( 'Tags', 'smart-leading-net' ); ?></span>
							<?php foreach ( $tags as $tag ) : ?>
								<a href="<?php echo esc_url( get_tag_link( $tag ) ); ?>"><?php echo esc_html( $tag->name ); ?></a>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>

				<section class="author-box" aria-label="<?php esc_attr_e( 'About the author', 'smart-leading-net' ); ?>">
					<span class="avatar" aria-hidden="true">
						<?php echo get_avatar( $author_id, 152, '', $author_name ); ?>
					</span>
					<div>
						<div class="author-box__name">
							<?php echo esc_html( $author_name ); ?>
							<?php echo $verified; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
						<?php if ( get_the_author_meta( 'nickname', $author_id ) ) : ?>
							<div class="author-box__role"><?php echo esc_html( get_the_author_meta( 'nickname', $author_id ) ); ?></div>
						<?php endif; ?>
						<?php if ( $author_bio ) : ?>
							<p class="bio"><?php echo esc_html( $author_bio ); ?></p>
						<?php endif; ?>
						<div class="author-box__stats">
							<span><b><?php echo esc_html( (string) $author_posts ); ?></b><?php esc_html_e( 'articles', 'smart-leading-net' ); ?></span>
						</div>
						<div class="author-box__links">
							<a href="<?php echo esc_url( $about_url ); ?>"><?php esc_html_e( 'About our team', 'smart-leading-net' ); ?></a>
						</div>
					</div>
				</section>
			</div>

			<aside class="sidebar" aria-label="<?php esc_attr_e( 'Article tools', 'smart-leading-net' ); ?>">
				<nav class="toc-card" aria-labelledby="tocTitle">
					<div class="toc-card__head">
						<h2 id="tocTitle"><?php esc_html_e( 'On this page', 'smart-leading-net' ); ?></h2>
						<span class="toc-pct" id="tocPct" aria-live="off">0% <?php esc_html_e( 'read', 'smart-leading-net' ); ?></span>
					</div>
					<div class="toc-card__scroller" data-toc-scroll tabindex="0">
						<ol class="toc" data-toc-list><li class="toc__fill" aria-hidden="true"></li></ol>
					</div>
				</nav>

				<div class="side-cta">
					<small>
						<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg>
						<?php esc_html_e( 'Free 7-day trial', 'smart-leading-net' ); ?>
					</small>
					<h3><?php echo esc_html( $cta['heading'] ); ?></h3>
					<p><?php echo esc_html( $cta['description'] ); ?></p>
					<a class="sln-blog-btn sln-blog-btn--accent" href="<?php echo esc_url( $cta['url'] ); ?>"><?php echo esc_html( $cta['label'] ); ?></a>
					<span class="side-cta__call"><?php esc_html_e( 'or call', 'smart-leading-net' ); ?> <a href="<?php echo esc_url( 'tel:' . $phone_tel ); ?>"><?php echo esc_html( $phone ); ?></a></span>
				</div>

				<div class="side-share">
					<h2><?php esc_html_e( 'Share this article', 'smart-leading-net' ); ?></h2>
					<div>
						<a class="icon-btn" data-share="linkedin" href="#" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'Share on LinkedIn', 'smart-leading-net' ); ?>"><?php echo $share_linkedin; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
						<a class="icon-btn" data-share="facebook" href="#" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'Share on Facebook', 'smart-leading-net' ); ?>"><?php echo $share_fb; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
						<a class="icon-btn" data-share="email" href="#" aria-label="<?php esc_attr_e( 'Share by email', 'smart-leading-net' ); ?>"><?php echo $share_email; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
						<button class="icon-btn" type="button" data-copy aria-label="<?php esc_attr_e( 'Copy link to this article', 'smart-leading-net' ); ?>"><?php echo $share_copy; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button>
					</div>
				</div>
			</aside>
		</div>
	</article>

	<?php if ( ! empty( $related ) ) : ?>
		<section class="related" aria-labelledby="relatedTitle">
			<div class="sln-blog-wrap">
				<div class="related__head">
					<div>
						<p class="kicker"><?php esc_html_e( 'From the blog', 'smart-leading-net' ); ?></p>
						<h2 id="relatedTitle"><?php esc_html_e( 'Keep reading', 'smart-leading-net' ); ?></h2>
					</div>
					<a class="link-arrow" href="<?php echo esc_url( $archive_url ); ?>">
						<?php esc_html_e( 'View all articles', 'smart-leading-net' ); ?>
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
					</a>
				</div>
				<div class="related__grid">
					<?php foreach ( $related as $related_post ) : ?>
						<?php
						$rel_cats = get_the_category( $related_post->ID );
						$rel_cat  = ! empty( $rel_cats ) ? $rel_cats[0]->name : '';
						?>
						<a class="post-card" href="<?php echo esc_url( get_permalink( $related_post ) ); ?>">
							<div class="post-card__thumb">
								<?php
								if ( has_post_thumbnail( $related_post ) ) {
									echo get_the_post_thumbnail(
										$related_post,
										'medium_large',
										array(
											'alt'     => esc_attr( get_the_title( $related_post ) ),
											'loading' => 'lazy',
										)
									);
								}
								?>
							</div>
							<div class="post-card__body">
								<?php if ( $rel_cat ) : ?>
									<span class="post-card__cat"><?php echo esc_html( $rel_cat ); ?></span>
								<?php endif; ?>
								<h3><?php echo esc_html( get_the_title( $related_post ) ); ?></h3>
							</div>
						</a>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<section class="growth-cta" aria-labelledby="ctaTitle">
		<div class="sln-blog-wrap">
			<div class="growth-cta__box">
				<div>
					<h2 id="ctaTitle"><?php esc_html_e( 'Your Revenue Growth Starts Here', 'smart-leading-net' ); ?></h2>
					<p><?php esc_html_e( "Tell us about your business, and we'll create a custom plan to grow your traffic, leads, and predictable revenue.", 'smart-leading-net' ); ?></p>
				</div>
				<div>
					<form class="proposal-form" action="<?php echo esc_url( $contact_url ); ?>" method="get">
						<label class="visually-hidden" for="ctaWebsite"><?php esc_html_e( 'Your website', 'smart-leading-net' ); ?></label>
						<input id="ctaWebsite" name="website" type="text" inputmode="url" placeholder="<?php esc_attr_e( 'Your website', 'smart-leading-net' ); ?>" autocomplete="url">
						<button class="sln-blog-btn sln-blog-btn--accent" type="submit"><?php esc_html_e( 'Get My Free Proposal', 'smart-leading-net' ); ?></button>
					</form>
					<ul class="assurances">
						<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg><?php esc_html_e( 'No credit card required', 'smart-leading-net' ); ?></li>
						<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg><?php esc_html_e( 'Free 7-day trial', 'smart-leading-net' ); ?></li>
						<li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg><?php esc_html_e( 'Cancel anytime', 'smart-leading-net' ); ?></li>
					</ul>
				</div>
			</div>
		</div>
	</section>

	<div class="copied-toast" id="slnBlogToast" role="status" aria-live="polite"></div>
</div>
