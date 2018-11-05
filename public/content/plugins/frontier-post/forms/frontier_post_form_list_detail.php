<?php 



// list of users post based on current theme settings

global $fps_access_check_msg;
//Reset access message
$fps_access_check_msg = "";
			
$concat= get_option("permalink_structure")?"?":"&";    
//set the permalink for the page itself
$frontier_permalink = get_permalink();

$tmp_status_list = get_post_statuses( );

// Add future to list
$tmp_status_list['future'] = __("Future", "frontier-post");


$tmp_info_separator = " | ";

//Display before text from shortcode
if ( strlen($fpost_sc_parms['frontier_list_text_before']) > 1 )
	echo '<div id="frontier_list_text_before">'.$frontier_list_text_before.'</div>';

// Dummy translation of ago for human readable time
$crap = __("ago", "frontier-post");


if (strlen(trim($fpost_sc_parms['frontier_add_link_text']))>0)
	$tmp_add_text = $fpost_sc_parms['frontier_add_link_text'];
else
	$tmp_add_text = __("Create New", "frontier-post")." ".fp_get_posttype_label_singular($fpost_sc_parms['frontier_add_post_type']);
		


//Display message
frontier_post_output_msg();



if (frontier_can_add($fpost_sc_parms['frontier_add_post_type']) && !fp_get_option_bool("fps_hide_add_on_list"))
	{
	?>
	<fieldset class="frontier-new-menu">
		<a id="frontier-post-add-new-link" href='<?php echo frontier_post_add_link($tmp_p_id) ?>'><?php echo $tmp_add_text; ?></a>
	</fieldset>
	<?php
	
	} // if can_add
else
	{
	if ( current_user_can("manage_options") && strlen(trim($fps_access_check_msg)) > 0)
		{
		echo '<div id="frontier-post-posttype-warning">';
		echo $fps_access_check_msg;
		echo ' - '.__("This message is only shown to admins", "frontier-post").'<br><br>';		
		echo '</div>';
		}
	
	}


//*******************************************************************************************************
//  Quickpost
//*******************************************************************************************************

frontier_quickpost($fpost_sc_parms);

	

if( $user_posts->found_posts > 0 )
	{
	echo '<div id="frontier-post-list_form">';
	while ($user_posts->have_posts()) 
		{
		$user_posts->the_post();
		
		// only display private posts if author is current users
		if ($post->post_status == "private" && $current_user->ID != $post->post_author )
			continue;
		
		
		$tmp_status_class="frontier-post-list-status-".$post->post_status;
		
		?>
			
			<fieldset class="frontier-new-list <?php echo $tmp_status_class; ?>">
			
			<table class="frontier-new-list">
				
				
				<?php
				// show status if pending or draft
				if ($post->post_status == "pending" || $post->post_status == "draft" || $post->post_status == "future")
					echo '<tr><td class="frontier-new-list '.$tmp_status_class.'" id="frontier-post-new-list-status" colspan=2>'.__("Status", "frontier-post").': '.(array_key_exists($post->post_status,$tmp_status_list) ? $tmp_status_list[$post->post_status] : $post->post_status).'</td></tr>';
				?>
				
				
				
				<tr>
				<td class="frontier-new-list" id="frontier-post-new-list-title">
					<?php the_post_thumbnail( array(50,50), array('class' => 'frontier-post-list-thumbnail') ); ?>
					<a id="frontier-post-new-list-title-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</td>
				</tr>
				
				<?php
				if ($fp_list_form == "full_post")
					{
					$tmp_content = apply_filters( 'the_content', $post->post_content );
					$tmp_content = str_replace( ']]>', ']]&gt;', $tmp_content );
					echo '<tr><td class="frontier-new-list" id="frontier-post-new-list-excerpt" colspan=2>';
					echo $tmp_content;
					echo '</td></tr>';
					}
				if ($fp_list_form == "excerpt")
					{
				//$tmp_content = get_the_excerpt();
					$tmp_content = $post->post_excerpt;
					if (strlen(trim($tmp_content)) == 0)
						$tmp_content = wp_trim_words($post->post_content);
					
					$tmp_content =  apply_filters( 'the_content', $tmp_content);
					
					//$tmp_content =  apply_filters( 'the_content', get_the_excerpt() );
					
					echo '<tr><td class="frontier-new-list" id="frontier-post-new-list-excerpt" colspan=2>';
					
					//the_excerpt();
					echo $tmp_content;
					echo '</td></tr>';
					echo '</td></tr>';
					}
				?>
				
				<tr>
				<td class="frontier-new-list" id="frontier-post-new-list-info" colspan=2 >
					
					<?php
					/*
					echo frontier_post_edit_link($post, $fp_show_icons, $frontier_permalink);
					echo frontier_post_approve_link($post, $fp_show_icons, $frontier_permalink);
					echo frontier_post_delete_link($post, $fp_show_icons, $frontier_permalink);
					echo frontier_post_preview_link($post, $fp_show_icons, $frontier_permalink);
					*/
					echo frontier_post_display_links($post, $fp_show_icons, $frontier_permalink);
					
					
					echo __("Status", "frontier-post").': '.( isset($tmp_status_list[$post->post_status]) ? $tmp_status_list[$post->post_status] : $post->post_status );
					if ($post->post_status === 'future' )
						echo " (".$post->post_date.")";
						
					echo $tmp_info_separator;
					echo __("Author", "frontier-post").': ';
					the_author();
					
					// Show word count
					echo $tmp_info_separator; 
					echo __("Words", "frontier-post").": ".str_word_count( strip_tags( $post->post_content ) );
					
					// show publish date
					echo $tmp_info_separator; 
					if ($post->post_status === 'publish' )
						{
						printf( _x( '%s ago', '%s = human-readable time difference', 'frontier-post' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) ); 
						echo $tmp_info_separator; 
						}
					
					$postlink = esc_url( get_permalink() )."#comments";
					echo '<a class="frontier-post-comment-link" id="frontier-query-comment-link" href="'.$postlink.'">'.frontier_get_icon('comments2').'&nbsp;'.intval($post->comment_count).'</a>';
					
					/*
					echo frontier_get_icon('comments2').'&nbsp;'.intval($post->comment_count);
					
					
					echo $tmp_info_separator; 
					echo __("Categories", "frontier-post").': ';
					the_category(', '); 
					echo $tmp_info_separator; 
					echo __("Tags", "frontier-post").': ';
					the_tags(', '); 
					*/
					
					// get taxonomy information
					echo fp_get_tax_values($post->ID, $tmp_info_separator); 
					
					
					?>
					
					
				</td>
				</tr>
			</table>	
			</fieldset>
			
		
		
		<?php
		//echo '<hr>';
		
		} // end while have posts 
	
	
	
	if ( fp_bool($fpost_sc_parms['frontier_pagination']) )
		{
		$pagination = paginate_links( 
			array(
				'base' => add_query_arg( 'pagenum', '%#%'),
				'format' => '',
				'prev_text' => __( '&laquo;', 'frontier-post' ),
				'next_text' => __( '&raquo;', 'frontier-post' ),
				'total' => $user_posts->max_num_pages,
				'current' => $pagenum,
				'add_args' => false  //due to wp 4.1 bug (trac ticket 30831)
				) 
			);

		//if ( $pagination ) 
		//	echo $pagination;
		if ( $pagination ) 
			{
			echo '<br><div id="frontier-post-pagination">'.$pagination.'</div>';
			}
		
		
		}
	if ( !fp_bool($fpost_sc_parms['frontier_list_all_posts']) )
		echo "</br>".__("Number of posts already created by you: ", "frontier-post").$user_posts->found_posts."</br>";
	
	echo '</div>';
	} // end if have posts
else
	{
		echo "</br><center>";
		_e('Sorry, you do not have any posts (yet)', 'frontier-post');
		echo "</center><br></br>";
	} // end post count
	
//Re-instate $post for the page
wp_reset_postdata();

?>