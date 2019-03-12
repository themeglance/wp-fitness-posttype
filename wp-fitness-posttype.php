<?php
/*
 Plugin Name: WP Fitness Posttype
 Plugin URI: http://www.themesglance.com/
 Description: Creating new post type for WP Fitness Theme.
 Author: ThemeGlance
 Version: 0.1
 Author URI: http://www.themesglance.com/
*/

define( 'WP_FITNESS_POSTTYPE_VERSION', '0.1' );

add_action( 'init', 'wp_fitness_posttype_create_post_type' );

function wp_fitness_posttype_create_post_type() {
	register_post_type( 'our_trainers',
		array(
			'labels' => array(
				'name' => __( 'Our Trainers','wp-fitness-posttype' ),
				'singular_name' => __( 'Our Trainers','wp-fitness-posttype' )
				),
			'capability_type' =>  'post',
			'menu_icon'  => 'dashicons-groups',
			'public' => true,
			'supports' => array(
				'title',
				'editor',
				'thumbnail',
				)
			)
		);
	register_post_type( 'testimonials',
		array(
			'labels' => array(
				'name' => __( 'Success Stories','wp-fitness-posttype' ),
				'singular_name' => __( 'Success Stories','wp-fitness-posttype' )
				),
			'capability_type' => 'post',
			'menu_icon'  => 'dashicons-businessman',
			'public' => true,
			'supports' => array(
				'title',
				'editor',
				'thumbnail',
				)
			)
		);

}

add_action('admin_menu', 'wp_fitness_posttype_bn_custom_meta_trainer');

/* Adds a meta box to the Trainer editing screen */
function wp_fitness_posttype_bn_custom_meta_trainer() {

	add_meta_box( 'wp-fitness-posttype-trainer-meta', __( 'Enter Social URL', 'wp-fitness-posttype' ), 'wp_fitness_posttype_meta_callback_trainer', 'our_trainers', 'normal', 'high' );
}

/* Adds a meta box for custom post */
function wp_fitness_posttype_meta_callback_trainer( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'wp_fitness_posttype_trainer_meta_nonce' );
	$facebookurl = get_post_meta( $post->ID, 'wp_fitness_posttype_facebookurl', true );
	$twitterurl = get_post_meta( $post->ID, 'wp_fitness_posttype_twitterurl', true );
	$googleplusurl = get_post_meta( $post->ID, 'wp_fitness_posttype_googleplusurl', true );
	$linkdenurl = get_post_meta( $post->ID, 'wp_fitness_posttype_linkedinurl', true );
	$desig = get_post_meta( $post->ID, 'wp_fitness_posttype_tariner_desig', true );
	?>
	<div id="postcustom">
		<table id="list-table">
			<tbody id="the-list" data-wp-lists="list:meta">
				<tr id="meta-1">
					<td class="left">
						<?php esc_html_e( 'Facebook URL', 'wp-fitness-posttype' )?>
					</td>
					<td class="left">
						<input type="url" name="wp_fitness_posttype_facebookurl" id="wp_fitness_posttype_facebookurl" value="<?php echo esc_url( $facebookurl ); ?>" />
					</td>
				</tr>
				<tr id="meta-2">
					<td class="left">
						<?php esc_html_e( 'Twitter URL', 'wp-fitness-posttype' )?>
					</td>
					<td class="left">
						<input type="url" name="wp_fitness_posttype_twitterurl" id="wp_fitness_posttype_twitterurl" value="<?php echo esc_url( $twitterurl ); ?>" />
					</td>
				</tr>
				<tr id="meta-3">
					<td class="left">
						<?php esc_html_e( 'GooglePlus URL', 'wp-fitness-posttype' )?>
					</td>
					<td class="left" >
						<input type="url" name="wp_fitness_posttype_googleplusurl" id="wp_fitness_posttype_googleplusurl" value="<?php echo esc_url( $googleplusurl ); ?>" />
					</td>
				</tr>
				<tr id="meta-4">
					<td class="left">
						<?php esc_html_e( 'Linkedin URL', 'wp-fitness-posttype' )?>
					</td>
					<td class="left" >
						<input type="url" name="wp_fitness_posttype_linkedinurl" id="wp_fitness_posttype_linkedinurl" value="<?php echo esc_url( $linkdenurl ); ?>" />
					</td>
				</tr>
				<tr id="meta-5">
					<td class="left">
						<?php esc_html_e( 'Designation', 'wp-fitness-posttype' )?>
					</td>
					<td class="left" >
						<input type="text" name="wp_fitness_posttype_tariner_desig" id="wp_fitness_posttype_tariner_desig" value="<?php echo esc_attr( $desig ); ?>" />
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<?php
}

/* Saves the custom meta input */
function wp_fitness_posttype_bn_meta_save_trainer( $post_id ) {

	if (!isset($_POST['wp_fitness_posttype_trainer_meta_nonce']) || !wp_verify_nonce($_POST['wp_fitness_posttype_trainer_meta_nonce'], basename(__FILE__))) {
		return;
	}

	// Save facebookurl
	if( isset( $_POST[ 'wp_fitness_posttype_facebookurl' ] ) ) {
		update_post_meta( $post_id, 'wp_fitness_posttype_facebookurl', esc_url_raw($_POST[ 'wp_fitness_posttype_facebookurl']) );
	}

	// Save linkdenurl
	if( isset( $_POST[ 'wp_fitness_posttype_linkedinurl' ] ) ) {
		update_post_meta( $post_id, 'wp_fitness_posttype_linkedinurl', esc_url_raw($_POST[ 'wp_fitness_posttype_linkedinurl']) );
	}

	if( isset( $_POST[ 'wp_fitness_posttype_twitterurl' ] ) ) {
		update_post_meta( $post_id, 'wp_fitness_posttype_twitterurl', esc_url_raw($_POST[ 'wp_fitness_posttype_twitterurl']) );
	}

	// Save googleplusurl
	if( isset( $_POST[ 'wp_fitness_posttype_googleplusurl' ] ) ) {
		update_post_meta( $post_id, 'wp_fitness_posttype_googleplusurl', esc_url_raw($_POST[ 'wp_fitness_posttype_googleplusurl']) );
	}

	// Save desig.
	if( isset( $_POST[ 'wp_fitness_posttype_tariner_desig' ] ) ) {
		update_post_meta( $post_id, 'wp_fitness_posttype_tariner_desig', $_POST[ 'wp_fitness_posttype_tariner_desig']);
	}

}

add_action( 'save_post', 'wp_fitness_posttype_bn_meta_save_trainer' );

/* Trainer shortcode */
function wp_fitness_posttype_trainers_func( $atts ) {
	$Trainers = '';
	$Trainers = '<div class="row">';
	$query = new WP_Query( array( 'post_type' => 'our_trainers') );

	if ( $query->have_posts() ) :

		$k=1;

    	$new = new WP_Query('post_type=our_trainers');

    	while ($new->have_posts()) : $new->the_post();

	    	$content = get_the_content();

			$post_id = get_the_ID();

			$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );

		  	$facebookurl= get_post_meta($post_id,'wp_fitness_posttype_facebookurl',true);

		  	$linkedin=get_post_meta($post_id,'wp_fitness_posttype_linkedinurl',true);

			$twitter=get_post_meta($post_id,'wp_fitness_posttype_twitterurl',true);

			$googleplus=get_post_meta($post_id,'wp_fitness_posttype_googleplusurl',true);

			$desig= get_post_meta($post_id,'wp_fitness_posttype_tariner_desig',true);


			if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }

			else { $thumb_url = esc_url(get_template_directory_uri().'/images/people-blank.jpg'); }

			$Trainers .= '<div class="col-md-4 trainer_single_post">';
	    	$Trainers .= '<div class="page-trainer-box">
	    		<div class="complete_box">
	    		    <div class="colimage-box">';
			$Trainers.='<img src="'.esc_url($thumb_url).'" alt="" />
		                </div>
						<div class="trainer_single_post_content">
							<div class="trainer-name"><a href="'.get_permalink().'">'.get_the_title().'</a>

							<div class="trainer-designation">'.esc_html($desig).'</div></div>
							<div class="content-wp">'.esc_html($content).'</div>
		                    <div class="about-socialbox">';
		                    	if($facebookurl != ''){
									$Trainers.='<a href="'.esc_url($facebookurl).'"><i class="fab fa-facebook-f align-middle " aria-hidden="true"></i></a>';
								}if($twitter != ''){
									$Trainers.='<a href="'.esc_url($twitter).'"><i class="fab fa-twitter align-middle" aria-hidden="true"></i></a>';
								}if($googleplus != ''){
									$Trainers.='<a href="'.esc_url($googleplus).'"><i class="fab fa-google-plus-g align-middle" aria-hidden="true"></i></a>';
								}if($linkedin != ''){
									$Trainers.='<a href="'.esc_url($linkedin).'"><i class="fab fa-linkedin-in align-middle" aria-hidden="true"></i></a>';
								}
							$Trainers.='</div>
				        </div>
				        <div class="clear"></div>
				      </div>
				      <div class="clear"></div>
				    </div>
		            <div class="clear"></div>
				</div>';
			    if($k%2 == 0){
			    	$Trainers.= '<div class="clear"></div>';
			    }
      		$k++;
		endwhile;
		else :
			$Trainers = '<h2 class="center">'.esc_html__('Post Not Found','wp-fitness-posttype').'</h2>';
		endif;
		$Trainers .= '</div>';
		return $Trainers;
}

add_shortcode( 'wp-team', 'wp_fitness_posttype_trainers_func' );

add_action('admin_menu', 'wp_fitness_posttype_bn_testimonial_meta_box');

/* Adds a meta box for Designation */
function wp_fitness_posttype_bn_testimonial_meta_box() {
	add_meta_box( 'wp-fitness-posttype-testimonial-meta', __( 'Enter Designation', 'wp-fitness-posttype' ), 'wp_fitness_posttype_bn_testimonial_meta_callback', 'testimonials', 'normal', 'high' );
}

/* Adds a meta box for custom post */
function wp_fitness_posttype_bn_testimonial_meta_callback( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'wp_fitness_posttype_testimonial_meta_nonce' );
	$desigstory = get_post_meta( $post->ID, 'wp_fitness_posttype_desigstory', true );
	?>
	<div id="postcustomstuff">
		<table id="list">
			<tbody id="the-list" data-wp-lists="list:meta">
				<tr id="meta-1">
					<td class="left">
						<?php esc_html_e( 'Designation', 'wp-fitness-posttype' )?>
					</td>
					<td class="left" >
						<input type="text" name="wp_fitness_posttype_desigstory" id="wp_fitness_posttype_desigstory" value="<?php echo esc_attr( $desigstory ); ?>" />
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<?php
}

/* Saves the custom Designation meta input */
function wp_fitness_posttype_bn_metadesig_save( $post_id ) {
	if (!isset($_POST['wp_fitness_posttype_testimonial_meta_nonce']) || !wp_verify_nonce($_POST['wp_fitness_posttype_testimonial_meta_nonce'], basename(__FILE__))) {
		return;
	}

	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	// Save desig.
	if( isset( $_POST[ 'wp_fitness_posttype_desigstory' ] ) ) {
		update_post_meta( $post_id, 'wp_fitness_posttype_desigstory', sanitize_text_field($_POST[ 'wp_fitness_posttype_desigstory']) );
	}

}

add_action( 'save_post', 'wp_fitness_posttype_bn_metadesig_save' );

/* Testimonials shorthcode */
function wp_fitness_posttype_team_func( $atts ) {
	$testimonial = '';
	$testimonial = '<div class="row">';
	$query = new WP_Query( array( 'post_type' => 'testimonials') );

    if ( $query->have_posts() ) :

	$k=1;
	$new = new WP_Query('post_type=testimonials');

	while ($new->have_posts()) : $new->the_post();
    
    	$excerpt = wp_trim_words(get_the_excerpt(),10);
      	$post_id = get_the_ID();

    	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), '' );

        if(has_post_thumbnail()) { $thumb_url = $thumb['0']; } else { $thumb_url = get_template_directory_uri(); }

      	$desigstory= get_post_meta($post_id,'wp_fitness_posttype_desigstory',true);

    	$testimonial .= '
			<div id="clients" class="col-md-4 col-sm-4 testimonialwrapper-box">
				<div class="stories-mainbox">
					<img class="stories-image" src="'.esc_url($thumb_url).'" alt="" />
				
					<h5><a href="'.get_permalink().'">'.get_the_title().'</a></h5>
					<h6 class="sh-desig m-0">'.esc_html($desigstory).'</h6>

	                <div class="clearfix"></div>
				</div>
			</div>';
		if($k%3 == 0){
			$testimonial.= '<div class="clearfix"></div>';
		}
      $k++;
  endwhile;
  else :
  	$testimonial = '<h2 class="center">'.esc_html__('Post Not Found','wp-fitness-posttype').'</h2>';
  endif;
  $testimonial .= '</div>';
  return $testimonial;
}

add_shortcode( 'wp-testimonial', 'wp_fitness_posttype_team_func' );
