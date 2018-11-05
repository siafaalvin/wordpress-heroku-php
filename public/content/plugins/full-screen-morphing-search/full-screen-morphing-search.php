<?php
/**
* Plugin Name: Full Screen Morphing Search
* Plugin URI: http://tympanus.net/codrops/2014/11/04/simple-morphing-search/
* Version: 1.2.1
* Author: LebCit
* Author URI: https://lebcit.tk/
* Description: Effect for any WordPress search input that morphs into a fullscreen overlay.
*/

/**
* Register a new image size for the plugin.
*/

add_action( 'init', 'msp_thumb' );
function msp_thumb() {
    add_image_size( 'msp-thumb', 50, 50, true );
}

/**
 * Main plugin class.
 *
 * @since 1.1
 */
class Morphing_Search {

	/**
	* Stores information about the Plugin
	*
	* @since 1.1
	*/
	public $plugin = '';

	/**
	* Constructor
	*
	* Adds necessary action and filter hooks for the plugin
	*
	* @since 1.0
	*/
	function __construct() {

        // Setup Plugin Details
        $this->plugin = new stdClass;
        $this->plugin->name         = 'morphing-search';
        $this->plugin->folder       = plugin_dir_path( __FILE__ );
        $this->plugin->url          = plugin_dir_url( __FILE__ );

        // Add actions if we're not in the WordPress Administration to load CSS, JS and the Morphing Search HTML
        if ( ! is_admin() ) {
        	add_action( 'wp_head', array( $this, 'enqueue_css_js' ) );
        	add_action( 'wp_footer', array( $this, 'output_morphing_search' ) );
        }

	}

	/**
	* Loads the CSS and JS used for this plugin
	*
	* @since 1.1
	*/
	function enqueue_css_js() {

		// Load CSS
		wp_enqueue_style( $this->plugin->name, $this->plugin->url . 'assets/css/full-screen-morphing-search.css', array(), false );

		// Require WordPress jQuery
		wp_enqueue_script( 'jquery' );
                // Require jquery-ui-core and jquery-ui-autocomplete for autoco;pletition search !
                wp_enqueue_script( 'jquery-ui-core' );
                wp_enqueue_script( 'jquery-ui-autocomplete' );

		// Load Javascript
		wp_enqueue_script( $this->plugin->name, $this->plugin->url . 'assets/js/full-screen-morphing-search.js', array( 'jquery' ), '1.0', true );
    
	}

	/**
	* Outputs the HTML markup for our Full Screen Morphing Search
	* CSS hides this by default, and Javascript displays it when the user
	* interacts with any WordPress search field
	*
	* @since 1.1
	*/
	function output_morphing_search() {

		?>
				
            <div id="morphsearch" class="morphsearch">
                <span class="morphsearch-close"></span>
                <form role="search" class="morphsearch-form" method="get" action="<?php echo home_url( '/' ); ?>">
                        <input required type="search" class="morphsearch-input" name="s" placeholder="<?php echo esc_attr_x( 'Search...', 'placeholder' ) ?>" value="" />
                        <button class="morphsearch-submit" type="submit"></button>
                </form>
                        
                        
                <div class="morphsearch-content">
                    <div class="dummy-column">
                        <h2>Recent Posts</h2>
                        <?php
                        $args = array(
                            'post_type' => 'post',
                            'posts_per_page'=> '5',
                            'ignore_sticky_posts' => 1
                        );
                        $msprp = new WP_Query( $args ); 
                        while ($msprp -> have_posts()) : $msprp -> the_post(); ?>
                        <div class="dummy-media-object">
                            <a href="<?php echo the_permalink() ?>" title="<?php the_title_attribute(); ?>">
                                    <?php echo the_post_thumbnail( 'msp-thumb', array('class' => 'round')); ?>
                            </a>                                     
                            <h3><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h3>
                        </div>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                    <div class="dummy-column">
                        <h2>Top Categories</h2>
                        <?php                        
                        $cats = get_categories();
                        if (empty($cats)) {
                                return;
                        }
                        $tc_counts = $cat_links = array();
                        foreach ( (array) $cats as $cat ) {
                                $tc_counts[$cat->name] = $cat->count;
                                $cat_links[$cat->name] = get_category_link( $cat->term_id );
                        }
                        asort($tc_counts);
                        $tc_counts = array_reverse( $tc_counts, true );
                        $i = 0;
                        foreach ( $tc_counts as $cat => $tc_count ) {
                                $i++;
                                $cat_link = esc_url($cat_links[$cat]);
                                $cat = str_replace(' ', '&nbsp;', esc_html( $cat ));
                                if($i < 6){ ?>
                                    <div class="dummy-media-object">
                                        <?php 
                                            echo '<img src="' . plugins_url( 'assets/img/category.png', __FILE__ ) . '" > ';    
                                            print "<h3><a href=\"$cat_link\">$cat ($tc_count)</a></h3>";
                                        ?>
                                    </div>
                                <?php }
                        } ?>
                    </div>
                    <div class="dummy-column">
                        <h2>Top Tags</h2>
                        <?php                        
                        $tags = get_tags();
                        if (empty($tags)) {
                                return;
                        }
                        $tt_counts = $tag_links = array();
                        foreach ( (array) $tags as $tag ) {
                                $tt_counts[$tag->name] = $tag->count;
                                $tag_links[$tag->name] = get_tag_link( $tag->term_id );
                        }
                        asort($tt_counts);
                        $tt_counts = array_reverse( $tt_counts, true );
                        $i = 0;
                        foreach ( $tt_counts as $tag => $tt_count ) {
                                $i++;
                                $tag_link = esc_url($tag_links[$tag]);
                                $tag = str_replace(' ', '&nbsp;', esc_html( $tag ));
                                if($i < 6){ ?>
                                    <div class="dummy-media-object">
                                        <?php 
                                            echo '<img src="' . plugins_url( 'assets/img/tag.png', __FILE__ ) . '" > ';    
                                            print "<h3><a href=\"$tag_link\">$tag ($tt_count)</a></h3>";
                                        ?>
                                    </div>
                                <?php }
                        } ?>                      
                    </div>                            
                </div><!-- .morphsearch-content -->
            </div><!-- #morphsearch.morphsearch -->
		
		<?php
                $fsmsac = array(
                        'post_type' => array('post','page'),
                        'post_status' => 'publish',
                        'posts_per_page'   => -1 // all posts and pages
                );

                $posts = get_posts( $fsmsac );

                        if( $posts ) :
                                foreach( $posts as $k => $post ) {
                                        $source[$k]['ID'] = $post->ID;
                                        $source[$k]['label'] = $post->post_title; // The name of the post
                                        $source[$k]['permalink'] = get_permalink( $post->ID );
                                }

                ?>
                <script type="text/javascript">
                        jQuery(document).ready(function($){
                                var posts = <?php echo json_encode( array_values( $source ) ); ?>;

                                jQuery( 'input[name="s"]' ).autocomplete({
                                source: posts,
                                minLength: 2,
                                select: function(event, ui) {
                                    var permalink = ui.item.permalink; // Get permalink from the datasource

                                    window.location.replace(permalink);
                                }
                            });
                        });    
                </script>
                <?php
                endif;

	}

}

$full_screen_search = new Morphing_Search;