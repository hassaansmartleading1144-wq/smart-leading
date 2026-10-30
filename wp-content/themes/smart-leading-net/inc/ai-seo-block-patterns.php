<?php
/**
 * Gutenberg block patterns for AI SEO custom blocks.
 *
 * @package Smart_Leading_Net
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function sln_ai_seo_register_block_patterns() {
	register_block_pattern_category(
		'sln-ai-seo',
		array(
			'label' => __( 'AI SEO Services', 'smart-leading-net' ),
		)
	);

	register_block_pattern(
		'sln-ai-seo/full-page',
		array(
			'title'       => __( 'AI SEO Services — Full Page', 'smart-leading-net' ),
			'description' => __( 'Complete AI SEO landing page built from custom Gutenberg section blocks.', 'smart-leading-net' ),
			'categories'  => array( 'sln-ai-seo' ),
			'content'     => '<!-- wp:smartleading/ai-seo-hero {"headingTag":"h1"} -->
<!-- wp:smartleading/ai-seo-journey-step {"number":"1","title":"Customer question","text":"A buyer asks a research question in plain language, such as how to compare providers or what a service involves."} /-->
<!-- wp:smartleading/ai-seo-journey-step {"number":"2","title":"Relevant public sources","text":"Websites, business profiles, articles and other public information that may relate to the question."} /-->
<!-- wp:smartleading/ai-seo-journey-step {"number":"3","title":"AI-generated answer","text":"A summary that may reference or link to some of those sources.","showAnswer":true} /-->
<!-- wp:smartleading/ai-seo-journey-step {"number":"4","title":"Further research or enquiry","text":"The buyer checks sources, compares options and may contact a business directly.","isEnd":true} /-->
<!-- /wp:smartleading/ai-seo-hero -->

<!-- wp:smartleading/ai-seo-meaning -->
<!-- wp:smartleading/ai-seo-term {"abbr":"SEO","full":"Search engine optimization","title":"Supports organic discovery","text":"Helps relevant pages get found in search results through useful content, technical accessibility and a clear site structure."} /-->
<!-- wp:smartleading/ai-seo-term {"abbr":"GEO","full":"Generative engine optimization","title":"Focuses on generated-answer visibility","text":"Looks at how a business and its information may appear, be described or be cited in AI-generated answers."} /-->
<!-- wp:smartleading/ai-seo-term {"abbr":"AEO","full":"Answer engine optimization","title":"Emphasizes answering questions clearly","text":"Structures content around the real questions people ask, so answers are easy to find and understand."} /-->
<!-- /wp:smartleading/ai-seo-meaning -->

<!-- wp:smartleading/ai-seo-benefits -->
<!-- wp:smartleading/ai-seo-benefit-card {"icon":"pie","title":"Build visibility across search experiences","text":"Support discovery in traditional search results and AI-generated answers with content and technical foundations that both can use."} /-->
<!-- wp:smartleading/ai-seo-benefit-card {"icon":"network","title":"Reach customers beyond a single search engine","text":"Plan around the search tools your audience actually uses, such as Google’s AI features, ChatGPT, Perplexity and Copilot, rather than one channel alone."} /-->
<!-- wp:smartleading/ai-seo-benefit-card {"icon":"accuracy","title":"Help AI answers describe you accurately","text":"Make it easier for AI-assisted search to describe your services, strengths and business details correctly, using clear and consistent public information."} /-->
<!-- /wp:smartleading/ai-seo-benefits -->

<!-- wp:smartleading/ai-seo-platforms -->
<!-- wp:smartleading/ai-seo-chip {"label":"Google AI Overviews and AI Mode"} /-->
<!-- wp:smartleading/ai-seo-chip {"label":"ChatGPT Search"} /-->
<!-- wp:smartleading/ai-seo-chip {"label":"Perplexity"} /-->
<!-- wp:smartleading/ai-seo-chip {"label":"Gemini"} /-->
<!-- wp:smartleading/ai-seo-chip {"label":"Copilot"} /-->
<!-- /wp:smartleading/ai-seo-platforms -->

<!-- wp:smartleading/ai-seo-approach -->
<!-- wp:smartleading/ai-seo-card {"number":"01","title":"Visibility assessment","text":"Review a selected set of questions and the sources that appear in sampled AI answers. The result is a starting point: where your business appears, where it does not, and which sources tend to shape the answers."} /-->
<!-- wp:smartleading/ai-seo-card {"number":"02","title":"Audience and question research","text":"Identify the questions that matter when prospective customers compare options, check requirements and look for help, so effort goes to questions with real business relevance."} /-->
<!-- wp:smartleading/ai-seo-card {"number":"03","title":"Technical SEO foundations","text":"Review accessibility, indexability and other technical barriers that can stop public information being found or understood. Fixes are specified after assessment, including appropriate structured data where it supports factual clarity."} /-->
<!-- wp:smartleading/ai-seo-card {"number":"04","title":"Helpful, answer-focused content","text":"Clarify service pages, comparisons and practical questions using your real expertise and evidence. The aim is content a person finds useful and that is clear enough to be summarized accurately."} /-->
<!-- wp:smartleading/ai-seo-card {"number":"05","title":"Consistent business information","text":"Check that core facts such as services, locations, contact details and descriptions are consistent across your website and relevant external profiles or references."} /-->
<!-- wp:smartleading/ai-seo-card {"number":"06","title":"Measurement and refinement","text":"Revisit an agreed sample of questions and use available referral and enquiry data to inform what to prioritize next."} /-->
<!-- /wp:smartleading/ai-seo-approach -->

<!-- wp:smartleading/ai-seo-pricing /-->

<!-- wp:smartleading/ai-seo-process -->
<!-- wp:smartleading/ai-seo-process-step {"number":"1","title":"Understand the business and audience","text":"Discuss your services, markets, existing search work and the questions prospective customers ask.","output":"a shared view of goals and the questions worth assessing."} /-->
<!-- wp:smartleading/ai-seo-process-step {"number":"2","title":"Establish a sampled baseline","text":"Review an agreed sample of questions across relevant AI search experiences and note which sources appear.","output":"a dated snapshot to compare against later."} /-->
<!-- wp:smartleading/ai-seo-process-step {"number":"3","title":"Agree scope and priorities","text":"Weigh content, technical and information-consistency opportunities against relevance and effort.","output":"agreed priorities and responsibilities."} /-->
<!-- wp:smartleading/ai-seo-process-step {"number":"4","title":"Review changes and refine","text":"Revisit the sample alongside available referral and enquiry data, and adjust priorities based on what is observed.","output":"observations kept separate from assumptions."} /-->
<!-- /wp:smartleading/ai-seo-process -->

<!-- wp:smartleading/ai-seo-measurement -->
<!-- wp:smartleading/ai-seo-signal-row {"number":"01","title":"Sampled mentions and citations","canTell":"Whether and how your business appears for the questions in an agreed sample, and which competitors or sources appear alongside it.","cannotShow":"How every user sees every answer. A sample is a snapshot of selected questions at a point in time."} /-->
<!-- wp:smartleading/ai-seo-signal-row {"number":"02","title":"Source and factual accuracy review","canTell":"Which pages and profiles answers appear to draw on, and whether your services and details are described correctly.","cannotShow":"Sources a platform uses but does not display. Not every answer shows its sources."} /-->
<!-- wp:smartleading/ai-seo-signal-row {"number":"03","title":"Identifiable AI referral visits","canTell":"Visits from AI platforms that pass referral information, and which pages those visitors land on.","cannotShow":"AI-assisted journeys that arrive without referral data, such as when someone later searches your name or types your address."} /-->
<!-- wp:smartleading/ai-seo-signal-row {"number":"04","title":"Recorded enquiries and qualification","canTell":"Whether enquiries arrive and, where your forms and records support it, how relevant those enquiries are.","cannotShow":"A complete attribution path. Results depend on the tracking and records already in place."} /-->
<!-- /wp:smartleading/ai-seo-measurement -->

<!-- wp:smartleading/ai-seo-principles -->
<!-- wp:smartleading/ai-seo-principle {"number":"01","title":"Define the questions being assessed","text":"Agree which customer questions, markets and platforms are in view, so everyone knows what a finding refers to."} /-->
<!-- wp:smartleading/ai-seo-principle {"number":"02","title":"Explain the priority behind each recommendation","text":"Each recommendation should state the problem it addresses and why it comes before other work."} /-->
<!-- wp:smartleading/ai-seo-principle {"number":"03","title":"Separate observed changes from assumptions","text":"Report what was seen in the sample and the data, and label interpretation as interpretation."} /-->
<!-- /wp:smartleading/ai-seo-principles -->

<!-- wp:smartleading/ai-seo-scope -->
<!-- wp:smartleading/ai-seo-scope-item {"title":"Size and complexity of the website","text":"How many pages, templates and services are involved."} /-->
<!-- wp:smartleading/ai-seo-scope-item {"title":"Current content and technical foundations","text":"What already works well and what may need attention first."} /-->
<!-- wp:smartleading/ai-seo-scope-item {"title":"Markets and services","text":"Which offerings, audiences and locations are in view."} /-->
<!-- wp:smartleading/ai-seo-scope-item {"title":"Assessment and measurement needs","text":"Which questions to sample and what data you already track."} /-->
<!-- /wp:smartleading/ai-seo-scope -->

<!-- wp:smartleading/ai-seo-banner /-->

<!-- wp:smartleading/ai-seo-faq -->
<!-- wp:smartleading/ai-seo-faq-item {"question":"What are AI SEO services?","answer":"AI SEO services focus on the website and brand information that may support discovery in AI-assisted search. The work can involve content, technical foundations and measurement. The specific scope of any engagement is agreed separately during scoping."} /-->
<!-- wp:smartleading/ai-seo-faq-item {"question":"What is the difference between SEO, GEO and AEO?","answer":"They are overlapping terms. SEO covers organic search discovery, GEO focuses on visibility in generated answers, and AEO emphasizes answering questions clearly. They describe related priorities rather than entirely separate systems."} /-->
<!-- wp:smartleading/ai-seo-faq-item {"question":"Does LLM optimization mean training a model?","answer":"No. On this page, LLM optimization means work on how clearly your website and brand information can be found and understood. Model development and fine-tuning are outside the proposed scope."} /-->
<!-- wp:smartleading/ai-seo-faq-item {"question":"Can you guarantee a ChatGPT mention or AI citation?","answer":"No. Placement in AI answers should not be promised by anyone. Each platform determines its own answers, and those answers can vary across questions, timing and context."} /-->
<!-- wp:smartleading/ai-seo-faq-item {"question":"Does AI search optimization replace SEO?","answer":"It is best treated as a related discovery consideration rather than a replacement. A useful, technically accessible website remains a sensible foundation for both."} /-->
<!-- wp:smartleading/ai-seo-faq-item {"question":"How is AI search visibility measured?","answer":"An agreed sample of questions can be reviewed for mentions and citations, alongside available referral and enquiry data. Each of these measures has limits, so they are reported separately rather than combined into a single score."} /-->
<!-- wp:smartleading/ai-seo-faq-item {"question":"How long will results take?","answer":"There is no fixed timeline. Timing depends on the work involved, your starting point and how each platform behaves."} /-->
<!-- wp:smartleading/ai-seo-faq-item {"question":"Which platforms and deliverables would my engagement include?","answer":"Platform coverage and responsibilities are confirmed during scoping. It should not be assumed that every platform named on this page, or every proposed module, is included."} /-->
<!-- /wp:smartleading/ai-seo-faq -->

<!-- wp:smartleading/ai-seo-enquiry /-->',
		)
	);
}
add_action( 'init', 'sln_ai_seo_register_block_patterns' );
