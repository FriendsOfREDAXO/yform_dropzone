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
<div class="dropzone dropzone-upload" id="dz-<?= rand() ?>" data-dz-types="<?= $this->getElement('types') ?>" data-dz-max-files="10" data-dz-file-size="<?= $this->getElement('size_single') ?>" data-dz-thumbnail-width="80" data-dz-thumbnail-height="80" data-dz-parallel-uploads="4">
<h3><?= $this->getElement('label') ?></h3>

<input type="hidden" id="<?= $this->getFieldId() ?>" name="<?= $this->getFieldName() ?>" value="<?= $this->getValue() ?>"/>


				<div class="upload-container">
					<div class="upload-cta">
						<p><?= $this->getElement('label_dropzone_file_info') ?></p>
						<button class="btn btn-primary"><?= $this->getElement('label_dropzone_file_button') ?></button>
					</div>
				</div>

<div class="table table-striped" class="files" data-dz-role="previews">

  <div class="dz-preview dz-file-preview file-row">
    <!-- This is used as the file preview template -->
	
    <div>
        <span class="preview"><img data-dz-thumbnail /></span>
    </div>

	<div class="dz-preview dz-file-preview">
		<div class="dz-details">
			<div class="dz-filename"><span data-dz-name></span></div>
			<div class="dz-size" data-dz-size></div>
			<img data-dz-thumbnail />
		</div>
		<div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
		<div class="dz-success-mark"><span>✔</span></div>
		<div class="dz-error-mark"><span>✘</span></div>
		<div class="dz-error-message"><span data-dz-errormessage></span></div>
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
</div>

    <div class="actions">
      <button type="button" class="btn btn-primary start">
          <i class="glyphicon glyphicon-upload"></i>
          <span>Start</span>
      </button>
      <button type="button" data-dz-remove class="btn btn-warning cancel">
          <i class="glyphicon glyphicon-ban-circle"></i>
          <span>Cancel</span>
      </button>
      <button type="button" data-dz-remove class="btn btn-danger delete">
        <i class="glyphicon glyphicon-trash"></i>
        <span>Delete</span>
      </button>
    </div>
  </div>

</div>
<!-- Wie Upload-Feld -->
    <?php echo $notice ?>
</div>