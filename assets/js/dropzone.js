var myDropzone;

Dropzone.autoDiscover = false;

var YFormDropzone = {

	init: function(dzElement)
		{
		this.initDropzone(dzElement);
		this.setActions(dzElement);
	},

		initDropzone: function(dzElement)
		{

			document.querySelector(dzElement);
			var previewNode = document.querySelector(dzElement +" .file-row");
			previewNode.id = [];

			var previewTemplate = previewNode.parentNode.innerHTML;
			previewNode.parentNode.removeChild(previewNode);

			// siehe https://www.dropzonejs.com/bootstrap.html#
			myDropzone = new Dropzone(
				document.querySelector(dzElement +" .upload-container"), // oder document.body für die gesamte Seite
			{
				// https://www.dropzonejs.com/#configuration-options
				url: "index.php?rex-api-call=yform_dropzone&func=upload",
				thumbnailWidth: document.querySelector(dzElement).getAttribute("data-dz-thumbnail-width"),
				thumbnailHeight: document.querySelector(dzElement).getAttribute("data-dz-thumbnail-height"),
				parallelUploads: document.querySelector(dzElement).getAttribute("data-dz-parallel-uploads"),
				previewTemplate: previewTemplate,		
				autoQueue: false, 	  
				acceptedFiles: document.querySelector(".dropzone").getAttribute("data-dz-types"),
				previewsContainer: document.querySelector(dzElement + ' [data-dz-role="previews"]'),
				// clickable: document.querySelector(dzElement +" .upload-container"),
				paramName: "file", // The name that will be used to transfer the file
				maxFilesize: document.querySelector(dzElement).getAttribute("data-dz-max-files"), // Laut Doku nicht maximale Dateigröße, sondern maximale Dateianzahl!
				filesizeBase: 1000,
				acceptedFiles: document.querySelector(dzElement).getAttribute("data-dz-types"),
				createImageThumbnails: true,
				addRemoveLinks: true,
				dictCancelUpload: "dictCancelUpload",
				dictCancelUploadConfirmation: "dictCancelUploadConfirmation",
				dictRemoveFile: "dictRemoveFile",

				// nachsehen, ob bereits Dateien hochgeladen wurden
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

				// Container für Fehlermeldungen
				error: function(file) {
					$(dzElement + ' [data-dz-errormessage]').css('display', 'block');
					myDropzone.removeFile(file);
				}
			}
		);

		
		myDropzone.on("addedfile", function(file) {

			
			// überprüfen, ob Dateianhang erlaubt ist
			var currentExtension = file.name.substring(file.name.lastIndexOf('.') + 1);
			var typesAllowed = document.querySelector(dzElement).getAttribute("data-dz-types");
			var maxFilesize = document.querySelector(dzElement).getAttribute("data-dz-file-size");
			if( file.size >  maxFilesize / 1024 || typesAllowed.split(',').indexOf(currentExtension) ){
				myDropzone.options.error.call(myDropzone, file);
			}
			else {

				var removeButton = Dropzone.createElement('<a class="remove-file">entfernen</a>');
				removeButton.addEventListener("click", function(e) {
					e.preventDefault();
					e.stopPropagation();
					$.post( 'index.php?rex-api-call=yform_dropzone&func=delete', { file: file.name }).done(function( data ) {
						myDropzone.removeFile(file);
					});
				});
				file.previewElement.querySelector(dzElement + ".start").onclick = function() { myDropzone.enqueueFile(file); };
				// file.previewElement.appendChild(removeButton);
				$('input[name="upload"]').val(file.name);
			}
		});

		myDropzone.on("totaluploadprogress", function(progress) {
			 document.querySelector(dzElement + " .progress-bar").style.width = progress + "%";
		  });

		  myDropzone.on("sending", function(file) {
			// Show the total progress bar when upload starts
			document.querySelector(".progress").style.opacity = "1";
			// And disable the start button
			file.previewElement.querySelector(".start").setAttribute("disabled", "disabled");
		  });
				
		// Hide the total progress bar when nothing's uploading anymore
		myDropzone.on("queuecomplete", function(progress) {
			document.querySelector(".progress").style.opacity = "0";
		});
		
		// Setup the buttons for all transfers
		// The "add files" button doesn't need to be setup because the config
		// `clickable` has already been specified.
		/*
		document.querySelector(dzElement + ".actions .start").onclick = function() {
			myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
		};
		
		
		document.querySelector(dzElement + ".actions .cancel").onclick = function() {
			myDropzone.removeAllFiles(true);
		};*/
		
  
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

	$(".dropzone").each(function() {
		YFormDropzone.init("#"+$(this).attr('id'));
	});	

});