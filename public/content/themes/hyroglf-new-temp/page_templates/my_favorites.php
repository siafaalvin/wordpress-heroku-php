<?php
/**
 * Template Name: My favorites
 *
 * @package WordPress
 * @subpackage project name
 */

get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <div class="content_wrapper hdtopmargin">
                <div class="content">
                    <h2><?php the_title(); ?></h2><?php
                    wpfp_list_favorite_posts();?>
                </div>
            </div>   
        </div>
    </div> 
<?php endwhile; else: endif; ?>
<?php get_footer(); ?>