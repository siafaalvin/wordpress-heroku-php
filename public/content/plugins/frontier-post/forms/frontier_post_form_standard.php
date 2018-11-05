
<?php


//Display message
frontier_post_output_msg();


if ( strlen($fpost_sc_parms['frontier_edit_text_before']) > 1 )
	echo '<div id="frontier_edit_text_before">'.$fpost_sc_parms['frontier_edit_text_before'].'</div>';



//***************************************************************************************
//* Start form
//***************************************************************************************?>
<div class="required_field">* Required field</div>
<div class="source_required_msg"></div><?php



	echo '<div class="frontier_post_form" ng-controller ="authEditCtrl"> ';
	echo '<form action="'.$frontier_permalink.'" method="post" name="frontier_post" id="frontier_post" enctype="multipart/form-data" >';

	// do not remove this include, as it holds the hidden fields necessary for the logic to work
	include(FRONTIER_POST_DIR."/forms/frontier_post_form_header.php");

	wp_nonce_field( 'frontier_add_edit_post', 'frontier_add_edit_post_'.$thispost->ID );

?>
	<table class="frontier-post-taxonomies"><tbody>
        <tr>
        <td class="frontier_no_border"><?php
			//**********************************************************************************
			//* Taxonomies
			//**********************************************************************************

			//$tax_list = array("category", "group", "article-type");
			$tax_list 			= $fpost_sc_parms['frontier_custom_tax'];
			$tax_layout_list 	= fp_get_tax_layout($fpost_sc_parms['frontier_custom_tax'], $fpost_sc_parms['frontier_custom_tax_layout']);

        	$cat_arr = array();
			foreach ( $tax_layout_list as $tmp_tax_name => $tmp_tax_layout) {
				if ($tmp_tax_layout != "hide")
					{
					// Cats_selected is set from script, but only for category
					if ($tmp_tax_name != 'category')
						$cats_selected = wp_get_post_terms($thispost->ID, $tmp_tax_name, array("fields" => "ids"));

						$tag_line = '';
						if( $tmp_tax_name == 'category' ) {
							$tag_line = 'What does your post fall under?';
						}

					echo '<fieldset class="frontier_post_fieldset_tax frontier_post_fieldset_tax_'.$tmp_tax_name.'">';
					//echo '<legend class="frontier_post_legend_tax" >'.fp_get_tax_label($tmp_tax_name).'('.$tag_line.')*</legend>';
					echo '<label>'.fp_get_tax_label($tmp_tax_name).' ('.$tag_line.') <span class = "text-red">*</span></label>';

					echo '<div class="cat_select_action">';
					frontier_tax_input($thispost->ID, $tmp_tax_name, $tmp_tax_layout, $cats_selected, $fpost_sc_parms, $tax_form_lists[$tmp_tax_name]);
					echo '</div>';
					if( $tmp_tax_name == 'category' ) {
						$cat_terms = get_the_terms($thispost->ID, 'category');
						//echo '<pre>'; print_r($cat_terms);
						if(!empty($cat_terms)){
							foreach( $cat_terms as $terms ) {
								$cat_arr[] = $terms->slug;
							}
						}
						echo '<div class="add_new_select_category">';
							if( isset( $_GET['task'] ) && $_GET['task'] == 'new' ) {
								echo '<ul class="post_categories_list"></ul>';
							} else {
								custom_get_post_category($thispost->ID, 'single_post_edit');
							}
						echo '</div>';
					}
					echo '</fieldset>';
					echo PHP_EOL;
					}
				}

			//****************************************************************************************************
			// tags
			//****************************************************************************************************

			if ( current_user_can( 'frontier_post_tags_edit' ) ) {
				echo '<fieldset class="frontier_post_fieldset_tax frontier_post_fieldset_tax_tag">';
				//echo '<legend>'.__("Tags", "frontier-post").'</legend>';
				echo '<label>'.__("Tags", "frontier-post").'</label>';
				/*for ($i=0; $i<$fp_tag_count; $i++)
					{
					$tmp_tag = isset($taglist[$i]) ? fp_tag_transform($taglist[$i]) : "";
					//$tmp_tag = array_key_exists($i, $taglist) ? fp_tag_transform($taglist[$i]) : "";
					echo '<input placeholder="'.__("Enter tag here", "frontier-post").'" type="text" value="'.$tmp_tag.'" name="user_post_tag'.$i.'" id="user_post_tag"><br>';
					}*/ ?>
					<!-- Custom code -->
					<div class="new_tag_section">
						<!--<h3>Tags</h3>-->
						<div class="tag_text_box">
						<input id="post_tag_text" type="text" value="" name="post_tag_text">
						<!--<a class="add_tag_trigger_action" onclick="add_tag('new_post');" href="javascript:void(0);">Add Tag</a>-->
						<!--<p id="new-tag-post_tag-desc" class="howto">Press enter after adding a tag</p>-->
                        <p id="new-tag-post_tag-desc" class="howto">Seperate tags with a space</p>
						</div>

						<div class="insert_tags"><?php
							for ($i=0; $i<$fp_tag_count; $i++) {
								$tmp_tag = isset($taglist[$i]) ? fp_tag_transform($taglist[$i]) : "";
								if($tmp_tag) {?>
								<div class="tagchecklist">
									<span>
									<a id="post_tag-check-num-0" class="post_tag-check post_tag-check-0" onclick="remove_tag_in_editor(this);">X</a>
									<?php echo $tmp_tag; ?>
									</span>
									<input id="post_tags_0" type="hidden" value="<?php echo $tmp_tag; ?>" name="user_post_tag<?php echo $i; ?>">
								</div><?php
								}
							}
							?>
						</div>

					</div>
					<!-- Custom code end -->
					<?php
				echo '</fieldset>';
			}

			$style = 'display:none;';
            $datepicker = get_post_meta( $thispost->ID, 'publish_date_news', true );
			if (in_array("news", $cat_arr)) {
				$style = '';
			} else {
				$style = 'display:none;';
			} ?>
            <fieldset class="frontier_post_fieldset_date frontier_post_fieldset_tax" id="date_picker_news" style="<?php //echo $style; ?>">
                <div class="row publish_date_section">
                    <div class="date_div">
                        <div>
                            <label>Source Date (When was the source published?) <!--<span class = "text-red">*</span>--></label>
                            <!--<input type="text" name="publish_date_news" id="publish_date_news" value="<?php// echo $datepicker; ?>"/>-->
                          <!-- <input type="text" name="publish_date_news" id="publish_date_news_manual"  placeholder="MM/DD/YYYY" value="<?php// echo $datepicker; ?>"/>-->
                            <input type="text" name="publish_date_news" id="publish_date_news_manual"  placeholder="MM/DD/YYYY" style="color:#666;" value="<?php echo $datepicker; ?>" onblur="ValidateDate(this.value);" maxlength="10" />

                        </div>
                    </div>
                </div>
            </fieldset>
        </td>
        </tr>
    <tr>
	<td class="frontier_no_border">

    <?php
	$post_meta = get_post_meta( $thispost->ID);
	//echo '<pre>'; print_r($post_meta);
    $post_reference_link = get_post_meta( $thispost->ID, 'reference_link', true ); ?>
    <fieldset class="frontier_post_fieldset_additional">
        <div id="reference_link">
            <label>Source URL (not necessary for posts categorized as "Physical Source" or "Other") <span class= "text-red">*</span></label>
            <input type="text" name="reference_link" id="reference_link" value="<?php echo $post_reference_link; ?>" onblur="set_post_title(this.value);"/>
            <!--<input type="text" name="reference_link" id="reference_link" ng-model="refere_link" value="<?php echo $post_reference_link; ?>" ng-blur="set_post_title(refere_link);"/>-->
            <!--<input type="text" name="reference_link" id="reference_link" ng-model="refere_link" ng-blur="set_post_title(refere_link);"/>-->
        </div>
    </fieldset>

    <?php
	//$post_meta = get_post_meta( $thispost->ID);
	//echo '<pre>'; print_r($post_meta);
    $refernce_link_home_page_title = get_post_meta( $thispost->ID, 'refernce_link_home_page_title', true );?>
    <fieldset id="frontier_post_fieldset_refernce_link_home_page_title" class="frontier_post_fieldset">
    <!--<label>Source Page Name (not necessary for posts categorized as "Physical Source" or "Other")</label>
		<?php /*?><legend><?php _e("Source Page Name (not necessary for posts categorized as 'Physical Source' or 'Other')", "frontier-post"); ?></legend><?php */?>
		<input class="frontier-refernce_link_home_page_title"  placeholder="<?php // _e('Enter title here', 'frontier-post'); ?>" type="text" value="<?php  echo $refernce_link_home_page_title; ?>" name="fp_refernce_link_home_page_title" id="fp_refernce_link_home_page_title" readonly="readonly">	-->
	</fieldset>

	<fieldset id="frontier_post_fieldset_title" class="frontier_post_fieldset">
		<?php /*?><legend><?php _e("Post Name", "frontier-post"); ?></legend><?php */?>
        <label>Post Name <span class = "text-red">*</span></label>
		<input class="frontier-formtitle"  placeholder="<?php // _e('Enter title here', 'frontier-post'); ?>" type="text" value="<?php  if(!empty($thispost->post_title))echo $thispost->post_title;?>" name="user_post_title" id="fp_title">
	</fieldset>
	<?php
	$post_source_name = get_post_meta( $thispost->ID, 'source_name', true ); ?>
    <fieldset class="frontier_post_fieldset_additional">
        <div id="reference_link">
            <label>Source Name (Where does the source come from? i.e. WSJ, BBC, Wikipedia?)</label>
            <input type="text" name="source_name" id="source_name" value="<?php echo $post_source_name; ?>"/>
        </div>
    </fieldset>
		<!--  New New New New New New New New New New New New New New New New New New New New  -->
		<?php
		$post_source_author = get_post_meta( $thispost->ID, 'source_author',true ); ?>
		<fieldset class="frontier_post_fieldset_additional">
        <div id="reference_author">
            <label>Source Author</label>
            <input type="text" name="source_author" id="source_author" value="<?php echo $post_source_author; ?>"/>
        </div>
    </fieldset>
		<?php
		$post_meta = get_post_meta( $thispost->ID);
		//echo '<pre>'; print_r($post_meta);
    $post_source_author_url = get_post_meta( $thispost->ID, 'source_author_url', true ); ?>
		<fieldset class="frontier_post_fieldset_additional">
        <div id="reference_author_link">
            <label>Source Author URL (Twitter, Facebook, LinkedIn, or any other author reference.)</label>
            <input type="text" name="source_author_url" id="source_author_url" placeholder="" value="<?php echo $post_source_author_url; ?>" onblur="set_post_title(this.value);"/>
        </div>
    </fieldset>
		<!-- END New New New New New New New New New New New New New New New New New New New New -->
	<?php
	$post_read_arr = get_post_meta( $thispost->ID, 'post_read_time', true );

	$post_read = unserialize($post_read_arr);
	//echo '<pre>'; print_r($post_read);
	$time = '';
	$type = '';
	if( !empty( $post_read ) && is_array( $post_read ) ) {
		foreach( $post_read as $key => $value ) {
			$time = $value;
			$type = $key;
		}
	}?>
    <div class="post_read_time_content">
        <p>Source Length (How long does it take to read or watch the entire source?) <span class = "text-red">*</span></p>
        <div>
            <label for="post_read_time" id="valid_post_read_time">
                <input type="text" name="post_read_time" id="post_read_time" value="<?php echo $time; ?>" required/>
            </label>
            <div class="time_select_option">
            	<select class="cs-select cs-skin-elastic" name="post_read_time_to" id="post_read_time_to">
                	<!--<option value="" disabled="disabled">select</option>-->
                    <option value="min" <?php echo ( $type == 'min' ) ? 'selected' : ''; ?>>Min</option>
                    <option value="sec" <?php echo ( $type == 'sec' ) ? 'selected' : ''; ?>>Sec</option>
                </select>
            </div>
            <input type="hidden" name="hidden_time_selected_val" id="hidden_time_selected_val" value="min" />
            <input type="hidden" name="glf_update" id="glf_update" value="" />
        </div>
    </div>

	<?php if ( fp_get_option_bool("fps_hide_status") )
		{
		echo '<input type="hidden" id="post_status" name="post_status" value="'.$thispost->post_status.'"  >';
		}
	else
		{
		//echo ' '.__("Status", "frontier-post").': ';
		?>
		<fieldset id="frontier_post_fieldset_status" class="frontier_post_fieldset">
			<?php /*?><legend><?php _e("Status", "frontier-post"); ?></legend><?php */?>
            <label>Status</label>
			<select  class="frontier_post_dropdown cs-select cs-skin-elastic" id="post_status" name="post_status" >
			<?php foreach($status_list as $key => $value) : ?>
				<option value='<?php echo $key ?>' <?php echo ( $key == $tmp_post_status) ? "selected='selected'" : ' ';?>>
					<?php echo $value; ?>
				</option>
			<?php endforeach; ?>
			</select>
		</fieldset>
	<?php } ?>

	</td></tr>

	<?php
		//****************************************************************************************************
		// Action fires before displaying the editor
		// Do action 		frontier_post_form_standard_top
		// $thispost 		Post object for the post
		// $tmp_task_new  	Equals true if the user is adding a post
		//****************************************************************************************************

		do_action('frontier_post_form_standard_top', $thispost, $tmp_task_new);
	?>

	<tr><td class="frontier_no_border">
	<fieldset class="frontier_post_fieldset">
		<?php /*?><legend><?php _e("Summary", "frontier-post"); ?></legend><?php */?>
        <label>Summary</label>
		<div id="frontier_editor_field">
		<?php
		//wp_editor($thispost->post_content, 'frontier_post_content', frontier_post_wp_editor_args($fpost_sc_parms['frontier_editor_height']));
		?>
        <textarea name="frontier_post_content" id="frontier_post_content" rows="7"><?php echo $thispost->post_content;?></textarea>
        <script src="https://tinymce.cachefly.net/4.0/tinymce.min.js"></script>
        <script>
			tinymce.init({
				selector: "textarea",
				paste_as_text: true,
				browser_spellcheck: true,
				contextmenu: false,
				menubar: false,


				plugins: [
					"advlist autolink lists link image charmap print preview anchor",
					"searchreplace visualblocks code fullscreen",
					"paste"
				],
				toolbar: "insertfile undo redo | removeformat | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link",
			});
        </script>

		</div>
	</fieldset>
	</td></tr>
    <tr>
    	<td>
            <div id="dragAndDropFiles" class="uploadArea">
                <h1 class="title">Add Images (Photos, Charts, Infographs, etc.)</h1>
                <?php
				$post_multi_image_arr = '';
				$post_multi_image_arr = unserialize( get_post_meta( $thispost->ID, 'post_multi_images', true ) );
				//echo '<pre>'; print_r($post_multi_image_arr);
				if( $post_multi_image_arr ) {
					foreach( $post_multi_image_arr as $post_multi_image ) {
						$image = wp_get_attachment_image_src ( $post_multi_image['image_id'], '150_150_img' );
						//print_r($image);?>
                        <div class="dfiles" style="">
                            <div class="uploaded_image">
                                <a href="javascript:void(0);" class="attach_image_close">X</a>
                                <img src="<?php echo $image[0]; ?>" width="<?php echo $image[1]; ?>" height="<?php echo $image[2]; ?>" />
                            	<input id="post_attach_image" type="hidden" value="<?php echo $post_multi_image['image_id']; ?>" name="post_attach_image[]">
                            	<input type="text" name="post_attach_image_title_<?php echo $post_multi_image['image_id']; ?>" id="post_attach_image_title_<?php echo $post_multi_image['image_id']; ?>" value="<?php echo $post_multi_image['title']; ?>" />
                            </div>
                        </div><?php
					}
				} ?>

                <script type="text/javascript">
					var config = {
						support : "image/jpg,image/png,image/bmp,image/jpeg,image/gif",		// Valid file formats
						form: "frontier_post",												// Form ID
						dragArea: "dragAndDropFiles",										// Upload Area ID
						//uploadUrl: "<?php //echo $upload_baseurl.'/upload.php'; //echo admin_url('admin-ajax.php'); ?>"			// Server side upload url
						uploadUrl: ajaxurl
					}
					jQuery(document).ready(function(){
						initMultiUploader(config);

						jQuery("body").on('click','.attach_image_close', function() {
							jQuery(this).parent().parent().remove();
							if(jQuery(".dfiles").length < 1){
								jQuery("#post_attach_image").val('');
							}
						});

					});
                </script>

            </div>
            <div class="post_image_upload_btn">
            	<input type="file" name="multiUpload" id="multiUpload" multiple />
            </div>
            <!--<a href="javascript:void(0);" id="image_upload_btn">Upload</a>-->
            <div class="custom_attached_images">
            </div>
            <!--</form>-->
            <div class="progressBar">
                <div class="status"></div>
            </div>
            <div id="error_msg"></div>

            <!--<div class="input_fields_wrap">
                <div class="video_add_section">
                    <h3>Add Video URL</h3><?php
					/*$post_multi_video_arr = get_post_meta( $thispost->ID, 'post_video_link', true );
					$post_multi_video_arr = unserialize( $post_multi_video_arr );
					if( $post_multi_video_arr ) {
						$inc = 0;
						foreach( $post_multi_video_arr as $post_multi_video ) { ?>
                        	<div class="video_url">
                        		<input type="text" name="mytext[]" value="<?php echo $post_multi_video; ?>"><?php
                                if( $inc != 0 ) { ?>
                                	<a href="#" class="remove_field">Remove Video</a><?php
                                } ?>
                            </div>
						<?php
						$inc++;
						}
					} else { ?>
                    	<div class="video_url">
							<input type="text" name="mytext[]" value="">
						</div><?php
					}*/ ?>
                    <button class="add_field_button">Add another video</button>
                </div>
            </div>-->

            <div class="input_fields_wrap">
                <div class="video_add_section">
                    <h3>Add Video URL (YouTube, Vimeo or DailyMotion):</h3>
                    <?php
					$post_video = get_post_meta( $thispost->ID, 'post_video', true );
					if( $post_video ) { ?>
                        <div class="video_url">
                            <input type="text" id="post_video" name="post_video" onblur="find_video_to_add_remove_btn();" value="<?php echo $post_video; ?>"/>
                            <!--<p>You must give a iframe width 100% <span class="text-red">*</span></p>-->
                            <!--<span class="remove_post_video_action">
                            	<a href="#" class="remove_field" onclick="remove_post_video();">Remove Video</a>
                            </span>-->
                        </div><?php
					} else { ?>
                    	<div class="video_url">
							 <input type="text" id="post_video" name="post_video" onblur="find_video_to_add_remove_btn();"  value="" />
                            <!--<p>You must give a iframe width 100% <span class="text-red">*</span></p>-->
                            <!--<span class="remove_post_video_action" style="display:none;">
                            	<a href="#" class="remove_field" onclick="remove_post_video();">Remove Video</a>
                            </span>-->
						</div><?php
					} ?>
                </div>
            </div>
            <style>
				textarea#post_video {
					width: 600px;
					height: 70px;
					border: 3px solid #cccccc;
					padding: 5px;
					font-family: Tahoma, sans-serif;
					background-image: url(bg.gif);
					background-position: bottom right;
					background-repeat: no-repeat;
				}
			</style>
        </td>
    </tr>
	<?php

	echo '<tr><td class="frontier_no_border">';

		if (fp_get_option_bool("fps_show_feat_img") )
			{
		//****************************************************************************************************
		// Featured image
		//****************************************************************************************************


		if ( fp_get_option_bool("fps_show_feat_img") )
			{

			//force grid view
			//update_user_option( get_current_user_id(), 'media_library_mode', 'grid' );

			//set iframe size for image upload
			if ( wp_is_mobile() )
				{
				$i_size 	= "&width=240&height=320";
				$i_TBsize 	= "&TB_width=240&TB_height=320";
				}
			else
				{
				$i_size 	= "&width=640&height=400";
				$i_TBsize 	= "&TB_width=640&TB_height=400";
				}

			?>
			<!--<td class="frontier_featured_image">-->

			<fieldset class="frontier_post_fieldset_tax frontier_post_fieldset_tax_featured">
			<legend><?php _e("Featured image", "frontier-post"); ?></legend>
            <label>Featured image</label>
			<?php
			//$FeatImgLinkHTML = '<a title="Select featured Image" href="'.site_url('/wp-admin/media-upload.php').'?post_id='.$post_id.'&amp;type=image&amp;TB_iframe=1'.$i_size.'" id="set-post-thumbnail" class="thickbox">';
			$FeatImgLinkHTML = '<a title="Select featured Image" href="'.site_url('/wp-admin/media-upload.php').'?post_id='.$post_id.$i_TBsize.'&amp;tab=library&amp;mode=grid&amp;type=image&amp;TB_iframe=1'.$i_size.'" id="set-post-thumbnail" class="thickbox">';
			//$FeatImgLinkHTML = '<a title="Select featured Image" href="'.site_url('/wp-admin/upload.php').'?post_id='.$post_id.$i_TBsize.'&amp;mode=grid&amp;type=image&amp;TB_iframe=1'.$i_size.'" id="set-post-thumbnail" class="thickbox">';

			if (has_post_thumbnail($post_id))
				$FeatImgLinkHTML = $FeatImgLinkHTML.get_the_post_thumbnail($post_id, 'thumbnail').'<br>';

			$FeatImgLinkHTML = $FeatImgLinkHTML.'<br>'.__("Select featured image", "frontier-post").'</a>';

			echo $FeatImgLinkHTML."<br>";
			echo '<div id="frontier_post_featured_image_txt">'.__("Not updated until post is saved", "frontier-post").'</div>';
			echo '</fieldset>';
			//echo '</td>';
			}
		//echo '</tr></tbody></table>';
		}

		if ( current_user_can( 'frontier_post_exerpt_edit' ) )
			{ ?>
			<fieldset class="frontier_post_fieldset_excerpt">
				<?php /*?><legend><?php _e("Excerpt", "frontier-post")?>:</legend><?php */?>
                <legend>Excerpt:</legend>
				<textarea name="user_post_excerpt" id="user_post_excerpt" ><?php if(!empty($thispost->post_excerpt))echo $thispost->post_excerpt;?></textarea>
			</fieldset>

	<?php 	}

	echo '</td></tr>';

		//****************************************************************************************************
		// post moderation
		//****************************************************************************************************

		if ( fp_get_option_bool("fps_use_moderation") && (current_user_can("edit_others_posts") || $current_user->ID == $thispost->post_author))
			{
			echo '<tr><td class="frontier_no_border">';
			echo '<fieldset class="frontier_post_fieldset_moderation">';
			echo '<label>'.__("Post Moderation", "frontier-post").'</label>';
			//Allow email to be send to author on comment update
			if (current_user_can("edit_others_posts"))
				echo __("Email author with moderation comments ?", "frontier-post").' '.'<input name="frontier_post_moderation_send_email" id="frontier_post_moderation_send_email" value="true"  type="checkbox"><br>';

			echo '<textarea name="frontier_post_moderation_new_text" id="frontier_post_moderation_new_text" >';
			echo '</textarea>';
			echo __("Previous comments", "frontier-post").':<br>';
			echo '<hr>';
			echo $fp_moderation_comments;

			echo '</fieldset>';


			echo '</td></tr>';

			}
		//****************************************************************************************************
		// Action fires just before the submit buttons
		// Do action 		frontier_post_form_standard
		// $thispost 		Post object for the post
		// $tmp_task_new  	Equals true if the user is adding a post
		//****************************************************************************************************

		do_action('frontier_post_form_standard', $thispost, $tmp_task_new);


		echo '<tr><td class="frontier_no_border">';

	?>
    <div class="source_required_msg_btm"></div>
		<fieldset class="frontier_post_fieldset">
		<!--<legend><?php //_e("Actions", "frontier-post"); ?>--></legend>
		<?php

		if ( fp_get_option_bool("fps_submit_save") && isset( $_GET['task'] ) && $_GET['task'] == 'edit' )
		{ ?>
			<button class="button" type="submit" name="user_post_submit" id="user_post_save" value="save" onclick="system_date_fn()"><?php _e("Save", "frontier-post"); ?></button>
		<?php }

		if ( fp_get_option_bool("fps_submit_savereturn") )
		{ ?>
			<button class="button" type="submit" name="user_post_submit" id="user_post_submit" value="savereturn"><?php echo $fpost_sc_parms['frontier_return_text']; ?></button>
		<?php }

		if ( fp_get_option_bool("fps_submit_publish") && ($thispost->post_status !== "publish" || $tmp_task_new) && current_user_can("frontier_post_can_publish") )
		{ ?>
			<button class="button" type="submit" name="user_post_submit" id="user_post_publish" value="publish" onclick="system_date_fn()"><?php _e("Post", "frontier-post"); ?></button>
		<?php }

		if ( fp_get_option_bool("fps_submit_preview") )
		{ ?>
			<button class="button" type="submit" name="user_post_submit" id="user_post_preview" value="preview"><?php _e("Save & Preview", "frontier-post"); ?></button>
		<?php }

		if ( fp_get_option_bool("fps_submit_delete")  && current_user_can("frontier_post_can_delete") && !$tmp_task_new )
		{ ?>
			<button class="button frontier-post-form-delete" type="submit" name="user_post_submit" id="user_post_delete" value="delete"><?php _e("Delete", "frontier-post"); ?></button>
		<?php }

		if ( fp_get_option_bool("fps_submit_cancel") )
		{ ?>
		<input type="button" value="<?php _e("Cancel", "frontier-post"); ?>"  name="cancel" id="frontier-post-cancel" onclick="location.href='<?php echo home_url('/');?>'">
		<?php
		}


		/*
		if ( fp_get_option_bool("fps_submit_delete") && $thispost->post_status !== "publish" && current_user_can("frontier_post_can_delete") && !$tmp_task_new )
			{
			echo "&nbsp;".frontier_post_delete_link($thispost, false, $frontier_permalink, 'frontier-post-form-delete' );
			}
		*/
		echo '<p class="frontier-post-form-posttype">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;('.__("Post type", "frontier-post").": ".fp_get_posttype_label_singular($thispost->post_type).') </p>';
		?>
	</fieldset>

	</td></tr></table>
</form>
	</div> <!-- ending div -->
<?php

	// end form file
?>
