<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="mh-wrapper">
<header class="mh-header">
	<div class="header-wrap clearfix">
                    <?php $a= get_field('counter','1698'); 
                          $b= $a+1;
                             update_field('counter',$b , '1698'); 

                          ?>
		<?php mh_newsdesk_lite_logo(); ?>    <h4>You are visitor Number : <?php echo get_field('counter','1698'); ?></h4>     
	</div>
<?php echo do_shortcode('[adrotate group="1"]'); ?>
	<div class="header-menu clearfix">
		<nav class="main-nav clearfix">
			<?php wp_nav_menu(array('theme_location' => 'main_nav')); ?>
		</nav>
	</div>
</header>