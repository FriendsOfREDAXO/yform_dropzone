var myDropzone;

Dropzone.autoDiscover = false;

var YFormDropzone = {

	init: function()
		{
		this.initDropzone();
		this.setActions();
	},

		initDropzone: function()
		{
			console.log("init");
		myDropzone = new Dropzone(
			"div#fileupload",
			{
				url: "index.php?rex-api-call=yform_dropzone&func=upload",
				paramName: "file", // The name that will be used to transfer the file
				maxFilesize: function() { xysize = $(this).data("dropzone-size_single")/1024; console.log(xysize); return xysize; }, // MB Todo: Aus Attributen auslesen
				acceptedFiles: ".pdf,.zip", // ToDo: Aus Attributen auslesen
				createImageThumbnails: false,
				previewTemplate: '<div class="upload-item"><div class="upload-progress" data-dz-uploadprogress></div><div class="upload-data"><span class="upload-name" data-dz-name></span> (<span class="upload-size" data-dz-size></span>)aaaaaaaaaaaa</div></div>',
				init: function() {
					$.get('index.php?rex-api-call=yform_dropzone&func=upload', function(data) {
						$.each(data, function(key,value){
							var file = {
								name: value.name,
								size: value.size
							};
							YFormDropzone.setFile(file);
							YFormDropzone.setActions();
						});
					});
				},
				error: function(file) {
					$('.upload-modal').addClass('active');
					myDropzone.removeFile(file);
				}
			}
		);
		
		myDropzone.on("addedfile", function(file) {
			var extension = file.name.substring(file.name.lastIndexOf('.') + 1);
			if( file.size > 10000000 || (extension != 'pdf' && extension != '.zip') ){
				myDropzone.options.error.call(myDropzone, file);
			}
			else{
				var removeButton = Dropzone.createElement('<a class="remove-file">entfernen</a>');
				removeButton.addEventListener("click", function(e) {
					e.preventDefault();
					e.stopPropagation();
					$.post( 'index.php?rex-api-call=yform_dropzone&func=delete', { file: file.name }).done(function( data ) {
						myDropzone.removeFile(file);
					});
				});
				file.previewElement.appendChild(removeButton);
				$('input[name="upload"]').val(file.name);
			}
		});
	},

		setActions: function()
		{
		$('.remove-file').unbind();
		$('.remove-file').click(function(e){
			e.preventDefault();
			
			var _this = $(this);
			
			$.post( 'index.php?rex-api-call=yform_dropzone&func=delete', { file: $(this).data('name') }).done(function( data ) {
				_this.parent().remove();
			});
			
			return false;
		});
		
		$('.close-modal').unbind();
		
		$('.close-modal').click(function(e){
			e.preventDefault()
			
			$('.upload-modal').removeClass('active');
			
			return false;
		});
	},

		setFile: function(file)
		{
		$('.upload-files').addClass('active');

		$('input[name="upload"]').val(file.name);

		$('.upload-files').append('<div class="upload-item"><div class="row upload-data"><div class="col"><strong><span class="upload-name">' + file.name + '</span></strong><br /><span class="upload-size">(' + (file.size / 1024 / 1024).toFixed(2) + ' MB)</span><a data-name="'+file.name+'" class="btn btn-default remove-file" role="button"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> entfernen</a></div>');

	},

};

$(document).ready(function() {
	YFormDropzone.init();

});