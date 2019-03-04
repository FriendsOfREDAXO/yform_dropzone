<?php
$notice = [];
if ($this->getElement('notice') != '') {
    $notice[] = rex_i18n::translate($this->getElement('notice'), false);
}
if (isset($this->dropzone_params['warning_messages'][$this->getId()]) && !$this->dropzone_params['hide_field_warning_messages']) {
    $notice[] = '<span class="text-warning">' . rex_i18n::translate($this->dropzone_params['warning_messages'][$this->getId()], false) . '</span>'; //    var_dump();
}
if (count($notice) > 0) {
    $notice = '<p class="help-block">' . implode('<br />', $notice) . '</p>';
} else {
    $notice = '';
}

$class = $this->getElement('required') ? 'form-is-required ' : '';

$class_group = trim('form-group  ' . $class . $this->getWarningClass());

?>
<!-- Wie Upload-Feld -->
<div class="<?php echo $class_group ?>" id="<?php echo $this->getHTMLId() ?>">
<?php
// Todo: Diese Texte einfügen.
/*		$dropzone_params['size_error_single'] = $this->getElement('size_error_single');
		$dropzone_params['size_all'] = $this->getElement('size_all');
		$dropzone_params['size_error_all'] = $this->getElement('size_error_all');
		$dropzone_params['types_error'] = $this->getElement('types_error');
*/
?>

<!-- Dropzone-Code -->
<!-- HTML heavily inspired by http://blueimp.github.io/jQuery-File-Upload/ -->
<div class="dropzone dropzone-upload" data-dz-id="<?= $unique ?>" data-dropzone-types="<?= $this->getElement('types') ?>">
<input type="hidden" id="<?= $this->getFieldId() ?>" name="<?= $this->getFieldName() ?>" value="<?= $this->getValue() ?>"/>

<div class="table table-striped" class="files" id="previews">

  <div id="template" class="file-row">
    <!-- This is used as the file preview template -->
	
    <div>
        <span class="preview"><img data-dz-thumbnail /></span>
    </div>
    <div>
        <p class="name" data-dz-name></p>
        <strong class="error text-danger" data-dz-errormessage style="display: none;"><?= $this->getElement('label_dropzone_modal_error') ?></strong>
    </div>
    <div>
        <p class="size" data-dz-size></p>
        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
          <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
        </div>
    </div>
    <div>
      <button class="btn btn-primary start">
          <i class="glyphicon glyphicon-upload"></i>
          <span>Start</span>
      </button>
      <button data-dz-remove class="btn btn-warning cancel">
          <i class="glyphicon glyphicon-ban-circle"></i>
          <span>Cancel</span>
      </button>
      <button data-dz-remove class="btn btn-danger delete">
        <i class="glyphicon glyphicon-trash"></i>
        <span>Delete</span>
      </button>
    </div>
  </div>

</div>



<!--

					previewTemplate: '<div class="upload-item"><div class="upload-progress" data-dz-uploadprogress></div><div class="upload-data"><span class="upload-name" data-dz-name></span> (<span class="upload-size" data-dz-size></span>)aaaaaaaaaaaa</div></div>',


    <div class="dropzone dropzone-upload" data-dropzone-id="<?= $unique ?>" >
				<h3><?= $this->getElement('label') ?></h3>
				<div class="upload-container" id="fileupload"  data-dropzone-size_single="<?= $this->getElement('size_single') ?>">
					<div class="upload-cta">
						<p><?= $this->getElement('label_dropzone_file_info') ?></p>
						<button class="btn btn-primary"><?= $this->getElement('label_dropzone_file_button') ?></button>
					</div>
				</div>
				<div class="upload-files container"><p><?= $this->dropzone_params['label_dropzone_dropzone_files']?></p></div>
			</div>
 / Dropzone-Code -->

<!-- Wie Upload-Feld -->
    <?php echo $notice ?>
</div>