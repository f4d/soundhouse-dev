<?php
define(SERVICE_SLUG, 'edit-service-options');
add_action('admin_menu', 'blogsmu_features_register');

function blogsmu_features_register() {
	update_services_data_structure();
	add_theme_page(_g ( $themename . __(' Services Options',TEMPLATE_DOMAIN)),  _g ( __('Services Options',TEMPLATE_DOMAIN)),  'edit_theme_options', SERVICE_SLUG, 'blogsmu_features_page');
}

function blogsmu_features_page() {
	global $theme_version, $themename;

	echo '<div id="options-panel">
			<div id="options-head">
				<h2>'.$themename.' '.__("Custom Homepage Options",TD).'</h2>
				<div class="theme-versions">'.__("Version",TD).' '.$theme_version.'</div>
			</div>
			<div id="sbtabs_uploads">
				<div class="tabc">

					'.(isset($_GET['saved'])?'<div id="message" class="updated fade"><p><strong>'.__('Settings saved.', TD).'</strong></p></div>':'').'

					<p>'.__('Service images and text can be added/removed here. These will be shown on your homepage. Optimal image sizes are',TD).' <strong>260px by 150px</strong>.</p>

					<div class="add_save_panel">
						<input type="submit" value="Add Service" class="button add_service">
						<input type="submit" value="Save All" class="button-primary save_all">
					</div>

					'.get_services_form().'

					<div class="one_service_template">
						'.get_one_service(0).'
					</div>

				</div>
			</div>
		</div>';
}

function get_services_form(){
	$services = get_option('blogs-mu_services');

	$all='';
	if(	$services!==false ){
		foreach($services as $k => $a){
			$all .= get_one_service($k,$a);
		}
	}

	return '<form class="services" method="post" name="services">
				'.$all.'

				<input type="hidden" name="services-options" value="1" />
			</form>';
}

function get_one_service($index, $a=array('headline'=>'', 'url'=>'', 'description'=>'', 'attachment_post_id'=>0)){
	$num = $index+1;
	$img_class = $a['attachment_post_id']==0?'no_image':'one_image';
	$i=$index;

	return '<div class="one_service" data-service-id="'.$i.'">
				<h4 class="stitle">Service '.$num.'</h4>

				<div class="service_image '.$img_class.'">
					<input type="submit" value="Add Image" class="button" name="add_image">

					<div class="image_control">
						<input type="submit" value="Edit Image" class="button edit_image">
						<input type="submit" value="Remove Image" class="button" name="remove_image" class="remove_image">
					</div>

					'.wp_get_attachment_image($a['attachment_post_id'], 'medium').'

					<input type="hidden" name="s['.$i.'][attachment_post_id]" value="'.$a['attachment_post_id'].'" />
				</div>

				<label>Headline</label>
				<input type="text" name="s['.$i.'][headline]" value="'.$a['headline'].'" placeholder="Headline">

				<label>URL</label>
				<input type="text" name="s['.$i.'][url]" value="'.$a['url'].'" placeholder="URL">

				<label>Description</label>
				<textarea name="s['.$i.'][description]" placeholder="Description">'.$a['description'].'</textarea><br>
				<i>Please don\'t use any block HTML element like div, p, table etc.</i>

				<input type="submit" value="Remove Service" class="remove_service button">
				<div class="clear"></div>
			</div>';
}

/**
 * save form data to wordpress options table
 */
function process_post(){
	global $blog_id;
	if( current_user_can('edit_theme_options') &&
		isset($_POST['services-options'])
	){
		$services = (isset($_POST['s']) && is_array($_POST['s'])) ? $_POST['s'] : array();
		update_option('blogs-mu_services', stripslashes_deep($services));
		$url = get_admin_url($blog_id, '/themes.php?page='.SERVICE_SLUG.'&saved=1');
		header("Location: $url");
		exit();
	}
}
add_action( 'admin_init', 'process_post', 1 );

/**
 * takes data for the old services options page and copies it to the new structure.
 * moves images to the wordpress media manager to support better image management
 * the new structure is required to reduce code duplication in the old version.
 * theme updates will keep the old services configuration (Appearance > Services Options)
 * @return bool true if the new option was added to the options table
 */
function update_services_data_structure(){

	// if new structure already stored do not update
	if(	get_option('blogs-mu_services')!==false ){
		return false;
	}

	require_once(ABSPATH . 'wp-admin/includes/image.php'); //needed for wp_generate_attachment_metadata()

	// path used for old image storage (<= blogs-mu version 1.3.6.3)
	$upload_dir = wp_upload_dir();
	$upload_path = $upload_dir['basedir'] . '/thumb/';

	$new_services = array();

	// services range from #1 - #9
	$i=1;
	while($i<10){

		// new_key => old_key
		$option=array(
			'headline' => "tn_blogsmu_headline{$i}",
			'url' => "tn_blogsmu_headline{$i}_link",
			'description' => "tn_blogsmu_text{$i}"
		);

		// old image paths ordered by preference
		$image=array(
			"{$upload_path}blogsmu{$i}_normal.jpg",
			"{$upload_path}blogsmu{$i}_thumb.jpg"
		);

		$new_default = array('headline'=>'', 'url'=>'', 'description'=>'', 'attachment_post_id'=>0);
		$new = array();

		// find prefered image
		foreach($image as $path){
			if(file_exists($path)){
				// move to wordpress media manager
				$wp_filetype = wp_check_filetype(basename($path), null);

				$post=array(
					'post_title'=> "Blogs MU Service Image {$i}",
					'post_mime_type'=> $wp_filetype['type'],
					'post_status'=> 'inherit',
					'post_content '=>''
				);

				$attach_id = wp_insert_attachment($post, $path);
				if(is_int($attach_id) && $attach_id>0){
					$attach_data = wp_generate_attachment_metadata( $attach_id, $path );
					wp_update_attachment_metadata( $attach_id, $attach_data );
					$new['attachment_post_id'] =  $attach_id;
					break;
				}
			}
		}

		// check if any options are set, if so copy them to new structure
		foreach($option as $new_key => $old_key){
			$str = get_option($old_key);
			if($str!==false){
				$new[$new_key] = $str;
			}
		}

		if(count($new)>0){
			$new_services[] = array_merge($new_default, $new);
		}

		$i++;
	}

	// save new structure to wordpress options table
	update_option('blogs-mu_services', $new_services);
	return true;
}

// load files for wp media selector if its the theme services options page
function blogsmu_options_enqueue_scripts(){
	wp_register_script( 'blogs-mu_services', get_template_directory_uri() .'/_inc/js/services.js', array('jquery','media-upload','thickbox') );
	if ( strpos(get_current_screen()->id, 'appearance_page_edit-service-options') !== false ) {
		wp_enqueue_script('jquery');
		wp_enqueue_script('thickbox');
		wp_enqueue_style('thickbox');
		wp_enqueue_script('media-upload');
		wp_enqueue_script('blogs-mu_services');
	}
}
add_action('admin_enqueue_scripts', 'blogsmu_options_enqueue_scripts');

function blogsmu_options_setup(){
	global $pagenow;
	if ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow ){
		// Now we'll replace the 'Insert into Post Button' inside Thickbox
		add_filter( 'gettext', 'replace_thickbox_text'  , 1, 3 );
	}
}
add_action( 'admin_init', 'blogsmu_options_setup' );

function replace_thickbox_text($translated_text, $text, $domain){
	if ('Insert into Post' == $text) {
		$referer = strpos( wp_get_referer(), 'blogsmu-settings' );
		if ( $referer != '' ) {
			return __('Use this image.', 'blogsmu' );
		}
	}
	return $translated_text;
}
?>