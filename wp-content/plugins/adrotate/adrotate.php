<?php
/*
Plugin Name: AdRotate
Plugin URI: https://ajdg.solutions/products/adrotate-for-wordpress/?utm_campaign=adrotate-page&utm_medium=plugin-info&utm_source=adrotate-free
Author: Arnan de Gans
Author URI: http://ajdg.solutions/?utm_campaign=homepage&utm_medium=plugin-info&utm_source=adrotate-free
Description: The popular choice for monetizing your website with adverts while keeping things simple. Start making money today!
Text Domain: adrotate
Domain Path: /languages/
Version: 4.4
License: GPLv3
*/

/* ------------------------------------------------------------------------------------
*  COPYRIGHT AND TRADEMARK NOTICE
*  Copyright 2008-2017 Arnan de Gans. All Rights Reserved.
*  ADROTATE is a trademark of Arnan de Gans.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from it's use.
------------------------------------------------------------------------------------ */

/*--- AdRotate values ---------------------------------------*/
define("ADROTATE_DISPLAY", '4.4');
define("ADROTATE_VERSION", 390);
define("ADROTATE_DB_VERSION", 63);
$plugin_folder = plugin_dir_path(__FILE__);
/*-----------------------------------------------------------*/

/*--- Load Files --------------------------------------------*/
include_once($plugin_folder.'/adrotate-setup.php');
include_once($plugin_folder.'/adrotate-manage-publisher.php');
include_once($plugin_folder.'/adrotate-functions.php');
include_once($plugin_folder.'/adrotate-statistics.php');
include_once($plugin_folder.'/adrotate-export.php');
include_once($plugin_folder.'/adrotate-output.php');
include_once($plugin_folder.'/adrotate-widget.php');
/*-----------------------------------------------------------*/

/*--- Check and Load config ---------------------------------*/
load_plugin_textdomain('adrotate', false, basename($plugin_folder) . '/language');
$adrotate_config = get_option('adrotate_config');
$adrotate_crawlers = get_option('adrotate_crawlers');
$adrotate_version = get_option("adrotate_version");
$adrotate_db_version = get_option("adrotate_db_version");
$adrotate_debug	= get_option("adrotate_debug");
/*-----------------------------------------------------------*/

/*--- Core --------------------------------------------------*/
register_activation_hook(__FILE__, 'adrotate_activate');
register_deactivation_hook(__FILE__, 'adrotate_deactivate');
register_uninstall_hook(__FILE__, 'adrotate_uninstall');
add_action('adrotate_evaluate_ads', 'adrotate_evaluate_ads');
add_action('adrotate_empty_trackerdata', 'adrotate_empty_trackerdata');
add_action('widgets_init', create_function('', 'return register_widget("adrotate_widgets");'));
/*-----------------------------------------------------------*/

/*--- Front end ---------------------------------------------*/
if($adrotate_config['stats'] == 1){
	add_action('wp_ajax_adrotate_impression', 'adrotate_impression_callback');
	add_action('wp_ajax_nopriv_adrotate_impression', 'adrotate_impression_callback');
	add_action('wp_ajax_adrotate_click', 'adrotate_click_callback');
	add_action('wp_ajax_nopriv_adrotate_click', 'adrotate_click_callback');
}
if(!is_admin()) {
	add_shortcode('adrotate', 'adrotate_shortcode');
	add_action("wp_enqueue_scripts", 'adrotate_custom_scripts');
	add_action('wp_head', 'adrotate_custom_css');
	add_filter('the_content', 'adrotate_inject_posts', 12);
}
/*-----------------------------------------------------------*/

/*--- Back End ----------------------------------------------*/
if(is_admin()) {
	adrotate_check_config();
	add_action('admin_menu', 'adrotate_dashboard');
	add_action("admin_enqueue_scripts", 'adrotate_dashboard_scripts');
	add_action("admin_print_styles", 'adrotate_dashboard_styles');
	add_action('admin_notices','adrotate_notifications_dashboard');
	/*--- Internal redirects ------------------------------------*/
	if(isset($_POST['adrotate_ad_submit'])) add_action('init', 'adrotate_insert_input');
	if(isset($_POST['adrotate_group_submit'])) add_action('init', 'adrotate_insert_group');
	if(isset($_POST['adrotate_action_submit'])) add_action('init', 'adrotate_request_action');
	if(isset($_POST['adrotate_disabled_action_submit'])) add_action('init', 'adrotate_request_action');
	if(isset($_POST['adrotate_error_action_submit'])) add_action('init', 'adrotate_request_action');
	if(isset($_POST['adrotate_save_options'])) add_action('init', 'adrotate_options_submit');
	if(isset($_POST['adrotate_request_submit'])) add_action('init', 'adrotate_mail_message');
	if(isset($_POST['adrotate_db_optimize_submit'])) add_action('init', 'adrotate_optimize_database');
	if(isset($_POST['adrotate_db_cleanup_submit'])) add_action('init', 'adrotate_cleanup_database');
	if(isset($_POST['adrotate_evaluate_submit'])) add_action('init', 'adrotate_prepare_evaluate_ads');
}

/*-------------------------------------------------------------
 Name:      adrotate_dashboard
 Purpose:   Add pages to admin menus
-------------------------------------------------------------*/
function adrotate_dashboard() {
	global $adrotate_config;

	$adrotate_page = $adrotate_pro = $adrotate_adverts = $adrotate_groups = $adrotate_schedules = $adrotate_media = $adrotate_settings =  '';
	$adrotate_page = add_menu_page('AdRotate', 'AdRotate', 'adrotate_ad_manage', 'adrotate', 'adrotate_info', plugins_url('/images/icon-menu.png', __FILE__), '25.8');
	$adrotate_page = add_submenu_page('adrotate', 'AdRotate · '.__('General Info', 'adrotate'), __('General Info', 'adrotate'), 'adrotate_ad_manage', 'adrotate', 'adrotate_info');
	$adrotate_pro = add_submenu_page('adrotate', 'AdRotate · '.__('AdRotate Pro', 'adrotate'), __('AdRotate Pro', 'adrotate'), 'adrotate_ad_manage', 'adrotate-pro', 'adrotate_pro');
	$adrotate_adverts = add_submenu_page('adrotate', 'AdRotate · '.__('Adverts', 'adrotate'), __('Adverts', 'adrotate'), 'adrotate_ad_manage', 'adrotate-ads', 'adrotate_manage');
	$adrotate_groups = add_submenu_page('adrotate', 'AdRotate · '.__('Groups', 'adrotate'), __('Groups', 'adrotate'), 'adrotate_group_manage', 'adrotate-groups', 'adrotate_manage_group');
	$adrotate_settings = add_submenu_page('adrotate', 'AdRotate · '.__('Settings', 'adrotate'), __('Settings', 'adrotate'), 'manage_options', 'adrotate-settings', 'adrotate_options');
 
	// Add help tabs
	add_action('load-'.$adrotate_page, 'adrotate_help_info');
	add_action('load-'.$adrotate_pro, 'adrotate_help_info');
	add_action('load-'.$adrotate_adverts, 'adrotate_help_info');
	add_action('load-'.$adrotate_groups, 'adrotate_help_info');
	add_action('load-'.$adrotate_settings, 'adrotate_help_info');
}

/*-------------------------------------------------------------
 Name:      adrotate_info
 Purpose:   Admin general info page
-------------------------------------------------------------*/
function adrotate_info() {
	global $wpdb;
	?>
	<div class="wrap">
		<h1><?php _e('AdRotate Info', 'adrotate'); ?></h1>

		<br class="clear" />

		<?php include("dashboard/info.php"); ?>

		<br class="clear" />
	</div>
<?php
}

/*-------------------------------------------------------------
 Name:      adrotate_pro
 Purpose:   AdRotate Pro Sales
-------------------------------------------------------------*/
function adrotate_pro() {
?>
	<div class="wrap">
		<h1><?php _e('AdRotate Professional', 'adrotate'); ?></h1>

		<br class="clear" />

		<?php include("dashboard/adrotatepro.php"); ?>

		<br class="clear" />
	</div>
<?php
}

/*-------------------------------------------------------------
 Name:      adrotate_manage
 Purpose:   Admin management page
-------------------------------------------------------------*/
function adrotate_manage() {
	global $wpdb, $userdata, $adrotate_config, $adrotate_debug;

	$status = $file = $view = $ad_edit_id = '';
	if(isset($_GET['status'])) $status = esc_attr($_GET['status']);
	if(isset($_GET['file'])) $file = esc_attr($_GET['file']);
	if(isset($_GET['view'])) $view = esc_attr($_GET['view']);
	if(isset($_GET['ad'])) $ad_edit_id = esc_attr($_GET['ad']);
	$now 			= adrotate_now();
	$today 			= adrotate_date_start('day');
	$in2days 		= $now + 172800;
	$in7days 		= $now + 604800;
	$in84days 		= $now + 7257600;

	if(isset($_GET['month']) AND isset($_GET['year'])) {
		$month = esc_attr($_GET['month']);
		$year = esc_attr($_GET['year']);
	} else {
		$month = date("m");
		$year = date("Y");
	}
	$monthstart = mktime(0, 0, 0, $month, 1, $year);
	$monthend = mktime(0, 0, 0, $month+1, 0, $year);	
	?>
	<div class="wrap">
		<h1><?php _e('Advert Management', 'adrotate'); ?></h1>

		<?php
		if($status > 0) adrotate_status($status, array('file' => $file));

		$allbanners = $wpdb->get_results("SELECT `id`, `title`, `type`, `tracker`, `weight` FROM `{$wpdb->prefix}adrotate` WHERE (`type` != 'empty' OR `type` != 'a_empty' OR `type` != 'queue') ORDER BY `id` ASC;");

		$active = $disabled = $error = false;
		foreach($allbanners as $singlebanner) {
			$starttime = $stoptime = 0;
			$starttime = $wpdb->get_var("SELECT `starttime` FROM `{$wpdb->prefix}adrotate_schedule`, `{$wpdb->prefix}adrotate_linkmeta` WHERE `ad` = '".$singlebanner->id."' AND `schedule` = `{$wpdb->prefix}adrotate_schedule`.`id` ORDER BY `starttime` ASC LIMIT 1;");
			$stoptime = $wpdb->get_var("SELECT `stoptime` FROM `{$wpdb->prefix}adrotate_schedule`, `{$wpdb->prefix}adrotate_linkmeta` WHERE `ad` = '".$singlebanner->id."' AND `schedule` = `{$wpdb->prefix}adrotate_schedule`.`id` ORDER BY `stoptime` DESC LIMIT 1;");
			
			$type = $singlebanner->type;
			if($type == 'active' AND $stoptime <= $in7days) $type = '7days';
			if($type == 'active' AND $stoptime <= $in2days) $type = '2days';
			if($type == 'active' AND $stoptime <= $now) $type = 'expired'; 

			if($type == 'active' OR $type == '7days') {
				$active[$singlebanner->id] = array(
					'id' => $singlebanner->id,
					'title' => $singlebanner->title,
					'type' => $type,
					'tracker' => $singlebanner->tracker,
					'weight' => $singlebanner->weight,
					'firstactive' => $starttime,
					'lastactive' => $stoptime
				);
			}
			
			if($type == 'error' OR $type == 'expired' OR $type == '2days') {
				$error[$singlebanner->id] = array(
					'id' => $singlebanner->id,
					'title' => $singlebanner->title,
					'type' => $type,
					'tracker' => $singlebanner->tracker,
					'weight' => $singlebanner->weight,
					'firstactive' => $starttime,
					'lastactive' => $stoptime
				);
			}
			
			if($type == 'disabled') {
				$disabled[$singlebanner->id] = array(
					'id' => $singlebanner->id,
					'title' => $singlebanner->title,
					'type' => $type,
					'tracker' => $singlebanner->tracker,
					'weight' => $singlebanner->weight,
					'firstactive' => $starttime,
					'lastactive' => $stoptime
				);
			}
		}
		?>
		
		<div class="tablenav">
			<div class="alignleft actions">
				<a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-ads&view=manage');?>"><?php _e('Manage', 'adrotate'); ?></a> | 
				<a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-ads&view=addnew');?>"><?php _e('Add New', 'adrotate'); ?></a>
			</div>
		</div>

    	<?php 
	    if ($view == "" OR $view == "manage") {
			// Show list of errorous ads if any			
			if ($error) {
				include("dashboard/publisher/adverts-error.php");
			}
	
			include("dashboard/publisher/adverts-main.php");

			// Show disabled ads, if any
			if ($disabled) {
				include("dashboard/publisher/adverts-disabled.php");
			}		
		} else if($view == "addnew" OR $view == "edit") { 
			include("dashboard/publisher/adverts-edit.php");
		} else if($view == "report") {
			include("dashboard/publisher/adverts-report.php");
		}
		?>
	<br class="clear" />

	<?php adrotate_credits(); ?>

	</div>
<?php
}

/*-------------------------------------------------------------
 Name:      adrotate_manage_group
 Purpose:   Manage groups
-------------------------------------------------------------*/
function adrotate_manage_group() {
	global $wpdb, $adrotate_config, $adrotate_debug;

	$status = $view = $group_edit_id = '';
	if(isset($_GET['status'])) $status = esc_attr($_GET['status']);
	if(isset($_GET['view'])) $view = esc_attr($_GET['view']);
	if(isset($_GET['group'])) $group_edit_id = esc_attr($_GET['group']);

	if(isset($_GET['month']) AND isset($_GET['year'])) {
		$month = esc_attr($_GET['month']);
		$year = esc_attr($_GET['year']);
	} else {
		$month = date("m");
		$year = date("Y");
	}
	$monthstart = mktime(0, 0, 0, $month, 1, $year);
	$monthend = mktime(0, 0, 0, $month+1, 0, $year);	

	$today = adrotate_date_start('day');
	$now 			= adrotate_now();
	$today 			= adrotate_date_start('day');
	$in2days 		= $now + 172800;
	$in7days 		= $now + 604800;
	?>
	<div class="wrap">
		<h1><?php _e('Group Management', 'adrotate'); ?></h1>

		<?php if($status > 0) adrotate_status($status); ?>

		<div class="tablenav">
			<div class="alignleft actions">
				<a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-groups&view=manage');?>"><?php _e('Manage', 'adrotate'); ?></a> | 
				<a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-groups&view=addnew');?>"><?php _e('Add New', 'adrotate'); ?></a>
				<?php if($group_edit_id AND $adrotate_config['stats'] == 1) { ?>
				| <a class="row-title" href="<?php echo admin_url('/admin.php?page=adrotate-groups&view=report&group='.$group_edit_id);?>"><?php _e('Report', 'adrotate'); ?></a>
				<?php } ?>
			</div>
		</div>

    	<?php if ($view == "" OR $view == "manage") { ?>

			<?php
			include("dashboard/publisher/groups-main.php");
			?>

	   	<?php } else if($view == "addnew" OR $view == "edit") { ?>

			<?php
			include("dashboard/publisher/groups-edit.php");
			?>

	   	<?php } else if($view == "report") { ?>

			<?php
			include("dashboard/publisher/groups-report.php");
			?>

	   	<?php } ?>
		<br class="clear" />

		<?php adrotate_credits(); ?>

	</div>
<?php
}

/*-------------------------------------------------------------
 Name:      adrotate_options
 Purpose:   Admin options page
-------------------------------------------------------------*/
function adrotate_options() {
	global $wpdb, $wp_roles;

    $active_tab = (isset($_GET['tab'])) ? esc_attr($_GET['tab']) : 'general';
	$status = (isset($_GET['status'])) ? esc_attr($_GET['status']) : '';
	$error = (isset($_GET['error'])) ? esc_attr($_GET['error']) : '';
	?>

	<div class="wrap">
	  	<h1><?php _e('AdRotate Settings', 'adrotate'); ?></h1>

		<?php if($status > 0) adrotate_status($status, array('error' => $error)); ?>

		<h2 class="nav-tab-wrapper">  
            <a href="?page=adrotate-settings&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"><?php _e('General', 'adrotate'); ?></a>  
            <a href="?page=adrotate-settings&tab=notifications" class="nav-tab <?php echo $active_tab == 'notifications' ? 'nav-tab-active' : ''; ?>"><?php _e('Notifications', 'adrotate'); ?></a>  
            <a href="?page=adrotate-settings&tab=stats" class="nav-tab <?php echo $active_tab == 'stats' ? 'nav-tab-active' : ''; ?>"><?php _e('Stats', 'adrotate'); ?></a>  
            <a href="?page=adrotate-settings&tab=geo" class="nav-tab <?php echo $active_tab == 'geo' ? 'nav-tab-active' : ''; ?>"><?php _e('Geo Targeting', 'adrotate'); ?></a>  
            <a href="?page=adrotate-settings&tab=advertisers" class="nav-tab <?php echo $active_tab == 'advertisers' ? 'nav-tab-active' : ''; ?>"><?php _e('Advertisers', 'adrotate'); ?></a>  
            <a href="?page=adrotate-settings&tab=roles" class="nav-tab <?php echo $active_tab == 'roles' ? 'nav-tab-active' : ''; ?>"><?php _e('Roles', 'adrotate'); ?></a>  
            <a href="?page=adrotate-settings&tab=misc" class="nav-tab <?php echo $active_tab == 'misc' ? 'nav-tab-active' : ''; ?>"><?php _e('Misc', 'adrotate'); ?></a>  
            <a href="?page=adrotate-settings&tab=maintenance" class="nav-tab <?php echo $active_tab == 'maintenance' ? 'nav-tab-active' : ''; ?>"><?php _e('Maintenance', 'adrotate'); ?></a>  
        </h2>		

		<?php
		$adrotate_config = get_option('adrotate_config');
		$adrotate_debug = get_option('adrotate_debug');

		if($active_tab == 'general') {  
			$adrotate_crawlers = get_option('adrotate_crawlers');

			$crawlers = '';
			if(is_array($adrotate_crawlers)) {
				$crawlers = implode(', ', $adrotate_crawlers);
			}

			include("dashboard/settings/general.php");						
		} elseif($active_tab == 'notifications') {
			$adrotate_notifications	= get_option("adrotate_notifications");
			include("dashboard/settings/notifications.php");						
		} elseif($active_tab == 'stats') {
			include("dashboard/settings/statistics.php");						
		} elseif($active_tab == 'geo') {
			include("dashboard/settings/geotargeting.php");						
		} elseif($active_tab == 'advertisers') {
			include("dashboard/settings/advertisers.php");						
		} elseif($active_tab == 'roles') {
			include("dashboard/settings/roles.php");						
		} elseif($active_tab == 'misc') {
			include("dashboard/settings/misc.php");						
		} elseif($active_tab == 'maintenance') {
			$adrotate_version = get_option('adrotate_version');
			$adrotate_db_version = get_option('adrotate_db_version');
			$advert_status	= get_option("adrotate_advert_status");

			$adevaluate = wp_next_scheduled('adrotate_evaluate_ads');
			$adschedule = wp_next_scheduled('adrotate_notification');
			$tracker = wp_next_scheduled('adrotate_empty_trackerdata');

			include("dashboard/settings/maintenance.php");						
		} elseif($active_tab == 'license') {
			$adrotate_is_networked = adrotate_is_networked();
			$adrotate_hide_license = get_option('adrotate_hide_license');
			if($adrotate_is_networked) {
				$adrotate_activate = get_site_option('adrotate_activate');
			} else {
				$adrotate_activate = get_option('adrotate_activate');
			}
		
			include("dashboard/settings/license.php");						
		}
		?>
	</div>
<?php 
}
?>