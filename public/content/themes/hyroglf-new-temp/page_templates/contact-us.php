<?php
/**
 * Template Name: Home Page
 *
 * @package WordPress
 * @subpackage project name
 */

get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    
	<?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>