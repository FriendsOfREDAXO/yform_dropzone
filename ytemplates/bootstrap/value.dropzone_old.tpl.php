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
    <div class="dropzone dropzone-upload" data-dropzone-id="<?= $unique ?>" >
				<input type="hidden" id="<?= $this->getFieldId() ?>" name="<?= $this->getFieldName() ?>" value="<?= $this->getValue() ?>"/>
				<h3><?= $this->getElement('label') ?></h3>
				<div class="upload-container" id="fileupload" data-dropzone-types="<?= $this->getElement('types') ?>" data-dropzone-size_single="<?= $this->getElement('size_single') ?>">
					<div class="upload-cta">
						<p><?= $this->getElement('label_dropzone_file_info') ?></p>
						<button class="btn btn-primary"><?= $this->getElement('label_dropzone_file_button') ?></button>
					</div>
				</div>
				<div class="upload-files container"><p><?= $this->dropzone_params['label_dropzone_dropzone_files']?></p></div>
				<div class="upload-modal">
					<div class="upload-modal-content">
						<p><?= $this->getElement('label_dropzone_modal_error') ?></p>
						<button class="btn btn-primary close-modal"><?= $this->getElement('label_dropzone_modal_button') ?></button>
					</div>
				</div>
			</div>
<!-- / Dropzone-Code -->

<!-- Wie Upload-Feld -->
    <?php echo $notice ?>
</div>