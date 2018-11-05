<?php
/**
 * Template Name: Login page
 *
 * @package WordPress
 * @subpackage project name
 */

get_header('sub'); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<?php header_section(); ?>
    <div class="content_wrapper hdtopmargin">
                <div class="content">
                    <h2><?php the_title(); ?></h2><?php
					if(is_user_logged_in()){
						$url = home_url();
						if( isset( $_GET['redirect_to'] ) ) {
							$url = urldecode($_GET['redirect_to']);
						} else {
							$url = home_url();
						} ?>
						<script>
                            window.location.href= '<?php echo $url; ?>';
                        </script><?php
					} else {
						the_content();
					}?>

                </div>
            </div>
        </div>
    </div>
<?php endwhile; else: endif; ?>
<?php get_footer(); ?>
