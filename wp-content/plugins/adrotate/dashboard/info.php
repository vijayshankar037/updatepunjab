<?php
/* ------------------------------------------------------------------------------------
*  COPYRIGHT AND TRADEMARK NOTICE
*  Copyright 2008-2017 Arnan de Gans. All Rights Reserved.
*  ADROTATE is a trademark of Arnan de Gans.

*  COPYRIGHT NOTICES AND ALL THE COMMENTS SHOULD REMAIN INTACT.
*  By using this code you agree to indemnify Arnan de Gans from any
*  liability that might arise from it's use.
------------------------------------------------------------------------------------ */

$banners = $groups = 0;
$banners = $wpdb->get_var("SELECT COUNT(*) FROM `{$wpdb->prefix}adrotate` WHERE `type` != 'empty' AND `type` != 'a_empty';");
$groups = $wpdb->get_var("SELECT COUNT(*) FROM `{$wpdb->prefix}adrotate_groups` WHERE `name` != '';");
$data = get_option("adrotate_advert_status");

// Random banner for Media.net
$partner = mt_rand(1,3);
?>

<div id="dashboard-widgets-wrap">
	<div id="dashboard-widgets" class="metabox-holder">

		<div id="postbox-container-1" class="postbox-container" style="width:50%;">
			<div id="normal-sortables" class="meta-box-sortables ui-sortable">
				
				<h3><?php _e('Currently', 'adrotate'); ?></h3>
				<div class="postbox-ajdg">
					<div class="inside">
						<table width="100%">
							<thead>
							<tr class="first">
								<td width="50%"><strong><?php _e('Your setup', 'adrotate'); ?></strong></td>
								<td width="50%"><strong><?php _e('Adverts that need you', 'adrotate'); ?></strong></td>
							</tr>
							</thead>
							
							<tbody>
							<tr class="first">
								<td class="first b"><a href="admin.php?page=adrotate-ads"><?php echo $banners; ?> <?php _e('Adverts', 'adrotate'); ?></a></td>
								<td class="b"><a href="admin.php?page=adrotate-ads"><?php echo $data['expiressoon'] + $data['expired']; ?> <?php _e('(Almost) Expired', 'adrotate'); ?></a></td>
							</tr>
							<tr>
								<td class="first b"><a href="admin.php?page=adrotate-groups"><?php echo $groups; ?> <?php _e('Groups', 'adrotate'); ?></a></td>
								<td class="b"><a href="admin.php?page=adrotate-ads"><?php echo $data['error']; ?> <?php _e('Have errors', 'adrotate'); ?></a></td>
							</tr>
							<tr>
								<td colspan="2">
									<p><strong><?php _e('Support AdRotate', 'adrotate'); ?></strong></p>
									<p><?php _e('Consider writing a review if you like AdRotate. Also follow my Facebook page for updates about me and my plugins. Thank you!', 'adrotate'); ?><br />
									<center><a class="button-primary" href="https://paypal.me/arnandegans/10usd" target="_blank">Donate $10 via Paypal</a> <a class="button" target="_blank" href="https://wordpress.org/support/plugin/adrotate/reviews/?rate=5#new-post">Write review on WordPress.org</a></center><br />
									<a href="http://www.arnan.me/?pk_campaign=adrotate-free&pk_kwd=infopage" title="arnan.me - Nomadic in the Philippines"><img src="<?php echo plugins_url('/images/arnan-credits.jpg', dirname(__FILE__)); ?>" alt="Arnan de Gans" align="center" class="ajdg-photo" width="100%" /></a><br />
																		<script>(function(d, s, id) {
									  var js, fjs = d.getElementsByTagName(s)[0];
									  if (d.getElementById(id)) return;
									  js = d.createElement(s); js.id = id;
									  js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5";
									  fjs.parentNode.insertBefore(js, fjs);
									}(document, 'script', 'facebook-jssdk'));</script>
									<p><center><div class="fb-page" 
										data-href="https://www.facebook.com/Arnandegans" 
										data-width="490" 
										data-adapt-container-width="true" 
										data-hide-cover="false" 
										data-show-facepile="false">
									</div></center></p>
								</td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>

				<h3><?php _e('Arnan de Gans News & Updates', 'adrotate'); ?></h3>
				<div class="postbox-ajdg">
					<div class="inside">
						<?php wp_widget_rss_output(array(
							'url' => array('http://www.arnan.me/feed/', 'http://ajdg.solutions/feed/'), 
							'items' => 4, 
							'show_summary' => 1, 
							'show_author' => 0, 
							'show_date' => 1)
						); ?>
					</div>
				</div>

			</div>
		</div>

		<div id="postbox-container-3" class="postbox-container" style="width:50%;">
			<div id="side-sortables" class="meta-box-sortables ui-sortable">
						
				<h3><?php _e('Buy AdRotate Professional', 'adrotate'); ?></h3>
				<div class="postbox-ajdg">
					<div class="inside">
						<a href="https://ajdg.solutions/products/adrotate-for-wordpress/?pk_campaign=adrotate-free&pk_kwd=infopage"><img src="<?php echo plugins_url('/images/adrotate-product.png', dirname(__FILE__)); ?>" alt="adrotate-product" width="150" height="150" align="right" style="padding: 0 0 10px 10px;" /></a>
						<p><h4><?php _e('Single License', 'adrotate'); ?> (&euro; 29.00)</h4><?php _e('One WordPress installation.', 'adrotate'); ?> <a href="https://ajdg.solutions/cart/?add-to-cart=1124&pk_campaign=adrotate-free&pk_kwd=single" target="_blank"><?php _e('Buy now', 'adrotate'); ?> &raquo;</a></p>
						<p><h4><?php _e('Duo License', 'adrotate'); ?> (&euro; 39.00)</h4><?php _e('Two WordPress installations.', 'adrotate'); ?> <a href="https://ajdg.solutions/cart/?add-to-cart=1126&pk_campaign=adrotate-free&pk_kwd=duo" target="_blank"><?php _e('Buy now', 'adrotate'); ?> &raquo;</a></p>
						<p><h4><?php _e('Multi License', 'adrotate'); ?> (&euro; 89.00)</h4><?php _e('Up to five WordPress installations.', 'adrotate'); ?> <a href="https://ajdg.solutions/cart/?add-to-cart=1128&pk_campaign=adrotate-free&pk_kwd=multi" target="_blank"><?php _e('Buy now', 'adrotate'); ?> &raquo;</a></p>
						<p><h4><?php _e('Developer License', 'adrotate'); ?> (&euro; 199.00)</h4><?php _e('Unlimited WordPress installations and/or networks.', 'adrotate'); ?> <a href="https://ajdg.solutions/cart/?add-to-cart=1130&pk_campaign=adrotate-free&pk_kwd=developer" target="_blank"><?php _e('Buy now', 'adrotate'); ?> &raquo;</a></p>
						<p><h4><?php _e('Compare licenses', 'adrotate'); ?></h4> <?php _e('Not sure which license is for you? Compare them...', 'adrotate'); ?> <a href="https://ajdg.solutions/products/adrotate-for-wordpress/?pk_campaign=adrotate-free&pk_kwd=compare" target="_blank"><?php _e('All Licenses', 'adrotate'); ?> &raquo;</a></p>
<!--
						<img src="<?php echo plugins_url('/images/adrotate-product.png', dirname(__FILE__)); ?>" alt="adrotate-product" width="150" height="150" align="right" style="padding: 0 0 10px 10px;" />
						<p><h4><?php _e('Lifetime License', 'adrotate'); ?> (&euro; 59.00)</h4><?php _e('Single installation.', 'adrotate'); ?> <a href="https://ajdg.solutions/cart/?add-to-cart=1124&utm_campaign=buy-single&utm_medium=info-page&utm_source=adrotate-free" target="_blank"><?php _e('Buy now', 'adrotate'); ?> &raquo;</a></p>
						<p><h4><?php _e('Duo License', 'adrotate'); ?> (&euro; 29.00 p/year)</h4><?php _e('Up to 2 installations.', 'adrotate'); ?> <a href="https://ajdg.solutions/cart/?add-to-cart=1126&utm_campaign=buy-duo&utm_medium=info-page&utm_source=adrotate-free" target="_blank"><?php _e('Buy now', 'adrotate'); ?> &raquo;</a></p>
						<p><h4><?php _e('Multi License', 'adrotate'); ?> (&euro; 99.00 p/year)</h4><?php _e('Up to 10 installations.', 'adrotate'); ?> <a href="https://ajdg.solutions/cart/?add-to-cart=1128&utm_campaign=buy-multi&utm_medium=info-page&utm_source=adrotate-free" target="_blank"><?php _e('Buy now', 'adrotate'); ?> &raquo;</a></p>
						<p><h4><?php _e('Developer License', 'adrotate'); ?> (&euro; 199.00 p/year)</h4><?php _e('Up to 25 installations or multisite networks.', 'adrotate'); ?> <a href="https://ajdg.solutions/cart/?add-to-cart=1130&utm_campaign=buy-developer&utm_medium=info-page&utm_source=adrotate-free" target="_blank"><?php _e('Buy now', 'adrotate'); ?> &raquo;</a></p>
						<p><em><?php _e('Subscriptions get 1 year access to updates, email support & AdRotate Geo.', 'adrotate'); ?></em></p>
						<p><h4><?php _e('Compare licenses', 'adrotate'); ?></h4> <?php _e("Not sure which license is for you?", 'adrotate'); ?> <a href="https://ajdg.solutions/products/adrotate-for-wordpress/?utm_campaign=compare-license&utm_medium=info-page&utm_source=adrotate-free" target="_blank"><?php _e('Compare Licenses', 'adrotate'); ?> &raquo;</a></p>
-->
					</div>
				</div>

				<h3><?php _e('Join the Media.net advertising network', 'adrotate'); ?></h3>
				<div class="postbox-ajdg">
					<div class="inside">
						<center><a href="http://ajdg.link/medianet" target="_blank"><img src="<?php echo plugins_url("/images/medianet-large-$partner.jpg", dirname(__FILE__)); ?>" width="440" /></a></center>
						<p><a href="http://ajdg.link/medianet" target="_blank">Media.net</a> is the <strong>#2 largest contextual ads platform</strong> in the world that provides its publishers with an <strong>exclusive access to the Yahoo! Bing Network of advertisers and $6bn worth of search demand.</strong></p>

						<p><a href="http://ajdg.link/medianet" target="_blank">Media.net</a> <strong>ads are contextual</strong> and hence always relevant to your content. They are also <strong>native by design</strong> and highly customizable, delivering a great user experience and higher CTRs.</p>
						
						<strong><u>Some of the benefits when joining Media.net</u></strong>
						<p>You <strong>get your own Account Manager</strong> at <a href="http://ajdg.link/medianet" target="_blank">Media.net</a> who guides you every step of the way with suggestions that will help you max your revenue potential. <a href="http://ajdg.link/medianet" target="_blank">Media.net</a> is <strong>the #1 alternative to Google Adsense</strong> and works very well with whatever your current revenue strategy may be.</p>

						<strong><u>Exclusive offer for AdRotate users</u></strong>
						<p>As an AdRotate user, sign up with <a href="http://ajdg.link/medianet" target="_blank">Media.net</a> and you'll earn 10% more, over and above your regular earnings for your first 3 months. <strong>Sign up now!</strong></p>
						
						<p><a class="button-primary" href="http://ajdg.link/medianet" target="_blank">Sign up with Media.net now &raquo;</a>&nbsp;&nbsp;<a class="button" target="_blank" href="http://ajdg.link/medianet">Learn more &raquo;</a></p>
					</div>
				</div>

			</div>	
		</div>

	</div>

	<div class="clear"></div>
	<p><?php echo adrotate_trademark(); ?></p>
</div>