<?php
/*
Plugin Name: AMP Toolbox
Plugin URI:
Description: This is a plugin that extends and fixes functionality from the AMP plugin, allowing you a better control of your AMP pages.
Version: 2.1.1
Author: deano1987
Author URI: http://deano.me
*/

$opt 						= array();

$opt['view_amp'] = '1';
$opt['view_amp_text'] = 'There is a Mobile Optimized version of this page (AMP). <a href="[amp_url]" title="[title]">Open Mobile Version</a>.';
$opt['view_amp_css'] = '.amp_toolbox_post_box_link {
    background-color: #ffe18d;
    font-weight: bold;
    padding: 5px 5px 5px 15px;
    margin-bottom: 20px;
}';
$opt['view_amp_priority'] = '0';
$opt['view_amp_position'] = '0';

$opt['view_original'] = '1';
$opt['amp_analytics'] = '0';
$opt['amp_analytics_id'] = '';

$opt['view_original_text'] = 'You are currently viewing the Mobile Optimized version (AMP), some features may be missing or may not work as expected. <a href="[original_url]" title="[title]">Open Full Version</a>.';
$opt['view_original_css'] = '.amp_toolbox_post_box_link {
    background-color: #ffe18d;
    font-weight: bold;
    padding: 5px 5px 5px 15px;
    margin-bottom: 20px;
}';
$opt['view_original_priority'] = '0';
$opt['view_original_position'] = '0';

$opt['override_publisher_logo'] 		= "0";
$opt['publisher_logo_url'] 		= "";
$opt['publisher_logo_width'] 		= "";
$opt['publisher_logo_height'] 	= "";

$opt['override_header_logo'] 	= "0";
$opt['override_header_css'] 	= ".amp-wp-header {
background:#e8e8e8;
padding:12px 0;
}

.amp-wp-header a {
background-image:url(/path/to/image);
background-repeat:no-repeat;
background-size:contain;
display:block;
height:85px;
width:320px;
text-indent:-9999px;
margin:0 auto;
}";

add_option("amp_toolbox_settings",$opt);

if ( ! class_exists( 'WDPanelAdmin2' ) ) {
	require_once('WDPanelAdmin.php');
}
if ( ! class_exists( 'AmpToolbox_WDPanelAdmin' ) ) {

	class AmpToolbox_WDPanelAdmin extends WDPanelAdmin2 {

		var $hook 		= 'amp-toolbox';
		var $longname	= 'AMP Toolbox Configuration';
		var $shortname	= 'AMP Toolbox';
		var $filename	= 'amp-toolbox/amp-toolbox.php';
		var $ozhicon	= 'script_link.png';

		function clean_css($csstovalidateindiv) {
			$csstidy = new csstidy();
			$csstidy->set_cfg( 'css_level', 'CSS3.0' );

			$csstidy->parse( $csstovalidateindiv );
			return $csstidy->print->plain();
		}

		function config_page() {
			if ( isset($_POST['submit']) ) {
				if (!current_user_can('manage_options')) die(__('You cannot edit these options.'));
				check_admin_referer('schema-breadcrumbs-updatesettings');

				//clean up css??
				require_once( 'css-tidy/class.csstidy.php' );


				$opt 						= array();

				$opt['view_amp'] 		= (int)$_POST['view_amp'];
				$opt['view_amp_text'] 		= wp_kses_post($_POST['view_amp_text']);
				$opt['view_amp_css'] 		= $this->clean_css($_POST['view_amp_css']);
				$opt['view_amp_priority'] 		= (int)$_POST['view_amp_priority'];
				$opt['view_amp_position'] 		= (int)$_POST['view_amp_position'];

				$opt['view_original'] 		= (int)$_POST['view_original'];
				$opt['view_original_text'] 		= wp_kses_post($_POST['view_original_text']);
				$opt['view_original_css'] 		= $this->clean_css($_POST['view_original_css']);
				$opt['view_original_priority'] 		= (int)$_POST['view_original_priority'];
				$opt['view_original_position'] 		= (int)$_POST['view_original_position'];

				$opt['amp_analytics'] 		= (int)$_POST['amp_analytics'];
				$opt['amp_analytics_id'] 		= wp_kses_post($_POST['amp_analytics_id']);

				$opt['override_publisher_logo'] 		= (int)$_POST['override_publisher_logo'];
				$opt['publisher_logo_url'] 		= esc_url($_POST['publisher_logo_url']);
				$opt['publisher_logo_width'] 		= (int)$_POST['publisher_logo_width'];
				$opt['publisher_logo_height'] 		= (int)$_POST['publisher_logo_height'];

				$opt['override_header_logo'] 		= (int)$_POST['override_header_logo'];
				$opt['override_header_css'] 		= $this->clean_css($_POST['override_header_css']);

				update_option('amp_toolbox_settings', $opt);
			}

			$opt  = get_option('amp_toolbox_settings');
			?>
			<div class="wrap">

				<h2><?php echo $this->longname?></h2>
				<form action="" method="post" id="schemabreadcrumbs-conf">
					<div class="postbox-container" style="width:70%;">
						<div class="metabox-holder">
							<div class="meta-box-sortables">

								<?php if (function_exists('wp_nonce_field'))
										wp_nonce_field('schema-breadcrumbs-updatesettings');

								$rows = array();
								$rows[] = array(
									"id" => "view_amp",
									"label" => __('Link To AMP Version'),
									"content" => '<label for="view_amp_yes"><input type="radio" name="view_amp" id="view_amp_yes" value="1" '.($opt['view_amp']==1?'checked="checked"':'').' /> Yes</label>
												<label for="view_amp_no"><input type="radio" name="view_amp" id="view_amp_no" value="0" '.($opt['view_amp']==0?'checked="checked"':'').' /> No</label>
												<br>
												<i>If enabled you can position a link on normal post pages to your AMP version.</i>',
								);
								$rows[] = array(
									"id" => "view_amp_text",
									"label" => __('AMP Link Data'),
									"content" => '<textarea name="view_amp_text" id="view_amp_text" style="width:50%;height:200px">'.esc_textarea(stripslashes($opt['view_amp_text'])).'</textarea>
												<br>
												<i><b>[amp_url]</b> - AMP URL of this post.</i>
												<i><b>[original_url]</b> - Normal URL of this post.</i>
												<i><b>[title]</b> - Post title.</i>',
								);
								$rows[] = array(
									"id" => "view_amp_css",
									"label" => __('AMP Link CSS'),
									"content" => '<textarea name="view_amp_css" id="view_amp_css" style="width:50%;height:200px">'.esc_textarea(stripslashes($opt['view_amp_css'])).'</textarea>',
								);
								$rows[] = array(
									"id" => "view_amp_position",
									"label" => __('AMP Link Position'),
									"content" => '<label for="view_amp_position_top"><input type="radio" name="view_amp_position" id="view_amp_position_top" value="0" '.($opt['view_amp_position']==0?'checked="checked"':'').' /> Before</label>
												<label for="view_amp_position_bottom"><input type="radio" name="view_amp_position" id="view_amp_position_bottom" value="1" '.($opt['view_amp_position']==1?'checked="checked"':'').' /> After</label>
												<br>
												<i>Before or after the post?</i>',
								);
								$rows[] = array(
									"id" => "view_amp_priority",
									"label" => __('AMP Link Priority'),
									"content" => '<input name="view_amp_priority" id="view_amp_position" type="number" value="'.(int)$opt['view_amp_priority'].'" /><br>
													<i>The priority, 0 is generally at the top</i>
													',
								);




								$table = $this->form_table($rows);


								$this->postbox('breadcrumbssettings',__('Link To AMP Version'), $table.'<div class="submit"><input type="submit" class="button-primary" name="submit" value="Save Settings" /></div>')
								?>
							</div>
						</div>
						<div class="metabox-holder">
							<div class="meta-box-sortables">

								<?php if (function_exists('wp_nonce_field'))
										wp_nonce_field('schema-breadcrumbs-updatesettings');

								$rows = array();
								$rows[] = array(
									"id" => "view_original",
									"label" => __('Link To Original Version'),
									"content" => '<label for="view_original_yes"><input type="radio" name="view_original" id="view_original_yes" value="1" '.($opt['view_original']==1?'checked="checked"':'').' /> Yes</label>
												<label for="view_original_no"><input type="radio" name="view_original" id="view_original_no" value="0" '.($opt['view_original']==0?'checked="checked"':'').' /> No</label>
												<br><i>If enabled you can position a link on AMP post pages to your original version.</i>',
								);
								$rows[] = array(
									"id" => "view_original_text",
									"label" => __('Original Link Data'),
									"content" => '<textarea name="view_original_text" id="view_original_text" style="width:50%;height:200px">'.esc_textarea(stripslashes($opt['view_original_text'])).'</textarea>
												<br>
												<i><b>[amp_url]</b> - AMP URL of this post.</i>
												<i><b>[original_url]</b> - Normal URL of this post.</i>
												<i><b>[title]</b> - Post title.</i>',
								);
								$rows[] = array(
									"id" => "view_original_css",
									"label" => __('Original Link CSS'),
									"content" => '<textarea name="view_original_css" id="view_original_css" style="width:50%;height:200px">'.esc_textarea(stripslashes($opt['view_original_css'])).'</textarea>',
								);
								$rows[] = array(
									"id" => "view_original_position",
									"label" => __('AMP Link Position'),
									"content" => '<label for="view_amp_position_top"><input type="radio" name="view_original_position" id="view_amp_position_top" value="0" '.($opt['view_original_position']==0?'checked="checked"':'').' /> Before</label>
												<label for="view_amp_position_bottom"><input type="radio" name="view_original_position" id="view_amp_position_bottom" value="1" '.($opt['view_original_position']==1?'checked="checked"':'').' /> After</label>
												<br>
												<i>Before or after the post?</i>',
								);
								$rows[] = array(
									"id" => "view_original_priority",
									"label" => __('AMP Link Priority'),
									"content" => '<input name="view_original_priority" id="view_original_priority" type="number" value="'.(int)$opt['view_original_priority'].'" />
													<br>
													<i>The priority, 0 is generally at the top</i>
													',
								);

								$table = $this->form_table($rows);


								$this->postbox('breadcrumbssettings',__('Link To Original Version'), $table.'<div class="submit"><input type="submit" class="button-primary" name="submit" value="Save Settings" /></div>')
								?>
							</div>
						</div>
						<div class="metabox-holder">
							<div class="meta-box-sortables">

								<?php if (function_exists('wp_nonce_field'))
										wp_nonce_field('schema-breadcrumbs-updatesettings');

								$rows = array();
								$rows[] = array(
									"id" => "amp_analytics",
									"label" => __('Use AMP Analytics?'),
									"content" => '<label for="view_original_yes"><input type="radio" name="amp_analytics" id="amp_analytics_yes" value="1" '.($opt['amp_analytics']==1?'checked="checked"':'').' /> Yes</label>
												<label for="view_original_no"><input type="radio" name="amp_analytics" id="amp_analytics_no" value="0" '.($opt['amp_analytics']==0?'checked="checked"':'').' /> No</label>
												<br><i>If enabled you can apply AMP Analytics tracking to your AMP pages.</i>',
								);
								$rows[] = array(
									"id" => "amp_analytics_id",
									"label" => __('Google Analytics ID'),
									"content" => '<input name="amp_analytics_id" id="amp_analytics_id" type="text" value="'.esc_textarea($opt['amp_analytics_id']).'" />
												<br>
												<i>Paste your Google Analytics ID.</i>',
								);

								$table = $this->form_table($rows);


								$this->postbox('breadcrumbssettings',__('AMP Analytics'), $table.'<div class="submit"><input type="submit" class="button-primary" name="submit" value="Save Settings" /></div>')
								?>
							</div>
						</div>
						<div class="metabox-holder">
							<div class="meta-box-sortables">

								<?php if (function_exists('wp_nonce_field'))
										wp_nonce_field('schema-breadcrumbs-updatesettings');

								$rows = array();
								$rows[] = array(
									"id" => "override_publisher_logo",
									"label" => __('Override Publisher Logo Schema?'),
									"content" => '<label for="override_publisher_logo_yes"><input type="radio" name="override_publisher_logo" id="override_publisher_logo_yes" value="1" '.($opt['override_publisher_logo']==1?'checked="checked"':'').' /> Yes</label>
												<label for="override_publisher_logo_no"><input type="radio" name="override_publisher_logo" id="override_publisher_logo_no" value="0" '.($opt['override_publisher_logo']==0?'checked="checked"':'').' /> No</label>
												<br><i>If enabled you can position a link on AMP post pages to your original version.</i>',
								);
								$rows[] = array(
									"id" => "publisher_logo_url",
									"label" => __('Publisher Logo URL'),
									"content" => '<input name="publisher_logo_url" id="publisher_logo_url" type="text" style="width:50%" value="'.esc_url($opt['publisher_logo_url']).'" />
													<br>
													<i>Full URL or absolute path from domain.</i>
													',
								);
								$rows[] = array(
									"id" => "publisher_logo_width",
									"label" => __('Publisher Logo Width'),
									"content" => '<input name="publisher_logo_width" id="publisher_logo_width" type="number" style="width:100px" value="'.(int)$opt['publisher_logo_width'].'" />px

													',
								);
								$rows[] = array(
									"id" => "publisher_logo_height",
									"label" => __('Publisher Logo Height'),
									"content" => '<input name="publisher_logo_height" id="publisher_logo_height" type="number" style="width:100px" value="'.(int)$opt['publisher_logo_height'].'" />px

													',
								);


								$rows[] = array(
									"id" => "override_header_logo",
									"label" => __('Override AMP Header?'),
									"content" => '<label for="override_header_logo_yes"><input type="radio" name="override_header_logo" id="override_header_logo_yes" value="1" '.($opt['override_header_logo']==1?'checked="checked"':'').' /> Yes</label>
												<label for="override_header_logo_no"><input type="radio" name="override_header_logo" id="override_header_logo_no" value="0" '.($opt['override_header_logo']==0?'checked="checked"':'').' /> No</label>
												<br><i>If enabled you override the header with a logo, sizes and colours of your choice.</i>',
								);
								$rows[] = array(
									"id" => "override_header_css",
									"label" => __('Header CSS'),
									"content" => '<textarea name="override_header_css" id="override_header_css" style="width:50%;height:200px">'.esc_textarea(stripslashes($opt['override_header_css'])).'</textarea>',
								);

								$table = $this->form_table($rows);


								$this->postbox('breadcrumbssettings',__('Other Settings'), $table.'<div class="submit"><input type="submit" class="button-primary" name="submit" value="Save Settings" /></div>')
								?>
								<script>
									//view_amp
									jQuery('#view_amp_yes, #view_amp_no').on('change', function() {
										if (jQuery('#view_amp_yes').prop("checked")) {
											jQuery('#tr_view_amp_text').show();
											jQuery('#tr_view_amp_css').show();
											jQuery('#tr_view_amp_position').show();
										} else {
											jQuery('#tr_view_amp_text').hide();
											jQuery('#tr_view_amp_css').hide();
											jQuery('#tr_view_amp_position').hide();
										}
									});
									jQuery('#view_amp_yes').trigger('change');

									//view_original
									jQuery('#view_original_yes, #view_original_no').on('change', function() {
										if (jQuery('#view_original_yes').prop("checked")) {
											jQuery('#tr_view_original_text').show();
											jQuery('#tr_view_original_css').show();
											jQuery('#tr_view_original_position').show();
										} else {
											jQuery('#tr_view_original_text').hide();
											jQuery('#tr_view_original_css').hide();
											jQuery('#tr_view_original_position').hide();
										}
									});
									jQuery('#view_original_yes').trigger('change');

									//override_publisher_logo
									jQuery('#override_publisher_logo_yes, #override_publisher_logo_no').on('change', function() {
										if (jQuery('#override_publisher_logo_yes').prop("checked")) {
											jQuery('#tr_publisher_logo_url').show();
											jQuery('#tr_publisher_logo_width').show();
											jQuery('#tr_publisher_logo_height').show();
										} else {
											jQuery('#tr_publisher_logo_url').hide();
											jQuery('#tr_publisher_logo_width').hide();
											jQuery('#tr_publisher_logo_height').hide();
										}
									});
									jQuery('#override_publisher_logo_yes').trigger('change');
								</script>

							</div>
						</div>
					</div>
					<div class="postbox-container" style="width:30%;padding-left:10px;box-sizing: border-box;">
						<div class="metabox-holder">
							<div class="meta-box-sortables">
								<center style="background-color:white;">
									<a href="https://webdesires.co.uk" target="_blank">
										<div style="margin-bottom:20px;padding:5px 10px 10px 10px">
											<img style="width:100%" src="<?php echo plugins_url( '/WebDesiresLogo.png', __FILE__ ); ?>" alt="WebDesires - Web Development" title="WebDesires - Web Development" /><br>
											Looking for a developer?<br>
											Professional UK WordPress Web Development Company
										</div>
									</a>
								</center>
								<?php
									$this->plugin_like();
									$this->plugin_support();
									$this->wd_knowledge();
									$this->wd_news();
								?>
							</div>
							<br/><br/><br/>
						</div>
					</div>
				</form>
			</div>

<?php		}
	}

	$ybc = new AmpToolbox_WDPanelAdmin();
}

$opt  = get_option('amp_toolbox_settings');

if ($opt['view_amp'] == 1) {

	//modify content for AMP pages
	function amptoolbox_amp_page_mods($content) {
		if (is_page()) {
				$ampurl = '?amp';
		} else {
				$ampurl = 'amp/';
		}
		if( is_singular() && is_main_query() ) {
			if (function_exists('is_amp_endpoint') && is_amp_endpoint()) {

				$post_id = get_the_ID();
				$post = get_post($post_id);
				$opt  = get_option('amp_toolbox_settings');
				$text = stripslashes($opt['view_original_text']);
				$text = str_replace('[amp_url]', esc_url(rtrim(get_permalink($post_id), '/') . '/' . $ampurl), $text);
					$text = str_replace('[original_url]', esc_url(rtrim(get_permalink($post_id), '/') . '/'), $text);
				$text = str_replace('[title]', preg_replace('/[^a-Z0-9-. ]/', '', $post->post_title), $text);
				if ($opt['view_original_position'] == 0) {
					$content ="<div class='amp_toolbox_post_box_link'><p>".($text)."</p></div>" . $content;
				} else {
					$content = $content."<div class='amp_toolbox_post_box_link'><p>".($text)."</p></div>";
				}

				//strip bad tags + data
				$tags = array('embed', 'font');
				$content = preg_replace( '#<(' . implode( '|', $tags) . ').*>.*?</\1>#s', '', $content);

				//replace attributes
				$content = preg_replace('/(<[^>]+) pikto-uid=".*?"/i', '$1', $content);

				//strip bad tags + leave data
				$content = str_replace('<quote','<em', $content );

				/*if ($opt['amp_analytics'] == 1) {
					//add AMP Analytics ID if set
					$content = '<amp-analytics type="googleanalytics" id="analytics1">
						<script type="application/json">
						{
						  "vars": {
							"account": "'.$opt['amp_analytics_id'].'"
						  },
						  "triggers": {
							"trackPageview": {
							  "on": "visible",
							  "request": "pageview"
							}
						  }
						}
						</script>' . $content;
				}*/
			}
		}

		return $content;
	}

	//Modify content for normal pages
	function amptoolbox_normal_page_mods($content) {
		$post_id = get_the_ID();
		$post = get_post($post_id);

		if (is_page()) {
				$ampurl = '?amp';
		} else {
				$ampurl = 'amp/';
		}

		if( is_singular() && is_main_query() ) {
			if (post_supports_amp($post)) {
				if (function_exists('is_amp_endpoint') && !is_amp_endpoint()) {
					$opt  = get_option('amp_toolbox_settings');
					$text = stripslashes($opt['view_amp_text']);
					$text = str_replace('[amp_url]', esc_url(rtrim(get_permalink($post_id), '/') . '/' . $ampurl), $text);
					$text = str_replace('[original_url]', esc_url(rtrim(get_permalink($post_id), '/') . '/'), $text);
					$text = str_replace('[title]', preg_replace('/[^a-Z0-9-. ]/', '', $post->post_title), $text);
					$text = $text.'</p><style>'.$opt['view_amp_css'].'</style>';
					if ($opt['view_amp_position'] == 0) {
						$content ="<div class='amp_toolbox_post_box_link'><p>".($text)."</div>" . $content;
					} else {
						$content = $content."<div class='amp_toolbox_post_box_link'><p>".($text)."</div>";
					}
				}
			}
		}

		return $content;
	}

	//modify header on AMP pages (DEACTIVATED SEE BELOW)
	function amptoolbox_amp_head_mods() {
		$post_id = get_the_ID();
		$post = get_post($post_id);
		if (post_supports_amp($post)) {
			if (function_exists('is_amp_endpoint') && is_amp_endpoint()) {
				$opt  = get_option('amp_toolbox_settings');

				if ($opt['amp_analytics'] == 1) {
					echo '<script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>';
				}
			}
		}

	}

	//modify header on normal pages
	function amptoolbox_normal_head_mods() {
		$post_id = get_the_ID();
		$post = get_post($post_id);
		if (post_supports_amp($post)) {
			if (is_page()) {
				$ampurl = '?amp';
			} else {
					$ampurl = 'amp/';
			}

			if (function_exists('is_amp_endpoint') && !is_amp_endpoint() && !is_category()) {
				$opt  = get_option('amp_toolbox_settings');

				//alternate handheld header
				//echo "<link rel=\"alternate\" media=\"handheld\" href=\"".esc_url(rtrim(get_permalink($post_id), '/') . '/' . $ampurl)."\" />";
				//echo "<link rel=\"amphtml\" href=\"".esc_url(rtrim(get_permalink($post_id), '/') . '/' . $ampurl)."\" />";

			}
		}

	}

	function xyz_amptoolbox_amp_my_additional_css_styles() {
		// only CSS here please...
		$opt  = get_option('amp_toolbox_settings');
		echo $opt['view_original_css'];
	}

	add_filter( 'the_content', 'amptoolbox_amp_page_mods', $opt['view_original_priority'] );
	add_filter( 'the_content', 'amptoolbox_normal_page_mods', $opt['view_amp_priority'] );
	//add_filter('amp_post_template_head', 'amptoolbox_amp_head_mods');
	add_action('wp_head', 'amptoolbox_normal_head_mods');
	add_action( 'amp_post_template_css', 'xyz_amptoolbox_amp_my_additional_css_styles' );


	function amptoolbox_add_custom_analytics( $analytics ) {
		$opt  = get_option('amp_toolbox_settings');


		if ( ! is_array( $analytics ) ) {
			$analytics = array();
		}

		if ($opt['amp_analytics'] == 1) {
			// https://developers.google.com/analytics/devguides/collection/amp-analytics/
			$analytics['xyz-googleanalytics'] = array(
				'type' => 'googleanalytics',
				'attributes' => array(
					// 'data-credentials' => 'include',
				),
				'config_data' => array(
					'vars' => array(
						'account' => $opt['amp_analytics_id']
					),
					'triggers' => array(
						'trackPageview' => array(
							'on' => 'visible',
							'request' => 'pageview',
						),
					),
				),
			);

		}

		return $analytics;
	}

	add_filter( 'amp_post_template_analytics', 'amptoolbox_add_custom_analytics' );
}

if ($opt['override_publisher_logo'] == 1) {
	add_filter( 'amp_post_template_metadata', 'xyz_amptoolbox_amp_modify_json_metadata', 10, 2 );
}

function xyz_amptoolbox_amp_modify_json_metadata( $metadata, $post ) {
    //$metadata['@type'] = 'NewsArticle';

	$opt = get_option('amp_toolbox_settings');

   $metadata['publisher']['logo'] = array(
        '@type' => 'ImageObject',
        //'url' => get_template_directory_uri().'/images/logo.png',
        'url' => $opt['publisher_logo_url'],
        'height' => (string)$opt['publisher_logo_height'],
        'width' => (string)$opt['publisher_logo_width'],
    );

	if (!isset($metadata['image']) || $metadata['image'] == "") {
		$metadata['image'] = array(
			'@type' => 'ImageObject',
			'url' => $opt['publisher_logo_url'],
			'height' => (string)$opt['publisher_logo_height'],
			'width' => (string)$opt['publisher_logo_width'],
		);
	} else {
		if (isset($metadata['image']['height'])) {
			$metadata['image']['height'] = (string)$metadata['image']['height'];
			$metadata['image']['width'] = (string)$metadata['image']['width'];
		}
	}

    return $metadata;
}

if ($opt['override_header_logo'] == 1) {
	add_action( 'amp_post_template_css', 'xyz_amptoolbox_amp_additional_css_styles' );
}

function xyz_amptoolbox_amp_additional_css_styles( $amp_template ) {
    // only CSS here please...
	$opt = get_option('amp_toolbox_settings');

	echo stripslashes($opt['override_header_css']);
}

//remove generator tags from wordpress Amp
add_action( 'amp_post_template_head', 'amptoolbox_remove_amp_generator_meta_tag', 2 );
function amptoolbox_remove_amp_generator_meta_tag() {
    remove_action( 'amp_post_template_head', 'amp_add_generator_metadata' );
	remove_action('amp_post_template_head', 'wp_generator');
}

?>