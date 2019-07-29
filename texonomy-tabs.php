<?php
/*
 * Plugin Name: Texonomy Tabs
 * Author: Manoj Samudre
 * version: 1.0.0
 * Description: This is texomony tab plugin with mobile view set to accordians
 */

/**
 * Create a class first
 */
class WP_TexonomyTabs
{
	/**
 	 * Call to construct
 	 */
	function __construct()
	{
		add_shortcode('WP_TexonomyTab', array($this, 'shortcode'));
		add_action('wp_enqueue_scripts', array($this, 'flat_ui_kit'));

		// Add the fields, using our callback function  
		// if you have other taxonomy name, replace category with the name of your taxonomy. ex: book_add_form_fields, book_edit_form_fields
		add_action('category_add_form_fields', array($this, 'wcr_category_fields'), 10, 2);
		add_action('category_edit_form_fields', array($this, 'wcr_category_fields'), 10, 2);

		// Save the fields values, using our callback function
		// if you have other taxonomy name, replace category with the name of your taxonomy. ex: edited_book, create_book
		add_action('edited_category', array($this, 'wcr_save_category_fields'), 10, 2);
		add_action('create_category', array($this, 'wcr_save_category_fields'), 10, 2);

		//Add Specific body class
		add_filter( 'body_class', array($this, 'wp_body_classes') );
	}

	function get_texonomy() {
		//$catquery = new WP_Query( 'cat=1&posts_per_page=6' );  // Pass the taxonomy ID here-> cat=ID
	
		$categories = get_categories( array(
			    'orderby' => 'name',
			    'order'   => 'ASC'
			) );
		?>

        <div class="container-fluid">

        	<!----- Vertical Tabs --------->
        	
		    <div class="vertical-tabs"> 
		    	<?php if($this->isMobile()){ ?>
					<div class="panel-group" id="accordion">
				  		<?php
					      	$i=0;
					      	$AllCat_ID = array();
				      	?>
				        <?php foreach($categories as $cat) { ?>	
				        	<div class="panel panel-default br-3 ">		     
					        	<div class="cpanel-heading pad-4 brand-white-bg br-3 gap-y-3">
					        		<h4 class="panel-title">
							    	<a class="accordion-toggle gray-dark" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $cat->term_id;?>">
							    		<?php $icon_URL = get_term_meta($cat->term_id, 'category_icon', true); ?>
							    		<img src="<?php echo $icon_URL;?>" alt="">
							    		<span class="font-bold"><?php echo $cat->cat_name; ?></span>
							    	</a>
							    	</h4>
							    </div>
							    <?php $category_feature_img = get_term_meta($cat->term_id, 'category_feature_img', true); ?>
								
							    <div id="collapse<?php echo $cat->term_id; ?>" class="panel-collapse collapse <?php echo ($i==0)?'in':''; ?> gap-y-3">
							    	<div class="arrow-up"></div>	
						      		<div class="cpanel-body">
										<?php $wp_posts = new WP_Query( 'cat='.$cat->term_id.'&posts_per_page=6' ); ?>
						        		<div id="myCarousel" class="carousel slide" data-ride="carousel">
										    <!-- Indicators -->
										    <ol class="carousel-indicators">
										      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
										      <li data-target="#myCarousel" data-slide-to="1"></li>
										      <li data-target="#myCarousel" data-slide-to="2"></li>
										    </ol>
											    <!-- Wrapper for slides -->
											<div class="sv-tab-panel">
											    <div class="carousel-inner brand-white">
													<?php $k=0; ?>	    	
										        	<?php while($wp_posts->have_posts()) : $wp_posts->the_post(); ?>
										        		<?php $feat_image_url = wp_get_attachment_url( get_post_thumbnail_id() ); ?>
											        		<div class="item <?php echo ($k==0)?'active':'';?>">
														      	<?php //echo $feat_image_url;?>
														      	<h5 class=""><span class="br-3"><?php echo the_title();?></span></h5>
														      	<h3 class="font-size-largest"><?php echo the_content();?></h3>
														      	<h6>
														      		<a href="<?php echo get_permalink( $id )?>" class="brand-white">Read More
														      			<span>&#8594;</span>
														      		</a>
														      	</h6> 
														    </div>
												    <?php $k++; ?>
											   		<?php endwhile; ?>	
											   	</div>
											</div>
										</div>
										
					        		</div> 
					       		</div>
					       	</div>	 
							<?php $i++; } ?>
					</div>
					<?php } else {	?>
				    <ul class="nav nav-tabs gray-white-bg" role="tablist">
				      	<?php
					      	$i=0;
					      	$AllCat_ID = array();
				      	 ?>
				        <?php foreach($categories as $cat) { ?>			     
				        	<li class="nav-item gap-3 brand-white-bg br-3">
						    	<a class="nav-link gray-dark <?php echo ($i==0)?'active':''; ?>" data-toggle="tab" href="#pag<?php echo $cat->term_id;?>" role="tab" aria-controls="home">
						    		<?php $icon_URL = get_term_meta($cat->term_id, 'category_icon', true); ?>
						    		<img src="<?php echo $icon_URL;?>" alt="">
						    		<span class="font-bold"><?php echo $cat->cat_name; ?></span>
						    	</a>
						    </li>
						<?php

							$AllCat_ID[] =  $cat->term_id;
							$i++;
						 ?>
						<?php } ?>		      
				    </ul>

				    <!----- All Tabs Contents --------->			    
			      	<div class="tab-content">
		      	      	<?php  $J=0;  ?>
				        <?php foreach ($AllCat_ID as $catID) { ?>
				      	<div class="tab-pane <?php echo ($J==0)?'active':''; ?>" id="pag<?php echo $catID; ?>" role="tabpanel">
				        	
				        		<?php $wp_posts = new WP_Query( 'cat='.$catID.'&posts_per_page=6' ); ?>

				        		
				        		<div id="myCarousel" class="carousel slide" data-ride="carousel">
								    <!-- Indicators -->
								    <ol class="carousel-indicators">
								      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
								      <li data-target="#myCarousel" data-slide-to="1"></li>
								      <li data-target="#myCarousel" data-slide-to="2"></li>
								    </ol>
									    <!-- Wrapper for slides -->
									<div class="sv-tab-panel">
									    <div class="carousel-inner brand-white">
											<?php $k=0; ?>	    	
								        	<?php while($wp_posts->have_posts()) : $wp_posts->the_post(); ?>
								        		<?php $feat_image_url = wp_get_attachment_url( get_post_thumbnail_id() ); ?>
									        		<div class="item <?php echo ($k==0)?'active':'';?>">
												      	<?php //echo $feat_image_url;?>
												      	<h5 class=""><span class="br-3"><?php echo the_title();?></span></h5>
												      	<h3 class="font-size-largest"><?php echo the_content();?></h3> 
												      	<h6>
												      		<a href="<?php echo get_permalink( $id )?>" class="brand-white">Read More
												      			<span>&#8594;</span>
												      		</a>
												      	</h6> 
												      </div>
										    <?php $k++; ?>
									   		<?php endwhile; ?>	
									   	</div>
									</div>
								    <!-- Left and right controls -->
								<!--     <a class="left carousel-control" href="#myCarousel" data-slide="prev">
								      <span class="glyphicon glyphicon-chevron-left"></span>
								      <span class="sr-only">Previous</span>
								    </a>
								    <a class="right carousel-control" href="#myCarousel" data-slide="next">
								      <span class="glyphicon glyphicon-chevron-right"></span>
								      <span class="sr-only">Next</span>
								    </a> -->
								</div>
								<div class="cat-img">
									<?php $category_feature_img = get_term_meta($catID, 'category_feature_img', true); ?>
									<?php if(!empty($category_feature_img)){ ?>
										<img src="<?php echo $category_feature_img; ?>" alt=""/>
									<?php } else{ ?>
										<img src="https://www.rahisystems.com/wp-content/uploads/2016/01/aboutus.jpg" alt="Placeholder img" />
									<?php } ?>	
								</div>
				        	</div>  
					    <?php $J++; ?>
						<?php } ?>		          
			        </div>
		    	<?php } // Close End Desktop View ?>
			</div>
		</div>
		<?php
	}

	/**
 	 * Enques jquery and CSS Files.
 	 */
	function flat_ui_kit() {
		wp_enqueue_style('bootstrap-css', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css');
		wp_enqueue_style('custom-css', plugins_url('css/custom-style.css', __FILE__));
        wp_enqueue_script( 'jquery-script', 'https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js', array( 'jquery' ) );
        wp_enqueue_script( 'bootstrap-script', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js', array( 'jquery' ) );
        wp_enqueue_script( 'bootstrap-script', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js',  array( 'jquery' ) );
	}
	/**
 	 * Created the Shortcode 
 	 */
	function isMobile() {
	    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
	}

	function shortcode() {
		ob_start();		
		$this->get_texonomy();		
		return ob_get_clean();
	}

	/*====== Added Body Class Because of Applying the CSS on Particular Page ============*/
	function wp_body_classes( $c ) {
	    global $post;
	    if( isset($post->post_content) && has_shortcode( $post->post_content, 'WP_TexonomyTab' ) ) {
	        $c[] = 'wp-texonomytabs';
	    }
	    return $c;
	}
	/*==========================Texonomy Add Custom Two Fields ============================*/

	function wcr_category_fields($term) {
	    // we check the name of the action because we need to have different output
	    // if you have other taxonomy name, replace category with the name of your taxonomy. ex: book_add_form_fields, book_edit_form_fields
	    if (current_filter() == 'category_edit_form_fields') {
	        $category_icon = get_term_meta($term->term_id, 'category_icon', true);
	        $category_feature_img = get_term_meta($term->term_id, 'category_feature_img', true);
	        ?>
	        <tr class="form-field">
	            <th valign="top" scope="row"><label for="term_fields[category_icon]"><?php _e('Category Before Icon'); ?></label></th>
	            <td>
	                <input type="text" placeholder="htttp://" class="large-text" id="term_fields[category_icon]" name="term_fields[category_icon]" value="<?php if(!empty($category_icon)){ echo $category_icon; } ?>"><br/>
	                <span class="description"><?php _e('Please Image Icon URL'); ?></span>
	            </td>
	        </tr> 
	        <tr class="form-field">
	            <th valign="top" scope="row"><label for="term_fields[category_feature_img]"><?php _e('Category Feature Image'); ?></label></th>
	            <td>
	                <input type="text" placeholder="htttp://" class="large-text" id="term_fields[category_feature_img]" name="term_fields[category_feature_img]" value="<?php if(!empty($category_feature_img)){ echo $category_feature_img; } ?>"><br/>
	                <span class="description"><?php _e('Please Feature Image URL'); ?></span>
	            </td>
	        </tr>  
		<?php } elseif (current_filter() == 'category_add_form_fields') {
	        ?>
	        <div class="form-field">
	            <label for="term_fields[category_icon]"><?php _e('Category Before Icon'); ?></label>
	            <input type="text"  placeholder="htttp://" id="term_fields[category_icon]" name="term_fields[category_icon]" />
	            <p class="description"><?php _e('Please Image Icon URL'); ?></p>
	        </div>
	         <div class="form-field">
	            <label for="term_fields[category_feature_img]"><?php _e('Category Feature Image'); ?></label>
	            <input type="text" placeholder="htttp://" id="term_fields[category_feature_img]" name="term_fields[category_feature_img]" />
	            <p class="description"><?php _e('Please enter Feature Image URL'); ?></p>
	        </div>         
	    <?php
	    }
	}
	
	function wcr_save_category_fields($term_id) {
	    if (!isset($_POST['term_fields'])) {
	        return;
	    }

	    foreach ($_POST['term_fields'] as $key => $value) {
	        update_term_meta($term_id, $key, sanitize_text_field($value));
	    }
	}
	
}
new WP_TexonomyTabs;

?>