<?php
/**
 * Template Name: Home Page
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
            <div class="wiki_category">
            	<ul><?php
					if( $cat_left_arr ) {
						foreach( $cat_left_arr as $term) {
							$term_image_id = get_option('category_'.$term->term_id.'_post_category_image');
							$image = wp_get_attachment_image_src ( $term_image_id, '150_150_img' ); ?>
                            <li class="pro_list_image <?php echo $term->slug; ?>" data-value="<?php echo $term->slug; ?>" >
                                <div class="post_cat_img">
                                    <img src="<?php echo $image[0]; ?>" width="<?php echo $image[1]; ?>" height="<?php echo $image[2]; ?>" alt="<?php echo $term->name; ?>" />
                                </div>
                            <span class="list_cat_name"><?php echo $term->name; ?></span>
                            </li><?php
						}
					} ?>
                </ul>
            </div>
        </div>

        <div class="wiki_content wiki_center_section">
        	<?php header_section(); ?>
            <div class="home_content_section"><?php
                $args = array(
                            'post_type' => 'post',
                            'posts_per_page' => 4
                            );

                $loop = new WP_Query( $args );
                $total_pages = $loop->max_num_pages;
                if ($loop->have_posts()) {
                    while ( $loop->have_posts() ) : $loop->the_post(); global $post; ?>
                        <div class="post_content post-<?php echo $post->ID; ?>"><?php
                            $content_data = get_post_meta($post->ID);
                            if($content_data['post_ref_link_favicon'][0]){?>
                                <img src="<?php echo $content_data['post_ref_link_favicon'][0]; ?>"><?php // favicon image from referencce link
                            } ?>

                            <div class="post_content_title">
                                <h3 id="post-<?php echo $post->ID; ?>"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>

								<?php custom_get_post_category($post->ID); ?>

                            </div>

                            <p>Last modified: <?php the_modified_time('n/j/y'); ?> <?php the_modified_time('g:i a'); ?></p>

                            <div class="post_action_links"><?php
                                if($content_data['reference_link'][0] || $content_data['refernce_link_home_page_title'][0]){?>
                                    <a href="<?php echo $content_data['reference_link'][0]; ?>" class="source_link" target="_blank">
                                        <?php echo $content_data['refernce_link_home_page_title'][0]; ?>
                                    </a><?php
                                } ?>
                            </div><?php
                        	if( get_the_content( $post->ID ) ) { ?>
                            	<div class="post_add_edit_section">
                                	<a href="javascript:void(0);" class="pop_edit_tex post_edit_btn_<?php echo $post->ID; ?>" data-id="<?php echo $post->ID; ?>" onclick="open_popup(<?php echo $post->ID; ?>);" style="display:none;">

                                        <?php get_image('pencil_sign.png', 35, 35);?>

                                    </a>
                        			<a href="javascript:void(0);" class="view_list_post view-post-<?php echo $post->ID; ?>" onclick="view_content(<?php echo $post->ID; ?>);">
                                    <?php //get_image('plus_sign.png', 35, 35);?>
																		<i class="fa fa-plus fa-2x"></i>
                                    </a>
                                    <input type="hidden" name="post_edit_action[]" id="post_edit_action_<?php echo $post->ID; ?>" value="View" />
                                </div>

                                <div class="entry list_post_content content-<?php echo $post->ID; ?>" style="display:none;">
                                    <div class="post_content_section post_content-<?php echo $post->ID; ?>">
                                        <div class="post_content">
                                            <?php the_content(); ?>
                                        </div>

                                        <?php custom_get_post_tags($post->ID); ?>

                                    </div>
                                </div><?php
							} ?>
                        </div><?php
                    endwhile; wp_reset_query();
                } ?>

                <?php hidden_inputs($total_pages); // Don't remove this function ?>

            </div>
        </div>

        <div class="scroll_post_loader" style="display:none;">
            <?php get_image('loader.gif', '', '', 'Loader');?>
        </div>

        <div class="wiki_right_section">
            <div class="wiki_category">
            	<ul><?php
					if( $cat_right_arr ) { ?>
                        <li class="pro_list_image" data-value="recent_post">
                            <div class="post_cat_img">
                                <?php get_image('recently_added_edited.jpg', '150', '150', 'Recently Added Edited');?>
                            </div>
                            <span>Recently Added/Edited!</span>
                        </li><?php
						foreach( $cat_right_arr as $term) {
                        	$term_image_id = get_option('category_'.$term->term_id.'_post_category_image');
							$image = wp_get_attachment_image_src ( $term_image_id, '150_150_img' ); ?>
                            <li class="pro_list_image <?php echo $term->slug; ?>" data-value="<?php echo $term->slug; ?>">
                                <div class="post_cat_img">
                                    <img src="<?php echo $image[0]; ?>" width="<?php echo $image[1]; ?>" height="<?php echo $image[2]; ?>" alt="<?php echo $term->name; ?>" />
                                </div>
                            	<span class="list_cat_name"><?php echo $term->name; ?></span>
                            </li><?php
						}
					} ?>
                </ul>
            </div>
        </div>
	</div><!-- This div open in header -->

    <?php popup_wp_editor_content(); // Don't remove this function ?>

    <?php popup_category_content(); // Don't remove this function ?>

<?php endwhile; else: endif; ?>
<?php get_footer(); ?>
