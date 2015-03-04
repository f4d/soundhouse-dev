<?php include ( TEMPLATEPATH . '/options-var.php' ); ?>

<div id="services-content">
	<?php
	$all='';

	$default_desc = __('You can replace this area with a new text in <a href="wp-admin/themes.php?page=custom-homepage.php">your theme options</a> and <a href="wp-admin/themes.php?page=custom-homepage.php">upload and crop new images</a> to replace the image you can see here already.', TEMPLATE_DOMAIN);

	$services = get_option('blogs-mu_services');
	if($services===false || count($services)==0 ){
		// if no services configured, display 3 blank
		$d = array('headline'=>'Service Headline', 'url'=>'', 'description'=>$default_desc, 'attachment_post_id'=> 0);
		$services = array($d, $d, $d);
	}

	foreach($services as $k=>$ar){
		//keys = 'headline', 'url', 'description', 'attachment_post_id'

		// truncate to 200 chars, replace last space with '...'
		if($ar['description']!=$default_desc){
			$description = substr($ar['description'],0,200).'...';
			if( function_exists('do_shortcode') ) {
				$description = do_shortcode($description);
			}
		}

		if(strlen($description)==0){
			$description = $default_desc;
		}

		// get service img
		$default ='<img src="'.get_template_directory_uri().'/_inc/images/default.jpg" alt="img" />';
		$custom = wp_get_attachment_image( $ar['attachment_post_id'], 'medium' );
		$img = $custom == '' ? $default : $custom;

		$all .= '<div class="sbox">
						<div class="simg">
							<div class="img-services">
								<a href="'.$ar['url'].'">
									'.$img.'
								</a>
							</div>
						</div>
						<h3>'.$ar['headline'].'</h3>
						<p>
							'.$description.'
							<span class="learn-more">
								<a href="'.$ar['url'].'">'.__("Find out more", TEMPLATE_DOMAIN).'</a>
							</span>
						</p>
				</div>';
	}
	echo $all;
	?>
</div>