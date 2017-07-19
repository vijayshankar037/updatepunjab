<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class es_cls_default {
	public static function es_pluginconfig_default() {

		global $wpdb;

		//Needs work-temp fixed in v3.3.3
		$result = es_cls_dbquery::es_view_subscriber_count(0);
		if ($result == 0) {

			$admin_email = get_option('admin_email');
			$blogname = get_option('blogname');

			if($admin_email == "") {
				$admin_email = "admin@gmail.com";
			}

			$home_url = home_url('/');
			$optinlink = $home_url . "?es=optin&db=###DBID###&email=###EMAIL###&guid=###GUID###";
			$unsublink = $home_url . "?es=unsubscribe&db=###DBID###&email=###EMAIL###&guid=###GUID###";

			$default = array();
			$default['ig_es_fromname'] = "Admin";
			$default['ig_es_fromemail'] = $admin_email;
			$default['ig_es_emailtype'] = "WP HTML MAIL";
			$default['ig_es_notifyadmin'] = "YES";
			$default['ig_es_adminemail'] = $admin_email;
			$default['ig_es_admin_new_sub_subject'] = $blogname . " New email subscription";
			$default['ig_es_admin_new_sub_content'] = "Hi Admin, \r\n\r\nWe have received a request to subscribe new email address to receive emails from our website. \r\n\r\nName : ###NAME###\r\nEmail: ###EMAIL###\r\nGroup: ###GROUP### \r\n\r\nThank You\r\n".$blogname;
			$default['ig_es_welcomeemail'] = "YES";
			$default['ig_es_welcomesubject'] = $blogname . " Welcome to our newsletter";
			$default['ig_es_welcomecontent'] = "Hi ###NAME###, \r\n\r\nWe have received a request to subscribe this email address to receive newsletter from our website in group ###GROUP###. \r\n\r\nThank You\r\n".$blogname." \r\n\r\n No longer interested in emails from ".$blogname."?. Please <a href='###LINK###'>click here</a> to unsubscribe";
			$default['ig_es_optintype'] = "Double Opt In";
			$default['ig_es_confirmsubject'] = $blogname . " confirm subscription";
			$default['ig_es_confirmcontent'] = "Hi ###NAME###, \r\n\r\nA subscription request for this email address was received. Please confirm it by <a href='###LINK###'>clicking here</a>.\r\n\r\nIf you still cannot subscribe, please click this link : \r\n ###LINK### \r\n\r\nThank You\r\n".$blogname;
			$default['ig_es_optinlink'] = $optinlink;
			$default['ig_es_unsublink'] = $unsublink;
			$default['ig_es_unsubcontent'] = "No longer interested in emails from ".$blogname."?. Please <a href='###LINK###'>click here</a> to unsubscribe";
			$default['ig_es_unsubtext'] = "Thank You, You have been successfully unsubscribed. You will no longer hear from us.";
			$default['ig_es_successmsg'] = "Thank You, You have been successfully subscribed.";
			$default['ig_es_suberror'] = "Oops.. This subscription cant be completed, sorry. The email address is blocked or already subscribed. Thank you.";
			$default['ig_es_unsuberror'] = "Oops.. We are getting some technical error. Please try again or contact admin.";

			foreach ( $default as $option_name => $option_value ) {
				update_option( $option_name, $option_value );
			}

		}

		return true;
	}

	public static function es_subscriber_default() {

		$result = es_cls_dbquery::es_view_subscriber_count(0);
		if ($result == 0) {
			$form["es_email_mail"] = get_option('admin_email');
			$form["es_email_name"] = "Admin";
			$form["es_email_group"] = "Public";
			$form["es_email_status"] = "Confirmed";
			es_cls_dbquery::es_view_subscriber_ins($form, "insert");

			$form["es_email_mail"] = "a.example@example.com";
			$form["es_email_name"] = "Example";
			$form["es_email_group"] = "Public";
			$form["es_email_status"] = "Confirmed";
			es_cls_dbquery::es_view_subscriber_ins($form, "insert");
		}
		return true;

	}

	public static function es_template_default() {

		$result = es_cls_compose::es_template_count(0);

		if ($result == 0) {

			// Adding a sample Post Notification content
			$es_b = "Hello ###NAME###,\r\n\r\n";
			$es_b .= "We have published a new blog article on our website : ###POSTTITLE###\r\n";
			$es_b .= "###POSTIMAGE###\r\n\r\n";
			$es_b .= "You can view it from this link : ";
			$es_b .= "###POSTLINK###\r\n\r\n";
			$es_b .= "Thanks & Regards,\r\n";
			$es_b .= "Admin\r\n\r\n";
			$es_b .= "You received this email because in the past you have provided us your email address : ###EMAIL### to receive notifications when new updates are posted.";

			$form['es_templ_heading'] = 'New Post Published - ###POSTTITLE###';
			$form['es_templ_body'] = $es_b;
			$form['es_templ_status'] = 'Published';
			$form['es_email_type'] = 'Post Notification';
			$action = es_cls_compose::es_template_ins($form, $action = "insert");

			// Adding a sample Newsletter content
			$Sample = '<strong style="color: #990000">What can you achieve using Email Subscribers?</strong><p>Add subscription forms on website, send HTML newsletters & automatically notify subscribers about new blog posts once it is published.';
			$Sample .= ' You can also Import or Export subscribers from any list to Email Subscribers.</p>';
			$Sample .= ' <strong style="color: #990000">Plugin Features</strong><ol>';
			$Sample .= ' <li>Send notification emails to subscribers when new blog posts are published.</li>';
			$Sample .= ' <li>Subscribe form available with 3 options to setup.</li>';
			$Sample .= ' <li>Double Opt-In and Single Opt-In support.</li>';
			$Sample .= ' <li>Email notification to admin when a new user signs up (Optional).</li>';
			$Sample .= ' <li>Automatic welcome email to subscriber.</li>';
			$Sample .= ' <li>Auto add unsubscribe link in the email.</li>';
			$Sample .= ' <li>Import/Export subscriber emails to migrate to any lists.</li>';
			$Sample .= ' <li>Default WordPress editor to compose emails.</li>';
			$Sample .= ' </ol>';
			$Sample .= ' <strong>Thanks & Regards,</strong><br>Admin';

			$form['es_templ_heading'] = 'Welcome To Email Subscribers';
			$form['es_templ_body'] = $Sample;
			$form['es_templ_status'] = 'Published';
			$form['es_email_type'] = 'Newsletter';
			$action = es_cls_compose::es_template_ins($form, $action = "insert");
		}

		return true;
	}

	public static function es_notifications_default() {

		$result = es_cls_notification::es_notification_count(0);
		if ($result == 0) {
			$form["es_note_group"] = "Public";
			$form["es_note_status"] = "Enable";
			$form["es_note_templ"] = "1";

			$listcategory = "";
			$args = array( 'hide_empty' => 0, 'orderby' => 'name', 'order' => 'ASC' );
			$categories = get_categories($args);
			$total = count($categories);
			$i = 1;
			foreach($categories as $category) {
				$listcategory = $listcategory . " ##" . $category->cat_name . "## ";
				if($i < $total) {
					$listcategory = $listcategory .  "--";
				}
				$i = $i + 1;
			}
			$form["es_note_cat"] = $listcategory;
			es_cls_notification::es_notification_ins($form, "insert");
		}

		return true;
	}
}