<?php /* Template for displaying a "No posts found" message. */ ?>
<div class="entry-content no-content-found clearfix">
	<?php if (is_search()) { ?>
		<p><?php _e('Sorry, but nothing matched your search terms. Please try again with different keywords.', 'mh-newsdesk-lite'); ?></p>
	<?php } else { ?>
		<p><?php _e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'mh-newsdesk-lite'); ?></p>
	<?php } ?>
	<?php get_search_form(); ?>
</div>