<?php
/**
 * Template Name: Editor page
 *
 * @package WordPress
 * @subpackage project name
 */

/* header section */ get_header(); ?>
<?php
/* custom left sidebar */ category_left_sidebar_for_page('page'); ?>
<?php
/* Header section */ header_section();

	if (have_posts()) : while (have_posts()) : the_post();?>
    <div class="content_wrapper hdtopmargin edit_post_section" style="display:none"><?php
		echo fn_get_edit_post_content();?>
    </div>

        <div class="wiki_content wiki_center_section" style="display:none;">
            <div class="home_content_section"></div>
        </div>

	<?php endwhile;
		endif; ?>

    <?php category_right_sidebar_for_page('page'); ?>

    </div> <!-- this div open in header -->

<?php get_footer(); ?>
