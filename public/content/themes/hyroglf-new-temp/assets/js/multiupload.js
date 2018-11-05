function multiUploader(config){
	this.config = config;
	this.items = "";
	this.all = []
	var self = this;
	
	multiUploader.prototype._init = function(){
		if (window.File && 
			window.FileReader && 
			window.FileList && 
			window.Blob) {	
			 var inputId = jQuery("#"+this.config.form).find("input[type='file']").eq(0).attr("id");
			 document.getElementById(inputId).addEventListener("change", this._read, false);
			 document.getElementById(this.config.dragArea).addEventListener("dragover", function(e){ e.stopPropagation(); e.preventDefault(); }, false);
			 document.getElementById(this.config.dragArea).addEventListener("drop", this._dropFiles, false);
			 document.getElementById(this.config.form).addEventListener("submit", this._submit, false);
		} else
			console.log("Browser supports failed");
	}
	
	//console.log(multiUploader.prototype);
	
	jQuery("body").on('click', '#image_upload_btn', function(e) {
		e.stopPropagation(); e.preventDefault();
		self._startUpload();
	});
	/*multiUploader.prototype._submit = function(e){
		e.stopPropagation(); e.preventDefault();
		self._startUpload();
	}*/
	
	multiUploader.prototype._preview = function(data){
		this.items = data;
		if(this.items.length > 0){
			var html = "";		
			var uId = "";
 			for(var i = 0; i<this.items.length; i++){
				uId = this.items[i].name._unique();
				var sampleIcon = '<img src="'+drag_drop_object.images+'/images/image.png" />';
				var errorClass = "";
				if(typeof this.items[i] != undefined){
					if(self._validate(this.items[i].type) <= 0) {
						sampleIcon = '<img src="'+drag_drop_object.images+'/images/unknown.png" />';
						errorClass =" invalid";
					}
						New_uId = uId.split('.').join("");
						
						html += '<div class="dfiles'+errorClass+'" rel="'+uId+'"><h5>'+sampleIcon+this.items[i].name+'</h5><div id="'+uId+'" class="progress '+New_uId+'" style="display:none;"><img src="'+drag_drop_object.images+'/images/ajax-loader.gif" /></div></div>';
						if(!errorClass){
							jQuery("#dragAndDropFiles h1").remove();
						}
					
					
					/*if( errorClass == " invalid") {
						jQuery("."+New_uId).slideUp("normal", function(){ jQuery(this).parent().remove(); });
					}*/
					
					//html += '<div class="dfiles'+errorClass+'" rel="'+uId+'"><h5>'+sampleIcon+this.items[i].name+'</h5><div id="'+uId+'" class="progress" style="display:none;"><img src="'+drag_drop_object.images+'/images/ajax-loader.gif" /></div></div>';
					
				}
			}
			
			if(!errorClass){
				jQuery("#dragAndDropFiles").append(html);
				jQuery("#error_msg").hide();
			}else{
				jQuery("#error_msg").show();
				jQuery("#error_msg").html("Please upload only images");
			}
			
		}
	}

	multiUploader.prototype._read = function(evt){
		if(evt.target.files){
			self._preview(evt.target.files);
			self.all.push(evt.target.files);
			
			self._startUpload();
			
			
		} else 
			console.log("Failed file reading");
	}
	
	multiUploader.prototype._validate = function(format){
		var arr = this.config.support.split(",");
		return arr.indexOf(format);
	}
	
	multiUploader.prototype._dropFiles = function(e){
		e.stopPropagation(); e.preventDefault();
		self._preview(e.dataTransfer.files);
		self.all.push(e.dataTransfer.files);
		
		self._startUpload();
		
	}
	
	multiUploader.prototype._uploader = function(file,f){
		
		if(typeof file[f] != undefined && self._validate(file[f].type) > 0){
			
			
			var data = new FormData();
			console.log(data);
			var ids = file[f].name._unique();
			data.append('file',file[f]);
			data.append('action','custom_file_upload');
			data.append('index',ids);
			jQuery(".dfiles[rel='"+ids+"']").find(".progress").show();
			
			jQuery.ajax({
				type:"POST",
				url:this.config.uploadUrl,
				data:data,
				cache: false,
				contentType: false,
				processData: false,
				success:function(rponse){
					//alert(rponse);
					jQuery("#"+ids).hide();
					var obj = jQuery(".dfiles").get();
					jQuery.each(obj,function(k,fle){
						
						jQuery("#user_post_publish").attr("type","submit");
						jQuery("#user_post_publish").removeClass("uploading");
						
						data = rponse.split('&&');
						
						if(jQuery(fle).attr("rel") == jQuery.trim( data[0])){
							//alert();
							jQuery(fle).show(function(){ jQuery(this).html(data[1]); });
						}
						
						/*if(jQuery(fle).attr("rel") == rponse){
							jQuery(fle).slideUp("normal", function(){ jQuery(this).remove(); });
						}*/
					});
					if (f+1 < file.length) {
						self._uploader(file,f+1);
						jQuery("#user_post_publish").attr("type","button");
						
						jQuery("#user_post_publish").removeClass("uploading");
						jQuery("#user_post_publish").addClass("uploading");
						
					}
					
					
				}
			});
			return false;
		} else
			console.log("Invalid file format - "+file[f].name);
	}
	
	multiUploader.prototype._startUpload = function(){
		
		if(this.all.length > 0){
			for(var k=0; k<this.all.length; k++){
				var file = this.all[k];
				this._uploader(file,0);
				jQuery("#user_post_publish").attr("type","button");
						jQuery("#user_post_publish").addClass("uploading");
				
			}
		}
	}
	
	String.prototype._unique = function(){
		return this.replace(/[a-zA-Z]/g, function(c){
     	   return String.fromCharCode((c <= "Z" ? 90 : 122) >= (c = c.charCodeAt(0) + 13) ? c : c - 26);
    	});
	}

	this._init();
}

function initMultiUploader(){
	new multiUploader(config);
	
}