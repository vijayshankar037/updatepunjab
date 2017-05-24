<?php /* Template Name: Page - Full Width */ ?>
<?php get_header(); ?>
<div class="page-full-width"><?php
	mh_newsdesk_lite_before_page_content();
	if (have_posts()) :
		while (have_posts()) : the_post();
			get_template_part('content', 'page');
			get_template_part('comments', 'pages');
		endwhile;
	endif; ?>
</div>
<?php get_footer(); ?>