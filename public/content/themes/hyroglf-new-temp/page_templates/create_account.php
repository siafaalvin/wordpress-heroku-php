<?php
/**
 * Template Name: Create Account
 *
 * @package WordPress
 * @subpackage project name
 */

get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post();

	$terms = get_terms('category');
	$cat_left_arr = array();
	$cat_right_arr = array();
	if( $terms ) {
		$i = 0;
		foreach( $terms as $term ) {
			if( $i%2 == 0 ) {
				$cat_left_arr[] = $term;
			} else {
				$cat_right_arr[] = $term;
			}
			$i++;
		}
	} ?>
    
    <div class="wiki_left_section">
        <div class="wiki_single_category">
            <ul><?php
                if( $cat_left_arr ) {
                    foreach( $cat_left_arr as $term) {
                        $term_image_id = get_option('category_'.$term->term_id.'_post_category_image');
                        $image = wp_get_attachment_image_src ( $term_image_id, '200_150_img' ); ?>
                        <li class="pro_list_image <?php echo $term->slug; ?>" data-value="<?php echo $term->slug; ?>" >
                            <div class="post_cat_img">
                                <a href="<?php echo home_url('?term='.$term->slug);?>">
                                    <img src="<?php echo $image[0]; ?>" width="<?php echo $image[1]; ?>" height="<?php echo $image[2]; ?>" alt="<?php echo $term->name; ?>" />
                                </a>
                            </div>
                        <span class="list_cat_name"><?php echo $term->name; ?></span>
                        </li><?php
                    } ?>
                        <li class="pro_list_image <?php echo $term->slug; ?>" data-value="favorite_posts" >
                            <div class="post_cat_img">
                            <a href="<?php echo home_url('?term=my_favorites');?>">
                                <?php echo get_image('Favorites Icon.png', '167', '164', 'Favorite posts'); ?>
                            </a>
                            </div>
                        <span class="list_cat_name">MY FAVORITES</span>
                        </li><?php
                } ?>
            </ul>
        </div>
    </div>
    
    <?php header_section(); ?>
    
    <div class="content_wrapper hdtopmargin">        
        <div class="content">
        <h1><?php the_title();?></h1><?php
            wp_nav_menu( array( 'theme_location' => 'registration', 'container' => '', 'menu_id' => '', 'menu_class'=> '') );
         ?>
        </div>
    </div>
    
    <div class="wiki_content wiki_center_section" style="display:none;">
        <div class="home_content_section"></div>
    </div>
    
    <div class="wiki_right_section">
        <div class="wiki_single_category">
            <ul><?php
            if( $cat_right_arr ) { ?>
                <li class="pro_list_image" data-value="recent_post">
                    <div class="post_cat_img">
                        <a href="<?php echo home_url('');?>">
                            <?php get_image('recently_added_edited.jpg', '150', '150', 'Recently Added Edited');?>
                        </a>
                    </div>
                    <span>Recently Added/Edited</span>
                </li><?php
                foreach( $cat_right_arr as $term) {
                    $term_image_id = get_option('category_'.$term->term_id.'_post_category_image');
                    $image = wp_get_attachment_image_src ( $term_image_id, '200_150_img' ); ?>
                    <li class="pro_list_image <?php echo $term->slug; ?>" data-value="<?php echo $term->slug; ?>">
                        <div class="post_cat_img">
                            <a href="<?php echo home_url('?term='.$term->slug);?>">
                                <img src="<?php echo $image[0]; ?>" width="<?php echo $image[1]; ?>" height="<?php echo $image[2]; ?>" alt="<?php echo $term->name; ?>" />
                            </a>
                        </div>
                        <span class="list_cat_name"><?php echo $term->name; ?></span>
                    </li><?php
                } ?>
                    <li class="pro_list_image <?php echo $term->slug; ?>" data-value="my_posts_and_votes">
                        <div class="post_cat_img">
                            <a href="<?php echo home_url('?term=my_posts');?>">
                                <?php echo get_image('pencil_sign.png', '167', '164', 'my posts + votes'); ?>
                            </a>
                        </div>
                    <span class="list_cat_name">MY POSTS + VOTES</span>
                    </li><?php
            } ?>
            </ul>
        </div>
    </div>
    
</div> 
<?php endwhile; else: endif; ?>
<?php get_footer(); ?>