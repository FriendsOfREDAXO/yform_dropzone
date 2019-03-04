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

			var previewNode = document.querySelector("[data-dz-id] #file-row");
			previewNode.id = [];

			var previewTemplate = previewNode.parentNode.innerHTML;
			previewNode.parentNode.removeChild(previewNode);

			// siehe https://www.dropzonejs.com/bootstrap.html#
			myDropzone = new Dropzone(
			document.body,
			{
				url: "index.php?rex-api-call=yform_dropzone&func=upload",
				thumbnailWidth: 80,
				thumbnailHeight: 80,
				parallelUploads: 4,
				previewTemplate: previewTemplate,		
				autoQueue: false, 	  
				previewsContainer: "#previews",
				clickable: ".fileinput-button",
				paramName: "file", // The name that will be used to transfer the file
				maxFilesize: function() { xysize = $(this).data("dropzone-size_single")/1024; console.log(xysize); return xysize; }, // MB Todo: Aus Attributen auslesen
				acceptedFiles: ".pdf,.zip", // ToDo: Aus Attributen auslesen
				createImageThumbnails: true,
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
					$('[data-dz-errormessage]').css('display', 'block');
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
				file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file); };
				// file.previewElement.appendChild(removeButton);
				$('input[name="upload"]').val(file.name);
			}
		});

		myDropzone.on("totaluploadprogress", function(progress) {
			document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
		  });

		  myDropzone.on("sending", function(file) {
			// Show the total progress bar when upload starts
			document.querySelector("#total-progress").style.opacity = "1";
			// And disable the start button
			file.previewElement.querySelector(".start").setAttribute("disabled", "disabled");
		  });
				
		// Hide the total progress bar when nothing's uploading anymore
		myDropzone.on("queuecomplete", function(progress) {
			document.querySelector("#total-progress").style.opacity = "0";
		});
		
		// Setup the buttons for all transfers
		// The "add files" button doesn't need to be setup because the config
		// `clickable` has already been specified.
		document.querySelector("#actions .start").onclick = function() {
			myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
		};
		document.querySelector("#actions .cancel").onclick = function() {
			myDropzone.removeAllFiles(true);
		};
  
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
		/*
		$('.close-modal').unbind();
		
		$('.close-modal').click(function(e){
			e.preventDefault()
			
			$('.upload-modal').removeClass('active');
			
			return false;
		});
		*/
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