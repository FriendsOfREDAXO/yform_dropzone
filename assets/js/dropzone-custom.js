var myDropzone;

Dropzone.autoDiscover = false;

var YFormDropzone = {

	init: function(dzId)
		{
		this.initDropzone(dzId);
		this.setActions(dzId);
	},

		initDropzone: function(dzId)
		{

			dzEl = document.querySelector(dzId);
			var previewNode = dzEl.querySelector(".dz-file-preview");
			previewNode.id = "";

			console.log(previewNode);
			var previewTemplate = previewNode.parentNode.innerHTML;
			previewNode.parentNode.removeChild(previewNode);


			// siehe https://www.dropzonejs.com/bootstrap.html#
			myDropzone = new Dropzone(
				dzEl.querySelector(".upload-container"), // oder document.body für die gesamte Seite
			{
				// https://www.dropzonejs.com/#configuration-options
				autoQueue: false, 	  
				autoProcessQueue: true,
				addRemoveLinks: true,
				acceptedFiles: dzEl.dataset.dzTypes,
				clickable: dzEl.querySelector(".fileinput-button"),
				createImageThumbnails: true,
				maxFiles: dzEl.dataset.dzMaxFiles,
				maxFilesize: dzEl.dataset.dzFileSize * 1024,
				parallelUploads: dzEl.dataset.dzParallelUploads,
				paramName: "file",
				previewsContainer: dzEl.querySelector('.dz-files'),
				// previewTemplate: previewTemplate,		
				thumbnailWidth: dzEl.dataset.dzThumbnailWidth,
				thumbnailHeight: dzEl.dataset.dzThumbnailHeight,
				url: "index.php?rex-api-call=yform_dropzone&func=upload",

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
					$(dzId + ' [data-dz-errormessage]').css('display', 'block');
					myDropzone.removeFile(file);
				}  
			}
		);
		myDropzone.on('sending', function(file, xhr, formData){
            formData.append("dz-element", dzEl.dataset.dzId);
        });

		myDropzone.on("addedfile", function(file) {

			// überprüfen, ob Dateianhang erlaubt ist
			var currentExtension = file.name.substring(file.name.lastIndexOf('.')).toLowerCase();
			var typesAllowed = dzEl.dataset.dzTypes;
			var maxFilesize = dzEl.dataset.dzFileSize;
			if( file.size >  maxFilesize * 1024 * 1024 || typesAllowed.split(',').indexOf(currentExtension) ){

				myDropzone.options.error.call(myDropzone, file);
			} else {
				// Wenn erfolgreich: Dateianhang hinzufügen und Entfernen-Link einbauen.

				 file.previewElement.querySelector(".dz-remove").addEventListener("click", function(e) {
					e.preventDefault();
					e.stopPropagation();
					$.post( 'index.php?rex-api-call=yform_dropzone&func=delete', { file: file.name }).done(function( data ) {
						myDropzone.removeFile(file);
					});
				});
				
				console.log(file.previewElement);
			}
		});


		myDropzone.on("totaluploadprogress", function(progress) {
			 document.querySelector(dzId + " .progress-bar").style.width = progress + "%";
		  });

		  myDropzone.on("sending", function(file) {
			// Show the total progress bar when upload starts
			document.querySelector(".progress").style.opacity = "1";
			// And disable the start button
		  });
				
		// Hide the total progress bar when nothing's uploading anymore
		myDropzone.on("queuecomplete", function(progress) {
			dzEl.querySelector(".progress").style.opacity = "0";
		});
		
		// Setup the buttons for all transfers
		document.querySelector(dzId + " .row .start").onclick = function() {
			myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
		};
		
		
		document.querySelector(dzId + " .row .cancel").onclick = function() {
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
	},

	setFile: function(file) {
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