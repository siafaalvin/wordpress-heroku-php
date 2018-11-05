	<input type="hidden" name="filter_category" id="filter_category" value="" />
    <input type="hidden" name="filter_taxonomy" id="filter_taxonomy" value="" />
    <input type="hidden" name="filter_cat_name" id="filter_cat_name" value="" />
    <input type="hidden" name="filter_cat_img" id="filter_cat_img" value="" />
    <input type="hidden" name="set_vote_option" id="set_vote_option" value="" />
    <input type="hidden" name="set_post_id" id="set_post_id" value="" />
    
    <input type="hidden" name="filter_informative" id="filter_informative" value="" />
    <input type="hidden" name="filter_bias" id="filter_bias" value="" />
    
    <input type="hidden" name="hidden_drop_down_action" id="hidden_drop_down_action" value="" />
    
    <input type="hidden" name="date_validation" id="date_validation" value="" />
    <input type="hidden" name="hidden_p_id" id="hidden_p_id" value="" />
    
    <input type="hidden" name="num_of_scroll" id="num_of_scroll" value="1" />
    <input type="hidden" name="num_of_page" id="num_of_page" value="" />

    <div style="display:none;">
        <div id="AbotContent" class="about_us_content">
        	<div class="content_about_popup">
        		<?php About_Content(); ?>
            </div>
        </div>
    </div>
    
    <div class="edit_loader" style="display:none;">
		<?php echo get_image('ajax_load_content.gif', '', '', 'Load content'); ?>
    </div>
    
    <footer>
        <div class="container">
            <div class="row">
            
            	<!--<div class="header_advertisement_logo mobile_advertisement_logo show-767"><?php
				/*if( of_get_option('advertisemnet_logo') ) { ?>
					<a <?php if(of_get_option('logo_url')) { ?> href = "<?php echo of_get_option('logo_url');?>"<?php } ?> target ="_blank">
						<img src="<?php echo of_get_option('advertisemnet_logo');?>" alt="logo" width="162" height="84" />
					</a><?php
				}*/ ?>
				</div>-->
                
                <div class="footer_links"><?php 
                    wp_nav_menu( array( 'theme_location' => 'footer', 'container' => '', 'menu_id' => '', 'menu_class'=> '') ); ?>
                </div>
                <div class="header_advertisement_logo mobile_advertisement_logo"><?php
				if( of_get_option('advertisemnet_logo') ) { ?>
					<a <?php if(of_get_option('logo_url')) { ?> href = "<?php echo of_get_option('logo_url');?>"<?php } ?> target ="_blank">
						<img src="<?php echo of_get_option('advertisemnet_logo');?>" alt="logo" width="162" height="84" />
					</a><?php
				} ?>
				</div>
            </div>
        </div>
    </footer>
</div> <!--- wiki wrapper --->
<div id="back-to-top">&lt;</div>
</div> <!--- Page wrapper --->

<?php wp_footer(); ?>
<textarea id="hidden_edit_data" class="hidden_edit_data" name="hidden_edit_data" style="display:none;"></textarea>
<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.4/jquery.touchSwipe.min.js' async defer></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/jquery.slicknav.min.js"></script>

<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>



<?php
if( is_page('my-posts') && isset($_GET['task']) ) { ?>
<script type="text/javascript">
	jQuery(window).load(function() {
	
		jQuery('.mce-statusbar.mce-container').append('<div class="mce-char-wordcount"></div><div class="word_count_error"></div><input type="hidden" name="word_count_error_hidden" id="word_count_error_hidden" value="">');
	
		var textarea_cont = jQuery('#frontier_post_content');
		var wysiwyg_cont = jQuery('#frontier_post_content_ifr').contents();
	
		if( textarea_cont && wysiwyg_cont ) {
			/* Variables Initial define */
			var setLimit = 1; // Limit = 1, no limit = 0
			var limitedItem = 'word'; // char or word
			var maxCharacters = 800; // max characters count if limit is set
			var charWarning = 'Sorry, but you exceeded the char limit!'; // number of characters before limit where the user is warned
			var maxWords = 80; // max words count if limit is set
			var wordWarning = 'Exceeding 80 word limit!'; // number of words before limit where the user is warned
			var formatString = '#chars characters | #words words'; // The syntax used to display the output
			var charInfo = jQuery('.mce-char-wordcount'); // Output container, same as Default WP Word count
			var contentLength = 0;
			var numChars = 0;
			var numCharsLeft = 0;
			var numWords = 0;
			var numWordsLeft = 0;
		
			/* The events on each container */
			textarea_cont.on('keyup', function(event){getTheWpwclCount('textarea');})
					  .on('paste', function(event){setTimeout(function(){getTheWpwclCount('textarea');}, 10);});
		
			wysiwyg_cont.on('keyup', 'body', function(event){getTheWpwclCount('wysiwig');})
						.on('paste', 'body', function(event){setTimeout(function(){getTheWpwclCount('wysiwig');}, 10);});
		
			/* Function to find and display the characters count */
			function getTheWpwclCount(cont){
				//charInfo.html(wpwclFormatDisplay(cont));
				wpwclFormatDisplay(cont);
			}
		
			/* Counting the characters and the words */
			function wpwclFormatDisplay(cont) {
				if (cont == 'textarea') {
					// Textarea case
					var raw_content = textarea_cont.val();
				} else {
					// WysiWyg case
					var raw_content = wysiwyg_cont.find('body').html();
				}
		
				/* Characters count */
				var content = raw_content.replace(/(\\r\\n)+|\\n+|\\r+|\s+|(&nbsp;)+/gm,' '); // Replace newline, tabulations, &nbsp; by space to preserve word count
				content = content.replace(/<[^>]+>/ig,''); // Remove HTML tags
				content = content.replace(/(&lt;)[^(\&gt;)]+(\&gt;)/ig,''); // Remove HTML tags (when entities)
				content = content.replace(/\[[^\]]+\]/ig,''); // Remove shortcodes
				numChars = content.length;
		
				/* Words count */
				// Cleaning and splitting wordstring (tags and shortcodes are already stripped)
				var rawContent = content;
				var cleanedContent = rawContent.replace(/[\.,:!\?;\)\]…\"]+/gi, ' '); //Replacing ending ponctuation with spaces to get right word number.
				var cleanedContent = cleanedContent.replace(/\s+/ig,' ') // Multiple spaces case (after punctuation replacement) replaced by one space
				var splitString = cleanedContent.split(' ');
				// Word Count defining
					var splitString = jQuery.map( splitString, function(v){
					  return v === "" ? null : v;
					});
					
					numWords = splitString.length;
		
				/* Treatment if limit set (change color by status) */
				if(setLimit > 0){
				  if (limitedItem == 'char') {
					/* Case of Characters limitation */
					if (numChars <= maxCharacters - charWarning)
						charInfo.css('color', 'inherit');
					else if (numChars <= maxCharacters && contentLength >= maxCharacters - charWarning )
						charInfo.css('color', 'orange');
					else if(numChars > maxCharacters)
						charInfo.css('color', 'red');
						numCharsLeft = (maxCharacters - numChars > 0) ? maxCharacters - numChars : 0;
					
				  } else { // word count
					/* Case of words limitation */
					if (numWords <= maxWords - wordWarning) {
						charInfo.css('color', 'inherit');
					} else if (numWords <= maxWords) {
						charInfo.css('color', 'orange');
						//jQuery("#user_post_publish").removeAttr("disabled");
					} else if(numWords > maxWords) {
						charInfo.css('color', 'red');
						//jQuery("#user_post_publish").attr("disabled", "disabled");
					}
					numWordsLeft = (maxWords - numWords > 0) ? maxWords - numWords : 0;
				  }
				  
				}
		
				/* Output the result */
				var output = formatString;
					output = output.replace(/#input|#chars/gi, numChars); // #input for backward compatibility
					output = output.replace(/#words/gi, numWords);
			   //When no limit set, #maxChars, #leftChars, #maxWords, #leftWords cannot be substituted.
					if(setLimit > 0){
						if (limitedItem == 'char') {
						  /* Chararacters case */
						  output = output.replace(/#max|#maxChars/gi, maxCharacters); // #max for backward compatibility
						  output = output.replace(/#left|#leftChars/gi, numCharsLeft);
						} else {
						  /* Words case */
						  output = output.replace(/#maxWords/gi, maxWords);
						  output = output.replace(/#leftWords/gi, numWordsLeft);
						}
					}
					
				charInfo.html(output); // display output
				
				if( numWords > maxWords ) {
					jQuery('.word_count_error').empty();
					jQuery('.word_count_error').append('<p>'+wordWarning+'</p>');
					jQuery("#word_count_error_hidden").val(wordWarning);
					//jQuery(".word_count_error").remove();
					//jQuery('.word_count_error').val('');
					
				} else {
					jQuery('.word_count_error').empty();
					jQuery(".word_count_plulish_error").remove();
					jQuery("#word_count_error_hidden").val('');
				}
				//return output;
			}
		
			// Launching word count on load
			getTheWpwclCount('wysiwig');
		
		
			jQuery('#user_post_save').on('click', function(e) {
				if ((limitedItem == 'char' && numChars > maxCharacters) || (limitedItem == 'word' && numWords > maxWords)) {
					/* Refuse saving if too many characters or words only for the defined users */
					e.preventDefault();
					//alert(wordWarning);
				}
				jQuery(".word_count_error").remove();
			}); // End if limit set and user must be impacted
		}
	}); // End jQuery handling

	jQuery(document).ready(function() {
		var max_fields      = 10; //maximum input boxes allowed
		var wrapper         = jQuery(".input_fields_wrap"); //Fields wrapper
		var add_button      = jQuery(".add_field_button"); //Add button ID
	   
		var x = 1; //initlal text box count
		jQuery(add_button).click(function(e){ //on add input button click
			e.preventDefault();
			if(x < max_fields){ //max input box allowed
				x++; //text box increment
				//jQuery('<div class="video_url"><textarea id="video_url" name="mytext[]"></textarea><a href="#" class="remove_field">Remove Video</a></div>').insertBefore(this);
				jQuery('<div class="video_url"><input type="text" name="mytext[]" value=""><a href="#" class="remove_field">Remove Video</a></div>').insertBefore(this); //add input box
			}
		});
		jQuery(wrapper).on("click",".remove_field", function(e){
			e.preventDefault();
			jQuery(this).parent('div').remove();
			x--;
		})
	});
	a= jQuery(".video_add_section #video_url").val();
	jQuery(".video_add_section #video_url").val(a);
</script>

<?php
	} ?>
    
<!--<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>-->
    
<script type="text/javascript">
jQuery(document).ready(function() {
	
	jQuery('<a href="<?php echo home_url('/reset-password/'); ?>" id="forgot_password_action">reset password</a>').insertAfter(".login-submit");
	//jQuery('<a href="<?php //echo home_url('/sign-up/'); ?>" id="signup_action">signup</a>').insertAfter("#forgot_password_action");
	
	//log_content = "<p><span class='dont_log_text'>Don't have an account? <a href='<?php echo home_url('/sign-up/'); ?>' class='create_account_action'>Join Hyroglf</a></span></p>";
	
	//jQuery("#wppb-login-wrap #loginform").append(log_content);
	
	<?php
	
	
	if( !is_single() ) { ?>
		if(jQuery('.wiki_category ul li:nth-child(4)').length > 0){
			var topli = jQuery('.wiki_category ul li:nth-child(4)').position().top;
			var topliheight = jQuery('.wiki_category ul li:nth-child(4)').height();
			jQuery('.wiki_left_section,.wiki_right_section').css('min-height',topli+topliheight+'px');
		}
		
		
		jQuery(window).on('scroll', function() {
			
			
			//var toplastchild = jQuery('.home_content_section .list_of_post_content_section:nth-last-child(2)').offset().top;
			//jQuery('.wiki_left_section').css({"height":toplastchild+'px',"overflow":"hidden"});
			//jQuery('.wiki_right_section').css({"height":toplastchild+'px',"overflow":"hidden"});
			
			if(jQuery('.data-height-view').length > 0) {
				if(jQuery('body .wiki_category ul li.pro_list_image').length > 0){
					jQuery('body .wiki_category ul li.pro_list_image').each(function() {
						if(jQuery(window).scrollTop() >= jQuery(this).position().top - 320) {
							jQuery(this).addClass('opacityone');
						} else {
							  jQuery(this).removeClass('opacityone');
							 // jQuery(this).remove();
						}
					});
				}
			}else {
			
			var scope = angular.element(document.getElementById('scroll_load_categories')).scope();
			  scope.$apply(function() {
				scope.scroll_load_categories();
			});
			
				
			
				if(jQuery('body .wiki_category ul li.pro_list_image').length > 0){
					jQuery('body .wiki_category ul li.pro_list_image').each(function() {
						if(jQuery(window).scrollTop() >= jQuery(this).position().top - 320) {
							jQuery(this).addClass('opacityone');
						} else {
							  jQuery(this).removeClass('opacityone');
							 // jQuery(this).remove();
						}
					});
				}
			}
			
		});
	<?php
	} ?>
	
});
</script>
<?php 
	global $post;
	
	if($post->post_name == 'my-posts'){
	?>
		<script type="text/javascript">
		/* gotChanged = 0;
		jQuery("#reference_link,#publish_date_news_manual,#fp_title,#source_name,#post_read_time,#post_video,#post_tag_text").keyup(function(){
			jQuery("#hidden_edit_data").val("gotchanaged");
		});
		
		jQuery("#fp_tax_category").change(function(){
			jQuery("#hidden_edit_data").val("gotchanaged");
		});
		
		jQuery(".frontier_post_fieldset button").click(function(){
			characters = jQuery(".mce-char-wordcount").text().split(' characters');
			if(jQuery("#hidden_edit_data").val() != ''){
				var r = confirm("Edits not saved! Close editor?");
				if(r == true){
					return true;
				} else {
					return false;
				}
			}
			
		}); */
		jQuery("#frontier_post").on("submit", function(e) {
			window.onbeforeunload = null;
			return true;
		});
		
		gotChanged = 0;
		jQuery("#reference_link,#source_name,#post_read_time,#frontier_post_content").keypress(function(){
			characters = jQuery(".mce-char-wordcount").text().split(' characters');
			if(characters[0] > 0){
				gotChanged = 1;
			}
			gotChanged = 1;
		});
		jQuery("body").on('click',".cs-options li",function(){
			gotChanged = 1;
		});
		
	  window.onbeforeunload =  function()
	  {
		  alert(111);
		  if(gotChanged == 1){
			  return "Edits not saved! Close editor?";
		  
		  characters = jQuery(".mce-char-wordcount").text().split(' characters');
			if(characters[0] > 0){
				gotChanged = 1;
			}
		  if(gotChanged == 1){
			return "Edits not saved! Close editor?";
		  }
		  return "Edits not saved! Close editor?";
		  }
	  }
	 
		
		</script><?php
	}?>
	

</body>
</html>